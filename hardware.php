<?php
include "access_token.php";

class HardWare{


	public  function checkSignature(){
		//验证服务器的有效性
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
		if($tmpStr== $signature && $echostr ) {
        	echo $echostr;//向服务器返回echostr字符串，证明验证成功.
        	self::menu_create();
        	self::send_welcome_message();       
		}
		else {
			self::auto_return_message();
			self::menu_create();		
		}
	}

	private static function send_welcome_message(){
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

	private static function menu_create(){
		$ch = curl_init(); //初始化一个CURL对象
		$token= new GetToken();
		$access_token= $token->read_token();
		$url= "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        curl_setopt($ch, CURLOPT_URL,$url);//设置你所需要抓取的URL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置curl参数，要求结果是否输出到屏幕上，为true的时候是不返回到网页中,假设上面的0换成1的话，那么接下来的$data就需要echo一下。
        curl_setopt($ch, CURLOPT_POST, 1);//设置curl参数，要求以post方式.
         $post_data = '{
	        "button": [
	            {
	                "type": "click", 
	                "name": "燃气浓度", 
	                "key": "GET_CONCENTRATION"
	            },
	            {
	                "type": "view", 
	                "name": "设备列表", 
	                "url": "https://hw.weixin.qq.com/devicectrl/panel/device-list.html?appid=wxc98cc90f6ab24780"
	            }, 
	            {
	                "type": "click", 
	                "name": "消音", 
	                "key": "MUTE"
	            },  
	          
	        ]
    	}';
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $result=curl_exec($ch);
       // if($result === FALSE){
         print curl_error($ch);    
       // }
        $data = json_decode($result);
        if($data->access_token){
            $token_file = fopen("token.txt","w") or die("Unable to open file!");//打开token.txt文件，没有会新建
            fwrite($token_file,$data->access_token);//重写tken.txt全部内容
            fclose($token_file);//关闭文件流
        }else{
            echo $data->errmsg;
        }
        curl_close($ch);
	}

    private static function auto_return_message(){
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
    		$content= 'you are good~';
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
    
    private static function response_device_message(){
    	$postArr= $GLOBALS['HTTP_RAW_POST_DATA'];
    	$postObj= simplexml_load_string($postArr);
    	//$postObj= json_decode($postArr);
    	//$postObj->ToUserName= '';
   		//$postObj->FromUserName= '';
        //$postObj->CreateTime= '';
        //$postObj->MsgType= '';
        //$postObj->DeviceType= '';   
        //$postObj->DeviceID= '';   
        //$postObj->SessionID= '';   
        //$postObj->MsgID= '';   
        //$postObj->OpenID= '';         
        if (True) {   	
    		$to_user= $postObj->FromUserName;
    		$from_user= $postObj->ToUserName;
    		$time= time();
    		$msg_type= 'device_text';
    		$device_type= $postObj->DeviceType;
    		$device_id= $postObj->DeviceID;
    		$session_id= $postObj->SessionID;
    		$content= 't';
    		$template= "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%u</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<DeviceType><![CDATA[%s]]></DeviceType>
						<DeviceID><![CDATA[%s]]></DeviceID>
						<SessionID>%u</SessionID>
						<Content><![CDATA[%s]]></Content>
    				   </xml>";
    		$info= sprintf($template,$to_user,$from_user,$time,$msg_type,$device_type,$device_id,$session_id,$content);
    		echo $info;  
    		$msg_type2= 'text';
    		$content2= 'you are good~';
    		$template2= "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						 <Content><![CDATA[%s]]></Content>
    				   </xml>";
    		$info2= sprintf($template,$to_user,$from_user,$time,$msg_type2,$content2);
    		echo $info2;  


  		}
	}  

	private static function response_device_status(){
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



}

$object= new HardWare();
$object->checkSignature();
?>