<template>
  <v-row>
    <v-spacer></v-spacer>
    <v-col cols="9" md="6" class="mt-n11">
      <!-- <v-img>TODO: 画像を追加</v-img> -->
    </v-col>
    <v-spacer></v-spacer>
    <v-col cols="12" md="5">
      <v-card>
        <v-card-title>
          <h1>Recordableをつかってみよう</h1>
          <!-- TODO: 文言検討 -->
        </v-card-title>

        <v-card-title class="pb-0">
          <h2 class="title">アカウントへログインする</h2>
        </v-card-title>

        <v-card-text>
          <v-form>
            <v-text-field tabindex="1" v-model="loginForm.email" label="メールアドレス" prepend-icon="mdi-email" />
            <v-text-field
              tabindex="2"
              v-model="loginForm.password"
              label="パスワード"
              prepend-icon="mdi-lock"
              :type="showPassword ? 'text' : 'password'"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="showPassword = !showPassword"
              @keyup.enter="login"
            />
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-tooltip buttom>
            <template>
              <v-btn text color="success">テストログイン</v-btn>
            </template>
            <span> <v-icon dark left>mdi-alert-circle</v-icon>一定期間が立つとアカウント情報は初期化されます。 </span>
          </v-tooltip>

          <v-btn
            tabindex="3"
            class="target"
            :disabled="loginForm.email === '' || loginForm.password === ''"
            color="info"
            text
            @click="login"
          >
            ログイン
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
        <v-btn text dark @click="sackbar = false">閉じる</v-btn>
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
    async login() {
      await this.$store.dispatch('auth/login', this.loginForm)

      if (this.apiStatus) {
        this.$router.push('/')
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

<style></style>
