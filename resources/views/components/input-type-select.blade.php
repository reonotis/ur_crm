{{-- セレクトボックスを生成する
    @param int $id id名
    @param string $name フォーム名
    @param array $options 選択しとなる配列 構成:['value'=>value値, 'label'=>'表示する文字列' ]
 --}}

@php
    $class = isset($class) ? $class : '';
@endphp

@if($errors->get($name))
    @php
        $class = 'input-error ' . $class;
    @endphp
@endif

<select name="{{ $name }}" id="{{ $id }}" class="{{ $class }}">
    <option value="">選択してください</option>
    @foreach($options as $option)
        <option value="{{ $option['value'] }}"
            @if($value == $option['value'] )
                selected
            @endif
        >{{ $option['label'] }}</option>
    @endforeach
</select>
