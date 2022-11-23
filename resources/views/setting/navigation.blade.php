<?php
    $URL = url()->current();
    if(strpos($URL,'index') !== false){
        $active = 1;
    }else if(strpos($URL,'Email') !== false){
        $active = 2;
    }else if(strpos($URL,'Password') !== false){
        $active = 3;
    }else if(strpos($URL,'notice') !== false){
        $active = 4;
    }else if(strpos($URL,'lecture') !== false){
        $active = 5;
    }else{
        $active = 0;
    }
?>

<div class="setting-navigation-area">
    <a href="{{route('setting.changeEmail')}}" ><div class="setting-navigation <?php if($active == 2) echo "active"; ?>" >メールアドレス変更</div></a>
    <a href="{{route('setting.changePassword')}}" ><div class="setting-navigation <?php if($active == 3) echo "active"; ?>" >パスワード変更</div></a>
</div>
