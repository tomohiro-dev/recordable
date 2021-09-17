import axios from 'axios'

const state = {
  user: null,
  apiUserStatus: null,
  loginErrorMessages: null,
  registerErrorMessages: null
}

const getters = {
  check: state => !!state.user,
  username: state => (state.user ? state.user.name : '')
}

const mutations = {
  SET_USER(state, user) {
    state.user = user
  },
  SET_API_USER_STATUS(state, status) {
    state.apiUserStatus = status
  },
  SET_LOGIN_ERROR_MESSAGES(state, messages) {
    state.loginErrorMessages = messages
  },
  SET_REGISTER_ERROR_MESSAGES(state, messages) {
    state.registerErrorMessages = messages
  }
}

const actions = {
  async register(context, data) {
    context.commit('SET_API_USER_STATUS', null)
    const response = await axios.post('api/register', data)

    // データ内容の変更を初期値はfalseで指定する？
    //（初めからtrueだとデータ更新の反映がされないはず）
  },

  async login(context, data) {
    context.commit('SET_API_USER_STATUS', null)
    const response = await axios.post('api/login', data)

    //上に同じ
  },

  async logout(context) {
    context.commit('SET_API_USER_STATUS', null)
    const response = await axios.post('api/logout')

    // ログアウトなのでデータは関係ないけど上に同じ
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
