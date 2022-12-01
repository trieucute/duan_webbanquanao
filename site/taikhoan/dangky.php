<?php
require '../../global.php';
require '../../dao/khach-hang.php';

extract($_REQUEST);

if(exist_param("dang-ky")){
    if( empty($mat_khau)&&empty($email) &&empty($mat_khau2) ){
        $thongbao = "Vui lòng nhập đầy đủ thông tin";
    }

 
    else if(empty($mat_khau) ){
        $thongbao= "Mời nhập mật khẩu";
    }
    else if(empty($mat_khau2) ){
        $thongbao = "Mời xác nhận mật khẩu";
    }
    else if(empty($email) ){
        $thongbao = "Mời nhập email";
    }
   
    else if(khach_hang_exist($email)){
        $thongbao= "Email này đã được sử dụng!";
    }
    else if($mat_khau2 != $mat_khau){
        $thongbao= "Xác nhận mật khẩu mới không đúng!";
    }
    else{
        // $file_name = save_file("hinh", "$IMAGE_DIR/");
        // $hinh = $file_name?$file_name:"user.png";
     
        try {
        
            // Mã Hoá Mật Khẩu
            $key = 'qkwjdiw239&&jdafwe^%$ggdnawhd4njshjw3123123^&*^#!@#uuO';
            function encryptthis($mat_khau, $key)
            {
                $encryption_key = base64_decode($key);
                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
                $encrypted = openssl_encrypt($mat_khau, 'aes-256-cbc', $encryption_key, 0, $iv);
                return base64_encode($encrypted . '::' . $iv);
            }
            // khach_hang_insert_ho_ten($ho_ten);
            $mat_khau = $_POST['mat_khau'];
            $mat_khau = trim(encryptthis($mat_khau, $key));
            khachhang_insert( $mat_khau, $email,$vai_tro);
            khachhang_mauser($email);
            $thongbao= "Đăng ký thành viên thành công!";
            
        } 
        catch (Exception $exc) {
            print_r($exc);
            // print_r($khachhang_insert);
            $thongbao= "Đăng ký thành viên thất bại!";
        }
    }
}
else{
    global $mat_khau, $ho_ten, $email, $hinh,  $vai_tro;
}
if(exist_param("dang-nhap")){

    $user = khach_hang_select_by_ma($ma_user,$email);
   
    if($user){
       
        if($user['mat_khau'] == $mat_khau){
            $thongbao= "Đăng nhập thành công!";
            header('Location:../trangchinh');
            if(exist_param("ghi_nho")){
                add_cookie("email", $email, 30);
                add_cookie("mat_khau", $mat_khau, 30);
            }
            else{
                delete_cookie("email");
                delete_cookie("mat_khau");
            }
            $_SESSION["user"] =  khach_hang_select_by_ma($ma_user,$email);
            
            if(isset($_SESSION['request_uri'])){
                header("location: " . $_SESSION['request_uri']);
            }
         
        }
        else{
            // print_r($user);
            $thongbao= "Sai mật khẩu!";
        }
    }
    else{
        if(empty($email) && empty($mat_khau)){
            $thongbao= "Vui lòng nhập thông tin";
        }else if(empty($email)){
            $thongbao= "Vui lòng nhập tên email";
        }else if(empty($mat_khau)){
            $thongbao= "Vui lòng nhập mật khẩu";
        }else{
             $thongbao= "Sai email!";
        }
       
    }
}

else{

    if(exist_param("btn_logoff")){
    
            session_unset();
            
            // $VIEW_NAME="../trang-chinh/";

        
        
    }
 
    $mat_khau = get_cookie("mat_khau");
    

}





$VIEW_NAME="taikhoan/dangky-form.php";
if(exist_param('infor')){
    $VIEW_NAME="taikhoan/dangnhap-inf.php";
    
    }
require '../layout.php';?>

                 