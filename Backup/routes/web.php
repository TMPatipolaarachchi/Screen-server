<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TankController;
use App\Http\Controllers\MeterController;
use App\Http\Controllers\PumpController;

/**
 * Public Routes (Auth required for home)
 */

// Home Page
Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home');

/**
 * Authentication Routes
 */
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/**
 * Authenticated User Routes
 */


/**
 * Admin Only Routes
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // User management routes
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::patch('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
    
    // Supplier management routes
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::patch('/suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggle-status');
    
    // Category management routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::patch('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    
    // Item management routes
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::patch('/items/{item}/toggle-status', [ItemController::class, 'toggleStatus'])->name('items.toggle-status');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    
    // Bank management routes
    Route::get('/banks', [BankController::class, 'index'])->name('banks.index');
    Route::get('/banks/create', [BankController::class, 'create'])->name('banks.create');
    Route::post('/banks', [BankController::class, 'store'])->name('banks.store');
    Route::get('/banks/{bank}', [BankController::class, 'show'])->name('banks.show');
    Route::get('/banks/{bank}/edit', [BankController::class, 'edit'])->name('banks.edit');
    Route::put('/banks/{bank}', [BankController::class, 'update'])->name('banks.update');
    Route::patch('/banks/{bank}/toggle-status', [BankController::class, 'toggleStatus'])->name('banks.toggle-status');
    
    // Vehicle management routes
    Route::get('/vehicles', [App\Http\Controllers\VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/create', [App\Http\Controllers\VehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [App\Http\Controllers\VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}', [App\Http\Controllers\VehicleController::class, 'show'])->name('vehicles.show');
    Route::get('/vehicles/{vehicle}/edit', [App\Http\Controllers\VehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle}', [App\Http\Controllers\VehicleController::class, 'update'])->name('vehicles.update');
    Route::patch('/vehicles/{vehicle}/toggle-status', [App\Http\Controllers\VehicleController::class, 'toggleStatus'])->name('vehicles.toggle-status');
    Route::delete('/vehicles/{vehicle}', [App\Http\Controllers\VehicleController::class, 'destroy'])->name('vehicles.destroy');
    
    // Employee management routes
    Route::get('/employees', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [App\Http\Controllers\EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [App\Http\Controllers\EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [App\Http\Controllers\EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [App\Http\Controllers\EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [App\Http\Controllers\EmployeeController::class, 'update'])->name('employees.update');
    Route::patch('/employees/{employee}/toggle-status', [App\Http\Controllers\EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::delete('/employees/{employee}', [App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employees.destroy');
    
    // Customer management routes
    Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [App\Http\Controllers\CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [App\Http\Controllers\CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [App\Http\Controllers\CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [App\Http\Controllers\CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [App\Http\Controllers\CustomerController::class, 'update'])->name('customers.update');
    Route::patch('/customers/{customer}/toggle-status', [App\Http\Controllers\CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::delete('/customers/{customer}', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('customers.destroy');
    
    // Payment management routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::patch('/payments/{payment}/toggle-status', [PaymentController::class, 'toggleStatus'])->name('payments.toggle-status');
    Route::patch('/payments/{id}/restore', [PaymentController::class, 'restore'])->name('payments.restore');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    
    // Invoice management routes
    Route::get('/invoices', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [App\Http\Controllers\InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [App\Http\Controllers\InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/setoff-management', [App\Http\Controllers\InvoiceController::class, 'setoffManagement'])->name('invoices.setoffManagement');
    Route::get('/invoices/setoff', [App\Http\Controllers\InvoiceController::class, 'showSetoff'])->name('invoices.showSetoff');
    Route::post('/invoices/process-setoff', [App\Http\Controllers\InvoiceController::class, 'processSetoff'])->name('invoices.processSetoff');
    Route::get('/invoices/{invoice}', [App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', [App\Http\Controllers\InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [App\Http\Controllers\InvoiceController::class, 'update'])->name('invoices.update');
    Route::patch('/invoices/{invoice}/toggle-status', [App\Http\Controllers\InvoiceController::class, 'toggleStatus'])->name('invoices.toggle-status');
    Route::delete('/invoices/{invoice}', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::patch('/invoices/{invoice}/restore', [App\Http\Controllers\InvoiceController::class, 'restore'])->name('invoices.restore');
    Route::get('/invoices/items/{id}', [App\Http\Controllers\InvoiceController::class, 'getItemDetails'])->name('invoices.getItemDetails');
    
    // Stock management routes
    Route::get('/stock', [App\Http\Controllers\StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/category/{categoryId}', [App\Http\Controllers\StockController::class, 'category'])->name('stock.category');
    Route::get('/stock/reduce/{itemId}', [App\Http\Controllers\StockController::class, 'showReduceForm'])->name('stock.reduce');
    Route::post('/stock/reduce/{itemId}', [App\Http\Controllers\StockController::class, 'reduceStock'])->name('stock.reduce.process');
    Route::get('/stock/logs/{itemId}', [App\Http\Controllers\StockController::class, 'logs'])->name('stock.logs');
    Route::delete('/stock/logs/{logId}/delete', [App\Http\Controllers\StockController::class, 'deleteLog'])->name('stock.logs.delete');
    
    // Chart of Account routes
    Route::get('/chart-of-account/{supplier}', [App\Http\Controllers\ChartOfAccountController::class, 'show'])->name('chart-of-account.show');
    
    // Tank management routes
    Route::get('/tanks', [TankController::class, 'index'])->name('tanks.index');
    Route::get('/tanks/create', [TankController::class, 'create'])->name('tanks.create');
    Route::post('/tanks', [TankController::class, 'store'])->name('tanks.store');
    Route::get('/tanks/{tank}', [TankController::class, 'show'])->name('tanks.show');
    Route::get('/tanks/{tank}/edit', [TankController::class, 'edit'])->name('tanks.edit');
    Route::put('/tanks/{tank}', [TankController::class, 'update'])->name('tanks.update');
    Route::patch('/tanks/{tank}/toggle-status', [TankController::class, 'toggleStatus'])->name('tanks.toggle-status');
    Route::delete('/tanks/{tank}', [TankController::class, 'destroy'])->name('tanks.destroy');
    
    // Meter management routes
    Route::get('/meters', [MeterController::class, 'index'])->name('meters.index');
    Route::get('/meters/create', [MeterController::class, 'create'])->name('meters.create');
    Route::post('/meters', [MeterController::class, 'store'])->name('meters.store');
    Route::get('/meters/{meter}', [MeterController::class, 'show'])->name('meters.show');
    Route::get('/meters/{meter}/edit', [MeterController::class, 'edit'])->name('meters.edit');
    Route::put('/meters/{meter}', [MeterController::class, 'update'])->name('meters.update');
    Route::patch('/meters/{meter}/toggle-status', [MeterController::class, 'toggleStatus'])->name('meters.toggle-status');
    Route::delete('/meters/{meter}', [MeterController::class, 'destroy'])->name('meters.destroy');
    
    // Pump management routes
    Route::get('/pumps', [PumpController::class, 'index'])->name('pumps.index');
    Route::get('/pumps/create', [PumpController::class, 'create'])->name('pumps.create');
    Route::post('/pumps', [PumpController::class, 'store'])->name('pumps.store');
    Route::get('/pumps/{pump}', [PumpController::class, 'show'])->name('pumps.show');
    Route::get('/pumps/{pump}/edit', [PumpController::class, 'edit'])->name('pumps.edit');
    Route::put('/pumps/{pump}', [PumpController::class, 'update'])->name('pumps.update');
    Route::patch('/pumps/{pump}/toggle-status', [PumpController::class, 'toggleStatus'])->name('pumps.toggle-status');
    Route::delete('/pumps/{pump}', [PumpController::class, 'destroy'])->name('pumps.destroy');
    
    // Sales management routes
    Route::get('/sales/create', [App\Http\Controllers\SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [App\Http\Controllers\SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/status', [App\Http\Controllers\SaleController::class, 'status'])->name('sales.status');
    Route::get('/sales/customer/{customer}', [App\Http\Controllers\SaleController::class, 'customerSales'])->name('sales.customer');
    Route::get('/sales/meter/{meter}/last-value', [App\Http\Controllers\SaleController::class, 'getLastMeterValue'])->name('sales.getLastMeterValue');
    Route::get('/sales/{sale}', [App\Http\Controllers\SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{sale}/complete', [App\Http\Controllers\SaleController::class, 'complete'])->name('sales.complete');
    Route::patch('/sales/{sale}/complete', [App\Http\Controllers\SaleController::class, 'processComplete'])->name('sales.processComplete');
    Route::patch('/sales/{sale}/update-status', [App\Http\Controllers\SaleController::class, 'updateStatus'])->name('sales.updateStatus');
    
    // System Settings routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});
