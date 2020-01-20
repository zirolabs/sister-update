<?php

function get_extention($mime)
{
	$mimes 	= get_mimes();
	$result = '';
	foreach($mimes as $key => $c)
	{
		if(is_array($c))
		{
			if(in_array($mime, $c))
			{
				return $key;
			}
		}
		else
		{
			if($c == $mime)
			{
				return $key;
			}
		}
	}
	return $result;
}

function err_msg($msg, $dismiss = true)
{
	$result = 	'<div class="alert alert-danger" role="alert">';
	if($dismiss == true)
	{
		$result .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
	}
	$result .= $msg . '</div>';
    return $result;
}

function war_msg($msg, $dismiss = true)
{
	$result = 	'<div class="alert alert-warning" role="alert">';
	if($dismiss == true)
	{
		$result .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
	}
	$result .= $msg . '</div>';
    return $result;
}

function suc_msg($msg, $dismiss = true)
{
	$result = 	'<div class="alert alert-success" role="alert">';
	if($dismiss == true)
	{
		$result .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
	}
	$result .= $msg . '</div>';
    return $result;
}

function info_msg($msg, $dismiss = true)
{
	$result = 	'<div class="alert alert-info" role="alert">';
	if($dismiss == true)
	{
		$result .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>';
	}
	$result .= $msg . '</div>';
    return $result;
}

function scrape_get_between($data, $start, $end)
{
    $data = stristr($data, $start); // Stripping all data from before $start
    $data = substr($data, strlen($start));  // Stripping $start
    $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
    $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
    return $data;   // Returning the scraped data from the function
}

function paging($controller = '', $total_rows = '', $limit = '', $uri = '')
{
	$CI =& get_instance();
	$CI->load->library('pagination');

	$config['base_url'] 	= site_url($controller);
	$config['total_rows'] 	= $total_rows;
	$config['per_page'] 	= $limit;

	$config['first_url'] 	= site_url($controller);

	// TAMBAHAN PENTING
	$suffix 			 = http_build_query($_GET, '', "&");
	$config['suffix'] 	 = '?'.$suffix;
	$config['first_url'] = site_url($controller . '?' . $suffix);
	// TAMBAHAN PENTING

	$config['cur_tag_open'] 	= '<span class="paging">';
	$config['cur_tag_close'] 	= '</span>';
	// $this->load->library('pagination');

	$config['full_tag_open'] 	= '<ul class="pagination pagination-sm">';
	$config['full_tag_close'] 	= '</ul>';
	$config['first_link'] 		= 'Awal';
	$config['first_tag_open'] 	= '<li>';
	$config['first_tag_close'] 	= '</li>';
	$config['last_link'] 		= 'Akhir';
	$config['last_tag_open'] 	= '<li>';
	$config['last_tag_close'] 	= '</li>';
	$config['next_link'] 		= '&gt;';
	$config['next_tag_open'] 	= '<li>';
	$config['next_tag_close'] 	= '</li>';
	$config['prev_link'] 		= '&lt;';
	$config['prev_tag_open'] 	= '<li>';
	$config['prev_tag_close'] 	= '</li>';
	$config['cur_tag_open'] 	= '<li class="page-active active"><a href="">';
	$config['cur_tag_close'] 	= '</a></li>';
	$config['num_tag_open'] 	= '<li>';
	$config['num_tag_close'] 	= '</li>';

	$CI->pagination->initialize($config);

	return $CI->pagination->create_links();
}

function hari_indonesia($index = '')
{
	$list = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu');
	if($index == '')
	{
		return $list;
	}
	return $list[$index];
}

function bulan_indonesia($index = '')
{
	$list = array(
		'01' => 'Januari',
		'02' => 'Februari',
		'03' => 'Maret',
		'04' => 'April',
		'05' => 'Mei',
		'06' => 'Juni',
		'07' => 'Juli',
		'08' => 'Agustus',
		'09' => 'September',
		'10' => 'Oktober',
		'11' => 'November',
		'12' => 'Desember'
	);
	if(!empty($index))
	{
		return $list[$index];
	}
	else
	{
		return $list;
	}
}

