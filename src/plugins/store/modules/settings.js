export default {
    namespaced: true,
    name:'settings',
    state: {
        isIdle:false,
        isMobile: false,
        curTime: new Date().getTime(),
        darkMode: false,
        apiUrl: process.env.NODE_ENV === 'development' ?
            process.env.VUE_APP_API_URL :
            process.env.VUE_APP_WEB_API_URL,
        avatarPlaceholder: require('@/assets/usericon.jpg'),
        colors: [
            {
                name: 'blue',
                color: '#1890ff',
                active: false
            },
            {
                name: 'green',
                color: '#4CAF50',
                active: true
            },
            {
                name: 'pink',
                color: '#E91E63',
                active: false
            },
            {
                name: 'navy',
                color: '#323259',
                active: false
            }
        ],
    },
    mutations: {
        SET_MOBILE ( state, payload ) {
            // state.snackbars = state.snackbars.concat( snackbar );
            state.isMobile = payload;
        },
        handleSetTime ( state ) {
            state.curTime = new Date().getTime();
        },
        handleDarkMode ( state, status ) {
            state.darkMode = status;
        },
        handleSetColor ( state, val ) {
            for ( let i = 0; i < state.colors.length; i++ ) {
                if ( state.colors[ i ].active ) {
                    state.colors[ i ].active = false;
                }
                if ( state.colors[ i ].name === val || state.colors[ i ].color === val || i === val ) {
                    state.colors[ i ].active = true;
                }
            }
        },
    },
    actions: {
        setMobile ( { commit }, payload ) {
            commit( 'SET_MOBILE', payload );
        },
    },
    getters: {
        apiUrl: (state) => state.apiUrl,
        avatarPlaceholder: (state) => state.avatarPlaceholder
    },
    modules: {}
};
