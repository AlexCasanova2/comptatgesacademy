<?php $__env->startSection('extracss'); ?>
<!-- aqui añadimos mas css para esta vista en concreto, si fuera necesario-->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>
<style>
	a {
		cursor:pointer;
	}
	.container-fluid a:hover, .view.active{
		color: red !important;
	}
	#grafica{
		display:none;
	}
	#graella{
		overflow-x: auto;
	}
	.mt-50{
		margin-top: 50px !important;
	}
	.chart-container-perAge, .chart-container-total, .chart-container-perHour{
		padding: 0px 200px 0px 200px;
	}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
	<br/>
	<div class="row">
		<div class="col-md-12">
		<a class="export export-all" data-table="graella" data-filename="graella"><i class="fas fa-file-excel excel"></i> Exportar</a>
		<a class="view" data-show="graella"><i class="fas fa-table"></i> Graella</a>
		<a class="view" data-show="grafica"><i class="fas fa-chart-bar"></i> Gràfiques</a>
			<br /><br />
		</div>
	</div>
	<div class="row statistics" id="graella">
		
	</div>
	<div class="row graphs" id="grafica">
		<div class="col-md-12 text-center graphPerDay">
			<h4>TOTAL STANDS<a class="linkGraph"><i class="fas fa-angle-up"></a></i></h4>
			<hr>
		</div>
		<div class="col-md-12 text-center mt-50 graphPerHour">
			<h4>AFLUENCIA DE STANDS PER HORA<a class="linkGraph"><i class="fas fa-angle-up"></a></i></h4>
			<hr>
		</div>
		<div class="col-md-12 text-center mt-50 graphPerAge">
			<h4>PERCENTATGE D'EDAT PER STAND<a class="linkGraph"><i class="fas fa-angle-up"></a></i></h4>
			<hr>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('extrajs'); ?>
<script>
	var statistics = {};
	var graphs = {};
	var chartDay, chartHour, chartAge;
	var colors = [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(129, 98, 90, 0.2)',
        'rgba(197, 194, 255, 0.2)',
        'rgba(61, 61, 219, 0.2)',
        'rgba(249, 200, 200, 0.2)',
        'rgba(240, 92, 193, 0.2)',
        'rgba(95, 37, 77, 0.2)'
    ];
	$(function(){
		var current_token = "<?php echo e(csrf_token()); ?>";
		$('.statistics').html('<div class="col-md-12 text-center"><br/>Carregant graella...</div>');
		$.post('<?php echo e(route('getGraphPerDay')); ?>', {'_token':current_token}).done(function(response){
			try{
				graphs = JSON.parse(response);
			}catch(e){
				console.log(e);
				return false;
			}
			graphTotal(graphs['totalGraph']);
			graphPerHour(graphs['hourGraph']);
			graphPerAge(graphs['ageGraph']);
		});
		$.post('<?php echo e(route('getStatistics')); ?>', {'_token':current_token}).done(function(response){
			$('.statistics').html('');
			try{
				statistics = JSON.parse(response);
			}catch(e){
				console.log(e);
				return false;
			}
			getGraella(statistics);
		
		});
	});
</script>
<script src="<?php echo e(url('')); ?>/packages/js/chart.js"></script>
<script src="<?php echo e(url('')); ?>/packages/js/statistics.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>