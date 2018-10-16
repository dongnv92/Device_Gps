<?php
require_once 'includes/core.php';

if($_POST['submit']){
    $imei       = isset($_REQUEST['imei'])      && !empty($_REQUEST['imei'])        ? trim($_REQUEST['imei'])   : '';
    $time_start = isset($_REQUEST['time_start'])&& !empty($_REQUEST['time_start'])  ? trim($_REQUEST['time_start'])  : '';
    $time_stop  = isset($_REQUEST['time_stop']) && !empty($_REQUEST['time_stop'])   ? trim($_REQUEST['time_stop'])  : '';
    $data       = file_get_contents('http://112.78.11.14/api/?act=get_detail&imei='. $imei .'&time_start='. $time_start .'&time_stop='.$time_stop);
    $data       = json_decode($data, true);
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit();
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
$data = array(
    array('lat' => '21.015726666666666666666666667','lng' => '105.77689777777777777777777778'),
    array('lat' => '21.016435555555555555555555556','lng' => '105.77407111111111111111111111'),
    array('lat' => '21.015208888888888888888888889','lng' => '105.77355555555555555555555556'),
    array('lat' => '21.015015555555555555555555556','lng' => '105.7736'),
    array('lat' => '21.014448888888888888888888889','lng' => '105.77428444444444444444444444'),
    array('lat' => '21.014702222222222222222222222','lng' => '105.77438222222222222222222222'),
    array('lat' => '21.017764444444444444444444444','lng' => '105.77859555555555555555555556'),
    array('lat' => '21.018293333333333333333333333','lng' => '105.77953777777777777777777778'),
    array('lat' => '21.017724444444444444444444444','lng' => '105.78064888888888888888888889')
);
$waypoints = array();
foreach ($data AS $datas){
    $waypoints[] = '{lat: '. $datas['lat'] .', lng: '. $datas['lng'] .'}';
}
$waypoints = implode(',', $waypoints);
$data_old = '{lat: '. $data[0]['lat'] .', lng: '. $data[0]['lng'] .'}';
$data_new = '{lat: '. $data[(count($data) - 1)]['lat'] .', lng: '. $data[(count($data) - 1)]['lng'] .'}';


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
                            zoom: 14,
                            center: <?php echo $data_new?>
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
                        var beachMarker = new google.maps.Marker({
                            position: <?php echo $data_new?>,
                            map: map,
                            icon: 'images/car.png'
                        });
                        var beachMarker = new google.maps.Marker({
                            position: <?php echo $data_old?>,
                            map: map,
                            icon: 'images/stop.png'
                        });
                    }
                </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA972MxvD7WSQxQyfrY_zmgsVf_KtDpq4M&callback=initMap"></script>
            </div>
        </div>

<?php
require_once 'footer.php';