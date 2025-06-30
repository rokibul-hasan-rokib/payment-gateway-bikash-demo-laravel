<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
</head>
<body>
    <h1>Payment Successful</h1>
    <p>Payment ID: {{ $payment->paymentID }}</p>
    <p>Amount: {{ $payment->amount }}</p>
    <p>Transaction ID: {{ $payment->trxID }}</p>
</body>
</html>