<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistoryBalance;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class HistoryBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userAuth = auth()->user();

        $user = HistoryBalance::where([
            ['user_id', '=', $userAuth->id],
            ['received_service_id', 'like', '%' . $request->query('q') . '%']
        ])->with(['receiver', 'service'])->paginate(10); 

        return response($user, 200);
    }

    public function show($id)
    {
        $user = User::find($id);

        if($user) {
            $user = HistoryBalance::where([
                ['user_id', '=', $user->id]
            ])->with(['receiver', 'service'])->paginate(10); 
    
            return response($user, 200);
        }
        else {
            return response([
                'message' => 'User not found'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $userAuth = auth()->user();
        $admin = User::find(1);

        $history_balance = HistoryBalance::find($id);

        $service_price = Service::find($history_balance->service_id);

        $userAuth->balance += $service_price->price;
        $userAuth->save();

        $admin->balance -= $service_price->price;
        $admin->save();
        
        HistoryBalance::find($id)->delete();
        HistoryBalance::find($id - 1)->delete();
        return response([
            'message' => 'History Deleted'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $history_balance_admin = HistoryBalance::find($id);
        $history_balance_user = HistoryBalance::find($id - 1);

        //HistoryBalance::create([
        //    'quantity' => $request->quantity
        //]);
        $admin = User::find($history_balance_admin->received_service_id);
        $service = Service::find($history_balance_admin->service_id);

        $history_balance_admin->update($input);
        $history_balance_user->update($input);

        $requestQuantity = $request->quantity;
        $historyBalanceQuantity = $history_balance_admin->quantity;

        $val = $historyBalanceQuantity - $requestQuantity;

        dd($val);

        $admin->balance = $service->price * $val;
        $admin->save(); 
    }
}
