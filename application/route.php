<?php
/**
 * Created by 陈东东
 * Author: 陈东东	<1182929304@qq.com>
 * 微信公号: 极客小寨工作室
 * Date: 2019/4/15
 * Time: 22:40
 */

use think\Route;

//Banner
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

// Category
// 所有分类接口
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

// 文章列表
Route::get('api/:version/article/getArticlesList', 'api/:version.Article/getArticlesList');
// 文章详情
Route::get('api/:version/article/getArticleDetail/:id', 'api/:version.Article/getArticleDetail');
// 精选文章列表
Route::get('api/:version/article/getChoiceArticlesList', 'api/:version.Article/getChoiceArticlesList');
// 分类文章分页接口 未使用
Route::get('api/:version/article/by_category/paginate', 'api/:version.Article/getByCategory');


