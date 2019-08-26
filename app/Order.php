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
class Order extends Model
{
    //use SoftDeletes;


    protected $fillable = [
        'order_code', 'book_count',
        'country', 'state', 'city', 'zip_code', 'street_address', 'suite_number',
        'printer_company_id', 'courier_company_id', 'status', 'courier_tracking',
        'track_number', 'web_hook_url',
        'finished_at', 'printed_at', 'picked_at', 'delivered_at'
    ];


    public static function storeValidation($request)
    {
        return [
            // 'book_names'    =>  'max:1000|required',
            //'order_code'    =>  'max:50|require',
            // 'book_count'    =>  'numeric|min:1|max:10|required',
            'country'       =>  'max:50|required',
            'state'         =>  'max:50|required',
            'city'          =>  'max:50|required',
            'zip_code'      =>  'max:10|required',
            'street_address' =>  'max:50|required',
            'suite_number'  =>  'max:50|required',
            // 'web_hook_url'  =>  'required'
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
            'printer_company_id'    =>  'required',
            'courier_company_id'    =>  'required',
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

    public function books()
    {
        return $this->hasMany('App\Book');
    }

    //override save function
    public function _save(array $options = array())
    {
        parent::save($options);
        //-------------call webhook-------------
        GlobalConstants::changeOrderStatus($this);
        //--------------------------------------
    }
}