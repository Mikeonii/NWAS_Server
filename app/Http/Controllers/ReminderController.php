<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reminder;
use Exception;
class ReminderController extends Controller
{
    //
    public function index($is_admin){
        if($is_admin) return Reminder::all();
        else return Reminder::where('reminder_type','!=','Admin Reminder')->get();
    }
    public function delete($id){
        return Reminder::findOrFail($id)->delete();
    }
    public function store(Request $request){
        $new = $request->isMethod('put') ? Reminder::findOrFail($request->id) : new Reminder;
        $new->reminder_type = $request->input('reminder_type');
        $new->reminder_description = $request->input('reminder_description');
        $new->level_of_urgency = $request->input('level_of_urgency');
        $new->reminder_date = $request->input('reminder_date');
        $new->reminder_time = $request->input('reminder_time');
        $new->is_finished = $request->isMethod('put') ? $request->input('is_finished') : 0;
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    private function get_date_range($date)
    {
        switch ($date) {
            case 'daily':
                $start = Carbon::today();
                $end = Carbon::tomorrow();
                break;
            case 'weekly':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            default:
                throw new Exception('Invalid date parameter.');
                break;
        }
        return [$start, $end];
    }
    
   
}
