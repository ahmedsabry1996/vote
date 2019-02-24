<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voter as voter;
use App\Vote as vote;
use App\VoteResult as voteresult;
class VoteController extends Controller
{
      public function store(Request $request)
      {

        $request->validate([
          'question'=>'bail|required|string',
          'vote_type'=>'required|numeric|between:1,3',
          'date'=>'bail|required|before_or_equal:today|date',
          'time'=>'nullable|date_format:H:i',
        ]);

        $question = $request->question;
        $date = $request->date;
        $time = $request->time;
        $vote_type = $request->vote_type;
        $stop_at = $date . " " . $time . ":00";

        $new_vote = vote::create([
          'question'=>$question,
          'vote_type'=>$vote_type,
          'stop_at'=>$stop_at,
        ]);

        $vote_id = $new_vote->id;

        $curren_vote = vote::findOrFail($vote_id);

        $curren_vote->voteresult()->create([
          'yes'=>0,
          'no'=>0,
          'abstain'=>0
        ]);

        return response()->json(['question'=>$question,
        'stop_at'=>$stop_at,'id'=>$vote_id],201);

      }
}
