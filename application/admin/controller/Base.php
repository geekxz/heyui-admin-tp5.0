<?php
/**
 * Created by 陈东东
 * Author: 陈东东  <1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/4/15
 * Time: 22:40
 */
namespace app\admin\controller;
use app\admin\model\Node;
use think\Controller;
use think\Db;
use \think\Request;
class Base extends Controller
{
    public function _initialize()
    {
        $request = request();
        $control_name = $request->controller();
        $action_name = $request->action();

        $crumbs = $this->position($control_name,$action_name);

		//$request = Request::instance();
        if(empty(session('username'))){
            $this->redirect(url('admin/login/index'));
        }
		
        //检测权限
        $control = lcfirst( request()->controller() );
		
        $action = lcfirst( request()->action() );		
		
        //跳过登录系列的检测以及主页权限
        if(!in_array($control, ['login', 'index','uplode'])){
			
            if(!in_array($control . '/' . $action, session('action'))){
				if(session('ruleid') != '1'){
					$this->error('没有权限');
				}                
            }
        }
		
		// dump($crumbs);
        //获取权限菜单
        $node = new Node();
        $this->assign([
            'username' => session('username'),
            'menu' => $node->getMenu(session('rule')),
            'rolename' => session('role'),
            'crumbs' => $crumbs,
			//'info'=>  $request->pathinfo(),
        ]);	
			
    }


    //面包屑导航

    public function position($control_name,$action_name){//传递当前栏目id

        static $pos=array();//创建接受面包屑导航的数组
        $map['control_name'] = array('eq', strtolower($control_name)); 
        $map['action_name'] = array('eq', $action_name); 
        // var_dump($map);die;

        if(empty($pos)){//哦，这个就比较重要了，如果需要把当前栏目也放到面包屑导航中的话就要加上

            $cates=db('node')->field('id,node_name,typeid,control_name,action_name,is_menu')->where($map)->find();

            $pos[]=$cates;

        }

        $data=db('node')->field('id,node_name,typeid,control_name,action_name,is_menu')->select();//所有栏目信息

        $cates=db('node')->field('id,node_name,typeid,control_name,action_name,is_menu')->where($map)->find();//当前栏目信息

        foreach ($data as $k => $v) {

            if($cates['typeid']==$v['id']){

                $pos[]=$v;

                $this->position($v['control_name'],$v['action_name']);

            }

        }

        return array_reverse($pos);

    }
    
	

    public function exportExcel($objPHPExcel)
    {   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="package('.date('Ymd-His').').xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit();
    }


    // 签到获得积分 积分明细表
    public function addScore($uid,$type,$score,$remarks)
    {
        $data['uid'] = $uid;
        $data['type'] = $type;
        $data['score'] = $score;
        $data['remarks'] = $remarks;
        $data['addtime'] = time();
        db('Score_details')->insert($data);
    }

    // 更新用户总积分
    public function updateUserTotalScore($uid,$score,$type)
    {
        $user_score = db('User')->where(['id'=>$uid])->value('total_score');
        switch ($type) {
            case 'REDUCE':
                $total_score = $user_score - $score;
                break;
            case 'ADD':
                $total_score = $user_score + $score;
                break; 
        }
        db('User')->where(['id'=>$uid])->update(['total_score'=>$total_score]);
    }

		
}