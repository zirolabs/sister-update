<script src='https://pdftron.s3.amazonaws.com/webviewer/5.1.0/lib/webviewer.min.js'> </script>

<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;<?=@$data->judul?>
        </h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <a href="<?=site_url('mata_pelajaran')?>" class="btn btn-default">
                    <i class="fa fa-history"></i> Kembali
                </a>
            </div>
        </div>
        <hr>
        <iframe src="<?=@base_url($data->lokasi_file)?>" width="100%" height="650px">
    </div>
</div>

