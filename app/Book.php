<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 *
 * @package App
 * @property string $name
 * @property string $postmen_id
 * @property string $img_logo
 */
class Book extends Model
{
    //use SoftDeletes;


    protected $fillable = [
        'order_id', 'book_name', 'book_code', 'lot_id', 'language', 'pdf_url',
        'printer_company_id', 'courier_company_id', 'status',
        'assigned_at', 'printed_at',
    ];


    public static function storeValidation($request)
    {
        return [
            // 'book_name'    =>  'max:1000|required',
            // 'book_count'    =>  'numeric|min:1|max:10|required',
            // 'country'       =>  'max:50|required',
            // 'state'         =>  'max:50|required',
            // 'city'          =>  'max:50|required',
            // 'zip_code'      =>  'max:10|required',
            // 'street_address' =>  'max:50|required',
            // 'suite_number'  =>  'max:50|required'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            // 'book_names'    =>  'max:1000|required',
            // // 'book_count'    =>  'numeric|min:1|max:10|required',
            // 'country'       =>  'max:50|required',
            // 'state'         =>  'max:50|required',
            // 'city'          =>  'max:50|required',
            // 'zip_code'      =>  'max:10|required',
            // 'street_address' =>  'max:50|required',
            // 'suite_number'  =>  'max:50|required'
            // 'printer_company_id'    =>  'required',
            // 'courier_company_id'    =>  'required',
        ];
    }

    public function printer_company()
    {
        return $this->belongsTo('App\PrinterCompany');
    }

    public function courier_company()
    {
        return $this->belongsTo('App\CourierCompany');
    }


    public function lot()
    {
        return $this->belongsTo('App\Lot');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    //override save function
    public function _save(array $options = array())
    {
        parent::save($options);
        //-------------call webhook-------------
        GlobalConstants::changeBookStatus($this);
        //--------------------------------------
    }
}