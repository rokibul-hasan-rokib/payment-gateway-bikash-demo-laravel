<!DOCTYPE html>
<html>
<head>
    <title>bKash Payment</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js"></script>
</head>
<body>
    <h1>Pay with bKash</h1>
    <form id="payment-form">
        <input type="number" name="amount" placeholder="Amount" required>
        <button type="button" id="bKash_button">Pay with bKash</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#bKash_button').click(function() {
                const amount = $('input[name="amount"]').val();

                $.ajax({
                    url: "{{ route('bkash.create-payment') }}",
                    type: 'POST',
                    data: { amount: amount },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.paymentID) {
                            initiateBkash(data);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });

            function initiateBkash(data) {
                bKash.init({
                    paymentMode: 'checkout',
                    paymentRequest: {
                        amount: data.amount,
                        intent: 'sale',
                        merchantInvoiceNumber: data.merchantInvoiceNumber
                    },
                    createRequest: function(request) {
                        // Already created payment, just execute
                        $.ajax({
                            url: "{{ route('bkash.execute-payment') }}",
                            type: 'POST',
                            data: { paymentID: data.paymentID },
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    bKash.success().onSuccess(response.payment);
                                } else {
                                    bKash.fail().onFail();
                                }
                            },
                            error: function(err) {
                                bKash.fail().onFail();
                            }
                        });
                    },
                    executeRequestOnAuthorization: function() {
                        // Not needed for this implementation
                    }
                });

                bKash.reconfigure({
                    paymentMode: 'checkout',
                    paymentRequest: {
                        paymentID: data.paymentID
                    }
                });

                bKash.click();
            }
        });
    </script>
</body>
</html>