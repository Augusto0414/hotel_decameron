<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Hotel;
use App\Http\Controllers\HotelHabitacion;
use App\Http\Controllers\TipoHabitacion;
use App\Http\Controllers\Acomodacion;


Route::get('/tipos-habitacion', [TipoHabitacion::class, 'index']);
Route::post('/tipos-habitacion', [TipoHabitacion::class, 'store']);
Route::get('/tipos-habitacion/{id}', [TipoHabitacion::class, 'show']);
Route::put('/tipos-habitacion/{id}', [TipoHabitacion::class, 'update']);
Route::delete('/tipos-habitacion/{id}', [TipoHabitacion::class, 'destroy']);

Route::get('/acomodaciones', [Acomodacion::class, 'index']);
Route::post('/acomodaciones', [Acomodacion::class, 'store']);
Route::get('/acomodaciones/{id}', [Acomodacion::class, 'show']);
Route::put('/acomodaciones/{id}', [Acomodacion::class, 'update']);
Route::delete('/acomodaciones/{id}', [Acomodacion::class, 'destroy']);

Route::get('/hoteles', [Hotel::class, 'index']);
Route::post('/hoteles', [Hotel::class, 'store']);
Route::get('/hoteles/{id}', [Hotel::class, 'show']);
Route::put('/hoteles/{id}', [Hotel::class, 'update']);
Route::delete('/hoteles/{id}', [Hotel::class, 'destroy']);

Route::get('/hoteles/{hotelId}/habitaciones', [HotelHabitacion::class, 'indexByHotel']);
Route::post('/hoteles/{hotelId}/habitaciones', [HotelHabitacion::class, 'store']);
Route::put('/hoteles/{hotelId}/habitaciones/{habitacionId}', [HotelHabitacion::class, 'update']);
Route::delete('/hoteles/{hotelId}/habitaciones/{habitacionId}', [HotelHabitacion::class, 'destroy']);

