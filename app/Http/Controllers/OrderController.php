<?php

namespace App\Http\Controllers;

use App\Actions\Options\GetCategoryOptions;
use App\Http\Resources\Order\OrderListResource;
use App\Http\Resources\Order\SubmitOrderListResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\SalesChannel;
use App\Services\OrderServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{   
    
    private $orderServices;
    public function __construct(OrderServices $orderServices)
    {
        $this->orderServices = $orderServices;
    }

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
            "products" => Product::get(),
            "platforms" => SalesChannel::get(),
        ]);

    }
    
    public function getData(Request $request) {
        try{
      
            $data = $this->orderServices->getData($request);
            $result = new OrderListResource($data);

            return $this->respond($result);
        }catch(\Exception $e)
        {
            return $this->exceptionError($e);
        };
    }

    public function createData(Request $request) {
        try{
 

            $data = $this->orderServices->createData($request);
            $result = new SubmitOrderListResource($data, "Success create order");

            return $this->respond($result);

        }catch(\Exception $e)
        {
            return $this->exceptionError($e->getMessage());

        };
    }

    public function deleteData($id){
        try{
           $data = $this->orderServices->deleteData($id);
           $result = new SubmitOrderListResource($data, "Success delete order");
            return $this->respond($result);
        }catch(\Exception $e)
        {
            return $this->exceptionError($e->getMessage());
        };
    }

    public function updateData(Request $request, $id){
        try{
            $data = $this->orderServices->updateData($id, $request);
            $result = new SubmitOrderListResource($data, "Success update order");
            return $this->respond($result);
        }catch(\Exception $e)
        {
            return $this->exceptionError($e->getMessage());
        };
    }


}
