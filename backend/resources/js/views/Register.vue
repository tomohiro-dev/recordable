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
              アカウントを作成することにより、
              <v-dialog>
                利用規約
              </v-dialog>
              および
              <v-dialog>
                プライバシーポリシー
              </v-dialog>
              に同意したものとみなされます。
            </small>
          </v-form>
        </v-card-text>

        <v-spacer></v-spacer>
        <v-btn tabindex="5" color="success" text @click="register">
          登録
        </v-btn>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {}
</script>

<style></style>
