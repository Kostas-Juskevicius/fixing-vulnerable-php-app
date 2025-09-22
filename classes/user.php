<?php
require 'classes/jwt.php';

class User {

  public static $lnk;

  public static function init($lnk) {
    self::$lnk = $lnk;
  }

  public static function logout() {
    setcookie("auth", NULL ,time()-10);  
  }
  public static function createcookie($user) {
    $data=array();
    $data['username']=$user;
    return JWT::sign($data);
  } 

  public static function addfile($user) {
    $file = "files/".$user."/".basename($_FILES["file"]["name"]);
    if (!preg_match("/\.pdf/", $file)) {
      return  "Only PDF are allowed"; 
    } elseif (!move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
      return "Sorry, there was an error uploading your file.";
    }
    return NULL;
  }

  public static function getfiles($user) {
    $base = "files/".$user;
    if (!file_exists($base)) {
      mkdir($base);
    }
    return array_diff(scandir($base), array('..', '.'));
  } 

  public static function getuserfromcookie($auth) {
    $data = JWT::verify($auth);
    $user = $data['username'];
    $sql = "SELECT * FROM users where login=\"";
    $sql.= mysqli_real_escape_string(self::$lnk, $user);
    $sql.= "\"";
    $result = mysqli_query(self::$lnk, $sql);
    if ($result) {
      $row = mysqli_fetch_assoc($result);
      return $row['login'];
    }
    else {
      return NULL;
    }
  }

  public static function login($user, $password) {
    $sql = "SELECT * FROM users where login=\"";
    $sql.= mysqli_real_escape_string(self::$lnk, $user);
    $sql.= "\"";

    $result = mysqli_query(self::$lnk, $sql);
    if ($result) {
      $row = mysqli_fetch_assoc($result);
      if ($user === $row['login'] and password_verify($password, $row['password'])) {
        return TRUE;
      }
    }
    return FALSE;
  }

  public static function register($user, $password) {
    $phash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (login ,password) values (\"";
    $sql.= mysqli_real_escape_string(self::$lnk, $user);
    $sql.= "\", \"";
    $sql.= $phash;
    $sql.= "\")";

    $result = mysqli_query(self::$lnk, $sql);
    if ($result) {
      return TRUE;
    }
    else 
      echo mysqli_error(self::$lnk);
    return FALSE;
  }
}

?>
