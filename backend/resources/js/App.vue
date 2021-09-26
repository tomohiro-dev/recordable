<template>
  <v-app>
    <!-- TODO: snackbar追加 -->
    <NavBar />
    <NavDrawer />
    <v-main>
      <v-container fluid>
        <RouterView />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import NavBar from './components/NavBar.vue'
import NavDrawer from './components/NavDrawer.vue'

// import { INTERNAL_SERVER_ERROR } from './const'

export default {
  components: {
    NavBar,
    NavDrawer
  },
  computed: {
    // error storeのcode state 取得
    errorCode() {
      return this.$store.state.error.code
    }
  },
  watch: {
    //TODO: snackbar追加時にコメントアウト解除
    // errorCode: {
    //   handler(val) {
    //     if (val === INTERNAL_SERVER_ERROR) {
    //       this.systemErrorSnackbar = true
    //     }
    //   },
    //   //createdで呼び出し
    //   immediate: true
    // },
    //同じrouteの異なるパラメータで画面遷移しても、vue-routerは画面を再描画しないのでwatchで監視する
    $route() {
      this.$store.commit('error/SET_CODE', null)
    }
  }
}
</script>

<style></style>
