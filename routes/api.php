<?php

use App\Http\Controllers\TeacherManagementController;
use App\Http\Controllers\SubjectManagementController;
use App\Http\Controllers\SubjectAssignmentController;
use App\Http\Controllers\DepartmentManagementController;
use App\Http\Controllers\SchoolClassManagementController;
use App\Http\Controllers\ClassTeacherAssignmentController;
use App\Http\Controllers\IssueManagementController;
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

	// 教師への教科の割り当て
	Route::post('/subject/assign', [SubjectAssignmentController::class, 'assignSubjectToTeacher']);
	Route::delete('/subject/assign/{teacher_subject}', [
		SubjectAssignmentController::class,
		'deleteSubjectAssignment',
	]);
	Route::get('/subject/assign', [SubjectAssignmentController::class, 'getSubjectList']);

	// 教師へのクラスの割り当て
	Route::post('/class/assign', [ClassTeacherAssignmentController::class, 'assignClassToTeacher']);
	Route::delete('/class/assign/{class_teacher}', [
		ClassTeacherAssignmentController::class,
		'deleteClassTeacherAssignment',
	]);
	Route::get('/class/assign', [ClassTeacherAssignmentController::class, 'getClassTeacherList']);

	// 課題情報
	Route::post('/issue/register', [IssueManagementController::class, 'registerIssue']);
	Route::get('/issue', [IssueManagementController::class, 'getIssueList']);
	Route::put('/issue/{issue_id}', [IssueManagementController::class, 'updateIssue']);
	Route::delete('/issue/{issue_id}', [IssueManagementController::class, 'deleteIssue']);

	// 課題学科割り当て情報
	Route::post('/issue/department/assign', [
		IssueManagementController::class,
		'registerIssueDepartment',
	]);
	Route::delete('/issue/department/assign/{issue_department}', [
		IssueManagementController::class,
		'deleteIssueDepartment',
	]);
});

Route::middleware(['jwt.verify', 'admin'])->group(function () {
	// 教科情報
	Route::post('/subject/register', [SubjectManagementController::class, 'subjectRegister']);
	Route::get('/subject', [SubjectManagementController::class, 'getSubjectList']);
	Route::put('/subject/{subject}', [SubjectManagementController::class, 'updateSubject']);
	Route::delete('/subject/{subject}', [SubjectManagementController::class, 'deleteSubject']);

	// 学科情報
	Route::post('/department/register', [
		DepartmentManagementController::class,
		'departmentRegister',
	]);
	Route::get('/department', [DepartmentManagementController::class, 'getDepartmentList']);
	Route::put('/department/{department}', [
		DepartmentManagementController::class,
		'updateDepartment',
	]);
	Route::delete('/department/{department}', [
		DepartmentManagementController::class,
		'deleteDepartment',
	]);

	// クラス情報
	Route::post('/class/register', [SchoolClassManagementController::class, 'classRegister']);
	Route::get('/class', [SchoolClassManagementController::class, 'getClassList']);
	Route::put('/class/{class}', [SchoolClassManagementController::class, 'updateClass']);
	Route::delete('/class/{class}', [SchoolClassManagementController::class, 'deleteClass']);
});
