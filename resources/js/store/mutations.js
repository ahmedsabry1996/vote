export default {

  newVoteErrors(state,payload){
    state.newVoteErrors = payload;
  },
    truncateErrors(state){
      state.newVoteErrors = null;
    },

    assignVoteUrl(state,payload){

      state.voteUrl = payload;

    }
}
