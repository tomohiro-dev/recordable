<template>
  <v-row>
    <v-spacer></v-spacer>
    <v-col cols="9" md="6" class="mt-n11 pt-12">
      <v-img :src="'./svg/register.svg'"></v-img>
    </v-col>
    <v-spacer></v-spacer>
    <v-col cols="12" md="5">
      <v-card>
        <v-card-title>
          <h1 class="display-1 font-weight-bold">Visualize the learning process.</h1>
        </v-card-title>

        <v-card-text>
          <div class="text--primary subtitle-2">
            <h2>You are connected to the world in Japanese.</h2>
          </div>
        </v-card-text>

        <v-card-title class="pb-0">
          <h2 class="title">Create account</h2>
        </v-card-title>

        <v-card-text class="pb-0 mb-0">
          <div class="text--primary subtitle-2">
            <p>Sign up and get started Recordable!</p>
          </div>
        </v-card-text>

        <v-card-text>
          <v-form>
            <v-text-field
              tabindex="1"
              v-model="registerForm.name"
              label="Enter your user name"
              prepend-icon="mdi-account-circle"
            />
            <v-text-field
              tabindex="2"
              v-model="registerForm.email"
              label="Email Address"
              prepend-icon="mdi-email"
            />
            <v-text-field
              tabindex="3"
              v-model="registerForm.password"
              label="Password(8 or more characters)"
              prepend-icon="mdi-lock"
              :type="showPassword ? 'text' : 'password'"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="showPassword = !showPassword"
            />
            <v-text-field
              tabindex="4"
              v-model="registerForm.password_confirmation"
              label="Confirm Password"
              prepend-icon="mdi-lock"
              :type="showPassword ? 'text' : 'password'"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="showPassword = !showPassword"
              @keyup.enter="register"
            />

            <small>
              By creating an account, you could agree to the Terms of Use and Privacy Policy.
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
          REGISTER
        </v-btn>
      </v-card>
    </v-col>
    <v-spacer></v-spacer>
    <resgister-errors />

  </v-row>
</template>

<script>
import ResgisterErrors from '../components/snackbar/RegisterErrors.vue'

export default {
  components: {
    ResgisterErrors
  },
  data() {
    return {
      showPassword: false,
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      //TODO: launch後に追加（termsDialog, privacyDialog）
      termsDialog: false,
      privacyDialog: false
    }
  },
  created() {
    this.clearError()
  },
  methods: {
    async register() {
      console.log(this.registerForm)
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch('auth/register', this.registerForm)

      // 通信に成功した場合はトップページに遷移
      if (this.apiStatus) {
        // トップページに移動する
        this.$router.push('/')
        console.log('Wow, Register success!!')
      } else {
        console.log('woops! register failed!!')
      }
    },
    clearError() {
      this.$store.commit('auth/setRegisterErrorMessages', null)
    }
  },
  computed: {
    apiStatus() {
      return this.$store.state.auth.apiStatus
    }
  }
}
</script>

<style scoped>
ul {
  list-style: none;
}
</style>
