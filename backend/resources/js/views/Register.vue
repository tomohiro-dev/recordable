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
              label="ユーザー名"
              v-model="registerForm.name"
              prepend-icon="mdi-account-circle"
            />
            <v-text-field tabindex="2" label="メールアドレス" v-model="registerForm.email" prepend-icon="mdi-email" />
            <v-text-field
              tabindex="3"
              label="パスワード"
              prepend-icon="mdi-lock"
              :type="showPassword ? 'text' : 'password'"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              @click:append="showPassword = !showPassword"
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
              アカウントを作成することにより、 利用規約 および プライバシーポリシー に同意したものとみなされます。
              <!-- TODO: v-dialogを追加 -->
            </small>
          </v-form>
        </v-card-text>

        <v-spacer></v-spacer>
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
          登録
        </v-btn>
      </v-card>
    </v-col>

    <template v-if="registerErrors">
      <v-snackbar v-model="snackbar" multi-line vertical color="error" right bottom>
        <ul v-if="registerErrors.email">
          <li v-for="msg in registerErrors.email" :key="msg">
            <span>{{ msg }}</span>
          </li>
        </ul>
        <ul v-if="registerErrors.password">
          <li v-for="msg in registerErrors.password" :key="msg">
            <span>{{ msg }}</span>
          </li>
        </ul>
        <v-btn text dark @click="snackbar = false">閉じる</v-btn>
      </v-snackbar>
    </template>
  </v-row>
</template>

<script>
export default {}
</script>

<style></style>
