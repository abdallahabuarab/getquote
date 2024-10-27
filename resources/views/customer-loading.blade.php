<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/app.js')
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <div id="loading-page" class="loading-container">
        <div class="spinner"></div>
        <p>Please wait, we are contacting the provider...</p>
    </div>

    <script>
        console.log('Customer loading page script is running...');

        // Wait for DOMContentLoaded to make sure Echo is initialized
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Echo !== 'undefined') {
                Echo.channel('request.' + {{ $request_id }})
                    .listen('ProviderResponse', function (e) {
                        console.log('Event received:', e);

                        if (e.response === 'accept') {
                            console.log('Redirecting to customer form...');
                            window.location.href = '{{ route('customer.create', ['request_id' => $request_id]) }}';
                        } else if (e.response === 'reject') {
                            console.log('Redirecting to apology page...');
                            let url = '/customer/apology/' + {{ $request_id }} + '/' + e.reason;
                            window.location.href = url;
                        }
                    });
            } else {
                console.error('Echo is not defined');
            }
        });
    </script>

    <style>
        /* Center the loading page */
        #loading-page {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Spinner design */
        .spinner {
            width: 50px;
            height: 50px;
            border: 6px solid #f3f3f3; /* Light border */
            border-top: 6px solid #3498db; /* Blue border on top */
            border-radius: 50%;
            animation: spin 1.5s linear infinite; /* Smooth rotation */
        }

        /* Spin animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Style for loading text */
        p {
            font-size: 18px;
            color: #555;
            margin-top: 20px;
        }

        /* Optional: make sure text aligns to the center */
        .loading-container {
            text-align: center;
        }
    </style>
</body>
</html>
