<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Authentication</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h2>Complete Your Payment</h2>
    <p>Click the button below to confirm your payment.</p>
    <button id="confirm-button">Confirm Payment</button>

    <script>
        document.getElementById("confirm-button").addEventListener("click", async function () {
            const stripe = Stripe("{{ config('services.stripe.key') }}");
            const result = await stripe.handleCardAction("{{ request('payment_intent') }}");
            if (result.error) {
                alert("Payment authentication failed: " + result.error.message);
            } else {
                window.location.href = "{{ route('payment.success', ['request_id' => request('request_id')]) }}";
            }
        });
    </script>
</body>
</html>
