<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      background-color: #E0F7FA;
      font-family: Arial, sans-serif;
      color: #333;
    }

    .payment-form-container {
      width: 80%;
      max-width: 600px;
      margin: 40px auto;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 25px;
    }

    h2 {
      text-align: center;
      color: #00796B;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    label {
      font-weight: bold;
      color: #00796B;
    }

    input[type="text"],
    input[type="number"],
    input[type="email"],
    input[type="tel"],
    select {
      width: 100%;
      padding: 10px;
      border: 1px solid #B2EBF2;
      border-radius: 5px;
      margin-top: 5px;
      box-sizing: border-box;
    }

    .form-section {
      margin-bottom: 20px;
    }

    .form-section-heading {
      font-size: 1.1em;
      color: #004D40;
      margin-bottom: 10px;
    }

    .form-actions {
      display: flex;
      justify-content: space-between;
    }

    .submit-button {
      background-color: #00796B;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 48%;
    }

    .submit-button:hover {
      background-color: #004D40;
    }

    .cancel-button {
      background-color: #E57373;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 48%;
    }

    .cancel-button:hover {
      background-color: #D32F2F;
    }

    @media (max-width: 768px) {
      .payment-form-container {
        width: 90%;
      }
      .form-actions {
        flex-direction: column;
      }
      .submit-button, .cancel-button {
        width: 100%;
        margin-top: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="payment-form-container">
    <h2>Payment Details</h2>

    <form action="{{ route('payment.submit') }}" method="POST">
      @csrf
      <input type="hidden" name="request_id" value="{{ $request_id }}">

      <!-- Payment Information Section -->
      <div class="form-section">
        <div class="form-section-heading">Payment Information</div>
        <label for="request_total">Request Total</label>
        <input type="number" id="request_total" name="request_total" placeholder="Total Amount">

        <label for="payment_method">Payment Method</label>
        <select id="payment_method" name="payment_method">
          <option value="stripe">Stripe</option>
          <option value="paypal">PayPal</option>
        </select>

        <label for="brand">Card Brand</label>
        <input type="text" id="brand" name="brand" placeholder="Visa, MasterCard, etc.">
      </div>

      <!-- Card Details Section -->
      <div class="form-section">
        <div class="form-section-heading">Card Details</div>
        <label for="payment_account_last4">Card Last 4 Digits</label>
        <input type="text" id="payment_account_last4" name="payment_account_last4" maxlength="4" placeholder="1234">

        <label for="method_exp_month">Expiration Month</label>
        <input type="number" id="method_exp_month" name="method_exp_month" placeholder="MM">

        <label for="method_exp_year">Expiration Year</label>
        <input type="number" id="method_exp_year" name="method_exp_year" placeholder="YYYY">
      </div>

      <!-- Billing Information Section -->
      <div class="form-section">
        <div class="form-section-heading">Billing Information</div>
        <label for="billing_address">Billing Address</label>
        <input type="text" id="billing_address" name="billing_address" placeholder="Enter your billing address">
      </div>

      <!-- Confirmation Section -->
      <div class="form-section">
        <label for="payment_confirmation">Payment Confirmation</label>
        <input type="text" id="payment_confirmation" name="payment_confirmation" placeholder="Confirmation Code">
      </div>

      <!-- Form Actions -->
      <div class="form-actions">
        <button type="submit" class="submit-button">Submit Payment</button>
      </div>
    </form>

    <!-- Cancel Request Form -->
    <form action="{{ route('payment.cancel') }}" method="POST">
      @csrf
      <input type="hidden" name="request_id" value="{{ $request_id }}">
      <div class="form-actions">
        <button type="submit" class="cancel-button">Cancel Request</button>
      </div>
    </form>
  </div>
</body>
</html>
