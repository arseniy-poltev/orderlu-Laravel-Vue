<?php

namespace App\Http\Controllers\Api\V2;

use App\VirtualBox;
use App\Order;
use App\Book;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\GlobalConstants;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class VirtualBoxesController extends Controller
{
    public function index()
    {
        if (Gate::denies('virtual_box_access')) {
            return abort(401);
        }
        $virtualBoxes = Auth::user()->printer_company->virtual_boxes;
        $result = [];
        foreach ($virtualBoxes as $virtualBox) {
            $item['id']         =   $virtualBox->id;
            $item['number']     =   $virtualBox->number;
            $item['order_id']   =   GlobalConstants::getOrderID($virtualBox->order_id);

            $item['processing'] =   false;
            $order = Order::find($virtualBox->order_id);
            if ($order == null) {
                $item['books']  =   [];
                $item['total']  =   0;
                $item['count']  =   0;
                array_push($result, $item);
                continue;
            }

            $books = Book::where('order_id', $virtualBox->order_id)
                ->where('status', $virtualBox->number)
                ->select('id', 'book_name', 'printed_at', 'created_at', 'assigned_at')
                ->get();
            $item['books']  =   $books;
            $item['total']  =   count($books) > 0 ? $order->book_count : 0;
            $item['count']  =   count($books);

            array_push($result, $item);
        }
        return new JsonResource($result);
    }
    public function publish(Request $request)
    {
        if (Gate::denies('virtual_box_edit')) {
            return abort(401);
        }
        $id = $request->id;
        $box = VirtualBox::findOrFail($id);
        //check this box is ours
        $user = Auth::user();
        if ($box->printer_company_id != $user->printer_company_id) {
            return abort(401);
        }
        if ($box->order_id == null) {
            return new JsonResource($box);
        }
        //check all books are printed!
        $cnt = Book::where('order_id', $box->order_id)
            ->where('status', '!=', $box->number)
            ->count();

        if ($cnt > 0) {
            return abort(401);
        }
        $order = Order::find($box->order_id);

        if ($order) {
            $order->status = GlobalConstants::ORDER_STATUS_PRINTED;
            $order->printed_at = now();
            $order->_save();

            foreach ($order->books as $book) {
                $book->status = GlobalConstants::ORDER_STATUS_PRINTED;
                $book->_save();
            }
        }

        //----------------------------


        $box->order_id = null;
        $box->save();


        $value = [
            'books'     =>  null,
            'order_id'  =>  null,
            'count'     =>  0,
            'total'     =>  0,
            'processing' =>  false,
            'number'    =>  $box->number,
            'id'        =>  $box->id
        ];

        return new JsonResource($value);
    }
}