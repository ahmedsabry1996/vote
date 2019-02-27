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
      let allVotes =response.data.all_votes;
      context.commit('assignVoteUrl',{url:voteUrl,allVotes:allVotes});


    })

    .catch((errors)=>{
      console.log(errors.response);
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
},

  latestVotes(context){


    let offset =  context.state.offset;
    axios.post('/api/latest-votes',{
      offset ,
    }).then((response)=>{
      console.log(response.data);
      context.commit('latestVotes',{latestVotes:response.data.latest_votes,
                                    allVotes:response.data.all_votes})
    })
    .catch((error)=>{

      console.log(error.response);

    })

  },

  loadMoreVotes(context,data){

    let offset = context.state.offset;
    let allVotes = context.state.allVotes;
    let loadedVotes = context.state.loadedVotes;

      if (loadedVotes != allVotes) {

        axios.post('/api/latest-votes',{
          offset ,
        }).then((response)=>{
          console.log(response.data);
          console.log(77);
          context.commit('loadMoreVotes',response.data.latest_votes);
        })
        .catch((error)=>{

          console.log(error.response);

        })
      }
      else{
        context.commit('noMore')
      }
  }



}
