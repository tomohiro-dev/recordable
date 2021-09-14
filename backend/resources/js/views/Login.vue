<template>
  <v-row>
    <v-col cols="12" md="5">
      <v-card>
        <v-card-title>
          <h1>ログイン画面だよ</h1>
        </v-card-title>

        <v-card-title>
          <h2 class="title">ログイン</h2>
        </v-card-title>

        <v-card-text>
          <v-form>
            <v-text-field tabindex="1" label="メールアドレス" prepend-icon="mdi-email" />
            <v-text-field tabindex="2" label="パスワード" prepend-icon="mdi-lock" />
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-tooltip button>
            <template>
              <v-btn text color="success">テストログイン</v-btn>
            </template>
            <span> <v-icon dark left>mdi-alert-circle</v-icon>一定期間が立つとアカウントは初期化されます。 </span>
          </v-tooltip>

          <v-btn color="info">ログイン</v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {}
</script>

<style></style>
