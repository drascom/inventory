import Vue from 'vue';
import Router from 'vue-router';
import errorsRouter from './errors';

import store from '../plugins/store/index';

import layout from '@/views/layout/layout.vue';
// import treeRoute from '@/views/layout/router.vue';

Vue.use(Router);
let router = new Router({
    base: process.env.environment === 'development' ? '/' : '/app/',
    mode: 'history',
    routes: [
        //首页
        {
            path: '/',
            redirect: '/login',
            name: 'index',
            visible: false,
            component: layout,
            meta: {
                title: 'home',
                keepAlive: false
            }
        },
        // 仪表盘
        {
            path: '/anasayfa',
            visible: true,
            component: layout,
            meta: {
                title: 'anasayfa',
                icon: 'mdi-home',
                keepAlive: false
            },
            children: [
            {
                path: '',
                name: 'anasayfa',
                visible: true,
                meta: {
                    title: 'anasayfa',
                    icon: 'mdi-home',
                    keepAlive: false
                },
                component: () => import( /* webpackChunkName: "dashboard" */ '@/views/dashboard/index.vue')
            }, ]
        },
        {
            path: '/urunler',
            visible: true,
            redirect: '/urunler/liste',
            component: layout,
            meta: {
                title: 'urunler',
                icon: 'mdi-format-list-checkbox',
                keepAlive: false
            },
            children: [
            {
                path: 'liste',
                name: 'liste',
                visible: true,
                props: { sayfa: 'products' },
                meta: {
                    title: 'Tüm Ürünler',
                    icon: 'mdi-account',
                    keepAlive: true
                },
                component: () => import( /* webpackChunkName: "productsList" */ '@/views/ornek/index.vue')
            },
            {
                path: 'kritik',
                name: 'kritik',
                visible: true,
                props: { sayfa: 'products' },
                meta: {
                    title: 'Azalan Ürünler',
                    icon: 'mdi-bell',
                    keepAlive: true
                },
                component: () => import( /* webpackChunkName: "productsList" */ '@/views/ornek/index.vue')
            }]
        },

        // 登录页面
        {
            path: '/stok-giris',
            visible: true,
            redirect: '/stok-giris/giris',
            component: layout,
            meta: {
                title: 'stokInOut',
                icon: 'mdi-message-plus-outline',
                keepAlive: false
            },
            children: [
                // {
                //     path: '1',
                //     name: 'stokGiris',
                //     visible: true,
                //     props: { tur: 'in' },
                //     meta: {
                //         title: 'Stok Girişi',
                //         icon: 'mdi-alpha-t',
                //         keepAlive: false
                //     },
                //     component: () => import( /* webpackChunkName: "myTask" */ '@/views/products/in_out.vue')
                // },
                {
                    path: 'giris',
                    name: 'stokGiris',
                    visible: true,
                    props: { tur: 'in' },
                    meta: {
                        title: 'Stok Girişi',
                        icon: 'mdi-timeline-plus-outline',
                        keepAlive: false
                    },
                    component: () => import( /* webpackChunkName: "myTask" */ '@/views/inOut/index.vue')
                },
                {
                    path: 'cikis',
                    name: 'stokCikis',
                    visible: true,
                    props: { tur: 'out' },
                    meta: {
                        title: 'Stok Çıkışı',
                        icon: 'mdi-timeline-minus-outline',
                        keepAlive: false
                    },
                    component: () => import( /* webpackChunkName: "myTask" */ '@/views/inOut/index.vue')
                }
            ]
        },
        {
            path: '/firmalar',
            visible: true,
            redirect: '/firmalar/',
            component: layout,
            meta: {
                title: 'firmalar',
                icon: 'mdi-bus-school',
                keepAlive: false
            },
            children: [
            {
                path: '',
                name: 'liste',
                visible: true,
                props: { sayfa: 'companies' },
                meta: {
                    title: 'Firma Listesi',
                    icon: 'mdi-bus-school',
                    keepAlive: true
                },
                component: () => import( /* webpackChunkName: "firmalarList" */ '@/views/ornek/index.vue')
            }]
        }, {
            path: '/movements',
            name: 'stokHareketi',
            component: layout,
            redirect: '/movements/',
            visible: true,
            meta: {
                title: 'stokHareketleri',
                icon: 'mdi-file-tree',
                keepAlive: false
            },
            children: [
            {
                path: '',
                name: 'stokHareketi',
                visible: true,
                meta: {
                    title: 'Stok İşlemleri',
                    icon: 'mdi-file-tree',
                    keepAlive: true
                },
                props: { sayfa: 'movements' },
                component: () => import( /* webpackChunkName: "ornek" */ '@/views/ornek/index.vue')
            }]
        },

        {
            path: '/login',
            name: 'login',
            visible: false,
            meta: {
                title: 'login',
                icon: 'mdi-fingerprint',
                keepAlive: false
            },
            component: () => import( /* webpackChunkName: "login" */ '@/views/login/index.vue')
        },
        { path: '*', redirect: '/anasayfa', hidden: true },
    ],
    // scrollBehavior(to, from, savedPosition) {
    //     if (savedPosition) {
    //         return savedPosition;
    //     } else {
    //         return { x: 0, y: 0 };
    //     }
    // }
});
router.beforeEach((to, from, next) => {
    next();
    if(to.name !== 'login') {
        if(!localStorage.getItem('user')) {
            next({
                name: 'login',
                params: { nextUrl: to.fullPath }
            });
        }
        next();
    }else{
        next();
    }


    //     if (to.name !== 'Login' && !store.state.user.isLogin) next({ name: 'Login' });
    //     else next({ name: 'anasayfa' });
});
export default router;
