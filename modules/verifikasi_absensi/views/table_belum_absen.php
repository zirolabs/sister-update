<form method="POST" action="<?=site_url('verifikasi_absensi/belum_absen_massal?tanggal=' . $tanggal . '&kelas=' . $kelas . '&sekolah=' . $sekolah)?>">
	<table class="table">
		<thead>
			<tr>
				<th><input type="checkbox" id="checkAll"></th>
				<th class="col-md-2">Sekolah</th>
				<th class="col-md-1">Kelas</th>
				<th class="col-md-2">NIS</th>
				<th class="col-md-3">Nama</th>
				<th class="col-md-3">Keterangan</th>
				<th class="col-md-1"></th>
			</tr>		
		</thead>
		<tbody>
			<?php foreach($data as $key => $c): ?>
				<tr>
					<td class="text-center">
						<input type="checkbox" name="user_id[]" value="<?=$c->user_id?>">
					</td>
					<td><?=$c->nama_sekolah?></td>
					<td><?=$c->nama_kelas?></td>
					<td><?=$c->nis?></td>
					<td><?=$c->nama?></td>
					<td>
						<?php if($c->telat_waktu > 0){ ?>
							Waktu : <?=format_tanggal_indonesia($c->telat_waktu, true)?><br/>
							<span class="label label-danger">Telat : <?=$c->telat_total?> menit</span>
						<?php } ?>
					</td>
					<td class="text-right">
						<a href="<?=site_url('verifikasi_absensi/belum_absen/' . $c->user_id . '?tanggal=' . $tanggal . '&kelas=' . $kelas . '&sekolah=' . $sekolah)?>" class="btn btn-success btn-xs">
							<i class="fa fa-check"></i> Verifikasi
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="7">
					<button type="submit" class="btn btn-success btn-xs">Verifikasi Siswa Terpilih</button>
				</th>
			</tr>
		</tfoot>
	</table>
</form>

<script type="text/javascript">
	$("#checkAll").click(function(){
	    $('input[name="user_id[]"').prop('checked', this.checked);
	});	
</script>