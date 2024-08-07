import { createStore } from "vuex";

const store = createStore({
  state: {
    user: JSON.parse(localStorage.getItem("user")) || null,
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
      localStorage.setItem("user", JSON.stringify(user)); // Save to local storage
    },
    clearUser(state) {
      state.user = null;
      localStorage.removeItem("user"); // Remove from local storage
    },
  },
  actions: {
    logIn({ commit }, user) {
      commit("setUser", user);
    },
    logOut({ commit }) {
      commit("clearUser");
    },
  },
  getters: {
    isAuthenticated: (state) => !!state.user,
    getUser: (state) => state.user,
  },
});

export default store;
