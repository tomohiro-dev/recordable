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
            <v-text-field tabindex="2" label="パスワード" />
          </v-form>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {}
</script>

<style></style>
