<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController_BA extends Controller
{
    public function start(Order $order)
    {
        // 1️⃣ امنیت: مطمئن شویم کاربر مالک سفارش است
        abort_if($order->user_id !== auth()->id(), 403);

        // 2️⃣ فقط سفارش‌های pending می‌توانند پرداخت شوند
        abort_if($order->status !== 'pending', 403);

        // 3️⃣ اگر قبلاً resNum وجود ندارد، بساز
        if (!$order->resNum) {
            $order->resNum = uniqid('ORD-');
        }

        // 4️⃣ درخواست توکن به درگاه
        $payload = [
            'action'      => 'token',
            'TerminalId'  => 31266886,
            'Amount'      => $order->amount,
            'ResNum'      => $order->resNum,
            'RedirectUrl' => route('payment.callback'),
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://sep.shaparak.ir/onlinepg/onlinepg', $payload);

        $result = $response->json();

        // 5️⃣ بررسی خطا در دریافت توکن
        if (!isset($result['token'])) {
            abort(500, 'خطا در دریافت توکن پرداخت');
        }

        // 6️⃣ ذخیره authority و resNum در Order
        $order->update([
            'resNum'    => $order->resNum,
            'authority' => $result['token'],
        ]);

        // 7️⃣ نمایش فرم redirect به درگاه
        return view('payment.redirect', [
            'token' => $result['token'],
        ]);
    }

    public function callback(Request $request)
    {
        $resNum = $request->ResNum; // شماره سفارش ما
        $order = Order::where('resNum', $resNum)->firstOrFail();

        // اگر سفارش قبلاً پرداخت شده یا تکمیل شده، دوباره Verify نکن
        if (in_array($order->status, ['paid', 'completed'])) {
            return redirect()->route('product.show', $order->product_id)
                ->with('success', 'پرداخت قبلا با موفقیت انجام شده است.');
        }

        // ۱) Verify تراکنش با بانک
        $verify = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('https://sep.shaparak.ir/verifyTxnRandomSessionkey/ipg/VerifyTransaction', [
            "RefNum"         => $request->RefNum,
            "TerminalNumber" => 31266886,
        ]);

        $result = $verify->json();

        // ۲) بررسی خطا
        if (!$result || !isset($result["ResultCode"]) || $result["ResultCode"] != 0) {
            $order->update(['status' => 'failed']);
            return redirect()->route('product.show', $order->product_id)
                ->with('error', 'پرداخت ناموفق بود: ' . ($result['ResultDescription'] ?? 'خطای ناشناخته'));
        }

        // ۳) پرداخت موفق: وضعیت Order را آپدیت کن
        $txn = $result["TransactionDetail"];
        $order->update([
            'status'  => 'paid',
            'refNum'  => $txn['RefNum'] ?? null,
            'amount'  => $txn['OrginalAmount'] ?? $order->amount, // بروزرسانی مبلغ واقعی
        ]);

        // ۴) ایجاد دسترسی به محصول برای کاربر
        // اگر محصول غیر تکرارپذیر است و قبلاً دسترسی دارد، دوباره نساخت
        if ($order->product->is_repeatable || !$order->user->productAccesses()->where('product_id', $order->product_id)->exists()) {
            $startsAt = now();
            $expiresAt = $order->product->duration_days
                ? now()->addDays($order->product->duration_days)
                : null;

            ProductAccess::create([
                'user_id'    => $order->user_id,
                'product_id' => $order->product_id,
                'starts_at'  => $startsAt,
                'expires_at' => $expiresAt,
                'is_active'  => true,
            ]);
        }

        // ۵) بازگشت به صفحه محصول با پیام موفقیت
        return redirect()->route('product.show', $order->product_id)
            ->with('success', 'پرداخت با موفقیت انجام شد و دسترسی به محصول ایجاد شد.');
    }
}
