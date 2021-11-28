<template>
  <v-snackbar top v-model="snackbarSystemError" color="error">
    A system error has occurred.
    <v-btn text @click="snackbarSystemError = false">CLOSE</v-btn>
  </v-snackbar>
</template>

<script>
export default {
  data() {
    return {
      snackbarSystemError: false
    }
  }
}
</script>

<style>
</style>
