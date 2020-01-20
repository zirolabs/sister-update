<table class="table">
	<thead>
		<tr>
			<th class="col-md-2">Sekolah</th>
			<th class="col-md-1">Kelas</th>
			<th class="col-md-2">Waktu</th>
			<th class="col-md-2">Nama</th>
			<th>Keterangan</th>
			<th class="col-md-1"></th>
		</tr>		
	</thead>
	<tbody>
		<?php foreach($data as $key => $c): ?>
			<tr>
				<td><?=$c->nama_sekolah?></td>
				<td><?=$c->nama_kelas?></td>
				<td><?=format_tanggal_indonesia($c->waktu, true)?></td>
				<td><?=$c->nama?><br/>NIS : <?=$c->nis?></td>
				<td>
					<span class="label label-default">
						<?=format_ucwords($c->status)?>							
						<?php if(!empty($c->jam_sesi)){ ?>
							/ Sesi : <?=$c->jam_sesi?> (<?=$c->nama_sesi?>)
						<?php } ?>
					</span>
					<?php if($c->telat > 0){ ?>
						<span class="label label-danger">Telat : <?=$c->telat?> menit</span>
					<?php } ?>
					<?php if(!empty($c->keterangan)){ ?>
						<br/>Keterangan : <?=$c->keterangan?>
					<?php } ?>
				</td>
				<td>
					<a style="margin: 0 2px;" href="<?=site_url('verifikasi_absensi/sudah_absen/' . $c->absensi_id . '?tanggal=' . $tanggal. '&kelas=' . $kelas . '&sekolah=' . $sekolah)?>" class="btn btn-success btn-xs pull-right">
						<i class="fa fa-edit"></i> Perbaharui
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>