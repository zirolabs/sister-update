<?php
if(!empty($_GET["pesan"]) && !empty($_GET["tlp"]))
{
	//KIRIM KE WHATSAPP
	/*************************************************************************************/
	function hp($nohp) 
	{
		$nohp = str_replace(" ","",$nohp);
		$nohp = str_replace("(","",$nohp);
		$nohp = str_replace(")","",$nohp);
		$nohp = str_replace(".","",$nohp);

		if(!preg_match('/[^+0-9]/',trim($nohp))){
			if(substr(trim($nohp), 0, 3)=='+62'){
				$hp = trim($nohp);
			}
			elseif(substr(trim($nohp), 0, 1)=='0'){
				$hp = '62'.substr(trim($nohp), 1);
			}
		}
		return $hp;
	}
	$tlp   = $_GET["tlp"];
	$nohp  = "$tlp";
	$nohp  = hp($nohp);
	$token = date("YmdHis");
	$pesan = $_GET["pesan"];
	$pesan = implode("+",explode(" ",$pesan));
	$wa    = 'https://www.waboxapp.com/api/send/chat?token=bf81699e79758d8921af0683a6af22d45c68ff8b83c77&uid=6281314734299&to='.$nohp.'&custom_uid=psn-'.$token.'&text='.$pesan.'';
	$str   = file_get_contents($wa);
	$json  = json_decode($str, true); 
	/*************************************************************************************/
}
?>