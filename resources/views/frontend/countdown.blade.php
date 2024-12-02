{{-- resources/views/frontend/countdown.blade.php --}}
@extends('frontend.main_master')

@section('content')
<div class="container mx-auto mt-12 text-center">
    <h1 class="text-3xl font-bold">Preparing your request...</h1>
    <p class="text-lg text-gray-600 mt-4">You will be redirected in <span id="countdown">10</span> seconds.</p>
</div>

<script>
    let seconds = 5;
    const countdownElement = document.getElementById('countdown');
    const interval = setInterval(() => {
        seconds--;
        countdownElement.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(interval);
            window.location.href = "{{ $redirectUrl }}";
        }
    }, 1000);
</script>
@endsection
