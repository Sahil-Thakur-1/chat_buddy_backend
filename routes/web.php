<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Middleware\AuthMiddleware;

Route::prefix('/auth')->group(function(){
    Route::post('/register',[AuthController::class,'register']);
});

Route::prefix('/chat')->middleware(AuthMiddleware::class)->group(function(){
    Route::post('/create',[ChatController::class,'createConversation']);
    Route::post('/add',[ChatController::class,'addParticipant']);
});