<template>
<v-app class="main-app">
  <v-container grid-list-lg>
    <create-vote></create-vote>
    <router-view></router-view>
  </v-container>
  </v-app>
</template>

<script>
  import CreateVote from './CreateVote.vue';
    export default {
      components:{
        CreateVote,
      },
        mounted() {

        }
    }
</script>
<style scoped>
.main-app{
   background-image: linear-gradient(to right top, #1f7771, #1e6f74, #276673, #325d6e, #3b5466, #3b5364, #3b5162, #3b5060, #355664, #2f5b65, #2c6064, #2e6561);
}

</style>
