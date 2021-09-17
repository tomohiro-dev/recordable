<template>
  <v-app>
    <div class="container">
      <NavBar />
      <NavDrawer />
      <RouterView />
    </div>
  </v-app>
</template>

<script>
import NavBar from './components/NavBar.vue'
import NavDrawer from './components/NavDrawer.vue'

export default {
  components: {
    NavBar,
    NavDrawer
  }
}
</script>

<style></style>
