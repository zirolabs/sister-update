<div class="box box-primary">
    <div class="box-header with-border">
    	<h3 class="box-title">
            <i class="fa fa-map-marker"></i>&nbsp;&nbsp;&nbsp;Lokasi - <?=$data->nama?>
        </h3>
    </div>
    <div class="box-body">
        <a href="<?=site_url('monitoring')?>" class="btn btn-default">
            <i class="fa fa-history"></i> Kembali
        </a>
        <a href="<?=site_url('monitoring/detail/' . $data->user_id)?>" class="btn btn-default">
            <i class="fa fa-refresh"></i> Refresh Lokasi
        </a>
        <hr/>
        <div class="row">
            <div class="col-md-4">
                <table class="table table-striped table-hover table-bordered">
                    <tbody>
                        <tr>
                            <th colspan="2" class="text-center">
                                <img src="<?=default_foto_user($data->foto)?>" style="width: 50%;">
                            </th>
                        </tr>
                        <tr>
                            <td class="col-md-4">NIS</td>
                            <td><?=$data->nis?></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td><?=$data->nama?></td>
                        </tr>
                        <tr>
                            <td>No. Telp</td>
                            <td><?=$data->no_hp?></td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td><?=$data->email?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td><?=$data->alamat?></td>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center">Orang Tua</th>
                        </tr>
                        <tr>
                            <td>Bapak</td>
                            <td><?=$data->nama_ortu_bapak?></td>
                        </tr>
                        <tr>
                            <td>Ibu</td>
                            <td><?=$data->nama_ortu_ibu?></td>
                        </tr>
                        <tr>
                            <td>No. Telp</td>
                            <td><?=$data->no_hp_ortu?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-8">
                <div id="map" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>

<?php if(!empty($data)){ ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?=$this->config->item('google_map')?>&language=id&v=3"></script>
    <script>
        var map, marker, bounds;
        var myLatLng = {lat: <?=$data->lokasi_latitude?>, lng: <?=$data->lokasi_longitude?>};
        var lastInfoWindow;
        var marker;
        var infowindow;

        function initMap()
        {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom            : 11,
                center          : myLatLng,
                mapTypeControl  : true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                    position: google.maps.ControlPosition.TOP_RIGHT
                },
            });

            var bounds = new google.maps.LatLngBounds();
            
            marker = new google.maps.Marker({
                position    : myLatLng,
                map         : map,
                title       : 'Lokasi Terakhir pada <?=format_tanggal_indonesia($data->lokasi_waktu, true)?>',
                // label       : '',
            });

            contentString = `
                <div class="text-center">
                    <p class="text-center">
                        <b><?=$data->nama?><br/>Lokasi Terakhir pada <?=format_tanggal_indonesia($data->lokasi_waktu, true)?></b>
                    </p>
                </div>
            `;
            infowindow = new google.maps.InfoWindow({ content: contentString });

            marker.addListener('click', function() {
                infowindow.open(map, marker);
                map.setZoom(17);
                map.panTo(marker.position);
            });

            bounds.extend(marker.position);
            map.fitBounds(bounds);
        }

        $(document).ready(function() {
    		initMap();
    	});
    </script>
<?php } ?>
