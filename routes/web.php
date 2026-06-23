<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\IssueTagController;
use App\Http\Controllers\IssueUserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('projects.index'));

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {

    // Projects
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{project}', [ProjectController::class, 'delete'])->name('projects.delete');

    // Issues
    Route::get('issues', [IssueController::class, 'index'])->name('issues.index');
    Route::get('issues/create', [IssueController::class, 'create'])->name('issues.create');
    Route::post('issues', [IssueController::class, 'store'])->name('issues.store');
    Route::get('issues/{issue}', [IssueController::class, 'show'])->name('issues.show');
    Route::get('issues/{issue}/edit', [IssueController::class, 'edit'])->name('issues.edit');
    Route::patch('issues/{issue}', [IssueController::class, 'update'])->name('issues.update');
    Route::delete('issues/{issue}', [IssueController::class, 'delete'])->name('issues.delete');

    // Tags
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    Route::post('tags', [TagController::class, 'store'])->name('tags.store');
    Route::delete('tags/{tag}', [TagController::class, 'delete'])->name('tags.delete');

    // Comments — flat resource, filtered by issue_id via QueryFilters
    Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'delete'])->name('comments.delete');

    // Issue tag toggle (AJAX)
    Route::post('issue-tags/{issue}/{tag}', [IssueTagController::class, 'toggle'])->name('issue-tags.toggle');

    // Issue assignee toggle (AJAX)
    Route::post('issue-users/{issue}/{user}', [IssueUserController::class, 'toggle'])->name('issue-users.toggle');
});
