<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderListResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->transformCollection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Success get product lists",
                'pagination' => $this->metaData()
            ]
        ];
    }

    private function transformData($data)
    {
        // Mengubah string menjadi array dengan memisahkan berdasarkan koma
        $productNames = explode(',', $data->product_names);
        $quantities = explode(',', $data->quantities);

        // Menggabungkan product_names dan quantities menjadi array of object
        $products = [];
        for ($i = 0; $i < count($productNames); $i++) {
            $products[] = [
                'name' => $productNames[$i],
                'quantity' => $quantities[$i]
            ];
        }

        return [
            'order_id' => $data->order_id,
            'customer_name' => $data->customer_name,
            'payment_status' => $data->payment_status,
            'platform_name' => $data->platform_name,
            'products' => $products,
            'paid_at' => $data->paid_at,
            'category_id' => $data->category_id,
            'total_price' => $data->total_price,
        ];
    }

    private function transformCollection($collection)
    {
        return $collection->transform(function ($data) {
            return $this->transformData($data);
        });
    }

    private function metaData()
    {
        return [
            "total" => $this->total(),
            "count" => $this->count(),
            "per_page" => (int)$this->perPage(),
            "current_page" => $this->currentPage(),
            "total_pages" => $this->lastPage(),
            "links" => [
                "next" => $this->nextPageUrl()
            ],
        ];
    }
}
