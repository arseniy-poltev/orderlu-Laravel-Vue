<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PrinterCompany
 *
 * @package App
 * @property string $name
 * @property string $full_address
 * @property string $img_logo
 */
class PrinterCompany extends Model
{
    //use SoftDeletes;  


    protected $fillable = ['name', 'country', 'state', 'city', 'street_address', 'zip_code', 'full_address', 'logo_url'];


    public static function storeValidation($request)
    {
        return [
            'name'          => 'max:191|required',
            'country'       => 'max:30|required',
            'state'         => 'max:30|required',
            'city'          => 'max:30|required',
            'zip_code'      => 'max:20|required',
            'street_address' => 'max:90|required',
            'full_address'  => 'max:191|required',
            'logo_url'      => 'max:191|nullable',
            'users'         => 'array',
            'users.*'       => 'integer|exists:users,id|max:4294967295'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'name'          => 'max:191|required',
            'country'       => 'max:30|required',
            'state'         => 'max:30|required',
            'city'          => 'max:30|required',
            'zip_code'      => 'max:20|required',
            'street_address' => 'max:90|required',
            'full_address'  => 'max:191|required',
            'logo_url'      => 'max:191|nullable',
            'users'         => 'array',
            'users.*'       => 'integer|exists:users,id|max:4294967295'
        ];
    }

    public function printer_routers()
    {
        return $this->belongsToMany('App\PrinterRouter')->using('App\PrinterRouterRelation');
    }

    public function courier_routers()
    {
        return $this->hasMany('App\CourierRouter');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function books()
    {
        return $this->hasMany('App\Book');
    }



    //delete function
    public function delete()
    {
        $this->printer_routers()->detach();
        return parent::delete();
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function virtual_boxes()
    {
        return $this->hasMany('App\VirtualBox');
    }

    public function lots()
    {
        return $this->hasMany('App\Lot');
    }
}