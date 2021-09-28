<template>
  <div>
    <!-- TODO: snackbar系は上にまとめる -->
    <!-- componentとして切り出してもいいかも -->

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
      <v-btn v-if="isEmpty(counter.timer.name)" fab dark small color="#696969" @click.stop="dialog.newTimer = true">
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

    <!-- 新規作成タイマー -->
    <div class="text-center">
      <v-dialog v-model="dialog.newTimer" width="500">
        <v-card>
          <v-card-title class="headline">
            <v-icon class="mr-2" color="#FB5D63">mdi-timer-outline</v-icon>タイマーモード
          </v-card-title>
          <v-card-text>
            <v-form v-model="newTimerValid" ref="newTimerForm">
              <v-container class="pt-0">
                <v-row>
                  <!-- 記録内容入力 -->
                  <v-col cols="12">
                    <v-text-field
                      v-model="newTimer.name"
                      color="#46DBC9"
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
                      color="#46DBC9"
                      v-model="newTimer.category"
                      :items="categories"
                      item-text="name"
                      item-value="value"
                      label="カテゴリー*を追加してください"
                      return-object
                      required
                      prepend-icon="mdi-folder"
                      disabled
                    ></v-select>
                    <v-select
                      v-else
                      color="#46DBC9"
                      v-model="newTimer.category"
                      :items="categories"
                      item-text="name"
                      item-value="value"
                      label="カテゴリーを選択する*"
                      return-object
                      required
                      prepend-icon="mdi-folder"
                      :rules="rules.category"
                    ></v-select>
                  </v-col>
                  <!-- カテゴリ追加ボタン -->
                  <div class="text-center">
                    <v-menu v-model="menu.newTimerCategory" :close-on-content-click="false" :nudge-width="200" offset-x>
                      <template v-slot:activator="{ on }">
                        <v-btn text color="grey lighten-1" v-on="on">
                          <v-icon color="light-green accent-4">mdi-plus</v-icon>カテゴリーを追加する
                        </v-btn>
                      </template>

                      <!-- カテゴリー追加メニュー -->
                      <v-card>
                        <v-card-title>
                          <span class="headline">カテゴリーを追加する</span>
                        </v-card-title>
                        <v-card-text>
                          <v-container>
                            <v-row>
                              <v-col cols="12">
                                <!-- カテゴリー名入力 -->
                                <v-text-field
                                  v-model="newCategory.name"
                                  label="カテゴリー名を入力"
                                  prepend-icon="mdi-folder-plus"
                                ></v-text-field>

                                <!-- カラーコード選択 -->
                                <v-text-field
                                  prepend-icon="mdi-format-color-fill"
                                  v-mask="mask"
                                  hide-details
                                  class="ma-0 pa-0"
                                  label="色を選択"
                                  v-model="newCategory.color"
                                >
                                  <template v-slot:append>
                                    <v-menu
                                      v-model="menu.newTimerColor"
                                      top
                                      nudge-bottom="248"
                                      nudge-left="16"
                                      :close-on-content-click="false"
                                    >
                                      <template v-slot:activator="{ on }">
                                        <div :style="swatchStyle" v-on="on" />
                                      </template>
                                      <v-card>
                                        <v-card-text class="pa-0">
                                          <v-color-picker v-model="newCategory.color" flat show-swatches />
                                        </v-card-text>
                                      </v-card>
                                    </v-menu>
                                  </template>
                                </v-text-field>
                              </v-col>
                            </v-row>
                          </v-container>
                        </v-card-text>

                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn text @click="menu.newTimerCategory = false">閉じる</v-btn>
                          <v-btn color="primary" text :disabled="newCategory.name === ''" @click="createCategory()"
                            >保存
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-menu>
                  </div>

                  <!-- メモ入力 -->
                  <v-col cols="12">
                    <v-textarea
                      color="#696969"
                      v-model="newTimer.memo"
                      label="メモ"
                      type="text"
                      prepend-icon="mdi-text-box"
                      :rules="rules.memo"
                      :counter="140"
                    ></v-textarea>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
            <small>*は必須項目です。</small>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="dialog.newTimer = false">閉じる</v-btn>
            <v-btn color="blue darken-1" text @click="createTimer()" :disabled="!newTimerValid">スタート</v-btn>
          </v-card-actions>

          <!-- マニュアルモード -->
          <div class="text-center">
            <v-dialog v-model="dialog.saveTimer" width="500">
              <v-card>
                <v-card-title class="headline">
                  <v-icon class="mr-2" color="#696969">mdi-playlist-plus</v-icon>マニュアルモード
                </v-card-title>

                <v-card-text :class="{ 'px-4': windowSize.width < 600 }">
                  <v-form ref="saveTimerForm">
                    <v-container class="pt-0">
                      <v-row>
                        <!-- 記録内容入力 -->
                        <v-col cols="12">
                          <v-text-field
                            v-model="saveTimer.name"
                            label="記録する内容"
                            color="#696969"
                            required
                            prepend-icon="mdi-border-color"
                            :rules="rules.name"
                            :counter="30"
                          >
                          </v-text-field>
                        </v-col>

                        <!-- カテゴリー選択 -->
                        <v-col cols="12">
                          <v-select
                            v-if="isEmpty(categories)"
                            color="#696969"
                            v-model="saveTimer.category"
                            :items="categories"
                            item-text="name"
                            item-value="value"
                            label="カテゴリーを追加してください*"
                            return-object
                            required
                            prepend-ion="mdi-folder"
                            disabled
                          >
                          </v-select>
                          <v-select
                            v-else
                            color="#696969"
                            v-model="saveTimer.category"
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

                        <!-- カテゴリーを追加 -->
                        <div class="text-center">
                          <v-menu
                            v-model="menu.saveTimerCategory"
                            :close-on-content-click="false"
                            :nudge-width="200"
                            offset-x
                          >
                            <template v-slot:activator="{ on }">
                              <v-btn text color="grey lighten-1" v-on="on">
                                <v-icon color="light-green accent-4">mdi-plus</v-icon>カテゴリーを追加する
                              </v-btn>
                            </template>

                            <!-- カテゴリー追加メニュー -->
                            <v-card>
                              <v-card-title>
                                <span class="headline">カテゴリーを追加する</span>
                              </v-card-title>
                              <v-card-text>
                                <v-container>
                                  <v-row>
                                    <v-col cols="12">
                                      <v-text-field
                                        v-model="newCategory.name"
                                        label="カテゴリー名を入力"
                                        prepend-icon="mdi-folder-plus"
                                      >
                                      </v-text-field>

                                      <v-text-field
                                        prepend-icon="mdi-format-color-fill"
                                        v-mask="mask"
                                        hide-details
                                        class="ma-0 pa-0"
                                        label="色を選択"
                                        v-model="newCategory.color"
                                      >
                                        <template v-slot:append>
                                          <v-menu
                                            v-model="menu.saveTimerColor"
                                            top
                                            nudge-bottom="248"
                                            nudge-left="16"
                                            :close-on-content-click="false"
                                          >
                                            <template v-slot:activator="{ on }">
                                              <div :style="swatchStyle" v-on="on" />
                                            </template>

                                            <v-card>
                                              <v-card-text class="pa-0">
                                                <v-color-picker v-model="newCategory.color" flat show-swatches />
                                              </v-card-text>
                                            </v-card>
                                          </v-menu>
                                        </template>
                                      </v-text-field>
                                    </v-col>
                                  </v-row>
                                </v-container>
                              </v-card-text>

                              <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn text @click="menu.saveTimerCategory = false">閉じる</v-btn>
                                <v-btn
                                  color="primary"
                                  text
                                  :disabled="newCategory.name === ''"
                                  @click="createCategory()"
                                  >保存
                                </v-btn>
                              </v-card-actions>
                            </v-card>
                          </v-menu>
                        </div>

                        <!-- ここから -->
                      </v-row>
                    </v-container>
                  </v-form>
                </v-card-text>
              </v-card>
            </v-dialog>
          </div>
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
      newCategory: {
        name: '',
        color: '#696969'
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
      saveTimer: {
        id: '',
        name: '',
        category: '',
        memo: '',
        started_at: new Date(),
        stopped_at: '',
        time: { hours: '', minutes: '', seconds: '' }
      },
      editTimer: {
        id: '',
        name: '',
        category: '',
        memo: '',
        started_at: new Date(),
        stopped_at: '',
        time: { hours: '', minutes: '', seconds: '' }
      },
      dialog: {
        newTimer: false,
        saveTimer: false
        // 記録の編集
      },
      menu: {
        saveTimerCategory: false
      },
      categories: [],
      //floatingAtionBtn（名前変えるかも）
      fab: false,
      counter: { seconds: 0, timer: { name: '', category: '' } },
      newTimerValid: false,
      //カラーコードの入力制御(colorCodeMaskにするかも)
      mask: '#XXXXXXXX',
      windowSize: {
        width: window.innerWidth
      }
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
    },
    createCategory: function () {
      windows.axios
        .post('api/categories', {
          //名前の指定
          //色の指定
        })
        .then((response) => {
          this.categories.push(response.data)
          // カテゴリーネームをresponse
          //新規記録を空欄でresponse
          this.menu.saveTimerCategory = false
        })
        .catch((err) => {})
    }
  }
}
</script>

<style></style>
