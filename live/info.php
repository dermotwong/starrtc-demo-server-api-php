<?php
//
$dir = dirname(dirname(__FILE__));
require_once($dir . '/config.php');

$uuid = array_key_exists('uuid', $_REQUEST) ? $_REQUEST['uuid'] : 0;
if(empty($uuid)){
	echoErr('missing args');	
}


$ret = getInfo($g_writeMdb, $uuid);
if($ret['ret'] != 0){
	echoErr('getList:'.$ret['ret']);
}else{
	echoK($ret['data']);
}


function getInfo($mdb, $uuid){
    $retArr = array();
    try{  
		$sql = "select channelId, roomId, name, userId, liveState from live_lists where uuid = ? limit 1";	  
        if(!($pstmt = $mdb->prepare($sql))){
            $retArr['ret'] = 12;return $retArr;
        }
        if($pstmt->execute(array($uuid))){
            $result = $pstmt->fetchAll();
            $resNum = count($result);
            if($resNum == 0){
                $retArr['ret']  = 14;return $retArr;
            }
			
            $info = array();
            $info['channelId']       = $result[0][0];
            $info['roomId']   = $result[0][1];
            $info['name']   = $result[0][2];
            $info['userId'] = $result[0][3];
			$info['liveState']  = $result[0][4];
            $retArr['ret']  = 0;
            $retArr['data'] = $info;
            return $retArr;
        }else{
            $retArr['ret'] = 13;return $retArr;
        }
    }catch(PDOException $e){
        $retArr['ret'] = 11;return $retArr;
    }
    $retArr['ret'] = 10;return $retArr;
}



