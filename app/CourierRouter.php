<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourierRouter extends Model
{
    //
    protected $fillable = ['continent', 'country', 'region', 'printer_company_id'];


    public static function storeValidation($request)
    {
        return [
            'continent'             => 'max:20|required',
            'country'               => 'max:20',
            'region'                => 'max:20',
            'courier_companies'     => 'required',
            'printer_company_id'    =>  'required'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'continent'             => 'max:20|required',
            'country'               => 'max:20',
            'region'                => 'max:20',
            'courier_companies'     => 'required',
            'printer_company_id'    =>  'required'
        ];
    }



    public function courier_companies()
    {
        return $this->belongsToMany('App\CourierCompany')
            ->using('App\CourierRouterRelation')
            ->withPivot(['id'])
            ->select('percent', 'order_cnt', 'name', 'logo_url');
    }

    public function printer_company()
    {
        return $this->belongsTo('App\PrinterCompany');
    }

    //delete function
    public function delete()
    {
        $this->courier_companies()->detach();
        return parent::delete();
    }
}