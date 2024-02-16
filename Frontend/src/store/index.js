
import { createStore, mapGetters } from 'vuex';

export default createStore({
  
  state: {
    token: localStorage.getItem('token') || null,
    user: JSON.parse(localStorage.getItem('user')) || null,
    switchTheme: JSON.parse(localStorage.getItem('switchTheme')) || null,
  },
  mutations: {
    setToken(state, token) {
      state.token = token;
      localStorage.setItem('token', token);
    },
    setUser(state, user) {
      state.user = user;
      localStorage.setItem('user', JSON.stringify(user));
    },
    setSwitchTheme(state, switchTheme) {
      state.switchTheme = switchTheme;
      localStorage.setItem('switchTheme', JSON.stringify(switchTheme));
    },
    clearToken(state) {
      state.token = null;
      state.user = null;
      state.switchTheme = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      localStorage.removeItem('user');
    },
  },
  actions: {
    setToken({ commit }, token) {
      commit('setToken', token);
    },
    setUser({ commit }, user) {
      commit('setUser', user);
    },
    setSwitchTheme({ commit }, switchTheme) {
      commit('setSwitchTheme', switchTheme);
    },
    clearToken({ commit }) {
      commit('clearToken');
    },
  },
  getters: {
    getToken: (state) => state.token,
    getUser: (state) => state.user,
    getSwitchTheme: (state) => state.switchTheme,
  },
});
