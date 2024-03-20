<?php
if(!defined('_CODE')){
    die('Access denied...');
}
$data = [
    'pageTitle' => 'Đăng nhập tài khoản'
];
layouts('header', $data);
if(isLogin()){
    redirect('?module=auth&action=login');
}
$listuser = getRaw('SELECT *FROM user ORDER BY update_at');
?>
<div class="container">
   
    <hr>
    <h2>quản lý người dùng</h2>
    <p><a href="?module=users&action=add" class="btn btn-success btn-sm"> thêm người dùng<i class="fa-solid fa-plus"></i></a></p>
    <table class="table table-bordered">
        <thead>
            <th>stt</th>
            <th width='180px'> họ tên </th>
            <th>email</th>
            <th>số điện thoại</th>
            <th>trạng thái</th>
            <th>sửa</th>
            <th>xóa</th>
        </thead>
         <tbody>
        <?php
        if(!empty($listuser)):
            $count=0;
            foreach($listuser as $item):
            $count++;
        
        ?>
       <tr>
            <td><?php echo $count; ?></td>
            <td><?php echo $item['fullname']; ?></td>
            <td><?php echo $item['email']; ?></td>
            <td><?php echo $item['phone']; ?></td>
            <td><?php echo $item['']; ?></td>
            <td> <a href="<?php echo _WEB_HOST; ?>?module=users&action=edit&id=<?php echo $item['id'];?> " class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
            <td><a href="<?php echo _WEB_HOST; ?>?module=users&action=delete&id=<?php echo $item['id'];?>" onclick="return confirm('bạn có chắc chắn muốn xóa')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
            </tr>
        <?php
        endforeach;
        endif;
        ?>
        </tbody>
    </table>
</div>