<?php $__env->startSection('extracss'); ?>
<!-- aqui añadimos mas css para esta vista en concreto, si fuera necesario-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<br />
<div class="container ">
	<div class="row">
		<div class="col-md-12">
			<legend></legend>
		</div>		
	</div>


	<div class="row">
		
		
		<div class="col-md-12">
			<legend>Totals per stand</legend>
				<hr />
			<table class="table table-striped">
			  <thead>
			    <tr>
					<th class="">Stand</th>
					<th class="text-center">Visites Noves</th>
					<th class="text-center">Visites repetides</th>
					<th class="text-center">Visites amb PASS</th>
					<th class="text-center">Total</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $__currentLoopData = App\Stands::get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $stand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			    <tr>
			      <td><a href="<?php echo e(url('/admin/stand/'.$stand->id)); ?>"><i class="fas fa-external-link-alt"></i> <?php echo e($stand->nom); ?> </a></td>
			      <td class="text-center"><?php echo e($stand->countToday()); ?></td>
			      <td class="text-center"><?php echo e($stand->countTodayRepeat()); ?></td>
			      <td class="text-center"><?php echo e($stand->countTodayPass()); ?></td>
			      <td class="text-center"><?php echo e($stand->countTodayAll()); ?></td>
			    </tr>
			   	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			  </tbody>
			</table>
			
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('extrajs'); ?>
<!-- aqui añadimos mas JS para esta vista en concreto, si fuera necesario-->
<script>
	(function() {
		
	})(); 
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>