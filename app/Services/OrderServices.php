<?php

namespace  App\Services;

use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\DB;

class OrderServices
{
   public function getData ($request) {

      $search = $request->search;


      $query = Order::query();

      $query->join('order_items','order_items.order_id','=','orders.id');
      $query->join('products','order_items.product_id','=','products.id');
      $query->join('customers','orders.customer_id','=','customers.id');
      $query->join('sales_channels','orders.sales_channel_id','=','sales_channels.id');
      $query->selectRaw('orders.*, products.name AS product_name, customers.name AS customer_name, sales_channels.platform_name');


    return $query->paginate(10);
   }

   public function deleteData ($id) {
      $order = Order::findOrFail($id);
      $order->delete();

      return $order;
   }

   public function createData($request){
      $inputs =  $request->only(['customer_id','payment_status' ,'sales_channel_id', 'total_price']);
      if ($inputs['payment_status'] === 'paid') {
          $inputs['paid_at'] = now()->format('Y-m-d H:i:s');; // Menggunakan waktu saat ini sebagai paid_date
      } else {
          $inputs['paid_at'] = null; // Jika payment_status bukan "paid", set paid_date menjadi null
      }



      $order = new Order();
      $order->fill($inputs);
      $order->save();
      $orderID = $order->id;
      $selectedProducts = $request->input('product_id');
      $orderItemQuantity = $request->input('order_quantity');
      $productPrice = $request->input('price');

      OrderItems::create([
          'product_id' => $selectedProducts,
          'order_id' => $orderID,
          'quantity' => $orderItemQuantity,
          'price' => $productPrice,
          'total_price' => $orderItemQuantity * $productPrice,
      ]);

      return $order;
   }

   public function updateData($id, $request) {
      $order = Order::findOrFail($id);
      $inputs =  $request->only(['payment_status']);
      if ($inputs['payment_status'] === 'paid') {
          $inputs['paid_at'] = now()->format('Y-m-d H:i:s');; 
      } else {
          $inputs['paid_at'] = null; 
      }
      $order->fill($inputs);
      $order->save();

      return $order;
   }
}