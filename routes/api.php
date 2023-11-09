<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationsController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ICsController;
use App\Http\Controllers\PosDocsController;
use App\Http\Controllers\PesquisadoresColabController;
use App\Http\Controllers\DefesasController;
use App\Http\Controllers\DocentesController;
use App\Http\Controllers\FuncionariosController;
use App\Http\Controllers\EstagiariosController;
use App\Http\Controllers\CountController;

Route::post('invite', [InvitationsController::class, 'generateInvitation']);
Route::post('register', [RegistrationController::class, 'register']);
Route::post('auth', [AuthController::class, 'auth']);

Route::get('ics', [ICsController::class, 'index']);
Route::get('posdocs', [PosDocsController::class, 'index']);
Route::get('pcs', [PesquisadoresColabController::class, 'index']);
Route::get('defesas', [DefesasController::class, 'index']);

Route::get('vinculos/docentes', [DocentesController::class, 'index']);
Route::get('vinculos/funcionarios', [FuncionariosController::class, 'index']);
Route::get('vinculos/estagiarios', [EstagiariosController::class, 'index']);

Route::get('/{endpoint}/count', [CountController::class, 'index'])->where('endpoint', '.*');