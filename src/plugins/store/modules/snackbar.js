export default {
    namespaced: true,
    name:'snackbar',
    state: {
        // snackbars: []
        snackbar:{}
    },
    mutations: {
        SET_SNACKBAR ( state, snackbar ) {
            // state.snackbars = state.snackbars.concat( snackbar );
            state.snackbar = snackbar;
        },
    },
    actions: {
        setSnackbar ( { commit }, snackbar ) {
            snackbar.timeout = snackbar.timeout ||2000;
            snackbar.right = true;
            snackbar.color = snackbar.color || 'success';
            commit( 'SET_SNACKBAR', snackbar );
        },
    },
    getters: {},
    modules: {}
};
