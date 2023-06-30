<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\BrandController;

use App\Http\Controllers\API\Chowhub\ChowhubProductController;
use App\Http\Controllers\API\Chowhub\ChowhubCategoryController;
use App\Http\Controllers\API\Chowhub\ChowhubStoreController;
use App\Http\Controllers\API\Chowhub\ChowhubCartController;

use App\Http\Controllers\API\Litterhub\LitterhubStoreController;
use App\Http\Controllers\API\Litterhub\LitterhubProductController;
use App\Http\Controllers\API\Litterhub\LitterhubCartController;
use App\Http\Controllers\API\Litterhub\LitterhubFaqController;

use App\Http\Controllers\API\Solutionhub\SolutionhubProductController;




use App\Http\Controllers\API\PageController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\FaqController;
use App\Http\Controllers\API\RatingController;
use App\Http\Controllers\API\PetController;
use App\Http\Controllers\API\TestController;


use App\Http\Controllers\API\Chowhub\ChowhubRatingController;





use App\Http\Controllers\API\StoreController;
use App\Http\Middleware\EnsureApiTokenIsValid;
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


Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::post('oauth/token', [PassportAuthController::class, 'oauth_token']);
Route::get('products', [ProductController::class, 'index']);
Route::middleware([EnsureApiTokenIsValid::class])->group(function () {
  Route::get('categories', [CategoryController::class, 'index']);
  Route::any('categories/{id}', [CategoryController::class, 'category_by_id']);

  Route::any('products/{id}', [ProductController::class, 'productById']);
  Route::any('products/category/{id}', [ProductController::class, 'productByCategoryId']);
  Route::get('products/attributes/{id}', [ProductController::class, 'getAttributeByProduct']);

  Route::any('chowhub/products/{id}', [ChowhubProductController::class, 'productById']);
  Route::any('chowhub/products/category/{id}', [ChowhubProductController::class, 'productByCategoryId']);
  Route::get('chowhub/products/attributes/{id}', [ChowhubProductController::class, 'getAttributeByProduct']);
  Route::get('chowhub/categories', [ChowhubCategoryController::class, 'index']);
  Route::any('chowhub/categories/{id}', [ChowhubCategoryController::class, 'category_by_id']);
//litterhub
  Route::get('litterhub/products', [LitterhubProductController::class, 'index']);
  Route::any('litterhub/products/{id}', [LitterhubProductController::class, 'productById']);
  Route::get('litterhub/products/attributes/{id}', [LitterhubProductController::class, 'getAttributeByProduct']);
//solutionhub

  Route::get('pages', [PageController::class, 'index']);
  Route::any('pages/{id}', [PageController::class, 'pageByID']);


});
Route::get('chowhub/products', [ChowhubProductController::class, 'index']);
Route::get('solutionhub/categories', [CategoryController::class, 'solution_category']);
Route::any('solutionhub/categories/{id}', [CategoryController::class, 'solutionhubcategory_by_id']);
Route::get('solutionhub/products', [SolutionhubProductController::class, 'index']);
Route::any('solutionhub/products/{id}', [SolutionhubProductController::class, 'productById']);

Route::get('solutionhub/tags', [SolutionhubProductController::class, 'allTags']);
Route::get('chowhub/tags', [ChowhubProductController::class, 'allTags']);
Route::get('litterhub/tags', [LitterhubProductController::class, 'allTags']);
Route::middleware('auth:api')->group(function ()
{
    Route::get('profile', [UserController::class, 'userProfile']);
    Route::post('update', [UserController::class, 'updateProfile']);
    Route::get('logout', [PassportAuthController::class, 'logout']);

    Route::group(['middleware' => ['role:User']], function ()
    {

    });
    Route::resource('order', OrderController::class);
});
Route::get('brand/{id}', [BrandController::class, 'index']);
Route::get('brand/product/{id}', [BrandController::class, 'productByBrand']);


Route::resource('cart', CartController::class);
Route::get('cartIdByKey', [CartController::class, 'getCartIDUsingKey']);
Route::delete('cart/{cart}/{itemId}', [CartController::class, 'deleteCartItem']);
Route::post('/cart/{cart}',[CartController::class, 'addProducts']);
Route::post('/cart/update/{cart}',[CartController::class, 'updateProducts']);

Route::post('/checkout/{cart}',[CartController::class, 'checkout']);
Route::post('/payment',[CartController::class, 'payment']);

Route::get('settings', [SettingController::class, 'index']);
Route::post('coupon', [CouponController::class, 'index']);

Route::get('stores', [StoreController::class, 'index']);
Route::get('faq/{id}', [FaqController::class, 'index']);
Route::get('faq/{id}/{string}', [FaqController::class, 'getFaqByString']);
Route::get('chowhub/faq/{id}/{string}', [FaqController::class, 'getChowhubFaqByString']);

Route::post('faq/store', [FaqController::class, 'store']);
Route::get('chowhub/faq/{id}', [FaqController::class, 'chouhubIndex']);
Route::post('chowhub/faq/store', [FaqController::class, 'chouhubStore']);
Route::get('litterhub/faq/{id}', [LitterhubFaqController::class, 'index']);
Route::post('litterhub/faq/store', [LitterhubFaqController::class, 'store']);
Route::post('rating/create', [RatingController::class, 'create']);
Route::get('rating/overall/{id}', [RatingController::class, 'getOverallRating']);

Route::get('rating/{id}', [RatingController::class, 'index']);


Route::post('chowhub/rating/create', [ChowhubRatingController::class, 'create']);
Route::get('chowhub/rating/{id}', [ChowhubRatingController::class, 'index']);
Route::get('chowhub/rating/overall/{id}', [ChowhubRatingController::class, 'getOverallRating']);

Route::get('stores/{store}', [StoreController::class, 'show']);
Route::get('chowhub/stores', [ChowhubStoreController::class, 'index']);
Route::get('chowhub/stores/{store}', [ChowhubStoreController::class, 'show']);
Route::get('pet', [PetController::class, 'index']);
Route::post('pet/create', [PetController::class, 'store']);

Route::resource('chowhub/cart', ChowhubCartController::class, ['as' => 'chowhubcart']);
Route::get('/chowhub/cartIdByKey', [ChowhubCartController::class, 'getCartIDUsingKey']);
Route::delete('/chowhub/cart/{cart}/{itemId}', [ChowhubCartController::class, 'deleteCartItem']);
Route::post('/chowhub/cart/{cart}',[ChowhubCartController::class, 'addProducts']);
Route::post('/chowhub/checkout/{cart}',[ChowhubCartController::class, 'checkout']);
Route::post('/chowhub/cartUpdate/{cart}/{itemId}', [ChowhubCartController::class, 'updateCartItem']);

Route::get('/payments',[TestController::class, 'index']);
//litterhub
Route::get('litterhub/stores', [LitterhubStoreController::class, 'index']);
Route::get('litterhub/stores/{store}', [LitterhubStoreController::class, 'show']);
Route::resource('litterhub/cart', LitterhubCartController::class, ['as' => 'litterhubcart']);
Route::get('/litterhub/cartIdByKey', [LitterhubCartController::class, 'getCartIDUsingKey']);
Route::delete('/litterhub/cart/{cart}/{itemId}', [LitterhubCartController::class, 'deleteCartItem']);
Route::post('/litterhub/cartUpdate/{cart}/{itemId}', [LitterhubCartController::class, 'updateCartItem']);

Route::post('/litterhub/cart/{cart}',[LitterhubCartController::class, 'addProducts']);
Route::post('/litterhub/checkout/{cart}',[LitterhubCartController::class, 'checkout']);


