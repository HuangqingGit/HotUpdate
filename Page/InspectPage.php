<?php
include_once 'common.php';
include 'header.php';
include 'menu.php';
require_once __DIR__ . '/../assets/depend.php';
setcookie('Joe_now_version', _getVersion(), time() + 10 * 60, '/admin', '');
setcookie('V_domain_name', get_host(), time() + 10 * 60, '/admin', '');
?>

<link rel="stylesheet" href="<?php $options->pluginUrl('HotUpdate/Page/css/element.min.css'); ?>">
<link rel="stylesheet" href="https://at.alicdn.com/t/font_2514219_dwgo6pu3zvb.css">
<link rel="stylesheet" href="<?php $options->pluginUrl('HotUpdate/Page/css/index.css'); ?>">
<script src="<?php $options->pluginUrl('HotUpdate/Page/js/vue.min.js'); ?>"></script>
<script src="<?php $options->pluginUrl('HotUpdate/Page/js/axios.min.js'); ?>"></script>
<script src="<?php $options->pluginUrl('HotUpdate/Page/js/element.min.js'); ?>"></script>

<body>
    <div class="main">
        <div class="body container">
            <div class="row typecho-page-main" role="form">
                <div id="app">
                    <div id="hotup" v-loading.fullscreen.lock="Loading" :element-loading-text="Loading_text" type="primary" element-loading-background="rgba(255,255,255,0.6)">
                        <el-dialog class="dialog" title="插件更新通知" :visible.sync="hot_box_show" :before-close="handleClose">
                            <div class="demo-type">
                                <el-avatar class="Hot_logo" :size="60" src="<?php $options->pluginUrl('HotUpdate/Page/img/hot.png'); ?>" @error="errorHandler">
                                    <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png" />
                                </el-avatar>
                            </div>
                            <div class="Hottitle">HotUpdate</div>
                            <div class="el_upversion">版本 {{hot_now_version}} -> {{hot_new_version}}</div>
                            <div class="Hotlog">
                                <span class="cont_title">更新内容：</span>
                                <div class="cont_msg">
                                    <el-collapse id="new_log" v-model="up_activeNames">
                                        <el-collapse-item :title="hot_new_version" name="1">
                                            <div class="el_upmes">
                                                <div v-for="(newmes,index) in hot_new_data" :key="index" class="span_a">
                                                    <span>{{index + 1}}.</span>
                                                    <span>{{newmes.term}}</span>
                                                </div>
                                            </div>
                                        </el-collapse-item>
                                    </el-collapse>
                                </div>
                            </div>
                            <span slot="footer" class="dialog-footer">
                                <el-button @click="hot_box_show = false">暂不更新</el-button>
                                <el-button type="primary" @click="Hot_updata()" :loading="Loading">立即更新</el-button>
                            </span>
                        </el-dialog>
                        <el-drawer :visible.sync="drawer" direction="rtl">
                            <div class="drawer_title" slot="title">
                                <el-avatar class="Hot_logo" :size="50" src="<?php $options->pluginUrl('HotUpdate/Page/img/hot.png'); ?>" @error="errorHandler">
                                    <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png" />
                                </el-avatar>
                                <span class="p1">Hot 更新日志</span>
                                <span class="p2">V {{Version(false)}}</span>
                            </div>
                            <div v-loading="!hot_show" element-loading-text="正在加载" style="height:100%">
                                <el-collapse id="list_log" v-model="log_activeNames" v-show="hot_show" accordion>
                                    <el-collapse-item v-for="(item,index) in hot_list_data" :key="index" :name='index'>
                                        <div class="hot_log_title" slot="title">
                                            <span><i class="iconfont el-icon-gengxinshijian"></i> {{getLocalTime(item.uptime)}}</span>
                                            <span>V{{item.version}}</span>
                                        </div>
                                        <div class="hot_log_mes" v-for="(mes,index) in uni_cn(item.uplog)" :key="index">
                                            <span>{{index + 1}}.</span>
                                            <span>{{mes.term}}</span>
                                        </div>
                                    </el-collapse-item>
                                </el-collapse>
                            </div>
                        </el-drawer>
                    </div>
                    <div class="UP_title_box">
                        <el-avatar class="Joe-logo" :size="60" src="<?php $options->pluginUrl('HotUpdate/Page/img/Joe.png'); ?>" @error="errorHandler">
                            <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png" />
                        </el-avatar>
                        <span class="title">Joe</span>
                        <span class="joe_upversion">当前版本：{{Joe_now_version}}</span>
                        <span class="subhead loading" v-if="show">正在获取更新 · · ·</span>
                        <span class="subhead" v-if="log" :style="{'color':znew ? '#ff461f':'#00bc12'}">{{Joe_versions_mes}}<span @click="Joe_updata()" v-if="znew" class="update_but" title="更新Joe主题">立即更新</span></span>
                    </div>
                    <div class="UP_Update_log">
                        <div class="log_title">
                            <span class="loading" v-if="show">正在获取更新日志 · · ·</span>
                            <span class="subhead" v-show="log">Joe更新日志：<span class="now_version"></span></span>
                            <span class="updateTime" v-if="log">更新时间：{{Joe_update}}</span>
                        </div>
                        <div class="log_cont" v-html="Joe_uplog"></div>
                    </div>
                    <div id="row">
                        <el-tooltip :disabled="disabled" class="item" effect="dark" content="Hot 更新日志" placement="left">
                            <el-button type="info" icon="iconfont el-icon-rizhi" circle @click="get_list_vs()"></el-button>
                        </el-tooltip>
                        <el-tooltip :disabled="disabled" class="item" effect="dark" content="插件设置" placement="left">
                            <el-button type="primary" icon="iconfont el-icon-tubiao01" circle @click="OpenUrl('options-plugin.php?config=HotUpdate',false)"></el-button>
                        </el-tooltip>
                        <el-tooltip :disabled="disabled" class="item" effect="dark" content="联系作者" placement="left">
                            <el-button type="success" icon="iconfont el-icon-QQ" circle @click="OpenUrl('http://wpa.qq.com/msgrd?v=3&uin=1666385076&site=qq&menu=yes',true)"></el-button>
                        </el-tooltip>
                        <el-tooltip :disabled="disabled" class="item" effect="dark" content="去点个Star" placement="left">
                            <el-button type="warning" icon="iconfont el-icon-shoucang1" circle @click="OpenUrl('https://github.com/HuangqingGit/HotUpdate',true)"></el-button>
                        </el-tooltip>
                        <el-tooltip :disabled="disabled" class="item" effect="dark" content="开发者的博客" placement="left">
                            <el-button type="info" icon="iconfont el-icon-wangluo1" circle @click="OpenUrl('https://www.kuckji.cn',true)"></el-button>
                        </el-tooltip>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php include 'common-js.php'; ?>
<?php include 'footer.php'; ?>
<script src="<?php $options->pluginUrl('HotUpdate/Page/js/index.js'); ?>"></script>