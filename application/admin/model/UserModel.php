<?php
/**
 * Created by 陈东东
 * Author: 陈东东  <1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/4/15
 * Time: 22:40
 */

namespace app\admin\model;

use think\Model;

class UserModel extends Model

{

    // protected $table = 'admin';

    /**
     * 根据搜索条件获取用户列表信息
     * @param $where
     * @param $offset
     * @param $limit
     */

    public function getUsersByWhere($where, $offset, $limit)
    {
        return $this->field('admin.*,rolename')
            ->join('role', 'admin.typeid = role.id')
            ->where($where)->limit($offset, $limit)->order('id desc')->select();
    }



    /**
     * 根据搜索条件获取所有的用户数量
     * @param $where
     */

    public function getAllUsers($where)
    {
        return $this->where($where)->count();
    }



    /**
     * 插入管理员信息
     * @param $param
     */

    public function insertUser($param)
    {
        try{
            $result =  $this->validate('UserValidate')->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }



    /**
     * 编辑管理员信息
     * @param $param
     */

    public function editUser($param)
    {
        try{
            $result =  $this->validate('UserValidate')->save($param, ['id' => $param['id']]);
            if(false === $result){
                // 验证失败 输出错误信息
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '编辑用户成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }



    /**
     * 根据管理员id获取角色信息
     * @param $id
     */

    public function getOneUser($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除管理员
     * @param $id
     */
    public function delUser($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除管理员成功'];
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }

    }

}