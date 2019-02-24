import ShowVote from './components/ShowVote.vue';
export const routes= [

    {
      path:'/:voteId',
      component:ShowVote,
      name:'vote',

  }
]
