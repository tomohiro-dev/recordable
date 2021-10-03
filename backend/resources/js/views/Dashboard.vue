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
      }
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
