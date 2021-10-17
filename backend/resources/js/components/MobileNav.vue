<template>
<v-bottom-navigation app v-if="isLogin" v-model="mobileNav" shift class="d-lg-none">
    <v-btn v-for="item in items" :key="item.title" :to="item.url" :disabled="item.disabled">
      <span>{{item.title}}</span>
      <v-icon>{{item.icon}}</v-icon>
    </v-btn>
    <v-btn @click="logout">
      <span>LOGOUT</span>
      <v-icon>mdi-exit-run</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>

<script>
export default {
  data() {
    return {
      mobileNav: 0,
      items: [
        {
          title: 'タイマー',
          icon: 'mdi-clock',
          url: '/',
        },
        {
          title: 'ダッシュボード',
          icon: 'mdi-chart-bar',
          url: '/dashboard',
        },
      ]
    };
  },
  methods: {
    async logout() {
      if(confirm('ログアウトしますか？')){
        await this.$store.dispatch('auth/logout');
        if (this.apiStatus) {
          this.$router.push('/login');
        }
      }
    },
  },
  computed: {
    isLogin() {
      return this.$store.getters['auth/check'];
    },
    activeTimer() {
      return this.$store.state.timer.active;
    },
    apiStatus() {
      return this.$store.state.auth.apiStatus;
    },
  }
};
</script>

<style scoped>
a {
  text-decoration: none;
}
</style>
