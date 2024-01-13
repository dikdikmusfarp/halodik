export const state = () => ({});

export const mutations = {};

export const getters = {};

export const actions = {

  getContact({ commit, dispatch, getters }, {params} = {}) {
    return this.$axios
      .$get("/user/list", { params: params })
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },
};
