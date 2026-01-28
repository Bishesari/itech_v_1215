<?php
namespace App\Services;
use App\Models\Order;
use App\Models\ProductAccess;
class ProductPurchaseService
{
    public static function completeOrder(Order $order): ProductAccess
    {
        $product = $order->product;
        $user = $order->user;

        if (!$product->is_repeatable) {
            $existing = ProductAccess::where('user_id', $user->id)->where('product_id', $product->id)->first();
            if ($existing) {
                return $existing;
            }
        }
        return ProductAccess::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'starts_at' => now(),
            'expires_at' => $product->duration_days ? now()->addDays($product->duration_days) : null,
            'is_active' => true,
        ]);
    }

}
