export default {


  createVote(context,data){
    axios.post('/api/new-vote',{

      question:data.question,
      vote_type:data.voteType,
      date:data.date,
      time:data.time,

    })

    .then((response)=>{
      context.commit('truncateErrors');
      let voteUrl = response.data.id;
      context.commit('assignVoteUrl',voteUrl);


    })

    .catch((errors)=>{
      context.commit('newVoteErrors',errors.response.data.errors)
    })
  },


  ShowVote(context,data){
    return new Promise(function(resolve, reject) {

      axios.post('/api/show-vote',{
        vote_id:data.voteId,
      })
      .then((response)=>{
          resolve(response)
      })
      .catch((error)=>{
        reject(error)
      })
    });

  },


  vote(context,data){

    new Promise(function(resolve, reject) {

    axios.post('/api/vote',{
      vote_id:data.voteId,
      vote:data.vote
    })
    .then((response)=>{
      console.log(response.data);
      resolve(response);
    })
    .catch((error)=>{
      console.log(error.response);
      reject(error)
    })
  });
  }



}
