<?php
/**
 * Created by 陈东东
 * Author: 陈东东  <1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/4/14
 * Time: 17:40
 */
use think\DB;

class ActionLog{
	// 修改前的数据
	protected static $prev_params;

	protected static $tbid;
	protected static $tbname;

	/**
	 * 参数说明
	 * int	$tbid 	指定表的id
	 * string $tbname 	数据库表名
	 */
	public static function insert($tbid, $tbname){
		//查询表注释
		$tb = DB::query('show table status where name = "'.$tbname.'"');
		//插入日志表
		$returnid = DB::table('hey_log')->insertGetId( array(
			'admin_id' =>session('id'),
			'type' => 1,
			'table_id' => $tbid,
			'table_name_en' => $tbname,
			'table_name_zh' => $tb[0]['Comment'],
			'update_time' => time()
		));
		
		//查询所有字段信息，插入日志表
		$res = DB::table($tbname)->where(['id'=>$tbid])->select();
		DB::table('hey_log')->where(['id'=>$returnid])->update(['prev_params'=>json_encode($res),'last_params'=>json_encode($res)]);
	}

	/**
	 * 参数说明
	 * int	$tbid 	指定表的id
	 * string $tbname 	数据库表名
	 */
	public static function updateStart($tbid, $tbname){
		//查询表注释
		// $tb = DB::query('show table status where name = "'.$tbname.'"');
		$res = DB::table($tbname)->where(['id'=>$tbid])->select();
		self::$prev_params = $res;
		self::$tbid = $tbid;
		self::$tbname = $tbname;
		
	}


	public static function updateEnd(){
		$tbname = self::$tbname;
		$tbid = self::$tbid;
		$prev_params = self::$prev_params;
		//查询表注释
		$tb = DB::query('show table status where name = "'.$tbname.'"');

		//查询修改后数据信息
		$res = DB::table($tbname)->where(['id'=>$tbid])->select();

		//插入日志主表
		$returnid = DB::table('hey_log')->insert( array(
			'admin_id' =>session('id'),
			'type' => 2,
			'table_id' => $tbid,
			'table_name_en' => $tbname,
			'table_name_zh' => $tb[0]['Comment'],
			'prev_params'=>json_encode($prev_params),
			'last_params'=>json_encode($res),
			'update_time' => time()
		));
		
	}

	/**
	 * 参数说明
	 * int	$tbid 	指定表的id
	 * string $tbname 	数据库表名
	 */
	public static function delete($tbid, $tbname){
		//查询表注释
		$tb = DB::query('show table status where name = "'.$tbname.'"');

		//查询所有字段信息，插入日志表
		$res = DB::table($tbname)->where(['id'=>$tbid])->select();

		//插入日志表
		$returnid = DB::table('hey_log')->insertGetId( array(
			'admin_id' =>session('id'),
			'type' => 0,
			'table_id' => $tbid,
			'table_name_en' => $tbname,
			'table_name_zh' => $tb[0]['Comment'],
			'prev_params'=>json_encode($res),
			'last_params'=>json_encode($res),
			'update_time' => time()
		));
	}
	public static function offOrOn($tbid, $tbname){
		
	}
}