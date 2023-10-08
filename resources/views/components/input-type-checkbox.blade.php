{{-- セレクトボックスを生成する
    @param int $id id名
    @param string $name フォーム名
    @param array $options 選択しとなる配列 構成:['value'=>value値, 'label'=>'表示する文字列' ]
 --}}

@php
    $class = isset($class) ? $class : '';
    if($errors->get($name)){
        $class = 'input-error';
    }
@endphp


<input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
       @if($checked)
           checked
       @endif
>





