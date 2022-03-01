<?php
/**
 * Created by 陈东东
 * Author: 陈东东	<1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/3/26
 * Time: 13:34
 */
namespace app\admin\controller;
class Banner extends Base
{
    
    public function type()
    {
		/*$request = request();
		echo "当前模块名称是" . $request->module();
		echo "当前控制器名称是" . $request->controller();
		echo "当前操作名称是" . $request->action();*/
		$result = db('banner')->select();
		return view('type',[
	        'res'=>$result
	    ]);		
    }
    public function addtype() {
        if (request()->isPost()) {			
			$data = input('post.');
			$res = db('Banner')->insert($data);
			if ($res) {
                echo geekxz_success('您好，添加成功！','/admin/Banner/type');
            } else {
                echo geekxz_error('您好，添加失败！');
            }
		} else {
			return view('addtype');				
	 	}
	}
	// 删除banner
    public function typedel()
    {
        $id = input('param.id');
		
		$result = db('Banner')->where(['id'=>$id])->delete();
		if ($result) {
            return geekxza('删除成功');
        } else {
            return geekxzb('删除失败');
        }
    }
    public function edittype($id) {
        if (input('post.')) {
            $data = input('post.'); 
            $data['update_time'] = time();
            $res = db('Banner')->where('id', input('post.id'))->update($data);
            if ($res) {
                echo geekxz_success('您好，修改成功！','/admin/banner/type');
            } else {
                echo geekxz_error('您好，修改失败！');
            }
        } else {
			 $info = db('Banner')->find($id);
			return view('edittype',[
						        'info'=>$info
					   		]);
         }
    }



    public function index()
    {
		// $request = request();
  //       $control_name = $request->controller();
  //       $action_name = $request->action();
  //       $crumbs = $this->position($control_name,$action_name);
        // die;
		$result = db('banner_item')->alias('t')->join('banner b', 'b.id=t.banner_id')->field('t.*,b.name')->select();

		return view('index',[
            'res'=>$result,
	        // 'crumbs'=>$crumbs,
	    ]);		
    }
	public function add() {
        if (request()->isPost()) {			
			$data = input('post.');
            // var_dump($data);die;
			$res = db('Banner_item')->insert($data);
			if ($res) {
                echo geekxz_success('您好，添加成功！','/admin/Banner/index');
            } else {
                echo geekxz_error('您好，添加失败！');
            }
		} else {
			$res = db('Banner')->select();
			return view('add',[
						        'res'=>$res
					   		]);				
	 	}
	}
	// 删除banner
    public function dels()
    {
        $id = input('param.id');
		
		$result = db('Banner_item')->where(['id'=>$id])->delete();
		if ($result) {
            return geekxza('删除成功');
        } else {
            return geekxzb('删除失败');
        }
    }
    public function edit($id) {
        if (input('post.')) {
            $data = input('post.'); 
            $data['update_time'] = time();
            $res = db('Banner_item')->where('id', input('post.id'))->update($data);
            if ($res) {
                echo geekxz_success('您好，修改成功！','/admin/banner/index');
            } else {
                echo geekxz_error('您好，修改失败！');
            }
        } else {
			 $info = db('Banner_item')->find($id);
			 $res = db('Banner')->select();
			return view('edit',[
						        'res'=>$res,
						        'info'=>$info
					   		]);
         }
    }
	
	
    public function uploadImg222(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');  
         // dump($file);die;
        $info = $file->move(ROOT_PATH . 'uploads/banner/');
        if(!$info) {// 上传错误提示错误信息  
            $this->error($upload->getError());            
        }else{// 上传成功 获取上传文件信息  
            $data['path'] =  '/uploads/banner/'.$info->getSaveName();

            return $data['path'];
        }  
    }


    // banner图片上传uploadImg
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
	
}
