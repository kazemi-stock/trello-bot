<?php

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
    if (auth()->user()) {
        return redirect()->route('home');
    }else {
        return redirect()->route('login');
    }
});
Route::get('/getmessage', [App\Http\Controllers\Controller::class, 'message_get'])->name('message.get');
Route::get('/getactions', [App\Http\Controllers\Controller::class, 'actions_get'])->name('actions.get');
Route::get('/send/message', [App\Http\Controllers\Controller::class, 'send_message'])->name('send.message');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

