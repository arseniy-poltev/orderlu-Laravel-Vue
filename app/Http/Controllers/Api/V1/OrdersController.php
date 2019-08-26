<?php

namespace App\Http\Controllers\Api\V1;

use App\Order;
use App\Book;
use App\PrinterRouterRelation;
use App\CourierRouterRelation;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order as OrderResource;
use App\Http\Requests\Admin\StoreOrdersRequest;
use App\Http\Requests\Admin\UpdateOrdersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\GlobalConstants;
use Khsing\World\Models\Country;
use DB;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Foundation\Http\FormRequest;

class OrdersController extends Controller
{
    public function index()
    {


        if (Gate::denies('order_access')) {
            return abort(401);
        }
        $books = Book::with(['printer_company', 'courier_company'])->get();
        $result = [];
        foreach ($books as $book) {
            $item = $book->toArray();
            $item['order_id']       =   GlobalConstants::getOrderID($book->order_id);
            $item['country_code']   =   $book->order->country;
            $item['book_id']        =   GlobalConstants::getBookID($book->id);
            array_push($result, $item);
        }
        return new JsonResource($result);
    }

    public function show($id)
    {
        if (Gate::denies('order_view')) {
            return abort(401);
        }

        // $order = Order::with(['printer_company'])
        //     ->select('id', 'order_id', 'printer_company_id', 'created_at', 'status')
        //     ->findOrFail($id);


        $book = Book::findOrFail($id);
        if (!$book) {
            return new JsonResource(null);
        }
        $order = Order::with('printer_company', 'courier_company')
            ->find($book->order_id);

        $order['order_id']       =   GlobalConstants::getOrderID($order->id);

        // $books = Order::where('order_id', $order_id)
        //     ->orderBy('printed_at', 'desc')
        //     ->get();
        // $number_books = count($books);

        // foreach ($books as $book) {
        //     if (
        //         $book->status == GlobalConstants::ORDER_STATUS_PRINTING
        //         || $book->status == GlobalConstants::ORDER_STATUS_PRINTED
        //         || $book->status == GlobalConstants::ORDER_STATUS_FINISHED
        //     ) {
        //         $status = $book->status;
        //         break;
        //     }
        //     $status = $book->status;
        // }

        // $order = $books[0];
        // $order['number_books'] = $number_books;
        // $order['printed_at']   = $status == GlobalConstants::ORDER_STATUS_PRINTED
        //     ? $order['printed_at'] : null;
        // $order['status']    =   $status;
        // $order['printer_company'] = $order->printer_company;
        // $order['courier_company'] = $order->courier_company;


        return new JsonResource($order);
    }


    public function store(Request $request)
    {
        // if (Gate::denies('order_create')) {
        // return abort(401);
        // }

        // $statement = DB::select("SHOW TABLE STATUS LIKE 'orders'");
        // $nextId = $statement[0]->Auto_increment;
        // $orderCode = "ORD-" . str_pad($nextId, 7, "0", STR_PAD_LEFT);
        // $request->merge(['order_code' => $orderCode]);


        // if ($request->status != GlobalConstants::ORDER_STATUS_PAID) {
        //     return (new JsonResource(null))
        //         ->response()
        //         ->setStatusCode(201);
        // }

        $location = [
            'country'   =>  $request->country,
            'state'     =>  $request->state,
            'city'      =>  $request->city
        ];
        $printerResult = GlobalConstants::decidePrinter($location);
        if ($printerResult) {
            //increase order cnt for the selected printer
            $relation = PrinterRouterRelation::find($printerResult->id);
            $relation->order_cnt += 1;
            $relation->save();
            //*************************************** */
            $request->merge(['printer_company_id' => $printerResult->printer_company_id]);
        } else {
            $request->merge(['printer_company_id' => -1]);
        }


        $location['printer_company_id'] = $request['printer_company_id'];

        $courierResult = GlobalConstants::decideCourier($location);
        if ($courierResult) {
            //increase order cnt for the selected printer
            $relation = CourierRouterRelation::find($courierResult->id);
            $relation->order_cnt += 1;
            $relation->save();
            //*************************************** */
            $request->merge(['courier_company_id' => $courierResult->courier_company_id]);
        } else {
            $request->merge(['courier_company_id' => -1]);
        }



        // $books = json_decode($request->book_names);
        $books = $request->items;
        $cnt = $books ? count($books) : 0;

        $request->merge(['book_count' => $cnt]);
        $request['status'] = GlobalConstants::ORDER_STATUS_PENDING;
        $request['order_code'] = "abc";

        $order = Order::create($request->all());

        // foreach ($books as $book) {

        $params = [];
        $params['order_id'] = $order->id;
        // $params['book_name'] = $book['name'];
        // $params['language'] = $book['language'];
        // $params['book_code'] = $book['book_code'];
        $params['book_name'] = "abc";
        $params['language'] = "es";
        $params['book_code'] = "abc";
        $params['pdf_url'] = 'http://www.africau.edu/images/default/sample.pdf';
        $params['printer_company_id'] = $order->printer_company_id;
        $params['courier_company_id'] = $order->courier_company_id;

        Book::create($params);
        // }


        return (new JsonResource(null))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateOrdersRequest $request, $id)
    {
        if (Gate::denies('order_edit')) {
            return abort(401);
        }

        $order = Order::findOrFail($id);
        if ($order) {

            $books = $order->books;
            foreach ($books as $book) {
                $book->update($request->all());
            }
            $order->update($request->all());
        }

        return (new JsonResource($book))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('order_delete')) {
            return abort(401);
        }

        $book = Book::findOrFail($id);
        $book->delete();

        return response(null, 204);
    }
}