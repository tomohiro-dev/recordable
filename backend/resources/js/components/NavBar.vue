<template>
  <v-app-bar v-if="!isLogin" color="transparent" app flat hide-on-scroll>
    <v-img :src="'./svg/recordableRogo.svg'" max-height="40" max-width="240"></v-img>
    <v-spacer></v-spacer>
    <v-btn class="me-7" to="/login" text>LOGIN</v-btn>
    <v-btn to="/register" text>REGISTER</v-btn>
  </v-app-bar>
</template>

<script>
export default {
  computed: {
    isLogin() {
      return this.$store.getters['auth/check']
    }
  }
}
</script>

<style></style>
