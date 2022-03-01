<?php
/**
 * Created by 陈东东
 * Author: 陈东东	<1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/4/15
 * Time: 22:40
 */
namespace app\admin\controller;
use think\Request;
class Admin extends Base
{
    //用户列表
    public function index()
    {
    	// 搜索框条件        
		$result = db('admin')->alias('a')
            ->join('role r', 'a.typeid=r.id','left')
            ->field('a.*,r.rolename')
			->paginate(10);
		return view('index',[
	        'res'=>$result
	    ]);
    }	
  
    // 删除操作
    public function dels()
    {
    	$id = input('param.id');		
		$result = db('Admin')->where(['id'=>$id])->delete();        
        if ($result) {
            return geekxza('删除成功');
        } else {
            return geekxzb('删除失败');
        }
    }
	public function add(){		 
	 	if (request()->isPost()) {			
			$data = input('post.');
			$data['password'] = md5($data['password']);
            $res = db('Admin')->insert($data);
			if ($res) {
                echo geekxz_success('您好，添加成功！','/admin/admin/index');
            } else {
                echo geekxz_error('您好，添加失败！');
            }			             
        } else {
			return 	view();			
	 	}	 
	 }

	 public function edit(){	  	
		if (request()->isPost()) {			
			$data = input('post.');
			$res = db('Admin')->where('id', input('post.id'))->update($data);
			if ($res) {
                echo geekxz_success('您好，修改成功！','/admin/admin/index');
            } else {
                echo geekxz_error('您好，修改失败！');
            }
            
        } else {
			$id = input('param.id');
			$info = db('Admin')->where(['id'=>$id])->find();
			$role = db('Role')->select();
			return view('edit',[
						        'info'=>$info,
						        'role'=>$role,
					   		]);			
		 }
	 }
	 public function editpwd(){
	 	if (request()->isPost()) {			
			$data = input('post.');

			$data['lodpassword'] = md5($data['lodpassword']);

			$info = db('Admin')->where('id', input('post.id'))->find();
			if ($info['passworld'] == $data['lodpassword']) {
				$res = db('Admin')->where('id', input('post.id'))->update($data['passworld']);
				if ($res) {
	                echo geekxz_success('您好，修改成功！','/admin/admin/index');
	            } else {
	                echo geekxz_error('您好，修改失败！');
	            }
			}else{
				echo geekxz_error('您好，修改失败！');
			}
        } else{
        	$id = input('param.id');
			$info = db('Admin')->where(['id'=>$id])->find();
			return view('editpwd',[
						        'info'=>$info
					   		]);
        }
	 }
	 
	 //删除用户
	 public function destyoy(){		
	 	$id = input('param.id');
		
		$result = db('Admin')->where(['id'=>$id])->delete();
		if ($result) {
            return geekxza('删除成功');
        } else {
            return geekxzb('删除失败');
        }		
	 }
	
	
	 
}
