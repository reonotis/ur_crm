
@php
    $class = isset($class) ? $class : '';
@endphp

@if($errors->get($name))
    @php
        $class = 'input-error ' . $class;
    @endphp
@endif

<input type="text" name="{{ $name }}" id="{{ $id }}" class="{{ $class }}" value="{{ $value }}">
