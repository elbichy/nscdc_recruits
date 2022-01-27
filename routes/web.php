<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\SettingsController;
use App\Models\State;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/login', [AuthenticatedSessionController::class, 'create']);
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::group(['prefix' => 'dashboard'], function (){
	Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
	
	// PERSONNEL
	Route::group(['prefix' => 'personnel'], function () {
		Route::get('/', [PersonnelController::class, 'index'])->name('personnel_all');
		Route::get('/new', [PersonnelController::class, 'create'])->name('personnel_create');
		Route::get('/all', [PersonnelController::class, 'index'])->name('personnel_all');
		Route::get('/get_all', [PersonnelController::class, 'get_all'])->name('personnel_get_all');
		Route::get('/get_outofservice', [PersonnelController::class, 'get_outofservice'])->name('get_outofservice');
		Route::get('/{user}', [PersonnelController::class, 'show'])->name('personnel_show');
		Route::get('/{user}/ros', [PersonnelController::class, 'ros'])->name('personnel_ros');
		Route::post('/store', [PersonnelController::class, 'store'])->name('store_personnel');
		Route::get('{user}/edit/', [PersonnelController::class, 'edit'])->name('personnel_edit');
		
		Route::post('{user}/change_password/', [PersonnelController::class, 'change_password'])->name('personnel_change_password');
		// Route::post('{user}/delete/', [PersonnelController::class, 'change_password'])->name('personnel_delete');
		
		Route::put('/{user}/update', [PersonnelController::class, 'update'])->name('personnel_update');
		Route::post('/delete', [PersonnelController::class, 'destroy'])->name('personnel_delete');
		
		Route::group(['prefix' => 'import'], function () {
			Route::get('/data', [PersonnelController::class, 'import_data'])->name('import_data');
			Route::post('/users/store', [PersonnelController::class, 'store_imported_users'])->name('store_imported_users');
		});

		Route::group(['prefix' => 'nok'], function () {
			Route::post('{personnel}/store', [NokController::class, 'store'])->name('personnel_store_nok');
			Route::delete('/{nok}/delete', [NokController::class, 'destroy'])->name('personnel_delete_nok');
			Route::post('/{nok}/update', [NokController::class, 'update'])->name('personnel_update_nok');
		});

		Route::group(['prefix' => 'file'], function () {
			Route::post('/upload/{user}', [PersonnelController::class, 'upload_file'])->name('personnel_upload_file');
			Route::delete('/document/{document}/delete', [PersonnelController::class, 'destroyDocument'])->name('deletePersonnelDocument');
		});
		
		// QUALIFICATION
		Route::group(['prefix' => 'qualification'], function () {
			Route::post('{personnel}/store',  [QualificationController::class, 'store'])->name('personnel_store_qualification');
			Route::delete('/{qualification}/delete',  [QualificationController::class, 'destroy'])->name('personnel_delete_qualification');
			Route::post('/{qualification}/update',  [QualificationController::class, 'update'])->name('personnel_update_qualification');
		});
		
		// // DEPLOYMENT
		Route::group(['prefix' => 'deployment'], function () {
			Route::post('{personnel}/store',  [DeploymentController::class, 'store'])->name('personnel_store_deployment');
			Route::delete('/{deployment}/delete',  [DeploymentController::class, 'destroy'])->name('personnel_delete_deployment');
			Route::post('/{deployment}/update',  [DeploymentController::class, 'update'])->name('personnel_update_deployment');
		});

		// // PROGRESSION
		Route::group(['prefix' => 'progression'], function () {
			Route::get('/appointment',  [GenerateController::class, 'generate_appointment'])->name('generate_appointment');
		});

		Route::get('export/{type}',  [PersonnelController::class, 'export']);

	});

	// SETTINGS
	Route::group(['prefix' => 'settings'], function () {
		Route::get('/', [SettingsController::class, 'index'])->name('app_settings');
		Route::post('/roles/add/', [SettingsController::class, 'add_role'])->name('app_settings_add_role');
		Route::post('/roles/get/permissions', [SettingsController::class, 'get_permissions'])->name('permissions_get_from_role');
		Route::put('/privilage/{user}/update', [SettingsController::class, 'update_privilage'])->name('app_settings_update_privilage');
		Route::post('/privilage/update/', [SettingsController::class, 'asign_privilage'])->name('app_settings_assign_privilage');
	});
});


// require __DIR__.'/auth.php';

