<?php

require_once __DIR__ . '/../bootstrap_env.php';

class JWT {
  
  public static function sign($data) {
    $header_data = ['alg' => 'HS256', 'iat' => time()]; 
    $header = urlb64_encode(json_encode($header_data)); 
    $payload = urlb64_encode(json_encode($data)); 
    $to_sign = $header . "." . $payload;
    return $to_sign . "." . JWT::signature($to_sign); 
  } 

  public static function signature($data) {
    return hash("sha256", $_ENV['jwt_sign_secret'].$data);
  }

  public static function verify($auth) {
    list($h64,$d64,$sign) = explode(".",$auth);
    if (!empty($sign) and (JWT::signature($h64.".".$d64) != $sign)) {
      die("Invalid Signature");
    }
    $header = urlb64_decode($h64);
    $data = urlb64_decode($d64);
    return JWT::parse_json($data);
  }
  
  public static function parse_json($str) {
    $data = explode(",",rtrim(ltrim($str, '{'), '}'));
    $ret = array();
    foreach($data as $entry) {
      list($key, $value) =  explode(":",$entry);
      $key = rtrim(ltrim($key, '"'), '"');
      $value = rtrim(ltrim($value, '"'), '"');
      $ret[$key] = $value;
    }
    return $ret;
  }

  public static function urlb64_encode($str) {
    $str_b64 = base64_encode($str); 
    $str_b64_url_ready = str_replace(['+', '/', '='], ['-', '_', ''], $str_b64);
    return $str_b64_url_ready;
  }

    public static function urlb64_decode($str) {
    $str_b64_url = str_replace(['-', '_'], ['+', '/'], $str);

    // we had lost info - whether there was =, == or nothing
    $pad_length = 4 - (strlen($str_b64_url) % 4);
    if ($pad_length != 4) {
        $str_b64_url .= str_repeat('=', $pad_length);
    }

    $str_final = base64_decode($str_b64_url); 
    return $str_final;
  }
}

?>
