<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('music')->group(function () {
    Route::post('url', 'MusicController@url');
});

Route::prefix('ku-gou')->group(function () {
    Route::post('url', 'KuGouController@url');
});

Route::prefix('test')->group(function () {
    Route::post('statistic', function () {
        class A {
            public function say() {
                echo 123;
            }
        }

        class B {
            public function can() {
                echo 'can';
            }
        }

        $a = new A;
        $a->say();

        $b = new B;
        $b->can();
    });
});
