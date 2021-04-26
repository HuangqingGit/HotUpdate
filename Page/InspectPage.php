<?php
include_once 'common.php';
include 'header.php';
include 'menu.php';
require_once __DIR__ . '/../assets/depend.php';
setcookie('getJoe', _getVersion(), time() + 10 * 60, '/admin', '');
?>

<link rel="stylesheet" href="<?php $options->pluginUrl('HotUpdate/Page/css/element.min.css'); ?>">
<link rel="stylesheet" href="https://at.alicdn.com/t/font_2514219_xib1twmu64m.css">
<link rel="stylesheet" href="<?php $options->pluginUrl('HotUpdate/Page/css/index.css'); ?>">
<script src="<?php $options->pluginUrl('HotUpdate/Page/js/vue.min.js'); ?>"></script>
<script src="<?php $options->pluginUrl('HotUpdate/Page/js/axios.min.js'); ?>"></script>
<script src="<?php $options->pluginUrl('HotUpdate/Page/js/element.min.js'); ?>"></script>

<body>
    <div id="app">
        <div id="hotup" v-loading.fullscreen.lock="Loading" type="primary">
            <el-dialog class="dialog" title="插件更新通知" :visible.sync="hot_box_show" :before-close="handleClose">
                <div class="demo-type">
                    <el-avatar :size="60" src="<?php $options->pluginUrl('HotUpdate/Page/img/hot.png'); ?>" @error="errorHandler">
                        <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png" />
                    </el-avatar>
                </div>
                <div class="Hottitle">HotUpdate</div>
                <div class="el_upversion">版本 {{hot_now_version}} -> {{hot_new_version}}</div>
                <div class="Hotlog">
                    <span class="cont_title">更新内容：</span>
                    <div class="cont_msg">
                        <el-collapse v-model="activeNames" accordion>
                            <el-collapse-item :title="hot_new_version" name="1">
                                <div class="el_upmes">
                                    <div v-for="(list,index) in hot_new_data" :key="index" class="span_a">
                                        <span>{{index + 1}}.</span>
                                        <span>{{list.term}}</span>
                                    </div>
                                </div>
                            </el-collapse-item>
                        </el-collapse>
                    </div>
                </div>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="hot_box_show = false">暂不更新</el-button>
                    <el-button type="primary" @click="hot_box_show = false">立即更新</el-button>
                </span>
            </el-dialog>
            <el-drawer title="Hot 更新日志" :visible.sync="drawer" direction="rtl">
                <el-collapse v-model="activeNames">
                    <el-collapse-item title="一致性 Consistency" name="1">
                        <div>与现实生活一致：与现实生活的流程、逻辑保持一致，遵循用户习惯的语言和概念；</div>
                    </el-collapse-item>
                </el-collapse>
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
                <el-button type="info" icon="iconfont el-icon-rizhi" circle @click="drawer = true"></el-button>
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
        </div>
    </div>
</body>

<script src="<?php $options->pluginUrl('HotUpdate/Page/js/index.js'); ?>"></script>