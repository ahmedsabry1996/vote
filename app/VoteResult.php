<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteResult extends Model
{

  protected $guarded = [];

    public function vote()
    {
      return $this->belongsTo('App\Vote');
    }
}
