<template>
  <div>
    <page
        @click:append="showPassword = !showPassword"
        @keyup.enter="keyUpEnter"
        @click="tutorialLogin"
        @login="login"
        @loginErrors="loginErrors"
    />
  </div>
</template>

<script>
import Page from 'Login/Page.vue'

export default {
  components: {
    Page
  },
  data() {
    return {
      snackbar: false,
      showPassword: false,
      loginForm: {
        email: '',
        password: ''
      }
    }
  },
  created() {
    this.clearError()
  },
  methods: {
    tutorialLogin() {
      this.loginForm = {
        email: "tutorial@tutorial.com",
        password: "tutorial"
      }
      this.login()
    },
    keyUpEnter() {
      this.login()
    },
    async login() {
      await this.$store.dispatch('auth/login', this.loginForm)

      if (this.apiStatus) {
        this.$router.push('/')
        console.log('login success!!')
      } else {
        console.log('login failed!')
      }
    },
    clearError() {
      this.$store.commit('auth/setLoginErrorMessages', null)
    }
  },
  computed: {
    apiStatus() {
      return this.$store.state.auth.apiStatus
    },
    loginErrors() {
      this.snackbar = true
      return this.$store.state.auth.loginErrorMessages
    }
  }
}
</script>

<style>
</style>
