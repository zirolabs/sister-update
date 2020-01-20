<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp;Detail Mutasi
        </h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <a href="<?=site_url('keuangan_mutasi')?>" class="btn btn-default">
                    <i class="fa fa-history"></i> Kembali
                </a>
            </div>
        </div>
        <hr style="margin: 5px 0;" />
        <table class="table table-bordered table-striped">
            <tr>
                <td>NIS</td>
                <td><?=$nis?></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td><?=$nama?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td><?=$kelas?></td>
            </tr>
            <tr>
                <td>Sekolah</td>
                <td><?=$sekolah?></td>
            </tr>
        </table>        
        <hr style="margin: 5px 0;" />
        <div class="table-responsive">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
                        <th class="col-md-2 text-center">Waktu</th>
                        <th class="col-md-2 text-center">Jenis</th>
                        <th class="col-md-2 text-center">Nominal</th>
                        <th class="text-center">Keterangan</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
							<tr>
                                <td class="text-center"><?=format_tanggal_indonesia($c->waktu, true)?></td>
                                <td class="text-center"><?=format_ucwords($c->jenis)?></td>
                                <td class="text-right"><?=format_rupiah($c->nominal)?></td>
								<td><?=$c->keterangan?></td>
							</tr>
						<?php endforeach; ?>
					<?php } else { ?>
						<tr>
							<td colspan="4">Tidak ada data.</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?=$pagination?>
    </div>
</div>