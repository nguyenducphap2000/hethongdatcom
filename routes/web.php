<?php

use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\Client\TicketController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\Client\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::user()){
        return back();
    }
    return view('auth.login');
});

Auth::routes();
//other route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::fallback(function(){
    return back();
});

//User
Route::get('user', [UserController::class, 'index'])->middleware('auth');

//Login
Route::post('logged', [UserController::class, 'login']);

//Admin
Route::group(['prefix' => 'admin'],function(){
    Route::group(['middleware' => 'auth'], function () {
        Route::match(['get','post'],'/',[StatisticController::class,'index'])->name('StatisticIndex');
        
        //Statistic day and month
        Route::group(['prefix' => 'report'],function(){
            Route::get('/month/{dmy?}', [StatisticController::class, 'StatisticByMonth'])->name('StatisticByMonth');
            Route::get('/day/{day?}',[StatisticController::class,'StatisticByDay'])->name('StatisticByDay');
            // Export file 
            Route::get('/export/{dmy}',[StatisticController::class,'ReportExport'])->name('ReportExport');
        });

        // handled price in admin
        Route::get('/prices',[PriceController::class, 'index'])->name('pricesIndex');
        Route::post('/prices/store', [PriceController::class, 'store'])->name('pricesStore');
        Route::put('/prices/update/{id}', [PriceController::class, 'update'])->name('pricesUpdate');
        Route::delete('/prices/{id}', [PriceController::class, 'destroy'])->name('pricesDelete');

        
    });
});

//Tickets
Route::group(['prefix' => 'tickets'],function(){
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [TicketController::class, 'index'])->name('ticketsIndex');
        Route::post('/update', [TicketController::class, 'update'])->name('ticketsUpdate');
        Route::post('/show', [TicketController::class, 'show'])->name('ticketsShow');
    });
});

//Profile
Route::group(['prefix' => 'profile'],function(){
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profileIndex');
        Route::put('/update', [ProfileController::class, 'update'])->name('profileUpdate');
    });
});

