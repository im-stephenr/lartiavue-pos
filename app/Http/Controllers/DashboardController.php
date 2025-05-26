<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //months
        $months = array();
       
        $salesMonthly = array();
        for($i=1;$i<=12;$i++)
        {
            $months[] = date('F', mktime(0, 0, 0, $i, 10));
            $salesMonthly[date('F', mktime(0, 0, 0, $i, 10))] = Sale::select(DB::raw('COALESCE(SUM(total),0) as total'))
                                                                    ->whereRaw("strftime('%m', created_at)  = ?", [str_pad($i, 2, '0', STR_PAD_LEFT)]) // NOTE: Sqlite doesn't have MONTH(date), added str_pad since the result of strftime starts with 0
                                                                    ->whereRaw("strftime('%Y', created_at) = strftime('%Y', 'now')") // NOTE: Sqlite doesn't have YEAR(date)
                                                                    ->first();
        }

        // weekly
        $weeks = array("Sunday", "Monday","Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        $salesWeekly = array();
        for($x=0;$x<count($weeks);$x++)
        {
            $salesWeekly[$weeks[$x]] = Sale::select(DB::raw('COALESCE(SUM(total),0) as total'))
                                            ->whereRaw("strftime('%w', datetime(created_at))  = ?", [(string)$x]) // NOTE: Sqlite doesn't have WEEK(date), added (string) since the query expects single quotes ex: '3' for wednesday
                                            ->whereRaw("strftime('%Y', datetime(created_at)) = strftime('%Y', 'now')") // NOTE: Sqlite doesn't have YEAR(date)
                                            ->first();
        }

        // Sales History
        $salesHistory = Sale::latest()->get();

        // Product sold history
        $products = Product::latest()->orderBy('title', 'ASC')->get();
        $orders = Sale::get('orderDetails');
        $sold_history_data = array();
        foreach($products as $product)
        {
            $qty = 0;
            $sold_history_data[$product["title"]] = $qty; // set all products with qty 0
            // echo $product["title"]."<br />";
            foreach($orders as $order)
            {
                $list_of_order = json_decode($order["orderDetails"], true);
                for($i=0;$i<count($list_of_order);$i++)
                {
                    // If there's a match of product under orderDetails then add the qty to that product
                    if($list_of_order[$i]["title"] == $product["title"])
                    {
                        $qty = $qty + $list_of_order[$i]["qty"];
                        $sold_history_data[$product["title"]] = $qty;
                    }
                }
            }
        }

        if(!empty($sold_history_data)) ksort($sold_history_data);

        return Inertia::render("Dashboard", [
            'months' => $months,
            'salesMonthly' => $salesMonthly,
            'weeks' => $weeks,
            'salesWeekly' => $salesWeekly,
            'salesHistory' => $salesHistory,
            'productSoldHistory' => $sold_history_data
        ]);

    }

}
