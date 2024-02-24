<?php

use App\Http\Controllers\TeacherManagementController;
use App\Http\Controllers\SubjectManagementController;
use App\Http\Controllers\SubjectAssignmentController;
use App\Http\Controllers\DepartmentManagementController;
use App\Http\Controllers\SchoolClassManagementController;
use App\Http\Controllers\ClassTeacherAssignmentController;
use App\Http\Controllers\IssueManagementController;
use App\Http\Controllers\IssueCoverManagementController;
use App\Http\Controllers\StudentManagementController;
use App\Http\Controllers\DiscordGuildController;
use App\Models\Issue;
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

Route::middleware(['jwt.verify', 'student'])->group(function () {
	// 生徒情報
	Route::post('/student/register', [StudentManagementController::class, 'registerStudent']);
	Route::get('/student', [StudentManagementController::class, 'getStudentInfo']);
	Route::put('/student', [StudentManagementController::class, 'updateStudent']);
	Route::delete('/student', [StudentManagementController::class, 'deleteStudent']);
	Route::get('/student/exists', [StudentManagementController::class, 'checkStudentExists']);

	// 課題表紙情報
	Route::get('/issue/cover/not_submitted', [
		IssueCoverManagementController::class,
		'getNotSubmittedIssueCover',
	]);
	Route::post('/issue/cover/register', [
		IssueCoverManagementController::class,
		'registerIssueCover',
	]);
	Route::post('/issue/cover', [IssueCoverManagementController::class, 'searchIssueCover']);
	Route::delete('/issue/cover/{issue_cover}', [
		IssueCoverManagementController::class,
		'deleteIssueCover',
	]);

	Route::put('/student/issue/cover', [
		IssueCoverManagementController::class,
		'updateIndividualIssueCover',
	]);

	// クラス一覧
	Route::get('/student/class', [SchoolClassManagementController::class, 'getClassList']);
});

Route::middleware(['jwt.verify', 'teacher'])->group(function () {
	// 教師情報
	Route::post('/teacher/register', [TeacherManagementController::class, 'teacherRegister']);
	Route::get('/teacher', [TeacherManagementController::class, 'getTeacherList']);
	Route::put('/teacher', [TeacherManagementController::class, 'updateTeacher']);
	Route::delete('/teacher', [TeacherManagementController::class, 'deleteTeacher']);
	Route::get('/teacher/exists', [TeacherManagementController::class, 'checkTeacherExists']);
	Route::get('/teacher/info', [TeacherManagementController::class, 'findTeacherInfo']);

	// 教科情報
	Route::get('/teacher/subject', [SubjectManagementController::class, 'getSubjectList']);

	// 教師への教科の割り当て
	Route::post('/subject/assign', [SubjectAssignmentController::class, 'assignSubjectToTeacher']);
	Route::delete('/subject/assign/{teacher_subject}', [
		SubjectAssignmentController::class,
		'deleteSubjectAssignment',
	]);
	Route::get('/subject/assign', [SubjectAssignmentController::class, 'getSubjectList']);

	Route::get('/subject/class/{class_id}', [
		SubjectManagementController::class,
		'getSubjectListByClassId',
	]);

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
	Route::get('/issue/{teacher_subject_id}', [
		IssueManagementController::class,
		'getIssueListByTeacherSubjectId',
	]);
	Route::put('/issue/{issue_id}', [IssueManagementController::class, 'updateIssue']);
	Route::delete('/issue/{issue_id}', [IssueManagementController::class, 'deleteIssue']);

	//課題表紙情報
	Route::post('/teacher/issue/cover', [
		IssueCoverManagementController::class,
		'searchIssueCoverByIssueId',
	]);
	Route::put('/teacher/issue/cover/collective', [
		IssueCoverManagementController::class,
		'updateCollectiveIssueCovers',
	]);
	Route::put('/teacher/issue/cover/individual', [
		IssueCoverManagementController::class,
		'updateIndividualIssueCover',
	]);

	// 学科情報
	Route::get('/teacher/department', [DepartmentManagementController::class, 'getDepartmentList']);

	// 課題学科割り当て情報
	Route::post('/issue/department/assign', [
		IssueManagementController::class,
		'registerIssueDepartment',
	]);
	Route::delete('/issue/department/assign/{issue_department}', [
		IssueManagementController::class,
		'deleteIssueDepartment',
	]);

	Route::post('/issue/class', [
		IssueCoverManagementController::class,
		'findIssueCoverByClassIdAndSubjectId',
	]);

	// クラス一覧
	Route::get('/teacher/class', [SchoolClassManagementController::class, 'getClassList']);
	Route::get('/teacher/class/exemption/{class_id}', [
		IssueCoverManagementController::class,
		'getExemptedIssueCoversByClassId',
	]);

	// Discordギルド情報
	Route::get('/discord/guild/{class_id}', [DiscordGuildController::class, 'findGuild']);
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
