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
          $inputs['paid_at'] = now()->format('Y-m-d H:i:s');; 
      } else {
          $inputs['paid_at'] = null; 
      }

      Order::create($inputs);
      $orderID = $order->id;
      $selectedProducts = $request->input('product_id');
      $orderItemQuantity = $request->input('order_quantity');
      $productPrice = $request->input('price');

      for($i = 0; $i < count($selectedProducts); $i++){
         $orderItem = new OrderItems();
         $orderItem->product_id = $selectedProducts[$i];
         $orderItem->order_id = $orderID;
         $orderItem->quantity = $orderItemQuantity;
         $orderItem->price = $productPrice[$i];
         $orderItem->total_price = $orderItemQuantity[$i] * $productPrice[$i];
         $orderItem->save();
      }
      // OrderItems::create([
      //     'product_id' => $selectedProducts,
      //     'order_id' => $orderID,
      //     'quantity' => $orderItemQuantity,
      //     'price' => $productPrice,
      //     'total_price' => $orderItemQuantity * $productPrice,
      // ]);

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