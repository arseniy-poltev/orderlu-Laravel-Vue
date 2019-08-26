<?php

namespace App\Http\Controllers\Api\V1;

use App\PrinterCompany;
use App\Http\Controllers\Controller;
use App\Http\Resources\PrinterCompany as PrinterCompanyResource;
use App\Http\Requests\Admin\StorePrinterCompaniesRequest;
use App\Http\Requests\Admin\UpdatePrinterCompaniesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\User;
use App\GlobalConstants;

class PrinterCompaniesController extends Controller
{
    public function index()
    {
        return new PrinterCompanyResource(PrinterCompany::with([])->get());
    }

    public function show($id)
    {
        if (Gate::denies('printer_company_view')) {
            return abort(401);
        }

        $printer_company = PrinterCompany::with(['users'])->findOrFail($id);

        return new PrinterCompanyResource($printer_company);
    }

    private function saveImage($image)
    {
        $ext = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        $ext = explode('+', $ext)[0];
        $name = time() . '.' . $ext;
        $url = public_path(GlobalConstants::PRINTER_COMPANY_LOGO_PATH) . $name;


        $header = substr($image, 0, strpos($image, ',') + 1);
        $image = str_replace($header, '', $image);
        $image = str_replace(' ', '+', $image);

        file_put_contents($url, base64_decode($image));
        return GlobalConstants::PRINTER_COMPANY_LOGO_PATH . $name;
    }

    public function store(StorePrinterCompaniesRequest $request)
    {
        if (Gate::denies('printer_company_create')) {
            return abort(401);
        }

        if ($request->get('logo')) {
            $request->merge(['logo_url' => $this->saveImage($request->get('logo'))]);
        }

        $printer_company = PrinterCompany::create($request->all());


        $userIDs = $request->input('users', []);
        foreach ($userIDs as $userID) {
            $user = User::findOrFail($userID);
            if ($user) {
                $user->printer_company_id = $printer_company->id;
                $user->save();
            }
        }

        return (new PrinterCompanyResource($printer_company))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdatePrinterCompaniesRequest $request, $id)
    {
        if (Gate::denies('printer_company_edit')) {
            return abort(401);
        }

        $printer_company = PrinterCompany::findOrFail($id);

        if ($request->get('logo')) {
            if (!$request->get('logo_url') || $request->logo != $request->logo_url) {
                $request->merge(['logo_url' => $this->saveImage($request->get('logo'))]);
            }
        } else {
            //delete file
            $request->merge(['logo_url' => null]);
        }

        $users = $printer_company->users;
        foreach ($users as $user) {
            $user->printer_company_id = null;
            $user->save();
        }

        $userIDs = $request->input('users', []);
        foreach ($userIDs as $userID) {
            $user = User::findOrFail($userID);
            if ($user) {
                $user->printer_company_id = $id;
                $user->save();
            }
        }


        $printer_company->update($request->all());




        return (new PrinterCompanyResource($printer_company))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('printer_company_delete')) {
            return abort(401);
        }

        $printer_company = PrinterCompany::findOrFail($id);
        $printer_company->delete();

        return response(null, 204);
    }
}