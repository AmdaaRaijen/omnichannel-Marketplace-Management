<?php

namespace  App\Services;

use App\Models\Order;

class OrderServices
{
   public function getData ($request) {
    $query = DB::table('order_items')
    ->join('orders', 'order_items.order_id', '=', 'orders.id')
    ->join('products', 'order_items.product_id', '=', 'products.id')
    ->join('customers', 'orders.customer_id', '=', 'customers.id')
    ->join('sales_channels', 'orders.sales_channel_id', '=', 'sales_channels.id')
    ->select('orders.*', 'products.name', 'customers.name', 'sales_channels.platform_name')->get();
    return $query->paginate(10);
   }
}