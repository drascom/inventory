import Vue from 'vue';
import Vuex from 'vuex';


Vue.use(Vuex);
function loadStores () {
    const context = require.context( './modules', true, /\.js$/ );
    return context.keys()
        .map( context ) // import module
        .map( (m) => m.default ); // get `default` export from each resolved module
}

const resourceModules = {};
loadStores().forEach( ( resource ) => {
   resourceModules[ resource.name ] = resource;
   if(resource.name !='user'){

    }
} );


export default new Vuex.Store({
    modules: resourceModules,
});
