<template>
  <span>
    <v-col cols="12" sm="4" md="3">
      <v-card>
        <v-icon>mdi-file</v-icon>レコード
        <v-card-title class="pb-2">今日</v-card-title>
        <v-divider></v-divider>
        <v-card-text class="display-2">
          <span class="headline">分</span>
        </v-card-text>
      </v-card>
    </v-col>

    <v-col cols="12" sm="4" md="3">
      <v-card>
        <v-card-title class="">今週</v-card-title>
        <v-divider></v-divider>
        <v-card-text class="display-2">
          <span>時間</span>
        </v-card-text>
      </v-card>
    </v-col>

    <v-col cols="12" sm="4" md="3">
      <v-card>
        <v-card-title class="">今月</v-card-title>
        <v-divider></v-divider>
        <v-card-text class="display-2">
          <span>時間</span>
        </v-card-text>
      </v-card>
    </v-col>

    <v-col cols="12" md="3">
      <v-card>
        <v-card-title>総合計</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <span>時間</span>
        </v-card-text>
      </v-card>
    </v-col>
  </span>
</template>

<script>
export default {}
</script>

<style></style>
