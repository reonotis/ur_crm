@php
    $type = isset($type) ? $type : 'text';
    $inputName = isset($inputName) ? $inputName : '';
    $id = isset($id) ? $id : '';
    $class = isset($class) ? $class : '';
    $oldName = isset($oldName) ? $oldName : '';
    $old = old($oldName, '');
    $value = isset($value) ? $value : '';
    $value = (!empty($old)) ? $old : $value;
@endphp

{{-- エラーがある場合はclass追加 --}}
@if ($errors->any() && $errors->first($oldName))
    @php
        $class = $class  . ' input-error';
    @endphp
@endif

<input
    type="{{ $type }}"
    name="{{ $inputName }}"
    id="{{ $id }}"
    class="{{ $class }}"
    value="{{ $value }}"
>

@if ($errors->first($oldName))
    <span class="error-message">
        @php
            echo $errors->first($oldName);
        @endphp
    </span>
@endif
