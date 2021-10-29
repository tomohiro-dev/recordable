import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../status'

const state = {
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
  registerErrorMessages: null
}

const getters = {
  check: (state) => !!state.user,
  username: (state) => (state.user ? state.user.name : '')
}

const mutations = {
  setUserLogin(state, user) {
    state.user = user
  },
  setApiStatus(state, status) {
    state.apiStatus = status
  },
  setLoginErrorMessages(state, messages) {
    state.loginErrorMessages = messages
  },
  setRegisterErrorMessages(state, messages) {
    state.registerErrorMessages = messages
  }
}

const actions = {
  async register(context, data) {
    context.commit('setApiStatus', null)
    const response = await axios
      .post('/api/register', data)

      .catch((err) => err.response || err)

    if (response.status === CREATED) {
      context.commit('setApiStatus', true)
      context.commit('setUserLogin', response.data)
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setRegisterErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, {
        root: true
      })
    }
  },

  async login(context, data) {
    context.commit('setApiStatus', null)
    const response = await axios
      .post('/api/login', data)

      .catch((err) => error.response || err)

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUserLogin', response.data)
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setLoginErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, {
        root: true
      })
    }
  },

  async logout(context) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/logout')

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUserLogin', null)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, {
      root: true
    })
  },

  async currentUser(context) {
    context.commit('setApiStatus', null)
    const response = await axios.get('/api/user')
    const user = response.data || null
    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUserLogin', user)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, {
      root: true
    })
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
