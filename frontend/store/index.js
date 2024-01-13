// store/index.js
export const state = () => ({
  onlineData: null
})

export const mutations = {
  setOnlineData(state, data) {
    state.onlineData = data
  }
}
