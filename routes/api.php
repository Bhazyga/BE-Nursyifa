<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\bangunRumahController;
// use App\Http\Controllers\BeliMaterialController;
// use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\Api\cetakPembelianController;
use App\Http\Controllers\Api\cetakMaterialController;
use App\Http\Controllers\Api\cetakUserController;
use App\Http\Controllers\PembelianController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarangkeluarController;
use App\Http\Controllers\Api\TransaksiController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/users',UserController::class);
});

Route::post('/index', [AuthController::class,'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('cetak-user', [cetakUserController::class, 'cetakLaporanUser']); //Laporan User
Route::get('cetak-material', [cetakMaterialController::class, 'cetakLaporanMaterial']);
Route::get('cetak-pembelian', [cetakPembelianController::class, 'cetakLaporanPembelian']);
Route::post('/bangunrumah', [bangunRumahController::class,'bangunrumah']);
Route::apiResource('/materials', MaterialController::class);
Route::get('/materials/{id}', [MaterialController::class, 'show']);
Route::put('/materials/{id}', [MaterialController::class, 'update']);

Route::post('/barangkeluar', [BarangkeluarController::class, 'store']);
Route::get('/barangkeluar', [BarangkeluarController::class, 'index']);
Route::delete('/barangkeluar/{id}', [BarangkeluarController::class, 'destroy']);


Route::get('/BeliMaterialDetail/{id}', [MaterialController::class, 'detailUserBeli']);


Route::post('/pembelian', [PembelianController::class, 'store']);
Route::get('/riwayatpembelian', [PembelianController::class, 'index']);
Route::put('/datapembelian/{id}', [PembelianController::class, 'destroy']);


Route::get('/transaksi', [TransaksiController::class,'index']);
Route::post('/transaksi', [TransaksiController::class,'store']);
Route::delete('/transaksi/{id}', [TransaksiController::class,'destroy']);


// Route::post('/BeliMaterial', [BeliMaterialController::class, 'beliMaterial']);
// Route::post('/Materials/create', [MaterialController::class, 'create']);
