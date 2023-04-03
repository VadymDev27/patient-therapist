<?php

use App\Http\Controllers\ActivitiesFileController;
use App\Http\Controllers\Admin\SetPasswordController;
use App\Http\Controllers\Admin\TestUserController;
use App\Http\Controllers\Admin\TestUserNotificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SurveyPreviewController;
use App\Http\Controllers\ThankYouController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\VideoHistoryController;
use App\Http\Controllers\WeeklySettingsController;
use Illuminate\Support\Str;
use App\Models\User;
use App\Surveys\TestSurvey;
use App\Surveys\Therapist\ScreeningSurvey;
use App\Test;
use App\View\Components\AppLayout;
use App\View\MessagePage;
use Illuminate\Mail\Markdown;
use Illuminate\Support\HtmlString;

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

Route::post('/test-users/logout', [TestUserController::class, 'logout'])->name('test-users.logout');


Route::prefix('admin')->middleware(['auth', 'group:admin'])->group(function () {
    Route::view('/', 'admin.dashboard')
        ->name('admin.dashboard');

    Route::resource('week', WeeklySettingsController::class)->only([
            'index', 'update'
        ])->middleware('can-do:edit-settings');

    Route::post('/test-users/login/{user}', [TestUserController::class, 'login'])->name('test-users.login');

    Route::resource('test-users', TestUserController::class)->parameters(['test-users' => 'user'])->withoutMiddleware('group:admin');

    Route::put('/users', [AdminController::class, 'update'])->name('admin.users.update');
    Route::resource('users', AdminController::class, ['as' => 'admin'])->only(['index','store','destroy']);

    Route::middleware('can-do:download-data')->group(function () {
        Route::get('/data', [DataController::class, 'create'])->name('data');
        Route::get('/data/download', [DataController::class, 'download'])->name('data.download');
        Route::get('/data/generate', [DataController::class, 'generate'])->name('data.generate');
    });

});

Route::resource('users.notifications', TestUserNotificationController::class)
        ->only(['index','show','update']);

Route::get('/admin/set-password/{token}', [SetPasswordController::class, 'create'])
                ->middleware('guest')
                ->name('admin.password.create');

Route::post('/admin/set-password', [SetPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('admin.password.update');

Route::middleware(['auth','group:user'])->group(function () {
    Route::resource('videos', VideoHistoryController::class)->only([
        'index', 'show'
    ]);

    Route::get('/{slug}/thank-you', [ThankYouController::class, '__invoke'])
        ->name('thank-you');

    Route::get('/dashboard', [HomeController::class, '__invoke'])
        ->name('dashboard');

    Route::get('/download/activities/{number}', [ActivitiesFileController::class, 'download'])
        ->name('download.activities');

    Route::post('/surveys/therapist/screening/reset', [ScreeningSurvey::class, 'reset'])
        ->name('screening-survey.reset');
});

Route::resource('analytics', AnalyticsController::class)->only(['index','store']);

Route::view('/error', 'error');

Route::get('/', function () {
    return view('landing');
});

Route::get('/preview-survey', [SurveyPreviewController::class, 'index'])
    ->name('preview-survey.index');

Route::get('/preview-survey/{role}/{category}/{slug?}', [SurveyPreviewController::class, 'show'])
    ->name('preview-survey.show');

Route::post('/preview-survey/{role}/{category}/{slug?}', [SurveyPreviewController::class, 'update'])
    ->name('preview-survey.update');

Route::post('/', function (Request $request) {
    $request->flash();
    return redirect()->back();
});

require __DIR__.'/auth.php';

Route::fallback(function () {
    abort(404);
});

