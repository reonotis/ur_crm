
@php
    if($errors->get($name)){
        $class = 'input-error ' . $class;
    }
@endphp

<input type="date" name="{{ $name }}" id="{{ $id }}" class="{{ $class }}" value="{{ $value }}">
