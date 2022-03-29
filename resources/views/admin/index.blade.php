@extends('admin.layout.main')

@section('extracss')
<!-- aqui añadimos mas css para esta vista en concreto, si fuera necesario-->
@endsection

@section('content')
<br/>
<div class="container ">
	
	<div class="row">
		<div class="col-md-12">
			<legend></legend>
		</div>		
	</div>

	<div class="row">
		<div class="col-md-12">
			<legend>Totals per stand</legend>
				<hr/>
			<table class="table table-striped">
			  	<thead>
			    <tr>
					<th class="">Stand</th>
					<th class="text-center">Visites Noves</th>
					<th class="text-center">Visites repetides</th>
					<!--<th class="text-center">Visites amb PASS</th>-->
					<th class="text-center">Total</th>
			    </tr>
			  	</thead>
			  	<tbody>
			  		@foreach(App\Stands::get() as $key => $stand)
			    	<tr>
			    	  	<td><a href="{{url('/admin/stand/'.$stand->id)}}"><i class="fas fa-external-link-alt"></i> {{$stand->nom}} </a></td>
			    	  	<td class="text-center">{{$stand->countToday()}}</td>
			    	  	<td class="text-center">{{$stand->countTodayRepeat()}}</td>
			    	  	<!--<td class="text-center">{{$stand->countTodayPass()}}</td>-->
			    	  	<td class="text-center">{{$stand->countTodayAll()}}</td>
			    	</tr>
			   	@endforeach
			  </tbody>
			</table>
		</div>
	</div>

</div>

@endsection

@section('extrajs')
<!-- aqui añadimos mas JS para esta vista en concreto, si fuera necesario-->
<script>
	(function() {
		
	})(); 
</script>
@endsection
