<?php

use App\Http\Controllers\TeacherManagementController;
use App\Http\Controllers\SubjectManagementController;
use App\Http\Controllers\SubjectAssignmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['jwt.verify', 'teacher'])->group(function () {
	// 教師情報
	Route::post('/teacher/register', [TeacherManagementController::class, 'teacherRegister']);
	Route::get('/teacher', [TeacherManagementController::class, 'getTeacherList']);
	Route::put('/teacher', [TeacherManagementController::class, 'updateTeacher']);
	Route::delete('/teacher', [TeacherManagementController::class, 'deleteTeacher']);
	Route::get('/teacher/exists', [TeacherManagementController::class, 'checkTeacherExists']);
});

Route::middleware(['jwt.verify', 'admin'])->group(function () {
	// 教科情報
	Route::post('/subject/register', [SubjectManagementController::class, 'subjectRegister']);
	Route::get('/subject', [SubjectManagementController::class, 'getSubjectList']);
	Route::put('/subject/{subject}', [SubjectManagementController::class, 'updateSubject']);
	Route::delete('/subject/{subject}', [SubjectManagementController::class, 'deleteSubject']);

	// 教師への教科の割り当て
	Route::post('/subject/assign', [SubjectAssignmentController::class, 'assignSubjectToTeacher']);
	Route::delete('/subject/assign/{teacher_subject}', [
		SubjectAssignmentController::class,
		'deleteSubjectAssignment',
	]);
	Route::get('/subject/assign', [SubjectAssignmentController::class, 'getSubjectList']);
});
