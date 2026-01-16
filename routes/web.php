<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\UnitController;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('login.auth');

Route::middleware('auth')->group(function () {
    Route::get('/apps/admin/dashboard', [StockController::class, 'index'])->name('dashboard');

    Route::get('/apps/admin/master/position', [PositionController::class, 'index'])->name('position.index');
    Route::get('/apps/admin/master/position/data', [PositionController::class, 'getDataPosition'])->name('position.data');
    Route::get('/apps/admin/master/position/create', [PositionController::class, 'create'])->name('position.create');
    Route::post('/apps/admin/master/position/store', [PositionController::class, 'store'])->name('position.store');
    Route::get('/apps/admin/master/position/edit/{id}', [PositionController::class, 'edit'])->name('position.edit');
    Route::put('/apps/admin/master/position/update/{id}', [PositionController::class, 'update'])->name('position.update');
    Route::delete('/apps/admin/master/position/delete/{id}', [PositionController::class, 'destroy'])->name('position.destroy');

    Route::get('/apps/admin/master/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/apps/admin/master/category/data', [CategoryController::class, 'getDataCategory'])->name('category.data');
    Route::get('/apps/admin/master/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/apps/admin/master/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/apps/admin/master/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/apps/admin/master/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/apps/admin/master/category/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/apps/admin/master/employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/apps/admin/master/employee/data', [EmployeeController::class, 'getDataEmployee'])->name('employee.data');
    Route::get('/apps/admin/master/employee/create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('/apps/admin/master/employee/store', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/apps/admin/master/employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('/apps/admin/master/employee/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/apps/admin/master/employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');

    Route::get('/apps/admin/master/unit', [UnitController::class, 'index'])->name('unit.index');
    Route::get('/apps/admin/master/unit/data', [UnitController::class, 'getDataUnit'])->name('unit.data');
    Route::get('/apps/admin/master/unit/create', [UnitController::class, 'create'])->name('unit.create');
    Route::post('/apps/admin/master/unit/store', [UnitController::class, 'store'])->name('unit.store');
    Route::get('/apps/admin/master/unit/edit/{id}', [UnitController::class, 'edit'])->name('unit.edit');
    Route::put('/apps/admin/master/unit/update/{id}', [UnitController::class, 'update'])->name('unit.update');
    Route::delete('/apps/admin/master/unit/delete/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');

    Route::get('/apps/admin/master/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/apps/admin/master/items/data', [ItemController::class, 'getDataItems'])->name('items.data');
    Route::get('/apps/admin/master/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/apps/admin/master/items/store', [ItemController::class, 'store'])->name('items.store');
    Route::get('/apps/admin/master/items/edit/{id}', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/apps/admin/master/items/update/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/apps/admin/master/items/delete/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

    Route::get('/apps/admin/warehouse/stock-in', [StockInController::class, 'index'])->name('stock-in.index');
    Route::get('/apps/admin/warehouse/stock-in/data', [StockInController::class, 'getDataStockIn'])->name('stock-in.data');
    Route::get('/apps/admin/warehouse/stock-in/create', [StockInController::class, 'create'])->name('stock-in.create');
    Route::post('/apps/admin/warehouse/stock-in/store', [StockInController::class, 'store'])->name('stock-in.store');
    Route::get('/apps/admin/warehouse/stock-in/edit/{id}', [StockInController::class, 'edit'])->name('stock-in.edit');
    Route::put('/apps/admin/warehouse/stock-in/update/{id}', [StockInController::class, 'update'])->name('stock-in.update');
    Route::delete('/apps/admin/warehouse/stock-in/delete/{id}', [StockInController::class, 'destroy'])->name('stock-in.destroy');
    Route::post('/stock-in/change-status', [StockInController::class, 'changeStatus'])
        ->name('stock-in.change-status');

    Route::get('/apps/admin/warehouse/stock-out', [StockOutController::class, 'index'])->name('stock-out.index');
    Route::get('/apps/admin/warehouse/stock-out/data', [StockOutController::class, 'getDataStockOut'])->name('stock-out.data');
    Route::get('/apps/admin/warehouse/stock-out/create', [StockOutController::class, 'create'])->name('stock-out.create');
    Route::post('/apps/admin/warehouse/stock-out/store', [StockOutController::class, 'store'])->name('stock-out.store');
    Route::get('/apps/admin/warehouse/stock-out/edit/{id}', [StockOutController::class, 'edit'])->name('stock-out.edit');
    Route::put('/apps/admin/warehouse/stock-out/update/{id}', [StockOutController::class, 'update'])->name('stock-out.update');
    Route::delete('/apps/admin/warehouse/stock-out/delete/{id}', [StockOutController::class, 'destroy'])->name('stock-out.destroy');
    Route::post('/stock-out/change-status', [StockOutController::class, 'changeStatus'])->name('stock-out.change-status');

    Route::get('/apps/admin/warehouse/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('apps/admin/warehouse/stock/data', [StockController::class, 'data'])->name('stock.data');
    Route::get('/stock/{item}/history', [StockController::class, 'history'])
        ->name('stock.history');
});

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');
