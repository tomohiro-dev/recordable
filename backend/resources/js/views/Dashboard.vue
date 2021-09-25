<template>
  <div>
    <v-snackbar top v-model="snackbar.error" color="error">
      エラーが発生しました。
      <v-btn text @click="snackbar.error">閉じる</v-btn>
    </v-snackbar>

    <span class="title"><v-icon>mdi-timer</v-icon>レコード</span>

    <v-row>
      <v-col cols="12" sm="4" md="3">
        <v-card>
          <v-card-title class="pb-2">今日</v-card-title>
          <v-divider class="my-0 mx-4"></v-divider>
          <v-card-text class="display-2">
            <span class="headline">分</span>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" sm="4" md="3">
        <v-card>
          <v-card-title class="">今週</v-card-title>
          <v-divider class="my-0 mx-4"></v-divider>
          <v-card-text class="display-2">
            <span class="headline">時間</span>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" sm="4" md="3">
        <v-card>
          <v-card-title class="">今月</v-card-title>
          <v-divider class="my-0 mx-4"></v-divider>
          <v-card-text class="display-2">
            <span class="headline">時間</span>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card>
          <v-card-title class="pb-2">総合計</v-card-title>
          <v-divider class="my-0 mx-4"></v-divider>
          <v-card-text>
            <span class="headline">時間</span>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="5">
        <span class="title"><v-icon class="mr-2 mb-1">mdi-chart-pie</v-icon>内訳</span>
        <v-tabs v-model="pie">
          <v-tab>今月（時間）</v-tab>
        </v-tabs>

        <v-tabs-items v-model="pie">
          <v-tab-item>
            <v-card>
              <div class="py-4">
                <!-- todo: loading系のアニメーションを追加 -->
              </div>

              <div class="py-4">
                <chart :options="chartPie" autoresize v-if="!isEmpty(chartPie.series[0].data)"></chart>
                <div v-else class="echarts">
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
              <!-- TODO: stackチャート追加 -->

              <div class="py-4">
                <v-row align="center" justify="center">
                  <v-col>
                    <!-- <v-img>TODO: データがない場合の画像を追加</v-img> -->
                    <p class="mt-2 subtitle-1 text-center">早速勉強を始めましょう！</p>
                  </v-col>
                </v-row>
              </div>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: {
        pie: true
      },
      snackbar: {
        error: false
      },
      timers: {
        month: []
      },
      chartPie: {
        tooltip: {
          trigger: 'item',
          formatter: '{b} : {c} ({d}%)'
        },
        legend: {
          bottom: '10',
          left: 'center'
        },
        series: [
          {
            name: 'カテゴリー毎の記録',
            type: 'pie',
            roseType: 'radius',
            radius: [15, 95],
            center: ['50%', '40%'],
            data: [
              {
                value: 1,
                name: 'Reading',
                itemStyle: { color: 'FFF' }
              }
            ]
          }
        ]
      }
    }
  },
  created() {
    window.axios
      .get('api/records')
      .then((response) => {
        timers.month = response.data
      })
      .catch((err) => {
        errorMessage = err
        snackbar.error = true
      })
    window.axios
      .get('api/records/total')
      .then((response) => {
        record.total = response.data
      })
      .catch((err) => {
        errorMessage = err
        snackbar.error = true
      })
  },
  watch: {
    //Pie Chart logic
    //月のデータがemptyじゃない時に実行
    //データをObjectとして保存する
    //id, value, name, colorをそれぞれvariableで定義
    //Pie chartへ渡すdata
  }
}
</script>

<style scoped>
.echarts {
  width: 100%;
  height: 350px;
}
</style>
