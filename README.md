# HotUpdate
## HotUpdate 是基于Joe主题的一款热更新插件 
## 插件由[黄小嘻](https://www.kuckji.cn)独立开发

# json表说明

## forms.json 表单数据格式说明

    "type": "表类型：Text|Password|Radio|Select|Checkbox|Textarea   首字母必须大写",
    "name": "给表定义的唯一 ID 不可重复",
    "class": "属于分类须与 munus.json 表中的 class 一致",
    "content": {
        "title": "给改表定义一个 title 将展示在表上方",
        "array": "Radio|Select|Checkbox 会用到这个，用于展示多选选择按钮",
        "value": "默认内容",
        "explain": "表单提示"
    }


## menus.json 表单数据格式说明

    "type":"one_menu|two_menu 目前就两种一及和二级",
    "class":"分类定义 须与 forms.json 表中的 class 一致",
    "title":"目录名称",
    "color":"目录icon字体图标颜色",
    "icon":"字体图标代码",
    "menus":[           二级目录有效
        {"title":"二级目录的title","name":"名称标识须与 munus.json 表中的 name 一致"},
        {"title":"二级目录的title","name":"名称标识须与 munus.json 表中的 name 一致"},
        ....
    ]