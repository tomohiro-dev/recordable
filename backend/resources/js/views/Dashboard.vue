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
                   v-if="!isEmpty(chartPie.series[0].data)"
                  >
                  </chart>
                  <v-row align="center" justify="center">
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
                 v-if="!isEmpty(this.chartStackWeek.series)"
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
      timers: {
        month: []
      },
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
        },
        series: [
          {
            name: "カテゴリー毎の記録",
            type: "pie",
            roseType: "radius",
            center: ["50%", "38%"],
            data: [
              // {
              // value: 3,
              // name: Writing,
              // itemStyle: { color: "#696969" }
              // }
            ]
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

      }
    }
  },
  methods: {
    isEmpty: function(val) {
      if(!val) {
        if(val !== 0 && val !== false) {
          return true
        }
      } else if (typeof val == "object") {
        return Object.keys(val).length === 0
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
      handler: function() {
        const now = moment();

        /*
        *今日のレコード
        */
        function today(val) {
          return (
            val.stopped_at !== null &&
            moment(val.started_at).isSame(now, "day")
          );
        }
        const timers_today = this.timers.month.filter(today)
        let amountToday = 0

        for (let i = 0; i < timers_today.length; i++) {
          const started_at = moment(timers_today[i].started_at)
          const stopped_at = moment(timers_today[i].stopped_at)
          amountToday += stopped_at.diff(started_at, "seconds")
        }

        this.record.today = Math.round(amountToday / 60)


        /*
        *今週のレコード
        */
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
       let amountThisMonth = 0;

       for (let i = 0; i < this.timers.month.length; i++) {
         const started_at = moment(this.timers.month[i].started_at)
         if (this.timers.month[i].stopped_at === null) {
           continue
         }
         const stopped_at = moment(this.timers.month[i].stopped_at)
         amountThisMonth += stopped_at.diff(started_at, "seconds")
       }

       this.record.thisMonth = Math.round(amountThisMonth / 3600)



        /*
        *Pie Chartへ今月のデータを表へ表示
        */
        //今月のデータがある場合に実行（データが空ではない）
        //TODO: ↑コメント削除
        if(!this.empty(this.timers.month)) {
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

          for (let i = 1; i < this.timers.month.length; i++) {
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
              })
            }
          }

          //降順に並び替える
          //TODO: a,bをrifactoring
          function compare(a, b) {
            let comparison = 0
            if (a.value > b.value) {
              comparison = 1
            } else {
              comparison = -1
            }
            return comparison * -1
          }

          //計測期間を長い順番で並び替える
          data.sort(compare)

          for (let i = 0; i < data.length; i++) {
            data[i].value = Math.round((data[i].value / 3600) * 10) / 10
          }
          this.chartPie.series[0].data = data
          this.loading.pie = false
        } else {
          this.loading.pie = false
        }

        if(!this.isEmpty(timers_this_week)) {
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
            for (let j = 0; j < categories.length; j++) {
              if (categories[j]["id"] === timers_this_week[i]["category_id"]) {
                isSame = true;
                categories[j]["data"].push(timers_this_week[i])
                break;
              }
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
          let data = [0, 0, 0, 0, 0, 0, 0]
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
          categories[i].data = data
        }
        //seriesObject merge to data of category
        for (let i = 0; i < categories.length; j++) {
          for (let j = 0; j < categories.length; j++) {
              if (series[i].id === categories[j].id) {
                series[i] = Object.assign(series[i], categories[j]);
                break;
              }
            }
          }

          for (let i = 0; i < series.length; i++) {
            for (let j = 0; j < series[i].data.length; i++) {
              series[i].data[j] = Math.round((series[i].data[j] / 60) * 10) / 10
            }
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
