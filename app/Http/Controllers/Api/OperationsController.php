<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistoryBalance;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OperationsController extends Controller
{
    public function amountOperations(Request $request) {
        $service_name = $request->service_name;
        $user = $request->email;
        $user = User::where('email', $user)->first();
        $quantity = $request->quantity;

        if($quantity == 0)
        {
            return response([
                'message' => '0 adet giremezsiniz!'
            ], 500);
        }
            
        $service_name = Service::where('service_name', $service_name)->first();
        
        if(!$service_name)
            return response([
                'message' => 'Böyle bir hizmet bulunamadı!'
                ], 500);

        $price = $service_name->price * $quantity;

        $oldBalance = $user->balance;
        $newBalance = $user->balance;

        $newBalance -= $price;
        $user->balance = $newBalance;

        $admin = User::find(1);
        $admin->balance += $price;
        $admin->save();
        $user->save();
        
        $this->saveHistoryBalance($user->id, $service_name->id, $quantity, $oldBalance, $newBalance, $user->id);
        $this->saveHistoryBalance($user->id, $service_name->id, $quantity, $oldBalance, $newBalance, $admin->id);
    }

    public function saveHistoryBalance($received_service_id, $service_id, $quantity, $oldBalance, $newBalance, $user_id) {
        HistoryBalance::create([
            'received_service_id' => $received_service_id,
            'service_id' => $service_id,
            'quantity' => $quantity,
            'oldBalance' => $oldBalance,
            'newBalance' => $newBalance,
            'user_id' =>$user_id,
        ]);
    }
}
