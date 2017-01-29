<?php

use App\Lib\Downloader;

$namespace = '\crocodicstudio\crudbooster\controllers';

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
    return view('welcome');
});

/* ROUTER FOR BACKEND CRUDBOOSTER */
Route::group(['middleware'=>['web','\crocodicstudio\crudbooster\middlewares\CBBackend'],'prefix'=>config('crudbooster.ADMIN_PATH'),'namespace'=>$namespace], function () {
	
	Route::get('toto', function() {
		return view('admin.getcontent-tools');
	});
	
	Route::get('download', function() {
		$downloader = new App\Lib\Downloader();
		
		$decode = base64_decode($_GET['url']);
		if(base64_encode($decode) === $_GET['url']) {
			return $downloader->getPage($decode, 'download', false, true);
		} else {
			return $downloader->getPage($_GET['url'], 'download', true);
		}
	});
	
});

