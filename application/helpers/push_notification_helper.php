<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('sendPush')) {
     function sendPush($deviceIds=array(), $message){
		//print_r($deviceIds);die;
		$url = 'https://fcm.googleapis.com/fcm/send';
		$msg = array(
			        'message' 	=> $message,
			        'title'		=> 'Scrap App'		
		            );

		$fields = array(
			'registration_ids' 	=> $deviceIds,
			'data'			=> $msg
		);
		
        //print_r($fields);die;
		$headers = array(			
			'Authorization: key=' . FIREBASE_API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		
		//print_r($headers);die;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields,$true));

		$result = curl_exec($ch);
        // print_r($result );die;
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}

		curl_close($ch);
		return !empty($result);       
     }

    }


?>