<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function show(Request $request, $id)
    {
        $order = Order::find($id);
        if ($order === null)
            return $this->myresponse(false, 'There is no order with this id');

        $customer = User::find($order->user_id);

        if ($customer === null || $customer->type == 0)
            return $this->myresponse(false, 'There is no customer with this id');

        Travel::create([
            'price' => $request->price,
            'user_id' => $order->user_id,
            'driver_id' => $order->driver_id,
            'start' => $order->start,
            'destination' => $order->destination,
        ]);
        $response = [
            'id' => $customer->id,
            'name' => $customer->name,
            'phone_number' => $customer->phone_number,
            'start' => $order->start,
            'destination' => $order->destination,
        ];
        Order::destroy($id);
        return $this->myresponse(true, 'Driver Information', $response);
    }

    public function getNearlyCars()
    {
        $me = User::find(auth()->user()->id);
        /*
            join query to get cars name and its driver,
            drivers near to cleint and free at the current moment,
            compare location between driver and customer as strings
        */
        $cars = DB::table('carowners')
            ->Join('users', 'carowners.driver_id', '=', 'users.id')
            ->where('users.type', 0)
            ->where('carowners.free', 1)
            ->where('carowners.location', 'like', '%' . $me->location . '%')
            ->select('carowners.driver_id', 'carowners.model')
            ->get();
        return $this->myresponse(true, 'Near Cars for you', $cars);
    }

    public function orderTaxi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'required|string|min:3|max:40',
            'destination' => 'required|string|min:3|max:40',
            'driver_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return self::myresponse(false, $validator->errors()->first());
        } else {

            $driver = User::find($request->driver_id);
            if ($driver->type != 0)
                return $this->myresponse(false, 'User with this id is not a driver');

            $order = Order::create([
                'user_id' => auth()->user()->id,
                'driver_id' => $driver->id,
                'start' => $request->start,
                'destination' => $request->destination,
            ]);
            return $this->myresponse(true, 'Ordering Done!', $order);
        }
    }
}
