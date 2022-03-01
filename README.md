<p align="center">
    <img width="150" class="QR-img" src="https://oss.geekxz.com/hey-ui-oss/logo.jpg">
</p>

<div align="center">
    <span><a target="_blank" href="#">:memo: 中文文档</a></span>
    <span>|</span>
    <span><a target="_blank" href="#">:computer: 官方教程</a></span>
</div>

<div align="center">
    <span>HeyUI Admin, not just an UI component library!</span><br/>
    <strong>HeyUI Admin 是基于thinkphp5.0开发,开箱即用的中后台解决方案。</strong>
</div>



## 目录

- [目录](#目录)
- [简介](#简介)
- [源码与体验](#源码与体验)
  - [源码地址](#源码地址)
  - [效果展示](#效果展示)
- [主要功能](#主要功能)
- [安装](#安装)
  - [快速安装](#快速安装)
  - [项目结构](#项目结构)
- [讨论交流](#讨论交流)
- [贡献代码](#贡献代码)
- [源码演示](#优秀源码演示)
- [开源协议](#开源协议)
- [赞助](#赞助)


## 简介

  HeyAdmin 是基于基于thinkphp5.0开发,实现权限动态加载路由，渲染侧边栏,面向全层次的PHP开发者,低门槛开箱即用的后台管理系统解决方案.


## 源码与体验

  开源不易，需要鼓励。给项目点个 star吧！

#### 源码地址

  - 后台demo地址：http://admin.geekxz.com
  - Github仓库：https://github.com/geekxz/heyui-admin-tp5.0.git
  - Gitee仓库：https://gitee.com/geek-club/heyui-admin-tp5.0.git

#### 效果展示

![admin-home.png](https://p9-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/8f4e00a91f6648b7802ed440c4f7802d~tplv-k3u1fbpfcp-watermark.image?)
![log.png](https://p3-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/343582496c784a21b1101ec0172a60f1~tplv-k3u1fbpfcp-watermark.image?)
![node.png](https://p6-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/a9f6740978b54b64bb484077166d1599~tplv-k3u1fbpfcp-watermark.image?)
![db.png](https://p3-juejin.byteimg.com/tos-cn-i-k3u1fbpfcp/9b3b7c63ea4547958c3c6422a1586227~tplv-k3u1fbpfcp-watermark.image?)

## 主要功能

- 登录  用户名密码
- 权限  
- 动态路由
- echarts各种图表
- 富文本
- Markdown
- 错误页面 403 404 500
- 个人设置
- 系统设置
- 两种布局方式
- 面包屑
- 标签页
- 返回顶部
- table表
- form表单
- 上传头像
- 用户操作日志
- 数据库备份还原

## 安装

#### 快速安装

直接通过git下载 Hey Admin 源代码，然后直接`http://你的域名地址/install`项目即可。
```
git clone https://gitee.com/geek-club/heyui-admin-tp5.0.git
```
例如：网站域名是`http://www.geekxz.com`，配置好网站运行环境以后，直接浏览器输入`http://www.geekxz.com/install`,然后根据提示信息一步步安装即可。


**超级管理员**：获取账号密码请关注微信公众号`极客小寨工作室`回复关键词`后台账号`

#### 项目结构

```
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录（可以更改）
│  ├─admin            后台模块目录
│  │  ├─config.php      模块配置文件
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  ├─api            接口模块目录
│  │  ├─config.php      模块配置文件
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─service         业务层目录
│  │  ├─validate        验证器目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  ├─extra            配置目录
│  │  ├─web.php         配置文件
│  │  └─ ...            更多类库目录
│  ├─lib            其他类库目录
│  │  ├─enum            枚举文件目录
│  │  ├─exception       异常文件目录
│  │  └─ ...            更多类库目录
│  │
│  ├─command.php        命令行工具配置文件
│  ├─common.php         公共函数文件
│  ├─config.php         公共配置文件
│  ├─route.php          路由配置文件
│  ├─tags.php           应用行为扩展定义文件
│  └─database.php       数据库配置文件
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  ├─phpunit.xml        phpunit配置文件
│  └─start.php          框架入口文件
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─index.php             网站入口文件
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
```


## 讨论交流

微信群：加入微信群请先添加开发者微信，备注HeyUI。QQ群：689112212 或扫描二维码。

<p align="center">
    <img width="800" src="https://oss.geekxz.com/hey-ui-oss/communication_primary.png">
</p>

## 贡献代码

我们的代码基于 develop 分支开发，欢迎提交 Pull Request 进行代码贡献。

在提交 Pull Request 之前，请详细阅读我们的[开发规范](http://heyui.geekxz.com/start/contribute.html)，否则可能因为 Commit 信息不规范等原因被关闭 Pull Request。


## 开源协议

[MIT](LICENSE) © 2021  PP_Team


## 赞助

![](https://oss.geekxz.com/hey-ui-oss/communications.png)

| 时间        	| 名称          | 金额       | 留言  		    |
| ------------- |:-------------:| :---------:|:-------------|
| 2021-03-01    |李*木          | 50元       |感谢
| 2021-03-01    |李*木			    | 20元 	     |感谢