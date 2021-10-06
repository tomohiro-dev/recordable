<template>
  <div>
    <!-- TODO: snackbar系は上にまとめる -->
    <!-- componentとして切り出してもいいかも -->

    <!-- vue loading -->
    <!-- <template v-if="loading">
      <v-row :style="`height:${windowSize.height}px`" align="center" justify="center">
        <v-col>
          <vue-loading
            type="bubbles"
            color="#B0BEC5"
            :size="{ width: '50px', height: '50px' }"
          ></vue-loading>
        </v-col>
      </v-row>
    </template> -->

   <!-- loading animation -->
    <!-- <template v-if="loading">
      <v-row :style="`height:${windowSize.height}px`" align="center" justify="center">
        <v-col>
          <vue-loading type="bars" color="#B0BEC5" :size="{ width: '50px', height: '50px' }"></vue-loading>
        </v-col>
      </v-row>
    </template> -->
      <!-- loading animation fin -->

    <!-- 一覧表示 -->
    <template>
      <template v-if="!isEmpty(timersArray)">
        <span class="title">
          {{ formatDate(timers[0].started_at )}}
        </span>
        <v-data-table
          :headers="headers"
          :items="timers"
          :items-per-page="100"
          class="elevation-1 mb-4"
          :hide-default-header="windowSize.width < 600"
          hide-default-footer
          @click:row="openEditTimer"
        >
          <template v-slot:item.name="{ item }">
            <span>{{ item.name }}</span>
          </template>

          <template v-slot:item.memo="{ item }">
            <small class="text-muted">{{ item.memo }}</small>
          </template>

          <template v-slot:item.category="{ item }">
            <span
             :style="{ backgroundColor: item.category_color }"
             style="display: inline-block; height: 10px; width: 10px; border-radius: 50%"
            >
            </span>
            <span :style="{ color: item.category_color }">
              {{ item.category_name }}
            </span>
          </template>

          <template v-slot:item.started_at="{ item }">
            {{ formatTimer(item) }}
          </template>

          <template v-slot:item.time="{ item }">
            <span v-if="showTimer(item)">{{ activeTimerString }}</span>
            <span v-else>{{ calculaterTimeSpent(item) }}</span>
          </template>
        </v-data-table>
      </template>

        <!-- <v-row v-if="!lastPage" justify="center"> -->
          <!-- <v-btn :loading="infiniteLoading" @click="loadMore"> -->
            <!-- <v-btn> -->
              <!-- TODO: loading編集時に削除するタグ -->
            <!-- <v-icon left>mdi-chevron-down</v-icon>もっと見る -->
          <!-- </v-btn> -->
        <!-- </v-row> -->




      <!-- 記録がない時の画面 -->
      <v-row align="center" justify="center">
        <v-col cols="10" md="4">
          <!-- <v-img>TODO: 画像を追加</v-img> -->
          <p class="mt-2 title text-center">千里の道も一歩から。</p>
          <p class="text-center">学習の準備はできましたか？学習を始めましょう！</p>
        </v-col>
      </v-row>
    </template>


    <!-- タイマー追加ボタン -->
    <v-speed-dial
      v-model="fab"
      fab
      bottom
      right
      fixed
      direction="top"
      transition="slide-y-reverse-transition"
    >
      <template v-slot:activator>
        <v-hover v-slot:default="{ hover }">
          <v-btn
            v-model="fab"
            color="#696969"
            dark
            fab
            x-large
            :elevation="hover ? 12 : 6"
          >
            <v-icon v-if="fab">mdi-close</v-icon>
            <v-icon v-else>mdi-play</v-icon>
          </v-btn>
        </v-hover>
      </template>
      <v-btn
        v-if="isEmpty(counter.timer.name)"
        fab
        dark
        small
        color="#696969"
        @click.stop="dialog.newTimer = true"
      >
        <v-icon>mdi-timer-outline</v-icon>
      </v-btn>
      <v-btn fab dark small color="#696969" @click.stop="dialog.saveTimer = true">
        <v-icon>mdi-playlist-plus</v-icon>
      </v-btn>
    </v-speed-dial>


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
                    <v-menu
                      v-model="menu.newTimerCategory"
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
            <v-btn
              color="blue darken-1"
              text
              @click="createTimer()"
              :disabled="!newTimerValid"
            >スタート</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>

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

                  <!-- メモの入力 -->
                  <v-col cols="12">
                    <v-textarea
                      v-model="editTimer.memo"
                      label="メモ"
                      type="text"
                      prepend-icon="mdi-text-box"
                      :rules="rules.memo"
                      :counter="140"
                    ></v-textarea>
                  </v-col>

                  <v-col cols="12">
                    <v-datetime-picker
                      v-model="saveTimer.started_at"
                      :text-field-props="textFieldProps"
                      label="開始日時* / 計測期間*"
                      :timePickerProps="editTimePickerProps"
                    >
                      <template slot="dateIcon">
                        <v-icon color="#696969">mdi-calendar</v-icon>
                      </template>
                      <template slot="timeIcon">
                        <v-icon color="#696969">mdi-clock-outline</v-icon>
                      </template>
                      <template slot="actions" slot-scope="{ parent }">
                        <v-btn text color="primary" @click="parent.okHandler">保存</v-btn>
                      </template>
                    </v-datetime-picker>
                  </v-col>

                  <v-col cols="4">
                    <v-select
                      v-model="saveTimer.time.hours"
                      color="#696969"
                      label="時間"
                      required
                      prepend-icon="mdi-timer-sand-full"
                      :items="time.hours"
                    >
                    </v-select>
                  </v-col>

                  <v-col cols="4">
                    <v-select
                      v-model="saveTimer.time.seconds"
                      color="#696969"
                      label="秒"
                      required
                      :items="time.seconds"
                    >
                    </v-select>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
            <small>*は必須項目です</small>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="dialog.saveTimer = false">閉じる</v-btn>
            <v-btn
              color="blue darken-1"
              text
              @click="addTimer()"
              :disabled="
                saveTimer.name.length > 30 ||
                saveTimer.name === '' ||
                saveTimer.category === '' ||
                (saveTimer.memo && saveTimer.memo.length > 140) ||
                saveTimer.started_at === '' ||
                saveTimer.stopped_at === '' ||
                (!saveTimer.time.hours && !saveTimer.time.minutes && !saveTimer.time.seconds)
              "
            >
              保存</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>

    <!-- タイマーの編集 -->
    <div class="text-center">
      <v-dialog v-model="dialog.editTimer" width="500">
        <v-card>
          <v-card-title
            class="grey--text text--darken-2 headline"
            :class="{ title: windowSize.width < 600 }"
          >
            <v-icon class="mr-2">mdi-update </v-icon>
            レコードを編集する
            <v-spacer></v-spacer>
          </v-card-title>
          <v-card-text :class="{ 'px-4': windowSize.width < 600 }">
            <v-container class="pt-0">
              <v-row>
                <v-col cols="12">
                  <v-select
                    v-model="editTimer"
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

                <!-- メモ入力 -->
                <v-col cols="12">
                  <v-textarea
                    v-model="editTimer.memo"
                    label="メモ"
                    type="text"
                    prepend-icon="mdi-texgt-box"
                    :rules="rules.memo"
                    :counter="140"
                  >
                  </v-textarea>
                </v-col>

                <!-- 開始時刻 -->
                <v-col cols="12">
                  <v-datatime-picker
                    v-model="editTimer.started_at"
                    :text-field-props="textFieldProps"
                    :timePickerProps="timePickerProps"
                    :datePickerProps="datePickerProps"
                    label="開始時刻* / 計測期間*"
                  >
                    <template slot="dateIcon">
                      <v-icon>mdi-calender</v-icon>
                    </template>
                    <template slot="timeIcon">
                      <v-icon>mdi-clock-outline</v-icon>
                    </template>
                    <template slot="actions" slot-scope="{ parent }">
                      <v-btn text color="primary" @click="parent.okHandler"> 保存 </v-btn>
                    </template>
                  </v-datatime-picker>
                </v-col>

                <!-- 計測期間入力 -->
                <v-col cols="4">
                  <v-select
                    v-model="editTimer.time.hours"
                    label="時間"
                    required
                    prepend-icon="mdi-timer-sand-full"
                    :items="time.hours"
                  >
                  </v-select>
                </v-col>
                <v-spacer></v-spacer>

                <v-col cols="4">
                  <v-select
                    v-model="editTimer.time.minutes"
                    label="分"
                    required
                    :items="time.minutes"
                  ></v-select>
                </v-col>
                <v-spacer></v-spacer>
                <v-col cols="4">
                  <v-select
                    v-model="editTimer.time.seconds"
                    label="秒"
                    required
                    :items="time.seconds"
                  >
                  </v-select>
                </v-col>
              </v-row>
            </v-container>
            <small>*は必須項目です。</small>
          </v-card-text>

          <!-- 削除ボタン -->
          <v-menu
            v-model="menu.delete"
            :close-on-content="false"
            :nudge-width="200"
            offset-x
          >
            <template v-slot:action="{ on }">
              <v-btn text color="red lighten-1" v-on="on">削除</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">記録を削除します</span>
              </v-card-title>
              <v-card-text>本当によろしいですか？</v-card-text>

              <v-card-action>
                <v-spacer></v-spacer>
                <v-btn text @click="menu.delete = false">閉じる</v-btn>
                <v-btn
                  color="blue darken-1"
                  text
                  @click="updateTimer()"
                  :disabled="
                    saveTimer.name.length > 30 ||
                    saveTimer.name === '' ||
                    saveTimer.category === '' ||
                    (saveTimer.memo && saveTimer.memo.length > 140) ||
                    saveTimer.started_at === '' ||
                    saveTimer.stopped_at === '' ||
                    (!saveTimer.time.hours && !saveTimer.time.minutes && !saveTimer.time.seconds)
                  "
                >
                  保存</v-btn
                >
              </v-card-action>
            </v-card>
          </v-menu>
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
        saveTimerCategory: false,
        newTimerCategory: false
      },
      time: {
        hours: [],
        minutes: [],
        seconds: []
      },
      headers: [
        // TODO: barみたいな名前にrefactoring
        {
          text: "記録の内容",
          align: "start",
          sortable: false,
          value: "name",
          width: "30%"
        },
        {
          text: "メモ",
          value: "memo",
          sortable: false,
          width: "30%"
        },
        {
          text: "カテゴリー",
          value: "category",
          sortable: "false",
          width: "15%"
        },
        {
          text: "時刻",
          value: "started_at",
          sortable: "false",
          width: "15%"
        },
        {
          text: "計測期間",
          value: "time",
          sortable: "false",
          width: "10%"
        }
      ],
      timersArray: [],
      categories: [],
      fab: false, //floatingActionBtn（名前変えるかも）
      counter: { seconds: 0, timer: { name: '', category: '' } },
      newTimerValid: false,
      mask: '#XXXXXXXX', //カラーコードの入力制御(colorCodeMaskにするかも),
      textFieldProps: {
        prependIcon: 'mdi-clock',
        color: '#696969' //TODO: 色変更
      },
      windowSize: {
        width: window.innerWidth
      },
      timePickerProps: {
        format: '24hr'
      },
      editTimePickerProps: {
        format: '24hr'
      },
      datePickerProps: {
        color: '#696969'
      },
      // loading: true,
      infiniteLoading: false
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
          name: this.newCategory.name,
          color: this.nreCategory.color
        })
        .then((response) => {
          this.categories.push(response.data)
          this.newCategory.name = '',
          this.newTimerCategory = false,
          this.menu.saveTimerCategory = false
        })
        .catch((err) => {})
    },
    openEditTimer(event) {
      const item = event
      if (item.id === this.counter.timer.id) {
        return false
      }
      this.editTimer.id = item.id
      this.editTimer.name = item.name
    },
    // timersArray: {
    //   handler: function() {
    //     this.loading = false
    //   }
    // }
  }
}
</script>

<style></style>
