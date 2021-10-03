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
      errorMessage: ""
    }
  },
}
</script>

<style scoped>
.echarts {
  width: 100%;
  height: 350px;
}
</style>
