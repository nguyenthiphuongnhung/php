<!-- cac ham chung cua du an-->
<?php
if (!defined('_CODE')) {
    die('Access denied...');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layouts($layoutName = 'header', $data = [])
{
    if (file_exists(_WEB_PATH_TEMPLATES . '/layout/' . $layoutName . '.php')) {
        require_once _WEB_PATH_TEMPLATES . '/layout/' . $layoutName . '.php';
    }
}
//ham gui mail
function sendMail($to, $subject, $content)
{
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'nhung.com@gmail.com';                     //SMTP username
        $mail->Password   = 'ylvn utlm lsjl bqgb';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->setFrom('nhungnguyen.778396@gmail.com', 'Nhung');
        $mail->addAddress($to);     //Add a recipient
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $mail->send();
        echo 'gui thanh cong';
    } catch (Exception $e) {
        echo "gui mail that bai. Mailer Error: {$mail->ErrorInfo}";
    }
}
//kiem tra phuong thuc get
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}
//kiem tra phuong thuc post
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}
//ham filter loc du lieu
function filter()
{
    $filterArr = [];
    if (isGet()) {
        // return $_GET;
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    if (isPost()) {
        // return $_GET;
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    return $filterArr;
}
//kiem tra email
function isEmail($email)
{
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}
//kiem tra int
function isNumberInt($number)
{
    $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    return $checkNumber;
}
//kiem tra int
function isFload($fload)
{
    $checkFload = filter_var($fload, FILTER_VALIDATE_FLOAT);
    return $checkFload;
}
// kiem tra so dien thoai
function isPhone($phone)
{
    $checkZero = false;
    if ($phone[0] == '0') {
        $checkZero = true;
        $phone = substr($phone, 1);
    }
    $checkNumber = false;
    if (isNumberInt($phone) && (strlen($phone) == 9)) {
        $checkNumber = true;
    }
    if ($checkZero && $checkNumber) {
        return true;
    }
    return false;
}
//thong bao loi
function getSmg($smg, $type = 'success')
{
    echo '<div class="alert alert-' . $type . '">';
    echo $smg;
    echo '</div>';
}
//ham chuyen huong
function redirect($path = 'index.php')
{
    header("Location: $path");
    exit;
}
//ham thong bao loi input
function form_error($fileName, $beforeHtml = '', $afterHtml = '', $errors)
{
    return (!empty($errors[$fileName])) ? '<span  class="error">' . reset($errors[$fileName]) . '</span>' : null;
}
//ham hien thi du lieu da nhap input
function old($fileName, $oldData, $default = null)
{
    return (!empty($oldData[$fileName])) ? $oldData[$fileName] : $default;
}
//kiem tra dang nhap
function isLogin()
{
    $checkLogin = false;
    if (getSession('loginToken')) {
        $tokenLogin = getSession('loginToken');
        $queryToken = oneRaw("SELECT user_Id FROM loginToken WHERE token = '$tokenLogin' ");
        if (!empty($queryToken)) {
            $checkLogin = true;
        } else {
            removeSession('loginToken');
        }
    }
}
