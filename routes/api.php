<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ArmadaController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleGalleryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CoverController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\MisiController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProsController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceGalleryController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TravelPermitController;
use App\Http\Controllers\TravelSupplyController;
use App\Http\Controllers\TravelTrackingController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('user');
    Route::get('/users', [AuthController::class, 'index'])->middleware('user');
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user', [AuthController::class, 'user'])->middleware('user');
    Route::get('/user/{id}', [AuthController::class, 'show'])->middleware('admin');
    Route::get('/user/verify/{id}', [AuthController::class, 'verified'])->middleware('admin');
    Route::post('/user/update', [AuthController::class, 'update'])->middleware('user');
    Route::post('/user/update/{id}', [AuthController::class, 'updateById'])->middleware('admin');
    Route::get('/user/delete/{id}', [AuthController::class, 'destroy'])->middleware('admin');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'password'

], function ($router) {
    Route::post('/create', [PasswordResetController::class, 'create']);
    Route::get('/find/{token}', [PasswordResetController::class, 'find']);
    Route::post('/reset', [PasswordResetController::class, 'reset']);
});

Route::group([
    'middleware' => 'api',

], function ($router) {
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verify/resend', [VerifyEmailController::class, 'resend'])->middleware(['throttle:6,1'])->name('verification.send');
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'armada'

], function ($router) {
    Route::post('/create', [ArmadaController::class, 'store'])->middleware('user');
    Route::get('/', [ArmadaController::class, 'index']);
    Route::post('/update/{id}', [ArmadaController::class, 'update'])->middleware('user');
    Route::get('/{id}', [ArmadaController::class, 'show']);
    Route::get('/delete/{id}', [ArmadaController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'team'

], function ($router) {
    Route::post('/create', [TeamController::class, 'store'])->middleware('user');
    Route::get('/', [TeamController::class, 'index']);
    Route::post('/update/{id}', [TeamController::class, 'update'])->middleware('user');
    Route::get('/{id}', [TeamController::class, 'show']);
    Route::get('/delete/{id}', [TeamController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'client'

], function ($router) {
    Route::post('/create', [ClientController::class, 'store'])->middleware('user');
    Route::get('/', [ClientController::class, 'index']);
    Route::post('/update/{id}', [ClientController::class, 'update'])->middleware('user');
    Route::get('/{id}', [ClientController::class, 'show']);
    Route::get('/delete/{id}', [ClientController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'article'

], function ($router) {
    Route::post('/create', [ArticleController::class, 'store'])->middleware('user');
    Route::get('/', [ArticleController::class, 'index']);
    Route::post('/update/{id}', [ArticleController::class, 'update'])->middleware('user');
    Route::get('/{id}', [ArticleController::class, 'show']);
    Route::get('/delete/{id}', [ArticleController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'price'

], function ($router) {
    Route::post('/create', [PriceController::class, 'store'])->middleware('user');
    Route::get('/', [PriceController::class, 'index']);
    Route::post('/update/{id}', [PriceController::class, 'update'])->middleware('user');
    Route::get('/{id}', [PriceController::class, 'show']);
    Route::get('/delete/{id}', [PriceController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'city'

], function ($router) {
    Route::post('/create', [CityController::class, 'store'])->middleware('user');
    Route::get('/', [CityController::class, 'index']);
    Route::post('/update', [CityController::class, 'update'])->middleware('user');
    Route::get('/{id}', [CityController::class, 'show']);
    Route::get('/delete/{id}', [CityController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'contact'

], function ($router) {
    Route::post('/create', [ContactController::class, 'store'])->middleware('user');
    Route::get('/', [ContactController::class, 'index']);
    Route::post('/update/{id}', [ContactController::class, 'update'])->middleware('user');
    Route::get('/{id}', [ContactController::class, 'show']);
    Route::get('/delete/{id}', [ContactController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'aboutUs'

], function ($router) {
    Route::post('/create', [AboutUsController::class, 'store'])->middleware('user');
    Route::get('/', [AboutUsController::class, 'index']);
    Route::post('/update/{id}', [AboutUsController::class, 'update'])->middleware('user');
    Route::get('/{id}', [AboutUsController::class, 'show']);
    Route::get('/delete/{id}', [AboutUsController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'misi'

], function ($router) {
    Route::post('/create', [MisiController::class, 'store'])->middleware('user');
    Route::get('/', [MisiController::class, 'index']);
    Route::post('/update/{aboutUsId}', [MisiController::class, 'update'])->middleware('user');
    Route::get('/{aboutUsId}', [MisiController::class, 'show']);
    Route::get('/delete/{id}', [MisiController::class, 'destroy'])->middleware('user');
    Route::get('/delete/all/{aboutUsId}', [MisiController::class, 'deleteAll'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'service'

], function ($router) {
    Route::post('/create', [ServiceController::class, 'store'])->middleware('user');
    Route::get('/', [ServiceController::class, 'index']);
    Route::post('/update/{id}', [ServiceController::class, 'update'])->middleware('user');
    Route::get('/{id}', [ServiceController::class, 'show']);
    Route::get('/delete/{id}', [ServiceController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'serviceGallery'

], function ($router) {
    Route::post('/create', [ServiceGalleryController::class, 'store'])->middleware('user');
    Route::get('/', [ServiceGalleryController::class, 'index']);
    Route::post('/update/{id}', [ServiceGalleryController::class, 'update'])->middleware('user');
    Route::get('/{id}', [ServiceGalleryController::class, 'show']);
    Route::get('/delete/{id}', [ServiceGalleryController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'articleGallery'

], function ($router) {
    Route::post('/create', [ArticleGalleryController::class, 'store'])->middleware('user');
    Route::get('/', [ArticleGalleryController::class, 'index']);
    Route::post('/update/{id}', [ArticleGalleryController::class, 'update'])->middleware('user');
    Route::post('/update/article/{articleId}', [ArticleGalleryController::class, 'updateByArticleId'])->middleware('user');
    Route::get('/{id}', [ArticleGalleryController::class, 'show']);
    Route::get('/delete/{id}', [ArticleGalleryController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'cover'

], function ($router) {
    Route::post('/create', [CoverController::class, 'store'])->middleware('user');
    Route::get('/', [coverController::class, 'index']);
    Route::post('/update/{id}', [coverController::class, 'update'])->middleware('user');
    Route::get('/{id}', [coverController::class, 'show']);
    Route::get('/delete/{id}', [coverController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'email'

], function ($router) {
    Route::post('/send', [EmailController::class, 'postEmail']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'pros'

], function ($router) {
    Route::post('/create', [ProsController::class, 'store'])->middleware('user');
    Route::get('/', [ProsController::class, 'index']);
    Route::post('/update/{id}', [ProsController::class, 'update'])->middleware('user');
    Route::get('/{id}', [ProsController::class, 'show']);
    Route::get('/delete/{id}', [ProsController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'brand'

], function ($router) {
    Route::post('/create', [BrandController::class, 'store'])->middleware('user');
    Route::get('/', [BrandController::class, 'index']);
    Route::post('/update/{id}', [BrandController::class, 'update'])->middleware('user');
    Route::get('/{id}', [BrandController::class, 'show']);
    Route::get('/delete/{id}', [BrandController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'permit'
], function($router) {
    Route::post('/create', [TravelPermitController::class, 'store'])->middleware('user');
    Route::get('/', [TravelPermitController::class, 'index'])->middleware('user');
    Route::get('/{id}', [TravelPermitController::class, 'show'])->middleware('user');
    Route::post('/{id}', [TravelPermitController::class, 'update'])->middleware('user');
    Route::delete('/{id}', [TravelPermitController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'supply'
], function($router) {
    Route::post('/create', [TravelSupplyController::class, 'store'])->middleware('user');
    Route::get('/{id}', [TravelSupplyController::class, 'show'])->middleware('user');
    Route::delete('/{id}', [TravelSupplyController::class, 'clear'])->middleware('user');
    Route::post('/item/{id}', [TravelSupplyController::class, 'update'])->middleware('user');
    Route::delete('/item/{id}', [TravelSupplyController::class, 'destroy'])->middleware('user');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'tracking'
], function($router) {
    Route::post('/create', [TravelTrackingController::class, 'store'])->middleware('user');
    Route::get('/{id}', [TravelTrackingController::class, 'show'])->middleware('user');
});
