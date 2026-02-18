<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Tank;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display stock management dashboard
     */
    public function index()
    {
        $categories = Category::active()->withCount('items')->orderBy('name')->get();
        $items = Item::with('category')->where('status', 'active')->get();
        
        return view('stock.index', compact('categories', 'items'));
    }

    /**
     * Display stock for a specific category
     */
    public function category($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $items = Item::with(['category', 'tanks' => function($query) {
                $query->where('status', 'active');
            }])
            ->where('category_id', $categoryId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Update current_stock for each tank from latest stock log
        foreach ($items as $item) {
            foreach ($item->tanks as $tank) {
                $latestLog = StockLog::where('tank_id', $tank->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($latestLog) {
                    $tank->current_stock = $latestLog->after_quantity;
                } else {
                    $tank->current_stock = 0;
                }
            }
        }
        
        $categories = Category::active()->withCount('items')->orderBy('name')->get();
        
        return view('stock.category', compact('category', 'items', 'categories'));
    }

    /**
     * Show form to reduce stock for an item
     */
    public function showReduceForm($itemId)
    {
        $item = Item::with('category')->findOrFail($itemId);
        
        return view('stock.reduce', compact('item'));
    }

    /**
     * Process stock reduction
     */
    public function reduceStock(Request $request, $itemId)
    {
        $request->validate([
            'reduce_quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($itemId);
        $reduceQuantity = $request->reduce_quantity;

        // Check if sufficient stock
        if ($item->stock_quantity < $reduceQuantity) {
            return redirect()->back()
                ->with('error', 'Insufficient stock. Available: ' . number_format($item->stock_quantity, 2));
        }

        DB::transaction(function () use ($item, $reduceQuantity, $request) {
            $beforeQuantity = $item->stock_quantity;
            $afterQuantity = $beforeQuantity - $reduceQuantity;

            // Update item stock
            $item->stock_quantity = $afterQuantity;
            $item->save();

            // Create stock log
            StockLog::create([
                'item_id' => $item->id,
                'type' => 'decrease',
                'before_quantity' => $beforeQuantity,
                'quantity_change' => $reduceQuantity,
                'after_quantity' => $afterQuantity,
                'reason' => $request->reason ?? 'Manual reduction',
                'reference_type' => 'manual',
                'reference_id' => null,
                'user_id' => auth()->id(),
            ]);
        });

        return redirect()->route('stock.category', $item->category_id)
            ->with('success', 'Stock reduced successfully.');
    }

    /**
     * Show stock logs for an item
     */
    public function logs($itemId)
    {
        $item = Item::with('category')->findOrFail($itemId);
        $logs = StockLog::with(['user', 'tank'])
            ->where('item_id', $itemId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('stock.logs', compact('item', 'logs'));
    }

    /**
     * Delete a stock log
     */
    public function deleteLog($logId)
    {
        $log = StockLog::findOrFail($logId);
        $itemId = $log->item_id;
        $item = Item::findOrFail($itemId);
        
        // Reverse the stock change when deleting the log
        if ($log->type === 'decrease') {
            // If it was a decrease, add the quantity back
            $item->stock_quantity += $log->quantity_change;
        } else {
            // If it was an increase, subtract the quantity
            $item->stock_quantity -= $log->quantity_change;
        }
        
        $item->save();
        $log->delete();
        
        return redirect()->route('stock.logs', $itemId)
            ->with('success', 'Stock log deleted and stock quantity adjusted successfully.');
    }
}
