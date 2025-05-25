<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\CurrentController;
use App\Http\Controllers\User\DeleteController;
use App\Http\Controllers\User\EditController;
use App\Http\Controllers\User\IndexController;
use Illuminate\Support\Facades\Route;


// Auth
/** Регистрация, создание пользователя */
Route::post('/register', [RegisterController::class, 'register']);

/** Авторизация */
Route::post('/login', [LoginController::class, 'login']);



// Users
Route::middleware('auth:sanctum')->group(function () {
    /** Получение списка всех пользователей (только для администратора) */
    Route::get('/users', [IndexController::class, 'index'])->middleware('role_admin');

    /** Получение информации о текущем пользователе */
    Route::get('/user', [CurrentController::class, 'current']);

    /** Обновление профиля (только текущий пользователь может обновить свои данные) */
    Route::patch('/users', [EditController::class, 'edit']);

    /** Удаление пользователя (только администратор может удалить пользователя). */
    Route::delete('/users/{id}', [DeleteController::class, 'delete'])->middleware('role_admin');
});

