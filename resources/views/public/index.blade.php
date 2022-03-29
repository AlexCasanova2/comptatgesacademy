@extends('public.layout.main')
@section('extracss')
  <!-- aqui añadimos mas css para esta vista en concreto, si fuera necesario-->
@endsection

@section('content')

        <div class="container">
        	<div class="row">
        		<div class="col-md-12 text-center">
        			<img class="title" src="{{url('')}}/packages/images/fc-barcelona.png" />
        		</div>
        		<div class="col-md-12 text-center">
        			<h1></h1>
        		</div>
        	</div>

        	<div class="row">
        		<div class="col-xs-md  col-md-4  text-center">
        			
        		</div>
        		<div class="col-xs-md  col-md-4  text-center">
        			<div class="well">

        				<form  class="form-login" method="post" action="{{ url('/') }}">
	        				<div class="wrap">
	        					<input type="hidden" name="_token" value="{{ csrf_token() }}">
	        					<div class="row">
		        					<div class="col-xs-2">
		        						<i class="fa fa-user"></i>
		        					</div>
		        					<div class="col-xs-10">
		        						<input class="user-input" type="text" name="email" placeholder="USUARI" required="required"/>
		        					</div>
	        					</div>
	        					<div class="row">
		        					<div class="col-xs-2">
		        						<i class="fa fa-lock"></i>
		        					</div>
		        					<div class="col-xs-10">
		        						<input class="user-input" type="password" name="passw" placeholder="CONTRASENYA" required="required"/>
		    						</div>
	    						</div>

								<div class="row">
			        		
									@if(!empty($html->error_fields))
									<div class="col-xs-12">
										<div class="alert alert-danger">
											<p>Los datos de acceso no son correctos.</p>

											<ul class="list-unstyled hidden">
												@foreach($html->error_fields as $error)
													<li>{{$error}}</li>
												@endforeach
											</ul>
										</div>
									</div>
									@endif
								</div>
	        				</div>

        					<button class="btn btn-witl btn-block">ENTRAR</button>
        				</form>
        			</div>
        		</div>

        	</div>



@endsection

@section('extrajs')
  <!-- aqui añadimos mas JS para esta vista en concreto, si fuera necesario-->
  <script>


  (function(){

  })();
  </script>
@endsection
