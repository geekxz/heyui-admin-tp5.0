<?php


//获取管理员状态
const ADMIN = ["<span class='label label-danger'>×</span>","<span class='label label-primary'>√</span>"];
function getAdminStatus($status){
   if(empty($status)){
      return ADMIN[0];   
   }else{
      return ADMIN[1]; 
   }
}

//获取分类状态
const CATE = ["<span class='label label-danger'>×</span>","<span class='label label-primary'>√</span>"];
function getCateStatus($status){
	if(empty($status)){
		return CATE[0];	
	}else{
		return CATE[1]; 
	}
}


//获取日志类型
const LOGTYPE = ["<span class='label label-danger'>删除</span>",
               "<span class='label label-success'>新增</span>",
               "<span class='label label-info'>修改</span>",
               
               "<span class='label label-success'>已发货</span>",
               "<span class='label label-danger'>已支付，但库存不足</span>",
               "<span class='label label-info'>已处理PAID_BUT_OUT_OF</span>"

               ];
function getLogType($type){
   if($type==0){
      return LOGTYPE[0];   
   }elseif($type==1){
      return LOGTYPE[1]; 
   }elseif($type==2){
      return LOGTYPE[2]; 
   }
}


// 检测是否有权限
function checkJurisdiction($node){
   if(!in_array($node, session('action'))){
      return false;               
   }{
      return true;
   }
}