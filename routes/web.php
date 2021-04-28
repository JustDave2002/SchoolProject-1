<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackFormController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\UserController;
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
/*
 *
 */

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::resource('feedbackForm', FeedbackFormController::class)->middleware(['auth']);

Route::get('/answer/create/{id}',[AnswerController::class, 'create'])->name('answerPage');
Route::get('/guestAnswer/create/{id}',[AnswerController::class, 'create'])->name('guestAnswerPage');

Route::resource('answer', AnswerController::class);

Route::resource('user', UserController::class)->middleware(['auth']);


require __DIR__.'/auth.php';
