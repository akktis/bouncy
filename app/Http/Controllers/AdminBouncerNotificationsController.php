<?php 
	namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminBouncerNotificationsController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function cbInit() {
			$this->form   = array();

			$this->form[] = array("label"=>"Group","name"=>"bouncer","type"=>"select","datatable"=>"bouncer,name","datatable_format"=>"name,' - (',company_id,')'");

			$this->form[] = array("label"=>"Title","name"=>"title","type"=>"text","required"=>TRUE,"validation"=>"required|min:3|max:255|alpha_spaces");
			$this->form[] = array("label"=>"Body","name"=>"body","type"=>"textarea","required"=>TRUE,'placeholder'=>'Body text');
			$this->form[] = array("label"=>"Url","name"=>"url","type"=>"text","required"=>TRUE,"validation"=>"required|min:3|max:255|alpha_spaces","placeholder"=>"Link when the user click on the notif");
			$this->form[] = array("label"=>"Icon","name"=>"icon","type"=>"text","required"=>TRUE,"validation"=>"required|min:3|max:255|alpha_spaces","placeholder"=>"Image of this notif");



			echo view("sendnotifications",['forms'=>$this->form]);
			die();

		}


		public function postSend() {
			/*
				curl -X POST -H "Authorization: key=AAAAb3XFDlE:APA91bGvb2cQxT13dNRKe47rRP7LSuljXKS6jsc_6XbHJpKdLOYnjWRD0nkcNu4aVUb5o8Fz9MPg7uD5DI8u4HPlRz-0XZ4O6pgVKk6XwHoILGQJET9T--iibvUPiQmztHfvqNTccaJU" -H "Content-Type: application/json" -d '{
				    "notification": {
				      "title": "Portugal vs. Denmark",
				      "body": "5 to 1",
				      "icon": "firebase-logo.png",
				      "click_action": "http://localhost:8081"
				    },
				    "to": "/topics/4"
				  }' "https://fcm.googleapis.com/fcm/send"
			*/

			$arr = array(
				"notification" => 
					array(
			    		"title" => $_POST['title'],
			    		"body" => $_POST['body'],
			    		"icon" => $_POST['icon'],
			    		"click_action" => $_POST['url']
			    	),
			    "to" => "/topics/".$_POST['bouncer']
			);

			$data = json_encode($arr);

			$url = "https://fcm.googleapis.com/fcm/send";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: key=AAAAb3XFDlE:APA91bGvb2cQxT13dNRKe47rRP7LSuljXKS6jsc_6XbHJpKdLOYnjWRD0nkcNu4aVUb5o8Fz9MPg7uD5DI8u4HPlRz-0XZ4O6pgVKk6XwHoILGQJET9T--iibvUPiQmztHfvqNTccaJU', 'content-type: application/json', 'Content-Length: ' . strlen($data)));
			$return = json_decode(curl_exec($ch), true);
			curl_close($ch);


			echo view("sentnotification",['return'=>$return]);
		}


	}