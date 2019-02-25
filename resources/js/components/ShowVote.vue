<template>
<v-content>
    <v-container grid-list-md v-if="vote">
<v-layout row wrap justify-center>
<v-flex xs12 sm4>
  <v-card dark>
        <div class="text-xs-center">

          <bdi>
              <h2>
                    <v-icon>format_quote</v-icon>
                    {{vote.question}}
                    <v-icon>format_quote</v-icon>
              </h2>
            </bdi>

        </div>
        <div :style="voteClass">

          <template v-if="vote.vote_type==1">
            <v-radio-group v-model="selectedVote" row>
                <v-radio value="yes">
                  <div slot="label">
                    yes
                  </div>
                </v-radio>
                <v-radio value="no">
                  <div slot="label">
                    no
                  </div>
                </v-radio>
              </v-radio-group>
          </template>
          <template v-else>
            <v-radio-group v-model="selectedVote" row>

              <v-radio value="yes" label="yes"></v-radio>
              <v-radio value="no" label="no"></v-radio>
              <v-radio value="notsure" label="other"></v-radio>
            </v-radio-group>

          </template>

      </div>
      <div class="text-xs-center">
            <h4>
            End {{vote.stop_at | dateForHuman}}
            </h4>
      </div>
    <div class="text-xs-center">
      <v-btn  medium round color="primary" :loading="loading" @click="voted">
        vote
      </v-btn>
    </div>
  </v-card>
</v-flex>
</v-layout>


    <v-snackbar
      color="indigo"
      v-model="snackbar">
      <h3>Done</h3>
      <v-btn
        dark
        flat
        @click="snackbar = false">
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
        vote:null,
        selectedVote:'null',
        loading:false,
        snackbar:false

      }
    },
    watch:{
      '$route'(to,from){

          this.ShowVote(to.params.voteId);

      }
    },
    computed:{

      voteClass(){
          if (this.vote.vote_type == 1) {
              return {
                  width:"40%",
                  margin:"5px auto",
              }
          }

        else{
          return {
              width:"64%",
              margin:"5px auto",
          };
        }
      },

    currentVote(){
    return  this.$route.params.voteId
    }

  },

    created(){
      this.ShowVote(this.currentVote);
    },
    methods:{
      ShowVote(id){

              this.$store.dispatch('ShowVote',{voteId:id})
              .then((response)=>{

                this.vote = response.data.vote;
                this.selectedVote = response.data.is_voted_before; 
              })
              .catch((error)=>{
                console.log(error.response);
              });
      },
      voted(){
        this.snackbar = true;
        this.loading = true;
        let id = this.vote.id;
        this.$store.dispatch('vote',{voteId:id,vote:this.selectedVote})
        .then((response)=>{

          this.loading = false;

        })
        .catch((error)=>{
          this.loading = true;

        })
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
