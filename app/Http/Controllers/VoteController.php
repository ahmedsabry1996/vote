<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voter as voter;
use App\Vote as vote;
use App\VoteResult as voteresult;
use Carbon\Carbon ;
class VoteController extends Controller
{
      public function store(Request $request)
      {

        $request->validate([
          'question'=>'bail|required|string',
          'vote_type'=>'required|numeric|between:1,3',
          'date'=>'bail|required|after:today|date',
        ]);

        $question = $request->question;
        $date = $request->date;
        $time = $request->time;
        $vote_type = $request->vote_type;
        $stop_at = $date ;

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

        $num_of_votes = vote::count();
        return response()->json([
        'question'=>$question,
        'stop_at'=>$stop_at,
        'all_votes'=>$num_of_votes,

        'id'=>$vote_id],201);

      }


      public function show_vote(Request $request)
      {
          $vote_id = $request->vote_id;

          $voter_ip = $request->ips();
          $curren_vote = vote::findOrFail($vote_id);
          $curren_vote_results = $curren_vote->voteresult;
          $vote_ended = $this->is_finished($curren_vote->stop_at);
          $is_voted_before = voter::where('voter_ip',$voter_ip)
                            ->where('vote_id',$vote_id)->exists();

          if ($is_voted_before) {
              $is_voted_before = voter::where('voter_ip',$voter_ip)
                                ->where('vote_id',$vote_id)->first()->answer;
          }
          else{
            $is_voted_before = null;
          }
          return response()->json(['vote'=>$curren_vote,
                                    'vote_ended'=>$vote_ended,
                                    'vote_results'=>$curren_vote_results,
                                  'is_voted_before'=>$is_voted_before],201);
      }


    public function vote(Request $request)
    {
        $vote_id = $request->vote_id;
        $curren_vote = vote::findOrFail($vote_id);
        $vote = $request->vote;
        $voter_ip = $request->ip();

        $vote_still_available = $this->is_finished($curren_vote->stop_at);


          if (!$vote_still_available) {

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
      }

        return response()->json([
                                  'is'=>$not_voted_before,
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


    public function latest_votes(Request $request)
    {
        $num_of_votes = vote::count();

        if ($num_of_votes > 0) {

            $offset = $request->has('offset') ? $request->offset : 0 ;

            $latest_votes = vote::latest()
            ->offset($offset)
            ->limit(5)
            ->get();

        }

      return response()->json(['all_votes'=>$num_of_votes,
                              'latest_votes'=>$latest_votes]);
    }

    public function is_finished($stop_at)
    {
      if (now()->addHours(2) < $stop_at) {
          return false;
      }
      return true;
    }

}
