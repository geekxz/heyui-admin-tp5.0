<?php
/**
 * Created by 陈东东
 * Author: 陈东东	<1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/4/15
 * Time: 22:40
 */
namespace app\admin\controller;

use ActionLog;

class Index extends Base
{
    public function index()
    {
    //   	$request = request();
    // echo "当前模块名称是" . $request->module();
    // echo "当前控制器名称是" . $request->controller();
    // echo "当前操作名称是" . $request->action();
    //   	echo "<pre>";
    //   	$control_name = $request->controller();
    //   	$action_name = $request->action();
    //   	var_dump($this->position($control_name,$action_name));
        // $actionlog = new ActionLog();
        // var_dump($actionlog->insert(1,'hey_banner'));
      	// die;

		return view();
    }

   
}
