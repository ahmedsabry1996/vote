<template>
  <v-content>
    <v-container grid-list-md>
      <v-layout row wrap>

        <v-flex xs12>

        <h2 class="text-xs-center white--text display-2">
          create your vote now !
        </h2>

        </v-flex>
        <v-flex xs12 sm3>
          <div class="ml-5">
            <v-text-field
              label="vote title"
              dark
              v-model="question">
              </v-text-field>
              <template v-if="createVoteErrors">

              <p class="error--text" v-if="!!createVoteErrors.question">{{createVoteErrors.question[0]}}</p>
            </template>
            </div>
        </v-flex>
        <v-flex xs12 sm3>
          <div class="text-xs-center ml-5">

          <v-menu
                 v-model="menu2"
                 :close-on-content-click="false"
                 :nudge-right="40"
                 lazy
                 transition="scale-transition"
                 offset-y
                 full-width
                 min-width="290px"
               >
            <v-text-field

        slot="activator"
        v-model="date"
        label="deadline"
        prepend-icon="event"
        readonly
        dark
      ></v-text-field>
      <v-date-picker :reactive="true"  v-model="date" @input="menu2 = false"></v-date-picker>
    </v-menu>
    <template v-if="createVoteErrors">
    <p class="error--text" v-if="createVoteErrors.date">{{createVoteErrors.date[0]}}</p>
  </template>
  </div>
        </v-flex>
        <v-flex xs12 sm3>
          <v-menu
          class="ml-4"
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        :nudge-right="40"
        :return-value.sync="time"
        lazy
        transition="scale-transition"
        offset-y
        full-width
        max-width="290px"
        min-width="290px"
      >
        <v-text-field
          dark
          slot="activator"
          v-model="time"
          label="time"
          prepend-icon="access_time"
          readonly
        ></v-text-field>
        <v-time-picker
          v-if="menu"
          v-model="time"
          full-width
          value="00:00"
          @click:minute="$refs.menu.save(time)"
        ></v-time-picker>
      </v-menu>
        </v-flex>
        <v-flex xs12 sm3>
          <v-radio-group v-model="voteType" class="my-0">
            <span class="label label-default white--text">vote options</span>
            <v-radio color="white" :value="1">
              <div slot="label" class="white--text">
                yes - no
              </div>
            </v-radio>
            <v-radio color="white" :value="2">
              <div slot="label" class="white--text">
                yes - no - not sure
              </div>
            </v-radio>

          </v-radio-group>

        </v-flex>
        <v-flex xs12 sm5 offset-sm1>

              <v-btn @click="createVote" color="indigo mt-3" class="white--text">
                create <v-icon> add </v-icon>
              </v-btn>

        </v-flex>
        <v-flex xs12 sm6>
          <div class="text-xs-center" v-if="voteUrl">
            <h3>your vote is available via : </h3>
            <v-btn class="white--text" flat router :to="{ name: 'vote', params: {voteId:voteUrl} }">
              {{voteUrl}} <v-icon right class="mb-2">thumb_up</v-icon>
            </v-btn>
          </div>
        </v-flex>
      </v-layout>

    </v-container>
  </v-content>
</template>

<script>
import axios from 'axios';

export default {
  data(){
    return {
      date: new Date().toISOString().substr(0, 10),
      menu2:false,
      menu:false,
      time:"00:00",
      question:'',
      voteType:1

    }
  },
  computed:{
    createVoteErrors(){
          return this.$store.getters.newVoteErrors;
    },
    voteUrl(){
      return this.$store.getters.voteUrl;
    }
  },
  methods:{
    createVote(){

      var question = this.question;
      var date = this.date
      var time  =  this.time;
      var voteType = this.voteType;
      this.$store.dispatch('createVote',{question,voteType,date,time})
    }
  }
}
</script>

<style scoped>
</style>
