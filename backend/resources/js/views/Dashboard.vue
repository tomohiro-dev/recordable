<template>
  <div>
    <v-snackbar top v-model="snackbar.error" color="error">
      An error has occurred. message：{{ errorMessage }}
      <v-btn text @click="snackbar.error = false">CLOSE</v-btn>
    </v-snackbar>

    <!-- レコード -->
    <span class="title">
      <v-icon class="mr-2 mb-1" color="#02E3FF">mdi-archive-clock</v-icon>My Record
    </span>
    <v-row>
      <v-col cols="12" sm="4" md="3">
        <v-card>
          <v-card-text class="display-1">
            <ICountUp :endVal="record.today" />
            <span class="headline">min</span>
          </v-card-text>
          <!-- <v-divider class="my-0 mx-4"></v-divider> -->
          <v-card-title class="pb-2">Today</v-card-title>
        </v-card>
      </v-col>

      <v-col cols="12" sm="4" md="3">
        <v-card>
          <v-card-text class="display-1">
            <ICountUp
             :endVal="record.thisWeek"
             :options="{ decimals: '.', decimalPlaces: 1}"
            />
            <span class="headline">hours</span>
          </v-card-text>
          <v-card-title class="pb-2">This Week({{ thisWeek }}~)</v-card-title>
        </v-card>
      </v-col>

      <v-col cols="12" sm="4" md="3">
        <v-card>
          <v-card-text class="display-1">
            <ICountUp :endVal="record.thisMonth" />
            <span class="headline">hours</span>
          </v-card-text>
          <v-card-title class="pb-2">This Month({{ thisMonthEng }})</v-card-title>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card>
          <v-card-text class="display-1">
            <ICountUp :endVal="record.total" />
            <span class="headline">hours</span>
          </v-card-text>
          <v-card-title class="pb-2">Total</v-card-title>
        </v-card>
      </v-col>
    </v-row>

    <v-row>


        <!-- スタックチャート -->
      <v-col cols="12" md="7">
        <span class="title">
          <v-icon class="mr-2 mb-1" color="#02E3FF">mdi-equalizer</v-icon>Weekly Report
        </span>

        <v-tabs v-model="stack" color="#00CED1">
          <v-tab>{{ thisWeek }}~ (MIN)</v-tab>
        </v-tabs>

        <v-tabs-items v-model="stack">
          <v-tab-item>
            <v-card>
              <div class="py-4" v-if="loading.stack">
                <div class="echarts d-flex justify-center align-center">
                  <vue-loading
                   type="bubbles"
                   color="#C7CED1"
                   :size="{ width: '50px', height: '50px' }"
                  >
                  </vue-loading>
                </div>
              </div>


              <div class="py-4" v-else>
                <chart
                 v-if="!isEmpty(this.chartStackWeek.series)"
                 :options="chartStackWeek"
                 autoresize
                >
                </chart>

                <div class="echarts" v-else>
                  <v-row align="center" justify="center">
                    <v-col>
                      <v-img :contain="true" max-height="250px" :src="'./svg/noData.svg'"></v-img>
                      <p class="mt-2 subtitle-1 text-center">
                        Your records will now be added.
                        <br />Are you ready to get started?
                      </p>
                    </v-col>
                  </v-row>
                </div>
              </div>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
      <!-- スタックチャートfin -->

    <!-- pieチャート -->
      <v-col cols="12" md="5">
        <span class="title">
          <v-icon class="mr-2 mb-1" color="#02E3FF">mdi-chart-pie</v-icon>Monthly Report
        </span>

        <v-tabs v-model="pie" color="#00CED1">
          <v-tab>{{ thisMonthEng }}(HOUR)</v-tab>
        </v-tabs>

        <!-- パイチャート -->
        <v-tabs-items v-model="pie">
          <v-tab-item>
            <v-card>
              <div class="py-4" v-if="loading.pie">
                <div class="echarts d-flex justify-center align-center">
                  <vue-loading
                   type="bubbles"
                   color="#C7CED1"
                   :size="{ width: '50px', height: '50px' }"
                  >
                  </vue-loading>
                </div>
              </div>

              <div class="py-4" v-else>
                  <chart
                   :options="chartPie"
                   autoresize
                   v-if="!isEmpty(chartPie.series[0].data)"
                  >
                  </chart>

                  <div class="echarts" v-else>
                  <v-row align="center" justify="center">
                    <v-col>
                      <v-img :contain="true" max-height="240px" :src="'./svg/noData.svg'"></v-img>
                      <p class="mt-2 subtitle-1 text-center">
                        Your records will now be added.
                        <br />Are you ready to get started?
                      </p>
                    </v-col>
                  </v-row>
                  </div>
                </div>
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
        <!-- pieチャート fin-->

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
      snackbar: {
        error: false
      },
      loading: {
        pie: true,
        stack: true
      },
      timers: {
        month: []
      },
      record: {
        today: 0,
        thisWeek: 0,
        thisMonth: 0, //TODOローマ字表記 dashboard表示用に追加？
        total: 0
      },
      recordEngNotation: {
        thisMonthEng: ''
      },
      stack: "",
      pie: "",
      errorMessage: "",
      // パイチャート用のプロパティ
      chartPie: {
        tooltip: {
          trigger: "item",
          formatter: "{b} : {c}H ({d}%)"
        },
        //legend「地図やグラフにつく説明部分」
        legend: {
          bottom: "10",
          left: "center"
        },
        calculable: true,
        series: [
          {
            name: "Records by category",
            type: "pie",
            roseType: "radius",
            radius: [25, 100],
            //radius: '55%',
            center: ["50%", "40%"],
            data: [],
            animationEasing: "cubicInOut"
          }
        ]
      },
      chartStackWeek: {
        tooltip: {
          trigger: "axis",
          axisPointer: {
            type: "shadow"
          }
        },
        grid: {
          top: 10,
          left: "2%",
          right: "2%",
          bottom: "3%",
          containLabel: true
        },
        xAxis: [
          {
            type: "category",
            data: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            axisTick: {
              alignWithLabel: true
            }
          }
        ],
        yAxis: [
          {
            type: "value",
            axisTick: {
              show: false
            }
          }
        ],
        //データのこと
        series: []
      }
    };
  },
  created() {
    // 今月の記録を取得
    window.axios
      .get("/api/records")
      .then(response => {
        this.timers.month = response.data;
      })
      .catch(err => {
        this.errorMessage = err;
        this.snackbar.error = true;
      });
    // これまでの総合計を取得
    window.axios
      .get("/api/records/total")
      .then(response => {
        this.record.total = response.data;
      })
      .catch(err => {
        this.errorMessage = err;
        this.snackbar.error = true;
      });
  },
  methods: {
    isEmpty: function(val) {
      if (!val) {
        //null or undefined or ''(空文字) or 0 or false
        if (val !== 0 && val !== false) {
          return true;
        }
      } else if (typeof val == "object") {
        return Object.keys(val).length === 0;
      }
      return false
    }
  },
  computed: {
    thisWeek() {
      return moment()
        .day(0)
        .format("M/D")
    },
    thisMonth() {
      return moment()
        .format("M,YYYY")
    },
    thisMonthEng() {
      return moment()
        .format("MMM, YYYY")
    }
  },
  watch: {
    "timers.month": {
      handler: function(val) {
        const now = moment()

        function today(val) {
          return (
            // 計測中のタイマーを弾く
            val.stopped_at !== null &&
            // 今日と同じ日付の記録のみに絞り込む
            moment(val.started_at).isSame(now, "day")
          )
        }
        // 今日のレコード
        const timers_today = this.timers.month.filter(today);
        let amountToday = 0
        // 今日の全レコードの経過分を計算
        for (let i = 0; i < timers_today.length; i++) {
          const started_at = moment(timers_today[i].started_at)
          const stopped_at = moment(timers_today[i].stopped_at)
          amountToday += stopped_at.diff(started_at, "seconds")
        }
        this.record.today = Math.round(amountToday / 60)

        function thisWeek(val) {
          return (
            // 計測中のタイマーを弾く
            val.stopped_at !== null &&
            // 今週の記録のみに絞り込み
            moment(val.started_at).week() === now.week()
          );
        }

        // 今週のレコード
        const timers_this_week = this.timers.month.filter(thisWeek)
        let amountThisWeek = 0

        // 今週の全レコードの経過時間(少数第一位まで)を計算
        for (let i = 0; i < timers_this_week.length; i++) {
          const started_at = moment(timers_this_week[i].started_at)
          const stopped_at = moment(timers_this_week[i].stopped_at)
          amountThisWeek += stopped_at.diff(started_at, "seconds")
        }
        this.record.thisWeek = Math.round((amountThisWeek / 3600) * 10) / 10

        let amountThisMonth = 0;

        for (let i = 0; i < this.timers.month.length; i++) {
          const started_at = moment(this.timers.month[i].started_at);
          // もし計測していたら飛ばす
          if (this.timers.month[i].stopped_at === null) {
            continue;
          }
          const stopped_at = moment(this.timers.month[i].stopped_at);
          amountThisMonth += stopped_at.diff(started_at, "seconds");
        }
        this.record.thisMonth = Math.round(amountThisMonth / 3600);


        //今月のデータがある場合に実行（データが空ではない）
        //TODO: ↑コメント削除
        if (!this.isEmpty(this.timers.month)) {
          let data = [
            {
              id,
              value,
              name,
              itemStyle: { color }
            }
          ]
          let started_at = moment(this.timers.month[0].started_at)
          let stopped_at = moment(this.timers.month[0].stopped_at)
          let id = this.timers.month[0].category_id
          let value = stopped_at.diff(started_at, "seconds")
          let name = this.timers.month[0].category_name
          let color = this.timers.month[0].category_color
          // パイチャート用に渡すデータ
          // 今月の記録でループを回す
          for (let i = 1; i < this.timers.month.length; i++) {
            // 計算中のタイマーは含めないので回避
            if (this.timers.month[i].stopped_at === null) {
              continue
            }
            let started_at = moment(this.timers.month[i].started_at)
            let stopped_at = moment(this.timers.month[i].stopped_at)
            let value = stopped_at.diff(started_at, "seconds")
            let added = false
            for (let j = 0; j < data.length; j++) {
              if (data[j]["id"] === this.timers.month[i].category_id) {
                data[j]["value"] += value
                added = true
                break
              }
            }
            if (added === false) {
              let id = this.timers.month[i].category_id
              let name = this.timers.month[i].category_name
              let color = this.timers.month[i].category_color
              data.push({
                id,
                value,
                name,
                itemStyle: { color }
              });
            }
          }

          //降順に並び替える
          //TODO: a,bをrefactoring
          function compare(a, b) {
            let comparison = 0
            if (a.value > b.value) {
              comparison = 1
            } else {
              comparison = -1
            }
            return comparison * -1
          }

          // 計測期間が長い順に並び替え
          data.sort(compare)
          // 秒を時間に変換
          for (let i = 0; i < data.length; i++) {
            data[i].value = Math.round((data[i].value / 3600) * 10) / 10
          }
          this.chartPie.series[0].data = data
          this.loading.pie = false
        } else{
          this.loading.pie = false
        }


        // 今週のデータが空でない場合のみ実行
        if (!this.isEmpty(timers_this_week)) {
          let series = [
            {
              id: timers_this_week[0]["category_id"],
              name: timers_this_week[0]["category_name"],
              type: "bar",
              color: timers_this_week[0]["category_color"],
              stack: "vistors",
              barWidth: "50%"
            }
          ]

          for (let i = 1; i < timers_this_week.length; i++) {
            let isSame = false;
            for (let j = 0; j < series.length; j++) {
              if (series[j]["id"] === timers_this_week[i]["category_id"]) {
                isSame = true
                break
              }
            }
            if (isSame === false) {
              series.push({
                id: timers_this_week[i]["category_id"],
                name: timers_this_week[i]["category_name"],
                type: "bar",
                color: timers_this_week[i]["category_color"],
                stack: "vistors",
                barWidth: "50%"
              })
            }
          }
          // それぞれのカテゴリーで下記形式の日毎のデータを生成する
          // [日, 月, 火, 水, 木, 金, 土]
          // [79, 52, 200, 334, 390, 330, 220]
          // まずはカテゴリーごとに分類
          // 最初のデータでオブジェクトを生成
          let categories = [
            {
              id: timers_this_week[0]["category_id"],
              data: [timers_this_week[0]]
            }
          ]
          for (let i = 1; i < timers_this_week.length; i++) {
            let isSame = false;
            for (let j = 0; j < categories.length; j++) {
              if (categories[j]["id"] === timers_this_week[i]["category_id"]) {
                isSame = true
                categories[j]["data"].push(timers_this_week[i])
                break
              }
            }
            if (isSame === false) {
              categories.push({
                id: timers_this_week[i]["category_id"],
                data: [timers_this_week[i]]
              })
            }
          }

          // 日毎のデータ作成
          //TODO: i,jをrefactoring
          for (let i = 0; i < categories.length; i++) {
            let data = [0, 0, 0, 0, 0, 0, 0];
            for (let j = 0; j < categories[i].data.length; j++) {
              const started_at = moment(categories[i].data[j].started_at)
              const stopped_at = moment(categories[i].data[j].stopped_at)
              const diff = stopped_at.diff(started_at, "seconds")
              const day = started_at.day()

              switch (day) {
                case 0:
                  data[0] += diff
                  break
                case 1:
                  data[1] += diff
                  break
                case 2:
                  data[2] += diff
                  break
                case 3:
                  data[3] += diff
                  break
                case 4:
                  data[4] += diff
                  break
                case 5:
                  data[5] += diff
                  break
                case 6:
                  data[6] += diff
                  break
              }
            }
            categories[i].data = data;
          }
          for (let i = 0; i < series.length; i++) {
            for (let j = 0; j < categories.length; j++) {
              if (series[i].id === categories[j].id) {
                series[i] = Object.assign(series[i], categories[j]);
                break;
              }
            }
          }
          for (let i = 0; i < series.length; i++) {
            for (let j = 0; j < series[i].data.length; j++) {
              series[i].data[j] =
                Math.round((series[i].data[j] / 60) * 10) / 10
            }
          }
          this.chartStackWeek.series = series;
          this.loading.stack = false;
        }
        else{
          this.loading.stack = false;
        }
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
