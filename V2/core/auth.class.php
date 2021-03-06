<?php

class Auth {

	public static $message = "";
	public static $code = 200;
	public static $access = true;
	
	private function __construct()
	{
		$this->checkKey();
	}

	public static function checkKey(){

		if (!isset($_SERVER['PHP_AUTH_USER'])||!isset($_SERVER['PHP_AUTH_PW'])){
				
				self::$message = "Solicitud Invalida: Debe incluir un token";
				self::$code = 401;
				return false;
			} else {
				$sm = new StudentManager();
				$am = new AdvisorManager();
				$advisor = $am->findById($_SERVER['PHP_AUTH_USER']);
				$advisor ? "" : $student = $sm->findById($_SERVER['PHP_AUTH_USER']);

				if ($advisor){
					if ($advisor->pasword == $_SERVER['PHP_AUTH_PW'] ){
						return true;
					} else {
						self::$message = "Solicitud Invalida: Usuario no autorizado!";
						self::$code = 401;
						return false;
					}
				}

				if ($student){
					if ($student->password == $_SERVER['PHP_AUTH_PW'] ){
						return true;
					} else {
						self::$message = "Solicitud Invalida: Usuario no autorizado!";
						self::$code = 401;
						return false;
					}
				}
				
				if (($_SERVER['PHP_AUTH_USER']!="Aladdin")||($_SERVER['PHP_AUTH_PW']!="open sesame")) {
					self::$message = "Solicitud Invalida: Usuario no autorizado!";
					self::$code = 401;
					return false;
				}
				return true;
		}

	}

	public static function response($status,$status_message,$data){
		header("HTTP/1.1 ".$status);
		$response=[
			'status' => $status,
			'status_message' => $status_message
		];

			$response['data']=$data;
	
		$json_response = json_encode($response);
		echo $json_response;

	}
}

?>