<?php
/**
 * Created by 陈东东
 * Author: 陈东东	<1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/4/15
 * Time: 22:40
 */
namespace app\admin\model;
use think\Model;
use think\Controller;
use think\Db;
class Node extends Model
{
	public function getNodeInfo($id)
    {
        $result = $this->field('id,node_name,typeid')->select();
        $str = "";
        $role = new Role();
        $rule = $role->getRuleById($id);
        if(!empty($rule)){
            $rule = explode(',', $rule);
        }
        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['typeid'] . '", "name":"' . $vo['node_name'].'"';
            if(!empty($rule) && in_array($vo['id'], $rule)){
                $str .= ' ,"checked":1';
            }
            $str .= '},';
        }
        return "[" . substr($str, 0, -1) . "]";
    }
    /**
     * 根据节点数据获取对应的菜单
     * @param $nodeStr
     */
    public function getMenu($nodeStr = '')
    {
        //超级管理员没有节点数组
        $where = empty($nodeStr) ? 'is_menu = 2' : 'is_menu = 2 and id in('.$nodeStr.')';
		
        $result = db('node')->field('id,node_name,typeid,control_name,action_name,style')
            ->where($where)->select();
        $menu = prepareMenu($result);
        return $menu;
    }
	
	
	
	public function getPriBtn(){
		// 取出当前管理员拥有的前两级的权限
		// 先取出所有的
		$allPriData = $this->getAllPri();
		// 从所有的权限中提取出前两级的
		$btn = array();
		foreach ($allPriData as $k => $v)
		{
			if($v['typeid'] == 0)
			{
				// 再找这个顶级的下一级的
				foreach ($allPriData as $k1 => $v1)
				{
					if($v1['typeid'] == $v['id'])
					{						
						// 把子权限放到顶级权限的children字段中
						$v['children'][] = $v1;
					}else{						
						// $v['children'][] = '';
					}
				}
				$btn[] = $v;
			}
		}
	 	//dump($btn);
		return $btn;
	}
	
	// 获取当前管理员所拥有的所有的权限
	public function getAllPri()
	{
		// 获取当前管理员的角色id
		$ruleid = session('ruleid');
		$adminId = session('id');
		//$adminId = 4;
		if($ruleid == 1)
		{
			// 超级管理员拥有所有的权限
			$data = db('node')->where(['is_menu'=>'2'])->select();	
		}
		else 
		{
			// 根据管理员ID取出权限：
			//流程：
			// 1.先根据管理员ID取出这个管理员所在的角色ID  
			// 2. 再取出这些角色所拥有的权限的ID 
			// 3. 再从权限表取出这些权限的信息

			// $sql = "select r.rule from user as u,role as r where u.typeid =r.id and u.id = $adminId";
			// $priData = Db::query($sql);			
			// $ids = $priData[0]['rule'];
			$priData = db('admin')->alias('u')
				->join('role r', 'u.typeid = r.id')
				->where('u.id ='.$adminId)
				->field('r.rule')
				->select();
			$ids = $priData[0]['rule'];

			// $sql1 =  "select * from node where id in ($ids)";			 
			// $data = Db::query($sql1);

			$data = db('node')->whereIn('id',$ids)->select();
		}
		return $data;
	}
	public static function getNodes(){
	    //查询
	    $result = db('node')->field('id,node_name,typeid,control_name,action_name,style')
            ->where($where)->select();
	    var_dump($result);die;
	    $res = self::getChild($result->toArray());
	    return $res;
	}

	/**
	 * 递归 树节点算法
	 * @param array $array
	 * @param number $pid
	 */
	private static function getChild($arr, $typeid = 0){
	    $data = array();

	    foreach ($arr as $k=>$v){
	        //PID符合条件的
	        if($v['typeid'] == $typeid){
	            //寻找子集
	            $child = $this->getChild($arr,$v['id']);
	            //加入数组
	            $v['child'] = $child ? : []; 
	            $data[] = $v;//加入数组中
	        }
	    }

	    return $data;
	}
}