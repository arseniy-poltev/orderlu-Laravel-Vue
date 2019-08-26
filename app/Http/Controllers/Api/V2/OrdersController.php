<?php

namespace App\Http\Controllers\Api\V2;

use App\Order;
use App\VirtualBox;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\GlobalConstants;
use Khsing\World\Models\Country;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
use DB;

class OrdersController extends Controller
{

    public function getPrintedOrders()
    {
        $user = Auth::user();
        $orders = $user->printer_company->orders()
            ->with(['courier_company'])
            ->where('status', GlobalConstants::ORDER_STATUS_PRINTED)
            ->get();

        $result = [];
        foreach ($orders as $order) {
            $order['country_code'] = $order->country;
            $order['order_id']  =   GlobalConstants::getOrderID($order->id);

            array_push($result, $order);
        }

        return new JsonResource($orders);
    }

    public function getFinishedOrders()
    {
        $user = Auth::user();
        $orders = $user->printer_company->orders()
            ->with(['courier_company'])
            ->where('status', GlobalConstants::ORDER_STATUS_PRINTED)
            ->get();

        $result = [];
        foreach ($orders as $order) {
            $order['country_code'] = $order->country;
            $order['order_id']  =   GlobalConstants::getOrderID($order->id);

            array_push($result, $order);
        }

        return new JsonResource($orders);
    }

    public function getAllOrders()
    {
        // $user = Auth::user();
        // $orders = $user->printer_company->orders()
        //     ->with(['courier_company'])
        //     ->get();

        // $result = [];
        // foreach ($orders as $order) {
        //     $item = $order->toArray();

        //     if ($order->country) {
        //         $country = Country::getByName($order->country);
        //         $item['country_code'] = $country->code;
        //     }
        //     $item['book_id']    = GlobalConstants::SUFFIX_BOOK . $item['id'];
        //     array_push($result, $item);
        // }
        $user = Auth::user();
        $books = $user->printer_company->books()
            ->with(['courier_company'])
            ->get();

        $result = [];
        foreach ($books as $book) {
            $item = $book->toArray();
            $item['order_id']       =   GlobalConstants::getOrderID($book->order_id);
            $item['book_id']        =   GlobalConstants::getBookID($book->id);
            $item['country_code']   =   $book->order->country;
            array_push($result, $item);
        }
        return new JsonResource($result);
    }

    public function getOrder($id)
    {
        $user = Auth::user();
        $order = $user->printer_company->orders()
            ->with(['courier_company'])
            ->where('id', $id)
            ->first();
        $order['country_code'] = $order->country;
        $order['order_id']  =   GlobalConstants::getOrderID($order->id);
        return new JsonResource($order);
    }
}