<meta name="viewport" content="width=device-width, initial-scale=1.0" />
@extends('layouts.app2')

@section('content')
<style>
	input[type="password"]::-webkit-input-placeholder {
		color: black;
		font-weight: bold;
	}

	input[type="text"]::-webkit-input-placeholder {
		color: black;
		font-weight: bold;
	}

	input:-webkit-autofill {
		transition: all 5000s ease-in-out 0s;
</style>
{!! $errors->first('name_user',
'<div class="alert alert-danger" role="alert" align="center">
	<strong>El usuario y/o contraseña son incorrectos</strong>
</div>'
) !!}
<div class="" align="center">
	@include('plantilla.flash')
	<hr>
	<div class="col-md-5 col-md-offset-3" align="left" style="background-color: transparent; border-radius:10px;">
		<div class="panel panel-default">
			<div class="panel-heading" align="center">
				<img src="img/conserflow.png" class="img-fluid" alt="Conserflow" style="max-width:70%" >
			</div>
			<br>
			<br>
			<div class="panel-body">
				<form action="{{ route('login') }}" method="POST">
					{{ csrf_field() }}
					<div class="form-group">
						<i class="fa fa-user"></i>
						<input type="text" name="name_user" class="form-control" placeholder="Usuario" required="required">
					</div>
					<div class="form-group">
						<i class="fa fa-lock"></i>
						<input type="password" name="password" class="form-control" placeholder="Contraseña" required="required">
					</div>
					<div class="form-group">
						<button class="btn btn-success btn-block btn-lg">Ingresar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<hr>
</div>
@endsection