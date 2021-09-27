<template>
  <div>
    <!-- TODO: snackbar系は上にまとめる -->

    <!-- タイマー追加ボタン -->
    <v-speed-dial v-model="fab" fab bottom right fixed direction="top" transition="slide-y-reverse-transition">
      <template v-slot:activator>
        <v-hover v-slot:default="{ hover }">
          <v-btn v-model="fab" color="#696969" dark fab x-large :elevation="hover ? 12 : 6">
            <v-icon v-if="fab">mdi-close</v-icon>
            <v-icon v-else>mdi-plus</v-icon>
          </v-btn>
        </v-hover>
      </template>
      <v-btn v-if="isEmpty" fab dark small color="#696969">
        <v-icon>mdi-timer-outline</v-icon>
      </v-btn>
      <v-btn fab dark small color="#696969">
        <v-icon>mdi-playlist-plus</v-icon>
      </v-btn>
    </v-speed-dial>

    <!-- 記録がない時の画面 -->
    <v-row align="center" justify="center">
      <v-col cols="10" md="4">
        <!-- <v-img>TODO: 画像を追加</v-img> -->
        <p class="mt-2 title text-center">千里の道も一歩から。</p>
        <p class="text-center">学習の準備はできましたか？学習を始めましょう！</p>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fab: false,
      counter: { seconds: 0, timer: { name: '', category: '' } }
    }
  },
  methods: {
    // 値が空( null or undefined or ''(空文字) or [](空の配列) or {}(空のオブジェクト) )を判定
    isEmpty: function (val) {
      if (!val) {
        if (val !== 0 && val !== false) {
          return true
        }
      } else if (typeof val == 'object') {
        return Object.keys(val).length === 0
      }
      return false
      //値を返す
    }
  }
}
</script>

<style></style>
