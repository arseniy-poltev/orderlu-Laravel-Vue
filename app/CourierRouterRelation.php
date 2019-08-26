<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourierRouterRelation extends Pivot
{
    //
    protected $table = 'courier_company_courier_router';
    protected $hidden = ['created_at ', ' updated_at'];
}