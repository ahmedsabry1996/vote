
<?php

Route::post('/new-vote','VoteController@store');

Route::post('/show-vote','VoteController@show_vote');

Route::post('/vote','VoteController@vote');

Route::post('/latest-votes','VoteController@latest_votes');
