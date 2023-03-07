<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Exception;
class InventoryController extends Controller
{
    public function store(Request $request)
    {
        $new = $request->isMethod('put') ? Inventory::findOrFail($request->id) 
        : new Inventory;
        $new->fill($request->all());
        try {
            $new->save();
            return $new;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
