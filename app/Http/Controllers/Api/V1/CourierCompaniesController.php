<?php

namespace App\Http\Controllers\Api\V1;

use App\CourierCompany;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourierCompany as CourierCompanyResource;
use App\Http\Requests\Admin\StoreCourierCompaniesRequest;
use App\Http\Requests\Admin\UpdateCourierCompaniesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\GlobalConstants;

class CourierCompaniesController extends Controller
{
    public function index()
    {
        return new CourierCompanyResource(CourierCompany::with([])->get());
    }

    public function show($id)
    {
        if (Gate::denies('courier_company_view')) {
            return abort(401);
        }

        $courier_company = CourierCompany::with([])->findOrFail($id);

        return new CourierCompanyResource($courier_company);
    }

    private function saveImage($image)
    {
        $ext = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        $ext = explode('+', $ext)[0];
        $name = time() . '.' . $ext;
        $url = public_path(GlobalConstants::COURIER_COMPANY_LOGO_PATH) . $name;


        $header = substr($image, 0, strpos($image, ',') + 1);
        $image = str_replace($header, '', $image);
        $image = str_replace(' ', '+', $image);

        file_put_contents($url, base64_decode($image));
        return GlobalConstants::COURIER_COMPANY_LOGO_PATH . $name;
    }

    public function store(StoreCourierCompaniesRequest $request)
    {
        if (Gate::denies('courier_company_create')) {
            return abort(401);
        }

        if ($request->get('logo')) {
            $request->merge(['logo_url' => $this->saveImage($request->get('logo'))]);
        }

        $courier_company = CourierCompany::create($request->all());



        return (new CourierCompanyResource($courier_company))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateCourierCompaniesRequest $request, $id)
    {
        if (Gate::denies('courier_company_edit')) {
            return abort(401);
        }

        $courier_company = CourierCompany::findOrFail($id);

        if ($request->get('logo')) {
            if (!$request->get('logo_url') || $request->logo != $request->logo_url) {
                $request->merge(['logo_url' => $this->saveImage($request->get('logo'))]);
            }
        } else {
            //delete file
            $request->merge(['logo_url' => null]);
        }

        $courier_company->update($request->all());




        return (new CourierCompanyResource($courier_company))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('courier_company_delete')) {
            return abort(401);
        }

        $courier_company = CourierCompany::findOrFail($id);
        $courier_company->delete();

        return response(null, 204);
    }
}