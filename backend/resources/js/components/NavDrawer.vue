<template>
  <v-navigation-drawer
    v-if="isLogin"
    :disable-route-watcher="true"
    app
    color="#F6F5FA"
    floating
  >

    <v-list-item class="px-2">
      <!-- TODO: 次回アップデート時に追加する -->
      <!-- TODO: ユーザーアイコンの追加 -->
      <v-list-item-title class="font-weight-bold">{{ username }}</v-list-item-title>
    </v-list-item>
    <v-divider></v-divider>

    <v-list nav>
      <v-list-item v-for="item in items" :key="item.title" :to="item.url" :disabled="item.disabled">
        <v-list-item-icon v-if="item.badge">
          <v-badge v-if="activeTimer" color="pink" dot overlap>
            <v-icon>{{ item.icon }}</v-icon>
          </v-badge>
          <v-icon v-else>{{ item.icon }}</v-icon>
        </v-list-item-icon>
        <v-list-item-icon v-else>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>

    <template v-slot:append>
      <div class="px-2">
        <v-menu top offset-x :close-on-content-click="false">
          <!-- TODO: 次回アップデート時に追加する -->
          <!-- 着せ替え追加（追加するか検討中） -->
        </v-menu>
      </div>

      <div class="pa-2">
        <v-btn block @click="logout">LOGOUT</v-btn>
      </div>
    </template>
  </v-navigation-drawer>
</template>

<script>
export default {
  data() {
    return {
      floating: "false",
      items: [
        {
          title: 'Timer',
          icon: 'mdi-camera-timer',
          url: '/',
          disabled: false,
          badge: true
        },
        {
          title: 'Dashboard',
          icon: 'mdi-chart-bar',
          url: '/dashboard',
          disabled: false,
          badge: false
        }
      ]
    }
  },
  methods: {
    async logout() {
      if (confirm('Would you like to logout?')) {
        await this.$store.dispatch('auth/logout')

        if (this.apiStatus) {
          this.$router.push('/login')
        }
      }
    }
  },
  computed: {
    apiStatus() {
      return this.$store.state.auth.apiStatus
    },
    isLogin() {
      return this.$store.getters["auth/check"]
    },
    username() {
      return this.$store.getters["auth/username"]
    },
    activeTimer() {
      return this.$store.state.timer.active
    }
  }
}
</script>

<style scoped>
a {
  text-decoration: none;
}
</style>
