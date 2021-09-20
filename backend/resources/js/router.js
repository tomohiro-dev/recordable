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
        if (store.getters['auth/check']) {
          next('/login')
        } else {
          next()
        }
      }
    },
    {
      // path: '/timer',
      // TODO: ↑開発用なので、サンプルユーザーでログインができるようになったら削除する
      path: '/',
      component: Timer,
      beforeEnter(to, from, next) {
        if (!store.getters['auth/check']) {
          next('/login')
          //next('/timer')
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
