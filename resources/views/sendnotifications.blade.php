@extends("crudbooster::admin_template")

@section("content")


	<div class="panel panel-primary">
		<div class="panel-heading">
			Notification
		</div>
		<div class="panel-body">
		<form class='form-horizontal' method='post' id="form" enctype="multipart/form-data" action='{{CRUDBooster::mainpath("send")}}'>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">  
			<input type='hidden' name='return_url' value='{{Request::fullUrl()}}'/>
			

			@include("crudbooster::default.form_body")


		</div>
			<p align="right"><input type='submit' class='btn btn-primary' value='Send'/></p>
		</form>
	</div>


@endsection