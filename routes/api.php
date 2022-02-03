<?php

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/get-lgoo/{id}', function($id) {
    $data = State::find($id)->lgas;
	return response()->json($data);
})->name('get_lgas');

Route::get('/personnel/sync', function(){
    return 'It works!';
});