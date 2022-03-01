<?php
/**
 * Created by 陈东东
 * Author: 陈东东  <1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2021/7/2
 * Time: 10:34
 */
namespace app\admin\controller;
use think\Request;

class Images extends Base
{
	// 图片上传uploadImg
    public function uploadImg(){
        // 获取表单上传文件 例如上传了001.jpg
        // $current_cate_id = $_POST['current_cate_id'];
        $file = request()->file('image'); 
        $info = $file->rule('uniqid')->move(ROOT_PATH . 'uploads/images/');
        if(!$info) {// 上传错误提示错误信息  
            $this->error($upload->getError());            
        }else{// 上传成功 获取上传文件信息  
            $data['path'] =  '/uploads/images/'.$info->getSaveName();
            return $data;
        }  
    }
    /**
     * 判断文件是否存在后，删除
     * @param $path
     * @return bool
     * @author byron sampson <xiaobo.sun@qq.com>
     * @return boolean
     */
    private function unlink($path)
    {
        return is_file($path) && unlink($path);
    }
    // 删除图片
    public function delMainImg(){
        $path = input('param.img');
        // 查询id与路径
        $path = ".".$path;
        $this ->unlink($path);
        if (true) {
            return geekxza('删除成功');
        } else {
            return geekxzb('删除失败');
        }
    }

    public function editormdPic(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('editormd-image-file');  
         // dump($file);die;
        $info = $file->rule('uniqid')->move(ROOT_PATH . DS . 'uploads/article');
        if(!$info) {// 上传错误提示错误信息  
            $this->error($upload->getError());            
        }else{// 上传成功 获取上传文件信息  
            $data['path'] =  '/uploads/article/'.$info->getSaveName();
            $res = [
                'success' => 1,
                'message' => '成功',
                'url' => $data['path']
            ];
            return json_encode($res);
        }
    } 
}