<?php

namespace App\Http\Controllers\Api\V2;

use App\Order;
use App\Book;
use App\VirtualBox;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\GlobalConstants;
use Khsing\World\Models\Country;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
use DB;

class BooksController extends Controller
{
    public function getPendingBooks()
    {
        $user = Auth::user();
        $cnt = $user->printer_company->books()
            ->where('status', GlobalConstants::ORDER_STATUS_PENDING)
            ->count();

        return $cnt;
    }

    public function getBooksInLots()
    {
        $user = Auth::user();
        $books = $user->printer_company->books()
            ->where('status', GlobalConstants::ORDER_STATUS_CHECKING)
            ->orderBy('lot_id', 'desc')
            ->get();
        $result = [];
        foreach ($books as $book) {
            $item = $book->toArray();
            $item['book_id']    = GlobalConstants::getBookID($book->id);
            $item['order_id']    = GlobalConstants::getOrderID($book->order_id);
            $item['lot_number'] = $book->lot->lot_number;
            $item['brother_books'] = $book->order->book_count - 1;
            array_push($result, $item);
        }
        return new JsonResource($result);
    }


    public function getPrintingBooks()
    {
        $user = Auth::user();
        $books = $user->printer_company->books()
            ->where('status', GlobalConstants::ORDER_STATUS_PRINTING)
            ->get();
        $result = [];
        foreach ($books as $book) {
            $item = $book->toArray();
            $item['book_id']    = GlobalConstants::getBookID($book->id);
            $item['order_id']    = GlobalConstants::getOrderID($book->order_id);
            $item['lot_number'] = $book->lot->lot_number;
            $item['brother_books'] = $book->order->book_count - 1;
            array_push($result, $item);
        }
        return new JsonResource($result);
    }


    public function getAllBooks()
    {
        $user = Auth::user();
        $books = $user->printer_company->books()
            ->with(['courier_company'])
            ->get();

        $result = [];
        foreach ($books as $book) {
            $item = $book->toArray();
            //$countryName = $book->order->country;
            $item['country_code'] = $book->order->country;
            // if ($countryName) {
            //     $country = Country::getByName($countryName);
            //     $item['country_code'] = $country->code;
            // }
            $item['book_id']    = GlobalConstants::getBookID($book->id);
            $item['order_id']    = GlobalConstants::getOrderID($book->order_id);
            array_push($result, $item);
        }
        return new JsonResource($result);
    }

    public function getBook($id)
    {
        $user = Auth::user();
        $book = $user->printer_company->books()
            ->with(['courier_company'])
            ->where('id', $id)
            ->first();

        $book['book_id']    = GlobalConstants::getBookID($book->id);
        $book['order_id']    = GlobalConstants::getOrderID($book->order_id);
        return new JsonResource($book);
    }

    public function setAsPrinting(Request $request)
    {
        $user = Auth::user();
        $id = $request->id;
        $printer_company = $user->printer_company;
        $book = $printer_company->books()
            ->where('id', $id)
            ->first();
        if ($book == null) {
            return abort(401);
        }
        $book->status = GlobalConstants::ORDER_STATUS_PRINTING;
        $book->_save();


        $cnt = Book::where('order_id', $book->order_id)
            ->where('status', GlobalConstants::ORDER_STATUS_PRINTING)
            ->count();
        if ($cnt == $book->order->book_count) {
            $book->order->status = GlobalConstants::ORDER_STATUS_CHECKED;
            $book->order->_save();
        }

        return (new JsonResource($book))
            ->response()
            ->setStatusCode(201);
    }

    public function setAsPrinted(Request $request)
    {
        $user = Auth::user();
        $id = $request->id;
        $printer_company = $user->printer_company;
        $book = $printer_company->books()
            ->where('id', $id)
            ->first();
        if ($book == null) {
            return abort(401);
        }
        if ($book->status == GlobalConstants::ORDER_STATUS_PRINTING) {

            if ($book->order->book_count == 1) {
                $book->status = GlobalConstants::ORDER_STATUS_PRINTED;
                $book->order->status = GlobalConstants::ORDER_STATUS_PRINTED;
                $book->order->_save();
            } else {
                $bookInBox = $printer_company->books()
                    ->where('order_id', $book->order_id)
                    ->where('status', 'like', GlobalConstants::ORDER_STATUS_VIRTUALBOX . '%')
                    ->first();

                if ($bookInBox) {
                    $book->status = $bookInBox->status;
                } else {
                    //find the empty virtual box
                    $box = $printer_company->virtual_boxes()
                        ->where('order_id', null)
                        ->first();
                    $boxCnt = $printer_company->virtual_boxes()->count();

                    if ($box) {
                        $box->order_id = $book->order_id;
                        $box->save();
                    } else {
                        //create a new virtual box
                        $box = VirtualBox::create([
                            'order_id' => $book->order_id,
                            'printer_company_id' => $printer_company->id,
                            'number'    =>  GlobalConstants::ORDER_STATUS_VIRTUALBOX . ($boxCnt + 1)
                        ]);
                    }
                    $book->status = $box->number;
                }
            }
            $lot = $book->lot;
            $book->lot_id = null;
            $book->printed_at = now();
            $book->_save();

            //$book->order->update(['status' => GlobalConstants::ORDER_STATUS_PRINTING]);
            //$book->order->status = $book->status;
            //$book->order->_save();

            if ($lot->books()->count() == 0) {
                //this lot is finished!
                $lot->finished_at = now();
                $lot->save();
            }
        }

        return (new JsonResource($book))
            ->response()
            ->setStatusCode(201);
    }
}