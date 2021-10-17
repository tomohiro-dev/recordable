<template>
  <v-app>
    <v-snackbar top v-model="systemErrorSnackbar" color="error">
      A system error has occurred.
      <v-btn text @click="systemErrorSnackbar = false">CLOSE</v-btn>
    </v-snackbar>
    <NavBar />
    <NavDrawer />
    <v-main>
      <v-container fluid>
        <RouterView />
      </v-container>
    </v-main>
    <MobileNav />
  </v-app>
</template>

<script>
import NavBar from './components/NavBar.vue'
import NavDrawer from './components/NavDrawer.vue'
import MobileNav  from './components/MobileNav.vue'

import { INTERNAL_SERVER_ERROR } from './util'

export default {
  components: {
    NavBar,
    NavDrawer,
    MobileNav
  },
  data() {
    return {
      systemErrorSnackbar: false
    }
  },
  computed: {
    // error storeのcode state 取得
    errorCode() {
      return this.$store.state.error.code
    }
  },
  watch: {
    errorCode: {
      handler(val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.systemErrorSnackbar = true
        }
      },
      //createdで呼び出し
      immediate: true
    },
    //同じrouteの異なるパラメータで画面遷移しても、vue-routerは画面を再描画しないのでwatchで監視する
    $route() {
      this.$store.commit('error/SET_CODE', null)
    }
  }
}
</script>

<style></style>
