import Vue from 'vue'
import Router from 'vue-router'

import Login from './views/Login.vue'
import Register from './views/Register.vue'
import Dashboard from './views/Dashboard.vue'
import Timer from './views/Timer.vue'
import NavDrawer from './components/NavDrawer.vue' //TODO:画面作成後削除

import store from './store'

Vue.use(Router)

// TODO: 画面作成時はbeforeEnterのコメントアウトする(timer)
export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/navdrawer',
      component: NavDrawer
    },
    {
      path: '/timer',
      // path: '/', //本番
      component: Timer
      // beforeEnter(to, from, next) {
      //   if (!store.getters['auth/check']) {
      //     next('/login') // path: '/'
      //     // next('/') // path: '/timer'
      //   } else {
      //     next()
      //   }
      // }
      // 画面開発中はコメントアウト
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
        if (store.getters['auth/check']) {
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
