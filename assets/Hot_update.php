<?php
require_once __DIR__ . '/depend.php';
/**
 * 升级的方法
 * 
 * @access public
 * @return void
 */
function update(){
    $down_url = 'https://codeload.github.com/HuangqingGit/HotUpdate/zip/refs/heads/master';
    $save_path = '../down/HotUpdate.zip';
    $pkunzip_path = '../down/';
    
    if(file_exists($pkunzip_path)){     //如果文件夹存在，则先删除
        deldir('../down');
    }
    
    //下载文件
    if (downloadFile($down_url,$save_path)) {
        if (empty($pkunzip_path) || empty($save_path)) {
            deldir('../down');
	        return 10053;
        }
        $zip = new ZipArchive();
        // 解压文件
        if ($zip->open($save_path) === true) {
            $zip->extractTo($pkunzip_path);
            $zip->close();
            // 将文件更名为Joe
	        if(rename('../down/HotUpdate-master','../down/HotUpdate')){
	            //复制更新
	            recurse_copy('../down/HotUpdate','../../HotUpdate');
	            return 0;
	        }
            return 10054;
        }
        return 10055;
    }
    return 10056;
    
}

$ret = update();
deldir('../down');  //删除缓存目录
if(!$ret){
    echo json_encode(
        array(
            'code'=>$ret,
            'mes'=>'升级成功'
        )
    );
}else{
    echo json_encode(
        array(
            'code'=>$ret,
            'mes'=>'升级失败'
        )
    );
}

?>