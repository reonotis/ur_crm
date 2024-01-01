
@php
    $class = isset($class) ? $class : '';
@endphp

@if($errors->get($name))
    @php
        $class = 'input-error ' . $class;
    @endphp
@endif


<textarea  name="{{ $name }}" id="{{ $id }}" class="{{ $class }}"
>{{ $value }}</textarea>
