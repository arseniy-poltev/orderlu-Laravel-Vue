<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CourierCompany
 *
 * @package App
 * @property string $name
 * @property string $postmen_id
 * @property string $img_logo
 */
class CourierCompany extends Model
{
    //use SoftDeletes;


    protected $fillable = ['name', 'postmen_id', 'logo_url'];


    public static function storeValidation($request)
    {
        return [
            'name' => 'max:191|required',
            'postmen_id' => 'max:191|required',
            'logo_url' => 'max:191|nullable'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'name' => 'max:191|required',
            'postmen_id' => 'max:191|required',
            'logo_url' => 'max:191|nullable'
        ];
    }

    public function courier_routers()
    {
        return $this->belongsToMany('App\CourierRouter')->using('App\CourierRouterRelation');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }


    //delete function
    public function delete()
    {
        $this->courier_routers()->detach();
        return parent::delete();
    }
}