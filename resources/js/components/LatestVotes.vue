<template>

<v-content>
<v-container grid-list-md>
<v-layout row wrap>

<template v-if="latestVotes.length > 0 ">
  <v-flex xs12>
    <h2 class="white--text title">latest votes</h2>

  </v-flex>
<template v-for="vote in latestVotes">
  <v-flex xs12 sm4>

    <v-card class="indigo white--text " router :to="`/${vote.id}`">
      <v-card-title primary-titl class="text-xs-center">
        <h3>{{vote.question}}</h3>
      </v-card-title>
      <span class="ma-1 white--text">{{vote.stop_at | dateForHuman}}</span><br>
    </v-card>
  </v-flex>
  </template>
  <v-btn round small class="mt-3 green white--text" :loading="loading" @click='loadMoreVotes'>
    load more
  </v-btn>
</template>

<template v-else>
  <h2 class="white--text">
    No recent votes
  </h2>
</template>

</v-layout>
<v-snackbar
bottom
  color="indigo"
  v-model="noMore">
  <h3>you loaded all existing votes :D</h3>
  <v-btn
    dark
    flat
    @click="noMore=false">
    Close
  </v-btn>
</v-snackbar>
</v-container>
</v-content>

</template>

<script>
var moment = require('moment');

export default {

  data(){
    return {

      loading:false
    };
  },
  computed:{
    latestVotes(){
      return this.$store.getters.latestVotes;
    },
    noMore:{
      get(){
        return this.$store.getters.noMore;
      },
      set(){
         false;
      }
    }
  },
  created(){
    this.$store.dispatch('latestVotes');
  },
  methods:{
    loadMoreVotes(){
      this.loading = true;
      this.$store.dispatch('loadMoreVotes').then(()=>{
        this.loading = false
      });

    }
  },
  filters:{
    dateForHuman(val){
      return moment(val).fromNow();
    }
  }
}
</script>

<style scoped>
</style>
