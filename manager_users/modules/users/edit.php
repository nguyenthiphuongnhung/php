<?php
if(!defined('_CODE')){
    die('Access denied...');
}
$data = [
'pageTitle'=>'danh sách người dùng'
];
layouts('header-login',$data);

$filterAll = filter();

if (!empty($filterAll['id'])) {
    $userId = $filterAll['id'];
    
    $userDetail = oneRaw("SELECT * FROM user WHERE id='$userId'");
    if (!empty($userDetail)) {
        setFlashData('user-detail', $userDetail);
    } else redirect('?module=users&action=list');
}

///////////
if(isPost()) {
    $filterAll = filter();
    $errors = [];
    if(empty($filterAll['fullName'])) {
        $errors['fullName']['required'] = 'vui lòng nhập họ tên';
    }else{
        if(strlen($filterAll['fullName']) < 5) {
            $errors['fullName']['min'] = 'phải dài hơn 5 kí tự';
        }
    }
///////////
    if(empty($filterAll['email'])) {
        $errors['email']['required'] = 'vui lòng nhập email';
    }else{
        $email = $filterAll['email'];
        $sql= "SELECT id FROM user WHERE email = '$email' AND id<>$userId";
        if(getRows($sql)>0){
            $errors['email']['unique'] = 'email đã tồn tại';
        }
    }
///////////
    if(empty($filterAll['phone'])) {
        $errors['phone']['required'] = 'vui lòng nhập số điện thoại';
    }else{
        if(!isPhone($filterAll['phone'])) {
            $errors['phone']['isPhone'] = 'số điện thoại không hợp lệ';
        }
    }
///////////
    if(empty($filterAll['password'])) {
        $errors['password']['required'] = 'vui lòng nhập password';
    }else{
        if(strlen($filterAll['password']) < 8) {
            $errors['password']['min'] = 'phải dài hơn 8 kí tự';
        }
    }
///////////
    if(empty($filterAll['password_confirm'])) {
    $errors['password_confirm']['required'] = 'vui lòng nhập lại password';
    }else{
    if(($filterAll['password']) != $filterAll['password_confirm']) {
        $errors['password_confirm']['match'] = 'mật khẩu không trùng khớpS';
    }
}
// echo '<pre>';
// print_r($errors);
// echo '<?/pre>';

/////// 
    if(empty($errors)) {
        $activeToken = sha1(uniqid().time());
    $dataupdate = [
        'fullName'=>$filterAll['fullName'],
        'email'=>$filterAll['email'],
        'phone'=>$filterAll['phone'],
       // 'password'=> password_hash($filterAll['password'],PASSWORD_DEFAULT),
        'activeToken'=>$activeToken,
        'create_at'=>date('Y-m-d H:i:s')
    ];    
    if(!empty($filterAll['password'])){
        $dataupdate['password']= password_hash($filterAll['password'],PASSWORD_DEFAULT);
    }
    $condition = "id= $userId";
    $updateStatus = update('user',$dataupdate,$condition);
    setFlashData('smg','sua thanh cong');
    setFlashData('smg_type','success');
    //redirect('?module=auth&action=login');
    }
    else {
    setFlashData('smg','vui long kiem tra lai du lieu');
    setFlashData('smg_type','danger');
    setFlashData('errors',$errors);
    setFlashData('old',$filterAll);
   
    }
    redirect('?module=auth&action=edit&id='.$userId);
}

$smg = getFlashData('smg');
$smg_type = getFlashData('smg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
$userDe = getFlashData('user-detail');
// echo '<pre>';
// print_r($userDe);
// echo '</pre>';
// echo '<pre>';
// print_r($errors);
// echo '</pre>';
if(!empty($userDe)){
    $old=$userDe;
}
?>
<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">update người dùng</h2>
        <?php
        if(!empty($smg)){
            getSmg($smg,$smg_type);
        }
        ?>
        <form action="" method="post">
        <div class="form-group mg-form">
                <label for="">Họ Tên</label>
                <input name="fullname" type="fullname" class="form-control" placeholder="Họ và tên"value="<?php
                echo(!empty($old['fullname']))?$old['fullname']:null;
                ?>">
                <?php
                echo(!empty($errors['email']))?'<span  class="error">'.reset($errors['email']).'</span>':null;
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" class="form-control" placeholder="Địa chỉ email"value="<?php
                echo(!empty($old['email']))?$old['email']:null;
                ?>">
                <?php
                echo(!empty($errors['email']))?'<span  class="error">'.reset($errors['email']).'</span>':null;
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Số điện thoại</label>
                <input name="phone" type="number" class="form-control" placeholder="Số điện thoại"value="<?php
                echo(!empty($old['phone']))?$old['phone']:null;
                ?>">
                <?php
                echo(!empty($errors['phone']))?'<span  class="error">'.reset($errors['phone']).'</span>':null;
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="text" class="form-control" placeholder="Mật khẩu">
                <?php
                echo(!empty($errors['password']))?'<span  class="error">'.reset($errors['password']).'</span>':null;
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Nhập lại Password</label>
                <input name="password_confirm" type="text" class="form-control" placeholder="Nhập lại mật khẩu">
                <?php
                echo(!empty($errors['password_confirm']))?'<span  class="error">'.reset($errors['password_confirm']).'</span>':null;
                ?>
            </div>
            <button type="submit" class="btn btn-success btn-block mg-btn">sửa</button>
         
            <a class="btn btn-success btn-block mg-btn" href="?module=users&action=list">quay lại</a>
        </form>
    </div>
</div>
<?php
layouts('footer');
?>