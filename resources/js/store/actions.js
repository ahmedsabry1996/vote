export default {


  createVote(context,data){
    axios.post('/api/new-vote',{
      question:data.question,
      vote_type:data.voteType,
      date:data.date,
      time:data.time,

    })
    .then((response)=>{
      console.log(response.data);
      
    })
    .catch((errors)=>{
      context.commit('newVoteErrors',errors.response.data.errors)
    })
  }

}
