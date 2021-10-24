import Vue from 'vue'
import Router from 'vue-router'

import Login from './views/Login.vue'
import Register from './views/Register.vue'
import Dashboard from './views/Dashboard.vue'
import Timer from './views/Timer.vue'

import store from './store'

Vue.use(Router)

//※beforeEnter内のto, fromは削除しないこと！（予期せぬエラー発生）
export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      component: Timer,
      beforeEnter(to, from, next) {
        if (!store.getters['auth/check']) {
          next('/login')
        } else {
          next()
        }
      }
    },
    {
      path: '/login',
      component: Login,
      beforeEnter(to, from, next) {
        if (store.getters['auth/check']) {
          next('/')
        } else {
          next()
        }
      }
    },
    {
      path: '/register',
      component: Register,
      beforeEnter(to, from, next) {
        if (store.getters['auth/check']) {
          next('/')
        } else {
          next()
        }
      }
    },
    {
      path: '/dashboard',
      component: Dashboard,
      beforeEnter(to, from, next) {
        if (!store.getters['auth/check']) {
          next('/login')
        } else {
          next()
        }
      }
    },
    {
      path: '*',
      redirect: '/login'
    }
  ]
})
