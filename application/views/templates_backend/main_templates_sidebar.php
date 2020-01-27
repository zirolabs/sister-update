<section class="sidebar">
    <div class="user-panel">
        <div class="pull-left image">
            <img src="<?=default_foto_user($data_agen->foto)?>" class="img-circle" alt="User Image<?=$data_agen->nama?>">
        </div>
        <div class="pull-left info">
            <p><?=$data_agen->nama?></p>
            <a href="<?=site_url('profil')?>">
                <?php echo strtoupper($login_level); ?>
            </a>
        </div>
    </div>

    <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menu Utama</li>
        <li class="<?=@$page_active == 'home' ? 'active open' : "" ?>">
            <a href="<?=site_url()?>">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>
        <?php if(in_array($login_level, array('administrator'))){ ?>
            <li class="<?=@$page_active == 'buku_tamu' ? 'active open' : "" ?>">
                <a href="<?=site_url('buku_tamu')?>">
                    <i class="fa fa-users"></i> <span>Buku Tamu [UC]</span>
                </a>
            </li>
        <?php } ?>
        <li class="<?=@$page_active == 'monitoring' ? 'active open' : "" ?>">
            <a href="<?=site_url('monitoring')?>">
                <i class="fa fa-bullseye"></i> <span>Monitoring</span>
            </a>
        </li>
        <li class="treeview <?=@$page_active == 'pesan' ? 'active' : ''?>">
            <a href="#">
                <i class="fa fa-envelope"></i>
                <span>Pesan</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="<?=@$sub_page_active == 'pesan_kotak' ? 'active' : ''?>"">
                    <a href="<?=site_url('pesan_kotak')?>">
                        <i class="fa fa-circle-o"></i> Kotak Pesan
                    </a>
                </li>
                <li class="<?=@$sub_page_active == 'pesan_broadcast' ? 'active' : ''?>"">
                    <a href="<?=site_url('pesan_broadcast')?>">
                        <i class="fa fa-circle-o"></i> Kirim Broadcast
                    </a>
                </li>
                <li class="<?=@$sub_page_active == 'pesan_langsung' ? 'active' : ''?>"">
                    <a href="<?=site_url('pesan_langsung')?>">
                        <i class="fa fa-circle-o"></i> Kirim Direct Message
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview <?=@$page_active == 'pengumuman' ? 'active' : ''?>">
            <a href="#">
                <i class="fa fa-info-circle"></i>
                <span>Pengumuman</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="<?=@$sub_page_active == 'pengumuman' ? 'active' : ''?>"">
                    <a href="<?=site_url('pengumuman')?>">
                        <i class="fa fa-circle-o"></i> Milik Saya
                    </a>
                </li>
                <li class="<?=@$sub_page_active == 'pengumuman_saya' ? 'active' : ''?>"">
                    <a href="<?=site_url('pengumuman/saya')?>">
                        <i class="fa fa-circle-o"></i> Untuk Saya
                    </a>
                </li>
            </ul>
        </li>
        <?php if(in_array($login_level, array('administrator'))){ ?>
        <li class="treeview <?=@$page_active == 'mata_pelajaran' ? 'active' : ''?>">
            <a href="#">
                <i class="fa fa-book"></i>
                <span>Mata Pelajaran</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="<?=@$sub_page_active == 'mata_pelajaran_materi' ? 'active' : ''?>"">
                    <a href="<?=site_url('mata_pelajaran_materi')?>">
                        <i class="fa fa-circle-o"></i> Materi
                    </a>
                </li>
                <li class="<?=@$sub_page_active == 'mata_pelajaran_nilai' ? 'active' : ''?>"">
                    <a href="<?=site_url('mata_pelajaran_nilai')?>">
                        <i class="fa fa-circle-o"></i> Nilai
                    </a>
                </li>
            </ul>
        </li>
        <?php } ?>
        <?php if(in_array($login_level, array('operator sekolah'))||in_array($login_level, array('guru'))){ ?>
        <li class="treeview <?=@$page_active == 'mata_pelajaran' ? 'active' : ''?>">
            <a href="#">
                <i class="fa fa-book"></i>
                <span>Mata Pelajaran</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="<?=@$sub_page_active == 'mata_pelajaran' ? 'active' : ''?>"">
                    <a href="<?=site_url('mata_pelajaran')?>">
                        <i class="fa fa-circle-o"></i> Materi
                    </a>
                </li>
            </ul>
        </li>
        <?php } ?>
        

        <!-- Menu pelanggaran siswa -->
        <li class="treeview <?=@$page_active == 'pelanggaran' ? 'active' : ''?>">
            <a href="#">
                <i class="fa fa-book"></i>
                <span>Pelanggaran</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="<?=@$sub_page_active == 'pelanggaran' ? 'active' : ''?>"">
                    <a href="<?=site_url('pelanggaran')?>">
                        <i class="fa fa-book"></i> Data Pelanggaran
                    </a>
                </li>
                <li class="<?=@$sub_page_active == 'kategori' ? 'active' : ''?>"">
                    <a href="<?=site_url('kategori')?>">
                        <i class="fa fa-tag"></i> Kategori Pelanggaran
                    </a>
                </li>
				<li class="<?=@$sub_page_active == 'subkategori' ? 'active' : ''?>"">
                    <a href="<?=site_url('subkategori')?>">
                        <i class="fa fa-tag"></i> Sub Kategori Pelanggaran
                    </a>
                </li>
				 <li class="<?=@$sub_page_active == 'pelanggaran/laporan' ? 'active' : ''?>"">
                    <a href="<?=site_url('pelanggaran/laporan')?>">
                        <i class="fa fa-print"></i> Laporan
                    </a>
                </li>
				<li class="<?=@$sub_page_active == 'pelanggaran/laporan_per_siswa' ? 'active' : ''?>"">
                    <a href="<?=site_url('pelanggaran/laporan_per_siswa')?>">
                        <i class="fa fa-print"></i> Laporan Per Siswa
                    </a>
                </li>
            </ul>
        </li>
        <?php if(in_array($login_level, array('administrator', 'kepala sekolah', 'operator sekolah'))){ ?>        
            <li class="treeview <?=@$page_active == 'keuangan' ? 'active' : ''?>">
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>Keuangan</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=@$sub_page_active == 'keuangan_rfid' ? 'active' : ''?>"">
                        <a href="<?=site_url('keuangan_rfid')?>">
                            <i class="fa fa-credit-card"></i> Data Kartu
                        </a>
                    </li>
                    <li class="<?=@$sub_page_active == 'keuangan_deposit' ? 'active' : ''?>"">
                        <a href="<?=site_url('keuangan_deposit')?>">
                            <i class="fa fa-tag"></i> Deposit Saldo
                        </a>
                    </li>
                    <li class="<?=@$sub_page_active == 'keuangan_transaksi' ? 'active' : ''?>"">
                        <a href="<?=site_url('keuangan_transaksi')?>">
                            <i class="fa fa-tag"></i> Transaksi
                        </a>
                    </li>
    				 <li class="<?=@$sub_page_active == 'keuangan_mutasi' ? 'active' : ''?>"">
                        <a href="<?=site_url('keuangan_mutasi')?>">
                            <i class="fa fa-print"></i> Laporan Mutasi
                        </a>
                    </li>
                    <li class="<?=@$sub_page_active == 'keuangan_master' ? 'active' : ''?>"">
                        <a href="<?=site_url('keuangan_master')?>">
                            <i class="fa fa-database"></i> Master Data
                        </a>
                    </li>
                </ul>
            </li>
        <?php } ?>
        <!-- End Of menu pelanggaran siswa -->

        <li class="treeview <?=@$page_active == 'laporan' ? 'active' : ''?>">
            <a href="#">
                <i class="fa fa-check"></i>
                <span>Absensi</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="<?=@$sub_page_active == 'verifikasi_dispensasi' ? 'active' : ''?>"">
                    <a href="<?=site_url('verifikasi_dispensasi')?>">
                        <i class="fa fa-circle-o"></i> Dispensasi
                    </a>
                </li>
                <li class="<?=@$sub_page_active == 'verifikasi_absensi' ? 'active' : ''?>"">
                    <a href="<?=site_url('verifikasi_absensi')?>">
                        <i class="fa fa-circle-o"></i> Verifikasi
                    </a>
                </li>
                <li class="<?=@$sub_page_active == 'absensi_laporan_bulanan' ? 'active' : ''?>"">
                    <a href="<?=site_url('absensi_laporan_bulanan')?>">
                        <i class="fa fa-circle-o"></i> Laporan Bulanan
                    </a>
                </li>
                <li class="<?=@$sub_page_active == 'absensi_laporan_periode' ? 'active' : ''?>"">
                    <a href="<?=site_url('absensi_laporan_periode')?>">
                        <i class="fa fa-circle-o"></i> Laporan Periode
                    </a>
                </li>
                <li class="<?=@$sub_page_active == 'rekap_absensi' ? 'active' : ''?>"">
                    <a href="<?=site_url('rekap_absensi')?>">
                        <i class="fa fa-circle-o"></i> Rekap Absensi
                    </a>
                </li>
            </ul>
        </li>
        <?php if(in_array($login_level, array('administrator'))){ ?>
            <li class="<?=@$page_active == 'manajemen_sekolah' ? 'active open' : "" ?>">
                <a href="<?=site_url('profil_sekolah')?>">
                    <i class="fa fa-university"></i> <span>Manajemen Sekolah</span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array($login_level, array('administrator', 'kepala sekolah', 'operator sekolah'))){ ?>
            <li class="<?=@$page_active == 'manajemen_guru' ? 'active open' : "" ?>">
                <a href="<?=site_url('manajemen_guru')?>">
                    <i class="fa fa-users"></i> <span>Manajemen Guru</span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array($login_level, array('administrator', 'kepala sekolah'))){ ?>
            <li class="<?=@$page_active == 'manajemen_operator' ? 'active open' : "" ?>">
                <a href="<?=site_url('manajemen_operator')?>">
                    <i class="fa fa-users"></i> <span>Manajemen Op. Sekolah</span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array($login_level, array('administrator', 'kepala sekolah', 'guru', 'operator sekolah'))){ ?>
            <li class="<?=@$page_active == 'manajemen_siswa' ? 'active open' : "" ?>">
                <a href="<?=site_url('manajemen_siswa')?>">
                    <i class="fa fa-users"></i> <span>Manajemen Siswa</span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array($login_level, array('guru'))){ ?>
            <li class="<?=@$sub_page_active == 'pengaturan_jadwal_pelajaran' ? 'active' : ''?>"">
                <a href="<?=site_url('pengaturan_jadwal_pelajaran')?>">
                    <i class="fa fa-circle-o"></i> Jadwal Mata Pelajaran
                </a>
            </li>
        <?php } ?>
        <?php if(in_array($login_level, array('administrator','kepala sekolah', 'guru', 'operator sekolah'))){ ?>
            <li class="<?=@$page_active == 'jurnal_guru' ? 'active open' : "" ?>">
                <a href="<?=site_url('jurnal_guru')?>">
                    <i class="fa fa-users"></i> <span>Jurnal Guru</span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array($login_level, array('administrator'))){ ?>
            <li class="<?=@$page_active == 'manajemen_user' ? 'active open' : "" ?>">
                <a href="<?=site_url('manajemen_user')?>">
                    <i class="fa fa-users"></i> <span>Manajemen Admin</span>
                </a>
            </li>
        <?php } ?>
        <?php if(in_array($login_level, array('administrator', 'operator sekolah'))){ ?>
            <li class="treeview <?=@$page_active == 'master' ? 'active' : ''?>">
                <a href="#">
                    <i class="fa fa-database"></i>
                    <span>Master Data</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=@$sub_page_active == 'master_mata_pelajaran' ? 'active' : ''?>"">
                        <a href="<?=site_url('master_mata_pelajaran')?>">
                            <i class="fa fa-circle-o"></i> Mata Pelajaran
                        </a>
                    </li>
                </ul>
            </li>
        <?php } ?>

        <?php if(in_array($login_level, array('administrator', 'kepala sekolah', 'operator sekolah'))){ ?>
            <li class="treeview <?=@$page_active == 'pengaturan' ? 'active' : ''?>">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Pengaturan</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php if(in_array($login_level, array('administrator'))){ ?>
                        <li class="<?=@$sub_page_active == 'pengaturan_semester' ? 'active' : ''?>"">
                            <a href="<?=site_url('pengaturan_semester')?>">
                                <i class="fa fa-circle-o"></i> Semester
                            </a>
                        </li>
                    <?php } ?>
                    <?php if(in_array($login_level, array('administrator'))){ ?>
                        <li class="<?=@$sub_page_active == 'pengaturan_jurusan' ? 'active' : ''?>"">
                            <a href="<?=site_url('pengaturan_jurusan')?>">
                                <i class="fa fa-circle-o"></i> Jurusan
                            </a>
                        </li>
                    <?php } ?>
                    <?php if(in_array($login_level, array('administrator'))){ ?>
                        <li class="<?=@$sub_page_active == 'pengaturan_sesi' ? 'active' : ''?>"">
                            <a href="<?=site_url('pengaturan_sesi')?>">
                                <i class="fa fa-circle-o"></i> Sesi
                            </a>
                        </li>
                    <?php } ?>
                    <?php if(in_array($login_level, array('administrator', 'kepala sekolah', 'operator sekolah'))){ ?>
                        <li class="<?=@$sub_page_active == 'pengaturan_kelas' ? 'active' : ''?>"">
                            <a href="<?=site_url('pengaturan_kelas')?>">
                                <i class="fa fa-circle-o"></i> Kelas
                            </a>
                        </li>
                    <?php } ?>
                    <?php if(in_array($login_level, array('administrator', 'kepala sekolah', 'operator sekolah'))){ ?>
                        <li class="<?=@$sub_page_active == 'pengaturan_jam_sesi' ? 'active' : ''?>"">
                            <a href="<?=site_url('pengaturan_jam_sesi')?>">
                                <i class="fa fa-circle-o"></i> Jam Sesi
                            </a>
                        </li>
                    <?php } ?>
                    <?php if(in_array($login_level, array('administrator', 'operator sekolah'))){ ?>
                        <li class="<?=@$sub_page_active == 'pengaturan_jadwal_pelajaran' ? 'active' : ''?>"">
                            <a href="<?=site_url('pengaturan_jadwal_pelajaran')?>">
                                <i class="fa fa-circle-o"></i> Jadwal Mata Pelajaran
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>

        <?php if(in_array($login_level, array('administrator', 'kepala sekolah', 'operator sekolah'))){ ?>
            <li class="treeview <?=@$page_active == 'notifikasi' ? 'active' : ''?>">
                <a href="#">
                    <i class="fa fa-bell"></i>
                    <span>Notifikasi</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=@$sub_page_active == 'notifikasi_jadwal' ? 'active' : ''?>"">
                        <a href="<?=site_url('notifikasi_jadwal')?>">
                            <i class="fa fa-circle-o"></i> Terjadwal
                        </a>
                    </li>
                    <li class="<?=@$sub_page_active == 'notifikasi_sms' ? 'active' : ''?>"">
                        <a href="<?=site_url('notifikasi_sms')?>">
                            <i class="fa fa-circle-o"></i> SMS
                        </a>
                    </li>
                </ul>
            </li>
        <?php } ?>

        <?php if(in_array($login_level, array('administrator'))||in_array($login_level, array('operator sekolah'))){ ?>
        <li class="treeview <?=@$page_active == 'kantin' ? 'active' : ''?>">
            <a href="#">
                <i class="fa fa-sellsy"></i>
                <span>Produk Kantin</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="<?=@$sub_page_active == 'produk_kantin' ? 'active' : ''?>"">
                    <a href="<?=site_url('produk_kantin')?>">
                        <i class="fa fa-circle-o"></i> Produk Kantin
                    </a>
                </li>
                <li class="<?=@$sub_page_active == 'riwayat_produk_kantin' ? 'active' : ''?>"">
                    <a href="<?=site_url('riwayat_produk_kantin')?>">
                        <i class="fa fa-circle-o"></i> Riwayat Transaksi
                    </a>
                </li>
            </ul>
        </li>
        <?php } ?>


        <li class="header">Menu User</li>
        <li class="<?=@@$page_active == 'profil' ? 'active' : ''?>">
            <a href="<?=site_url('profil')?>">
                <i class="fa fa-user"></i> <span>Profil</span>
            </a>
        </li>
        <li>
            <a href="<?=site_url('logout')?>">
                <i class="fa fa-sign-out"></i> <span>Logout</span>
            </a>
        </li>
    </ul>
</section>
