import { createStore } from 'vuex';

const store = createStore({
    state: {
        user: null,
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        clearUser(state) {
            state.user = null;
        },
    },
    actions: {
        logIn({ commit }, user) {
            commit('setUser', user);
        },
        logOut({ commit }) {
            commit('clearUser');
        },
    },
    getters: {
        isAuthenticated: state => !!state.user,
        getUser: state => state.user,
    },
});

export default store;
