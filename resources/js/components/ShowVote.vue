<template>
<v-content>

  <h2 color="white" class="text-xs-center white--text">
    {{ro}}
  </h2>
</v-content>
</template>

<script>
export default {

computed:{

  ro(){

    return  this.$route.params.voteId
  }
}


}
</script>

<style scoped>
</style>
