<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
   
    Route::get('/dashboard', function () { 
        if(Auth::user()->isAdmin()){
            return redirect('/admin');
        }else{
            return redirect('/dashboard');
        }
    })->name('dashboard');
    Route::get('/user-dashboard', function () { return view('dashboard');})->name('user.dashboard');
});
