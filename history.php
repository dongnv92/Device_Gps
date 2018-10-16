<?php
require_once 'includes/core.php';

if($_POST['submit']){
    $imei       = isset($_REQUEST['imei'])      && !empty($_REQUEST['imei'])        ? trim($_REQUEST['imei'])   : '';
    $time_start = isset($_REQUEST['time_start'])&& !empty($_REQUEST['time_start'])  ? trim($_REQUEST['time_start'])  : '';
    $time_stop  = isset($_REQUEST['time_stop']) && !empty($_REQUEST['time_stop'])   ? trim($_REQUEST['time_stop'])  : '';
    $data       = file_get_contents('http://112.78.11.14/api/?act=get_detail&imei='. $imei .'&time_start='. $time_start .'&time_stop='.$time_stop);
    $data       = json_decode($data, true);
    $waypoints  = array();
    foreach ($data AS $datas){
        $waypoints[] = '{lat: '. $datas['detail_lat'] .', lng: '. $datas['detail_lng'] .'}';
    }
    $waypoints      = implode(',', $waypoints);
    $data_old       = '{lat: '. $data[0]['detail_lat'] .', lng: '. $data[0]['detail_lng'] .'}';
    $data_new       = '{lat: '. $data[(count($data) - 1)]['detail_lat'] .', lng: '. $data[(count($data) - 1)]['detail_lng'] .'}';
    $address_new    = getDetailAddress($data[(count($data) - 1)]['detail_lat'].','.$data[(count($data) - 1)]['detail_lng'], 'latlng');
    $address_old    = getDetailAddress($data[0]['detail_lat'].','.$data[0]['detail_lng'],'latlng');
    $caculator      = getCaculatorRoutor($data[(count($data) - 1)]['detail_lat'].','.$data[(count($data) - 1)]['detail_lng'], $data[0]['detail_lat'].','.$data[0]['detail_lng']);
    $result         = 'Từ <i>'.$address_new['results'][0]['formatted_address'].'</i> Đến <i>'.$address_old['results'][0]['formatted_address'].'</i> dài khoảng '.$caculator['long'];
}


$css_plus = array(
    'app-assets/vendors/css/pickers/daterange/daterangepicker.css',
    'app-assets/vendors/css/pickers/pickadate/pickadate.css',
    'app-assets/css/plugins/pickers/daterange/daterange.min.css'
);

$js_plus = array(
    'app-assets/vendors/js/pickers/pickadate/picker.js',
    'app-assets/vendors/js/pickers/pickadate/picker.date.js',
    'app-assets/vendors/js/pickers/pickadate/picker.time.js',
    'app-assets/vendors/js/pickers/pickadate/legacy.js',
    'app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js',
    'app-assets/vendors/js/pickers/daterange/daterangepicker.js'
);
require_once 'header.php';
?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col">
                                <fieldset class="form-group">
                                    <select class="form-control round" name="imei">
                                        <option value="385811214445073">Citypost</option>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col">
                                <fieldset class="form-group">
                                    <input type="text" name="time_start" value="<?php echo $time_start?>" class="form-control round pickadate" placeholder="Thời Gian Bắt Đầu" />
                                </fieldset>
                            </div>
                            <div class="col">
                                <fieldset class="form-group">
                                    <input type="text" name="time_stop" value="<?php echo $time_stop?>" class="form-control round pickadate" placeholder="Thời Gian Kết Thúc" />
                                </fieldset>
                            </div>
                            <div class="col text-right">
                                <fieldset class="form-group">
                                    <input type="submit" name="submit" value="Tìm Kiếm" class="btn btn-outline-blue round">
                                </fieldset>
                            </div>
                        </div>
                    </form>
                    <?php echo $result;?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col">
                <div id="map" style="height: 100%;float: left;width: 100%;height: 800px;"></div>
                <script>
                    function initMap() {
                        var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 10,
                            center: <?php echo $data_new?>
                        });
                        var infowindow = new google.maps.InfoWindow({
                            content: 'hello'
                        });

                        var flightPlanCoordinates = [<?php echo $waypoints;?>];
                        var flightPath = new google.maps.Polyline({
                            path: flightPlanCoordinates,
                            geodesic: true,
                            strokeColor: '#005792',
                            strokeOpacity: 1.0,
                            strokeWeight: 2
                        });
                        flightPath.setMap(map);

                        var marker_start = new google.maps.Marker({
                            position: <?php echo $data_new?>,
                            map: map,
                            icon: 'images/car.png'
                        });

                        var marker_stop = new google.maps.Marker({
                            position: <?php echo $data_old?>,
                            map: map,
                            icon: 'images/stop.png'
                        });
                    }
                </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo _KEY_GOOGLE_MAPS?>&callback=initMap"></script>
            </div>
        </div>

<?php
require_once 'footer.php';