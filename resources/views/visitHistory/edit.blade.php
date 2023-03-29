@extends('layouts.visitHistory')
@section('pageTitle', '来店履歴編集')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="visit-history-contents">
                <div class="card-body">
                    <form action="{{route('visitHistory.update', ['visitHistory' => $visitHistory->id ])}}" enctype="multipart/form-data" method="post">
                        @method('post')
                        @csrf
                        <div class="visit-history-row" >
                            <div class="visit-history-title" >顧客名</div>
                            <div class="visit-history-content" >{{ $visitHistory->customer->f_name . " " . $visitHistory->customer->l_name }}&nbsp;様</div>
                        </div>
                        <div class="visit-history-row" >
                            <div class="visit-history-title" >日付</div>
                            <div class="visit-history-content" >{{ $visitHistory->vis_date->format('Y 年 m 月 d 日') }}</div>
                        </div>
                        <div class="visit-history-row" >
                            <div class="visit-history-title" ><label for="vis_time">時間</label></div>
                            <div class="visit-history-content" >
                                <input type="time" name="vis_time" class="form-control" id="vis_time" value="{{ substr($visitHistory->vis_time, 0, 5) }}" >
                            </div>
                        </div>
                        <div class="visit-history-row" >
                            <div class="visit-history-title" ><label for="menu_id">メニュー</label></div>
                            <div class="visit-history-content" >
                                <select name="menu_id" id="menu_id" class="form-control" >
                                    <option value="0" >選択してください</option>
                                    @foreach ($menus AS $menu)
                                        <option value="{{ $menu->id }}" {{ ($visitHistory->menu_id == $menu->id)? "selected": "" }}   >{{ $menu->menu_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="visit-history-row" >
                            <div class="visit-history-title" ><label for="user_id">スタイリスト</label></div>
                            <div class="visit-history-content" >
                                <select name="user_id" id="user_id" class="form-control" >
                                    @foreach ($users AS $user)
                                        <option value="{{ $user->id }}" {{ ($visitHistory->user_id == $user->id)? "selected": "" }}   >{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="visit-history-row" >
                            <div class="visit-history-title" ><label for="memo">メモ</label></div>
                            <div class="visit-history-content" >
                                <textarea name="memo" id="memo" class="form-control" >{{ $visitHistory->memo }}</textarea>
                            </div>
                        </div>
                        <div class="visit-history-row">
                            <div class="visit-history-title"><label for="">画像</label></div>
                            <div class="visit-history-content">
                                <div class="img-upload-area">
                                    @for ($angle = 1; $angle <= 3; $angle++)
                                        <div class="img-upload-box">
                                            <div class="">
                                                {{ Common::ANGLE_LIST[$angle] }}
                                            </div>
                                            <label class="img-upload-label">
                                                <input type="file" name="image[{{ $angle }}]" class="img-file" id="img-file{{ $angle }}" accept=".png, .jpg, .jpeg">ファイルを選択
                                            </label>
                                            <p class="img-paragraph" id="img-file{{ $angle }}-paragraph">選択されていません</p>
                                            @foreach($images as $image)
                                                @if($image['angle'] === $angle)
                                                    <div class="customer-image-box">
                                                        <img id="img{{ $angle }}" src="{{ asset('storage/' . Common::DISPLAY_CUSTOMER_IMG_RESIZE_DIR . '/' . $image['img_pass']) }}" alt="{{ Common::ANGLE_LIST[$image['angle']] }}">
                                                    </div>
                                                    <div class="">
                                                        <label>
                                                            <input type="checkbox" name="imgDelete[{{ $angle }}]" class="img-delete" id="img-file{{ $angle }}-delete" >
                                                            削除する
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="flex mt-4" >
                            <a href="javascript:history.back()" class="submit back-btn" >戻る</a>
                            <input type="submit" name="" value="更新する" class="edit-btn" >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
</script>
