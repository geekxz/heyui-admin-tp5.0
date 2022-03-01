<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件



// 应用公共文件
function geekxza($str)
{
    return json(array("code" => 200, "msg" => $str));
}
function geekxzb($str)
{
    return json(array("code" => 0, "msg" => $str));
}
function geekxzc()
{
    return view();
}


/**
 * $msg 待提示的消息
 * $url 待跳转的链接
 * $icon 这里主要有两个，5和6，代表两种表情（哭和笑）
 * $time 弹出维持时间（单位秒）
 */
function geekxz_success($msg='',$url='',$time=3){ 
    $str='<script type="text/javascript" src="/public/static/admin/js/jquery-2.1.1.js"></script><script type="text/javascript" src="/public/static/admin/js/plugins/sweetalert/sweetalert.min.js"></script><link href="/public/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';//加载jquery和layer
    $str.='<script>
        $(function(){
            swal("提示","操作成功","success");
            setTimeout(function(){
               self.parent.location.href="'.$url.'"
            },1000)
        });
    </script>';
    return $str;
}

/**
 * $msg 待提示的消息
 * $icon 这里主要有两个，5和6，代表两种表情（哭和笑）
 * $time 弹出维持时间（单位秒）
 */
function geekxz_error($msg='',$time=3){ 
    $str='<script type="text/javascript" src="/public/static/admin/js/jquery-2.1.1.js"></script> <script type="text/javascript" src="/public/static/admin/js/plugins/sweetalert/sweetalert.min.js"></script><link href="/public/static/admin/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">';//加载jquery和layer
    $str.='<script>
        $(function(){
            swal("提示","操作成功","success"); 
            setTimeout(function(){
                   self.parent.location.href="'.$url.'"
            },1000)
        });
    </script>';
    return $str;
}


/**
 * 生成操作按钮
 * @param array $operate 操作按钮数组
 */
function showOperate($operate = [])
{
    if(empty($operate)){
        return '';
    }
    $option = <<<EOT
<div class="btn-group">
    <button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        操作 <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
EOT;
    foreach($operate as $key=>$vo){
        $option .= '<li><a href="'.$vo.'">'.$key.'</a></li>';
    }
    $option .= '</ul></div>';
    return $option;
}
/**
 * 将字符解析成数组
 * @param $str
 */
function parseParams($str)
{
    $arrParams = [];
    parse_str(html_entity_decode(urldecode($str)), $arrParams);
    return $arrParams;
}
/**
 * 子孙树 用于菜单整理
 * @param $param
 * @param int $pid
 */
function subTree($param, $pid = 0)
{
    static $res = [];
    foreach($param as $key=>$vo){
        if( $pid == $vo['pid'] ){
            $res[] = $vo;
            subTree($param, $vo['id']);
        }
    }
    return $res;
}
/**
 * 整理菜单住方法
 * @param $param
 * @return array
 */
function prepareMenu($param)
{
    $parent = []; //父类
    $child = [];  //子类
    foreach($param as $key=>$vo){
        if($vo['typeid'] == 0){
            $vo['href'] = '#';
            $parent[] = $vo;
        }else{
            $vo['href'] = url($vo['control_name'] .'/'. $vo['action_name']); //跳转地址
            $child[] = $vo;
        }
    }
    foreach($parent as $key=>$vo){
        foreach($child as $k=>$v){
            if($v['typeid'] == $vo['id']){
                $parent[$key]['child'][] = $v;
            }
        }
    }
    unset($child);
    return $parent;
}
/**
 * 解析备份sql文件
 * @param $file
 */
function analysisSql($file)
{
    // sql文件包含的sql语句数组
    $sqls = array ();
    $f = fopen ( $file, "rb" );
    // 创建表缓冲变量
    $create = '';
    while ( ! feof ( $f ) ) {
        // 读取每一行sql
        $line = fgets ( $f );
        // 如果包含空白行，则跳过
        if (trim ( $line ) == '') {
            continue;
        }
        // 如果结尾包含';'(即为一个完整的sql语句，这里是插入语句)，并且不包含'ENGINE='(即创建表的最后一句)，
        if (! preg_match ( '/;/', $line, $match ) || preg_match ( '/ENGINE=/', $line, $match )) {
            // 将本次sql语句与创建表sql连接存起来
            $create .= $line;
            // 如果包含了创建表的最后一句
            if (preg_match ( '/ENGINE=/', $create, $match )) {
                // 则将其合并到sql数组
                $sqls [] = $create;
                // 清空当前，准备下一个表的创建
                $create = '';
            }
            // 跳过本次
            continue;
        }
        $sqls [] = $line;
    }
    fclose ( $f );
    return $sqls;
}
/**
 * 数组转对象
 * @param $file
 */
function array2object($array) {

  if (is_array($array)) {
    $obj = new StdClass();
    foreach ($array as $key => $val){
      $obj->$key = $val;
    }
  }
  else { 
    $obj = $array; 
}
  return $obj;
}
/**
 * 对象转数组
 * @param $file
 */
function object2array($object) {
    $array = array();
  if (is_object($object)) {
    foreach ($object as $key => $value) {
      $array[$key] = $value;
    }
  }
  else {
    $array = $object;
  }
  return $array;
}

