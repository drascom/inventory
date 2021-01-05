import userApi from '@/api/user.js';
const END_POINT = '/api/cockpit/authUser';

const state = () => {
    return {
        isLogin: false,
        userData: {},
    };
};
const getters = {
    userData: (state) => {
        return state.userData;
    },
    loggedIn: (state) => {
        return state.isLogin;
    },
};

const actions = {
    login ({ commit, dispatch }, payload) {
        return userApi.login( payload ).then( async ( response ) => {
            commit('SET_USER', response.data);
            localStorage.setItem('user', JSON.stringify(response.data));
            localStorage.setItem('api_key', JSON.stringify(response.data.api_key));
            return response.data;

        });
    },
};
const mutations = {
    SET_USER (state, payload) {
        if(payload.active) {
            state.isLogin = true;
        }
        state.userData = payload;
    },
    SET_MESSAGES (state, payload) {
        state.messages = payload;
    },
    handleSignIn (state) {
        state.isLogin = true;
    },
    handleSignOut (state) {
        localStorage.removeItem('api_key');
        localStorage.removeItem('user');
        state.isLogin = false;
    },

};
export default {
    namespaced: true,
    name:'user',
    state,
    getters,
    actions,
    mutations
};
