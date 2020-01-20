<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-circle-o"></i>&nbsp;&nbsp;&nbsp;Input Nilai Mata Pelajaran
        </h3>
    </div>
    <div class="box-body">
		<div class="row">
			<div class="col-md-12">
				<a href="<?=site_url('mata_pelajaran_nilai/index?' . $url_param)?>" class="btn btn-default">
					<i class="fa fa-history"></i> Kembali
				</a>
			</div>
		</div>
		<hr/>
		<form class="form-horizontal" method="POST" action="<?=site_url('mata_pelajaran_nilai/submit/' . $id . '?' . $url_param)?>" enctype="multipart/form-data" autocomplete="off">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th style="vertical-align: middle;">Sekolah</th>
						<th style="vertical-align: middle;"> : <?=$data_sekolah->nama?></th>
						<th style="vertical-align: middle;">Semester</th>
						<th style="vertical-align: middle;"> : <?=$data_semester->tahun_mulai . ' / ' . $data_semester->tahun_akhir . ' ' . $data_semester->nama?></th>
						<th style="vertical-align: middle;">Mapel</th>
						<th style="vertical-align: middle;"> : <?=$data_mata_pelajaran->nama?></th>
					</tr>
					<tr>
						<th style="vertical-align: middle;">Kelas</th>
						<th style="vertical-align: middle;"> : <?=$data_kelas->jenjang . ' ' . $data_kelas->nama_jurusan . ' ' . $data_kelas->nama?></th>
						<th style="vertical-align: middle;">Jenis Nilai</th>
						<th style="vertical-align: middle;"> : <?=$data_jenis?></th>
						<th style="vertical-align: middle;"><?=format_ucwords($data_jenis) . ' ke '?></th>
						<th style="vertical-align: middle;"><input type="text" class="form-control" name="keterangan" value="<?=@$keterangan?>"></th>
					</tr>
				</thead>
			</table>
			<table class="table table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th class="col-md-3">NIS</th>
						<th>Nama</th>
						<th class="col-md-2 text-center">Nilai</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($data)){ ?>
						<?php foreach($data as $key => $c): ?>
							<tr>
								<td style="vertical-align: middle;"><?=$c->nis?></td>
								<td style="vertical-align: middle;"><?=$c->nama?></td>
								<td><input type="number" name="nilai[<?=$c->user_id?>]" class="form-control text-center" value="<?=@$c->nilai?>"></td>
							</tr>
						<?php endforeach; ?>
					<?php } else { ?>
						<tr>
							<td colspan="3"><?=info_msg('Tidak ada data siswa.')?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
            <hr/>
		    <div class="form-group">
		        <div class="col-md-12 text-right">
		            <button type="submit" class="btn btn-default">
		            	<i class="fa fa-check"></i>&nbsp;&nbsp;Simpan
		            </button>
		            <a href="<?=site_url('mata_pelajaran_nilai/index?' . $url_param)?>" class="btn btn-default">
		            	<i class="fa fa-times"></i>&nbsp;&nbsp;Batal
		            </a>
		        </div>
		    </div>
		</form>
    </div>
</div>