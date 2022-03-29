<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>FCB Comptatges - Administrador</title>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<!-- Fontawesome -->
		<script src="https://kit.fontawesome.com/d02b701383.js"></script>
		<link href="{{url('')}}/packages/bootstrap-sweetalert-master/lib/sweet-alert.css" rel="stylesheet">
 		@yield('extracss')		
		 <link href="{{url('')}}/packages/css/custom.css" rel="stylesheet">
		 <link href="{{url('')}}/packages/css/chart.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
	
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container">
	<a class="navbar-brand" href="{{url('/admin')}}">
	    <img src="{{url('')}}/packages/images/logo_1.png"  height="50" alt="">
	  </a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  </button>
	
	  <div class="collapse navbar-collapse" id="navbarSupportedContent">
	    <ul class="navbar-nav mr-auto">
	    	
	    	@if($html->user->inRole('admin'))
	      <li class="nav-item">
	        <a class="nav-link" href="{{url('/admin/stands')}}">Stands</a>
	      </li>
  	      <li class="nav-item">
	        <a class="nav-link" href="{{url('admin/statics')}}">Estadistiques</a>
	      </li>
	    @endif
	      <li class="nav-item dropdown">
	        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	           {{$html->user->first_name}} {{$html->user->last_name}}
	        </a>
	        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
	          <a class="dropdown-item" href="{{ url('/logout') }}">Desconectar</a>
	        </div>
	      </li>
	     
	    </ul>
	    
	  </div>
	  </div>
	</nav>

    @yield('content')
	
	<footer>
		<div class="container-fluid">
			<div class="row">
				
			</div>
		</div>
	</footer>    
	    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="{{url('')}}/packages/bootstrap-sweetalert-master/lib/sweet-alert.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    @yield('extrajs')
  </body>
</html>
