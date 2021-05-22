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

Route::get('answer/guestCreate/{id}',[AnswerController::class, 'guestCreate'])->name('answer.guestCreate');
route::put('guestAnswer/updateCreate/{id}',[AnswerController::class, 'guestStore'])->name('guestAnswer.updateCreate');



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('feedbackForm/createForm', [FeedbackFormController::class, 'createForm'])->middleware(['auth']);
Route::post('feedbackForm/storeForm', [FeedbackFormController::class, 'storeForm'])->middleware(['auth'])->name('feedbackForm.storeForm');

Route::get('feedbackForm/editForm', [FeedbackFormController::class, 'editForm'])->middleware(['auth']);
Route::put('feedbackForm/updateForm', [FeedbackFormController::class, 'updateForm'])->middleware(['auth'])->name('feedbackForm.updateForm');




Route::get('/answer/create/{id}',[AnswerController::class, 'create'])->middleware(['auth']);



require __DIR__.'/auth.php';

Route::resource('feedbackForm', FeedbackFormController::class)->middleware(['auth']);
Route::resource('answer', AnswerController::class);
Route::resource('user', UserController::class)->middleware(['auth']);

