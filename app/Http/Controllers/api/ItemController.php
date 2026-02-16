<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'shop_plan_id' => 'required|integer|exists:shop_plans,id',
            // 'server_id' => 'required|integer|exists:shop_plans,id',
            'name' => 'required|string|max:255',
            'expected_quantity' => 'required|integer',
        ]);

        $item = Item::create([
            'shop_plan_id' => $request->server_id,
            'name' => $request->name,
            'price' => 0,
            'expected_quantity' => $request->expected_quantity,
        ]);

        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Item created successfully',
                'data' => [
                    'id' => $item->id,
                    'shop_plan_id' => $item->shop_plan_id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'expected_quantity' => $item->expected_quantity,
                ]
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Item creation failed'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
