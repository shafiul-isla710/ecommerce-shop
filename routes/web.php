<?php

use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard',[UserController::class,'index'])->name('user.index');
});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin-dashboard',[AdminController::class,'index'])->name('admin.index');
    Route::get('/admin-dashboard/brand',[AdminController::class,'brandPage'])->name('admin.brand');
    Route::get('/admin-dashboard/brand/add',[AdminController::class,'add_brand'])->name('add.brand');
    Route::post('/admin-dashboard/brand/store',[AdminController::class,'store_brand'])->name('store.brand');

    //update brand
    Route::get('/admin-dashboard/brand/edit/{id}',[AdminController::class,'edit_brand'])->name('edit.brand');
    Route::put('/admin-dashboard/brand/update/{id}',[AdminController::class,'update_brand'])->name('update.brand');

    //delete brand
    Route::delete('/admin-dashboard/brand/delete/{id}',[AdminController::class,'delete_brand'])->name('delete.brand');
});
