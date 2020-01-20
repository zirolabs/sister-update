<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script type="text/javascript">
	$(function () {
	 	$(".timepicker").timepicker({
	    	showInputs		: false,
	    	showMeridian	: false,
	    	defaultTime		: 'value'
	    });
	    <?php foreach($info_siswa as $key => $c): ?>
			get_jam($('select[name="status[<?=$c->user_id?>]"]').val(), <?=$c->user_id?>);
	    <?php endforeach; ?>
	});	

	function get_jam(val, target)
	{
		if(val == 'hadir')
		{
			$('#waktu_masuk_area_' + target).removeAttr('style');
		}
		else
		{
			$('#waktu_masuk_area_' + target).attr('style', 'display: none;');			
		}
	}
</script>