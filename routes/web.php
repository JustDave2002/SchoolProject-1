<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackFormController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedbackToolController;
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
//TODO change the first route url to something that makes fcking sense
Route::get('answer/info/{id}',[AnswerController::class, 'formStart'])->middleware(['auth']);
Route::get('guestAnswer/info/{id}',[AnswerController::class, 'formStart']);
route::post('guestAnswer/update',[AnswerController::class, 'guestStore'])->name('guestAnswer.updateCreate');
Route::get('/guestAnswer/create',[AnswerController::class, 'create']);

Route::get('/answer/create',[AnswerController::class, 'create'])->middleware(['auth']);
Route::get('/answer/edit',[AnswerController::class, 'editForm'])->middleware(['auth']);
Route::post('/answer/updateForm',[AnswerController::class, 'updateForm'])->middleware(['auth'])->name('answer.updateForm');


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('welcome');
})->middleware(['auth'])->name('dashboard');

Route::get('feedbackForm/createForm', [FeedbackFormController::class, 'createForm'])->middleware(['auth']);
Route::post('feedbackForm/storeForm', [FeedbackFormController::class, 'storeForm'])->middleware(['auth'])->name('feedbackForm.storeForm');

Route::get('feedbackForm/editForm', [FeedbackFormController::class, 'editForm'])->middleware(['auth']);
Route::put('feedbackForm/updateForm', [FeedbackFormController::class, 'updateForm'])->middleware(['auth'])->name('feedbackForm.updateForm');
Route::get('feedbackForm/pdf/{id}', [FeedbackFormController::class, 'makePDF'])->middleware(['auth']);

require __DIR__.'/auth.php';

Route::resource('feedbackForm', FeedbackFormController::class)->middleware(['auth']);
Route::resource('answer', AnswerController::class);
Route::resource('user', UserController::class)->middleware(['auth']);
Route::get('/admin', [UserController::class, 'showAdmin'])->middleware(['auth'])->name('admin');

Route::get('/sendmail/test/', [FeedbackToolController::class, 'store'])->middleware(['auth']);

Route::get('/adminPage/verified/{id}', [UserController::class,'verifyAdmin'])->name('adminPage.verified');
Route::get('/adminPage/declined/{id}', [UserController::class,'declineAdmin'])->name('adminPage.declined');

require __DIR__.'/auth.php';

