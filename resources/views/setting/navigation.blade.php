

<?php
    $URL = url()->current();
    if(strpos($URL,'index') !== false){
        $active = 1;
    }else if(strpos($URL,'changeEmail') !== false){
        $active = 2;
    }else if(strpos($URL,'changePassword') !== false){
        $active = 3;
    }
?>


<div class="setting_navigation_area">
    <div class="LeftBOX">
        <a href="{{route('setting.index')}}"          ><div class="setting_navigation LeftBOX <?php if($active == 1) echo "active"; ?>" >設定画面TOP</div></a>
        <a href="{{route('setting.changeEmail')}}"    ><div class="setting_navigation LeftBOX <?php if($active == 2) echo "active"; ?>" >メールアドレス変更</div></a>
        <a href="{{route('setting.changePassword')}}" ><div class="setting_navigation LeftBOX <?php if($active == 3) echo "active"; ?>" >パスワード変更</div></a>
    </div>
</div>
