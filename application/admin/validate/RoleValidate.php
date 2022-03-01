<?php


namespace app\admin\validate;



use think\Validate;



class RoleValidate extends Validate

{

    protected $rule = [

        ['rolename', 'unique:role', '角色已经存在']

    ];



}