<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Riwayat Transaksi Kantin
        </h3>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('riwayat_produk_kantin')?>">
        	<div class="row">
                <div class="col-md-3 col-md-offset-4">
                    <div class="form-group">
                        <label>Sekolah</label>
                        <?=form_dropdown('sekolah', $opt_sekolah, $sekolah, 'class="form-control"')?>
                    </div>                    
                </div>
        		<div class="col-md-3">
					<div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group">
                            <div class="input-group-control">
	                            <input type="text" class="form-control input" placeholder="Pencarian.." name="q" value="<?=$keyword?>">
                            </div>
                            <span class="input-group-btn btn-right">
                                <button class="btn btn-default" type="submit">Cari !</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <hr/>
        <div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
                        <th class="col-md-2">Sekolah</th>
                        <th class="col-md-3">Nama Siswa</th>
                        <th class="col-md-2">Total Transaksi</th>
                        <th class="col-md-2">Waktu Transaksi</th>
                        <th class="col-md-1 text-center">Opsi</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
							<tr>
                                <td><?=$c->sekolah?></td>
                                <td><?=$c->nama_siswa?></td>
                                <td><?=format_rupiah($c->nominal)?></td>
                                <td><?=waktu_berlalu($c->waktu)?></td>
                                <td class="text-center">
                                    <form method="GET" action="<?=site_url('riwayat_produk_kantin/detail')?>">
                                        <input type="hidden" name="mutasi_id" value="<?=$c->mutasi_id ?>">
                                        <button class="btn btn-default btn-xs" title="Detail Pembelian"><i class="fa fa-info"></i></button>
                                    </form>
                                </td>                      
							</tr>
						<?php endforeach; ?>
					<?php } else { ?>
						<tr>
							<td colspan="5">Tidak ada data.</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?=$pagination?>
    </div>
</div>

