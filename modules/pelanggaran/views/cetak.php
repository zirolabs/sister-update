<?php
$GLOBALS["data"] = $data;
class Cetak extends FPDF
{
	//Page header

    function Header()
	{
			$data = $GLOBALS['data'];
             //   $this->setFont('Arial','',10);
             //   $this->setFillColor(255,255,255);
             //   $this->cell(100,6,"Laporan daftar pegawai gubugkoding.com",0,0,'L',1); 
             //   $this->cell(100,6,"Printed date : " . date('d/m/Y'),0,1,'R',1); 
               
                
                if ($this->PageNo()>1) {
                     # code...
                 } else {
					foreach ($data as $row) {
						$this->Image(base_url().'assets/img/logo-sekolah/'.$row->logo.'', 10, 10,'15','20','');
						$this->setFillColor(255,255,255);
						$this->cell(5,0,'',0,0,'C',0); 
						$this->setFont('Arial','B',18);
						$this->cell(0,8,$row->nama_sekolah,0,1,'C',0); 
						$this->setFont('Arial','',12);
						$this->cell(5,5,'',0,0,'C',0); 
						$this->cell(0,6,$row->alamat,0,1,'C',0); 
						$this->cell(5,5,'',0,0,'C',0); 
						$this->cell(0,8,'Telp/Fax. '.$row->telepon.' Email : '.$row->email.'',0,1,'C',0); 
						$this->line(10,35,200,35);
						$this->Line(10,35,200,35);
						$this->Line(10,36,200,36); 
					}
                 }
                
                
	}
	function Content($data)
	{
        $hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu");
        $bulan = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
        //echo $hari[date("w")].", ".date("j")." ".$bulan[date("n")]." ".date("Y");
      
                foreach ($data as $key) {
                $this->setFillColor(255,255,255);
                $this->Ln(8);
                $this->setFont('Arial','B',14);
                $this->cell(0,8,'LAPORAN PELANGGARAN SISWA',0,1,'C',0); 
                $this->Ln(8);
                $this->setFont('Arial','B',12);
                $this->cell(0,5,'A. KETERANGAN TENTANG DIRI SISWA',0,0,'L',1);
                $this->Ln(8);
                $this->setFont('Arial','',10);
                $this->cell(70,5,'1. Nama Lengkap',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);

                $this->cell(80,5,$key->nama_siswa,0,0,'L',1);
                $this->Image(base_url().'assets/img/foto-profil/'.$key->foto.'', 165, 53,'30','40');
                $this->Ln(5);
                $this->cell(70,5,'2. NIS',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->cell(80,5,$key->nis,0,0,'L',1);
                $this->Ln(5);
				$this->cell(70,5,'3. Tempat/Tanggal Lahir',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->cell(80,5,$key->tempat_lahir_siswa .', '. date('j', strtotime(date($key->tanggal_lahir_siswa))).' '.$bulan[date('n',strtotime(date($key->tanggal_lahir_siswa)))].' '.date('Y', strtotime(date($key->tanggal_lahir_siswa))),0,0,'L',1);
                $this->Ln(5);
                $this->cell(70,5,'4. Agama',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->cell(80,5,$key->agama_siswa,0,0,'L',1);
                $this->Ln(5);
             
				$this->cell(70,5,'5. Kelas',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->cell(80,5,$key->jenjang.' '.$key->nama_jurusan.' '.$key->nama_kelas,0,0,'L',1);
                $this->Ln(5);
				/*
                $this->cell(70,5,'6. Kompetensi Keahlian',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->cell(80,5,$key->jurusan_siswa,0,0,'L',1);
                $this->Ln(5);
				*/
				$this->setFont('Arial','',10);
                $this->cell(70,5,'7. Alamat ',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->multicell(110,5,$key->alamat_siswa,0,'L','L',1);
                $this->Ln(10);
                
				$this->setFont('Arial','B',12);
                $this->cell(0,5,'B. Keterangan Pelanggaran',0,0,'L',1);
                $this->Ln(8);
                $this->setFont('Arial','',10);
                $this->cell(70,5,'Tanggal Kejadian ',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->cell(80,5,date('j', strtotime(date($key->tanggal_pelanggaran))).' '.$bulan[date('n',strtotime(date($key->tanggal_pelanggaran)))].' '.date('Y', strtotime(date($key->tanggal_pelanggaran))),0,0,'L',1);
                $this->Ln(5);
                $this->cell(70,5,'Kelas',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->cell(80,5,$key->jenjang.' '.$key->nama_jurusan.' '.$key->nama_kelas,0,0,'L',1);
                $this->Ln(5);
                $this->cell(70,5,'Pelanggaran',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->multicell(110,5,$key->deskripsi_pelanggaran,0,'L','L',1);
                $this->cell(70,5,'Poin',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->cell(110,5,$key->point_pelanggaran,0,'L','L','false');
                $this->Ln(5);
                $this->cell(70,5,'Tindak Lanjut',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->multicell(110,5,$key->tindak_lanjut,0,'L','L',1);
                $this->Ln(5);
				$this->cell(70,5,'Guru',0,0,'L',1);
                $this->cell(5,5,':',0,0,'L',1);
                $this->cell(80,5,$key->nama_guru,0,0,'L',1);
			}
                
	}
	function Footer()
	{
		$data = $GLOBALS['data'];
		//atur posisi 1.5 cm dari bawah
		$this->SetY(-15);
		//buat garis horizontal
		$this->Line(10,$this->GetY(),200,$this->GetY());
		//Arial italic 9
		$this->SetFont('Arial','I',9);
        foreach ($data as $row) {
			$this->Cell(0,10,'Bimbingan dan Konseling '.$row->nama.' ' . date('Y'),0,0,'L');
		}
		//nomor halaman
		$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
	}
}
 
$pdf = new Cetak();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Content($data);
$pdf->Output();