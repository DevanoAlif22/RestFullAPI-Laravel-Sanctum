<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// perlu juga mengedit route login jika user mencoba akses tanpa token
Route::get('/',function(){
    return response()->json(
        [
            'success' => false,
            'message' => 'Tidak di izinkan mengakses. Pastikan role sudah benar atau token tidak kadaluarsa',

        ],401
    );
})->name('login');


// hanya yang dapat token yang bisa akses dengan menambahkan middleware sanctum

// yang bisa mengakses get product adalah yang mempunyai token dengan ability product-list
Route::get('/product', [ProductController::class, 'index'])->middleware('auth:sanctum', 'ability:user,admin');

Route::post('/product', [ProductController::class, 'storeProduct'])->middleware('auth:sanctum', 'ability:admin');
Route::patch('/product/{id}', [ProductController::class, 'updateProduct'])->middleware('auth:sanctum', 'ability:admin');
Route::delete('/product/{id}', [ProductController::class, 'destroyProduct'])->middleware('auth:sanctum', 'ability:admin');

// ini semisal : yang bisa mengakses post product adalah semua yang mempunyai token
// Route::post('/product', [ProductController::class, 'storeProduct'])->middleware('auth:sanctum');

Route::post('/register/user',[AuthController::class,'registerUser']);
Route::post('/login/user',[AuthController::class,'loginUser']);

