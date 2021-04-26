$(function () { 
    let messMV = new Vue({
        el: '#app', //vue绑定元素
        data: {
            hot_now_version: '',    //插件当前版本
            hot_new_version: '',    //插件最新版本
            Joe_now_version: '',    //主题当前版本
            Joe_new_version: '',    //主题最新版本
            Joe_versions_mes:'',    //主题版本信息
            hot_box_show: false,    //插件更新弹窗
            hot_new_data:[],        //插件最新版本数据
            hot_list_data:[],       //插件版本列表数据
            activeNames: ['1'],     //折叠面板默认显示第一条
            Joe_update:'',          //主题更新时间
            Joe_uplog:'',           //主题更新日志
            show: true,             //是否显示检测更新
            log: false,             //是否显示更新日志
            znew: false,            //更新按钮样式
            drawer: false,          //右侧抽屉
            Loading: false,         //是否显示懒加载
            disabled:false,         //是否关闭 tooltip 气泡功能
        },
        mounted() {
            this.Joe_now_version = this.getCookie('getJoe');
            this.getCollect();
            this.get_new_vs();
            if(this.isMobile()){
                this.disabled = true;
            }
        },
        methods: {
            // 获取插件新版本
            get_new_vs(){
                axios.get('https://port.kuckji.cn', {
                    params: {
                        appid: 'hot_get_new_version',
                        secret:'z1I4HMCGqAUWmcQl6Du'
                    }
                })
                
                .then((res)=> {
                    this.show_main=true;
                    res = res.data;
                    if(!res.code){
                        if(Number(res.data.vsnum) > this.Version(true)){
                            this.hot_now_version = this.Version(false);
                            this.hot_new_version = res.data.version;
                            this.hot_new_data = this.uni_cn(res.data.uplog);
                            this.hot_box_show = true;
                        }
                    }
                })
            },
            
            // 获取插件版本列表
            get_list_vs(){
                axios.get('https://port.kuckji.cn', {
                    params: {
                        appid: 'hot_get_list_version',
                        secret:'z1I4HMCGqAUWmcQl6Du'
                    }
                })
                
                .then((res)=> {
                    res = res.data;
                    console.log(res);
                    // if(!res.data.code){
                    //     this.hot_box_show = true;
                    // }else{
                    //     this.Tips(res.data.mes,'success');
                    // }
                })
            },
            
            // 获取主题新版本
            getCollect(){
                axios.get('https://as.js.cn/qqshoucang.php/?key=18e958d8c7fa5d435844f95c9f254fca')
                .then((res)=> {
                    res = res.data;
                    if(res.success){
                        // 隐藏加载项
                        this.Joe_new_version =  res.title;
                        this.Joe_update = res.update;
                        this.Joe_uplog = res.content;
                        this.show = false;
                        this.log = true;
                        // 判断是否有新版本
                        if(parseInt(res.title.replace(/\./g,''))>parseInt(this.getCookie('getJoe').replace(/\./g,''))){
                            this.Joe_versions_mes = "发现新版本 "+ this.Joe_new_version;
                            this.znew = true;
                        }else{
                            this.Joe_versions_mes = "已是最新版本";
                        }
                    }else{
                        this.Tips('获取主题更新失败 Unknown Error!','error');
                    }
                })
            },
            
            // Joe 更新请求
            Joe_updata(){
                this.Loading = true;
                axios.get('../usr/plugins/HotUpdate/assets/update.php')
                .then((res)=> {
                    res = res.data;
                    if(!res.code){
                        let exp = new Date(); 
                        exp.setTime(exp.getTime() + 60*10*10);
                        document.cookie = "getJoe=" + escape(this.Joe_new_version) + ";expires=" + exp.toGMTString();
                        this.Joe_now_version = this.Joe_new_version;
                        this.Loading = false;
                        this.znew = false;
                        this.getCollect();
                    }else{
                        this.Loading = false;
                        this.Tips('升级失败 Error code：!' + res.code,'error');
                    }
                })
            },
            
            // Hot 更新请求
            Hot_updata(){
                this.Loading = true;
                axios.get('../usr/plugins/HotUpdate/assets/update.php')
                .then((res)=> {
                    res = res.data;
                    if(!res.code){
                        let exp = new Date(); 
                        exp.setTime(exp.getTime() + 60*10*10);
                        document.cookie = "getJoe=" + escape(this.Joe_new_version) + ";expires=" + exp.toGMTString();
                        this.Joe_now_version = this.Joe_new_version;
                        this.Loading = false;
                        this.znew = false;
                        this.getCollect();
                    }else{
                        this.Loading = false;
                        this.Tips('升级失败 Error code：!' + res.code,'error');
                    }
                })
            },
            
            // ASCII 转中文
            uni_cn(str){
                let cn=[];
                str.forEach((value, key, ite)=> {
                    let v = value.title.split('u').join("\\u");
                    str = v.replace(/\\/g, "%");
                    let obj = {};
                    obj.term = unescape(str);
                    cn.push(obj);
                });
                    return cn;
            },
            
            // 本地版本号
            Version(t){
                if(t){
                    return 102;
                }else{
                    return '1.0.2';
                }
            },
            
            // 获取指定名称的cookie
            getCookie(name){
                var strcookie = document.cookie;
                var arrcookie = strcookie.split("; ");
                for ( var i = 0; i < arrcookie.length; i++) {
                    var arr = arrcookie[i].split("=");
                    if (arr[0] == name){
                        return arr[1];
                    }
                }
                return false;
            },
            
            // 关闭更新确认框
            handleClose(done) {
                this.$confirm('关闭更新程序将不为您更新插件，确认关闭？')
                    .then(_ => {
                        done();
                    })
                    .catch(_ => {});
            },
            
            // img错误检测
            errorHandler() {
                return true
            },
            
            // 提示弹窗
            Tips(mes,type) {
                this.$message({
                  message: mes,
                  type: type
                });
            },
            
            // 打开一个连接
            OpenUrl(url,type){
                if(type){window.open(url)}else{window.location.href=url}
            },
            
            // 判断是否为移动端
            isMobile() {
                let userAgentInfo = navigator.userAgent;
                let mobileAgents = [ "Android", "iPhone", "SymbianOS", "Windows Phone", "iPad","iPod"];
                let mobile_flag = false;
            
                //根据userAgent判断是否是手机
                for (let v = 0; v < mobileAgents.length; v++) {
                    if (userAgentInfo.indexOf(mobileAgents[v]) > 0) {
                        mobile_flag = true;
                        return mobile_flag;
                    }
                }
            
                let screen_width = window.screen.width;
                let screen_height = window.screen.height;    
            
                 //根据屏幕分辨率判断是否是手机
                if(screen_width < 500 && screen_height < 800){
                    mobile_flag = true;
                }
                return mobile_flag;
            },
        }
    });
});
