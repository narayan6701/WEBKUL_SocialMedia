<?php

require_once('assets/php/functions.php');
if(isset($_SESSION['Auth'])){
    $user = getUser($_SESSION['userdata']['id']);
}

$pagecount = count($_GET);

//manage pages
if(isset($_SESSION['Auth']) && $user['ac_status']==1 && !$pagecount){
    showPage('header',['page_title'=>'Pictogram - Home']);
    showPage('navbar');
    showPage('wall');
}
elseif(isset($_SESSION['Auth']) && $user['ac_status']==0 && !$pagecount){
    showPage('header',['page_title'=>'Pictogram - Verification']);
    showPage('verify_email');
}
elseif(isset($_SESSION['Auth']) && $user['ac_status']==2 && !$pagecount){
    showPage('header',['page_title'=>'Pictogram - Blocked']);
    showPage('blocked');
}
elseif(isset($_SESSION['Auth']) && isset($_GET['editprofile']) && $user['ac_status']==1){
    showPage('header',['page_title'=>'Pictogram - Edit Your Profile']);
    showPage('navbar');
    showPage('edit_profile');
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
    if(isset($_SESSION['Auth']) && $user['ac_status']==1){
        showPage('header',['page_title'=>'Pictogram - Home']);
        showPage('navbar');
        showPage('wall');
    }elseif(isset($_SESSION['Auth']) && $user['ac_status']==0){
    showPage('header',['page_title'=>'Pictogram - Verification']);
    showPage('verify_email');
}
elseif(isset($_SESSION['Auth']) && $user['ac_status']==2){
    showPage('header',['page_title'=>'Pictogram - Blocked']);
    showPage('blocked');
}
    else{
        showPage('header',['page_title'=>'Pictogram - Login']);
        showPage('login');
    }
}

showPage('footer');
unset($_SESSION['error']);
unset($_SESSION['formdata']);