<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\bangunRumahController;
// use App\Http\Controllers\BeliMaterialController;
// use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\api\cetakPembelianController;
use App\Http\Controllers\api\cetakMaterialController;
use App\Http\Controllers\api\cetakUserController;
use App\Http\Controllers\PembelianController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/BeliMaterialDetail/{id}', [MaterialController::class, 'detailUserBeli']);


Route::post('/pembelian', [PembelianController::class, 'store']);
Route::get('/riwayatpembelian', [PembelianController::class, 'index']);
Route::put('/datapembelian/{id}', [PembelianController::class, 'konfirmasi']);




// Route::post('/BeliMaterial', [BeliMaterialController::class, 'beliMaterial']);
// Route::post('/Materials/create', [MaterialController::class, 'create']);
