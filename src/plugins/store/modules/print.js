export default {
    namespaced: true,
    name:'printer',
    state: {
        // snackbars: []
        PrintStuff:''
    },
    mutations: {
        SET_PRINTSTUFF ( state, PrintStuff ) {
            // state.snackbars = state.snackbars.concat( snackbar );
            state.PrintStuff = PrintStuff;
        },
    },
    actions: {
        fetchPrintStuff ( { commit }, payload ) {
            commit( 'SET_PRINTSTUFF', payload );
        },
    },
    getters: {},
    modules: {}
};
