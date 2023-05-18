<?php

namespace App\Http\Controllers;

use App\Actions\Options\GetCategoryOptions;
use App\Http\Resources\Order\OrderListResource;
use App\Models\Customer;
use App\Models\OrderItems;
use App\Services\OrderServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{   

    // public function __construct(OrderServices $orderServices)
    // {
    //     $this->orderServices = $orderServices;
    // }
    public function index()
    {


        return Inertia::render('admin/order/index', [
            "title" => 'POS | Order'
            ]);

    }
    public function createPage()
    {


        return Inertia::render('admin/order/create', [
            "title" => 'POS | Create Order',
            "customers" => Customer::get(),
            ]);

    }
    

    public function getData(Request $request) {
        try{
            $query = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('sales_channels', 'orders.sales_channel_id', '=', 'sales_channels.id')
            ->selectRaw('orders.*, products.name AS product_name, customers.name AS customer_name, sales_channels.platform_name')
            ->get();

            return response()->json($query);
        }catch(\Exception $e)
        {
        };
    }
}
