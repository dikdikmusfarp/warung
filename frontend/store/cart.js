export const state = () => ({});

export const mutations = {};

export const getters = {};

export const actions = {

  // index({ commit, dispatch, getters }, { params } = {}) {
  //   return this.$axios
  //     .$get("/transaction", { params: params })
  //     .then((res) => {
  //       return Promise.resolve(res);
  //     })
  //     .catch(function (error) {
  //       return Promise.reject(error);
  //     });
  // },

  getCart({ commit, dispatch, getters }, { id } = {}) {
    return this.$axios
      .$get("/cart/" + id)
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  getListCart({ commit, dispatch, getters }, { } = {}) {
    return this.$axios
      .$get("cart/show")
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  store({ commit, dispatch, getters }, { form } = {}) {
    return this.$axios
      .$post("/cart", form)
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  update({ commit, dispatch, getters }, { id, form } = {}) {
    return this.$axios
      .$post("/cart/" + id, form)
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },

  deleteProduct({ commit, dispatch, getters }, { id } = {}) {
    return this.$axios
      .$delete("/cart/" + id)
      .then((res) => {
        return Promise.resolve(res);
      })
      .catch(function (error) {
        return Promise.reject(error);
      });
  },
};
