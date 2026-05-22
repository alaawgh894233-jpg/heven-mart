<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CacheController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreFollowerController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AttributeOptionController;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get(' ',[StoreController::class,'getAddressFromCoordinates']);

Route::post('/register', [AuthController::class, 'register']);
//    ->middleware('throttle:5,1');
Route::post('verify-otp', [AuthController::class , 'verifyByOtp']);
Route::post('resend-otp', [AuthController::class , 'resendOTP']);
Route::post('login' , [AuthController::class , 'login']);
Route::post('forget-password' , [AuthController::class , 'forgetPassword']);
Route::post('verifyPassword', [AuthController::class , 'verifyPassword']);
Route::post('reset-password' , [AuthController::class , 'resetPassword']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout' , [AuthController::class , 'logout']);
    Route::post('change-password' , [AuthController::class , 'changePassword']);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('stores', [StoreController::class , 'create']);
    Route::post('stores/{id}', [StoreController::class , 'update']);
    Route::get('/stores/myStore', [StoreController::class , 'myStore']);
    Route::get('stores/{id}/suspend', [StoreController::class , 'suspend']);//admin
    Route::get('stores/{id}/approve', [StoreController::class , 'approve']);//admin
});
Route::get('stores/{id}/showForUser', [StoreController::class , 'showForUser']);
Route::get('stores/{id}/showForAdmin', [StoreController::class , 'showForAdmin']);
Route::get('stores/getAllForAdmin', [StoreController::class , 'getAllForAdmin']);
Route::get('stores/getAllForUser', [StoreController::class , 'getAllForUser']);

Route::get('getallproductsjfdytd', [ProductController::class , 'getAllProducts']);



Route::prefix('categories')->group(function () {
    Route::post('/', [CategoryController::class, 'create']);
    Route::post('/{category}', [CategoryController::class, 'update']);
    Route::get('/{category}/available-parents', [CategoryController::class, 'getAvailableParentsForUpdateCategory']);
    Route::delete('/{category}', [CategoryController::class, 'delete']);
    Route::get('/{category}', [CategoryController::class, 'show']);
    Route::get('/', [CategoryController::class, 'getAllForAdmin']);
    Route::get('/leaves/get', [CategoryController::class, 'getLeafCategories']);
    Route::get('/parents/get', [CategoryController::class, 'getParents']);
    Route::get('/{category}/children', [CategoryController::class, 'getChildren']);
    Route::get('/{category}/siblings', [CategoryController::class, 'getSiblingLeafCategories']);
});

Route::prefix('attributes')->group(function () {
    Route::get('/available', [AttributeController::class, 'availableAttributes']);
    Route::get('/{attribute}', [AttributeController::class, 'showAttribute']);
    Route::post('/', [AttributeController::class, 'store']);
    Route::post('/{attribute}', [AttributeController::class, 'update']);
    Route::delete('/{attribute}', [AttributeController::class, 'destroy']);

    Route::post('/{attribute}/values', [AttributeController::class, 'addValue']);
    Route::get('/values/{attributeOption}', [AttributeController::class, 'showValue']);
    Route::post('/values/{attributeOption}', [AttributeController::class, 'editValue']);
    Route::delete('/values/{attributeOption}', [AttributeController::class, 'removeValue']);
});


Route::prefix('brands')->group(function () {
    Route::get('/', [BrandController::class, 'indexAdmin']);
    Route::post('/', [BrandController::class, 'store']);

    Route::post('/{brand}/update', [BrandController::class, 'update']);
    Route::post('/{brand}/toggle-status', [BrandController::class, 'toggleStatus']);

    Route::delete('/{brand}', [BrandController::class, 'destroy']);
    Route::get('/{brand}', [BrandController::class, 'show']);
});
Route::get('public/brands', [BrandController::class, 'indexUser']);




//Route::post('/attributes', [\App\Http\Controllers\AttributeController::class, 'store']);
Route::post('products/{product}', [ProductController::class, 'edit']);
Route::post('products/add/f', [ProductController::class, 'add']);
Route::get('products/get', [ProductController::class, 'showAllProducts']);
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('favorite/{id}', [FavoriteController::class, 'add']);
    Route::delete('favorite/{id}', [FavoriteController::class, 'remove']);
    Route::get('favorites', [FavoriteController::class, 'view']);

    Route::post('products/{productId}/images', [ProductController::class, 'addImage']);
    Route::get('products/{productId}/images', [ProductController::class, 'images']);


    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

   Route::post('products/{id}/demand-feature', [ProductController::class, 'demandFeature']);
