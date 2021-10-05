import { OK } from '../util'

const state = {
  timers: [],
  active: false
}

const mutations = {
  SET_TIMERS(state, timers) {
    state.timers = timers
  },
  SET_ACTIVETIMER(state, bool) {
    state.active = bool
  }
}

const actions = {
  async fetchTimers(context) {
    const response = await axios.get('api/timers')

    //もし通信に失敗したら、エラーをcommitする
    //TODO: http通信をするためのコードが必要
    if (response !== OK) {
      this.$store.commit('error/SET_CODE', response.data)

      return false
    }
    context.commit('SET_TIMERS', response.data)
  },
  fetchActive(context, bool) {
    context.commit('SET_ACTIVETIMER', bool)

    return false
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
