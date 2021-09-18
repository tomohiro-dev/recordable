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
  }
  //TODO: 記録中のmutationsを追加
}

const actions = {
  async fetchTimers(context) {
    const response = await axios.get('api/timers')

    //もし通信に失敗したら、エラーをcommitする
    //TODO: http通信をするためのコードが必要
    if(response !== success) {
      store.commit(errorCode, response.data)
      return false
    }
    context.commit('SET_TIMERS', response.data)
  },

},

export default {
  namespaced: true,
  state,
  getters, //TODO: 使わない場合は削除
  mutations,
  actions
}
