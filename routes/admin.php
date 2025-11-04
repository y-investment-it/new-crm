<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\ReAssignController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');

Route::get('/users/list', [UserController::class, 'listAgents']);

Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
Route::post('/leads/reassign', [ReAssignController::class, 'store'])->name('leads.reassign');
