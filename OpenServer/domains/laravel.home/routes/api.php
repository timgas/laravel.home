<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


/*Route::get('works', [WorkerController::class, 'index']);
Route::post('works', [WorkerController::class, 'store']);
Route::get('works/{worker}', [WorkerController::class, 'show']);
Route::put('works/{worker}', [WorkerController::class, 'update']);
Route::delete('works/{worker}', [WorkerController::class, 'destroy']);*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

//Route::get('user', [UserController::class, 'index'])->middleware('auth.sanctum');

// Authenticated users
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::apiResource('user', UserController::class);
    Route::apiResource('organization', OrganizationController::class);
    Route::apiResource('vacancy', VacancyController::class);/*->middleware('vacancy');*/
    Route::post('vacancy-book', [VacancyController::class, 'book']);
    Route::post('vacancy-unbook', [VacancyController::class, 'un_book']);

});
Route::group(['middleware' => ['auth:sanctum',]], function () {
    Route::group(['prefix' => 'stats'], function () {

        Route::get('vacancy', [StatsController::class, 'indexStatsVacancies']);
        Route::get('user', [StatsController::class, 'indexStatsUsers']);
        Route::get('organization', [StatsController::class, 'indexStatsOrganizations']);
    });
});


