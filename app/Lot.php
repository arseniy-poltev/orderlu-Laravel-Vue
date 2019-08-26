<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Lot
 *
 * @package App
 * @property integer $number_books
 * @property integer $number_printed
 * @property dateTime $finished_at
 */
class Lot extends Model
{
    //use SoftDeletes;


    protected $fillable = ['number_books', 'finished_at', 'lot_number', 'printer_company_id'];


    public function books()
    {
        return $this->hasMany('App\Book');
    }

    public function printer_company()
    {
        return $this->belongsTo('App\PrinterCompany');
    }
}