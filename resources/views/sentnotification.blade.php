@extends("crudbooster::admin_template")

@section("content")

	<label>Result</label>
	<?php
		if(array_key_exists('message_id', $return) && $return["message_id"] > 0) {
			echo "<br>Message Sent!<br><br>";
		} else {
			echo "<pre>";
			var_dump($return);
			echo "</pre>";
		}
	?>
	<a href="{{ CRUDBooster::mainpath() }}">Back</a>


@endsection