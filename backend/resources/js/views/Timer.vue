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

    <!-- 記録が一覧で並んでいる時の画面 -->

    <!-- タイマー追加ボタン -->
    <v-speed-dial v-model="fab" fab bottom right fixed direction="top" transition="slide-reversse-transition">
      <template v-slot:activator>
        <v-hover v-slot:default="{ hover }">
          <v-btn v-model="fab" color="#696969" dark fab x-large :elevation="hover ? 12 : 6">
            <v-icon v-if="fab">mdi-close</v-icon>
            <v-icon v-else>mdi-plus</v-icon>
          </v-btn>
        </v-hover>
      </template>

      <v-btn fab dark small color="#696969" @click.stop="dialog.newTimer = true">
        <v-icon>mdi-timer-outline</v-icon>
      </v-btn>
    </v-speed-dial>

    <!-- 新規作成タイマー -->
    <div class="text-center">
      <v-dialog width="500">
        <v-card>
          <v-card-title class="headline">
            <v-icon class="mr-2" color="#d3d3d3">mdi-timer-outline</v-icon>タイマーモード
          </v-card-title>
          <v-card-text>
            <v-form>
              <v-container class="pt-0">
                <v-col cols="12">
                  <!-- 記録内容 -->
                  <v-text-field
                    v-model="newTimer.name"
                    color="#696969"
                    label="記録する内容*"
                    required
                    prepend-icon="mdi-border-color"
                    :rules="rules.name"
                    :counter="30"
                  ></v-text-field>
                </v-col>

                <!-- カテゴリー選択 -->
                <v-col cols="12">
                  <v-select
                    v-if="isEmpty(categories)"
                    color="#696969"
                    v-model="newTimer.category"
                    :items="categories"
                    item-text="name"
                    item-value="value"
                    label="カテゴリーを追加してください*"
                    return-object
                    required
                    prepend-icon="mdi-folder"
                    disabled
                  >
                  </v-select>
                  <v-select
                    v-else
                    color="#696969"
                    v-model="newTimer.category"
                    :items="categories"
                    item-text="name"
                    item-value="value"
                    label="カテゴリーを選択する"
                    return-object
                    required
                    prepend-icon="mdi-folder"
                    :rules="rules.category"
                  >
                  </v-select>
                </v-col>
              </v-container>
            </v-form>
          </v-card-text>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      newTimer: {
        name: '',
        memo: '',
        category: ''
      },
      // inputTimerRules（名前変えるかも）
      rules: {
        category: [(value) => !!value || '選択してください。'],
        memo: [(value) => (value || '').length <= 140 || '最大140文字までです。'],
        require: [(value) => !!value || '入力してください。'],
        name: [
          (value) => !!value || '入力してください。',
          (value) => (value || '').length <= 30 || '最大30文字までです。'
        ]
      },
      categories: [],
      //floatingAtionBtn（名前変えるかも）
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
