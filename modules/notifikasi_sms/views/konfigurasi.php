<form class="form-horizontal" method="POST" action="<?=site_url('notifikasi_sms/konfigurasi_submit')?>" enctype="multipart/form-data">
	<div class="form-group">
        <label class="col-md-2 control-label">API Key</label>
        <div class="col-md-9">
        	<textarea class="form-control" class="form-control" rows="3" name="sms_gateway_key"><?=$this->config->item('sms_gateway_key')?></textarea>
        </div>
    </div>
    <hr/>
    <div class="form-group">
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-primary">
            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
            </button>
        </div>
    </div>
</form>
