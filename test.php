<?php
error_reporting(0);
date_default_timezone_set('Asia/Ho_Chi_Minh');
define('_DB_SERVER', 'WIN-T2JRC8V71J9\SQL2008');
define('_DB_USER', 'sa');
define('_DB_PASS', 'citypost@2018@*#');
define('_DB_NAME', 'qbit');
$connection = sqlsrv_connect( _DB_SERVER, array( "Database"=> _DB_NAME, "UID"=>_DB_USER, "PWD"=>_DB_PASS, "CharacterSet" => "UTF-8"));
if( !$connection ) {
    $response = array('response' => 400, 'message' => 'Connect Fail First');
    echo json_encode($response);
    exit();
}

function insertSqlserver($table, $colum, $data){
    global $connection;
    if (!$table || !$data || !$colum) {
        return false;
    } else {
        $colums = implode(',', $colum);
        $datas  = implode(',', $data);
        $query  = 'INSERT INTO '.$table.'('. $colums .') VALUES('. $datas .')';
        $query  = sqlsrv_query($connection, $query);
        // Debug
        if($query == FALSE){
            die('error: '.FormatErrors(sqlsrv_errors($connection)));
        }else{
            return true;
        }
    }
}

function getGlobalAll($table, $data = '', $option = ''){
    global $connection;
    if($data){
        foreach ($data as $key => $value) {
            $colums[] = '['.$key .'] = '. "N'". $value ."'";
        }
        $colums_list = implode(' AND ', $colums);
    }

    $extra = '';

    if($option['order_by'] && $option['order_by_soft']){
        $extra .= ' ORDER BY '.$option['order_by'].' '. $option['order_by_soft'].' ';
    }

    if($option['limit'] && $option['limit_offset']){
        $extra .= ' LIMIT '.$option['limit'].' OFFSET '.$option['limit_offset'].' ';
    }else if($option['limit'] && !$option['limit_offset']){
        $extra .= ' LIMIT '. $option['limit'] .' ';
    }

    if($option['query']){
        $query = $option['query'];
    }else{
        $query = 'SELECT '. ($option['select'] ? $option['select'] : '*') .' FROM '. $table .' '. (($data) ? 'WHERE '.$colums_list : '') .' '.$extra;
    }

    if($option['onecolum'] && ($option['onecolum'] != 'limit')){
        $q = sqlsrv_query($connection, $query);
        if($q == FALSE){
            die(FormatErrors( sqlsrv_errors()));
        }
        $r = sqlsrv_fetch_array($q,SQLSRV_FETCH_ASSOC);
        return $r[$option['onecolum']];
    }else if($option['onecolum'] == 'limit'){
        $q = sqlsrv_query($connection, $query);
        if($q == FALSE){
            die(FormatErrors( sqlsrv_errors()));
        }
        $r = sqlsrv_fetch_array($q,SQLSRV_FETCH_ASSOC);
        return $r;
    }else{
        $q = sqlsrv_query($connection, $query);
        if($q == FALSE){
            die(FormatErrors( sqlsrv_errors()));
        }
        while($r = sqlsrv_fetch_array($q, SQLSRV_FETCH_ASSOC)){
            $n[] = $r;
        }
        return $n;
    }
}

function updateGlobal($table, $data='', $where = ''){
    global $connection;

    foreach($data as $key => $value){
        $colums[] =  '['.$key ."] = N'". checkInsert($value) ."'";
    }
    $colums_list = implode(',', $colums);

    if($where) {
        foreach ($where as $key_w => $value_w) {
            $colums_w[] =  $key_w . " = '" . checkInsert($value_w) . "'";
        }
        $colums_list_w = ' WHERE '.implode(' AND ', $colums_w);
    }
    if(sqlsrv_query($connection, 'UPDATE '. $table .' SET '.$colums_list.' '.$colums_list_w)){
        return true;
    }else{
        return false;
    }
}

function deleteGlobal($table, $data = ''){
    global $connection;
    foreach ($data as $key => $value) {
        $colums[] = $key . " = " . checkInsert($value);
    }
    $colums_list = implode(' AND ', $colums);
    $q = sqlsrv_query($connection,'DELETE FROM '. $table .' WHERE '.$colums_list);
    if($q){
        return true;
    }else{
        return false;
    }
}

