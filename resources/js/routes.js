import ShowVote from './components/ShowVote.vue';
import NotFound from './components/NotFound.vue';
export const routes= [

    {
      path:'/:voteId',
      component:ShowVote,
      name:'vote',
  },
          { path: "*",
          component: NotFound }
]
