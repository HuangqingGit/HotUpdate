document.addEventListener('DOMContentLoaded', () => {
    let messMV = new Vue({
        el: '#UP_main', //vue绑定元素
        data: {
            vb:'',
            result:'',
            versions:'',
            show: true,
            znew: false,
            log: false,
            vbst: false,
            popup: false
        },
        mounted() {
            this.getCollect($('.now_version').html());
        },
        methods: {
            getCollect(now_vb){
                var that = this;
                $.ajax({
                url: "https://as.js.cn/qqshoucang.php",
                type: 'get',
                dateType: 'json',
                data: {
                    key: '18e958d8c7fa5d435844f95c9f254fca'
                },
                    success(res){
                        if(res.success){
                            // 隐藏加载项
                            that.vb =  res.title;
                            that.result = res;
                            that.show = false;
                            that.log = true;
                            that.vbst = true;
                            $('.log_cont').html(res.content);
                            // 判断是否有新版本
                            if(parseInt(res.title.replace(/\./g,''))>parseInt(now_vb.replace(/\./g,''))){
                                that.versions = "发现新版本 "+ res.title;
                                that.znew = true;
                            }else{
                                that.versions = "已是最新版本"
                                $('.now_version').html(that.vb);
                            }
                        }else{
                            return "错误";
                        }
                    }
                })
            },
            updata(){
                var that = this;
                that.vbst = false;
                that.popup = true;
                $.ajax({
                url: "../usr/plugins/HotUpdate/Page/update.php",
                type: 'POST',
                dateType: 'json',
                data: {
                    key: '18e958d8c7fa5d435844f95c9f254fca'
                },
                    success(ret){
                        if(!ret.mes){
                            $('.log_cont').html('');
                            that.log = false;
                            that.show = true;
                            that.popup = false;
                            that.znew = false;
                            that.getCollect(that.vb);
                        }
                    }
                })
            }
        }
    });
});
