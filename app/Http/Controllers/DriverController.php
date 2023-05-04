<?php

namespace App\Http\Controllers;

use App\Models\Carowner;
use App\Models\DriverStatus;
use App\Models\DriverTime;
use App\Models\Order;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function getOrders()
    {
        return $this->myresponse(true, 'Orders', Order::where('driver_id', auth()->user()->id)->get());
    }

    public function show($id)
    {
        $driver = User::find($id);
        if ($driver === null || $driver->type == 1)
            return $this->myresponse(false, 'There is no driver with this id');

        return $this->myresponse(true, 'Driver Information', $driver);
    }

    public function getFree()
    {
        $status = Carowner::where('driver_id', auth()->user()->id)->first();
        if ($status === null)
            return $this->myresponse(false, 'You don\'t have driver account');
        else
            return $this->myresponse(true, 'status', $status->free);
    }

    public function setFree(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'string|min:5',
            'model' => 'string|min:3|max:25',
            'key' => 'required|integer'
        ]);
        $free = 0;
        if ($request->key == 1)
            $free = 1;

        if ($validator->fails()) {
            return self::myresponse(false, $validator->errors()->first());
        } else {
            $status = Carowner::where('driver_id', auth()->user()->id)->first();
            if ($status === null) {
                if ($request->location) {
                    $status = Carowner::create([
                        'driver_id' => auth()->user()->id,
                        'location' => $request->location,
                        'model' => $request->model,
                        'free' => $free
                    ]);
                    return $this->myresponse(true, 'status', $status->free);
                } else
                    return self::myresponse(false, 'location field is required');
            } else {
                $status->free = $free;
                if ($request->location)
                    $status->location = $request->location;
                if ($request->model)
                    $status->model = $request->model;
                $status->save();
                return $this->myresponse(true, 'status', $status->free);
            }
        }
    }

    public function get_travels()
    {
        return $this->myresponse(
            true,
            'Travels',
            Travel::where('driver_id', auth()->user()->id)->get()
        );
    }

    public function delete_travel($id)
    {
        $travel = Travel::find($id);
        if ($travel === null)
            return $this->failed("There is no travel with this id");

        Travel::destroy($id);
        return $this->failed('Deleted Success!');
    }

    // public function setCar(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'model' => 'required|string|min:3|max:25',
    //     ]);

    //     if ($validator->fails()) {
    //         return self::myresponse(false, $validator->errors()->first());
    //     } else {

    //         $status = Carowner::where('driver_id', auth()->user()->id)->first();
    //         if ($status === null) {
    //             if ($request->location) {
    //                 $status = Carowner::create([
    //                     'driver_id' => auth()->user()->id,
    //                     'model' => $request->model,
    //                 ]);
    //                 return $this->myresponse(true, 'status', $status->free);
    //             } else
    //                 return self::myresponse(false, 'location field is required');
    //         } else {
    //             $status->model = $request->model;
    //             $status->save();
    //         }
    //         return self::myresponse(true, 'Setting car done!');
    //     }
    // }
}
