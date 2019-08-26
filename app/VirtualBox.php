<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VirtualBox extends Model
{
    //
    protected $fillable = ['order_id', 'printer_company_id', 'number'];
    public function printer_company()
    {
        return $this->belongsTo('App\PrinterCompany');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}