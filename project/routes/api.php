<?php

use Illuminate\Http\Request;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('foo', function () {

    $databaseName = \DB::connection();//->getDatabaseName();

    dd($databaseName);

    //return database_path("database.sqlite");
});*/

Route::group(['namespace' => 'Api'], function () {

    Route::post('StoreOrder','OrderControlller@StoreOrder');


    Route::get('GetCoupon','OrderControlller@GetCoupon');
    Route::get('OneOrder','OrderControlller@OneOrder');

    //-----------------------------------------
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('slider', 'SettingController@slider');
    Route::get('GetProducts','SettingController@GetProducts');
    Route::get('main-categories', 'CategoriesController@main');
    Route::get('sub-categories', 'CategoriesController@sub');
    Route::get('child-categories', 'CategoriesController@child');
    Route::get('sub-with-child', 'CategoriesController@subWithChild');
    Route::get('offers', 'ProductsController@offers');
    Route::get('GetOtherProducts', 'ProductsController@GetOtherProducts');
    Route::get('feature', 'ProductsController@feature');
    Route::get('best', 'ProductsController@best');
    Route::get('home', 'ProductsController@home');
    Route::get('one-product', 'ProductsController@oneProduct');
    Route::get('get-product-by-name', 'ProductsController@getProductByName');
    Route::get('get-product-by-dep', 'ProductsController@getProductByDep');

    Route::get('get-category-and-sup', 'ProductsController@GetCategoryAndThemSup');
    Route::get('get-product-and-sup', 'ProductsController@GetproductAndThemSup');


//    Route::get('get-top-slider-products','ProductsController@GetTopSliderProducts');
    Route::get('products-by-key','ProductsController@GetProductsByKey');

    Route::get('get-bottom-slider','ProductsController@GetBottomSlider');


    Route::get('top-keys','SettingController@GetTopSliderKeys');



    Route::get('notifications','SettingController@notifications');


    //-----------------------------------------

    /**
     *  ============================================================
     *
     *  ------------------------------------------------------------
     *
     *  ============================================================
     */
    Route::group(['middleware' => "checkheader"], function () {
        //-----------------------------------------
        Route::post('update-firebase', 'AuthController@updateFirebase');
        Route::get('get-profile', 'AuthController@getProfile');
        Route::post('update-profile', 'AuthController@UpdateProfile');
        Route::post('logout', 'AuthController@logout');

        Route::get('my-address', 'AuthController@myAddress');
        Route::post('add-address', 'AuthController@addAddress');
        Route::post('edit-address', 'AuthController@editAddress');
        Route::post('delete-address', 'AuthController@deleteAddress');
        //-----------------------------------------
        Route::get('my-wishlists', 'ProductsController@myWishlists');
        Route::post('action-wishlists', 'ProductsController@actionWishlists');
        //-----------------------------------------


//        ======================= Comments =================================

        Route::post('StoreComment','AuthController@StoreComment');

//        ======================= Menu ===========================
        Route::post('StoreMenu','MenuController@StoreMenu');
        Route::post('DeleteMenu','MenuController@DeleteMenu');
        Route::get('GetMenu','MenuController@GetMenu');


//        ===================== Order ========================
        Route::get('GetOrders','OrderControlller@GetOrders');

    });
    Route::post('StoreContact','AuthController@StoreContact');

});

//Route::get('/updateapp', function () {
//    $bath = base_path();
//    $order = 'php artisan vendor:publish --tag=laravel-errors';
//    return shell_exec("cd {$bath};{$order} ");
//    //   \Artisan::call('dump-autoload');
//    //   echo 'dump-autoload complete';
//});


//Route::get('/apo', function () {
//    //$bath = base_path();
//    //$order = 'php artisan vendor:publish --tag=laravel-errors';
//    //return shell_exec("cd {$bath};{$order} ");
//    //   \Artisan::call('dump-autoload');
//      echo 'dump-autoload complete';
//});


Route::get('paymob','PayMobController@test');
Route::get('paymob-check/{id}','PayMobController@checkingOut');

