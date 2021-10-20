<template>
  <div class="body" v-resize="onResize">
    <!-- TODO: snackbarをcomponentとして切り出す（リリース後のアップデート時に実施）-->
    <div v-if="!isEmpty(timers)">
      <v-snackbar
        v-model="snackbar.activeTimer"
        :multi-line="true"
        top
        right
        :timeout="-1"
        :color="counter.timer.category_color"
      >
      <strong class="timer-name pr-4">{{ counter.timer.name }}</strong>
      {{ activeTimerString }}
        <v-btn text @click="stopTimer()">
          <v-icon x-large>mdi-stop</v-icon>
        </v-btn>
      </v-snackbar>
    </div>

    <v-snackbar top v-model="snackbar.done" color="#00acee" :multi-line="true">
      Good Job! You've learned a good deal today. Let's Try to beat your personal best again tomorow!
    </v-snackbar>
    <v-snackbar top v-model="snackbar.updated" color="success">
      The record has been rewritten.
      <v-btn text @click="snackbar.updated = false">CLOSE</v-btn>
    </v-snackbar>
    <v-snackbar top v-model="snackbar.deleted" color="error">
      The record has been deleted.
      <v-btn text @click="snackbar.deleted = false">CLOSE</v-btn>
    </v-snackbar>
    <v-snackbar top v-model="snackbar.error" color="error" :multi-line="true">
      An error has occurred. message：{{ errorMessage }}
      <v-btn text @click="snackbar.error = false">CLOSE</v-btn>
    </v-snackbar>
    <!-- TODO: snackbarをcomponentとして切り出す fin-->


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
        <v-icon>mdi-book-edit-outline</v-icon>
      </v-btn>
    </v-speed-dial>
    <!-- タイマー追加ボタン fin -->

   <!-- loading animation -->
    <template v-if="loading">
      <v-row :style="`height:${windowSize.height}px`" align="center" justify="center">
        <v-col>
          <vue-loading type="bubbles" color="#CAD4D9" :size="{ width: '50px', height: '50px' }"></vue-loading>
        </v-col>
      </v-row>
    </template>
      <!-- loading animation fin -->

    <!-- 一覧表示 (loadingがv-if) v-else -->
    <template>
      <template v-if="!isEmpty(timersArray[0][0])">
        <template v-for="timers in timersArray">
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
                <span v-else>{{ calculateTimeSpent(item) }}</span>
              </template>
            </v-data-table>
        </template>

          <v-row v-if="!lastPage" justify="center">
            <v-btn :loading="infiniteLoading" @click="loadMore">
              <v-icon left>mdi-chevron-down</v-icon>Read more
            </v-btn>
          </v-row>
      </template>

          <!-- 記録がない時の画面 -->
      <v-row v-else :style="`height:${windowSize.height}px`" align="center" justify="center">
        <v-col cols="10" md="4">
          <!-- <v-img>TODO: 画像を追加</v-img> -->
          <p class="mt-2 title text-center">A journey of a thousand miles begins with a single step.</p>
          <p class="text-center">Are you ready to study? Let's get started!</p>
        </v-col>
      </v-row>
    </template>


    <!-- 新規作成タイマー -->
    <div class="text-center">
      <v-dialog v-model="dialog.newTimer" width="500">
        <v-card>
          <v-card-title class="headline">
            <v-icon class="mr-2" color="#FB5D63">mdi-timer-outline</v-icon>Recording
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
                      label="Contents to be recorded*"
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
                      label="You have to add a category*"
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
                      label="Choosing a category*"
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
                          <v-icon color="light-green accent-4">mdi-plus</v-icon>Create a category
                        </v-btn>
                      </template>

                      <!-- カテゴリー追加メニュー -->
                      <v-card>
                        <v-card-title>
                          <span class="headline">Create a category</span>
                        </v-card-title>
                        <v-card-text>
                          <v-container>
                            <v-row>
                              <v-col cols="12">
                                <!-- カテゴリー名入力 -->
                                <v-text-field
                                  v-model="newCategory.name"
                                  label="Enter a category name"
                                  prepend-icon="mdi-folder-plus"
                                ></v-text-field>

                                <!-- カラーコード選択 -->
                                <v-text-field
                                  prepend-icon="mdi-format-color-fill"
                                  v-mask="mask"
                                  hide-details
                                  class="ma-0 pa-0"
                                  label="Select a color"
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
                                          <v-color-picker
                                            v-model="newCategory.color"
                                            flat
                                            show-swatches
                                          />
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
                          <v-btn
                            text
                            @click="menu.newTimerCategory = false"
                          >CLOSE</v-btn>
                          <v-btn
                            color="primary"
                            text
                            :disabled="newCategory.name === ''"
                            @click="createCategory()"
                            >
                            SAVE</v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-menu>
                  </div>

                  <!-- Memo入力 -->
                  <v-col cols="12">
                    <v-textarea
                      color="#696969"
                      v-model="newTimer.memo"
                      label="Memo"
                      type="text"
                      prepend-icon="mdi-text-box"
                      :rules="rules.memo"
                      :counter="140"
                    ></v-textarea>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
            <small>*Required field.</small>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="dialog.newTimer = false">CLOSE</v-btn>
            <v-btn
              color="blue darken-1"
              text
              @click="createTimer()"
              :disabled="!newTimerValid"
            >START</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>

    <!-- Editing -->
    <div class="text-center">
      <v-dialog v-model="dialog.saveTimer" width="500">
        <v-card>
          <v-card-title class="headline">
            <v-icon class="mr-2" color="#696969">mdi-book-edit-outline</v-icon>Editing
          </v-card-title>

          <v-card-text :class="{ 'px-4': windowSize.width < 600 }">
            <v-form ref="saveTimerForm">
              <v-container class="pt-0">
                <v-row>
                  <!-- 記録内容入力 -->
                  <v-col cols="12">
                    <v-text-field
                      v-model="saveTimer.name"
                      label="Contents to be recorded"
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
                      label="You have to add a category*"
                      return-object
                      required
                      prepend-icon="mdi-folder"
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
                      label="Choose a category*"
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
                          <v-icon color="light-green accent-4">mdi-plus</v-icon>Create a category
                        </v-btn>
                      </template>

                      <!-- カテゴリー追加メニュー -->
                      <v-card>
                        <v-card-title>
                          <span class="headline">Create a category</span>
                        </v-card-title>
                        <v-card-text>
                          <v-container>
                            <v-row>
                              <v-col cols="12">
                                <!-- カテゴリー名入力 -->
                                <v-text-field
                                  v-model="newCategory.name"
                                  label="Enter a category name"
                                  prepend-icon="mdi-folder-plus"
                                ></v-text-field>

                                <!-- カラーコード選択 -->
                                <v-text-field
                                  prepend-icon="mdi-format-color-fill"
                                  v-mask="mask"
                                  hide-details
                                  class="ma-0 pa-0"
                                  label="Choose a color"
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
                                          <v-color-picker
                                            v-model="newCategory.color"
                                            flat
                                            show-swatches
                                          />
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
                          <v-btn
                            text
                            @click="menu.saveTimerCategory = false"
                           >CLOSE</v-btn>
                          <v-btn
                            color="primary"
                            text
                            :disabled="newCategory.name === ''"
                            @click="createCategory()"
                            >SAVE
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-menu>
                  </div>

                  <!-- Memoの入力 -->
                  <v-col cols="12">
                    <v-textarea
                      color="#696969"
                      v-model="saveTimer.memo"
                      label="Memo"
                      type="text"
                      prepend-icon="mdi-text-box"
                      :rules="rules.memo"
                      :counter="140"
                    ></v-textarea>
                  </v-col>

                  <!-- Starting timeの入力 -->
                  <v-col cols="12">
                    <v-datetime-picker
                      v-model="saveTimer.started_at"
                      :text-field-props="textFieldProps"
                      label="Starting time* / Measuring period*"
                      :timePickerProps="editTimePickerProps"
                    >
                      <template slot="dateIcon">
                        <v-icon color="#696969">mdi-calendar</v-icon>
                      </template>
                      <template slot="timeIcon">
                        <v-icon color="#696969">mdi-clock-outline</v-icon>
                      </template>
                      <template slot="actions" slot-scope="{ parent }">
                        <v-btn text color="primary" @click="parent.okHandler">SAVE</v-btn>
                      </template>
                    </v-datetime-picker>
                  </v-col>

                  <!-- Measuring period入力 -->
                  <v-col cols="4">
                    <v-select
                      v-model="saveTimer.time.hours"
                      color="#696969"
                      label="hour"
                      required
                      prepend-icon="mdi-timer-sand-full"
                      :items="time.hours"
                    ></v-select>
                  </v-col>

                  <v-col cols="4">
                    <v-select
                      v-model="saveTimer.time.minutes"
                      color="#696969"
                      label="minute"
                      required
                      :items="time.minutes"
                    >
                    </v-select>
                  </v-col>

                  <v-col cols="4">
                    <v-select
                      v-model="saveTimer.time.seconds"
                      color="#696969"
                      label="second"
                      required
                      :items="time.seconds"
                    >
                    </v-select>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
            <small>*Required field.</small>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="dialog.saveTimer = false">CLOSE</v-btn>
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
            >SAVE</v-btn>
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
            <v-icon class="mr-2">mdi-update</v-icon>
            Editing this record
            <v-spacer></v-spacer>
          </v-card-title>
          <v-card-text :class="{ 'px-4': windowSize.width < 600 }">
            <v-container class="pt-0">
              <v-row>
                <v-col cols="12">
                  <v-text-field
                   v-model="editTimer.name"
                   label="Contents to be recorded"
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
                    v-model="editTimer.category"
                    :items="categories"
                    item-text="name"
                    item-value="value"
                    label="Choose a category"
                    return-object
                    required
                    prepend-icon="mdi-folder"
                    :rules="rules.category"
                  >
                  </v-select>
                </v-col>

                <!-- Memo入力 -->
                <v-col cols="12">
                  <v-textarea
                    v-model="editTimer.memo"
                    label="Memo"
                    type="text"
                    prepend-icon="mdi-texgt-box"
                    :rules="rules.memo"
                    :counter="140"
                  >
                  </v-textarea>
                </v-col>

                <!-- Starting time -->
                <v-col cols="12">
                  <v-datetime-picker
                    v-model="editTimer.started_at"
                    :text-field-props="textFieldProps"
                    :timePickerProps="timePickerProps"
                    :datePickerProps="datePickerProps"
                    label="Starting time* / Measuring period*"
                  >
                    <template slot="dateIcon">
                      <v-icon>mdi-calender</v-icon>
                    </template>
                    <template slot="timeIcon">
                      <v-icon>mdi-clock-outline</v-icon>
                    </template>
                    <template slot="actions" slot-scope="{ parent }">
                      <v-btn text color="primary" @click="parent.okHandler">SAVE</v-btn>
                    </template>
                  </v-datetime-picker>
                </v-col>

                <!-- Measuring period入力 -->
                <v-col cols="4">
                  <v-select
                    v-model="editTimer.time.hours"
                    label="hour"
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
                    label="minute"
                    required
                    :items="time.minutes"
                  ></v-select>
                </v-col>
                <v-spacer></v-spacer>
                <v-col cols="4">
                  <v-select
                    v-model="editTimer.time.seconds"
                    label="second"
                    required
                    :items="time.seconds"
                  >
                  </v-select>
                </v-col>
              </v-row>
            </v-container>
            <small>*Required field.</small>
          </v-card-text>

          <!-- DELETEボタン -->
        <v-card-actions>
          <v-menu
            v-model="menu.delete"
            :close-on-content="false"
            :nudge-width="200"
            offset-x
          >
            <template v-slot:activator="{ on }">
              <v-btn text color="red lighten-1" v-on="on">DELETE</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">You could delete a record.</span>
              </v-card-title>
              <v-card-text>Are you really OK with this?</v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text @click="menu.delete = false">CLOSE</v-btn>
                <v-btn color="red lighten-1" text @click="deleteTimer()">OK</v-btn>
              </v-card-actions>
            </v-card>
          </v-menu>

          <v-spacer></v-spacer>
            <v-btn
              color="blue darken-1"
              text
              @click="updateTimer()"
              :disabled="
                editTimer.name.length > 30 ||
                editTimer.name === '' ||
                editTimer.category === '' ||
                (editTimer.memo && editTimer.memo.length > 140) ||
                editTimer.started_at === '' ||
                editTimer.stopped_at === ''
              "
              >
              SAVE
            </v-btn>
        </v-card-actions>
       </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script>
