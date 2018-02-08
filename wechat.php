<?php

	$nonce= $_GET['nonce'];
	$signature= $_GET['signature'];
	$encodingAesKey= 'T6IgjEU33ZZJyVXG0nXoI6NMijDADrMmPMy2uDD4f5y';
	$token= 'weixin';
	$appId= 'wxec30ef911dedd602';
	$timestamp= $_GET['timestamp'];
	$echostr= $_GET['echostr'];

	$array = array($timestamp, $nonce,$token);
	sort($array);
	$tmpStr = implode( $array );
	$tmpStr = sha1( $tmpStr );


	if( $tmpStr== $signature && $echostr ){
        echo $echostr;
        send_welcome_message();
          
        
	}else{

		auto_return_message();
		response_device_status();
		response_device_message();
		
	}


	function send_welcome_message(){
		$postArr= $GLOBALS['HTTP_RAW_POST_DATA'];

    $postObj= simplexml_load_string($postArr);
    //$postObj->ToUserName= '';
    //$postObj->FromUserName= '';
    //$postObj->CreateTime= '';
    //$postObj->MsgType= '';
    //$postObj->Event= '';
    
    if (strtolower($postObj->MsgType)== 'event'){
    	if (strtolower($postObj->Event)== 'subscribe'){ 
    		$to_user= $postObj->FromUserName;
    		$from_user= $postObj->ToUserName;
    		$time= time();
    		$msg_type= 'text';
    		$content= 'welcome';
    		$template= "<xml>
    					<ToUserName><![CDATA[%s]]></ToUserName>
    					<FromUserName><![CDATA[%s]]></FromUserName>
    					<CreateTime>%s</CreateTime>
    					<MsgType><![CDATA[%s]]></MsgType>
    					<Content><![CDATA[%s]]></Content>
    					</xml>";
    		$info= sprintf($template,$to_user,$from_user,$time,$msg_type,$content);
    		echo $info;
    		WriteLog($info,Server);
    		}
   	 	}
	}

    function auto_return_message(){
    	$postArr= $GLOBALS['HTTP_RAW_POST_DATA'];
    	$postObj= simplexml_load_string($postArr);
    	//$postObj->ToUserName= '';
   		//$postObj->FromUserName= '';
        //$postObj->CreateTime= '';
        //$postObj->MsgType= '';
        //$postObj->MsgId= '';    
        if (strtolower($postObj->MsgType)== 'text') {   	
    		$to_user= $postObj->FromUserName;
    		$from_user= $postObj->ToUserName;
    		$time= time();
    		$msg_type= 'text';
    		$content= 'welcome to the new world!';
    		$template= "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						 <Content><![CDATA[%s]]></Content>
    				   </xml>";
    		$info= sprintf($template,$to_user,$from_user,$time,$msg_type,$content);
    		echo $info;    	
  		}
	}  
    
    function response_device_message(){
    	$postArr= $GLOBALS['HTTP_RAW_POST_DATA'];
    	$postObj= simplexml_load_string($postArr);
    	//$postObj->ToUserName= '';
   		//$postObj->FromUserName= '';
        //$postObj->CreateTime= '';
        //$postObj->MsgType= '';
        //$postObj->DeviceType= '';   
        //$postObj->DeviceID= '';   
        //$postObj->SessionID= '';   
        //$postObj->MsgID= '';   
        //$postObj->OpenID= '';   
        //
        
        if (strtolower($postObj->MsgType)== 'device_text') {   	
    		$to_user= $postObj->FromUserName;
    		$from_user= $postObj->ToUserName;
    		$time= time();
    		$msg_type= 'device_text';
    		$device_type= $postObj->DeviceType;
    		$device_id= $postObj->DeviceID;
    		$session_id= $postObj->SessionID;
    		$content= 'tttttt';
    		$template= "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<DeviceType><![CDATA[%s]]></DeviceType>
						<DeviceID><![CDATA[%s]]></DeviceID>
						<SessionID>%u</SessionID>
						<Content><![CDATA[%s]]></Content>
    				   </xml>";
    		$info= sprintf($template,$to_user,$from_user,$time,$msg_type,$device_type,$device_id,$session_id,$content);
    		echo $info;    	
  		}
	}  

	function response_device_status(){
    	$postArr= $GLOBALS['HTTP_RAW_POST_DATA'];
    	$postObj= simplexml_load_string($postArr);
    	//$postObj->ToUserName= '';
   		//$postObj->FromUserName= '';
        //$postObj->CreateTime= '';
        //$postObj->MsgType= '';
        //$postObj->Event= '';   
        //$postObj->DeviceType= '';   
        //$postObj->DeviceID= '';   
        //$postObj->OpType= '';   
        //$postObj->OpenID= '';   
        //
        
        if (strtolower($postObj->MsgType)== 'device_event') { 
        	if(strtolower($postObj->OpType)== 'subscribe_status'){
	    		$to_user= $postObj->FromUserName;
	    		$from_user= $postObj->ToUserName;
	    		$time= time();
	    		$msg_type= 'device_status';
	    		$device_type= $postObj->DeviceType;
	    		$device_id= $postObj->DeviceID;
	    		$device_status= $postObj->DeviceStatus;
	    		$template= "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<DeviceType><![CDATA[%s]]></DeviceType>
							<DeviceID><![CDATA[%s]]></DeviceID>
							<DeviceStatus>%u</DeviceStatus>
	    				   </xml>";
	    		$info= sprintf($template,$to_user,$from_user,$time,$msg_type,$device_type,$device_id,$device_status);
	    		echo $info;    	
    		}
  		}
	} 




?>