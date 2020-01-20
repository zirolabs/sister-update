<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-tag"></i>&nbsp;&nbsp;&nbsp;Sub Kategori Pelanggaran
        </h3>
    </div>
    <div class="box-body">
    	<div class="row">
            <div class="col-md-9">
                <a href="<?=site_url('subkategori/form')?>" class="btn btn-default">
                    <i class="fa fa-plus hidden-xs"></i> Tambah
                </a>
            </div>
    		<div class="col-md-3">
           		<form method="GET" action="<?=site_url('subkategori')?>">
					<div class="form-group" style="margin-bottom: 0px;">
                        <div class="input-group">
                            <div class="input-group-control">
	                            <input type="text" class="form-control input" placeholder="Pencarian.." name="q" value="<?=$keyword?>">
                            </div>
                            <span class="input-group-btn btn-right">
                                <button class="btn btn-default" type="submit">Cari !</button>
                            </span>
                        </div>
                    </div>
           		</form>
            </div>
        </div>
        <hr/>
        <div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1 text-center"></th>
                        <th class="text-center">No</th>
						<th class="">Kategori</th>
                        <th class="">Deskripsi Pelanggaran</th>
						<th class="">Point</th>
                   </tr>
				</thead>
				<tbody>
					<?php 
						if(!empty($data)){ 
					?>
						<?php foreach($data as $key => $c): ?>
							<tr>
								<td class="text-center">
									<a href="<?=site_url('subkategori/form/' . $c->id_subkategori)?>" class="btn btn-default btn-xs" title="Perbaharui / Update">
										<i class="fa fa-edit"></i>
									</a>
									<a onclick="confirm_hapus('<?=$c->id_subkategori?>')" class="btn btn-default btn-xs" title="Hapus">
										<i class="fa fa-trash"></i>
									</a>
								</td>
                                <td class="text-center"><?=$c->id_subkategori?></td>
                                <td><?=$c->nama_pelanggaran?></td>
								<td><?=$c->deskripsi_pelanggaran?></td>
								<td><?=$c->point_pelanggaran?></td>
                    
							</tr>
						<?php 
							endforeach; 
						?>
					<?php } else { ?>
						<tr>
							<td colspan="6">Tidak ada data.</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?=$pagination?>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-hapus">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                	<i class="fa fa-info-circle"></i>&nbsp;&nbsp;Hapus Data
                </h4>
            </div>
            <div class="modal-body">
            	<h4>Apakah Anda yakin ? </h4>
            </div>
            <div class="modal-footer">
            	<a href="<?=site_url('subkategori')?>" id="btn-yes" class="btn btn-default">
            		<i class="fa fa-check"></i> Ya, Saya Yakin
            	</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                	<i class="fa fa-times"></i> Tidak
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	function confirm_hapus(id)
	{
		$('#modal-hapus #btn-yes').attr({'href' : '<?=site_url('subkategori/hapus')?>/' + id});
		$('#modal-hapus').modal();
	}
</script>
