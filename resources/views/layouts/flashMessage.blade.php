<?php
    $successes = session(SessionConst::FLASH_MESSAGE_SUCCESS);
    $errors = session(SessionConst::FLASH_MESSAGE_ERROR);
?>

@if ($successes || $errors)
    <div class="flash-message-area" >
        {{-- サクセスメッセージ --}}
        @if ($successes)
            @foreach ($successes as $success)
                <div class="flash-message-box flash-message-success" >
                    <div class="flash-message-box-close" >×</div>
                    {{ $success }}
                </div>
            @endforeach
        @endif

        {{-- エラーメッセージ --}}
        @if ($errors)
            @foreach ($errors as $error)
                <div class="flash-message-box flash-message-error" >
                    <div class="flash-message-box-close" >×</div>
                    {{ $error }}
                </div>
            @endforeach
        @endif
    </div>
@endif
