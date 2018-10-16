<?php
require_once 'includes/core.php';

$data_last_location = file_get_contents('http://112.78.11.14/api/?act=get_last_location&imei=385811214445073');
$data_last_location = json_decode($data_last_location, true);
$data_last_online   = file_get_contents('http://112.78.11.14/api/?act=get_infomation&imei=385811214445073');
$data_last_online   = json_decode($data_last_online, true);
$address            = getDetailAddress($data_last_location[(count($data_last_location) - 1)]['detail_lat'].','.$data_last_location[(count($data_last_location) - 1)]['detail_lng'], 'latlng');
$result             = 'Địa chỉ: '.$address['results'][0]['formatted_address'].'<br>';
$result            .= 'Thời giân cập nhập lần cuối: '. date('H:i:s d/m/Y', strtotime($data_last_online[0]['info_last_online']['date'])) .'<br>';
$result            .= 'Tốc độ: '. $data_last_location[0]['detail_speed'] .' km/h<br>';

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
                    <?php echo $result;?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col">
                <div id="map" style="height: 100%;float: left;width: 100%;height: 800px;"></div>
                <script>
                    // This example displays a marker at the center of Australia.
                    // When the user clicks the marker, an info window opens.

                    function initMap() {
                        var uluru = {lat: <?php echo $data_last_location[0]['detail_lat'];?>, lng: <?php echo $data_last_location[0]['detail_lng'];?>};
                        var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 10,
                            center: uluru
                        });

                        var contentString = '<?php echo $result?>';

                        var infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });

                        var marker = new google.maps.Marker({
                            position: uluru,
                            map     : map,
                            icon    : 'images/car.png'
                        });
                        marker.addListener('click', function() {
                            infowindow.open(map, marker);
                        });
                    }
                </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo _KEY_GOOGLE_MAPS?>&callback=initMap"></script>
            </div>
        </div>

<?php
require_once 'footer.php';