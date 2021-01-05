import router from './index';
// import store from '../plugins/store';
import NProgress from 'nprogress'; // progress bar
import 'nprogress/nprogress.css'; // progress bar style
// const whiteList = ['/login', '/authredirect'];// 免登录白名单
NProgress.configure({ showSpinner: false });// NProgress configuration
router.beforeEach((to, from, next) => {
    NProgress.start();
    if (to.meta.keepAlive) {
        const toUrl = to.path.split('/');
        const fromUrl = from.path.split('/');
        if (toUrl[1] === fromUrl[1]) {
            to.meta.strategy = 'keep';
        } else {
            to.meta.strategy = 'refresh';
        }
    }
    next();
});
router.afterEach(() => {
    NProgress.done(); // 结束Progress
});
