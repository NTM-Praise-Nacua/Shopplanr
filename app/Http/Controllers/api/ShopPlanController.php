<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ShopPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
            'number_of_items' => 'required|integer',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.expected_quantity' => 'required|numeric|min:0'
        ]);

        $dateScheduled = Carbon::parse($request->date_scheduled);

        $status = 0;
        if ($dateScheduled->lt(today())) {
            $status = 3;
        }

        $shopPlan = ShopPlan::create([
            'created_by' => $request->created_by,
            'address' => $request->address,
            'date_scheduled' => $request->date_scheduled,
            'budget' => $request->budget,
            'number_of_items' => $request->number_of_items,
            'status' => $status
        ]);

        if ($shopPlan) {
            // call item creation here
            $itemsData = collect($request->items)->map(function ($item) use ($shopPlan) {
                return [
                    'shop_plan_id' => $shopPlan->id,
                    'name' => $item['name'],
                    'expected_quantity' => $item['expected_quantity'],
                    'price' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();
            
            $shopPlan->items()->insert($itemsData);

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
        $shopPlan = ShopPlan::with([
                'items:id,shop_plan_id,name,price,expected_quantity,actual_quantity'
            ])
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
            ->findOrFail($id);

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
            'status' => 'required|numeric|in:1,2',
            'items' => 'required_if:status,2|array',
            'items.*.name' => [
                'required_if:status,2',
                'string',
                'distinct',
                Rule::exists('items', 'name')->where('shop_plan_id', $id),
            ],
            'items.*.price' => 'required_if:status,2|numeric|min:0',
            'items.*.actual_quantity' => 'required_if:status,2|numeric|min:0',
        ]);

        $shopPlan = ShopPlan::find($id);

        if ($shopPlan) {
            if ($request->status === 2) {
                $items = [];
                DB::transaction(function () use ($request, $shopPlan) {
                    $itemsById = $shopPlan->items->keyBy('name');

                    $totalSpent = 0;

                    foreach ($request->items as $itemData) {
                        $item = $itemsById[$itemData['name']] ?? null;
                        if ($item) {
                            $item->update([
                                'price' => $itemData['price'],
                                'actual_quantity' => $itemData['actual_quantity'],
                            ]);
                            $totalSpent += $itemData['price'] * $itemData['actual_quantity'];
                        }
                        $items[] = $item;
                    }

                    $shopPlan->update([
                        'status' => 2,
                        'budget' => $shopPlan->budget - $totalSpent,
                    ]);
                });
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
                    'status' => $shopPlan->status,
                    'items' => $items,
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

        $shopPlans = ShopPlan::select('id', 'address', 'date_scheduled', 'budget', 'number_of_items', 'status')->where('created_by', $userId)->get();

        return response()->json([
            'success' => true,
            'message' => 'Shop plans retrieved successfully',
            'data' => $shopPlans
        ], 200);
    }

    public function updateOverdue($userId)
    {
        // Update overdue shop plans and forgotten in-progress shop plans
        $shopPlans = ShopPlan::where('created_by', $userId)
            ->whereDate('date_scheduled', '<', Carbon::today())
            ->whereIn('status', [0, 1])
            ->update(['status' => 3]);

        if ($shopPlans > 0) {
            return response()->json([
                'success' => true,
                'message' => 'Updated Overdue Plans',
                'data' => true
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'No overdue Plans found',
                'data' => false
            ], 200);
        }
    }

    public function startPlan($id)
    {
        $shopPlan = ShopPlan::find($id);

        if ($shopPlan) {
            $shopPlan->status = 1;
            $shopPlan->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Shop plans started successfully',
                'data' => true,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Shop plan not found',
                'data' => false
            ], 404);
        }
    }
}
