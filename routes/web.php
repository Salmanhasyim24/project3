<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\AboutController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\FeatureController;
use App\Http\Controllers\Backend\PortfolioController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\ProfileController;
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
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

 Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/logout', [ AdminController::class, 'adminlogout'])->name('admin.logout');
    Route::get('/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('update.password');

 Route::controller(BannerController::class)->group(function(){
    Route::get('/edit/banner' , 'edit')->name('banner');
    Route::post('/update/banner/{id}' , 'update')->name('update.banner');

    });
    Route::controller(AboutController::class)->group(function(){
        Route::get('/all/about' , 'index')->name('about');
        Route::get('/create/about' , 'create')->name('add.about');
        Route::post('/store/about' , 'store')->name('store.about');
        Route::get('/edit/about/{id}' , 'edit')->name('edit.about');
        Route::post('/update/about' , 'update')->name('update.about');
        Route::get('/delete/about/{id}' , 'destroy')->name('delete.about');
    });
    Route::controller(ServiceController::class)->group(function(){
        Route::get('/all/service' , 'index')->name('service');
        Route::get('/create/service' , 'create')->name('add.service');
        Route::post('/store/service' , 'store')->name('store.service');
        Route::get('/edit/service/{id}' , 'edit')->name('edit.service');
        Route::post('/update/service' , 'update')->name('update.service');
        Route::get('/delete/service/{id}' , 'destroy')->name('delete.service');
    });
    Route::controller(FeatureController::class)->group(function(){
        Route::get('/all/feature' , 'index')->name('feature');
        Route::get('/create/feature' , 'create')->name('add.feature');
        Route::post('/store/feature' , 'store')->name('store.feature');
        Route::get('/edit/feature/{id}' , 'edit')->name('edit.feature');
        Route::post('/update/feature' , 'update')->name('update.feature');
        Route::get('/delete/feature/{id}' , 'destroy')->name('delete.feature');
    });
    Route::controller(PortfolioController::class)->group(function(){
        Route::get('/all/portfolio' , 'index')->name('portfolio');
        Route::get('/create/portfolio' , 'create')->name('add.portfolio');
        Route::post('/store/portfolio' , 'store')->name('store.portfolio');
        Route::get('/edit/portfolio/{id}' , 'edit')->name('edit.portfolio');
        Route::post('/update/portfolio' , 'update')->name('update.portfolio');
        Route::get('/delete/portfolio/{id}' , 'destroy')->name('delete.portfolio');
    });
    Route::controller(CategoryController::class)->group(function(){
        Route::get('/all/category' , 'index')->name('category');
        Route::get('/create/category' , 'create')->name('add.category');
        Route::post('/store/category' , 'store')->name('store.category');
        Route::get('/edit/category/{id}' , 'edit')->name('edit.category');
        Route::post('/update/category' , 'update')->name('update.category');
        Route::get('/delete/category/{id}' , 'destroy')->name('delete.category');
    });
});





require __DIR__.'/auth.php';
