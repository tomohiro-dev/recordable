<template>
  <v-navigation-drawer v-if="isLogin" :disable-route-watcher="true" app dark>
    <!-- TODO: ユーザーの画像を追加（予定) Samirai & Oiran -->

    <v-list-item class="px-2">
      <v-list-item-title>{{ username }}</v-list-item-title>
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
        <v-list-item-icon>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>

    <!-- ここから -->

    <div class="pa-2">
      <v-btn block @click="logout">ログアウト</v-btn>
    </div>
  </v-navigation-drawer>
</template>

<script>
export default {
  data() {
    return {
      items: [
        {
          title: 'タイマー',
          icon: 'mdi-clock',
          // url: '/timer',
          url: '/',
          disabled: false,
          badge: true
        },
        {
          title: 'ダッシュボード',
          icon: 'mdi-poll-box',
          url: '/dashboard',
          disabled: false,
          badge: false
        }
      ]
    }
  },
  methods: {
    async logout() {
      if (confirm('今日は勉強を終了しますか？')) {
        await this.$store.dispatch('auth/logout')

        if (this.apistatus) {
          this.$router.push('/login')
        }
      }
    }
  },
  computed: {
    apistatus() {
      return this.$store.state.authapiStatus
    },
    isLogin() {
      return this.$store.getters['auth/check']
    },
    username() {
      return this.$store.getters['auth/username']
    }
  }
}
</script>

<style></style>
