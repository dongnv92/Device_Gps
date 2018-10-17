<?php
/**
 * Created by PhpStorm.
 * User: DONG
 * Date: 04/06/2018
 * Time: 15:34
 */

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

function getStaticDevice($option){
    $return = 0;
    if($option['type'] == 'click_day'){
        $query  = "select * from [tblTransactions] where datediff(DAY, [timeReq], '". $option['time'] ."') = 0";
        $return = checkGlobal(_DB_TABLE_TRANSACTIONS, '', array('query' => $query));
    }else if($option['type'] == 'click_day_plus'){
        $query  = "select * from [tblTransactions] where (datediff(DAY, [timeReq], '". $option['time'] ."') = 0) ".$option['data'];
        $return = checkGlobal(_DB_TABLE_TRANSACTIONS, '', array('query' => $query));
    }else if($option['type'] == 'between'){
        $query  = "SELECT * FROM [tblTransactions] WHERE [timeReq] between '". $option['time_start'] ."' AND '". $option['time_end'] ."'";
        $return = checkGlobal(_DB_TABLE_TRANSACTIONS, '', array('query' => $query));
    }else if($option['type'] == 'between_plus'){
        $query  = "SELECT * FROM [tblTransactions] WHERE ([timeReq] between '". $option['time_start'] ."' AND '". $option['time_end'] ."') ".$option['data'];
        $return = checkGlobal(_DB_TABLE_TRANSACTIONS, '', array('query' => $query));
    }
    return $return;
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

function getDetailAddress($address, $type = 'address'){
    if($type == 'address'){
        $google_search  = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='. urlencode($address) .'&key='._KEY_GOOGLE_MAPS);
    }else if('latlng'){
        $google_search  = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='. urlencode($address) .'&key='._KEY_GOOGLE_MAPS);
    }

    $google_search  = json_decode($google_search, true);
    return $google_search;
}

function getCaculatorRoutor($address_start, $address_end){
    $caculator  = file_get_contents('https://maps.googleapis.com/maps/api/directions/json?origin='. $address_start .'&destination='. $address_end .'&key='._KEY_GOOGLE_MAPS);
    $caculator  = json_decode($caculator, true);
    $response   = array('long' => $caculator['routes'][0]['legs'][0]['distance']['text']);
    return $response;
}

function getApi($action, $param){
    if(!$action || !$param){
        return false;
    }
    foreach($param as $key => $value){
        $para[] =  $key ."=".$value;
    }
    $para_list  = implode('&', $para);
    $data       = json_decode(file_get_contents(_URL_API.'/?act='.$action.'&'.$para_list), true);
    return $data;
}