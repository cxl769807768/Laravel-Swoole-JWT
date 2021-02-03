<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\UploadsController;
use Illuminate\Support\Facades\Route;

//Route::group(['middleware'=>'login'], function () use($router) {
//    $router->get('/',['uses' => 'IndexController@index']);
//    $router->get('/message_box',['uses' => 'IndexController@messageBox']);
//    $router->get('/chat_log',['uses' => 'IndexController@chatLog']);
//    $router->get('/userinfo',['uses' => 'UserController@userinfo']);
//    $router->post('/add_friend',['uses' => 'UserController@addFriend']);

//    $router->get('/group_members',['uses' => 'GroupController@groupMember']);
//    $router->post('/join_group',['uses' => 'GroupController@joinGroup']);
//    $router->get('/create_group',['uses' => 'GroupController@createGroup']);
//    $router->post('/create_group',['uses' => 'GroupController@createGroup']);
//    $router->post('/refuse_friend',['uses' => 'UserController@refuseFriend']);
//    $router->post('/update_sign',['uses' => 'UserController@updateSign']);
//    $router->get('/loginout',['uses' => 'IndexController@loginOut']);
//    $router->get('/chat_record_data',['uses' => 'IndexController@chatRecordData']);
//
//
//});
//$router->get('/login',['uses' => 'IndexController@login']);
//$router->post('/login',['uses' => 'IndexController@login']);
//
//$router->post('/register',['uses' => 'IndexController@register']);
//$router->post('/upload',['uses' => 'IndexController@upload']);
//$router->get('/image_code',['uses' => 'IndexController@imageCode']);



Route::get('/', 'AuthWebController@index');


Route::get('/register', 'AuthWebController@registerShow');
Route::get('/login', 'AuthWebController@loginShow');
//验证码
Route::get('/image_code', 'AuthWebController@verifyCode');

//查找
Route::get('/find',['uses' => 'AuthWebController@find']);
//聊天记录
Route::get('/chatLog',['uses' => 'AuthWebController@chatLog']);
//创建群页面
Route::get('/createGroupShow',['uses' => 'AuthWebController@createGroupShow']);

//消息盒子
Route::get('/messageBox',['uses' => 'AuthWebController@messageBox']);






