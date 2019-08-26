<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Khsing\World\World;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;

use Illuminate\Http\Resources\Json\JsonResource;

class WorldController extends Controller
{
    //
    public function getContinents()
    {
        $continents = World::Continents();
        $result = [];
        foreach ($continents as $continent) {
            array_push($result, $continent->name);
        }
        return new JsonResource($result);
    }

    public function getCountries($continent)
    {
        $continent = Continent::getByName($continent);
        $countries = $continent->countries()->select(['name'])->get();
        $result = [];
        foreach ($countries as $coutry) {
            array_push($result, $coutry->name);
        }
        return new JsonResource($result);
    }

    public function getRegions($country)
    {
        $country = Country::getByName($country);
        $regsions = $country->cities()->get();
        $result = [];
        foreach ($regsions as $region) {
            array_push($result, $region->name);
        }
        return new JsonResource($result);
    }
}