import moment from "moment"
// import { TheMask } from "vue-the-mask"
//TOOD: DELETE

export default {
  data() {
    return {
      snackbar: {
        activeTimer: false,
        done: false,
        updated: false,
        deleted: false,
        error: false
      },
      newTimer: {
        name: '',
        memo: '',
        category: ''
      },
      newCategory: {
        name: '',
        color: '#696969'
      },
      saveTimer: {
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
        saveTimer: false,
        editTimer: false
      },
      menu: {
        newTimerCategory: false,
        newTimerColor: false,
        saveTimerCategory: false,
        saveTimerColor: false,
        delete: false,
      },
        // inputTimerRules（名前変えるかも）
        rules: {
          category: [value => !!value || 'Make a section.'],
          memo: [value => (value || '').length <= 140 || 'Up to 140 characters.'],
          require: [value => !!value || 'Enter your information.'],
          name: [
            value => !!value || 'Enter your information.',
            value => (value || '').length <= 30 || 'Up to 30 characters.'
          ]
        },
      time: {
        hours: [],
        minutes: [],
        seconds: []
      },
      timers: [],
      timersArray: [[]],
      categories: [],
      headers: [
        // TODO: barみたいな名前にrefactoring
        {
          text: "Contents",
          align: "start",
          sortable: false,
          value: "name",
          width: "30%"
        },
        {
          text: "Memo",
          value: "memo",
          sortable: false,
          width: "30%"
        },
        {
          text: "Category",
          value: "category",
          sortable: "false",
          width: "15%"
        },
        {
          text: "Time",
          value: "started_at",
          sortable: "false",
          width: "15%"
        },
        {
          text: "Period",
          value: "time",
          sortable: "false",
          width: "10%"
        }
      ],
      page: 1,
      fab: false, //floatingActionBtn（名前変えるかも）
      counter: { seconds: 0, timer: { name: '', category: '' } },
      activeTimerString: "Calculating...",
      newTimerValid: false,
      errorMessage: "",
      mask: '!#XXXXXXXX', //カラーコードの入力制御(colorCodeMaskにするかも) ...そもそも不要かも
      loading: true,
      infiniteLoading: false,
      lastPage: false,
      windowSize: {
        width: window.innerWidth,
        height: window.innerHeight
      },
      textFieldProps: {
        prependIcon: 'mdi-clock',
        color: '#696969' //TODO: 色変更
      },
      timePickerProps: {
        format: '24hr',
        color: "#696969"
      },
      editTimePickerProps: {
        format: '24hr'
      },
      datePickerProps: {
        color: '#696969'
      },
    }
  },
  created() {
    window.axios
      .get("/api/timers")
      .then(response => {
        this.timers = response.data.data
        if (this.page === response.data.last_page) {
          this.lastPage = true
        }
        window.axios
        .get("/api/timers/active")
        .then(response => {
          if (response.data.id !== undefined) {
            this.startTimer(response.data)
            this.$store.dispatch("timer/fetchActive", true)
          }
        })
      })
    window.axios
      .get("/api/categories")
      .then(response => {
        this.categories = response.data
      })

    this._arrayHours()
    this._arrayMinutes()
  },
  methods: {
    onResize() {
      this.windowSize = {
      width: window.innerWidth,
      height: window.innerHeight
      }
    },
    loadMore() {
      this.infiniteLoading = true
      window.axios
        .get("/api/timers", {
          params: {
            page: this.page + 1
          }
        })
        .then(response => {
          this.page += 1;
          this.timers.push(...response.data.data);
          this.infiniteLoading = false;
          if (this.page === response.data.last_page) {
            this.lastPage = true;
          }
        })
    },
    _padNumber: number => (number > 9 ? number : "0" + number),

    _readableTimeFromSeconds: function(seconds) {
      const hours = 3600 > seconds ? 0 : parseInt(seconds / 3600, 10)
      return {
        hours: this._padNumber(hours),
        seconds: this._padNumber(seconds % 60),
        minutes: this._padNumber(parseInt(seconds / 60, 10) % 60)
      }
    },
    calculateTimeSpent: function(timer) {
      if (timer.stopped_at) {
        const started = moment(timer.started_at)
        const stopped = moment(timer.stopped_at)
        const time = this._readableTimeFromSeconds(
          parseInt(moment.duration(stopped.diff(started)).asSeconds())
        )
        return `${time.hours}:${time.minutes}:${time.seconds}`
      }
      return ""
    },
    showTimer: function(timer) {
      return this.counter.timer && this.counter.timer.id === timer.id
    },
    startTimer: function(timer) {
      this.$store.dispatch("timer/fetchActive", true)
      const started = moment(timer.started_at)

      this.counter.timer = timer
      this.counter.seconds = parseInt(
        moment.duration(moment().diff(started)).asSeconds()
      );
      this.counter.ticker = setInterval(() => {
        const time = this._readableTimeFromSeconds(++this.counter.seconds)
        this.activeTimerString = `${time.hours}:${time.minutes}:${time.seconds}`
      }, 1000)
      this.snackbar.activeTimer = true
    },
    stopTimer: function() {
      window.axios
        .post(`/api/timers/stop`)
        .then(response => {
          // activeTimerをストップ TODO:stopActiveTimerへ変更したい
          const activeTimer = this.timers.find(
            timer => timer.id === this.counter.timer.id
          )
          activeTimer.stopped_at = response.data.stopped_at
          // tickerをストップする
          clearInterval(this.counter.ticker)
          this.counter = { seconds: 0, timer: { name: "", category: "" } }
          this.activeTimerString = "Calculating..."
          this.snackbar.activeTimer = false;
          this.snackbar.done = true

          // confetti({
          var end = Date.now() + (3 * 1000);
          // go Buckeyes!
          var colors = ['#bb0000', '#ffffff'];
          (function frame() {
            confetti({
              particleCount: 2,
              angle: 60,
              spread: 55,
              origin: { x: 0 },
              colors: colors
            });
            confetti({
              particleCount: 2,
              angle: 120,
              spread: 55,
              origin: { x: 1 },
              colors: colors
            });
            if (Date.now() < end) {
              requestAnimationFrame(frame);
            }
          }());
          this.$store.dispatch("timer/fetchActive", false)
        })
        .catch(err => {
          this.errorMessage = err
          this.snackbar.error = true
        })
    },
    createTimer: function() {
      window.axios
        .post(`/api/timers`, {
          name: this.newTimer.name,
          memo: this.newTimer.memo,
          category_id: this.newTimer.category["id"],
          category_name: this.newTimer.category["name"]
            ? this.newTimer.category["name"]
            : "カテゴリー未登録",
          category_color: this.newTimer.category["color"]
            ? this.newTimer.category["color"]
            : "#696969"
        })
        .then(response => {
          this.timers.unshift(response.data)
          this.startTimer(response.data)
          this.newTimer.name = ""
          this.newTimer.memo = ""
          this.newTimer.category = ""
          this.dialog.newTimer = false
        })
        .catch(err => {
          this.errorMessage = err
          this.snackbar.error = true
        })
    },
    /**
     * 新規のタイマーを手入力でSAVEする
     */
    addTimer() {
      window.axios
        .post(`/api/timers/save`, {
          name: this.saveTimer.name,
          memo: this.saveTimer.memo,
          category_id: this.saveTimer.category.id,
          category_name: this.saveTimer.category.name,
          category_color: this.saveTimer.category.color,
          started_at: this.saveTimer.started_at,
          stopped_at: this.saveTimer.stopped_at
        })
        .then(response => {
          const savedTimer = response.data
          const oldestTimer = this.timers[this.timers.length - 1]

          if (this.lastPage || moment(savedTimer.started_at).isAfter(oldestTimer.started_at))
          {
            this.timers.push(savedTimer)
            this.timers.sort(function(a, b) {
              return a.started_at < b.started_at ? 1 : -1
            })
          }

          this.dialog.saveTimer = false
          this.snackbar.done = true
          this.saveTimer = {
            name: "",
            category: "",
            memo: "",
            started_at: new Date(),
            stopped_at: "",
            time: {hours: "", minutes: "", seconds: ""}
          }
        })
        .catch(err => {
          this.errorMessage = err
          this.snackbar.error = true
        })
    },

    /**
     * 新規カテゴリーを作成
     */
    createCategory: function () {
      window.axios
        .post('api/categories', {
          name: this.newCategory.name,
          color: this.newCategory.color
        })
        .then((response) => {
          this.categories.push(response.data)
          this.newCategory.name = '',
          this.newTimerCategory = false,
          this.menu.saveTimerCategory = false
        })
        .catch((err) => {
          this.errorMessage = err,
          this.snackbar.error = true
        })
    },
    /**
     * タイマー編集用のmodal
     */
    openEditTimer(event) {
      const item = event
      if (item.id === this.counter.timer.id) {
        return false
      }
      this.editTimer.id = item.id
      this.editTimer.name = item.name

      this.editTimer.memo = item.memo
      this.editTimer.started_at = new Date(item.started_at)

      const started = moment(item.started_at)
      const stopped = moment(item.stopped_at)
      const time = this._readableTimeFromSeconds(
        parseInt(moment.duration(stopped.diff(started)).asSeconds())
      )
      this.editTimer.time.hours = parseInt(time.hours)
      this.editTimer.time.minutes = parseInt(time.minutes)
      this.editTimer.time.seconds = parseInt(time.seconds)
      this.editTimer.stopped_at = moment(this.editTimer.started_at)
        .add({
          h: this.editTimer.time.hours,
          m: this.editTimer.time.minutes,
          s: this.editTimer.time.seconds
        })
        .toDate()
      this.dialog.editTimer = true
    },
    updateTimer() {
      window.axios
        .put(`/api/timers/${this.editTimer.id}`, {
          name: this.editTimer.name,
          memo: this.editTimer.memo,
          category_id: this.editTimer.category.id,
          category_name: this.editTimer.category.name,
          category_color: this.editTimer.category.color,
          started_at: this.editTimer.started_at,
          stopped_at: this.editTimer.stopped_at

        })
        .then(response => {
          const updatedTimer = response.data
          const timer = this.timers.find(
            timer => timer.id === updatedTimer["id"]
          )

          const startedBefore = moment(timer.started_at)
          const startedAfter = moment(new Date(updatedTimer["started_at"]))

          //Timer valueをupdate
          timer.name = this.editTimer.name
          timer.memo = this.editTimer.memo
          timer.category_id = this.editTimer.category.id
          timer.category_name = this.editTimer.category.name
          timer.category_color = this.editTimer.category.color
          timer.started_at = updatedTimer["started_at"]
          timer.stopped_at = updatedTimer["stopped_at"]

          const oldestTimer = this.timers[this.timers.length - 1]
          if (!this.lastPage &&
              moment(updatedTimer["started_at"]).isBefore(oldestTimer.started_at)) {
                this.timers = this.timers.filter(
                  timer => timer.id !== updatedTimer.id
                )
          }
          //もし日付が更新されたら
          //timersを日付の新しい順番に並び替える
          //TODO: refactoring to a(beforeUpdateTimerとか) and b(updatedTimerとか)
          if (!startedBefore.isSame(startedAfter)) {
            this.timers.sort(function(a, b) {
              return a.started_at < b.started_at ? 1 : -1
            })
          }

          this.dialog.editTimer = false
          this.snackbar.updated = true
        })
        .catch(err => {
          this.errorMessage = err
          this.snackbar.error = true
        })
    },
    deleteTimer() {
      window.axios
        .delete(`/api/timers/${this.editTimer.id}`)
        .then(response => {
          const deletedTimer = response.data
          this.timers = this.timers.filter(
            timer => timer.id !== deletedTimer.id
          )
          this.dialog.editTimer = false
          this.snackbar.deleted = true
        })
        .catch(err => {
          this.errorMessage = err
          this.snackbar.error = true
        })
    },

    _formatTime: function(date) {
      if (date) {
        const d = new Date(date)
        const h = d.getHours()
        const m = d.getMinutes()
        return `${this._padNumber(h)}:${this._padNumber(m)}`
      }
      return ""
    },
    formatTimer: function(timer) {
      const started = timer.started_at
      const stopped = timer.stopped_at
      return `${this._formatTime(started)} - ${this._formatTime(stopped)}`
    },
    formatDate: function(date) {
      //初期実装は英語表記のため"en". 多言語対応後は"ja"（に変更するかも→日本語ベースの多言語対応の予定）
      moment.updateLocale("en", {
        weekdatsShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]
      })
      const M = moment(date).format("M")
      const D = moment(date).format("D")
      const W = moment(date).format("ddd")
      return `${M}-${D}-${W}`
    },
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
    _arrayHours() {
      let i = 0
      //TODO: refactoringする | hourPullDownとか
      for (i; i <= 24; i++) {
        this.time.hours.push(i)
      }
    },
    _arrayMinutes() {
      let i = 0
      //TODO: refactoringする | hourPullDownとか
      for (i; i <= 59; i++) {
        this.time.minutes.push(i)
        this.time.seconds.push(i)
      }
    }
  },
  computed: {
    swatchStyle() {
      return {
        backgroundColor: this.newCategory.color,
        cursor: "pointer",
        height: "30px",
        width: "30px",
        borderRadius: this.menu.newTimerColor ? "50%" : "4px",
        transition: "border-radius 200ms ease-in-out"
      }
    }
  },
  watch: {
    //editTimer.stopped_at計算用
    "editTimer.time": {
      handler: function(val, oldVal) {
        this.editTimer.stopped_at = moment(this.editTimer.started_at)
          .add({
            h: this.editTimer.time.hours,
            m: this.editTimer.time.minutes,
            s: this.editTimer.time.seconds
          })
          .toDate()
      },
      deep: true
    },
    "editTimer.started_at": {
      handler: function(val, oldVal) {
        this.editTimer.stopped_at = moment(this.editTimer.started_at)
          .add({
            h: this.editTimer.time.hours,
            m: this.editTimer.time.minutes,
            s: this.editTimer.time.seconds
          })
          .toDate();
      }
    },
    //saveTimer.stopped_at計算用
    "saveTimer.time": {
      handler: function(val, oldVal) {
        this.saveTimer.stopped_at = moment(this.saveTimer.started_at)
          .add({
            h: this.saveTimer.time.hours,
            m: this.saveTimer.time.minutes,
            s: this.saveTimer.time.seconds
          })
          .toDate();
      },
      deep: true
    },
    "saveTimer.started_at": {
      handler: function(val, oldVal) {
        this.saveTimer.stopped_at = moment(this.saveTimer.started_at)
          .add({
            h: this.saveTimer.time.hours,
            m: this.saveTimer.time.minutes,
            s: this.saveTimer.time.seconds
          })
          .toDate();
      }
    },
    timers: {
      handler: function() {
        this.timersArray = [[]];
        if (this.timers[0] !== null) {
          // timers[0]をtimesArray[0]に格納
          this.timersArray[0].push(this.timers[0]);
          //timersでループを回す
          for (let i = 0; i < this.timers.length - 1; i++) {
            // timers[1]のstarted_atの年月日がtimers[0]のstarted_atの年月日と同じ場合、timers[1]を配列に格納
            if (
              moment(this.timers[i].started_at).isSame(
                moment(this.timers[i + 1].started_at),
                "day"
              )
            ) {
              this.timersArray[this.timersArray.length - 1].push(
                this.timers[i + 1]
              );
            } else {
              // 異なる場合は新たに配列を作成する
              this.timersArray.push([]);
              this.timersArray[this.timersArray.length - 1].push(
                this.timers[i + 1]
              );
            }
          }
        }
      }
    },
    "dialog.newTimer": {
      handler: function(val) {
        if(!this.dialog.newTimer) {
          this.$refs.newTimerForm.resetValidation()
        }
      },
      deep: true
    },
    "dialog.saveTimer": {
      handler: function(val) {
        if (!this.dialog.saveTimer) {
          this.$refs.saveTimerForm.resetValidation()
        }
      },
      deep: true
    },
    timersArray: {
        handler: function() {
            this.loading = false
      }
    }
  }
}
</script>

<style></style>
