<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function list()
    {
        // return only active stores
        $stores = Store::where('status','active')->get(['id','store_name']);
        return response()->json($stores);
    }
}