//    Route::post('attributes/{id}', [ProductController::class, 'addAttribute']);
//    Route::post('attribute-values', [ProductController::class, 'addAttributeValue']);
//

    Route::post('searchProducts',[ProductController::class,'search']);

    Route::get('admin/products', [ProductController::class, 'adminIndex']);
    Route::post('approve-feature/{id}', [ProductController::class, 'approveFeature']);
    Route::post('block/{id}', [ProductController::class, 'block']);
    Route::post('unblock/{id}', [ProductController::class, 'unblock']);
    Route::post('reviews/{id}', [ProductController::class, 'addReview']);
    Route::get('reviews/{id}', [ProductController::class, 'getReviews']);

    Route::post('profile', [ProfileController::class, 'create_profile']);
    Route::put('profile', [ProfileController::class, 'update_profile']);
    Route::get('profile', [ProfileController::class, 'show_profile']);



    Route::get('addresses', [AddressController::class, 'index']);
    Route::get('addresses/{id}', [AddressController::class, 'show']);
    Route::post('addresses', [AddressController::class, 'store']);
    Route::put('addresses/{id}', [AddressController::class, 'update']);
    Route::delete('addresses/{id}', [AddressController::class, 'destroy']);

    Route::post('stores/follow/{storeId}', [StoreFollowerController::class, 'follow']);
    Route::delete('stores/unfollow/{storeId}', [StoreFollowerController::class, 'unfollow']);
//    Route::get('stores/followers', [StoreFollowerController::class, 'view']);
});
Route::middleware([
    'auth:sanctum',
    'logging.aspect'
])->group(function () {

    // orders
    Route::get('orders', [OrderController::class, 'index']);

    Route::post('orders/{mode}', [OrderController::class, 'store']);

    Route::post('orders/compare', [OrderController::class, 'compare']);

    Route::post('process-sync', [OrderController::class, 'processWithoutBatch']);

    Route::post('batch', [OrderController::class, 'batchProcess']);

    Route::get('batch/{batchId}/status', [OrderController::class, 'batchStatus']);

    Route::put('orders/{order}', [OrderController::class, 'update']);

    Route::delete('orders/{order}', [OrderController::class, 'destroy']);

    Route::get('vendor/orders', [OrderController::class, 'vendorOrders']);

    // Route::post('orders/{order}/approve',
    //     [OrderController::class, 'approve']);
});
Route::middleware('auth:sanctum')->prefix('cart')->controller(CartController::class)->group(function () {
    Route::post('/addToCart/{productId}', [CartController::class, 'addToCart']);
    Route::delete('/deleteFromCart/{productId}', [CartController::class, 'deleteFromCart']);
    Route::put('/plusOne/{productId}', [CartController::class, 'plusOne']);
    Route::put('/minusOne/{productId}', [CartController::class, 'minusOne']);
    Route::get('/getCart', [CartController::class, 'getCart']);
    Route::delete('/clearCart', [CartController::class, 'clearCart']);
});

Route::get('testNotification', function () {
    $factory = (new Factory)->withServiceAccount(storage_path('app/nourexpress-61921-254e421d0d92.json'));
    $messaging = $factory->createMessaging();
    return response()->json($messaging);
});

Route::post('/send-notification', function(Request $request) {

    $token = $request->input('token'); // توكن الجهاز المستلم للإشعار
    $title = $request->input('title', 'عنوان الإشعار');
    $body = $request->input('body', 'محتوى الإشعار');

    // إنشاء الفاكتوري مع مسار ملف الخدمة json
    $factory = (new Factory)->withServiceAccount(storage_path('app/nourexpress-61921-254e421d0d92.json'));

    // الحصول على خدمة الرسائل
    $messaging = $factory->createMessaging();

    // إنشاء الإشعار
    $notification = Notification::create($title, $body);

    // إنشاء رسالة السحابة مع التوكن والإشعار
    $message = CloudMessage::withTarget('token', $token)
        ->withNotification($notification);

    try {
        $messaging->send($message);
        return response()->json(['status' => 'success', 'message' => 'Notification sent']);
    } catch (\Throwable $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});

//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('attributes/', [AttributeController::class, 'index']);
//    Route::post('attributes/', [AttributeController::class, 'store']);
//    Route::put('attributes/{id}', [AttributeController::class, 'update']);
//    Route::delete('attributes/{id}', [AttributeController::class, 'destroy']);
//
//    Route::post('addattributes/values', [AttributeController::class, 'addValue']);
//    Route::get('attributes/value/pending', [AttributeController::class, 'pendingValues']);
//    Route::put('attributes/value/approve/{id}', [AttributeController::class, 'approveValue']);
//    Route::delete('attributes/value/{id}', [AttributeController::class, 'deleteValue']);
//    Route::get('attributes/{attributeId}/values', [AttributeController::class, 'approvedValues']);
//
//

Route::post('/test-order', function (Request $request) {

    $user = auth()->user();

    Log::info('REQUEST START', [
        'user_id' => $user?->id,
        'email' => auth()->user()->email,
        'time' => microtime(true),
    ]);


});

Route::get('/check-auth', function () {
    return [
        'user' => auth()->user(),
        'check' => auth()->check(),
    ];
});
//});

Route::post('/load-test', function () {
    return response()->json([
        'server' => $_SERVER['SERVER_PORT'],
        'time' => microtime(true)
    ]);
});









Route::get('/no-cache/top-products', [TestController::class, 'topProductsNoCache']);
Route::get('/no-cache/daily-report', [TestController::class, 'dailyReportNoCache']);
Route::get('/no-cache/product/{id}', [TestController::class, 'productNoCache']);

Route::get('/cache/top-products', [TestController::class, 'topProductsCache']);
Route::get('/cache/daily-report', [TestController::class, 'dailyReportCache']);
Route::get('/cache/product/{id}', [TestController::class, 'productCache']);


Route::get('/clear-cache', [TestController::class, 'clearCache']);
