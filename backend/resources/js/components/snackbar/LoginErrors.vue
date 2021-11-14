<template>
  <div v-if="loginErrors">
    <v-snackbar v-model="snackbar" multi-line vertical color="error" right bottom>
      <!-- TODO: email, password毎に表示するメッセージを変更する 【時期update】-->
      <ul v-if="loginErrors.email">
        <li v-for="msg in loginErrors.email" :key="msg">
          <span>{{ msg }}</span>
        </li>
      </ul>
      <ul v-if="loginErrors.password">
        <li v-for="msg in loginErrors.password" :key="msg">
          <span>{{ msg }}</span>
        </li>
      </ul>
      <v-btn text dark @click="snackbar = false">CLOSE</v-btn>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  data() {
    return {
      snackbar: false
    }
  },
  computed: {
    loginErrors() {
      this.snackbar = true
      return this.$store.state.auth.loginErrorMessages
    }
  }
}
</script>

<style scoped>
ul {
  list-style: none;
}
</style>
