<!-- ham lien quan session hay cookies-->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}
//ham gan session
function setSession($key,$value){
    return $_SESSION[$key] =$value;
}
//ham doc session
function getSession($key=''){
    if(empty($key)){
        return $_SESSION;
    }
    else{
        if(isset($_SESSION[$key])){{
            return $_SESSION[$key];
        }}
    }
}
//ham xoa session 
function removeSession($key =''){
if(empty($key)){
    session_destroy();
    return true;
}else{
    if(isset($_SESSION[$key])){
       unset ($_SESSION[$key]);
       return true;
    }
}
}
//ham gan flash data
function setFlashData($key,$value){
    $key = 'flash_' .$key;
    return setSession($key, $value);
}
//ham doc flash data
function getFlashData($key){
    $key = 'flash_' .$key;
    $data= getSession($key);
    removeSession($key);
    return $data;
}