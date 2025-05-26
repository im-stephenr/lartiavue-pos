<?php

use App\Http\Controllers\TestFormController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// DEMO ROUTES
// Route::get('/', function () { 
//     return Inertia::render('Home'); // automatically detects the Home.vue file under resources/js/pages
// })->name('home');

// Route::get('/about', function () { 
//     return inertia('About', ['name' => 'Stephen']); // Inertia::render and inertia are just the same functionality
// })->name('about');

// Route::inertia('/register', 'Auth/Register')->name('register');
// Route::post('/register', [TestFormController::class, 'create']);

// Shorthand technique
// Route::inertia('/','Home')->name('home'); // This will only render a Static Vue page without fetching data from Laravel Controller


/* FINAL ROUTES */
/**
 * GUESTS
 */
// Adding middleware GUEST with grouped routes will prevent AUTHENTICATED user to access these routes
// Middleware guest is a built in feature of laravel from 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
Route::middleware('guest')->group(function(){
    //   Register
    Route::inertia('/auth/register', 'Register')->name('register');
    Route::post('/auth/register', [UserController::class, 'register']);

    //   Login
    Route::inertia('/auth/login', 'Login')->name('login');
    Route::post('/auth/login', [UserController::class, 'login']);
 });


 /**
  * AUTHENTICATED
  */
// Adding middleware AUTH with grouped routes will prevent UNAUTHENTICATED user to access these routes
// Middleware auth is a built in feature of laravel from 'auth' => \App\Http\Middleware\RedirectIfAuthenticated::class,
 Route::middleware('auth')->group(function(){
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos');

    // Products
    Route::get('products', [ProductController::class, 'index'])->name('products');
    Route::post('products', [ProductController::class, 'store'])->name('products.add');
    Route::post('products/{productId}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{productId}', [ProductController::class, 'delete'])->name('products.delete');

    // Categories
   //  Route::inertia('/categories', 'Categories')->name('categories'); 
    Route::get('categories', [CategoryController::class, 'index'])->name('categories'); // the Controller already fetches data and sends it as a props into Vue page file
    // Add
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.add');
   //  Update
    Route::put('categories/{categoryId}', [CategoryController::class, 'update'])->name('categories.update');
   //  Delete
   Route::delete('categories/{categoryId}', [CategoryController::class, 'delete'])->name('categories.delete');

   // Sale
   Route::post('sale', [SaleController::class, 'store'])->name('sale.add');

   // Settings
   Route::inertia('settings', 'Settings')->name('settings');

   // Manage User
   Route::get('users', [UserController::class, 'profile'])->name('users.profile');
   Route::post('users/{userId}', [UserController::class, 'update'])->name('users.profile.update');
   Route::put('users/{userId}', [UserController::class, 'updatePassword'])->name('users.password.update');

    // Auth logout
    Route::post('/auth/logout', [UserController::class, 'logout'])->name('logout');
 });



