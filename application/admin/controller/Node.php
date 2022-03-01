<?php
/**
 * Created by 陈东东
 * Author: 陈东东	<1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/4/15
 * Time: 22:40
 */
namespace app\admin\controller;
use app\admin\builder\ListBuilder;
use app\admin\builder\FormBuilder;
class Node extends Base
{
    
    public function index()
    {
		$result = $this->_getCTree();
		// echo "<pre>";
		// var_dump($result);die;
		return view('index',[
	        'res'=>$result['data']
	    ]);		
    }	
	private function _getTree($page_size='')
	{
		$data = db('node')->field(['id,node_name,module_name,control_name,action_name,typeid'])->paginate($page_size);
		$res['page'] = $data->render();
		$res['data'] = $this->_reSort($data);
		return $res;
	}
	private function _getCTree()
	{
		$data = db('node')->field(['id,node_name,module_name,control_name,action_name,typeid'])->select();
		// $res['page'] = $data->render();
		$res['data'] = $this->_reSort($data);
		return $res;
	}
	private function _reSort($data, $typeid=0, $level=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{			
			if($v['typeid'] == $typeid)
			{
				$v['level'] = $level;
				$child_id = db('node')->field(['id,typeid'])->where(['typeid'=>$v['id']])->find();
				$v['child'] = $child_id ? true : false;
				$ret[$v['id']] = $v;
				$this->_reSort($data, $v['id'], $level+1, FALSE);

			}
		}
		return $ret;
	}
	
	
	public function add() {
        if (request()->isPost()) {			
			$data = input('post.');
			$res = db('Node')->insert($data);
			if ($res) {
                echo geekxz_success('您好，添加成功！','/admin/node/index');
            } else {
                echo geekxz_error('您好，添加失败！');
            }
		} else {
			$result = $this->_getCTree();
			return view('add',[
		        'res'=>$result['data']
		    ]);				
	 	}
	}
	public function edit($id) {
        if (input('post.')) {
            $data = input('post.'); 
            $res = db('node')->where('id', input('post.id'))->update($data);
            if ($res) {
                echo geekxz_success('您好，修改成功！','/admin/node/index');
            } else {
                echo geekxz_error('您好，修改失败！');
            }
        } else {
			 $info = db('node')->find($id);
			 $result = $this->_getCTree();
			return view('edit',[
						        'res'=>$result['data'],
						        'info'=>$info
					   		]);
         }
    }

    // 删除操作
    public function dels()
    {
    	$id = input('param.id');		
		$result = db('Node')->where(['id'=>$id])->delete();        
        if ($result) {
            return geekxza('删除成功');
        } else {
            return geekxzb('删除失败');
        }
    }

	
}
