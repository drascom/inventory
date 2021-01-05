import 'babel-polyfill';
import Vue from 'vue';
import App from './App.vue';
import store from './plugins/store';
import i18n from './plugins/i18n';
import vuetify from './plugins/vuetify';
import VueHtmlToPaper from './plugins/html2paper';
import './plugins/moment';
import router from './router';
import './router/auth';
import VCharts from 'v-charts';
import '../src/styles/index.scss';
import configPlugin from '../mysettings';

import IdleVue from 'idle-vue';

const eventsHub = new Vue();
Vue.use(IdleVue, {
  eventEmitter: eventsHub,
  store,
  idleTime: 300000,
  startAtIdle: false,
  events:['mousemove', 'keydown', 'mousedown', 'touchstart','scroll']
});

Vue.use( configPlugin );
Vue.use(VCharts);


Vue.config.productionTip = false;
new Vue({
    router,
    i18n,
    store,
    vuetify,
    render: (h) => h(App)
}).$mount('#app');
// vm.$nextTick(function () {
//     console.log('update');
// });
