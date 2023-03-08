<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Controllers\InventoryController;
class ItemController extends Controller
{
    public function index(){
        return Item::with('warranty')->with('supplier')->orderBy('item_name','ASC')->get();
    }

    public  function add_stock(Request $request){
        
        $item = Item::where('id',$request->input('item_id'))->first();
        $item->quantity+=$request->input('quantity');
        try{
            InventoryController::store($request);
            $item->save();
            $item->load('warranty', 'supplier');
            return $item;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public static function modify_stock($request){
       
        $item = Item::findOrFail($request->get('item_id'));
        if($request->get('action') == 'add') $item->quantity+=$request->get('quantity');
        else $item->quantity-=$request->get('quantity');
    
        try{
            $item->save();
            return $item;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function store(Request $request){
        $new = $request->isMethod('put') ? Item::findOrFail($request->id) : new Item;
        $new->supplier_id = $request->input('supplier_id');
        $new->item_name = $request->input('item_name');
        $new->item_type = $request->input('item_type');
        $new->unit_price = $request->input('unit_price');
        $new->selling_price = $request->input('selling_price');
        $new->profitable_margin = $request->input('profitable_margin');
        $new->date_received = $request->input('date_received');
        try{
            $new->save();
            if($request->isMethod('put')){
                $new->warranty()->update([
                    "warranty_count"=>$request->input('warranty.warranty_count'),
                    "warranty_duration"=>$request->input('warranty.warranty_duration')
                ]);
            }
            else{
                $new->warranty()->create([
                    "warranty_count"=>$request->input('warranty_count'),
                    "warranty_duration"=>$request->input('warranty_duration')
                ]);
            }
          
            $new = Item::where('id',$new->id)->with('warranty')->with('supplier')->first();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
