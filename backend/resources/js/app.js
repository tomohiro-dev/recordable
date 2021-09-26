/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// ※削除厳禁
// backend/resources/js/bootstrap.jsをmoduleとして読み込んで実行
require('./bootstrap')

import './bootstrap'
import Vue from 'vue'
import VueRouter from 'vue-router'
import router from './router'
import App from './App.vue'

import store from './store'

import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'
import VueTheMask from 'vue-the-mask'

// window.Vue = require('vue')
window.Vue = Vue
Vue.use(Vuetify)
Vue.use(VueTheMask)
Vue.use(VueRouter)

const createApp = async () => {
  await store.dispatch('auth/currentUser')

  new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App />',
    vuetify: new Vuetify()
  })
}

createApp()
