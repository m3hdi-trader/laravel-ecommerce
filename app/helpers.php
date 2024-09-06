<?php

use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;

function CartTotalSaleAmount()
{
    $cartTotalSaleAmount = 0;
    foreach (\Cart::getContent() as $item) {
        if ($item->attributes->is_sale) {
            $cartTotalSaleAmount += $item->quantity * ($item->attributes->price - $item->attributes->sale_price);
        }
    }

    return $cartTotalSaleAmount;
}

function CartTotalDeliveryAmount()
{
    $cartTotalDeliveryAmount = 0;
    foreach (\Cart::getContent() as $item) {
        $cartTotalDeliveryAmount += $item->associatedModel->delivery_amount;
    }

    return $cartTotalDeliveryAmount;
}

function CartTotalAmount()
{
    if (session()->has('coupon')) {
        if (session()->get('coupon.amount') > (\Cart::getTotal() + CartTotalDeliveryAmount())) {
            return 0;
        } else {
            return (\Cart::getTotal() + CartTotalDeliveryAmount()) - session()->get('coupon.amount');
        }
    } else {
        \Cart::getTotal() + CartTotalDeliveryAmount();
    }
}

function checkCoupon($code)
{
    $coupon = Coupon::where('code', $code)->where('expired_at', '>', Carbon::now())->first();

    if ($coupon == null) {
        return ['error' => 'کد تخفیف وارد شده وجود ندارد'];
    }

    if (Order::where('user_id', auth()->id())->where('coupon_id', $coupon->id)->where('payment_status', 1)->exists()) {
        return ['error' => 'شما قبلا از این کد تخفیف استفاده کرده اید'];
    }

    if ($coupon->getRawOriginal('type') == 'amount') {
        session()->put('coupon', ['code' => $coupon->code, 'amount' => $coupon->amount]);
    } else {
        $total = \cart::getTotal();
        $amount = (($total * $coupon->percentage) / 100) > $coupon->max_percentage_amount ? $coupon->max_percentage_amount : (($total * $coupon->percentage) / 100);
        session()->put('coupon', ['code' => $coupon->code, 'amount' => $coupon->amount]);
    }

    return ['success' => 'کد تخفیف برای شما ثبت شد'];
}
