<template>
  <div v-if="registerErrors">
    <v-snackbar v-model="snackbar" multi-line vertical color="error" right bottom>
      <ul v-if="registerErrors.email">
        <li v-for="msg in registerErrors.email" :key="msg">
          <span>{{ msg }}</span>
        </li>
      </ul>
      <ul v-if="registerErrors.password">
        <li v-for="msg in registerErrors.password" :key="msg">
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
    registerErrors() {
      this.snackbar = true
      return this.$store.state.auth.registerErrorMessages
    }
  }
}
</script>

<style scoped>
ul {
  list-style: none;
}
</style>
