<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th colspan="2">Hasil Pencarian</th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($data)){ ?>
			<?php foreach($data as $key => $c): ?>
				<tr>
					<td>
						<?=$c->kode_barang?><br/>
                        <small>Nama Produk : <?=$c->nama?>, Harga : <?=format_rupiah($c->harga_jual)?></small><br/>	
                        <small>Kuantitas : <?=$c->kuantitas?></small>
                        <button type="button" class="btn btn-primary btn-block btn-xs <?php if($c->kuantitas<=0) echo 'disabled' ?>" onclick="tambah_beli('<?=$c->nama?>', <?=$c->produk_id?>, '<?=$c->harga_jual?>', '<?=$c->harga_awal?>')">
                            <i class="fa fa-plus"></i> Tambahkan produk
                        </button>
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
