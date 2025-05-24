<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdminRoleMiddleware;
use Illuminate\Support\Facades\Route;


// Auth
// Регистрация, создание пользователя
Route::post('/register', [AuthController::class, 'register']);

// Авторизация
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// Users
// Получение списка всех пользователей (только для администратора).
Route::get('/users', [UserController::class, 'users'])->middleware('auth:sanctum', 'admin');

// Получение информации о текущем пользователе
Route::get('/users/current', [UserController::class, 'current'])->middleware('auth:sanctum');

// Обновление профиля (только текущий пользователь может обновить свои данные)
Route::patch('/users', [UserController::class, 'update'])->middleware('auth:sanctum');

//Удаление пользователя (только администратор может удалить пользователя).
Route::delete('/users/{id}', [UserController::class, 'delete'])->middleware('auth:sanctum', 'admin');



