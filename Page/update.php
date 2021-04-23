<?php
require_once __DIR__ . '/../assets/Depend.php';
/**
 * 升级的方法
 * 
 * @access public
 * @return void
 */
function update(){
    $down_url = 'https://codeload.github.com/HaoOuBa/Joe/zip/master';
    // $save_path = 'usr/plugins/HotUpdate/down/Joe.zip';
    $save_path = '../down/Joe.zip';
    //下载文件
    if (downloadFile($down_url,$save_path)) {
        $zip = new Unzip();
        // 解压文件
        if($zip->unzip('../down/Joe.zip','../down/',false)){
            // 将文件更名为Joe
	        if(rename('../down/Joe-master','../down/Joe')){
	            //复制更新
	            recurse_copy('../down/Joe','../../../themes/Joe');
	            deldir('../down');
	            return 0;
	        }
	        deldir('../down');
            return 10054;
        }
        deldir('../down');
        return 10055;
    }
    deldir('../down');
    return 10056;
}

$ret = update();
if(!$ret){
    echo json_encode(
        array(
            'mes'=>$ret,
            'cont'=>'升级成功'
        )
    );
}else{
    echo json_encode(
        array(
            'mes'=>$ret,
            'cont'=>'升级失败'
        )
    );
}

?>