<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting for Customer</title>

    @vite(['resources/js/app.js'])

</head>
<body>

    <div id="loading-page" class="loading-container">
        <div class="spinner"></div>
        <p>Waiting for the customer to confirm pricing...</p>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    console.log('Provider loading page script is running...');

    const requestId = {{ $request_id }};

    if (typeof Echo !== 'undefined') {
        console.log('Listening for customer events...');

        Echo.channel('request.' + requestId)
            .listen('CustomerAcceptedPricing', function (e) {
                console.log('Customer accepted pricing, waiting for payment...');
                document.querySelector("#loading-page p").innerText = "Waiting for the customer to submit payment...";
            })
            .listen('CustomerRejectedPricing', function (e) {
                console.log('Customer rejected pricing, showing apology message...');

                let encodedReason = encodeURIComponent(e.reason);
                let url = `{{ route('customer.rejection', ['request_id' => $request_id]) }}?reason=` + encodedReason;

                window.location.href = url;
            })
            .listen('CustomerFormFilled', function (e) {
                if (e.success === true) {
                    window.location.href = '{{ route('provider.success', ['request_id' => $request_id]) }}';
                }
            });
    } else {
        console.error('Echo is not defined');
    }
});


        // document.addEventListener('DOMContentLoaded', function () {
        //     console.log('Provider loading page script is running...');  // Debugging log

        //     const requestId = {{ $request_id }};

        //     // Set a timeout for 3 minutes (180 seconds)
        //     let timeout = setTimeout(() => {
        //         console.log('Timeout reached. Redirecting to apology page...');
        //         window.location.href = '{{ route('provider.apology', ['request_id' => $request_id]) }}';
        //     }, 180000); // 180 seconds = 3 minutes

        //     // Check if Echo is initialized and defined
        //     if (typeof Echo !== 'undefined') {
        //         console.log('Echo is defined. Listening for events...');

        //         Echo.channel('request.' + requestId)
        //             .listen('CustomerFormFilled', function (e) {
        //                 clearTimeout(timeout);  // Stop the timeout if an event is received
        //                 console.log('Event received from customer:', e);

        //                 if (e.success === true) {
        //                     console.log('Redirecting to success page...');
        //                     window.location.href = '{{ route('provider.success', ['request_id' => $request_id]) }}';
        //                 } else if (e.canceled === true) {
        //                     console.log('Redirecting to apology page...');
        //                     window.location.href = '{{ route('provider.apology', ['request_id' => $request_id]) }}';
        //                 }
        //             });
        //     } else {
        //         console.error('Echo is not defined');
        //     }
        // });
    </script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Provider loading page script is running...');  // Debugging log

            const requestId = {{ $request_id }};

            // Set a timeout for 3 minutes (180 seconds)
            let timeout = setTimeout(() => {
                console.log('Timeout reached. Redirecting to apology page...');
                window.location.href = '{{ route('provider.apology', ['request_id' => $request_id]) }}';
            }, 180000); // 180 seconds = 3 minutes

            // Check if Echo is initialized and defined
            if (typeof Echo !== 'undefined') {
                console.log('Echo is defined. Listening for events...');

                // Listen for pricing confirmation from customer
                Echo.channel('request.' + requestId)
                    .listen('CustomerAcceptedPricing', function (e) {
                        console.log('Customer accepted pricing. Waiting for customer details...');
                        document.querySelector("#loading-page p").innerText = "Waiting for the customer to submit payment...";
                    });

                // Listen for form submission from customer
                Echo.channel('request.' + requestId)
                    .listen('CustomerFormFilled', function (e) {
                        clearTimeout(timeout);  // Stop the timeout if an event is received
                        console.log('Event received from customer:', e);

                        if (e.success === true) {
                            console.log('Redirecting to success page...');
                            window.location.href = '{{ route('provider.success', ['request_id' => $request_id]) }}';
                        } else if (e.canceled === true) {
                            console.log('Redirecting to apology page...');
                            window.location.href = '{{ route('provider.apology', ['request_id' => $request_id]) }}';
                        }
                    });
            } else {
                console.error('Echo is not defined');
            }
        });
    </script> --}}

    <style>
        /* Loading page styling */
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
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Loading text styling */
        p {
            font-size: 18px;
            color: #555;
            margin-top: 20px;
        }
    </style>

</body>
</html>
