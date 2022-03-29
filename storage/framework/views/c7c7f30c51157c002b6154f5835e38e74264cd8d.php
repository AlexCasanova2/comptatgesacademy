<?php $__env->startSection('extracss'); ?>
<!-- aqui añadimos mas css para esta vista en concreto, si fuera necesario-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<br />
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<legend><?php echo e($html->stand->nom); ?> - <small><?php echo e(date("j F Y")); ?></small></legend>
		</div>		
	</div>
	<div class="row form-compt">
		<div class="col-sm-4"><br />
		
			
			<a href="javascript:;" class="btn btn-success btn-block" onclick="more();">NEW</a>
		
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    <input type="number"  class="form-control input-counter" name="comptatges" value="<?php echo e($html->stand->countToday()); ?>">
		  </div>
		</div>	
		<div class="col-sm-4"><br />
				<a href="javascript:;"  class="btn btn-danger btn-block" onclick="minus();">- NEW</a>
		</div>		
	</div>
	<div class="row form-compt">
		<div class="col-sm-4"><br />
		
			
			<a href="javascript:;" class="btn btn-success btn-block" onclick="repeat();">REPEAT</a>
		
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    <input type="number"  class="form-control input-counter" name="comptatgesrepeat" value="<?php echo e($html->stand->countTodayRepeat()); ?>">
		  </div>
		</div>	
		<div class="col-sm-4"><br />
				<a href="javascript:;"  class="btn btn-danger btn-block" onclick="minusrepeat();">- REPEAT</a>
		</div>		
	</div>
	<div class="row form-compt">
		<div class="col-sm-4"><br />
		
			
			<a href="javascript:;" class="btn btn-success btn-block" onclick="pass();">FCB PASS</a>
		
		</div>	
		<div class="col-sm-4"><br />
			<div class="form-group">
		    <input type="number"  class="form-control input-counter" name="comptatgespass" value="<?php echo e($html->stand->countTodayPass()); ?>">
		  </div>
		</div>	
		<div class="col-sm-4"><br />
				<a href="javascript:;"  class="btn btn-danger btn-block" onclick="minuspass();">- FCB PASS</a>
		</div>		
	</div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('extrajs'); ?>
<!-- aqui añadimos mas JS para esta vista en concreto, si fuera necesario-->
<script>
	(function() {
		

		
	})(); 
	
	

	
	
	function minus(){
		var val = $('input[name="comptatges"]').val();
		
		if(val>0){
			var val_final = parseInt(val)-1;
			$('input[name="comptatges"]').val(val_final);
			$.get( "/admin/comptatge/edit", { id:<?php echo e($html->stand->id); ?>, name,value:val_final, more:0 , repeat:0 , pass:0} )
			  .done(function( data ) {
			   	
			});
		}
		
	
	}
	
		
	
	function minusrepeat(){
		var val = $('input[name="comptatgesrepeat"]').val();
		
		if(val>0){
			var val_final = parseInt(val)-1;
			$('input[name="comptatgesrepeat"]').val(val_final);
			$.get( "/admin/comptatge/edit", { id:<?php echo e($html->stand->id); ?>, name,value:val_final, more:0,  repeat:1 , pass:0} )
			  .done(function( data ) {
			   
			});
		}
		
	
	}
	
	function minuspass(){
		var val = $('input[name="comptatgespass"]').val();
		if(val>0){
			var val_final = parseInt(val)+1;
			$('input[name="comptatgespass"]').val(val_final);
			$.get( "/admin/comptatge/edit", { id:<?php echo e($html->stand->id); ?>, name,value:val_final, more:0, repeat:0, pass:0 } )
			  .done(function( data ) {
			   
			});
		}
			
	}
	
	
	function more(){
		var val = $('input[name="comptatges"]').val();
		var val_final = parseInt(val)+1;
		$('input[name="comptatges"]').val(val_final);
		$.get( "/admin/comptatge/edit", { id:<?php echo e($html->stand->id); ?>, name,value:val_final, more:1 , repeat:0, pass:0} )
		  .done(function( data ) {
		   	
		});
			
	}
	function repeat(){
		var val = $('input[name="comptatgesrepeat"]').val();
		var val_final = parseInt(val)+1;
		$('input[name="comptatgesrepeat"]').val(val_final);
		$.get( "/admin/comptatge/edit", { id:<?php echo e($html->stand->id); ?>, name,value:val_final, more:1, repeat:1, pass:0 } )
		  .done(function( data ) {
		   
		});
			
	}
	
	function pass(){
		var val = $('input[name="comptatgespass"]').val();
		var val_final = parseInt(val)+1;
		$('input[name="comptatgespass"]').val(val_final);
		$.get( "/admin/comptatge/edit", { id:<?php echo e($html->stand->id); ?>, name,value:val_final, more:1, repeat:0, pass:1 } )
		  .done(function( data ) {
		   
		});
			
	}
	

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>