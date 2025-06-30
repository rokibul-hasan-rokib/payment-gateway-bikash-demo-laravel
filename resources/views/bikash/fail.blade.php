<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
</head>
<body>
    <h1>Payment Failed</h1>
    <p>Error: {{ $payment->errorMessage ?? 'Unknown error' }}</p>
</body>
</html>