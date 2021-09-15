<template>
  <v-app>
    <div class="container">
      <NavBar />
      <RouterView />
    </div>
  </v-app>
</template>

<script>
import NavBar from './components/NavBar.vue'

export default {
  components: {
    NavBar
  }
}
</script>

<style></style>
