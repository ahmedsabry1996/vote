<template>
<v-app class="main-app">
  <v-container grid-list-lg>

    <router-view></router-view>

        <v-btn small router to="/test" color="green white--text">
          test it
        </v-btn>
        <v-btn small router to="/" color="indigo yellow--text">
          back to home
        </v-btn>
  </v-container>
  </v-app>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style scoped>
.main-app{
   background-image: linear-gradient(to right top, #1f7771, #1e6f74, #276673, #325d6e, #3b5466, #3b5364, #3b5162, #3b5060, #355664, #2f5b65, #2c6064, #2e6561);
}

</style>
