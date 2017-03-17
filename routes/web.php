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


Route::get('ti.js', function() {
	session_start();

	$token = $_GET['token'];

	$query = 'SELECT id,company_id FROM bouncer WHERE `key` = :key';
	$row = collect(DB::select($query, array("key"=>$_GET['key'])))->map(function($x){ return (array) $x; })->toArray();
	$company_id = $row[0]['company_id'];
	$bouncer_id = $row[0]['id'];


	$query = "SELECT id FROM `client` WHERE `company_id` = :company_id AND `session_id` = :session_id";
	$row = collect(DB::select($query, array("company_id"=>$row[0]['company_id'], 'session_id'=>session_id())))->map(function($x){ return (array) $x; })->toArray();
	$client_id = $row[0]['id'];


	$url = 'https://iid.googleapis.com/iid/v1/'.$token.'/rel/topics/'.$bouncer_id;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, count([]));
	curl_setopt($ch, CURLOPT_POSTFIELDS, []);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: key=AAAAb3XFDlE:APA91bGvb2cQxT13dNRKe47rRP7LSuljXKS6jsc_6XbHJpKdLOYnjWRD0nkcNu4aVUb5o8Fz9MPg7uD5DI8u4HPlRz-0XZ4O6pgVKk6XwHoILGQJET9T--iibvUPiQmztHfvqNTccaJU', 'content-type: application/json'));
	$return = curl_exec($ch);
	echo $return;
	curl_close($ch);



	$query = "INSERT INTO `notification_subscribtion` SET `company_id` = :company_id, `client_id` = :client_id, `token` = :token, `bouncer_id` = :bouncer_id, created_at = NOW() ON DUPLICATE KEY UPDATE  token = :token_u, updated_at = NOW()";

	DB::statement($query, array(
		'company_id'=> $company_id,
		'bouncer_id'=> $bouncer_id,
		'client_id'=> $client_id,
		'token' => $token,
		'token_u' => $token
	));

});


Route::get('tt.js', function() {
	//Token info
	session_start();
	$json = json_decode($_GET['data'], true);
	$token = $json['token'];


	$query = 'SELECT id,company_id FROM bouncer WHERE `key` = :key';
	$row = collect(DB::select($query, array("key"=>$json['key'])))->map(function($x){ return (array) $x; })->toArray();
	$company_id = $row[0]['company_id'];
	$bouncer_id = $row[0]['id'];


	$query = "SELECT id FROM `client` WHERE `company_id` = :company_id AND `session_id` = :session_id";
	$row = collect(DB::select($query, array("company_id"=>$company_id, 'session_id'=>session_id())))->map(function($x){ return (array) $x; })->toArray();
	$client_id = $row[0]['id'];

	$query = "INSERT INTO `token` SET `client_id` = :client_id, `company_id` = :company_id, `bouncer_id` = :bouncer_id,  `token` = :token, created_at = NOW() ON DUPLICATE KEY UPDATE  `bouncer_id` = :bouncer_id_u, `client_id` = :client_id_u, `company_id` = :company_id_u, `token` = :token_u, updated_at = NOW()";


	DB::statement($query, array(
		'client_id'=> $client_id,
		'client_id_u' => $client_id,
		'company_id' => $company_id,
		'company_id_u' => $company_id,
		'bouncer_id'=> $bouncer_id,
		'bouncer_id_u'=> $bouncer_id,
		'token'=> $token,
		'token_u'=> $token,
	));

	setcookie("id", session_id());
});




Route::get('shop', function() {
	//Token info
	session_start();


	$query = "SELECT id FROM `client` WHERE `session_id` = :session_id";
	$row = collect(DB::select($query, array( 'session_id'=>session_id())))->map(function($x){ return (array) $x; })->toArray();
	$client_id = $row[0]['id'];

	$query = "SELECT product FROM `data_targeting` WHERE client_id = :client_id AND (product IS NOT NULL and product != '') ORDER BY created_at LIMIT 1";
	$row = collect(DB::select($query, array('client_id'=>$client_id)))->map(function($x){ return (array) $x; })->toArray();

	$product = $row[0]["product"];

	
	return redirect('http://rest.mntzm.com/Mix/Partner/Offer.html?query='+$product+'&apikey=1PMOYV58C9CB19985C9&nb=8&outof=100&sortBy=score&sortDir=desc&countryCode=fr&imageFormat=large');
	//setcookie("id", session_id());
});


Route::get('ta.js', function() {
	session_start();
	$json = json_decode($_GET['data'], true);

	$query = 'SELECT id,company_id FROM bouncer WHERE `key` = :key';
	$row = collect(DB::select($query, array("key"=>$json['key'])))->map(function($x){ return (array) $x; })->toArray();

	//echo "<pre>";

	$query = "INSERT INTO `client` SET `session_id` = :session_id, `company_id` = :company_id, `bouncer_id` = :bouncer_id, `ip` = :ip, `userAgent` = :userAgent, created_at = NOW() ON DUPLICATE KEY UPDATE  `bouncer_id` = :bouncer_id_u, `ip` = :ip_u, `userAgent` = :userAgent_u, updated_at = NOW()";

	$company_id = $row[0]['company_id'];
	$bouncer_id = $row[0]['id'];

	DB::statement($query, array(
		'company_id'=> $company_id,
		'bouncer_id'=> $bouncer_id,
		'ip'=> Request::ip(),
		'userAgent'=> $json['userAgent'],
		'session_id'=>session_id(),
		'bouncer_id_u'=> $row[0]['id'],
		'ip_u'=> Request::ip(),
		'userAgent_u'=> $json['userAgent'],
	));


	$query = "SELECT id FROM `client` WHERE `company_id` = :company_id AND `session_id` = :session_id";
	$row = collect(DB::select($query, array("company_id"=>$row[0]['company_id'], 'session_id'=>session_id())))->map(function($x){ return (array) $x; })->toArray();
	$client_id = $row[0]['id'];
	//echo "<pre>";

	$price_arr = $json['price'];
	$price = 0;
	$currency = "";

	if(is_array($price_arr) && array_key_exists('price', $price_arr) && array_key_exists('currency', $price_arr)) {
		$price = $price_arr['price'];
		$currency = $price_arr['currency'];
	}


	$query = "INSERT INTO data_targeting SET url = :url, title = :title, userAgent = :userAgent, product = :product, action = :action, client_id = :client_id, company_id = :company_id, bouncer_id = :bouncer_id, created_at = NOW(), product_price = :product_price, product_currency = :product_currency, product_category = :product_category";

	DB::statement($query, array(
		'url' => $json['url'],
		'title' => $json['title'],
		'userAgent' => $json['userAgent'],
		'product' => $json['product'],
		'action' => $json['action'],
		'client_id' => $client_id,
		'company_id' => $company_id,
		'bouncer_id' => $bouncer_id,
		'product_category' => $json['category'],
		'product_currency' => $currency,
		'product_price' => $price

	));

	setcookie("id", session_id());
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

