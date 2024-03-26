<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])      ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])  ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth:api')->get('/user', function(Request $request){
    return $request->user();
});

require __DIR__.'/auth.php';

Route::controller(HomeController::class)->group(function(){
    Route::get('/',"renderHome")            ->name("home");
    Route::get('/add',"add")        ->name("product.goToCreate");
    Route::get('/edit/{id}',"edit") ->name("product.goToEdit");
    Route::post('/store',"store")   ->name("product.create");
});

