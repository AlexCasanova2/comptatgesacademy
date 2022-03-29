<?php $__env->startSection('extracss'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<br />
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<legend><?php echo e($html->stand->nom); ?> - <small><?php echo e($html->day); ?>/<?php echo e($html->month); ?>/<?php echo e($html->year); ?> <?php echo e($html->hour); ?>h</small></legend>
		</div>		
	</div>
	<div class="row form-compt">
		<div class="col-sm-4"><br />
			<a href="javascript:;" class="btn btn-success btn-block btn-comptatge" data-type="new" data-operation="1">SAVE NEW</a>
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    	<input type="number"  class="form-control input-counter" name="comptatges" value=""> ACTUAL: <?php echo e($html->stand->countTodayEdit($html->year,$html->month,$html->day,$html->hour)); ?>

		  	</div>
		</div>	
			
	</div>
	<div class="row form-compt">
		<div class="col-sm-4"><br />
			<a href="javascript:;" class="btn btn-success btn-block btn-comptatge" data-type="repeat" data-operation="1">SAVE REPEAT</a>
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    	<input type="number"  class="form-control input-counter" name="comptatgesrepeat" value=""> ACTUAL: <?php echo e($html->stand->countTodayRepeatEdit($html->year,$html->month,$html->day,$html->hour)); ?>

		  	</div>
		</div>	
				
	</div>
	<div class="row form-compt">
		<div class="col-sm-4"><br />
			<a href="javascript:;" class="btn btn-success btn-block btn-comptatge" data-type="pass" data-operation="1">SAVE PASS</a>
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    	<input type="number"  class="form-control input-counter" name="comptatgespass" value=""> ACTUAL: <?php echo e($html->stand->countTodayPassEdit($html->year,$html->month,$html->day,$html->hour)); ?>

		  	</div>
		</div>	
		
	</div>
	<div class="row form-compt">
		<div class="col-sm-4"><br />
			<a href="javascript:;" class="btn btn-success btn-block btn-comptatge" data-type="soci" data-operation="1">SAVE SOCI</a>
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    	<input type="number"  class="form-control input-counter" name="comptatgessoci" value=""> ACTUAL: <?php echo e($html->stand->countTodaySociEdit($html->year,$html->month,$html->day,$html->hour)); ?>

		  	</div>
		</div>	
			
	</div>

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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extrajs'); ?>
<!-- aqui añadimos mas JS para esta vista en concreto, si fuera necesario-->
<script>
	var comptatge = {};
	
	$(function(){
		$(document).on('click', '.btn-comptatge', function(){
			comptatge['type'] = $(this).data('type');
			console.log($(this).parent().parent().find('input').val());
			
			comptatge['vals'] = $(this).val();
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
			realitzarComptatge(comptatge['operation'], $('input[name=edat]:checked').val(), comptatge['type'],comptatge['vals']);
		});
	});

	function realitzarComptatge(operation, edat, type,vals){
		var $input;
		switch (type){
			case 'new': $input = $('input[name=comptatges]'); break;
			case 'repeat': $input = $('input[name=comptatgesrepeat]'); break;
			case 'pass': $input = $('input[name=comptatgespass]'); break;
			case 'soci': $input = $('input[name=comptatgessoci]'); break;
		}
		

		$.get('/admin/comptatge/editbyday', {
			id:<?php echo e($html->stand->id); ?>

			, name
			, more: operation > 0 ? 1 : 0 
			, repeat: type == 'repeat' ? 1 : 0 
			, pass: type == 'pass' ? 1 : 0
			, soci: type == 'soci' ? 1 : 0
			, year: <?php echo e($html->year); ?>

			, month: <?php echo e($html->month); ?>

			, day: <?php echo e($html->day); ?>

			, hour: <?php echo e($html->hour); ?>

			, number: $input.val()
			, edat: edat
			
			})
		.done(function(data){
			comptatge = {};
			$('#edatModal').modal('toggle');
		});
	}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>