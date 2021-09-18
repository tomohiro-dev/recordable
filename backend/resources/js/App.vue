<template>
  <v-app>
    <NavBar />
    <NavDrawer />
    <v-container fluid>
      <RouterView />
    </v-container>
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
