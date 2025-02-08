<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .success-icon {
            font-size: 60px;
            color: #28a745;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-8">
            <div class="text-center">
                <i class="fas fa-check-circle success-icon"></i>
                <h2 class="text-success mt-3">Payment Successful</h2>
                <p class="lead">Thank you for your payment. Your transaction has been successfully processed.</p>
            </div>

            <!-- Payment Details -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-credit-card"></i> Payment Details
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($payment->payment_status) }}</span></p>
                    <p><strong>Method:</strong> {{ strtoupper($payment->payment_method) }}</p>
                    <p><strong>Billing Address:</strong> {{ $payment->billing_address }}</p>
                    <p><strong>Amount Paid:</strong> <span class="text-success fw-bold">${{ number_format($payment->request_total, 2) }}</span></p>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-receipt"></i> Transaction Details
                </div>
                <div class="card-body">
                    <p><strong>Currency:</strong> {{ strtoupper($transaction->currency) }}</p>
                </div>
            </div>

            <!-- Return to Home -->
            <div class="text-center">
                <a href="/" class="btn btn-lg btn-primary">
                    <i class="fas fa-home"></i> Return to Home
                </a>
            </div>
        </div>
    </div>

</body>
</html>
