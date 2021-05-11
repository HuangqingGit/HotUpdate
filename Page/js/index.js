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
            log_activeNames: 0,     //折叠面板默认显示第一条
            up_activeNames: ['1'],  //折叠面板默认显示第一条
            Joe_update:'',          //主题更新时间
            Loading_text:'',        //loading加载时的文字
            Joe_uplog:'',           //主题更新日志
            hot_show:false,         //版本列表是否显示
            show: true,             //是否显示检测更新
            log: false,             //是否显示更新日志
            znew: false,            //更新按钮样式
            drawer: false,          //右侧抽屉
            Loading: false,         //是否显示懒加载
            disabled:false,         //是否关闭 tooltip 气泡功能
        },
        mounted() {
            this.Joe_now_version = this.getCookie('Joe_now_version');
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
                this.drawer = true;
                axios.get('https://port.kuckji.cn', {
                    params: {
                        appid: 'hot_get_list_version',
                        secret:'z1I4HMCGqAUWmcQl6Du'
                    }
                })
                
                .then((res)=> {
                    res = res.data;
                    if(!res.code){
                        this.hot_show = true;
                        this.hot_list_data = res.data;
                    }
                })
            },
            
            // 获取主题新版本
            getCollect(){
                axios.get('https://78.al/api.php?type=collect&key=18e958d8c7fa5d435844f95c9f254fca')
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
                        if(parseInt(res.title.replace(/\./g,''))>parseInt(this.getCookie('Joe_now_version').replace(/\./g,''))){
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
                this.Loading_text = '正在更新 Joe 请勿刷新页面';
                this.Loading = true;
                axios.get('../usr/plugins/HotUpdate/assets/Joe_update.php')
                .then((res)=> {
                    res = res.data;
                    if(!res.code){
                        let exp = new Date(); 
                        exp.setTime(exp.getTime() + 60*10*10);
                        document.cookie = "Joe_now_version=" + escape(this.Joe_new_version) + ";expires=" + exp.toGMTString();
                        this.Joe_now_version = this.Joe_new_version;
                        this.Loading = false;
                        this.znew = false;
                        this.Notice('成功','<h4 style="margin: 3px 0">Joe主题更新成功</h4><p>(请按Ctrl + F5刷新缓存)</p>','success');
                        this.getCollect();
                    }else{
                        this.Loading = false;
                        this.Tips('升级失败 Error code：!' + res.code,'error');
                    }
                })
            },
            
            // Hot 更新请求
            Hot_updata(){
                this.Loading_text = '正在更新 Hotupdate 请勿刷新页面';
                this.Loading = true;
                axios.get('../usr/plugins/HotUpdate/assets/Hot_update.php')
                .then((res)=> {
                    res = res.data;
                    if(!res.code){
                        this.up_record();
                        this.hot_now_version = this.hot_new_version;
                        this.Loading = false;
                        this.Notice('成功','<h4 style="margin: 3px 0">Hotupdate更新成功</h4><p>(请按Ctrl + F5刷新缓存)</p>','success');
                        this.hot_box_show = false;
                    }else{
                        this.Loading = false;
                        this.Tips('升级失败 Error code：!' + res.code,'error');
                    }
                })
            },
            
            // 热更新记录
            up_record(){
                axios.get('https://port.kuckji.cn', {
                    params: {
                        appid: 'hot_push_log',
                        secret:'z1I4HMCGqAUWmcQl6Du',
                        domain_name:this.getCookie('V_domain_name')
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
            
            // 时间戳解析
            getLocalTime(ns) {  
                let now = new Date(ns*1000);
                let year=now.getFullYear();                                                     //取得4位数的年份
                let month=now.getMonth()+1;                                                     //取得日期中的月份，其中0表示1月，11表示12月
                let date=now.getDate() < 10 ? "0" + now.getDate() : now.getDate();              //返回日期月份中的天数（1到31）
                let hour=now.getHours() < 10 ? "0" + now.getHours() : now.getHours();           //返回日期中的小时数（0到23）
                let minute=now.getMinutes() < 10 ? "0" + now.getMinutes() : now.getMinutes();   //返回日期中的分钟数（0到59）
                let second=now.getSeconds() < 10 ? "0" + now.getSeconds() : now.getSeconds();   //返回日期中的秒数（0到59）
                month = month < 10 ? "0" + month : month;
                return year + "-" + month + "-" + date + " " + hour + ":" + minute + ":" + second; 
            },
            
            // 本地版本号
            Version(t){
                if(t){
                    return 105;
                }else{
                    return '1.0.5';
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
            
            // 带有icon 的通知
            Notice(title,mes,type) {
                this.$notify({
                    title: title,
                    dangerouslyUseHTMLString: true,
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
