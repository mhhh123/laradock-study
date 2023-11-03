<?php


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Wx\CouponController;
use App\Http\Controllers\Wx\BrandController;
use App\Http\Controllers\Wx\AuthController;
use App\Http\Controllers\Wx\CatalogController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wx\AddressController;
use App\Http\Controllers\Wx\GrouponController;
use App\Http\Controllers\Wx\CartController;
use App\Http\Controllers\Wx\OrderController;



Route::get('/', function () {
    return view('welcome');
});

Route::prefix('t')->group(function () {
    Route::get('db', function () {
        dump(DB::select('select version()'));
        dd('db end');
    });
});


Route::get('home/index', [\App\Http\Controllers\Wx\HomeController::class, 'index']);
Route::get('home/redirectShareUrl', [\App\Http\Controllers\Wx\HomeController::class, 'redirectShareUrl'])->name('home.redirectShareUrl');
Route::get('home/test', [\App\Http\Controllers\Wx\HomeController::class,'test']);

// 专题
Route::get('topic/detail', [\App\Http\Controllers\Wx\TopicController::class,'detail']); // 专题详情
Route::get('topic/related', [\App\Http\Controllers\Wx\TopicController::class,'related']); // 相关专题

Route::get('user/index',[\App\Http\Controllers\Wx\UserController::class,'index']);
//购物车
Route::get('cart/index',[CartController::class,'index']);
Route::post('cart/add',[CartController::class,'add']);
Route::post('cart/fastadd',[CartController::class,'fastAdd']);
Route::post('cart/update',[CartController::class,'update']);
Route::post('cart/delete',[CartController::class,'delete']);
Route::post('cart/checked',[CartController::class,'checked']);
Route::get('cart/goodscount',[CartController::class,'goodsCount']);
Route::get('cart/checkout',[CartController::class,'checkout']);

//团购
Route::get('group/list',[GrouponController::class,'list']);

//优惠卷
Route::get('coupon/list',[CouponController::class,'list']);
Route::get('coupon/mylist',[CouponController::class,'mylist']);
Route::post('coupon/receive',[CouponController::class,'receive']);
//Route::any('coupon/selectlist');

//商品模块—品牌
Route::get('brand/detail',[BrandController::class,'detail']);
Route::get('brand/list',[BrandController::class,'list']);

Route::get('goods/count',[App\Http\Controllers\Wx\GoodsController::class,'count']);
Route::get('goods/category',[App\Http\Controllers\Wx\GoodsController::class,'category']);
Route::get('goods/list',[App\Http\Controllers\Wx\GoodsController::class,'list']);
Route::get('goods/detail',[App\Http\Controllers\Wx\GoodsController::class,'detail']);
Route::get('goods/related',[\App\Http\Controllers\Wx\GoodsController::class,'related']);

//商品模块-类目
Route::get('catalog/index',[CatalogController::class,'index']);
Route::get('catalog/current',[CatalogController::class,'current']);

//用户模块-地址
Route::any('address/list',[AddressController::class,'list']);//*
Route::post('address/detail',[AddressController::class,'detail']);//*
Route::post('address/save',[AddressController::class,'save']);//*
Route::post('address/delete',[AddressController::class,'delete']);//*

Route::get('auth/info', [AuthController::class,'info']);
Route::post('auth/login', [AuthController::class,'login']);
Route::post('auth/regCaptcha', [AuthController::class,'regCaptcha']);
Route::post('auth/register', [AuthController::class, 'register']);
Route::get('/test', [Controller::class,'dbTest']);

Route::post('auth/logout', [AuthController::class,'logout']);//?
Route::post('auth/profile', [AuthController::class,'profile']);
Route::post('auth/reset', [AuthController::class,'reset']);
Route::post('auth/captcha', [AuthController::class,'captcha']);//?

//订单
Route::post('order/submit',[OrderController::class,'submit']);//*
Route::post('order/cancel',[OrderController::class,'cancel']);//*
Route::get('order/detail',[OrderController::class,'detail']);//*

Route::get('auth/user',[AuthController::class,'user']);

Route::group([

    'middleware' => 'wx',
    'prefix' => 'api'

], function ($router) {

    Route::get('login', [\App\Http\Controllers\ApiController::class,'login']);
    Route::post('logout', [\App\Http\Controllers\ApiController::class,'logout']);
    Route::post('refresh', [\App\Http\Controllers\ApiController::class,'refresh']);
    Route::post('me', [\App\Http\Controllers\ApiController::class,'me']);

});

