<template>
  <div>
    <span class="title"> <v-icon>mdi-timer</v-icon>レコード</span>

    <v-row>
      <v-col cols="12" sm="4" md="3">
        <v-card>
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
    </v-row>

    <v-row>
      <v-col cols="12" md="5">
        <span class="title"> <v-icon>mdi-chart-pie</v-icon>内訳</span>
        <v-tabs v-model="pie">
          <v-tab>今月（時間）</v-tab>
        </v-tabs>

        <v-tabs-items>
          <v-tab-item>
            <v-card>
              <div class="py-4">
                <!-- todo: loading系のアニメーションを追加 -->
              </div>

              <div class="py-4">
                <chart></chart>
                <div>
                  <v-row aligh="center" justify="center">
                    <v-col>
                      <!-- <v-img>TODO: データがない場合の画像を追加</v-img> -->
                      <p class="mt-2 subtitle-1 text-center">早速勉強を始めましょう！</p>
                    </v-col>
                  </v-row>
                </div>
              </div>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-col>

      <v-col cols="12" md="7">
        <span class="title"> <v-icon class="mr-2 mb-1">mdi-equalizer</v-icon>推移</span>
        <v-tabs v-model="stack">
          <v-tab>今週（分）</v-tab>
        </v-tabs>

        <v-tabs-items>
          <v-tab-item>
            <v-card>
              <v-row align="center" justify="center">
                <v-col>
                  <!-- <v-img>TODO: データがない場合の画像を追加</v-img> -->
                  <p class="mt-2 subtitle-1 text-center">早速勉強を始めましょう！</p>
                </v-col>
              </v-row>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {}
</script>

<style></style>
