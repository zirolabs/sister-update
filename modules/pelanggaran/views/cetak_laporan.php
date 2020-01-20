<?php
//$GLOBALS["data"] = $data;
class Cetak extends FPDF
{
	//Page header

    function Header()
	{
			/*
			$data = $GLOBALS['data'];
                
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
                
                */
	}
	function Content($data,$tanggal_awal,$tanggal_akhir)
	{
		$total = 0;	
        $hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu");
        $bulan = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
        //echo $hari[date("w")].", ".date("j")." ".$bulan[date("n")]." ".date("Y");
      
                foreach($data as $row){
						$nama = $row->nama;
						$nis = $row->nis;
						$nama = $row->nama;
						$kelas = $row->kelas;
						$alamat = $row->alamat;
						$agama_siswa = $row->agama_siswa;
						$foto = $row->foto;
						$tempat_lahir_siswa = $row->tempat_lahir_siswa;
						$tanggal_lahir_siswa = $row->tanggal_lahir_siswa;
				}
                $this->setFillColor(255,255,255);
                $this->Ln(8);
                $this->setFont('Arial','B',14);
                $this->cell(0,8,'LAPORAN PELANGGARAN SISWA',0,1,'C',0); 
                $this->Ln(1);
				 $this->setFont('Arial','B',10);
				$this->cell(0,8,format_tanggal_indonesia($tanggal_awal).' - '.format_tanggal_indonesia($tanggal_akhir),0,1,'C',0); 
                $this->Ln(8);
				
				$this->SetFont('Arial','B',10);	
$this->SetFillColor(128,128,128);		
$this->SetTextColor(255,255,255);
$this->Cell(15,5,'No.',1,0,'C',TRUE);
$this->Cell(45,5,'Nama Siswa',1,0,'C',TRUE);
$this->Cell(25,5,'Kelas',1,0,'C',TRUE);
$this->Cell(30,5,'Tanggal',1,0,'C',TRUE);
$this->Cell(55,5,'Pelanggaran',1,0,'C',TRUE);
$this->Cell(20,5,'Point',1,0,'C',TRUE);

$this->SetFont('Arial','',8);
$this->SetFillColor(255,255,255);		
$this->SetTextColor(0,0,0);
$no=1;
foreach($data as $key) {
	$this->Cell(10,5,'',0,1);
	$this->Cell(15,5,$no++.'.',1,0,'C',TRUE);
	$this->Cell(45,5,$key->nama,1,0,'C',TRUE);
	$this->Cell(25,5,$key->kelas,1,0,'C',TRUE);
	$this->Cell(30,5,$key->tanggal_pelanggaran,1,0,'C',TRUE);
	$this->Cell(55,5,$key->deskripsi_pelanggaran,1,0,'C',TRUE);
	$this->Cell(20,5,$key->point_pelanggaran,1,0,'C',TRUE);
}
				
               
	}
	function Footer()
	{
		/*
		$data = $GLOBALS['data'];
		$this->SetY(-15);
		$this->Line(10,$this->GetY(),200,$this->GetY());
		$this->SetFont('Arial','I',9);
        foreach ($data as $row) {
			$this->Cell(0,10,'Bimbingan dan Konseling '.$row->nama.' ' . date('Y'),0,0,'L');
		}
		$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
		*/
	}
}
 
$pdf = new Cetak();
$pdf->SetTitle('Laporan');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Content($data,$tanggal_awal,$tanggal_akhir);
$pdf->Output();