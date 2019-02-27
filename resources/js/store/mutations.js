export default {

  newVoteErrors(state,payload){
    state.newVoteErrors = payload;
  },
    truncateErrors(state){
      state.newVoteErrors = null;
    },

    assignVoteUrl(state,payload){
      state.voteUrl = payload.url;
      state.allVotes = payload.allVotes;
    },

    latestVotes(state,payload){
      state.latestVotes = payload.latestVotes;
      state.allVotes = payload.allVotes;
      state.loadedVotes = state.latestVotes.length;
      state.offset += 5 ;
    },
    loadMoreVotes(state,payload){

      payload.map((val)=>{
        state.latestVotes.push(val);
      });
      state.loadedVotes = state.latestVotes.length;
      state.offset += 5 ;

    },

    noMore(state){
      state.noMore = true;
    }
}
