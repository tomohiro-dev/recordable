<template>
  <v-app>
    <NavBar />
    <NavDrawer />
    <v-main>
      <v-container fluid>
        <RouterView />
      </v-container>
    </v-main>
    <MobileNav />
    <SystemError />
  </v-app>
</template>

<script>
import NavBar from './components/NavBar.vue'
import NavDrawer from './components/NavDrawer.vue'
import MobileNav  from './components/MobileNav.vue'
import SystemError from './components/snackbar/SystemError.vue'

import { INTERNAL_SERVER_ERROR } from './plugin/status'

export default {
  components: {
    NavBar,
    NavDrawer,
    MobileNav,
    SystemError
  },
  computed: {
    errorCode() {
      return this.$store.state.error.code
    }
  },
  watch: {
    errorCode: {
      handler(val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.snackbarSystemError = true
          this.$router.push('/500')
        }
      },
      //beforeCreatedとcreatedの間で呼び出す
      immediate: true
    },
    //同じrouteの異なるパラメータで画面遷移しても、vue-routerは画面を再描画しないのでwatchで監視する
    $route() {
      this.$store.commit('error/setCode', null)
    }
  }
}
</script>

<style></style>
