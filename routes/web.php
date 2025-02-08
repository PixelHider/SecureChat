<?php

use App\Http\Controllers\AccessCodeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Crypt;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Send Message
    Route::get('victim/send-message/{username}', [MessageController::class, 'show'])->name('send.message');
    Route::post('victim/send-message', [MessageController::class, 'store'])->name('send.message.store');

    // Message for Police
    Route::get('police/messages', [MessageController::class, 'msg_for_police'])->name('msg.for.police');
    //Show Message
    Route::get('police/messages/{id}', [MessageController::class, 'show_msg_for_police'])->name('police.messages.show');
    // Reply Message
    Route::post('police/messages/reply/{id}', [MessageController::class, 'replyPolice'])->name('police.messages.reply');

    // Cases for Police
    Route::get('police/cases', [CaseController::class, 'cases_for_police'])->name('cases.for.police');
    // Closed Cases
    Route::get('police/cases/closed', [CaseController::class, 'closed_case_for_police'])->name('police.cases.closed');
    //Show Case
    Route::get('police/cases/{id}', [CaseController::class, 'show_case_for_police'])->name('police.cases.show');
    // Reply Case
    Route::post('police/cases/reply/{id}', [CaseController::class, 'reply_case_for_police'])->name('police.cases.reply');
    

    // Message for Journalist
    Route::get('/journalist/messages', [MessageController::class, 'msg_for_journalist'])->name('msg.for.journalist');
    //Show Message
    Route::get('/journalist/messages/{id}', [MessageController::class, 'show_msg_for_journalist'])->name('journalist.messages.show');
    // Reply Message
    Route::post('/journalist/messages/reply/{id}', [MessageController::class, 'replyJournalist'])->name('journalist.messages.reply');
    

    // Cases for Journalist
    Route::get('/journalist/cases', [CaseController::class, 'cases_for_journalist'])->name('cases.for.journalist');
    // Closed Cases
    Route::get('/journalist/cases/closed', [CaseController::class, 'closed_case_for_journalist'])->name('journalist.cases.closed');
    //Show Case
    Route::get('/journalist/cases/{id}', [CaseController::class, 'show_case_for_journalist'])->name('journalist.cases.show');
    // Reply Case
    Route::post('/journalist/cases/reply/{id}', [CaseController::class, 'reply_case_for_journalist'])->name('journalist.cases.reply');

    // Message for Victim
    Route::get('/victim/messages', [MessageController::class, 'msg_for_victim'])->name('msg.for.victim');
    //Show Message
    Route::get('/victim/messages/{id}', [MessageController::class, 'show_msg_for_victim'])->name('victim.messages.show');
    // Reply Message
    Route::post('/victim/messages/reply/{id}', [MessageController::class, 'replyVictim'])->name('victim.messages.reply');

    // Create Case
    Route::get('/victim/cases/create', [CaseController::class, 'createCase'])->name('victim.cases.create');
    Route::post('/victim/cases', [CaseController::class, 'storeCase'])->name('victim.cases.store');
    // Cases for Victim
    Route::get('/victim/cases', [CaseController::class, 'cases_for_victim'])->name('cases.for.victim');
    // Closed Cases
    Route::get('/victim/cases/closed', [CaseController::class, 'closed_case_for_victim'])->name('victim.cases.closed');
    //Show Case
    Route::get('/victim/cases/{id}', [CaseController::class, 'show_case_for_victim'])->name('victim.cases.show');
    // Reply Case
    Route::post('/victim/cases/reply/{id}', [CaseController::class, 'reply_case_for_victim'])->name('victim.cases.reply');

    // Delete Message
    Route::delete('victim/messages/{id}', [MessageController::class, 'destroyMsg'])->name('victim.messages.destroy');

    // Delete Case
    Route::delete('victim/cases/{id}', [CaseController::class, 'destroy_case_for_victim'])->name('victim.cases.destroy');

    // Close Case
    Route::post('victim/cases/close/{id}', [CaseController::class, 'close_case_for_victim'])->name('victim.cases.close');
    
});

Route::get('/verify-code', [AccessCodeController::class, 'show'])->name('verify.code');
Route::post('/verify-code', [AccessCodeController::class, 'verify']);

// Admin Routes

Route::middleware(['auth:admin', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/messages', [AdminController::class, 'messages'])->name('admin.messages');
    Route::get('/cases', [AdminController::class, 'cases'])->name('admin.cases');

    // delete user
    Route::delete('/users/{id}', [AdminController::class, 'destroy_user'])->name('admin.users.destroy');
    // delete cases
    Route::delete('/cases/{id}', [AdminController::class, 'destroy_case'])->name('admin.cases.destroy');
    // delete messages
    Route::delete('/messages/{id}', [AdminController::class, 'destroy_message'])->name('admin.messages.destroy');

    // Approve user
    Route::get('/users/approve/{id}', [AdminController::class, 'approve_user'])->name('admin.users.approve');

    // admin profile edit
    Route::get('/profile', [AdminController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/profile', [AdminController::class, 'update'])->name('admin.password.update');
    Route::delete('/profile', [AdminController::class, 'destroy'])->name('admin.profile.destroy');

    // admin logout
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
});

// Create admin
Route::get('/test', function () {
    Admin::create([
        'username' => 'Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password')
    ]);

    return 'Admin created';
});


require __DIR__.'/auth.php';
