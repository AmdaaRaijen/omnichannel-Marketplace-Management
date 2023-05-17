<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;




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




Route::get('/', function () {
  return redirect('/admin');
});




Route::prefix('admin')->group(function () {
  Route::controller(LoginController::class)->group(function () {
       Route::get('login', 'showLoginForm')->name('login');
       Route::post('login', 'login');


       Route::middleware(['auth'])->group(function () {
         Route::post('logout', 'logout')->name('logout');
       });
  });




  Route::middleware(['auth'])->group(function () {
      Route::get('/', function () {
          return 'Success';
      });
  });
});