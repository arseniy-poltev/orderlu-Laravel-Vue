<?php

namespace App\Http\Controllers\Api\V2;

use App\Lot;
use App\Order;
use App\Book;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\GlobalConstants;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class LotsController extends Controller
{
    public function index()
    {
        if (Gate::denies('lot_access')) {
            return abort(401);
        }

        $user = Auth::user();
        $lots = $user->printer_company->lots;

        $result = [];
        foreach ($lots as $lot) {
            $item = $lot->toArray();
            $item['number_printed'] = $item['number_books'] - $lot->books()->count();
            array_push($result, $item);
        }

        return new JsonResource($result);
    }


    public function store(Request $request)
    {
        if (Gate::denies('lot_create')) {
            return abort(401);
        }

        $user = Auth::user();
        $printer_company = $user->printer_company;
        $lotCnt = $printer_company->lots()->count();
        $lot = Lot::create([
            'number_books'      =>  $request->number_books,
            'printer_company_id' =>  $printer_company->id,
            'lot_number'        =>  GlobalConstants::PREFIX_LOT . ($lotCnt + 1)
        ]);
        $lot['number_printed'] = 0;
        //change orders status to assigned which are in my printer company

        $books = $printer_company->books
            ->where('status', GlobalConstants::ORDER_STATUS_PENDING)
            // ->orderBy('created_at', 'asc')
            ->take($request->number_books);

        $index = 0;
        foreach ($books as $book) {
            //if ($index == 0) {
            //$book->order->status = GlobalConstants::ORDER_STATUS_CHECKING;
            //$book->order->save();
            //}
            $book->status = GlobalConstants::ORDER_STATUS_CHECKING;
            $book->lot_id = $lot->id;
            $book->assigned_at = now();
            $book->_save();
            $index++;
        }




        $pendingCnt = $user->printer_company->books()
            ->where('status', GlobalConstants::ORDER_STATUS_PENDING)
            ->count();

        return (new JsonResource([
            'lot'           =>  $lot,
            'pendingCnt'    =>  $pendingCnt
        ]))
            ->response()
            ->setStatusCode(201);
    }



    public function destroy($id)
    {
        if (Gate::denies('lot_delete')) {
            return abort(401);
        }

        $lot = Lot::findOrFail($id);
        $lot->delete();

        return response(null, 204);
    }
}