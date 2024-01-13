export const state = () => ({});

export const mutations = {};

export const getters = {};

export const actions = {

  getChat({ commit, dispatch, getters }, { id } = {}) {
    return this.$axios
      .$get("/chat/room/" + id)
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  store({ commit, dispatch, getters }, { form } = {}) {
    return this.$axios
      .$post("/chat", form)
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },
  readChat({ commit, dispatch, getters }, { id } = {}) {
    return this.$axios
      .$post("/chat/" + id)
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },
};
