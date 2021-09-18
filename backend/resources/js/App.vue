<template>
  <v-app>
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

export default {
  components: {
    NavBar,
    NavDrawer
  }
}
</script>

<style></style>
