<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function start(Order $order)
    {
        // امنیت: مالک سفارش
        abort_if($order->user_id !== auth()->id(), 403);

        // فقط سفارش pending
        abort_if($order->status !== 'pending', 403);

        // ساخت resNum اگر وجود ندارد
        if (!$order->resNum) {
            $order->resNum = uniqid('ORD-');
            $order->save();
        }

        $payload = [
            'action'      => 'token',
            'TerminalId'  => 31266886,
            'Amount'      => $order->amount,
            'ResNum'      => $order->resNum,
            'RedirectUrl' => route('payment.callback'),
        ];

        $response = Http::asJson()
            ->post('https://sep.shaparak.ir/onlinepg/onlinepg', $payload);

        $result = $response->json();

        if (!isset($result['token'])) {
            abort(500, 'خطا در دریافت توکن پرداخت');
        }

        $order->update([
            'authority' => $result['token'],
        ]);

        return view('payment.redirect', [
            'token' => $result['token'],
        ]);
    }

    public function callback(Request $request)
    {
        $order = Order::where('resNum', $request->ResNum)->firstOrFail();

        // اگر قبلاً پرداخت شده
        if (in_array($order->status, ['paid', 'completed'])) {
            return redirect()
                ->route('product.show', $order->product_id)
                ->with('success', 'پرداخت قبلاً انجام شده است.');
        }

        $verify = Http::asJson()->post(
            'https://sep.shaparak.ir/verifyTxnRandomSessionkey/ipg/VerifyTransaction',
            [
                'RefNum'         => $request->RefNum,
                'TerminalNumber' => 31266886,
            ]
        );

        $result = $verify->json();

        if (
            !isset($result['ResultCode']) ||
            $result['ResultCode'] != 0 ||
            !isset($result['TransactionDetail'])
        ) {
            $order->update(['status' => 'failed']);

            return redirect()
                ->route('product.show', $order->product_id)
                ->with('error', 'پرداخت ناموفق بود');
        }

        $txn = $result['TransactionDetail'];

        $order->update([
            'status' => 'paid',
            'refNum' => $txn['RefNum'] ?? null,
            'amount' => $txn['OrginalAmount'] ?? $order->amount,
        ]);

        // ایجاد دسترسی محصول
        $hasAccess = $order->user
            ->productAccesses()
            ->where('product_id', $order->product_id)
            ->exists();

        if ($order->product->is_repeatable || !$hasAccess) {
            ProductAccess::create([
                'user_id'    => $order->user_id,
                'product_id' => $order->product_id,
                'starts_at'  => now(),
                'expires_at' => $order->product->duration_days
                    ? now()->addDays($order->product->duration_days)
                    : null,
                'is_active'  => true,
            ]);
        }

        return redirect()
            ->route('product.show', $order->product_id)
            ->with('success', 'پرداخت موفق بود و دسترسی فعال شد.');
    }
}