function opt_tahun($start = 1990)
{
	$result = array();
	for($start; $start <= date('Y'); $start++)
	{
		$result[$start] = $start;
	}
	return $result;
}

function cek_akses_module_multi_array($arr_1 = array(), $arr_2 = array())
{
	foreach($arr_1 as $key => $c)
	{
		if(in_array($c, $arr_2))
		{
			return true;
		}
	}

	return false;
}

function default_foto_user($foto)
{
	return !empty($foto) ? base_url('uploads/profil/' . $foto) :
						   base_url('vendor/almasaeed2010/adminlte/dist/img/avatar5.png');
}

function load_gambar($path = '')
{
	if(empty($path) || !file_exists($path))
	{
		return base_url('assets/img/not-found-png.png');
	}
	else
	{
		return base_url($path);
	}
}

function format_rupiah($data = '')
{
	if(empty($data) OR $data <= 0)
	{
		return 'Rp. 0,-';
	}
	else
	{
		return 'Rp. ' . number_format($data, 0, ',', '.') . ',-';
	}
}

function format_uri( $string, $separator = '-' )
{
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array( '&' => 'and', "'" => '');
    $string = mb_strtolower( trim( $string ), 'UTF-8' );
    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
}

function format_tanggal($tgl, $his = false)
{
	if(empty($tgl))
	{
		return '-';
	}
	if($his == false)
	{
		return date('d-m-Y', strtotime($tgl));
	}
	else
	{
		return date('d-m-Y H:i:s', strtotime($tgl));
	}
}

function umur($tgllahir)
{
	$from = new DateTime(date('Y-m-d', strtotime($tgllahir)));
	$to   = new DateTime('today');
	return $from->diff($to)->y;
}

function format_ucwords($string)
{
	return ucwords(strtolower($string));
}

function format_tanggal_indonesia($date, $his = false)
{
	if($date == '0000-00-00 00:00:00' || $date == '0000-00-00' || empty($date) || $date == null)
	{
		return '-';
	}
	$BulanIndo = array("Januari",
					   "Februari",
					   "Maret",
					   "April",
					   "Mei",
					   "Juni",
					   "Juli",
					   "Agustus",
					   "September",
					   "Oktober",
					   "November",
					   "Desember");

	$tahun = substr($date, 0, 4);
	$bulan = substr($date, 5, 2);
	$tgl   = substr($date, 8, 2);


	$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
	if($his == true)
	{
		$result .= ', ' . substr($date, 11);
	}
	return($result);
}

function pre($string)
{
	echo '<pre>';
	print_r($string);
	echo '</pre>';
	exit;
}


function create_image($name = '', $source = '', $dir_foto = './uploads/')
{
	if(!is_dir($dir_foto))
	{
		mkdir($dir_foto);
	}

	$imageName 	= $dir_foto . format_uri($name) . '.jpg';
	$imageData 	= base64_decode($source);
	$source 	= imagecreatefromstring($imageData);
	if(imagejpeg($source, $imageName, 100))
	{
		imagedestroy($source);
		return $imageName;
	}
	else
	{
		imagedestroy($source);
		return '';
	}
}


function waktu_berlalu($datetime = '', $full = false)
{
	if($datetime == '')
	{
		return '';
	}

    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    foreach ($string as $k => &$v)
    {
        if ($diff->$k)
        {
            if(in_array($v, array('jam', 'menit', 'detik')))
            {
                $v = $diff->$k . ' ' . $v;
            }
            else
            {
                if($v == 'hari' && $diff->$k == 1)
                {
                    $v = $diff->$k . ' ' . $v;
                }
                else
                {
                    return format_tanggal_indonesia($datetime, true);
                }
            }
        }
        else
        {
            unset($string[$k]);
        }
    }

    if (!$full)
    {
        $string = array_slice($string, 0, 1);
    }
    return $string ? implode(', ', $string) . ' yang lalu' : 'baru saja';
}
