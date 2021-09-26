<template>
  <v-app-bar v-if="!isLogin" color="transparent" app flat hide-on-scroll>
    <!-- <v-img>ロゴを追加</v-img> -->
    <v-toolbar-title>
      Recordable
      <!-- todo: ロゴを追加する時に削除する -->
    </v-toolbar-title>
    <v-spacer></v-spacer>
    <v-btn class="me-7" to="/login" text>ログイン</v-btn>
    <v-btn to="/register">新規登録</v-btn>
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
