<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontendController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\AvailableWebsiteController;
use App\Http\Controllers\Backend\TemplateController;
use App\Http\Controllers\InstallerController;


Route::get('/', [frontendController::class, 'index'])->name('home');
Route::get('/installer', [InstallerController::class, 'showStepOne'])->name('installer.step-one.page');
Route::get('/installer/theme', [InstallerController::class, 'showStepTwo'])->name('installer.step-two.page');

// Auth routes
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');
  


Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('backend.dashboard.index');
    })->middleware('permission:dashboard.view')->name('dashboard');

    // Role Management
    Route::resource('admin/roles', RoleController::class)->names('roles');

    // User Management
    Route::resource('admin/users', UserController::class)->names('users')->except('show');
     
    // Menu Management
    Route::resource('admin/menus', MenuController::class)->names('menus')->except('show');
    Route::post('admin/menus/reorder', [MenuController::class, 'reorder'])->name('menus.reorder');

    // Page Management
    Route::resource('admin/pages', PageController::class)->names('pages')->except('show');
    Route::post('admin/pages/reorder', [PageController::class, 'reorder'])->name('pages.reorder');
    Route::get('admin/pages/{page}/builder', [PageController::class, 'editContent'])->name('pages.builder');
    Route::put('admin/pages/{page}/builder', [PageController::class, 'updateContent'])->name('pages.builder.update');

    // Available Websites Management
    Route::resource('admin/available-websites', AvailableWebsiteController::class)->names('available-websites')->except('show');
    Route::post('admin/available-websites/reorder', [AvailableWebsiteController::class, 'reorder'])->name('available-websites.reorder');

    // Templates Management
    Route::resource('admin/templates', TemplateController::class)->names('templates')->except('show');
    Route::post('admin/templates/reorder', [TemplateController::class, 'reorder'])->name('templates.reorder');
});
