<?php

namespace App\Http\Requests\Admin;

use App\PrinterRouter;
use Illuminate\Foundation\Http\FormRequest;

class StorePrinterRoutersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return PrinterRouter::storeValidation($this);
    }
}