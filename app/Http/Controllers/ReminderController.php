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
    public function index(){
        return Reminder::all();
    }
    public function delete($id){
        return Reminder::delete($id);
    }
    public function store(Request $request){
        $new = $request->isMethod('put') ? Reminder::findOrFail($request->id) : new Reminder;
        $new->reminder_type = $request->input('reminder_type');
        $new->reminder_description = $request->input('reminder_description');
        $new->customer_id = $request->input('customer_id');
        $new->schedule_date = $request->input('schedule_date');
        $new->is_finished = 0;
        try{
            $new->save();
            return $new;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function get_reminders_by_type($is_admin, $date)
    {   
        $reminder_type = $is_admin ? 'Admin Reminder' : '!= Admin Reminder';
        $reminders = Reminder::where('reminder_type', $reminder_type)
        ->whereBetween('schedule_date', $this->get_date_range($date))
        ->get();
        return $reminders;
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
