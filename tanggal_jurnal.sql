-- pilih database sister lalu eksekusi perintah dibawah
ALTER TABLE `jurnal_guru` ADD `tanggal` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `keterangan`;