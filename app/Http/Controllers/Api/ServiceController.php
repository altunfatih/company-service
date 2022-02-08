<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Service::where('id', '<>', 1)->get();
    }

    public function show($id)
    {
        $service = Service::find($id);

        if($service)
            return response($service, 200);
        else
            return response([
                'message' => 'User not found'
            ], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service = Service::create([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description
        ]);

        return response([
            'data' => $service,
            'message' => 'Service Created'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $service = Service::find($id);

        if($service) {
            $service->update($input);

            return response([
                'data' => $service,
                'message' => 'service updated'
            ], 200);
        }
        else {
            return response([
                'message' => 'Service not found'
            ], 404);
        }
    }

    public function destroy($id) 
    {
        $service = Service::find($id);

        $service->delete();
    }
}
