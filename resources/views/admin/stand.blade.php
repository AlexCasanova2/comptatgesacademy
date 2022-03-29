@extends('admin.layout.main')

@section('extracss')

@endsection

@section('content')
<br />
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<legend>{{$html->stand->nom}} - <small>{{date("j F Y")}}</small></legend>
		</div>		
	</div>
	<!--
	<div class="row form-compt">
		<div class="col-sm-4"><br />
			<a href="javascript:;" class="btn btn-success fcb-block btn-comptatge btn-block" data-type="pass" data-operation="1">FIRST</a>
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    	<input type="number"  class="form-control input-counter" name="comptatgespass" value="{{$html->stand->countTodayPass()}}">
		  	</div>
		</div>	
		<div class="col-sm-4"><br />
			<a href="javascript:;"  class="btn btn-danger fcb-block btn-comptatge btn-block" data-type="pass" data-operation="-1">- FIRST</a>
		</div>		
	</div>
-->
	<div class="row form-compt">
		<div class="col-sm-4"><br />
			<a href="javascript:;" class="btn btn-success btn-block btn-comptatge" data-type="new" data-operation="1">NEW</a>
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    	<input type="number"  class="form-control input-counter" name="comptatges" value="{{$html->stand->countToday()}}">
		  	</div>
		</div>	
		<div class="col-sm-4"><br />
			<a href="javascript:;"  class="btn btn-danger btn-block btn-comptatge" data-type="new" data-operation="-1">- NEW</a>
		</div>		
	</div>
	<div class="row form-compt">
		<div class="col-sm-4"><br />
			<a href="javascript:;" class="btn btn-success btn-block btn-comptatge" data-type="repeat" data-operation="1">REPEAT</a>
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    	<input type="number"  class="form-control input-counter" name="comptatgesrepeat" value="{{$html->stand->countTodayRepeat()}}">
		  	</div>
		</div>	
		<div class="col-sm-4"><br />
			<a href="javascript:;"  class="btn btn-danger btn-block btn-comptatge" data-type="repeat" data-operation="-1">- REPEAT</a>
		</div>		
	</div>
	
	<!-- div class="row form-compt">
		<div class="col-sm-4"><br />
			<a href="javascript:;" class="btn btn-success btn-block btn-comptatge" data-type="soci" data-operation="1">SOCI</a>
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    	<input type="number"  class="form-control input-counter" name="comptatgessoci" value="{{$html->stand->countTodaySoci()}}">
		  	</div>
		</div>	
		<div class="col-sm-4"><br />
				<a href="javascript:;"  class="btn btn-danger btn-block btn-comptatge" data-type="soci" data-operation="-1">- SOCI</a>
		</div>		
	</div-->

</div>
<div class="modal fade" id="edatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Edat del participant</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<div class="col-md-12 text-center">
					<label class="radio-inline">
						<input type="radio" name="edat" value="5"> < 6
					</label><br/>
					<label class="radio-inline">
						<input type="radio" name="edat" value="12"> 6 - 12
					</label><br/>
					<label class="radio-inline">
						<input type="radio" name="edat" value="19"> 13 - 19
					</label><br/>
					<label class="radio-inline">
						<input type="radio" name="edat" value="20"> > 19
					</label>
				</div>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Tancar</button>
			  <button type="button" class="btn btn-primary btn-crear-comptatge">Comptar</button>
			</div>
		  </div>
		</div>
	  </div>
@endsection

@section('extrajs')
<!-- aqui añadimos mas JS para esta vista en concreto, si fuera necesario-->
<script>
	var comptatge = {};
	
	$(function(){
		$(document).on('click', '.btn-comptatge', function(){
			comptatge['type'] = $(this).data('type');
			comptatge['operation'] = $(this).data('operation');

			switch (comptatge['type']){
				case 'new': $input = $('input[name=comptatges]'); break;
				case 'repeat': $input = $('input[name=comptatgesrepeat]'); break;
				case 'pass': $input = $('input[name=comptatgespass]'); break;
				case 'soci': $input = $('input[name=comptatgessoci]'); break;
			}

			if (comptatge['operation'] < 0 && $input.val() == 0){
				return false;
			}
			$('#edatModal').modal();
		});

		$(document).on('click', '.btn-crear-comptatge', function(){
			if ($('input[name=edat]:checked').length == 0){
				return false;
			}
			realitzarComptatge(comptatge['operation'], $('input[name=edat]:checked').val(), comptatge['type']);
		});
	});

	function realitzarComptatge(operation, edat, type){
		var $input;
		switch (type){
			case 'new': $input = $('input[name=comptatges]'); break;
			case 'repeat': $input = $('input[name=comptatgesrepeat]'); break;
			case 'pass': $input = $('input[name=comptatgespass]'); break;
			case 'soci': $input = $('input[name=comptatgessoci]'); break;
		}
		value = operation > 0 ? parseInt($input.val()) + 1 : parseInt($input.val()) - 1;
		$input.val(value);

		$.get('/admin/comptatge/edit', {
			id:{{$html->stand->id}}
			, name
			, value: value
			, more: operation > 0 ? 1 : 0 
			, repeat: type == 'repeat' ? 1 : 0 
			, pass: type == 'pass' ? 1 : 0
			, soci: type == 'soci' ? 1 : 0
			, edat: edat
			})
		.done(function(data){
			comptatge = {};
			$('#edatModal').modal('toggle');
		});
	}
</script>
@endsection
