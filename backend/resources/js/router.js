import Vue from 'vue'
import VueRouter from 'vue-router'

import Login from './views/Login.vue'
import Register from './views/Register.vue'
import Dashboard from './views/Dashboard.vue'
import Timer from './views/Timer.vue'

import store from './store'

Vue.use(VueRouter)

export default new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/login',
      component: Login,
      beforeEnter(to, from, next) {
        //処理を追加
        //遷移しない場合は今開いている画面にとどまる
      }
    },
    {
      path: '/register',
      component: Register,
      beforeEnter(to, from, next) {
        //処理を追加
      }
    },
    {
      path: '/dashboard',
      component: Dashboard,
      beforeEnter(to, from, next) {
        //処理を追加
      }
    },
    {
      path: '/timer',
      component: Timer,
      beforeEnter(to, from, next) {
        //処理を追加
      }
    },
    {
      path: '*',
      redirect: '/login'
    }
  ]
})
