@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card">
                <div class="card-header">顧客情報編集</div>
                <div class="card-body">

                    <form action="{{route('VisitHistory.single_update', ['id' => $VisitHistory->id])}}" method="post"  enctype="multipart/form-data" >
                        @csrf
                        <div class="editRow_02" >
                            <div class="editTitle_02" >会員番号</div>
                            <div class="editContent_02" ><?= $VisitHistory->member_number ?></div>
                        </div>
                        <div class="editRow_02" >
                            <div class="editTitle_02" >名前</div>
                            <div class="editContent_02" ><?= $VisitHistory->f_name . $VisitHistory->l_name ?>　様</div>
                        </div>
                        <div class="editRow_02" >
                            <div class="editTitle_02" >店舗</div>
                            <div class="editContent_02" ><?= $VisitHistory->shop_name ?></div>
                        </div>
                        <div class="editRow_02" >
                            <div class="editTitle_02" >来店日時</div>
                            <div class="editContent_02" >
                                {{ $VisitHistory->vis_date->format('Y年 m月 d日') }}<br>
                                <input type="time" name="vis_time" class="formInput" value="<?= date('H:i', strtotime($VisitHistory->vis_time)) ?>" >
                            </div>
                        </div>
                        <div class="editRow_02" >
                            <div class="editTitle_02" >担当</div>
                            <div class="editContent_02" >
                                <select class="formInput" name="staff_id" >
                                    <option value="" >選択しない</option>
                                    @foreach( $users as $user)
                                        <option value="<?= $user->id ?>" <?php if($user->id == $VisitHistory->staff_id ) echo ' selected' ; ?>  ><?= $user->name ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="editRow_02" >
                            <div class="editTitle_02" >メニュー</div>
                            <div class="editContent_02" >
                                <select class="formInput" name="menu_id" >
                                    <option value="" >選択しない</option>
                                    @foreach( $menus as $menu)
                                        <option value="<?= $menu->id ?>" <?php if($menu->id == $VisitHistory->menu_id ) echo ' selected' ; ?> ><?= $menu->menu_name ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="editRow_02" >
                            <div class="editTitle_02" >画像</div>
                            <div class="editContent_02" >
                                <div class="visHisImgs" >
                                    <div class="visHisImg" >
                                    <div class="visHisImgAngleName" >正面</div>
                                        @if($VisitHistory->img_pass1)
                                            <div class="customer_img" >
                                                <img src="{{asset('storage/customer_img/'.$VisitHistory->img_pass1)}}" >
                                            </div>
                                            <a href="{{ route('VisitHistory.delete',['id'=>$VisitHistory->id , 'angle'=>'1']) }}" onclick="return confirmDeleteImg();" >削除する</a>
                                        @else
                                            <label for="photo">画像ファイル:</label>
                                            <input type="file" class="form-control" name="image1" >
                                        @endif
                                    </div>
                                    <div class="visHisImg" >
                                    <div class="visHisImgAngleName" >側面</div>
                                        @if($VisitHistory->img_pass2)
                                            <div class="customer_img" >
                                                <img src="{{asset('storage/customer_img/'.$VisitHistory->img_pass2)}}" >
                                            </div>
                                            <a href="{{ route('VisitHistory.delete',['id'=>$VisitHistory->id , 'angle'=>'2']) }}" onclick="return confirmDeleteImg();" >削除する</a>
                                        @else
                                            <label for="photo">画像ファイル:</label>
                                            <input type="file" class="form-control" name="image2" >
                                        @endif
                                    </div>
                                    <div class="visHisImg" >
                                    <div class="visHisImgAngleName" >背面</div>
                                        @if($VisitHistory->img_pass3)
                                            <div class="customer_img" >
                                                <img src="{{asset('storage/customer_img/'.$VisitHistory->img_pass3)}}" >
                                            </div>
                                            <a href="{{ route('VisitHistory.delete',['id'=>$VisitHistory->id , 'angle'=>'3']) }}" onclick="return confirmDeleteImg();" >削除する</a>
                                        @else
                                            <label for="photo">画像ファイル:</label>
                                            <input type="file" class="form-control" name="image3" >
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="editRow_02" >
                            <div class="editTitle_02" >メモ</div>
                            <div class="editContent_02" >
                                <textarea class="formInput" name="memo" ><?= $VisitHistory->memo ?></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="customer_id" value="<?= $VisitHistory->customer_id ?>" >
                        <input type="submit" name="update" value="更新する" class="button" >
                        <input type="submit" name="cancel" value="キャンセル" class="button cancel" >
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


<script>

    function confirmDeleteImg(){
        var result =  confirm('この画像を削除しますか？\nこの操作は取り消せません ');
        return result;
    }
</script>


@endsection
