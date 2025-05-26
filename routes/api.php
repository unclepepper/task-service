<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


use App\Http\Controllers\User\CurrentController as UserCurrentController;
use App\Http\Controllers\User\DeleteController as UserDeleteController;
use App\Http\Controllers\User\EditController as UserEditController;
use App\Http\Controllers\User\IndexController as UserIndexController;
use App\Http\Controllers\Task\DeleteController as TaskDeleteController;
use App\Http\Controllers\Task\EditController as TaskEditController;
use App\Http\Controllers\Task\IndexController as TaskIndexController;
use App\Http\Controllers\Task\NewController as TaskNewController;
use App\Http\Controllers\Task\TaskController as TaskController;
use Illuminate\Support\Facades\Route;


// Auth
/** Регистрация, создание пользователя */
Route::post('/register', [RegisterController::class, 'register']);

/** Авторизация */
Route::post('/login', [LoginController::class, 'login']);



// Users
Route::middleware('auth:sanctum')->group(function () {
    /** Получение списка всех пользователей (только для администратора) */
    Route::get('/users', [UserIndexController::class, 'index'])->middleware('role_admin');

    /** Получение информации о текущем пользователе */
    Route::get('/user', [UserCurrentController::class, 'current']);

    /** Обновление профиля (только текущий пользователь может обновить свои данные) */
    Route::patch('/users/{id}', [UserEditController::class, 'edit']);

    /** Удаление пользователя (только администратор может удалить пользователя). */
    Route::delete('/users/{id}', [UserDeleteController::class, 'delete'])->middleware('role_admin');
});


// Tasks
Route::middleware('auth:sanctum')->group(function () {
    /** Создание новой задачи */
    Route::post('/tasks', [TaskNewController::class, 'new']);

    /** Получение списка всех задач текущего пользователя */
    Route::get('/tasks', [TaskIndexController::class, 'index']);

    /** Получение конкретной задачи */
    Route::get('/tasks/{id}', [TaskController::class, 'current']);

    /** Обновление задачи */
    Route::patch('/tasks/{id}', [TaskEditController::class, 'edit']);

    /** Удаление задачи */
    Route::delete('/tasks/{id}', [TaskDeleteController::class, 'delete']);
});

