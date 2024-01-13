export const state = () => ({
  token : JSON.parse(localStorage.getItem('auth.token')) ?? null,
  user : JSON.parse(localStorage.getItem('auth.user')) ?? null,
});

export const mutations = {
  SET_TOKEN(state, newValue) {
    state.token = newValue
    saveState('auth.token', newValue)
  },
  SET_USER(state, newValue) {
    state.user = newValue
    saveState('auth.user', newValue)
  },
};

export const getters = {};

export const actions = {

  register({ commit, dispatch, getters }, { form } = {}) {
    return this.$axios
      .$post("/register", form)
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  login({ commit, dispatch, getters }, { form } = {}) {
    return this.$axios
      .$post("/login", form)
      .then((res) => {
        commit('SET_TOKEN', res.access_token)
        commit('SET_USER', res.user)
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  getUser({ commit, dispatch, getters }, { } = {}) {
    return this.$axios
      .$get("/user")
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  logout({ commit, dispatch, getters }, { } = {}) {
    return this.$axios
      .$post("/logout",)
      .then((res) => {
        commit('SET_TOKEN', null)
        commit('SET_USER', null)
        localStorage.removeItem('auth.token')
        localStorage.removeItem('auth.user')
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  status({ commit, dispatch, getters }, { form } = {}) {
    return this.$axios
      .$post("/status/", form)
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },
}

// ===
// Private helpers
// ===

function saveState(key, state) {
  if (process.browser) {
    localStorage.setItem(key, JSON.stringify(state))
  }
}
