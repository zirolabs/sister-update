<?php if(!empty($jadwal_pelajaran)){ ?>
    <div class="col-md-6">
        <div class="box box-primary box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-center">Jadwal Pelajaran, <?=format_tanggal_indonesia($jadwal_pelajaran['tanggal'])?></h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin table-bordered">
                        <thead>
                            <tr>
                                <th class="col-md-2 text-center">Mulai</th>
                                <th class="col-md-2 text-center">Selesai</th>
                                <th class="text-center">Kelas</th>
                                <th>Mata Pelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($jadwal_pelajaran['data'])){ ?>
                                <?php foreach($jadwal_pelajaran['data'] as $key => $c): ?>
                                    <tr>
                                        <td class="text-center"><?=$c->jam_mulai?></td>
                                        <td class="text-center"><?=$c->jam_akhir?></td>
                                        <td class="text-center"><?=$c->kelas?></td>
                                        <td><?=$c->nama_mata_pelajaran?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4">Tidak ada jadwal.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box-footer clearfix text-center">
                <a href="<?=site_url('home/index?jadwal_tanggal=' . date('Y-m-d', strtotime($jadwal_pelajaran['tanggal'] . ' -1DAY')))?>" class="btn btn-sm btn-default btn-flat pull-left">Sebelumnya</a>
                <a href="<?=site_url('home/index?jadwal_tanggal=' . date('Y-m-d'))?>" class="btn btn-sm btn-primary btn-flat">Hari Ini</a>
                <a href="<?=site_url('home/index?jadwal_tanggal=' . date('Y-m-d', strtotime($jadwal_pelajaran['tanggal'] . ' +1DAY')))?>" class="btn btn-sm btn-default btn-flat pull-right">Selanjutnya</a>
            </div>
        </div>
    </div>
<?php } ?>
