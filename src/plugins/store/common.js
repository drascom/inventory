import apiModule from '@/api/index.js';

// initial state
const state = () => {
    return {
        items: [],
        item: {},
        messages: [],
        total: 0
    };
};
// initial getters
const getters = {
    CriticalItems: ( state ) => {
        return state.items.filter( ( item ) => {return item.kritik_miktar >= item.stok;} );
    },
    CriticalItemsCount: (state) => {
        return state.items.filter( ( item ) => {return item.kritik_miktar >= item.stok; }).length;
    },
    items: (state) => {
        return state.items.sort((a, b) => {
            return a['name'] < b['name'];
        });
    },
    item: ( state ) => {
        return state.item;
    },
    total: ( state ) => {
        return state.total;
    },
    messages: (state) => state.messages
};

// initial actions
const actions = {
    getAllItems ({ commit }, payload) {
        return apiModule[payload.name].all(payload).then((res) => {
            commit('SET_ITEMS', res.data.entries);
            commit('SET_TOTAL', res.data.total);
            return res;
        });
    },

    save ({ commit, dispatch }, payload) {
        return apiModule[payload.name].save(payload).then((res) => {
            dispatch('getAllItems', { name: payload.name });
            return res;
        });
    },
    saveSingle ({ commit, dispatch }, payload) {
        return apiModule[payload.name].saveSingle(payload).then((res) => {
            return res;
        });
    },

    updateAvatar ({ commit, dispatch }, payload) {
        return apiModule[payload.name].updateAvatar(payload.data).then((res) => {
            dispatch('getAllItems', { name: payload.name });
            return res.data.data;
        });
    },
    delete ({ commit, dispatch }, payload) {
        console.log('store payload',payload);
        return apiModule[payload.name].deleteItem(payload.data).then((res) => {
            console.log(res);
            dispatch( 'getAllItems', {name: payload.name} );
            return res;
         });
    },
    deleteAll({ commit, dispatch }, payload) {
        Promise.all(
            payload.data.map((item) => {
                return apiModule[payload.name].deleteItem(item.id);
            })
        ).finally( () => {
            dispatch('getAllItems', { name: payload.name });
            return true;
        });
    },
};
// initial mutation
const mutations = {
    SET_ITEMS (state, payload) {
        state.items = payload;
    },
    SET_ITEM (state, payload) {
        state.item = payload.data;
    },
    SET_TOTAL (state, payload) {
        state.total = payload;
    },
    SET_MESSAGES (state, payload) {
        if(payload.data.messages){
            state.messages = payload;
        }
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
