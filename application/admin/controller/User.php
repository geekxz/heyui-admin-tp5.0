<?php
/**
 * Created by 陈东东
 * Author: 陈东东	<1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/6/26
 * Time: 14:50
 */
namespace app\admin\controller;
class User extends Base
{

    public function index()
    {
		/*$request = request();
		echo "当前模块名称是" . $request->module();
		echo "当前控制器名称是" . $request->controller();
		echo "当前操作名称是" . $request->action();*/
		$result = db('User')->field('id,openid,nickname,create_time,total_score')->paginate(10);
		
		return view('index',[
	        'res'=>$result
	    ]);		
    }
    // 积分明细列表
    public function score(){
    	$res = db('Score_details')->alias('s')
                        ->join('user u', 'u.id=s.uid','LEFT')
                        ->field('s.*,u.nickname')
                        ->order('id desc')
                        ->paginate(15);
    	return view('score',[
    		'res' => $res
    		]);
    }
	
	
	

	
}
