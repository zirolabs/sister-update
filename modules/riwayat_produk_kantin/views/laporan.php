<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>
<div id="editor"></div>
<div class="box box-primary" id="cetak">
    <div class="box-header with-border">
    	<h3 class="box-title">
            </i>&nbsp;&nbsp;&nbsp;Laporan Penjualan
        </h3>
        <div style="float: right">
            <form> 
                <input type="button" class="btn btn-info btn-block" value="Cetak" 
                    onclick="window.print()" /> 
            </form> 
        </div>
    </div>
    <div class="box-body">
        <form method="GET" action="<?=site_url('riwayat_produk_kantin/laporan/')?>">
        	<div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="date" class="form-control pull-right datepicker-input" name="tanggal" value="<?=empty($tanggal) ? date('Y-m-d') : $tanggal?>">
                        </div>
                    </div>                    
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sekolah</label>
                        <?=form_dropdown('sekolah', $opt_sekolah, $sekolah, 'class="form-control"')?>
                    </div>                    
                </div>
        		<div class="col-md-3">
					<div class="form-group">
                        <label>&nbsp;</label>
                        <div class="input-group">
                            <span class="input-group-btn btn-right">
                                <button class="btn btn-default" type="submit">Cari</button>
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
                        <th class="col-md-2">Harga Jual</th>
                        <th class="col-md-2">Total Harga</th>
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
                                <td><?=$c->total?></td>
                                <td><?=format_rupiah($c->harga_jual)?></td>
                                <td><?=format_rupiah($total_jual = $c->harga_jual*$c->total)?></td>                   
							</tr>
                        <?php 
                            $total_harga_jual = $total_harga_jual + $total_jual;
                            endforeach; ?>
					<?php } else { ?>
						<tr>
							<td colspan="4">Tidak ada data.</td>
						</tr>
					<?php } ?>
                    <tr>
                        <td colspan="3" class="text-right"><b>Total</b></td>
                        <td><b><?php if(!empty($total_harga_jual)) echo format_rupiah($total_harga_jual) ?></b></td>
                    </tr>
				</tbody>
                    
			</table>
		</div>
    </div>
</div>
