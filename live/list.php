<?php
//互动直播列表
$dir = dirname(dirname(__FILE__));
require_once($dir . '/config.php');


$ret = getList($g_writeMdb);
if($ret['ret'] != 0){
	echoErr('getList_failed:'.$ret['ret']);
}else{
	echoK($ret['data']);
}

function getList($mdb){
	$retArr = array();		
	try{			
		$sql = "select uuid, name, userId, liveState from live_lists order by liveState desc, id desc";	
		if(!($pstmt = $mdb->prepare($sql))){
            $retArr['ret'] = 12;return $retArr;    
        } 
		if($pstmt->execute()){
			$result = $pstmt->fetchAll();        
			$resNum = count($result);	
			
			$info   = array();
			$index  = 0;
			for($i = 0; $i < $resNum; $i++){					
				$uuid           = $result[$i][0];				
				$name           = $result[$i][1];				
				$userId         = $result[$i][2];				
				$liveState      = $result[$i][3];	
				
				$itemArr = array();					
				$itemArr['ID']        = $uuid;	
				$itemArr['Name']      = $name;	
				$itemArr['Creator']   = $userId;	
				$itemArr['liveState'] = $liveState;	
				
				$info[$index++] = $itemArr;
			}		
			
			$data = array();				
			$data['list']   = $info;	
		
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
