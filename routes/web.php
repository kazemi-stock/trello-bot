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
Route::get('/messages/get', [App\Http\Controllers\Controller::class, 'messages_get'])->name('messages.get');
Route::get('/messages/send', [App\Http\Controllers\Controller::class, 'messages_send'])->name('messages.send');
Route::get('/actions/get', [App\Http\Controllers\Controller::class, 'actions_get'])->name('actions.get');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Contacts
Route::get('/contacts', [App\Http\Controllers\HomeController::class, 'contacts_index'])->name('contacts.index');
Route::get('/contacts/create', [App\Http\Controllers\HomeController::class, 'contacts_create'])->name('contacts.create');
Route::post('/contacts/store', [App\Http\Controllers\HomeController::class, 'contacts_store'])->name('contacts.store');
Route::get('/contacts/edit', [App\Http\Controllers\HomeController::class, 'contacts_edit'])->name('contacts.edit');
Route::put('/contacts/update', [App\Http\Controllers\HomeController::class, 'contacts_update'])->name('contacts.update');
Route::delete('/contacts/delete/{contact}', [App\Http\Controllers\HomeController::class, 'contacts_delete'])->name('contacts.delete');
// Messages
Route::get('/messages', [App\Http\Controllers\HomeController::class, 'messages_index'])->name('messages.index');
// Comments
Route::get('/comments', [App\Http\Controllers\HomeController::class, 'comments_index'])->name('comments.index');
// Cards
Route::get('/cards', [App\Http\Controllers\HomeController::class, 'cards_index'])->name('cards.index');
// Actions
Route::get('/actions', [App\Http\Controllers\HomeController::class, 'actions_index'])->name('actions.index');
// Setting
Route::get('/setting', [App\Http\Controllers\HomeController::class, 'setting_index'])->name('setting.index');
Route::put('/setting/update', [App\Http\Controllers\HomeController::class, 'setting_update'])->name('setting.update');
// Users
Route::get('/users', [App\Http\Controllers\HomeController::class, 'users_index'])->name('users.index');
Route::get('/users/edit', [App\Http\Controllers\HomeController::class, 'users_edit'])->name('users.edit');
Route::put('/users/update', [App\Http\Controllers\HomeController::class, 'users_update'])->name('users.update');

