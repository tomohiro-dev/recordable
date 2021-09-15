/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

import Vue from 'vue'
import router from './router'
import App from './App.vue'

import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'
import VueTheMask from 'vue-the-mask'

window.Vue = require('vue')
Vue.use(Vuetify)
Vue.use(VueTheMask)

new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App />',
  vuetify: new Vuetify()
})
