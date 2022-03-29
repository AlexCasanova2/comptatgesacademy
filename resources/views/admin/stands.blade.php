@extends('admin.layout.main')

@section('extracss')
<!-- aqui añadimos mas css para esta vista en concreto, si fuera necesario-->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>

@endsection

@section('content')

<div class="container">

	<div class="row">
		<div class="col-md-6">

			@if (session('message'))
			<br />
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<p>
					<small>{{ session('message') }}</small>
				</p>
			</div>
			@endif
		</div>

		<div class="col-md-6 float-right text-right ">
			<br />
			<button type="button" class="btn btn-primary" onclick="openModalNew()">
				Nou stand
			</button>

		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-12">
			<table class="table" id="table">
				<thead>
					<tr>
						<th class="">#id</th>
						<th class="">Nom</th>
						<th ></th>

					</tr>
				</thead>
				<tbody>
					<!-- ajax loaded content -->
				</tbody>
			</table>

		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">

		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Stand</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<form class="form-product" action="{{url('/admin/stands')}}" method="post" enctype="multipart/form-data">
							<input type="hidden" name="id" value="0">
							<div class="row">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="col-md-12">
									<div class="form-group">
										<label>Nom</label>
										<input type="text" name="name" class="form-control"  required="required">
									</div>
								</div>
								<div class="col-md-12">
									<button type="submit" class="btn btn-success">
										Guardar stand
									</button>
								</div>
							</div>
						</form>

					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection

@section('extrajs')
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<script>
	var current_token = "{{ csrf_token() }}";
	var table = null;

	(function() {
		table = $('#table').DataTable({
			"processing" : true,
			"serverSide" : true,
			"language" : {
				"sProcessing" : "Processant...",
				"sLengthMenu" : "Mostra _MENU_ registres",
				"sZeroRecords" : "No s'han trobat registres.",
				"sInfo" : "Mostrant de _START_ a _END_ de _TOTAL_ registres",
				"sInfoEmpty" : "Mostrant de 0 a 0 de 0 registres",
				"sInfoFiltered" : "(filtrat de _MAX_ total registres)",
				"sInfoPostFix" : "",
				"sSearch" : "Filtrar:",
				"sUrl" : "",
				"oPaginate" : {
					"sFirst" : "Primer",
					"sPrevious" : "Anterior",
					"sNext" : "Següent",
					"sLast" : "Últim"
				}
			},
			"ajax" : {
				"url" : "/admin/stands/datatables",
				"type" : "POST",
				"data" : function(d) {
					d._token = current_token;
				},
				"complete" : function(settings, json) {
					// stopLoading();
				}
			}
		});

		jQuery('.upload-result').hide();

	})();

	function openModalNew() {
		$('#modal').modal();
		$('.form-product input[name="name"]').val("");

	}

	function openModalEdit(id) {
		$('#modal').modal();

		$.post("/admin/products/get/" + id, {
			'_token' : current_token
		}, function(json) {
			$('.form-product input[name="name"]').val(json.name);
		}, "json")

	}

	function removeBox(id, name) {
		swal({
			title : "¿Vol esborrar aquest stand?",
			text : name,
			type : "warning",
			showCancelButton : true,
			confirmButtonClass : "btn-danger",
			confirmButtonText : "Esborrar",
			cancelButtonText : "Cancel·lar",
			closeOnConfirm : false
		}, function() {
			$('.confirm').addClass('disabled');
			$('.cancel').hide();
			$.post("/admin/stands/remove/" + id, {
				'_token' : current_token
			}, function(json) {
				$('.confirm').removeClass('disabled');
				if (json.SUCCESS == "OK") {
					table.ajax.reload();
					swal({
						title : "¡Esborrat!",
						text : "Stand esborrat",
						type : "success",
						confirmButtonText : "Aceptar"
					});
				} else {
					swal({
						title : "¡Ups!",
						text : "Stand no esborrat",
						type : "error",
						confirmButtonText : "Aceptar"
					});
				}

			}, "json");

		});
	}

</script>
@endsection
