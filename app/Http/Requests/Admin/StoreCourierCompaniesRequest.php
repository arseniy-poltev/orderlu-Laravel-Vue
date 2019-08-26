<?php
namespace App\Http\Requests\Admin;

use App\CourierCompany;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourierCompaniesRequest extends FormRequest
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
        return CourierCompany::storeValidation($this);
    }
}
