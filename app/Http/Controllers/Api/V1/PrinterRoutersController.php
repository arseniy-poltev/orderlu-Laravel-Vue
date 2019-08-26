<?php

namespace App\Http\Controllers\Api\V1;

use App\PrinterRouter;
use App\Http\Controllers\Controller;
use App\Http\Resources\PrinterRouter as PrinterRouterResource;
use App\Http\Requests\Admin\StorePrinterRoutersRequest;
use App\Http\Requests\Admin\UpdatePrinterRoutersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\GlobalConstants;
use Khsing\World\World;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;
use Khsing\World\Models\City;
use Response;

class PrinterRoutersController extends Controller
{
    public function index()
    {
        $printer_routers = PrinterRouter::with(['printer_companies'])
            ->orderBy('updated_at', 'asc')
            ->get();
        $result = [];
        foreach ($printer_routers as $router) {
            $item = $router->toArray();

            if ($router->country) {
                $country = Country::getByName($router->country);
                $item['country_code'] = $country->code;
            }
            array_push($result, $item);
        }
        return new PrinterRouterResource($result);
    }

    public function show($id)
    {
        if (Gate::denies('printer_router_view')) {
            return abort(401);
        }

        $printer_router = PrinterRouter::with(['printer_companies'])->findOrFail($id);


        return new PrinterRouterResource($printer_router);
    }
    private function setPrinterCompanyForRouter($json)
    {
        //*************for Printer companies********
        $printer_companies = json_decode($json);
        $data = array();
        $totalPercent = 0;
        foreach ($printer_companies as $printer_company) {
            if ($printer_company->percent == 0) {
                return [
                    'status'    =>  'error',
                    'message'   =>  GlobalConstants::ERR_MSG_PERCENT_ZERO
                ];
            }
            $totalPercent += $printer_company->percent;
            $data[$printer_company->id] = array('percent' => $printer_company->percent);
        }
        if ($totalPercent != 100) {
            return [
                'status'    =>  'error',
                'message'   =>  GlobalConstants::ERR_MSG_PERCENT_NOT_FULL
            ];
        }
        return [
            'status'    =>  'success',
            'data'    =>  $data
        ];
    }

    private function targetValidate($request)
    {
        $cnt = PrinterRouter::where('continent', $request->continent)
            ->where('country', $request->country)
            ->where('region', $request->region)
            ->count();
        return $cnt == 0;
    }

    public function store(StorePrinterRoutersRequest $request)
    {
        if (Gate::denies('printer_router_create')) {
            return abort(401);
        }
        if (!isset($request->country)) {
            $request->merge(['country' => null]);
        }
        if (!isset($request->region)) {
            $request->merge(['region' => null]);
        }

        if (!$this->targetValidate($request)) {
            return Response::json(array(
                'code'      =>  422,
                'message'   =>  GlobalConstants::ERR_MSG_DUPLICATE_LOC
            ), 422);
        }

        $result = $this->setPrinterCompanyForRouter($request->printer_companies);
        if ($result['status'] == 'error') {
            return Response::json(array(
                'code'      =>  422,
                'message'   =>  $result['message']
            ), 422);
        }

        $printer_router = PrinterRouter::create($request->all());

        $printer_router->printer_companies()->sync($result['data']);

        //********************************

        return (new PrinterRouterResource($printer_router))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdatePrinterRoutersRequest $request, $id)
    {
        if (Gate::denies('printer_router_edit')) {
            return abort(401);
        }

        if (!isset($request->country)) {
            $request->merge(['country' => null]);
        }
        if (!isset($request->region)) {
            $request->merge(['region' => null]);
        }

        $printer_router = PrinterRouter::findOrFail($id);
        if (
            $printer_router->continent != $request->continent ||
            $printer_router->country != $request->country ||
            $printer_router->region != $request->region
        ) {
            if (!$this->targetValidate($request)) {
                return Response::json(array(
                    'code'      =>  422,
                    'message'   =>  GlobalConstants::ERR_MSG_DUPLICATE_LOC
                ), 422);
            }
        }

        $result = $this->setPrinterCompanyForRouter($request->printer_companies);
        if ($result['status'] == 'error') {
            return Response::json(array(
                'code'      =>  422,
                'message'   =>  $result['message']
            ), 422);
        }

        $printer_router->update($request->all());

        $printer_router->printer_companies()->sync($result['data']);

        //********************************

        return (new PrinterRouterResource($printer_router))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('printer_router_delete')) {
            return abort(401);
        }

        $printer_router = PrinterRouter::findOrFail($id);
        $printer_router->delete();

        return response(null, 204);
    }
}