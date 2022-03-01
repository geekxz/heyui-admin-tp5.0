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
use app\admin\model\Role as RoleModel;
class Role extends Base
{
    //角色列表
    public function index()
    {
        if(request()->isAjax()){
            $param = input('param.');
            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;
            $where = [];
            if (isset($param['searchText']) && !empty($param['searchText'])) {
                $where['rolename'] = ['like', '%' . $param['searchText'] . '%'];
            }
            $user = new RoleModel();
            $selectResult = $user->getRoleByWhere($where, $offset, $limit);
            foreach($selectResult as $key=>$vo){
                if(1 == $vo['id']){
                    $selectResult[$key]['operate'] = '';
                    continue;
                }
                $operate = [
                    '编辑' => url('role/edit', ['id' => $vo['id']]),
                    '删除' => "javascript:roleDel('".$vo['id']."')",
                    '分配权限' => "javascript:giveQx('".$vo['id']."')"
                ];
                $selectResult[$key]['operate'] = showOperate($operate);
            }
            $return['total'] = $user->getAllRole($where);  //总数据
            $return['rows'] = $selectResult;
            return json($return);
        }
        return view();
    }
    //添加角色
    public function add()
    {
        if(request()->isPost()){
            $param = input('param.');
            $role = new RoleModel();
            $flag = $role->insertRole($param);
            if ($flag['code'] == 1) {
                echo geekxz_success('您好，添加成功！','/admin/role/index');
            } else {
                echo geekxz_error('您好，添加失败！');
            }
        }
        return view();
    }
    //编辑角色
    public function edit()
    {
        $role = new RoleModel();
        if(request()->isPost()){
            $param = input('post.');
            // $param = parseParams($param['data']);
            $flag = $role->editRole($param);
            if ($flag['code'] == 1) {
                echo geekxz_success('您好，修改成功！','/admin/role/index');
            } else {
                echo geekxz_error('您好，修改失败！');
            }
        }
        $id = input('param.id');
        $this->assign([
            'role' => $role->getOneRole($id)
        ]);
        return view();
    }
    //删除角色
    public function roleDel()
    {
        $id = input('param.id');
        $role = new RoleModel();
        $flag = $role->delRole($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }
    //分配权限
    public function giveAccess()
    {
        $param = input('param.');
        $node = new Node();
        //获取现在的权限
        if('get' == $param['type']){
            $nodeStr = $node->getNodeInfo($param['id']);
            return json(['code' => 1, 'data' => $nodeStr, 'msg' => 'success']);
        }
        //分配新权限
        if('give' == $param['type']){
            $doparam = [
                'id' => $param['id'],
                'rule' => $param['rule']
            ];
            $user = new RoleModel();
            $flag = $user->editAccess($doparam);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}