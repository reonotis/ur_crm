
@php
    $type = isset($type) ? $type : 'text';
    $labelName = isset($labelName) ? $labelName : '';
    $required = isset($required) ? $required : false;
    $inputName = isset($inputName) ? $inputName : '';
    $id = isset($id) ? $id : '';
    $class = isset($class) ? $class : '';
    $value = isset($value) ? $value : '';
@endphp

@if($labelName)
    <div class="form-label">
        <label for="{{ $inputName }}">{{ $labelName }}</label>
        @if($required)
            <span class="required-info">※必須</span>
        @endif
    </div>
@endif

<div class="">
    @switch($type)
        @case('text')
            @component('components.input-type-text',
                [
                    'name' => $inputName,
                    'id' => $id,
                    'value' => $value,
                    'class' => $class,
                ])
            @endcomponent
            @break
        @case('textarea')
            @component('components.input-type-textarea',
                [
                    'name' => $inputName,
                    'id' => $id,
                    'value' => $value,
                ])
            @endcomponent
            @break
        @case('number')
            @component('components.input-type-number',
                [
                    'name' => $inputName,
                    'id' => $id,
                    'value' => $value,
                    'min' => $min,
                    'step' => $step,
                ])
            @endcomponent
            @break
        @case('select')
            @component('components.input-type-select',
                [
                    'name' => $inputName,
                    'id' => $id,
                    'value' => $value,
                    'options' => $options,
                    'class' => $class,
                ])
            @endcomponent
            @break
        @case('checkbox')
            @component('components.input-type-checkbox',
                [
                    'name' => $inputName,
                    'id' => $id,
                    'value' => $value,
                    'checked' => $checked,
                ])
            @endcomponent
            @break
        @case('date')
            @component('components.input-type-date',
                [
                    'name' => $inputName,
                    'id' => $id,
                    'value' => $value,
                    'class' => $class,
                ])
            @endcomponent
            @break
        @default
    @endswitch
</div>

@if ($errors->first($inputName))
    <span class="error-message">
        @php
            echo $errors->first($inputName);
        @endphp
    </span>
@endif
