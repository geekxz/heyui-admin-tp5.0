<?php
/**
 * Created by 陈东东
 * Author: 陈东东  <1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/4/15
 * Time: 22:40
 */
namespace app\admin\controller;

use app\admin\builder\ListBuilder;
use app\admin\builder\FormBuilder;
use app\admin\model\Role as RoleModel;
use think\Controller;
use org\Verify;

class Login extends Controller
{
    //登录页面
    public function index()
    {
        return view('/login');
    }

    //登录操作
    public function doLogin()
    {
        $username = input("param.username");
        $password = input("param.password");
        $code = input("param.code");
        $result = $this->validate(compact('username', 'password', "code"), 'AdminValidate');
        if(true !== $result){
            return json(['code' => -5, 'data' => '', 'msg' => $result]);
        }

        $verify = new Verify();
        if (!$verify->check($code)) {
            return json(['code' => -4, 'data' => '', 'msg' => '验证码错误']);
        }

        $hasUser = db('admin')->where('username', $username)->find();
        if(empty($hasUser)){
            return json(['code' => -1, 'data' => '', 'msg' => '管理员不存在']);
        }

        if(md5($password) != $hasUser['password']){
            return json(['code' => -2, 'data' => '', 'msg' => '密码错误']);
        }

        if(1 != $hasUser['status']){
            return json(['code' => -6, 'data' => '', 'msg' => '该账号被禁用']);
        }

        //获取该管理员的角色信息
        $user = new RoleModel();
        $info = $user->getRoleInfo($hasUser['typeid']);
        session('username', $username);
        session('real_name', $hasUser['real_name']); // 真实姓名
        session('id', $hasUser['id']);
        session('role', $info['rolename']);  //角色名
        session('rule', $info['rule']);  //角色节点
        session('ruleid', $hasUser['typeid']);  //角色id
        session('action', $info['action']);  //角色权限

        //更新管理员状态
        $param = [
            'loginnum' => $hasUser['loginnum'] + 1,
            'last_login_ip' => request()->ip(),
            'last_login_time' => time()
        ];

        db('admin')->where('id', $hasUser['id'])->update($param);

        return json(['code' => 1, 'data' => url('index/index'), 'msg' => '登录成功']);
    }

    //验证码
    public function checkVerify()
    {
        $verify = new Verify();
        $verify->imageH = 32;
        $verify->imageW = 100;
        $verify->length = 4;
        $verify->useNoise = false;
        $verify->fontSize = 14;
        return $verify->entry();
    }

    //退出操作
    public function loginOut()
    {
        session('username', null);
        session('id', null);
        session('role', null);  //角色名
        session('rule', null);  //角色节点
        session('action', null);  //角色权限

        $this->redirect(url('index'));
    }
    public function editpwd(){
        if (request()->isPost()) {          
            $data = input('post.');
            $data['password'] = md5($data['password']);
            $res = db('admin')->where('id',$data['id'])->update($data);
            if ($res) {                
                $this->success('修改成功', 'index');
            } else {
                $this->error('修改失败');
            }
            
        } else {
            $id = session('id');;
            $list = db('admin')->where(['id'=>$id])->find();
            // var_dump($list);die;
            $list['password'] ='';
            $builder = new FormBuilder();           
            return  $builder->setMetaTitle('修改用户')              // 设置页面标题
                    ->setPostUrl(url('editpwd'))                   // 设置表单提交地址
                    ->addFormItem('id', 'hidden', '隐藏', '')
                    ->addFormItem('password', 'text', '新密码')                    
                    ->setFormData($list)
                    ->display();            
         }
    }
}