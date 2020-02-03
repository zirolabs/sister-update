<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp;Detail Transaksi <?=$data_mutasi->nama_siswa ?>
        </h3>
        <p>Pada <?=waktu_berlalu($data_mutasi->waktu)?></p>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('riwayat_produk_kantin/detail/')?>">
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
                                <input type="hidden" name="mutasi_id" value="<?=$data_mutasi->mutasi_id ?>">
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
                        <th class="col-md-2">Nama Produk</th>
                        <th class="col-md-2">Kuantitas</th>
                        <th class="col-md-2">Harga Awal/Satuan</th>
                        <th class="col-md-2">Harga Jual</th>
                        <th class="col-md-2">Total Harga</th>
                        <th class="col-md-2">Keuntungan</th>
					</tr>
				</thead>
				<tbody>
                    <?php 
                        $total_semua = 0;
                        $total_harga_jual = 0;
                        if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
							<tr>
                                <td><?=$c->nama_produk?></td>
                                <td><?=$c->kuantitas?></td>
                                <td><?=format_rupiah($c->harga_awal)?></td> 
                                <td><?=format_rupiah($c->harga_jual)?></td>
                                <td><?=format_rupiah($total_jual = $c->harga_jual*$c->kuantitas)?></td>                   
                                <td><?=format_rupiah($total = $total_jual-($c->harga_awal*$c->kuantitas))?></td>                   
							</tr>
                        <?php 
                            $total_semua = $total_semua + $total; 
                            $total_harga_jual = $total_harga_jual + $total_jual;
                            endforeach; ?>
					<?php } else { ?>
						<tr>
							<td colspan="4">Tidak ada data.</td>
						</tr>
					<?php } ?>
                    <tr>
                        <td colspan="4" class="text-right"><b>Total</b></td>
                        <td><b><?php if(!empty($total_harga_jual)) echo format_rupiah($total_harga_jual) ?></b></td>
                        <td><b><?php if(!empty($total_semua)) echo format_rupiah($total_semua) ?></b></td>
                    </tr>
				</tbody>
                    
			</table>
		</div>
		<?=$pagination?>
    </div>
</div>

