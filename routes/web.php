<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackFormController;
use App\Http\Controllers\AnswerController;
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

Route::get('/answer/create/{id}',[AnswerController::class, 'create'])->middleware(['auth']);

Route::resource('answer', AnswerController::class)->middleware(['auth']);


require __DIR__.'/auth.php';
