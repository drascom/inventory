export default {
    namespaced: true,
    name: 'connection',
    state: {
        // snackbars: []
        response: {
            status: false,
            code: null,
            message: null
        }
    },
    mutations: {
        SET_CONNECTION (state, payload) {
            state.response = payload;
        },
    },
    actions: {
        setConnection ({ commit }, payload) {
            // payload.timeout = payload.timeout || 1000;
            commit('SET_CONNECTION', payload);
        },
    },
    getters: {},
    modules: {}
};
