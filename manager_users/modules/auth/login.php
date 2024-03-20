<!-- dang nhap tai khoan -->
<?php
if (!defined('_CODE')) {
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Đăng nhập tài khoản'
];
layouts('header-login', $data);
if(isLogin()){
    redirect('?module=home&action=dashboard');
}
if (isPost()) {
    $filterAll = filter();
    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        $email = $filterAll['email'];
        $password = $filterAll['password'];
        $userQuery = oneRaw("SELECT password FROM user WHERE email ='$email'");
        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            if (password_verify($password, $passwordHash)) {
                redirect('?module=home&action=dashboard');
            } else {
                setFlashData('msg', 'mật khẩu không chính xác');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'email không tồn tại');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'vui lòng nhập email và password');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=login');
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center text-uppercase">Đăng nhập quản lý</h2>
        <?php
        if (!empty($msg)) {
            getSmg($msg, $msg_type);
        }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="Email" class="form-control" placeholder="Địa chỉ email">
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="Password" class="form-control" placeholder="Mật khẩu">
            </div>
            <button type="submit" class="btn btn-primary btn-block mg-btn">Đăng nhập</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký</a></p>
        </form>
    </div>
</div>
<?php
layouts('footer');
?>