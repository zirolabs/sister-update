<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th colspan="2">Hasil Pencarian</th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($user)){ ?>
			<?php foreach($user as $key => $c): ?>
				<tr>
					<td class="col-md-2 text-center">
						<img src="<?=default_foto_user($c->foto)?>" class="img-responsive img-circle">
					</td>
					<td>
						<?=$c->nama?><br/>

						<?php if($jenis_penerima == 'siswa'){ ?>
							<small>NIS : <?=$c->nis?>, Kelas : <?=$c->kelas?></small><br/>
							<small><?=$c->email?> / <?=$c->no_hp?></small>							
							<button type="button" class="btn btn-primary btn-block btn-xs" onclick="tambah_penerima('<?='(' . format_ucwords($jenis_penerima) . ' / ' . $c->nis . ' - ' . $c->kelas . ') ' . $c->nama?>', <?=$c->user_id?>, '<?=format_uri($jenis_penerima)?>', '<?=$c->fcm?>')">
								<i class="fa fa-plus"></i> Tambahkan sbg Penerima
							</button>
						<?php } elseif($jenis_penerima == 'wali siswa'){ ?>
							<small>NIS : <?=$c->nis?>, Kelas : <?=$c->kelas?></small><br/>
							<small>Bpk. <?=$c->nama_ortu_bapak?> (<?=$c->no_hp_ortu?>)</small>
							<button type="button" class="btn btn-primary btn-block btn-xs" onclick="tambah_penerima('<?='(' . format_ucwords($jenis_penerima)  . ' / ' .  $c->nama . ' - ' . $c->nis . ' - ' . $c->kelas . ') ' . $c->nama_ortu_bapak?>', <?=$c->user_id?>, '<?=format_uri($jenis_penerima)?>', '<?=$c->fcm_ortu?>')">
								<i class="fa fa-plus"></i> Tambahkan sbg Penerima
							</button>
						<?php } elseif($jenis_penerima == 'wali kelas'){ ?>
							<small>Wali Kelas : <?=$c->kelas?></small><br/>
							<small><?=$c->email?> / <?=$c->no_hp?></small>
							<button type="button" class="btn btn-primary btn-block btn-xs" onclick="tambah_penerima('<?='(' . format_ucwords($jenis_penerima) . ' - ' . $c->kelas . ') ' . $c->nama?>', <?=$c->user_id?>, '<?=format_uri($jenis_penerima)?>', '<?=$c->fcm?>')">
								<i class="fa fa-plus"></i> Tambahkan sbg Penerima
							</button>
						<?php } elseif($jenis_penerima == 'operator sekolah') { ?>
							<small><?=$c->email?> / <?=$c->no_hp?></small>
							<button type="button" class="btn btn-primary btn-block btn-xs" onclick="tambah_penerima('<?='(' . format_ucwords($jenis_penerima) . ' - ' . $c->sekolah . ') ' . $c->nama?>', <?=$c->user_id?>, '<?=format_uri($jenis_penerima)?>', '<?=$c->fcm?>')">
								<i class="fa fa-plus"></i> Tambahkan sbg Penerima
							</button>
						<?php } elseif($jenis_penerima == 'kepala sekolah') { ?>
							<small><?=$c->email?> / <?=$c->no_hp?></small>
							<button type="button" class="btn btn-primary btn-block btn-xs" onclick="tambah_penerima('<?='(' . format_ucwords($jenis_penerima) . ' - ' . $c->sekolah . ') ' . $c->nama?>', <?=$c->user_id?>, '<?=format_uri($jenis_penerima)?>', '<?=$c->fcm?>')">
								<i class="fa fa-plus"></i> Tambahkan sbg Penerima
							</button>
						<?php } else { ?>
							<small><?=$c->email?> / <?=$c->no_hp?></small>
							<button type="button" class="btn btn-primary btn-block btn-xs" onclick="tambah_penerima('<?='(' . format_ucwords($jenis_penerima) . ') ' . $c->nama?>', <?=$c->user_id?>, '<?=format_uri($jenis_penerima)?>', '<?=$c->fcm?>')">
								<i class="fa fa-plus"></i> Tambahkan sbg Penerima
							</button>
						<?php } ?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php } else { ?>
			<tr>
				<td colspan="3"><?=err_msg('Data tidak ditemukan')?></td>
			</tr>
		<?php } ?>
	</small>
</table>
