export default {
    namespaced: true,
    name:'tabledata',
    state: {
        tableData:null,
        tableHeaders:null
    },
    mutations: {
        SET_TABLE_DATA ( state, payload ) {
            // state.snackbars = state.snackbars.concat( snackbar );
            state.tableData = payload;
        },
        SET_TABLE_HEADERS ( state, payload ) {
            // state.snackbars = state.snackbars.concat( snackbar );
            state.tableHeaders = payload;
        },
    },
    actions: {
        setData( { commit }, payload ) {
            commit( 'SET_TABLE_DATA', payload );
        },
        setHeaders ( { commit }, payload ) {
            commit( 'SET_TABLE_DATA', payload );
        },
    },
    getters: {},
    modules: {}
};
