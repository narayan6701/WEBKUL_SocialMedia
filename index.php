<?php

require_once('assets/php/functions.php');
if(isset($_SESSION['Auth'])){
    $user = getUser($_SESSION['userdata']['id']);
}

if(isset($_SESSION['Auth']) && $user['ac_status']==1){
    showPage('header',['page_title'=>'Pictogram - Home']);
    showPage('wall');
}
elseif(isset($_SESSION['Auth']) && $user['ac_status']==0){
    showPage('header',['page_title'=>'Pictogram - Verification']);
    showPage('verify_email');
}
elseif(isset($_SESSION['Auth']) && $user['ac_status']==2){
    showPage('header',['page_title'=>'Pictogram - Blocked']);
    showPage('blocked');
}
elseif(isset($_GET['signup'])){
    showPage('header',['page_title'=>'Pictogram - Signup']);
    showPage('signup');
}
elseif(isset($_GET['login'])){
    showPage('header',['page_title'=>'Pictogram - Login']);
    showPage('login');
}
else{
    showPage('header',['page_title'=>'Pictogram - Login']);
    showPage('login');
}

showPage('footer');
unset($_SESSION['error']);
unset($_SESSION['formdata']);