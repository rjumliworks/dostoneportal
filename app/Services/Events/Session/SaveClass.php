<?php

namespace App\Services\Events\Session;

use App\Models\EventSession;
use App\Models\EventSessionSchedule;
use App\Models\EventSessionManager;
use App\Models\EventSessionActivity;
use App\Models\EventSessionActivityBreakdown;

class SaveClass
{
    public function activity($request){
        if($request->has_breakdown){
            $data = EventSessionActivity::create($request->all());
            if($data){
                foreach($request->breakdowns as $b){
                    $breakdown = new EventSessionActivityBreakdown;
                    $breakdown->start_time = $b['start_time'];
                    $breakdown->end_time = $b['end_time'];
                    $breakdown->activity = $b['activity'];
                    $breakdown->speaker_id = $b['speaker_id'];
                    $breakdown->activity_id = $data->id;
                    $breakdown->save();
                }
            }
        }else{
            $speaker_id = $request->speaker['value'];
            $data = EventSessionActivity::create(array_merge($request->all(),[
                'speaker_id' => $speaker_id
            ]));
        }
        return [
            'data' => $data,
            'message' => 'Activity successfully created.', 
            'info' => "Great job! Your activity is now active and ready for participants."
        ];
    }

    public function session($request){
        $session = EventSession::create(array_merge($request->all(),[
            'code' => $this->generateCode()
        ]));
        if($session){
            $session->detail()->create($request->all());
            // $request->except(['title','is_closed','is_whole_day','is_invitational','is_exclusive','has_registration','venue_id','event_id','option']);
            foreach($request->dates as $date){
                if($date['timeOfDay'] == 'AM'){
                    $start = '08:00:00';
                    $end = '12:00:00';
                }else if($date['timeOfDay'] == 'PM'){
                    $start = '13:00:00';
                    $end = '17:00:00';
                }else if($date['timeOfDay'] == 'Whole Day'){
                    $start = '08:00:00';
                    $end = '17:00:00';
                }else{
                    $start = $date['start_time'];
                    $end = $date['start_time'];
                }
                $schedule = new EventSessionSchedule;
                $schedule->date = $date['date'];
                $schedule->time_of_day = $date['timeOfDay'];
                $schedule->start_time = $start;
                $schedule->end_time = $end;
                $schedule->session_id = $session->id;
                $schedule->save();
            }

            foreach($request->managers as $m){
                $manager = new EventSessionManager;
                $manager->type = $m['type'];
                $manager->user_id = $m['value'];
                $manager->session_id = $session->id;
                $manager->save();
            }
        }
        return [
            'data' => $session,
            'message' => 'Event successfully created.', 
            'info' => "Great job! Your event is now active and ready for participants."
        ];
    }

    private function generateCode(){
        $count = EventSession::count();
        $code = 'SES-'.date('m').date('Y').'-DOSTIX-'.str_pad(($count+1), 4, '0', STR_PAD_LEFT);  //$tsr_count+ remove since it will reset
        return $code;
    }
}
