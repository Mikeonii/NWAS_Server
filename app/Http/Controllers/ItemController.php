<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ImportBatch;
use App\Models\Payable;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ImportBatchController;
class ItemController extends Controller
{
    public function index(){
        return Item::with('warranty')
        ->with('supplier')
        ->with('import_batch')
        ->orderBy('item_name','ASC')->get();
    }

    public  function add_stock(Request $request){
        
        $item = Item::where('id',$request->input('item_id'))->first();
        $item->quantity+=$request->input('quantity');
        // add associated units to batch imports
        ImportBatchController::modify_associated_units('add',$request->quantity,$item->import_batch_id);
        try{
            InventoryController::store($request);
            $item->save();
            $item->load('warranty', 'supplier','import_batch');
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
        $new->item_name = strtoupper($request->input('item_name'));
        $new->item_type = strtoupper($request->input('item_type'));
        $new->unit_price = $request->input('unit_price');
        $new->selling_price = $request->input('selling_price');
        $new->profitable_margin = $request->input('profitable_margin');
        $new->import_batch_id = $request->import_batch_id;
        if($request->isMethod('post')){
            $new->quantity = 0;
            $new->unit = "PCS";
        }
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
            $new->load(['warranty','supplier','import_batch']);
            // $new = Item::where('id',$new->id)->with('warranty')->with('supplier')->first();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function print_batch_import($batch_import_id){
        $import_batch = ImportBatch::findOrFail($batch_import_id);
        $items = Item::where('import_batch_id',$batch_import_id)
        ->with('supplier')
        ->with('import_batch')
        ->get();
        return view('print_batch_import')
        ->with('items',$items)
        ->with('import_batch',$import_batch);
    }
    public function print_item_sales($fromDate, $toDate, $batch_import_id, $radioSelection)
    {
        
        if($radioSelection == 'items'){
            $itemSales = Payable::where('payable_type', 'App\Models\Item')
            ->whereHas('invoice', function ($query) {
                $query->where('invoice_status', 'Paid');
            })
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('id', 'DESC')
            ->with('invoice.customer', 'item.import_batch')
            ->when($batch_import_id != 'none', function ($query) use ($batch_import_id) {
                $query->whereHas('item', function ($query) use ($batch_import_id) {
                    $query->where('import_batch_id', $batch_import_id);
                });
            })
            
            ->get();

            return view('print_sales_summary')
            ->with('fromDate',$fromDate)
            ->with('toDate',$toDate)
            ->with('itemSales',$itemSales);


        }
        else if($radioSelection =='services'){
        
            $serviceSales = Payable::where('payable_type', 'App\Models\Service')
            ->whereHas('invoice', function ($query) {
                $query->where('invoice_status', 'Paid');
            })
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('id', 'DESC')
            ->with('invoice.customer', 'service.supplier')
            ->get();

            // return $serviceSales;
            return view('print_services_summary')
            ->with('serviceSales',$serviceSales)
            ->with('fromDate',$fromDate)
            ->with('toDate',$toDate);
        }
        else{
            $allSales = Payable::whereHas('invoice', function ($query) {
                $query->where('invoice_status', 'Paid');
            })
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('id', 'DESC')
            ->with('invoice.customer')
            ->when(function ($query) {
                $query->where('payable_type', 'App\Models\Service');
            }, function ($query) {
                $query->with(['service'])->without('item');
            })
            ->when(function ($query) {
                $query->where('payable_type', 'App\Models\Item');
            }, function ($query) {
                $query->with(['item.import_batch'])->without('service');
            })
            ->get();
        
            $allSales->map(function($item){
                if($item->payable_type == 'App\Models\Service'){
                    $item->unset('item');
                }
                else{
                    $item->unset('service');
                }
            });
        return $allSales;
        
        
        
        
            return view('print_all_summary')->with('allSales',$allSales);
        }
       
        
     
    }
    
}
