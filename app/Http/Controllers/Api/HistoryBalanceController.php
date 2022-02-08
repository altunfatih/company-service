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
        $admin = User::find(1);

        $history_balance = HistoryBalance::find($id);
        $user = User::find($history_balance->received_service_id);

        $service_price = Service::find($history_balance->service_id);

        if($history_balance->money == 0) {
            $user->balance += ($service_price->price * $history_balance->quantity);
            $user->save();
    
            $admin->balance -= ($service_price->price * $history_balance->quantity);
            $admin->save();
        }
        else {
            $user->balance -= $history_balance->money;
            $user->save();
        }
        
        HistoryBalance::find($id)->delete();
        HistoryBalance::find($id + 1)->delete();
        return response([
            'message' => 'History Deleted'
        ], 200);
    }
}
