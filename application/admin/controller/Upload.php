<?php
// +----------------------------------------------------------------------
// | ChenJunDong
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022  All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: ChenJunDong <geekxz@aliyun.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;
use app\admin\builder\ListBuilder;
use app\admin\builder\FormBuilder;

class Upload extends Base
{
    public function index()
    {
		 // 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('file');
		// 移动到框架应用根目录/public/uploads/ 目录下
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/geekxz/article');
		if($info){
			// 成功上传后 获取上传信息
			// 输出 jpg
			// 输出 42a79759f284b767dfcb2a0197904287.jpg
			$data['path'] =  '/public/uploads/geekxz/article/'.$info->getSaveName();		
			
			return $data['path'];
		
		}else{
			// 上传失败获取错误信息
			echo $file->getError();
		}
    }	

    /**
     * wangEditor编辑器本地上传图片
     * 注意: 一定是echo
     */
    public function doUploadPic()
    {
        $file = request()->file('FileName');
        $info = $file->move(ROOT_PATH . DS . 'uploads/article');
        if ($info) {
            $path = '/uploads/article/'.$info->getSaveName();
            echo str_replace("\\", "/", $path);
        }
    }

    /**
     * wangEditor编辑器本地上传图片
     * 注意: editor.md期望你上传图片的服务返回如下json格式的内容
	 * 	{
	 * 		success : 0 | 1, //0表示上传失败;1表示上传成功
	 * 		message : "提示的信息",
	 * 		url     : "图片地址" //上传成功时才返回
	 * 	}
     */
    public function articleUploadImg(){

        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('editormd-image-file');  
         // dump($file);die;
        $info = $file->move(ROOT_PATH . 'uploads/article');
        if(!$info) {// 上传错误提示错误信息  
            $this->error($upload->getError());            
        }else{// 上传成功 获取上传文件信息  
            $data['url'] =  '/uploads/article/'.$info->getSaveName();

            $data['success'] = 1;
            $data['message'] = '上传成功';
            return json_encode($data);
        }  
    }
	
}
