<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\IncomingMailController;
use App\Http\Controllers\OutgoingMailController;
use App\Http\Controllers\ComunityExperienceController;
use App\Http\Controllers\SocialController;

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

// Main Route
Route::get('/', [LoginController::class, 'index']);

//Login and Logout Route
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

//Route Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

Route::group(['middleware' => ['auth', 'admin']], function() {
    //Route Users
    Route::resource('/dashboard/users', UserController::class);

    //Route Members
    Route::resource('/dashboard/members', MemberController::class, [
        'parameters' => [
        'members' => 'member',
    ],
    ]);

    Route::resource('/dashboard/experiences', ComunityExperienceController::class)
    ->except(['destroy', 'show', 'edit', 'update']);

    Route::get('/dashboard/experiences/{comunityExperience}', [ComunityExperienceController::class, 'show'])
        ->name('experiences.show');

    Route::get('/dashboard/experiences/{comunityExperience}/edit', [ComunityExperienceController::class, 'edit'])
        ->name('experiences.edit');

    Route::put('/dashboard/experiences/{comunityExperience}', [ComunityExperienceController::class, 'update'])
        ->name('experiences.update');

    Route::delete('/dashboard/experiences/{comunityExperience}', [ComunityExperienceController::class, 'destroy'])
        ->name('experiences.destroy');

});

// Route Profile
Route::get('/dashboard/profile/{user}', [UserController::class, 'show'])->middleware('auth');
Route::put('/dashboard/profile', [UserController::class, 'updateProfile'])->middleware('auth');

// Route Social
Route::get('/dashboard/social', [SocialController::class, 'index'])->middleware('auth')->name('social.index');

//Route Publications
Route::resource('/dashboard/archive/publications', PublicationController::class)->middleware('auth');

//Route Mails
Route::middleware(['auth'])->group(function () {
    //Route Incoming Mails
    Route::resource('/dashboard/mails/incoming-mails', IncomingMailController::class);

    //Route Outgoing Mails
    Route::resource('/dashboard/mails/outgoing-mails', OutgoingMailController::class);
    Route::get('/documents/get-last-number', [OutgoingMailController::class, 'getLastNumber']);
});

