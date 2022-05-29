<?php

use App\Http\Controllers\PageController;
use App\Http\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SetThemeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Auth\DiscordLoginController;
use App\Http\Controllers\Auth\LoginController;


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
Route::get('/install', [InstallController::class, 'welcome'])->name('install.welcome')
    ->withoutMiddleware(AuthenticateSession::class);

Route::post('/install', [InstallController::class, 'install'])->name('install.complete')
    ->withoutMiddleware(AuthenticateSession::class);

Route::get('/login/steam', [LoginController::class, 'redirect'])->name('login.steam');
Route::get('/auth/steam', [LoginController::class, 'authenticated'])->name('auth.steam');

Route::get('/login/discord', [DiscordLoginController::class, 'redirect'])->name('login.discord');
Route::get('/auth/discord', [DiscordLoginController::class, 'authenticated'])->name('auth.discord');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/server/{server}', [IndexController::class, 'serverInfo'])->name('home.server.info');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
Route::post('/notifications/mark-read', [NotificationController::class, 'markAllRead'])->name('notifications.markRead');
Route::post('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');

Route::get('/staff', StaffController::class)->name('staff');

Route::post('/set-theme', SetThemeController::class)->name('set-theme');

Route::get('/changelogs', ChangelogController::class)->name('changelogs');

Route::prefix('/users')
    ->name('users.')
    ->group(base_path('routes/web/profile.php'));

Route::prefix('/forums')
    ->name('forums.')
    ->middleware('banned:forums')
    ->group(base_path('routes/web/forums.php'));

Route::prefix('/store')
    ->name('store.')
    ->middleware('banned:store')
    ->group(base_path('routes/web/store.php'));

Route::prefix('/manage')
    ->name('manage.')
    ->middleware('auth')
    ->group(base_path('routes/web/manage.php'));

Route::get('/{page:slug}', PageController::class)->name('page');