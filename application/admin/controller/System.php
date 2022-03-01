<?php
/**
 * Created by 陈东东
 * Author: 陈东东	<>
 * 微信公号: 极客小寨工作室
 * Date: 2019/3/26
 * Time: 13:34
 */
namespace app\admin\controller;
use think\DB;
class System extends Base
{
	/**
     * 判断指定路径下指定文件是否存在，如不存在则创建
     * @param string $fileName 文件名
     * @param string $content 文件内容
     * @return string 返回文件路径
     */
    private function getFilePath($fileName, $content)
    {
        $path = dirname(__FILE__) . "\\$fileName";
        if (!file_exists($path)) {
            file_put_contents($path, $content);
        }
        return $path;
    }

    /**
     * 获得cpu使用率vbs文件生成函数
     * @return string 返回vbs文件路径
     */
    private function getCupUsageVbsPath()
    {
        return $this->getFilePath(
            'cpu_usage.vbs',
            "On Error Resume Next
    Set objProc = GetObject(\"winmgmts:\\\\.\\root\cimv2:win32_processor='cpu0'\")
    WScript.Echo(objProc.LoadPercentage)"
        );
    }

    /**
     * 获得总内存及可用物理内存JSON vbs文件生成函数
     * @return string 返回vbs文件路径
     */
    private function getMemoryUsageVbsPath()
    {
        return $this->getFilePath(
            'memory_usage.vbs',
            "On Error Resume Next
    Set objWMI = GetObject(\"winmgmts:\\\\.\\root\cimv2\")
    Set colOS = objWMI.InstancesOf(\"Win32_OperatingSystem\")
    For Each objOS in colOS
     Wscript.Echo(\"{\"\"TotalVisibleMemorySize\"\":\" & objOS.TotalVisibleMemorySize & \",\"\"FreePhysicalMemory\"\":\" & objOS.FreePhysicalMemory & \"}\")
    Next"
        );
    }

    /**
     * 获得Windows CPU使用率
     * @return Number
     */
    public function getCpuUsage()
    {
        $path = $this->getCupUsageVbsPath();
        exec("cscript -nologo $path", $usage);
        return $usage[0];
    }

    /**
     * 获得Windows 内存使用率数组
     * @return array
     */
    public function getMemoryUsage()
    {
        $path = $this->getMemoryUsageVbsPath();
        exec("cscript -nologo $path", $usage);
        $memory = json_decode($usage[0], true);


        $memory['usage'] = Round((($memory['TotalVisibleMemorySize'] - $memory['FreePhysicalMemory']) / $memory['TotalVisibleMemorySize']) * 100);

        $memory['TotalVisibleMemorySize'] = Round($memory['TotalVisibleMemorySize']/1024/1024,2);
        $memory['FreePhysicalMemory'] = Round($memory['FreePhysicalMemory']/1024/1024,2);
        return $memory;
    }


    public function sysinfo(){
    	$memory= $this->getMemoryUsage();

    	$sysinfo['cpu'] = $this->getCpuUsage();		// cpu使用
    	$sysinfo['usage'] = $memory['usage'];		// 内存使用
    	$sysinfo['TotalVisibleMemorySize'] = $memory['TotalVisibleMemorySize'];		// 内存总数
    	$sysinfo['FreePhysicalMemory'] = $memory['FreePhysicalMemory'];		// 内存空闲

    	$sysinfo['loadstatus'] = ($memory['usage'] + $sysinfo['cpu'])/2;  //负载状态

    	return $sysinfo;
    	// return json(array("code" => 200, "msg" => 'success', 'data'=>$sysinfo));
        // echo "<pre>";
    	// var_dump($sysinfo);die;
    }


    // 独显电脑负载=(cpu负载 内存负载 网络负载 GPU负载)➗4
    // 实际电脑负载=(cpu负载 内存负载 网络负载 GPU负载 硬盘负载 散热系统负载)➗6






    public function sys()
    {
        $sys= array(
            '操作系统' => PHP_OS, //获取服务器操作系统
            'PHP服务器版本' => PHP_VERSION, //获取PHP服务器版本
            'mysql版本' => $this->_mysql_version(),
            'mysql已存空间' => $this->_mysql_db_size(),
            '最大上传' => ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled", //最大上传
            '脚本最大执行时间' => ini_get("max_execution_time")."秒", //脚本最大执行时间
            '服务器标识' => $_SERVER["SERVER_SOFTWARE"], //获取服务器标识的字串
            '主机域名' => $_SERVER['SERVER_NAME'], //当前主机名
            // 'osname' => php_uname(), //获取系统类型及版本号
            '服务器语言' => $_SERVER['HTTP_ACCEPT_LANGUAGE'], //获取服务器语言
            '服务器Web端口' => $_SERVER['SERVER_PORT'], //获取服务器Web端口
            '服务器时间' => date("Y-m-d H:i:s", time()), //获取服务器时间
        );
        return $sys;
    }
    private function _mysql_version()
    {
        $version = DB::query("select version() as ver");
        return $version[0]['ver'];
    }

    private function _mysql_db_size()
    {        
        $sql = "SHOW TABLE STATUS FROM ".config('database.database');
        $tblPrefix = config('prefix');
        if($tblPrefix != null) {
            $sql .= " LIKE '{$tblPrefix}%'";
        }
        $row = DB::query($sql);
        $size = 0;
        foreach($row as $value) {
            $size += $value["Data_length"] + $value["Index_length"];
        }
        return round(($size/1048576),2).'M';
    }
    

    // 服务器状态
    public function systemstatus(){
        $sysinfo = $this->sys();
    	return view('systemstatus',[
            'sysinfo' => $sysinfo
        ]);
    }


    // 参数配置
    public function param(){
    	return view();
    }

    // 操作日志
    public function log(){
        $map['table_name_en'] = array('neq','hey_article');
        $log = db('log')->alias('l')
            ->join('admin a', 'a.id=l.admin_id','LEFT')
            ->field('l.*,a.username')
            ->where($map)
            ->order('id desc')
            ->paginate(10);
    	return view('log',['log'=>$log]);
    }

    // 修改配置
    public function addparam()
    {
        $data = input('param.');
    	// 网站的基本配置
        $web_str=<<<php
<?php 
return [
    // 网站标题
    'web_title'=>'{$data['web_title']}',
    // 作者
    'web_author'=>'{$data['web_author']}',
    // 域名
    'web_com'=>'{$data['web_com']}',
    // qq
    'web_qq'=>'{$data['web_qq']}',
    // icp
    'web_icp'=>'{$data['web_icp']}',
    // 关键词
    'web_key'=>'{$data['web_key']}',
    // 描述
    'web_des'=>'{$data['web_des']}',
    // 标签
    'web_tag'=>'{$data['web_tag']}',
]; 
php;
// 创建配置文件
file_put_contents('application/extra/web.php', $web_str);        


// 小程序的配置
$wx_str=<<<php
<?php

return [

    // 小程序app_id
    'app_id' => '{$data['app_id']}',
    // 小程序app_secret
    'app_secret' => '{$data['app_secret']}',

    // 微信使用code换取用户openid及session_key的url地址
    'login_url' => "{$data['login_url']}",

    // 微信获取access_token的url地址
    'access_token_url' => "{$data['access_token_url']}",

];
php;
// 创建配置文件
file_put_contents('application/extra/wx.php', $wx_str);



        if (file_put_contents('application/extra/wx.php', $wx_str)) {
            return geekxza('修改成功');
        } else {
            return geekxzb('修改失败');
        }
    }
}