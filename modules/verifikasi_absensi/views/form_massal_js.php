<link rel="stylesheet" href="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?=base_url('vendor/almasaeed2010/adminlte')?>/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script type="text/javascript">
	$(function () {
	 	$(".timepicker").timepicker({
	    	showInputs		: false,
	    	showMeridian	: false,
	    	defaultTime		: 'value'
	    });

	    get_jam($('select[name="status"]').val());
	});	

	function get_jam(val)
	{
		if(val == 'hadir')
		{
			$('#waktu_masuk_area').removeAttr('style');
		}
		else
		{
			$('#waktu_masuk_area').attr('style', 'display: none;');			
		}
	}
</script>