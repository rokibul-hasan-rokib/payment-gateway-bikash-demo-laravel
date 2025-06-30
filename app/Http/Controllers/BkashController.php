<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BkashController extends Controller
{
    protected $bkashService;

    public function __construct(BkashService $bkashService)
    {
        $this->bkashService = $bkashService;
    }

    public function pay(Request $request)
    {
        return view('bkash.pay');
    }

    public function createPayment(Request $request)
    {
        $amount = $request->amount;
        $invoice = uniqid(); // Generate a unique invoice number

        $payment = $this->bkashService->createPayment($amount, $invoice);

        return response()->json($payment);
    }

    public function executePayment(Request $request)
    {
        $paymentID = $request->paymentID;
        $payment = $this->bkashService->executePayment($paymentID);

        // Process your payment here
        $status = $payment->transactionStatus ?? '';

        if ($status === 'Completed') {
            // Payment successful, update your database
            return response()->json(['status' => 'success', 'payment' => $payment]);
        }

        return response()->json(['status' => 'failed', 'payment' => $payment]);
    }

    public function callback(Request $request)
    {
        $paymentID = $request->paymentID;
        $payment = $this->bkashService->queryPayment($paymentID);

        if ($payment->transactionStatus === 'Completed') {
            // Payment successful
            return view('bkash.success', compact('payment'));
        }

        return view('bkash.fail', compact('payment'));
    }
}
