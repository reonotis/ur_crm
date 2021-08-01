
<?php
    if(strpos(url()->current(),'show') !== false){
        $active = 1 ;
    }else if(strpos(url()->current(),'visit_history') !== false){
        $active = 2 ;
    }
?>

<div class="col-md-8">
    <div class="LeftBOX">
        <div class="BOXin" >
            <a href="{{route('customer.show', ['id' => $id ])}}" >
                <div class="customer_navigation <?php if($active == 1 ) echo "active" ?>" >
                    顧客基本情報
                </div>
            </a>
        </div>
        <div class="BOXin" >
            <a href="{{route('customer.visit_history', ['id' => $id ])}}" >
                <div class="customer_navigation <?php if($active == 2 ) echo "active" ?>" >
                    顧客来店履歴
                </div>
            </a>
        </div>
    </div>
</div>