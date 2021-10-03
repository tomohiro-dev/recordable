<template>
  <!-- <v-navigation-drawer
    v-if="isLogin"
    :disable-route-watcher="true"
    app
  > -->
    <!-- ↑本番用 -->


  <v-navigation-drawer :disable-route-watcher="true" app>
    <!-- ↑画面開発用 -->
    <v-list-item class="px-2">
      <!-- <v-menu top offset-x :close-on-content-click="false">
        <template v-slot:activator="{ on }">
          <v-list-item-avatar class="avatar" v-on="on">
            <v-img>TODO:【nextUpdate】アイコン追加</v-img>
          </v-list-item-avatar>
        </template>
      </v-menu> -->

      <!-- <v-list-item-title>{{ username }}</v-list-item-title> -->
      <v-list-item-title>ユーザーネーム</v-list-item-title>
      <!-- TODO: ユーザーネームの表示に成功したら削除 -->
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

    <template v-slot:append>
      <div class="px-2">
        <v-menu top offset-x :close-on-content-click="false">
          <template v-slot:activator="{ on }">
            <v-btn icon v-on="on">
              <v-icon>mdi-image-multiple</v-icon>
            </v-btn>
          </template>
          <v-card max-width="500">
            <v-slide-group
              v-model="navImage"
              class="pa-2"
              mandatory
              center-active
              show-arrows
             >
              <v-slide-item
               v-for="image in navImages"
               :key="image"
               v-slot:default="{ active }"
              >
                <v-sheet
                  :class="active && 'image_active'"
                  class="ma-2 image"
                >
                  <v-img :src="image" height="100" width="50" />
                </v-sheet>
              </v-slide-item>
            </v-slide-group>
          </v-card>
        </v-menu>
      </div>

      <div class="pa-2">
        <v-btn block @click="logout">ログアウト</v-btn>
      </div>
    </template>
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
          url: '/timer',
          // url: '/',
          disabled: false,
          badge: true
        },
        {
          title: 'ダッシュボード',
          icon: 'mdi-chart-bar',
          url: '/dashboard',
          disabled: false,
          badge: false
        }
      ],
      navImage: 0,
      navImages: [
        // TODO: ASANAでpick upしている地域の画像を追加
      ]
    }
  },
  methods: {
    async logout() {
      if (confirm('ログアウトしますか？')) {
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
      return this.$store.state.auth.apiStatus
    },
    username() {
      return this.$storegetters["auth/username"]
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
