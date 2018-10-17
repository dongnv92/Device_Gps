<?php
require_once 'includes/core.php';

if(!$user){
    header('location:'._LOGIN);
    exit();
}

switch ($act){
    default:
        switch ($type){
            case 'edit':
                $device = json_decode(file_get_contents(_URL_API.'/?act=get_infomation&imei='. $id .'&type=info_id'), true);
                if($submit){
                    $device_imei    = isset($_REQUEST['device_imei'])   && !empty($_REQUEST['device_imei'])     ? trim($_REQUEST['device_imei'])  : '';
                    $device_truck   = isset($_REQUEST['device_truck'])  && !empty($_REQUEST['device_truck'])    ? trim($_REQUEST['device_truck']) : '';
                    $device_number  = isset($_REQUEST['device_number']) && !empty($_REQUEST['device_number'])   ? trim($_REQUEST['device_number']): '';
                    $device_status  = isset($_REQUEST['device_status']) && !empty($_REQUEST['device_status'])   ? trim($_REQUEST['device_status']): 0;
                    $uri_update     = _URL_API.'/?act=update_info&imei='.urlencode($device_imei).'&truck='.urlencode($device_truck).'&number='.urlencode($device_number).'&status='.$device_status.'&id='.$id;
                    echo "$uri_update";
                    exit();
                }
                break;
            default:
                if($submit){
                    $device_imei    = isset($_REQUEST['device_imei'])   && !empty($_REQUEST['device_imei'])     ? $_REQUEST['device_imei']  : '';
                    $device_truck   = isset($_REQUEST['device_truck'])  && !empty($_REQUEST['device_truck'])    ? $_REQUEST['device_truck'] : '';
                    $device_number  = isset($_REQUEST['device_number']) && !empty($_REQUEST['device_number'])   ? $_REQUEST['device_number']: '';
                    if($device_imei && $device_number){
                        $add = json_decode(file_get_contents(_URL_API.'/?act=add_device&truck='.$device_truck.'&number='.$device_number.'&imei='.$device_imei), true);
                    }
                }
                break;
        }
        $css_plus = array(
            'app-assets/vendors/css/forms/toggle/bootstrap-switch.min.css',
            'app-assets/vendors/css/forms/toggle/switchery.min.css',
            'app-assets/css/plugins/forms/switch.min.css',
            'app-assets/css/core/colors/palette-switch.min.css'
        );
        $js_plus = array(
            'app-assets/vendors/js/forms/toggle/bootstrap-switch.min.js',
            'app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js',
            'app-assets/vendors/js/forms/toggle/switchery.min.js',
            'app-assets/js/scripts/forms/switch.min.js'
        );
        $admin_title = 'Danh sách thiết bị';
        require_once 'header.php';
        ?>
        <div class="row">
            <!-- Add Or Edit -->
            <div class="col-md-3">
            <?php
            switch ($type){
                case 'edit':
                    ?>
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Sửa thông tin</h4> </div>
                        <div class="card-body">
                            <form action="" class="form form-horizontal" method="post">
                                <div class="form-group label-floating">
                                    <input type="text" required value="<?php echo $device[0]['info_imei']?>" name="device_imei" placeholder="Nhập mã Imei" class="form-control round border-primary">
                                </div>
                                <div class="form-group label-floating">
                                    <input type="text" value="<?php echo $device[0]['info_truck']?>" name="device_truck" placeholder="Nhập biển xe" class="form-control round border-primary">
                                </div>
                                <div class="form-group label-floating">
                                    <input type="text" required value="<?php echo $device[0]['info_number']?>" name="device_number" placeholder="Nhập số hiệu" class="form-control round border-primary">
                                </div>
                                <div class="form-group mt-1">
                                    <input type="checkbox" name="device_status" value="1" id="switcheryColor4" class="switchery" data-color="success" <?php echo $device[0]['info_status'] == 1 ? 'checked' : '';?>/>
                                    <label for="switcheryColor4" class="card-title ml-1">Hoạt Động</label>
                                </div>
                                <div class="text-right">
                                    <input type="submit" name="submit" value="Cập Nhập Thiết Bị" class="btn btn-outline-blue round">
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                    break;
                default:
                    ?>
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Thêm Thiết bị</h4> </div>
                        <div class="card-body">
                            <form action="" class="form form-horizontal" method="post">
                                <div class="form-group label-floating"><input type="text" required value="" name="device_imei" placeholder="Nhập mã Imei" class="form-control round border-primary"></div>
                                <div class="form-group label-floating"><input type="text" value="" name="device_truck" placeholder="Nhập biển xe" class="form-control round border-primary"></div>
                                <div class="form-group label-floating"><input type="text" required value="" name="device_number" placeholder="Nhập số hiệu" class="form-control round border-primary"></div>
                                <div class="text-right"><input type="submit" name="submit" value="Thêm thiết bị" class="btn btn-outline-blue round"></div>
                            </form>
                        </div>
                    </div>
                    <?php
                    break;
            }
            ?>
            </div>
            <!-- Add Or Edit -->
            <!-- List Device -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Danh sách thiết bị</h4> </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>Imei</td>
                                        <td>Ngày Tạo</td>
                                        <td>Thời Gian Update</td>
                                        <td>Biển Xe</td>
                                        <td>Số Hiệu</td>
                                        <td>Trạng Thái</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach (getApi('get_list_device','') AS $_device){
                                    if($_device['info_imei']){
                                        echo '<tr>
                                        <td><a href="device.php?type=edit&id='. $_device['info_id'] .'">'. $_device['info_imei'] .'</a></td>
                                        <td>'. date('H:i:s d/m/Y', $_device['info_timecreate']) .'</td>
                                        <td>'. date('H:i:s d/m/Y', strtotime($_device['info_last_online']['date'])) .'</td>
                                        <td>'. $_device['info_truck'] .'</td>
                                        <td>'. $_device['info_number'] .'</td>
                                        <td>'. ($_device['info_status'] == 1 ? 'Đang Bật' : 'Đang tắt') .'</td>
                                        </tr>';
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- List Device -->
        </div>
        <?php
        break;
}
require_once 'footer.php';