function checkGlobal($table, $data = '', $option = ''){
    global $connection;
    foreach ($data as $key => $value) {
        $colums[] = '['. $key . '] = ' . "'". checkInsert($value) ."'";
    }
    $colums_list = implode(' AND ', $colums);
    if($option['query']){
        $q = sqlsrv_query($connection, $option['query'], array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
    }else{
        $q = sqlsrv_query($connection,'SELECT * FROM '. $table .' WHERE '.$colums_list,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
    }
    $n = sqlsrv_num_rows( $q);
    if($n > 0){
        return $n;
    }else{
        return 0;
    }
}


function FormatErrors( $errors )
{
    /* Display errors. */
    echo "Error information: <br/>";

    foreach ( $errors as $error )
    {
        echo "SQLSTATE: ".$error['SQLSTATE']."<br/>";
        echo "Code: ".$error['code']."<br/>";
        echo "Message: ".$error['message']."<br/>";
    }
}

function checkInsert($text){
    return stripslashes($text);
}

$act        = isset($_REQUEST['act'])       && !empty($_REQUEST['act'])         ? trim($_REQUEST['act'])    : '';
$imei       = isset($_REQUEST['imei'])      && !empty($_REQUEST['imei'])        ? trim($_REQUEST['imei'])   : '';
$lat        = isset($_REQUEST['lat'])       && !empty($_REQUEST['lat'])         ? trim($_REQUEST['lat'])    : '';
$lng        = isset($_REQUEST['lng'])       && !empty($_REQUEST['lng'])         ? trim($_REQUEST['lng'])    : '';
$speed      = isset($_REQUEST['speed'])     && !empty($_REQUEST['speed'])       ? trim($_REQUEST['speed'])  : '';
$time_start = isset($_REQUEST['time_start'])&& !empty($_REQUEST['time_start'])  ? trim($_REQUEST['time_start'].' 00:00:00')  : '';
$time_stop  = isset($_REQUEST['time_stop']) && !empty($_REQUEST['time_stop'])   ? trim($_REQUEST['time_stop'].' 23:59:59')  : '';

switch ($act){
    case 'add':
        if(!$imei || !$lat || !$lng){
            $response = array('response' => 401, 'error' => 'Emty Imei Or Lat Or Lng');
            break;
        }
        $colum  = array('[detail_imei]','[detail_lat]','[detail_lng]','[detail_speed]','[detail_time]','[detail_last]');
        $data   = array("'$imei'", "'$lat'", "'$lng'", "'$speed'", "'". date('Y-m-d H:i:s', time()) ."'", "'". date('Y-m-d H:i:s', time()) ."'");
        if(!insertSqlserver('qbit_detail', $colum, $data)){
            $response = array('response' => 402, 'message' => 'Can Not Insert To Database');
            break;
        }
        $response = array('response' => 200, 'message' => 'Add Data Done!');
        break;
    case 'update':
        if(!$imei){
            $response = array('response' => 401, 'error' => 'Emty Imei');
            break;
        }
        if(!updateGlobal('qbit_info', array('info_last_online' => date('Y-m-d H:i:s', time())), array('info_imei' => $imei))){
            $response = array('response' => 402, 'message' => 'Can Not Update Row');
            break;
        }
        $response = array('response' => 200, 'message' => 'Update Done!');
        break;
    case 'get_detail':
        if(!$time_start || !$time_stop || !$imei){
            $response = array('response' => 401, 'error' => 'Emty Time Or Imei');
            break;
        }
        $query      = "SELECT * FROM [qbit_detail] WHERE ([detail_time] between '". $time_start ."' AND '". $time_stop ."') AND [detail_imei] = '$imei' ORDER BY [detail_id] DESC";
        $response   = getGlobalAll('','',array('query' => $query));
        break;
    case 'get_last_location':
        if(!$imei){
            $response = array('response' => 401, 'error' => 'Emty Imei');
            break;
        }
        $query      = "SELECT TOP 1 * FROM [qbit_detail] WHERE [detail_imei] = '$imei' ORDER BY [detail_id] DESC";
        $response   = getGlobalAll('','',array('query' => $query));
        break;
    case 'get_infomation':
        if(!$imei){
            $response = array('response' => 401, 'error' => 'Emty Imei');
            break;
        }
        $query      = "SELECT TOP 1 * FROM [qbit_info] WHERE [info_imei] = '$imei' ORDER BY [info_id] DESC";
        $response   = getGlobalAll('','',array('query' => $query));
        break;
    default:
        $response = array('response' => 200, 'message' => 'Default page');
        break;
}

echo json_encode($response);