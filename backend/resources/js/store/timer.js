import { OK } from '../status'

const state = {
  timers: [],
  active: false
}

const mutations = {
  setTimers(state, timers) {
    state.timers = timers
  },
  setTimerActive(state, bool) {
    state.active = bool
  }
}

const actions = {
  async fetchTimers(context) {
    const response = await axios.get('/api/timers')

    //もし通信に失敗したら、エラーをcommitする
    if (response.status !== OK) {
      this.$store.commit('error/setCode', response.data)

      return false
    }
    context.commit('setTimers', response.data)
  },
  fetchActive(context, bool) {
    context.commit('setTimerActive', bool)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
