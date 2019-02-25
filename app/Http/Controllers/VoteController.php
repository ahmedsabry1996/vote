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
          'date'=>'bail|required|after:today|date',
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


        return response()->json([
        'question'=>$question,
        'stop_at'=>$stop_at,
        'id'=>$vote_id],201);

      }


      public function show_vote(Request $request)
      {
          $vote_id = $request->vote_id;

          $voter_ip = $request->ips();
          $curren_vote = vote::findOrFail($vote_id);

          $is_voted_before = voter::where('voter_ip',$voter_ip)
                            ->where('vote_id',$vote_id)->exists();

          if ($is_voted_before) {
              $is_voted_before = voter::where('voter_ip',$voter_ip)
                                ->where('vote_id',$vote_id)->first()->answer;
          }
          else{
            $is_voted_before = null;
          }
          return response()->json(['vote'=>$curren_vote,'is_voted_before'=>$is_voted_before],201);
      }


    public function vote(Request $request)
    {
        $vote_id = $request->vote_id;
        $curren_vote = vote::findOrFail($vote_id);
        $vote = $request->vote;
        $voter_ip = $request->ip();

        $not_voted_before = voter::where('voter_ip', $voter_ip)
                                  ->where('vote_id',$vote_id)
                                  ->doesntExist();

      if ($not_voted_before) {
          voter::create([
            'voter_ip'=>$voter_ip,
            'vote_id'=>$vote_id,
            'answer'=>$vote
          ]);
          if ($vote == 'yes') {
            $curren_vote->voteresult->increment('yes');

          }
          elseif ($vote == 'no') {
            $curren_vote->voteresult->increment('no');

          }
          else{
            $curren_vote->voteresult->increment('abstain');

          }
      }

      else{
        $old_vote = voter::where('voter_ip',$voter_ip)->where('vote_id',$vote_id)->first();

        $old_vote_id = $old_vote->id;

        $old_answer = $old_vote->answer;

        $curren_vote =  voter::find($old_vote_id);

        $curren_vote->answer = $vote;

        $curren_vote->save();

        $this->results($vote_id,$old_answer,$vote);

      }

        return response()->json(['is'=>$not_voted_before,
                                 'vote'=>$vote,
                                  'ip'=>$voter_ip],201);

    }

    public function results($vote_id,$old_vote,$new_vote)
    {
        $curren_vote =vote::findOrFail($vote_id);

        if ($old_vote == 'yes') {
          $curren_vote->voteresult->decrement('yes');
        }
        elseif ($old_vote == 'no') {
          $curren_vote->voteresult->decrement('no');
        }
        else {
          $curren_vote->voteresult->decrement('abstain');
        }
        $curren_vote->voteresult->save();

        if ($new_vote == 'yes') {
          $curren_vote->voteresult->increment('yes');
        }

        elseif ($new_vote == 'no') {
          $curren_vote->voteresult->increment('no');
        }
        else{
          $curren_vote->voteresult->increment('abstain');
        }

        $curren_vote->voteresult->save();

    }

}
