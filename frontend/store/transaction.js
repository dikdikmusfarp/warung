export const state = () => ({});

export const mutations = {};

export const getters = {};

export const actions = {

  index({ commit, dispatch, getters }, { params } = {}) {
    return this.$axios
      .$get("/transaction", { params: params })
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  getTransaction({ commit, dispatch, getters }, { id } = {}) {
    return this.$axios
      .$get("/transaction/" + id)
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  // store({ commit, dispatch, getters }, { form } = {}) {
  //   return this.$axios
  //     .$post("/product", form)
  //     .then((res) => {
  //       return Promise.resolve(res);
  //     })
  //     .catch(function (error) {
  //       return Promise.reject(error);
  //     });
  // },

  // update({ commit, dispatch, getters }, { id, form } = {}) {
  //   return this.$axios
  //     .$post("/product/" + id, form)
  //     .then((res) => {
  //       return Promise.resolve(res);
  //     })
  //     .catch(function (error) {
  //       return Promise.reject(error);
  //     });
  // },

  // deleteProduct({ commit, dispatch, getters }, { id } = {}) {
  //   return this.$axios
  //     .$delete("/product/" + id)
  //     .then((res) => {
  //       return Promise.resolve(res);
  //     })
  //     .catch(function (error) {
  //       return Promise.reject(error);
  //     });
  // },
};
