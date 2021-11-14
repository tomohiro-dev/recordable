<template>
  <v-snackbar top v-model="snackbar.error" color="error">
      An error has occurred. message：{{ errorMessage }}
      <v-btn text @click="snackbar.error = false">CLOSE</v-btn>
  </v-snackbar>
</template>

<script>
export default {
  data() {
    return {
      snackbar: {
        error: false
      },
      errorMessage: ""
    }
  }
}
</script>

<style>

</style>
