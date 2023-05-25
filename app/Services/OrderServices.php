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

      $query->join('order_items', 'order_items.order_id', '=', 'orders.id')
      ->join('products', 'order_items.product_id', '=', 'products.id')
      ->join('customers', 'orders.customer_id', '=', 'customers.id')
      ->join('sales_channels', 'orders.sales_channel_id', '=', 'sales_channels.id')
      ->selectRaw('orders.id AS order_id, orders.paid_at AS paid_at,orders.total_price, orders.payment_status , customers.name AS customer_name, sales_channels.platform_name, GROUP_CONCAT(products.name) AS product_names, GROUP_CONCAT(order_items.quantity) AS quantities')
      ->groupBy('orders.id');

      $query->when($search, function ($query, $search) {
         return $query->where('orders.id', 'like', '%' . $search . '%')
            ->orWhere('products.name', 'like', '%' . $search . '%')
            ->orWhere('customers.name', 'like', '%' . $search . '%')
            ->orWhere('sales_channels.platform_name', 'like', '%' . $search . '%');
      });

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
      $selectedProducts = $request->input('product_selected');
      
      foreach($selectedProducts as $product){
            $orderItems = new OrderItems();
            $orderItems->order_id = $orderID;
            $orderItems->product_id = intval($product['product_id']);
            $orderItems->quantity = intval($product['order_quantity']);
            $orderItems->price = $product['price'];
            $orderItems->total_price = $product['priceAmount'];
            $orderItems->save();
      }

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