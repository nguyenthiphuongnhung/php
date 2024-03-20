<?php
if(!defined('_CODE')){
    die('Access denied...');
}
$filterAll = filter();
//kiem tra du lieu co ton tai k
if (!empty($filterAll['id'])) {
    $userId = $filterAll['id'];
    
    $userDetail = getRaw("SELECT * FROM user WHERE id='$userId'");
    if (!empty($userDetail)) {
        $deleteUser=delete('user',"id=$userId");
    } else{
        setFlashData('smg','liên kết người dùng không tồn tại');
        setFlashData('smg_type','danger');
    }
} else{
    setFlashData('smg','liên kết không tồn tại');
    setFlashData('smg_type','danger');
}
redirect('?module=users&action=list');
