<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-university"></i>&nbsp;&nbsp;&nbsp;Pilih Sekolah
        </h3>
    </div>
    <div class="box-body">
    	<table class="table table-striped table-hover table-bordered">
    		<thead>
    			<tr>
    				<th class="col-md-3">Nama Sekolah</th>
    				<th>Kontak</th>
    				<th class="col-md-2"></th>
    			</tr>
    		</thead>
    		<tbody>
				<?php if(!empty($data)){ ?>
					<?php foreach($data as $key => $c): ?>
						<tr>
                            <td>
                            	<b><?=$c->nama?></b><br/>
								NISN : <?=$c->nisn?>                            		
                        	</td>
                            <td>
                                <small>Alamat : <?=$c->alamat?> / No. Telepon : <?=$c->telepon?></small>
                        	</td>
                        	<td class="text-center">
                        		<a href="<?=site_url('kantin/form/' . $c->sekolah_id)?>" class="btn btn-success">
                        			Masuk ke Form Transaksi&nbsp;&nbsp;<i class="fa fa-angle-right"></i>
                        		</a>
                        	</td>
						</tr>
					<?php endforeach; ?>
				<?php } else { ?>
					<tr>
						<td colspan="3">Tidak ada data.</td>
					</tr>
				<?php } ?>
    		</tbody>
    	</table>
    </div>
</div>