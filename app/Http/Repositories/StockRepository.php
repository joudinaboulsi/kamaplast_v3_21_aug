<?php 

namespace App\Http\Repositories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class StockRepository {

    /*----------------------------------
    update the stock quantity in the database
    ------------------------------------*/
     public function updateStockValue($sku, $stock_quantity)
     {
        // update the stock quantity
            \DB::table('variants')
            ->where('sku', $sku)
            ->update(['stock_qty' => $stock_quantity]); 
     }



}
