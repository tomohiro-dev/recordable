<template>
  <v-row>
    <v-spacer></v-spacer>
    <v-col>
      <!-- <v-img>TODO: 画像を追加する</v-img> -->
    </v-col>

    <v-spacer></v-spacer>
    <v-col cols="12" md="5" class="mt-n11">
      <v-card>
        <v-card-title>
          <h1>登録画面だよ</h1>
        </v-card-title>

        <v-card-text>
          <div>登録してね</div>
        </v-card-text>

        <v-card-title>
          <h2>アカウントを作る</h2>
        </v-card-title>

        <v-card-text>
          <v-form>
            <v-text-field tabindex="1" label="ユーザー名" prepend-icon="mdi-account-circle" />
            <v-text-field tabindex="2" label="メールアドレス" prepend-icon="mdi-email" />
            <v-text-field
              tabindex="3"
              label="パスワード"
              prepend-icon="mdi-lock"
              :type="showPassword ? 'text' : 'password'"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="showPassword = !showPassword"
              @keyup.enter="register"
            />
            <v-text-field
              tabindex="4"
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

        <v-spacer></v-spacer>
        <v-btn tabindex="5" color="success" text @click="register"> 登録する </v-btn>
      </v-card>
    </v-col>
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
