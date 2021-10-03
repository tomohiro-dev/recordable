<template>
  <div>
    <!-- TODO: snackbarの追加 -->

    <span class="title">
      <v-icon class="mr-2 mb-1">mdi-archive-clock</v-icon>レコード
    </span>
    <v-row>
      <v-col cols="12" sm="4" md="3">
        <v-card>
          <v-card-title class="pb-2">今日</v-card-title>
          <v-divider class="my-0 mx-4"></v-divider>
          <v-card-text class="display-2">
            <ICountUp :sndVal="record.today" />
            <span class="headline">分</span>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" sm="4" md="3">
        <v-card>
          <v-card-title class="pb-2">今週({{ thisWeek }})</v-card-title>
          <v-divider class="my-0 mx-4"></v-divider>
          <v-card-text class="display-2">
            <ICountUp
             :endVal="record.thisweek"
             :options="{ decimals: '.', decimalPlaces: 1}"
            />
            <span class="headline">時間</span>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" sm="4" md="3">
        <v-card>
          <v-card-title class="pb-2">今月({{ thisMonth }})</v-card-title>
          <v-divider class="my-0 mx-4"></v-divider>
          <v-card-text class="display-2">
            <ICountUp :sndVal="record.thisMonth" />
            <span class="headline">時間</span>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" sm="4" md="3">
        <v-card>
          <v-card-title class="pb-2">合計</v-card-title>
          <v-divider class="my-0 mx-4"></v-divider>
          <v-card-text class="display-2">
            <ICountUp :sndVal="record.total" />
            <span class="headline">時間</span>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="5">
        <span class="title">
          <v-icon class="mr-2 mb-1">mdi-chart-pie</v-icon>内訳
        </span>

        <v-tabs v-model="pie">
          <v-tab>今月（時間）</v-tab>
        </v-tabs>

        <v-tabs-items v-model="pie">
          <v-tab-item>
            <v-card>
              <div class="py-4">
                <div class="echarts d-flex justify-center align-center">
                  <vue-loading
                   type="bars"
                   color="#696969"
                   :size="{ width: '50px', height: '50px' }"
                  >
                  </vue-loading>
                </div>
              </div>

              <div>
                <div class="py-4">
                  <chart
                   :options="chartPie"
                   autoresize
                  >
                  </chart>
                  <v-row>
                    <v-col>
                      <!-- <v-img>TODO: 画像追加</v-img> -->
                      <p class="mt-2 subtitle-1 text-center">データがありません。</p>
                    </v-col>
                  </v-row>
                </div>
              </div>

            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-col>

      <v-col cols="12" md="7">
        <span class="title">
          <v-icon class="mr-2 mb-1">mdi-equalizer</v-icon>週間
        </span>

        <v-tabs v-model="stack">
          <v-tab>今週（分）</v-tab>
        </v-tabs>

        <v-tabs-items v-model="stack">
          <v-tab-item>
            <v-card>
              <div class="py-4" v-if="loading.stack">
                <div class="echarts d-flex justify-center align-center">
                  <vue-loading
                   type="bars"
                   color="#696969"
                   :size="{ width: '50px', height: '50px' }"
                  >
                  </vue-loading>
                </div>
              </div>

              <div class="py-4" else>
                <chart
                 :options="chartStackWeek"
                 autoresize
                >
                </chart>
                <div>
                  <v-row>
                    <v-col>
                      <!-- <v-img>TODO: 画像追加</v-img> -->
                      <p class="mt-2 subtitle-1 text-center">データがありません。</p>
                    </v-col>
                  </v-row>
                </div>
              </div>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-col>

    </v-row>
  </div>
</template>

<script>
import ICountUp from "vue-countup-v2"
import moment from "moment"

export default {
  components: {
    ICountUp
  },
  data() {
    return {
      record: {
        today: 0,
        thisWeek: 0,
        thisMonth: 0,
        todal: 0
      },
      loading: {
        pie: true,
        stack: true
      },
      stack: "",
      pie: "",
      errorMessage: "",
      chartPie: {
        tooltip: {
          trigger: "item",
          formatter: "{b} : {c}時間 ({d}%)"
          // formatter: "{b} : {c}H ({d}%)"
        }
      }
    }
  },
  computed: {
    thisWeek() {
      return moment()
        .day(0)
        .format("M月D日")
      // .format("M/D")
    },
    thisMonth() {
      return moment()
        .format("YYYY年M月")
        // .format("YYYY,M")
    }
  },
  watch: {
    "timers.month": {
      handler: function(val) {
        const now = moment();

        /*
        *今日のレコード
        */
        //今日のレコードのみに絞るための関数
        //計測中のタイマー
        //今日と同じ日付の記録のみに絞る

        //今日のレコード定義
        //今日のレコードの経過秒を初期化する
        //今日のレコード経過分を計算する

        //表示する値を定義する

        function thisWeek(val) {
          return (
            val.stopped_at !== null &&
            moment(val.started_at).week() === now.week()
          )
        }

        const timers_this_week = this.timers.month.filter(thisWeek)
        let amountThisWeek = 0

        for (let i = 0; i < timers_this_week.length; i++) {
          const started_at = moment(timers_this_week[i].started_at)
          const stopped_at = moment(timers_this_week[i].stopped_at)
          amountThisWeek += stopped_at.diff(started_at, "seconds")
        }
        this.recordThisWeek = Math.round((amountThisWeek / 3600) * 10) / 10

        /*
        *今月のレコード
        */
        //今月のレコードのみに絞るための関数
        //計測中のタイマー
        //今月と同じ日付の記録のみに絞る

        //今月のレコード定義
        //今月のレコードの経過秒を初期化する
        //今月のレコード経過分を計算する

        //表示する値を定義する

        /*
        *Pie Chartへ今月のデータを表表示
        */
        //今月のデータがある場合に実行（データが空ではない）


      }


    }
  }
}
</script>

<style scoped>
.echarts {
  width: 100%;
  height: 350px;
}
</style>
