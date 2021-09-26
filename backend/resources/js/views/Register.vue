<template>
  <v-row>
    <v-spacer></v-spacer>
    <v-col cols="9" md="6" class="mt-n11">
      <!-- <v-img>TODO: 画像を追加する</v-img> -->
    </v-col>
    <v-spacer></v-spacer>
    <v-col cols="12" md="5">
      <v-card>
        <v-card-title>
          <h1 class="display-1 font-weight-bold">日本語をペラペラに</h1>
        </v-card-title>

        <v-card-text>
          <div class="text--primary subtitle-2">
            日本語学習を仕組み化してペラペラになるには練習が必要です。
            <br />学習内容を記録して練習プロセスを見える化してみましょう。
          </div>
        </v-card-text>

        <v-card-title class="pb-0">
          <h2 class="title">アカウントを作る</h2>
        </v-card-title>

        <v-card-text>
          <v-form>
            <v-text-field
              tabindex="1"
              v-model="registerForm.name"
              label="ユーザー名"
              prepend-icon="mdi-account-circle"
            />
            <v-text-field tabindex="2" v-model="registerForm.email" label="メールアドレス" prepend-icon="mdi-email" />
            <v-text-field
              tabindex="3"
              v-model="registerForm.password"
              label="パスワード"
              prepend-icon="mdi-lock"
              :type="showPassword ? 'text' : 'password'"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="showPassword = !showPassword"
            />
            <v-text-field
              tabindex="4"
              v-model="registerForm.password_confirmation"
              label="パスワード再入力"
              prepend-icon="mdi-lock"
              :type="showPassword ? 'text' : 'password'"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="showPassword = !showPassword"
              @keyup.enter="register"
            />

            <small>
              アカウントを作成することにより、利用規約およびプライバシーポリシーに同意したものとみなされます。
            </small>
          </v-form>
        </v-card-text>

        <v-btn
          tabindex="5"
          :disabled="
            registerForm.name === '' ||
            registerForm.email === '' ||
            registerForm.password === '' ||
            registerForm.password_confirmation === ''
          "
          color="success"
          text
          @click="register"
        >
          登録する
        </v-btn>
      </v-card>
    </v-col>
    <v-spacer></v-spacer>
  </v-row>
</template>

<script>
export default {
  created() {
    this.clearError()
  },
  data() {
    return {
      snackbar: false,
      showPassword: false,
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      //TODO: launch後に追加
      termsDialog: false,
      privacyDialog: false
    }
  },
  methods: {
    async register() {
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch('auth/register', this.registerForm)
      // 通信に成功した場合は
      if (this.apiStatus) {
        // トップページに移動する
        this.$router.push('/')
      }
    },
    clearError() {
      this.$store.commit('auth/SET_REGISTER_ERROR_MESSAGES', null)
    }
  },
  computed: {
    apiStatus() {
      return this.$store.state.auth.apiStatus
    },
    registerErrors() {
      this.snackbar = true
      return this.$store.state.auth.registerErrorMessages
    }
  }
}
</script>

<style></style>
