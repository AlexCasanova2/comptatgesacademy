<?php $__env->startSection('extracss'); ?>
<!-- aqui añadimos mas css para esta vista en concreto, si fuera necesario-->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<br /><br />
		</div>
	</div>
	<div class="row statistics">
		
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('extrajs'); ?>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
	var statistics = {};
	$(function(){
		var current_token = "<?php echo e(csrf_token()); ?>";
		$.post('<?php echo e(route('getStatistics')); ?>', {'_token':current_token}).done(function(response){
			try{
				statistics = JSON.parse(response);
			}catch(e){
				console.log(e);
				return false;
			}
			
			$.each(statistics, function(fecha, stands){
				day = fecha.split("/")[0];
				date = fecha.replace(/\//g, "");
				$div = $('<div>', {class: 'col-md-6'});
				$table = $('<table>', {class: 'table text-center'});
				$thead = $('<thead>');
				$tbody = $('<tbody>');
				$tfoot = $('<tfoot>');
				$trfoot = $('<tr>').append($('<td>', {html: 'TOTALES'}));
				$trtipus = $('<tr>').append($('<td>'));
				$thead.append($('<th>', {html: 'DIA '+day}));
				tipus_stands = Array();
				for(i=9; i<=20; i++){
					$tr = $('<tr>').append($('<td>', {html:i}));
					
					$.each(stands, function(stand, horas){
						if (i == 9){
							$trtipus.append($('<td>', {html: 'NEWS',class:'lborder'})).append($('<td>', {html: 'REPEAT'})).append($('<td>', {html: 'PASS'}));
							$thead.append($('<th>', {html: stand.split('_')[1], colspan: "3"}));
							tipus_stands.push(stand.split('_')[0]);
						}
						
					});
					
					$.each(tipus_stands, function(){
						$tr.append($('<td>', {class: date+'-'+i+'-'+this+'-news lborder', html: 0}));
						$tr.append($('<td>', {class: date+'-'+i+'-'+this+'-repeat', html: 0}));
						$tr.append($('<td>', {class: date+'-'+i+'-'+this+'-pass', html: 0}));
						if (i == 9){
							$trfoot.append($('<td>', {class: 'foot-'+date+'-'+this+'-news lborder', html: 0}));
							$trfoot.append($('<td>', {class: 'foot-'+date+'-'+this+'-repeat', html: 0}));
							$trfoot.append($('<td>', {class: 'foot-'+date+'-'+this+'-pass', html: 0}));
						}
					});
					$tbody.append($tr);
				} 
				
				$thead.append($trtipus);
				$tfoot.append($trfoot);
				$table.append($thead).append($tbody).append($tfoot);
				$div.append($table);
				$('.statistics').append($div);
			});
			$.each(statistics, function(fecha, stands){
				date = fecha.replace(/\//g, "");
				$.each(stands, function(stand, horas){
					$.each(horas, function(hora, recount){
						$.each(recount, function(key, value){
							console.log(stand);
							console.log(key);
							console.log(value);
							total = parseInt($('.foot-'+date+'-'+stand.split('_')[0]+'-'+key).html())||0;
							$('.'+date+'-'+hora+'-'+stand.split('_')[0]+'-'+key).html(value);
							$('.foot-'+date+'-'+stand.split('_')[0]+'-'+key).html(total+value);
						});
					});
				});	
			});
		});
	});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>