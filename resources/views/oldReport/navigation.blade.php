<?php
    $URL = url()->current();
    if(strpos($URL,'daily') !== false){
        $active = 1;
    }else if(strpos($URL,'weekly') !== false){
        $active = 2;
    }else if(strpos($URL,'monthly') !== false){
        $active = 3;
    }else{
        $active = 0;
    }
?>

<div class="setting_navigation_area">
    <div class="LeftBOX">
        <a href="{{route('oldReport.daily')}}"   ><div class="setting_navigation LeftBOX <?php if($active == 1) echo "active"; ?>" >日別</div></a>
        <!-- <a href="{{route('oldReport.weekly')}}"  ><div class="setting_navigation LeftBOX <?php if($active == 2) echo "active"; ?>" >週別</div></a> -->
        <a href="{{route('oldReport.monthly')}}" ><div class="setting_navigation LeftBOX <?php if($active == 3) echo "active"; ?>" >月別</div></a>
    </div>
</div>
