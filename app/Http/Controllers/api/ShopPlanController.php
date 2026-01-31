<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ShopPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShopPlanController extends Controller
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
            'created_by' => 'required|integer|exists:users,id',
            'address' => 'required|string|max:255',
            'date_scheduled' => 'required|date',
            'budget' => 'required|numeric',
            'number_of_items' => 'required|integer'
        ]);

        $shopPlan = ShopPlan::create([
            'created_by' => $request->created_by,
            'address' => $request->address,
            'date_scheduled' => $request->date_scheduled,
            'budget' => $request->budget,
            'number_of_items' => $request->number_of_items
        ]);

        if ($shopPlan) {
            // call item creation here
            $shopPlan->items()->create([
                'name' => $request->item_name,
                'price' => $request->item_price,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shop plan created successfully',
                'data' => [
                    'id' => $shopPlan->id,
                    'address' => $shopPlan->address,
                    'date_scheduled' => $shopPlan->date_scheduled,
                    'budget' => $shopPlan->budget,
                    'number_of_items' => $shopPlan->number_of_items,
                    'status' => $shopPlan->status
                ]
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create shop plan',
                'data' => null
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
        // $shopPlan = ShopPlan::find($id, ['id', 'address', 'date_scheduled', 'budget', 'number_of_items', 'status']);
        $shopPlan = ShopPlan::query()
            ->select([
                'id',
                'address',
                'date_scheduled',
                'budget',
                'number_of_items',
                'status',
            ])
            ->addSelect([
                'in_progress' => ShopPlan::query()
                    ->selectRaw('CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END')
                    ->whereColumn('created_by', 'shop_plans.created_by')
                    ->where('status', 1)
                    ->whereColumn('id', '!=', 'shop_plans.id'),
            ])
            ->where('id', $id)
            ->first();

        if ($shopPlan) {
            return response()->json([
                'success' => true,
                'message' => 'Shop plan retrieved successfully',
                'data' => $shopPlan
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Shop plan not found',
                'data' => null
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|numeric|in:1,2'
        ]);

        $shopPlan = ShopPlan::find($id);

        if ($shopPlan) {
            if ($request->status === 2) {
                $shopPlan->update([
                    'status' => $request->status,
                    'budget' => $request->budget ?? $shopPlan->budget
                ]);
            } else {
                $inProgress = ShopPlan::where('created_by', $shopPlan->created_by)
                    ->where('status', 1)
                    ->first();

                if ($inProgress) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Another shop plan is already in progress',
                        'data' => null
                    ], 400);
                } else {
                    $shopPlan->update([
                        'status' => $request->status
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Shop plan status updated successfully',
                'data' => [
                    'id' => $shopPlan->id,
                    'status' => $shopPlan->status
                ]
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Shop plan not found',
                'data' => null
            ], 404);
        }
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

    public function getShopPlansByUser($userId)
    {
        // Update overdue shop plans and forgotten in-progress shop plans
        ShopPlan::where('created_by', $userId)
            ->whereDate('date_scheduled', '<', Carbon::today())
            ->whereIn('status', [0, 1])
            ->update(['status' => 3]);

        $shopPlans = ShopPlan::select('id', 'address', 'date_scheduled', 'status')->where('created_by', $userId)->get();

        return response()->json([
            'success' => true,
            'message' => 'Shop plans retrieved successfully',
            'data' => $shopPlans
        ], 200);
    }
}
