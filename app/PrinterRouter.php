<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrinterRouter extends Model
{
    //
    protected $fillable = ['continent', 'country', 'region'];


    public static function storeValidation($request)
    {
        return [
            'continent'             => 'max:20|required',
            'country'               => 'max:20',
            'region'                => 'max:20',
            'printer_companies'     => 'required'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'continent'             => 'max:20|required',
            'country'               => 'max:20',
            'region'                => 'max:20',
            'printer_companies'     => 'required'
        ];
    }



    public function printer_companies()
    {
        return $this->belongsToMany('App\PrinterCompany')
            ->using('App\PrinterRouterRelation')
            ->withPivot(['id'])
            ->select('percent', 'order_cnt', 'name', 'logo_url');
    }

    //delete function
    public function delete()
    {
        $this->printer_companies()->detach();
        return parent::delete();
    }
}