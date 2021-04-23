<?php
include_once 'common.php';
include 'header.php';
include 'menu.php';
require_once __DIR__ . '/depend.php';
?>
<script src='https://js.cxwt.xyz/vue.min.js'></script>
<link rel="stylesheet" href="<?php $options->pluginUrl('HotUpdate/Page/css/index.css')?>">
<div id="UP_main">
    <div class="UP_title_box">
        <span class="title">Joe主题—更新插件</span>
        <span class="subhead loading" v-if="show">正在检测更新 · · ·</span>
        <span class="subhead" v-if="vbst" :style="{'color':znew ? '#ff461f':'#00bc12'}">{{versions}}<span @click="updata()" v-if="znew" class="update_but" title="更新Joe主题" >立即更新</span></span>
        <span class="shade" v-if="popup">
            正在更新Joe 请勿刷新页面
        </span>
    </div>
    <div class="UP_Update_log">
        <div class="log_title">
            <span class="loading" v-if="show">正在获取更新日志 · · ·</span>
            <span class="subhead" v-show="log">Joe更新日志：<span class="now_version"><?php echo _getVersion(); ?></span></span>
            <span class="updateTime" v-if="log">更新时间：{{result.update}}</span>
        </div>
        <div class="log_cont"></div>
    </div>
</div>
<script src="<?php $options->pluginUrl('HotUpdate/Page/js/index.js'); ?>"></script>