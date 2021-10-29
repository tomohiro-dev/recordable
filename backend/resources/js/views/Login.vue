<template>
  <v-row>
    <v-spacer></v-spacer>
    <v-col cols="12" md="6" class="mt-n8 pt-12">
      <v-img :src="'./svg/login.svg'"></v-img>
    </v-col>
    <v-col cols="12" md="5">
      <!-- <v-card elevation="0" style="background-color: var(--v-background-base)"> -->
        <!-- TODO:次回リリース時に対応↑ -->
      <v-card>
        <v-card-title>
          <h1>Welcome to Recordable!</h1>
        </v-card-title>

        <v-card-text>
          <div class="text--primary subtitle-2">
            "—A combination of passion and perseverance for a singularly important goal—
            <br />is the hallmark of high achievers in every domain."
            <br /><p class="text-right">Angela Duckworth</p>
          </div>
        </v-card-text>

        <v-card-title class="pb-0">
          <h2 class="title">How many hours will you concentrate today?</h2>
        </v-card-title>

        <v-card-text class="pb-0 mb-0">
          <div class="text--primary subtitle-2">
            <p>Login to manage your account</p>
          </div>
        </v-card-text>

        <v-card-text>
          <v-form>
            <v-text-field
              tabindex="1"
              v-model="loginForm.email"
              label="Email Address"
              prepend-icon="mdi-email"
            />
            <v-text-field
              tabindex="2"
              v-model="loginForm.password"
              label="Password"
              prepend-icon="mdi-lock"
              :type="showPassword ? 'text' : 'password'"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="showPassword = !showPassword"
              @keyup.enter="login"
            />
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn text color="success" v-on="on" @click="tutorialLogin">TUTORIAL</v-btn>
            </template>
            <span>
              <v-icon dark left>mdi-rocket-launch-outline</v-icon>
              You can try out how to operate it. Enjoy Recordable!
            </span>
          </v-tooltip>

          <v-btn
            tabindex="3"
            class="target"
            :disabled="loginForm.email === '' || loginForm.password === ''"
            color="info"
            text
            @click="login"
          >
            Login
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>

    <v-spacer></v-spacer>
    <template v-if="loginErrors">
      <v-snackbar v-model="snackbar" multi-line vertical color="error" right bottom>
        <ul v-if="loginErrors.email">
          <li v-for="msg in loginErrors.email" :key="msg">
            <span>{{ msg }}</span>
          </li>
        </ul>
        <ul v-if="loginErrors.password">
          <li v-for="msg in loginErrors.password" :key="msg">
            <span>{{ msg }}</span>
          </li>
        </ul>
        <v-btn text dark @click="snackbar = false">CLOSE</v-btn>
      </v-snackbar>
    </template>
  </v-row>
</template>

<script>
export default {
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
      this.$store.commit('auth/SET_LOGIN_ERROR_MESSAGES', null)
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

<style scoped>
ul {
  list-style: none;
}
</style>
