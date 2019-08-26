<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PrinterRouterRelation extends Pivot
{
    //
    protected $table = 'printer_company_printer_router';
    protected $hidden = ['created_at', 'updated_at'];
}