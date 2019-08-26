<?php

namespace App\Http\Controllers\Api\V1;

use App\CourierRouter;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourierRouter as CourierRouterResource;
use App\Http\Requests\Admin\StoreCourierRoutersRequest;
use App\Http\Requests\Admin\UpdateCourierRoutersRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\GlobalConstants;
use Khsing\World\World;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;
use Khsing\World\Models\City;
use Response;

class CourierRoutersController extends Controller
{
    public function index()
    {
        $courier_routers = CourierRouter::with(['courier_companies', 'printer_company'])
            ->orderBy('updated_at', 'asc')
            ->get();
        $result = [];
        foreach ($courier_routers as $router) {
            $item = $router->toArray();

            if ($router->country) {
                $country = Country::getByName($router->country);
                if ($country) {
                    $item['country_code'] = $country->code;
                }
            }
            if ($router->printer_company->country) {
                $country = Country::getByName($router->printer_company->country);
                if ($country) {
                    $item['printer_company']['country_code'] = $country->code;
                }
            }
            array_push($result, $item);
        }
        return new CourierRouterResource($result);
    }

    public function show($id)
    {
        if (Gate::denies('courier_router_view')) {
            return abort(401);
        }

        $courier_router = CourierRouter::with(['courier_companies', 'printer_company'])
            ->findOrFail($id);


        return new CourierRouterResource($courier_router);
    }
    private function setCourierCompanyForRouter($json)
    {
        //*************for courier companies********
        $courier_companies = json_decode($json);
        $data = array();
        $totalPercent = 0;
        foreach ($courier_companies as $courier_company) {
            if ($courier_company->percent == 0) {
                return [
                    'status'    =>  'error',
                    'message'   =>  GlobalConstants::ERR_MSG_PERCENT_ZERO
                ];
            }
            $totalPercent += $courier_company->percent;
            $data[$courier_company->id] = array('percent' => $courier_company->percent);
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
        $cnt = CourierRouter::where('continent', $request->continent)
            ->where('country', $request->country)
            ->where('region', $request->region)
            ->count();
        return $cnt == 0;
    }

    public function store(StoreCourierRoutersRequest $request)
    {
        if (Gate::denies('courier_router_create')) {
            return abort(401);
        }
        if (!isset($request->country)) {
            $request->merge(['country' => null]);
        }
        if (!isset($request->region)) {
            $request->merge(['region' => null]);
        }
        if (!isset($request->city)) {
            $request->merge(['city' => null]);
        }

        if (!$this->targetValidate($request)) {
            return Response::json(array(
                'code'      =>  422,
                'message'   =>  GlobalConstants::ERR_MSG_DUPLICATE_LOC
            ), 422);
        }

        $result = $this->setCourierCompanyForRouter($request->courier_companies);
        if ($result['status'] == 'error') {
            return Response::json(array(
                'code'      =>  422,
                'message'   =>  $result['message']
            ), 422);
        }

        $courier_router = CourierRouter::create($request->all());

        $courier_router->courier_companies()->sync($result['data']);

        //********************************

        return (new CourierRouterResource($courier_router))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateCourierRoutersRequest $request, $id)
    {
        if (Gate::denies('courier_router_edit')) {
            return abort(401);
        }

        if (!isset($request->country)) {
            $request->merge(['country' => null]);
        }
        if (!isset($request->region)) {
            $request->merge(['region' => null]);
        }
        if (!isset($request->city)) {
            $request->merge(['city' => null]);
        }

        $courier_router = CourierRouter::findOrFail($id);
        if (
            $courier_router->continent != $request->continent ||
            $courier_router->country != $request->country ||
            $courier_router->region != $request->region ||
            $courier_router->city != $request->city
        ) {
            if (!$this->targetValidate($request)) {
                return Response::json(array(
                    'code'      =>  422,
                    'message'   =>  GlobalConstants::ERR_MSG_DUPLICATE_LOC
                ), 422);
            }
        }

        $result = $this->setCourierCompanyForRouter($request->courier_companies);
        if ($result['status'] == 'error') {
            return Response::json(array(
                'code'      =>  422,
                'message'   =>  $result['message']
            ), 422);
        }

        $courier_router->update($request->all());

        $courier_router->courier_companies()->sync($result['data']);

        //********************************







        return (new CourierRouterResource($courier_router))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy($id)
    {
        if (Gate::denies('courier_router_delete')) {
            return abort(401);
        }

        $courier_router = CourierRouter::findOrFail($id);
        $courier_router->delete();

        return response(null, 204);
    }
}