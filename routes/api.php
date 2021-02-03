<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/ss', 'RegisterController@store');
//Route::any('/products/index', 'ProductsController@index');
Route::get('/line','ChatController@index');
Route::any('/mod/index','ModController@index');
Route::any('/user/index','UserController@index');


Route::middleware('api.refresh')->group(function($router) {
    $router->any('userInfo','ChatController@userInfo');


    $router->get('groupMembers',['uses' => 'ChatController@groupMember']);
    $router->post('joinGroup',['uses' => 'ChatController@joinGroup']);
    $router->post('addFriend',['uses' => 'ChatController@addFriend']);
    $router->post('refuseFriend',['uses' => 'ChatController@refuseFriend']);
    $router->post('createGroup',['uses' => 'ChatController@createGroup']);
    $router->post('messageBox',['uses' => 'ChatController@messageBox']);
    $router->post('updateSign',['uses' => 'ChatController@updateSign']);
    $router->any('chatRecordData',['uses' => 'ChatController@chatRecordData']);



});

//聊天上传图片或文件
Route::post('/upload', 'ChatController@upload');

Route::middleware('cors')->group(function () {
    Route::post('/uploadFile', 'UploadsController@uploadImg');

});

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logOut', 'AuthController@logOut');
    Route::post('refresh', 'AuthController@refresh');
    Route::any('getUser', 'AuthController@getUser');

});


//多个方法公用一个路由
Route::any('products/{action}', function(App\Http\Controllers\ProductsController $Products, $action,Request $request){
    return $Products->$action($request);
});

//Route::group(['middleware' => ['api.auth']], function () {
////    Route::get('/userCenter', 'userCenterController@show');
//    Route::get('/userCenter','userCenterController@show')->middleware('RefreshToken');
//
//});
/**
Route::any('unAuth', function () {
    return response()->json([
        'code'=>4001,
        'msg'=>"token验证失败",
        'data'=>[]
    ]);
})->name('unAuth');
Route::middleware('auth:api')->group(function() {
    Route::post('/logout', 'LoginController@logout');
    Route::get('/userCenter', 'userCenterController@show');
});
**/


