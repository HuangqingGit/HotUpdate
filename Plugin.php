<link rel="stylesheet" href="//at.alicdn.com/t/font_2514219_why3sgcs04.css">
<link rel="stylesheet" href="<?php echo Helper::options()->rootUrl ?>/usr/plugins/HotUpdate/assets/css/element.min.css">
<link rel="stylesheet" href="<?php echo Helper::options()->rootUrl ?>/usr/plugins/HotUpdate/assets/css/el.min.css">
<link rel="stylesheet" href="<?php echo Helper::options()->rootUrl ?>/usr/plugins/HotUpdate/assets/css/Plugin.setting.min.css">
<link rel="stylesheet" href="<?php echo Helper::options()->rootUrl ?>/usr/plugins/HotUpdate/assets/css/hot.main.min.css">
<script type="text/javascript" src="<?php echo Helper::options()->rootUrl ?>/usr/plugins/HotUpdate/assets/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="<?php echo Helper::options()->rootUrl ?>/usr/plugins/HotUpdate/assets/js/vue.min.js"></script>
<script type="text/javascript" src="<?php echo Helper::options()->rootUrl ?>/usr/plugins/HotUpdate/assets/js/axios.min.js"></script>
<script type="text/javascript" src="<?php echo Helper::options()->rootUrl ?>/usr/plugins/HotUpdate/assets/js/element.min.js"></script>

<body>
    <div id="hot" v-cloak>
        <el-container id="hot_container_main">
            <!--header start-->
            <el-header id="hot_header">
                <div class="plugin_return" @click="OpenUrl('extending.php?panel=HotUpdate/Page/InspectPage.php',false)" v-show="ret"><i class="iconfont ali-icon-fanhui2" style="margin-right:10px"></i> 返回</div>
                <div class="plugin_title">插件设置</div>
                <div class="panel_head">
                    <div class="logo_box">
                        <el-avatar class="hot_logo" size="number" src="/usr/plugins/HotUpdate/Page/img/hot.png"></el-avatar>
                        <span class="logo_t">HotUpdate</span>
                    </div>
                </div>
            </el-header>
            <!--heder end-->

            <el-container id="hot_container" style="height:0px">
                <!--menu start-->
                <el-aside id="hot_aside" :width='menu_fold ? "64px" : "200px"'>
                    <el-menu id="menus" :width='menu_fold ? "64px" : "200px"' :default-active="def_active" :collapse="menu_fold" class="el-menu-vertical-demo" @open="handleOpen" unique-opened=true text-color="var(--main)">
                        <span class="fold_box">
                            <div class="menu_fold iconfont" :class="menu_fold ? 'ali-icon-zhedie2':'ali-icon-shouqi'" @click="menu_fold=!menu_fold"></div>
                        </span>
                        <el-submenu v-for="(item, index) in menus" :key="index" v-if="item.type=='two_menu'" :index="item.class">
                            <template slot="title">
                                <i class="iconfont itemicon" :class="item.icon" :style="`color:${item.color}`"></i>
                                <span slot="title">{{item.title}}</span>
                            </template>
                            <el-menu-item v-for="(list, i) in item.menus" :index="`${item.class}-${i}`" @click="anchor(list.name,item.class)">{{list.title}}</el-menu-item>
                        </el-submenu>

                        <el-menu-item v-for="(item, index) in menus" @click="form_other_click(item.class)" :key="index" v-if="item.type=='one_menu'" :index="item.class">
                            <i class="iconfont itemicon" :class="item.icon" :style="`color:${item.color}`"></i>
                            <span slot="title">{{item.title}}</span>
                        </el-menu-item>
                    </el-menu>
                </el-aside>
                <!--menu end-->

                <!--Main start-->
                <el-main id="hot_main" class="hot_main">
                    <div class="form_other About">
                        其他内容1
                    </div>
                </el-main>
                <!--Main end-->
            </el-container>

            <!--Footer start-->
            <el-footer id="hot_footer" height="35px">
                <div class="hot_foot_left"><span class="dq_text">当前版本</span>V {{config.Version}}</div>
                <div class="hot_foot_core">©<span @click="OpenUrl('https://kuckji.cn',true)">酷创空间</span></div>
                <div class="hot_foot_right">
                    <el-button size="mini" @click="hot_Submit_form" type="primary" icon="iconfont ali-icon-baocun1" title="使用Ctrl+S快速保存"> 保存设置</el-button>
                </div>
            </el-footer>
            <!--Footer end-->
        </el-container>
    </div>
</body>
<script type="text/javascript" src="<?php echo Helper::options()->rootUrl ?>/usr/plugins/HotUpdate/assets/js/Plugin.setting.min.js"></script>

<?php
require_once __DIR__ . '/assets/depend.php';
/**
 * HotUpdate 是基于Joe主题的一款热更新插件
 * 
 * @package HotUpdate
 * @author 黄小嘻
 * @link https://www.kuckji.cn
 * @version 1.0.6
 */
class HotUpdate_Plugin implements Typecho_Plugin_Interface
{
    public static $panel = 'HotUpdate/Page/InspectPage.php';
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * subscriber
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Helper::addPanel(1, static::$panel, _t('Joe版本管理'), _t('Joe在线升级'), 'subscriber');
        // HotUpdate_Plugin::update();
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        Helper::removePanel(1, static::$panel);
    }

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $json = get_forms();
        if (count($json)) {
            foreach ($json as $key => $value) {
                $cen = $value['content'];
                switch ($value['type']) {
                    case "Text":    //input输入框
                        $li_form = new Typecho_Widget_Helper_Form_Element_Text($value['name'], $cen['array'], $cen['value'], $cen['title'], $cen['explain']);
                        break;
                    case "Password":    //input输入框
                        $li_form = new Typecho_Widget_Helper_Form_Element_Password($value['name'], $cen['array'], $cen['value'], $cen['title'], $cen['explain']);
                        break;
                    case "Radio":   //Radio单选框
                        $li_form = new Typecho_Widget_Helper_Form_Element_Radio($value['name'], $cen['array'], $cen['value'], $cen['title'], $cen['explain']);
                        break;
                    case "Select":  //Select下拉选择框
                        $li_form = new Typecho_Widget_Helper_Form_Element_Select($value['name'], $cen['array'], $cen['value'], $cen['title'], $cen['explain']);
                        break;
                    case "Checkbox":    //Checkbox复选框
                        $li_form = new Typecho_Widget_Helper_Form_Element_Checkbox($value['name'], $cen['array'], $cen['value'], $cen['title'], $cen['explain']);
                        break;
                    case "Textarea":    //Textarea输入框
                        $li_form = new Typecho_Widget_Helper_Form_Element_Textarea($value['name'], $cen['array'], $cen['value'], $cen['title'], $cen['explain']);
                }
                $li_form->setAttribute('class', 'hot_main_ul ' . $value['class']);
                $li_form->setAttribute('id', 'ul_' . $value['class'] . '_' . $value['name']);     //添加锚点
                $form->addInput($li_form);
            }
        }
    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function render()
    {
    }

    /**
     * 前端页面显示方法
     * 
     * @access public
     * @return void
     */
    public static function show()
    {
    }
}
