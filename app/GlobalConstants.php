<?php

namespace App;

use Khsing\World\World;
use Khsing\World\Models\Continent;
use Khsing\World\Models\Country;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;


class GlobalConstants
{
    const COURIER_COMPANY_LOGO_PATH     =   '/uploads/images/courier/';
    const PRINTER_COMPANY_LOGO_PATH     =   '/uploads/images/printer/';
    const ERR_MSG_DUPLICATE_LOC         =   'Duplicate Location!';
    const ERR_MSG_PERCENT_ZERO          =   'Percent Zero error!';
    const ERR_MSG_PERCENT_NOT_FULL      =   'Total percent must be 100!';

    const ORDER_STATUS_PAID             =   'paid';
    const ORDER_STATUS_PENDING          =   'pending';
    const ORDER_STATUS_ASSIGNED         =   'assigned';
    const ORDER_STATUS_PRINTING         =   'printing';
    const ORDER_STATUS_PRINTED          =   'printed';
    const ORDER_STATUS_FINISHED         =   'finished';
    const ORDER_STATUS_CHECKING         =   'checking';
    const ORDER_STATUS_CHECKED         =   'checked';

    const ORDER_STATUS_VIRTUALBOX       =   'virtual_box';
    const PREFIX_LOT                    =   'LOT-';
    const PREFIX_BOOK                   =   'BOOK-';
    const PREFIX_ORDER                  =   'ORD-';

    public static function getBookID($id)
    {
        if ($id == null) {
            return null;
        }
        return GlobalConstants::PREFIX_BOOK .
            str_pad($id, 10, "0", STR_PAD_LEFT);
    }

    public static function getOrderID($id)
    {
        if ($id == null) {
            return null;
        }
        return GlobalConstants::PREFIX_ORDER .
            str_pad($id, 6, "0", STR_PAD_LEFT);
    }


    public static function changeBookStatus($book)
    {
        $apiURL = env('MATERLU_PANEL_BOOK_STATUS_URL');
        self::webHook($apiURL, [
            'token'      =>  $book->book_code,
            'status'    =>  $book->status
        ]);
    }

    public static function changeOrderStatus($order)
    {
        $apiURL = env('MATERLU_PANEL_ORDER_STATUS_URL');
        self::webHook($apiURL, [
            'token'     =>  $order->order_code,
            'status'        =>  $order->status
        ]);
    }

    private static function webHook($apiURL, $params)
    {
        $client = new Client();

        try {
            $client->request('POST', $apiURL, [
                'form_params' => $params
            ]);
        } catch (ServerException $exception) {
            $responseBody = $exception->getResponse()->getBody(true);
            dd($exception);
        }
    }

    private static function decideCourierRouter($location)
    {
        /*
        location = country:code, state, city, zip_code ...
        */

        /*
            Step 1: Search whether city, state, country is in Routers table
        */
        //$out = new \Symfony\Component\Console\Output\ConsoleOutput();

        $country = Country::getByCode($location['country']);
        // $out->writeln($country->name);
        $routers = CourierRouter::where('printer_company_id', $location['printer_company_id'])
            ->where('country', $country->name)->get();
        if (count($routers) > 0) {
            $possibleRouter = null;
            foreach ($routers as $router) {
                if (
                    $router->region == $location['state']
                    || $router->region == $location['city']
                ) {
                    return $router;
                }
                if ($router->region == null) {
                    $possibleRouter = $router;
                }
            }
            if ($possibleRouter) {
                return $possibleRouter;
            }
        } else {
            /*
            Step 2: If there is none in Routers Table,
                then use Default Router for a continent!!!
            */
            //$country = Country::getByName($location['country']);
            if (!$country) {
                return null;
            }
            $continent = $country->parent();
            $router = CourierRouter::where('continent', $continent->name)
                ->where('country', null)
                ->first();
            if ($router) {
                return $router;
            }
        }
        return null;
    }
    private static function decidePrinterRouter($location)
    {
        /*
        location = country, state, city, zip_code ...
        */

        /*
            Step 1: Search whether city, state, country is in Routers table
        */

        $country = Country::getByCode($location['country']);
        $routers = PrinterRouter::where('country', $country->name)->get();

        if (count($routers) > 0) {
            $possibleRouter = null;
            foreach ($routers as $router) {

                if (
                    $router->region == $location['state']
                    || $router->region == $location['city']
                ) {
                    return $router;
                }
                if ($router->region == null) {
                    $possibleRouter = $router;
                }
            }
            if ($possibleRouter) {
                return $possibleRouter;
            }
        } else {
            /*
            Step 2: If there is none in Routers Table,
                then use Default Router for a continent!!!
            */
            if (!$country) {
                return null;
            }
            $continent = $country->parent();
            $router = PrinterRouter::where('continent', $continent->name)
                ->where('country', null)
                ->first();
            if ($router) {
                return $router;
            }
        }
        return null;
    }
    public static function decidePrinter($location)
    {
        $router = self::decidePrinterRouter($location);
        if (!$router) {
            return null;
        }

        $printers = $router->printer_companies()->get();
        $totalOrderCnt = 0;
        foreach ($printers as $printer) {
            $totalOrderCnt += $printer->order_cnt;
        }

        //find min value to adjust the percent
        $min = 10000;
        $result = null;
        foreach ($printers as $printer) {
            $x = $printer->order_cnt / ($totalOrderCnt + 1) * 100 - $printer->percent;
            if ($min > $x) {
                $min = $x;
                $result = $printer->pivot;
            }
        }
        return $result;
    }

    public static function decideCourier($location)
    {
        $router = self::decideCourierRouter($location);
        if (!$router) {
            return null;
        }

        $couriers = $router->courier_companies()->get();
        $totalOrderCnt = 0;
        foreach ($couriers as $courier) {
            $totalOrderCnt += $courier->order_cnt;
        }

        //find min value to adjust the percent
        $min = 10000;
        $result = null;
        foreach ($couriers as $courier) {
            $x = $courier->order_cnt / ($totalOrderCnt + 1) * 100 - $courier->percent;
            if ($min > $x) {
                $min = $x;
                $result = $courier->pivot;
            }
        }
        return $result;
    }
}