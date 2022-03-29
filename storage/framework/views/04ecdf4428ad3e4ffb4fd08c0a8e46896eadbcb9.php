<?php $__env->startSection('extracss'); ?>
  <!-- aqui añadimos mas css para esta vista en concreto, si fuera necesario-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

        <div class="container">
        	<div class="row">
        		<div class="col-md-12 text-center">
        			<img class="title" src="<?php echo e(url('')); ?>/packages/images/fc-barcelona.png" />
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

        				<form  class="form-login" method="post" action="<?php echo e(url('/')); ?>">
	        				<div class="wrap">
	        					<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
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
			        		
									<?php if(!empty($html->error_fields)): ?>
									<div class="col-xs-12">
										<div class="alert alert-danger">
											<p>Los datos de acceso no son correctos.</p>

											<ul class="list-unstyled hidden">
												<?php $__currentLoopData = $html->error_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<li><?php echo e($error); ?></li>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</ul>
										</div>
									</div>
									<?php endif; ?>
								</div>
	        				</div>

        					<button class="btn btn-witl btn-block">ENTRAR</button>
        				</form>
        			</div>
        		</div>

        	</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('extrajs'); ?>
  <!-- aqui añadimos mas JS para esta vista en concreto, si fuera necesario-->
  <script>


  (function(){

  })();
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>