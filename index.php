<?php
require_once 'includes/core.php';

if(!$user){
    header('location:'._LOGIN);
    exit();
}
$truck                  = isset($_REQUEST['truck']) && !empty($_REQUEST['truck']) ? $_REQUEST['truck'] : '';
if($truck){
    $data_last_location = file_get_contents('http://112.78.11.14/api/?act=get_last_location&imei='.urlencode($truck).'&type=detail_truck');
    $data_last_location = json_decode($data_last_location, true);
    $data_last_online   = file_get_contents('http://112.78.11.14/api/?act=get_infomation&imei='.urlencode($truck).'&type=info_truck');
    $data_last_online   = json_decode($data_last_online, true);
    $address            = getDetailAddress($data_last_location[(count($data_last_location) - 1)]['detail_lat'].','.$data_last_location[(count($data_last_location) - 1)]['detail_lng'], 'latlng');
    $time_stop          = strtotime($data_last_location[0]['detail_last']['date']) - strtotime($data_last_location[0]['detail_time']['date']);
    $result             = 'Địa chỉ: '.$address['results'][0]['formatted_address'].'<br>';
    $result            .= 'Thời gian Dừng: '. convert_seconds($time_stop) .'<br>';
    $result            .= 'Thời gian cập nhập lần cuối: '. date('H:i:s d/m/Y', strtotime($data_last_online[0]['info_last_online']['date'])) .'<br>';
    $result            .= 'Tốc độ: '. $data_last_online[0]['info_speed'] .' km/h<br>';
}

$css_plus = array(
    'app-assets/vendors/css/pickers/daterange/daterangepicker.css',
    'app-assets/vendors/css/pickers/pickadate/pickadate.css',
    'app-assets/css/plugins/pickers/daterange/daterange.min.css',
    'app-assets/css/chosen.css'
);

$js_plus = array(
    'app-assets/vendors/js/pickers/pickadate/picker.js',
    'app-assets/vendors/js/pickers/pickadate/picker.date.js',
    'app-assets/vendors/js/pickers/pickadate/picker.time.js',
    'app-assets/vendors/js/pickers/pickadate/legacy.js',
    'app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js',
    'app-assets/vendors/js/pickers/daterange/daterangepicker.js',
    'app-assets/js/scripts/ui/jquery-ui/autocomplete.js',
    'app-assets/js/chosen.jquery.js',
    'app-assets/js/prism.js',
    'app-assets/js/init.js',
);
$admin_title = 'Theo dõi định vị hiện tại';
require_once 'header.php';
?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col">
                                <fieldset class="form-group">
                                    <select name="truck" data-placeholder="Chọn Một Xe" class="chosen-select-width form-control">
                                        <option value=""></option>
                                        <?php
                                        foreach (getApi('get_list_device', array('type' => 'active')) AS $option){
                                            echo '<option '. ($truck == $option['info_truck'] ? ' selected="selected" ' : '') .' value="'. $option['info_truck'] .'">'. $option['info_truck'] .'</option>';
                                        }
                                        ?>
                                    </select>
                                </fieldset>
                            </div>
                            <div class="col text-right">
                                <fieldset class="form-group">
                                    <input type="submit" value="Xem Định Vị" class="btn btn-outline-blue round">
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
                        var uluru = {lat: <?php echo $data_last_location[0]['detail_lat'];?>, lng: <?php echo $data_last_location[0]['detail_lng'];?>};
                        var map = new google.maps.Map(document.getElementById('map'), {
                            zoom: 14,
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