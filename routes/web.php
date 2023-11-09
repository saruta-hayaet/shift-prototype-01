<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\CsvIssueController;
use App\Models\Employee;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('employee')->name('employee.')->group(function(){
    Route::get('/', [EmployeeController::class, 'index'])->name('');
    Route::get('/create', [EmployeeController::class, 'create'])->name('create');
    Route::post('/store', [EmployeeController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('update');
    Route::get('/delete/{id}', [EmployeeController::class, 'delete'])->name('delete');
    Route::post('/csv', [EmployeeController::class, 'csvImport'])->name('csv');
});

Route::prefix('vehicle')->name('vehicle.')->group(function(){
    Route::get('/', [VehicleController::class, 'index'])->name('');
    Route::get('/create', [VehicleController::class, 'create'])->name('create');
    Route::post('/store', [VehicleController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [VehicleController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [VehicleController::class, 'update'])->name('update');
    Route::get('/delete/{id}', [VehicleController::class, 'delete'])->name('delete');
    Route::post('/csv', [VehicleController::class, 'csvImport'])->name('csv');
});

Route::prefix('project')->name('project.')->group(function(){
    Route::get('/', [ProjectController::class, 'index'])->name('');
    Route::get('/create', [ProjectController::class, 'create'])->name('create');
    Route::post('/store', [ProjectController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [ProjectController::class, 'update'])->name('update');
    Route::get('/delete/{id}', [ProjectController::class, 'delete'])->name('delete');
    Route::get('/employee_payment/{id}', [ProjectController::class, 'employeePayment'])->name('employeePayment');
    Route::post('/employee_payment_store/{id}', [ProjectController::class, 'employeePaymentStore'])->name('employeePaymentStore');
    Route::get('/employee_payment_show/{id}', [ProjectController::class, 'employeePaymentShow'])->name('employeePaymentShow');
    Route::get('/employee_payment_edit/{id}/{employeeId}', [ProjectController::class, 'employeePaymentEdit'])->name('employeePaymentEdit');
    Route::post('/employee_payment_update/{id}', [ProjectController::class, 'employeePaymentUpdate'])->name('employeePaymentUpdate');
    Route::post('/csv', [ProjectController::class, 'csvImport'])->name('csv');
});

Route::prefix('shift')->name('shift.')->group(function(){
    Route::get('/', [ShiftController::class, 'index'])->name('');
    Route::get('/projectPriceShift', [ShiftController::class, 'projectPriceShift'])->name('projectPriceShift');
    Route::get('/employeeShowShift', [ShiftController::class, 'employeeShowShift'])->name('employeeShowShift');
    Route::get('/employeePriceShift', [ShiftController::class, 'employeePriceShift'])->name('employeePriceShift');
    Route::get('/create', [ShiftController::class, 'create'])->name('create');
    Route::post('/store', [ShiftController::class, 'store'])->name('store');
    Route::get('/edit', [ShiftController::class, 'edit'])->name('edit');
    Route::post('/update', [ShiftController::class, 'update'])->name('update');
    Route::get('/project', [ShiftController::class, 'project'])->name('project');
    Route::post('/csv', [ShiftController::class, 'csvImport'])->name('csv');
});

Route::prefix('csv-issue')->name('csv-issue.')->group(function(){
    Route::get('/',[CsvIssueController::class, 'index'])->name('');
    Route::post('/show',[CsvIssueController::class, 'show'])->name('show');
    Route::get('/export/{projectId}/{month}',[CsvIssueController::class, 'csvExport'])->name('export');
});

Route::prefix('csv-employee')->name('csv-employee.')->group(function(){
    Route::get('/',[CsvIssueController::class, 'employeeIndex'])->name('');
    Route::post('/show',[CsvIssueController::class, 'employeeShow'])->name('show');
    Route::get('/export/{employeeId}/{month}',[CsvIssueController::class, 'employeeCsvExport'])->name('export');
});



require __DIR__.'/auth.php';
