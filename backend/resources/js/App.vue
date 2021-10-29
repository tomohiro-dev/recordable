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

import { INTERNAL_SERVER_ERROR } from './plugin/status'

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
    errorCode() {
      return this.$store.state.error.code
    }
  },
  watch: {
    errorCode: {
      handler(val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.systemErrorSnackbar = true
          this.$router.push('/500')
        }
      },
      //createdで呼び出し
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
