import moment from 'moment'
import OK from '../const'

const state = {
  timers: [],
  active: false
}

const getters = {
  // TODO: 使わない場合は削除
}

const mutations = {
  SET_TIMERS(state, timers) {
    state.timers = timers
  },
  //TODO: 記録中のmutationsを追加
  SET_ACTIVE_TIMER(state, bool) {
    state.active = bool
  }
}

const actions = {
  async fetchTimers(context) {
    const response = await axios.get('api/timers')

    //もし通信に失敗したら、エラーをcommitする
    //TODO: http通信をするためのコードが必要
    if(response !== OK) {
      store.commit(errorCode, response.data)

      return false
    }
    context.commit('SET_TIMERS', response.data)
  },
  fetchActive(context, bool) {
    context.commit('SET_ACTIVE_TIMER', bool)

    return false
  }

},

export default {
  namespaced: true,
  state,
  getters, //TODO: 使わない場合は削除
  mutations,
  actions
}
