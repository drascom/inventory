<template>
<div class="page_root" v-resize="onResize">


    <!--菜单开始-->
    <v-navigation-drawer
        v-model="menuDrawer"
        :mini-variant="miniVariant"
        disable-resize-watcher
        mini-variant-width="74"
        class="page_drawer"
        absolute
        dark
        style="z-index: 4;">
        <v-img
            v-slot:img
            :src="sideBarBackground"
            gradient="rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)" />

        <v-list
            nav
            class="py-0"
            style="margin-top: 20px">
            <v-list-item>
                <v-list-item-avatar>
                    <v-icon>mdi-account</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title v-text="userData.name"></v-list-item-title>

                    <v-list-item-subtitle v-text="userData.group"></v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-action>
                    <v-menu>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                icon
                                v-bind="attrs"
                                v-on="on">
                                <v-icon color="grey lighten-1">mdi-drag-horizontal</v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item @click="handleSignOut()" >
                                <v-list-item-title>  <v-icon  color="grey lighten-1">mdi-cancel</v-icon> Çıkış</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>

                </v-list-item-action>
            </v-list-item>
            <template v-for="(item, index) in menus">
                <template v-if="item.visible && item.children && item.children.length > 1 ">
                    <v-list-group
                        :key="item.path"
                        :prepend-icon="item.meta.icon"
                        :group="item.name"
                        active-class="v_list_group_active"
                        :value="checkMenuGroupValue(item.path)">
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title class="text-subtitle-1">{{$t("header." + item.meta.title)}}</v-list-item-title>
                            </v-list-item-content>
                        </template>
                        <template v-for="(child, key) in item.children">
                            <v-list-item
                                v-if="child.visible"
                                class="pl-5"
                                :key="key"
                                :to="{ name: child.name }"
                                active-class="primary">
                                <v-icon size="20">mdi-arrow-right-bold-box-outline</v-icon>

                                <v-list-item-content class="ml-5 text-body-2">
                                    <v-list-item-title>{{child.meta.title}}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </template>

                    </v-list-group>
                </template>
                <template v-else>
                    <v-list-item
                        v-if="item.visible"
                        :key="index"
                        :to="{ path: item.path }"
                        active-class="primary">
                        <v-list-item-icon>
                            <v-icon>{{item.meta.icon}}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{$t("header." + item.meta.title)}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
            </template>
        </v-list>
    </v-navigation-drawer>
    <!--菜单结束-->
    <!--主体开始-->
    <v-main class="page_right_content" :class="{miniVariant: miniVariant, darkMode: darkMode}">
        <v-toolbar
            absolute
            class="header"
            flat>
            <!--处理显示导航菜单-->
            <v-btn
                fab
                small
                style="margin-right:18px;"
                @click="handleMenuDrawer"
                v-if="!menuDrawer">
                <v-icon v-if="menuDrawer">mdi-drag</v-icon>
                <v-icon v-else>mdi-drag-horizontal</v-icon>
            </v-btn>
            <!--处理导航菜单mini样式-->
            <v-btn
                fab
                small
                style="margin-right:18px;"
                @click="handleMiniMenu"
                v-else>
                <v-icon v-if="!miniVariant">mdi-drag</v-icon>
                <v-icon v-else>mdi-drag-horizontal</v-icon>
            </v-btn>

            <v-toolbar-title style="text-transform: capitalize;">{{pageTitle}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu
                v-model="noticeVisible"
                :close-on-content-click="false"
                max-width="90%"
                offset-y
                open-on-hover>
                <template v-slot:activator="{ on }">
                    <v-badge
                        :content="CriticalItemCount"
                        :value="CriticalItemCount"
                        :offset-x="30"
                        :offset-y="20"
                        overlap
                        bordered>
                        <v-btn text v-on="on">
                            <v-icon>mdi-bell</v-icon>
                        </v-btn>
                    </v-badge>
                </template>

                <v-card>
                    <v-list three-line>
                        <v-list-item to="/urunler/kritik">
                            <v-list-item-icon>
                                <v-icon color="indigo">
                                    mdi-bell
                                </v-icon>
                            </v-list-item-icon>
                            <v-list-item-content class="text-caption">
                                <v-list-item-title>Sipariş Çağrınız var.</v-list-item-title>
                                <v-list-item-subtitle>{{CriticalItemCount}} ürün kritik seviyede...</v-list-item-subtitle>
                            </v-list-item-content>
                            <v-list-item-action>
                                <v-btn small color="orange">incele</v-btn>
                            </v-list-item-action>
                        </v-list-item>

                    </v-list>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            text
                            @click="noticeVisible = false">Clear</v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>
            <v-btn
                text
                @click="fullScreen"
                class="min_hide">
                <v-icon>mdi-arrow-expand-all</v-icon>
            </v-btn>
            <v-menu
                bottom
                :close-on-content-click="false"
                :offset-y="true"
                open-on-hover>
                <template v-slot:activator="{ on }">
                    <v-btn text v-on="on">
                        <v-icon>mdi-cog</v-icon>
                    </v-btn>
                </template>
                <v-card style="min-width:220px; padding-bottom:20px">
                    <v-subheader>Color Option</v-subheader>
                    <v-list subheader>
                        <template v-for="(item, key) in colors">
                            <v-list-item
                                :key="key + 2"
                                :class="{'v-list-item--active': item.active}"
                                @click="handleChangeColor(item.color, key)">
                                <v-list-item-avatar :color="item.color" :size="25"></v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-subtitle>{{item.name}}</v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                        </template>
                    </v-list>
                    <v-subheader>Language</v-subheader>
                    <v-radio-group
                        v-model="Language"
                        @change="handleCutover"
                        style="margin: 0 20px;">
                        <v-radio
                            label="English"
                            value="en_US"
                            color="primary"></v-radio>
                        <v-radio
                            label="Russian"
                            value="ru_RU"
                            color="secondary"></v-radio>
                        <v-radio
                            label="Turkish"
                            value="tr_TR"
                            color="info"></v-radio>
                    </v-radio-group>
                    <v-subheader>Dark Mode</v-subheader>
                    <v-switch
                        v-model="darkMode"
                        @change="onDarkModeChange"
                        style="margin-left: 20px"></v-switch>
                </v-card>
            </v-menu>

        </v-toolbar>
        <div class="zwf"></div>
        <error-component /> <idle />
        <div>
            <transition name="fade-transform" mode="out-in">
                <keep-alive :key="curTime">
                    <router-view v-if="$route.meta.keepAlive" :key="curTime" />
                </keep-alive>
            </transition>
            <transition name="fade-transform" mode="out-in">
                <router-view v-if="!$route.meta.keepAlive" :key="curTime"></router-view>
            </transition>
        </div>

    </v-main>
    <!--主体结束-->
</div>
</template>

<script>
import idle from '@/views/components/idle';
import commonJS from '@/plugins/store/common.js';
const errorComponent = () => import('@/views/components/errorComponent.vue');
export default {
    name: 'Layout',
    components: {
        errorComponent,
                idle

    },
    data() {
        return {
            apiUrl: process.env.NODE_ENV === 'development' ?
                process.env.VUE_APP_API_URL : process.env.VUE_APP_WEB_API_URL,
            numberOfMessages: 0,
            sideBarBackground: require('../../assets/sidebar-1.jpg'),
            tx: require('../../assets/wx.png'),
            menuDrawer: true,
            expandOnHover: false,
            noticeVisible: false,
            windowWidth: window.innerWidth,
            bg: {
                'src': 'assets/sidebar-1.jpg',
                'linear-gradient': 'to top right, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)',
            },
            miniVariant: false,
            Language: 'tr_TR',
            settingsVisible: false,
            isFullScreen: false,
            name: '',
            desc: '',
            token: '',
            news: [],
            admins: [
                ['Management', 'people_outline'],
                ['Settings', 'settings'],
            ],
            cruds: [
                ['Create', 'add'],
                ['Read', 'insert_drive_file'],
                ['Update', 'update'],
                ['Delete', 'delete'],
            ],
            items: [{
                    header: 'Today'
                },
                {
                    avatar: 'https://cdn.vuetifyjs.com/images/lists/4.jpg',
                    title: 'Birthday gift',
                    subtitle: '<span class=\'text--primary\'>Trevor Hansen</span> &mdash; Have any ideas about what we should get Heidi for her birthday?',
                },
                {
                    divider: true,
                    inset: true
                },
                {
                    avatar: 'https://cdn.vuetifyjs.com/images/lists/5.jpg',
                    title: 'Recipe to try',
                    subtitle: '<span class=\'text--primary\'>Britta Holt</span> &mdash; We should eat this: Grate, Squash, Corn, and tomatillo Tacos.',
                },
            ],
        };
    },
    watch: {
        windowWidth(newWidth, oldWidth) {
            if (newWidth <= 768) {
                this.$store.commit('settings/SET_MOBILE', true);
                this.menuDrawer = false;
            } else {
                this.$store.commit('settings/SET_MOBILE', false);
                this.menuDrawer = true;

            }
        }
    },
    computed: {
        userData() {
            return JSON.parse(localStorage.getItem('user'));
        },
        userAvatar() {
            return JSON.parse(localStorage.getItem('avatar'));
        },
        CriticalItemCount() {
            return this.getCount();
        },
        loadPaths() {
            return this.$route.path.split('/');
        },
        isMobile() {
            return this.$store.state.settings.isMobile;
        },
        curTime() {
            return this.$store.state.settings.curTime;
        },
        pageTitle() {
            return this.$route.meta.title;
        },
        locale(key) {
            return this.$t('header.' + key);
        },
        darkMode: {
            get: function () {
                return this.$store.state.settings.darkMode;
            },
            set: function (newValue) {
                this.$store.state.settings.darkMode = newValue;
            },
        },
        menus() {
            const {
                options
            } = this.$router;
            return options.routes;
        },
        colors() {
            return this.$store.state.settings.colors;
        },
    },
    mounted() {
        this.numberOfMessages = this.getCount();
        window.onresize = () => {
            this.windowWidth = window.innerWidth;
        };
    },
    beforeCreate() {
        const myArray = ['products', 'movements', 'companies', 'todo']
        myArray.map((value, index) => {
            if (!(this.$store && this.$store.state && this.$store.state[value])) {
                console.log(value + ' modülü yaratılıyor.');
                this.$store.registerModule(value, commonJS);
            }
        });
    },
    created() {
        // const myArray = ['products', 'movements', 'companies','todo'];
        // myArray.map((value, index) => {
        //      this.$store.dispatch(`${value}/getAllItems`, {
        //         name: value,
        //         query: ''
        //     });
        // });
    },
    beforeDestroy() {
        if (typeof window !== 'undefined') {
            window.removeEventListener('resize', this.onResize, {
                passive: true,
            });
        }
    },
    methods: {
        getCount() {
            return this.$store.getters['products/CriticalItemsCount'];
        },
        onResize() {
            this.windowHeight = window.innerHeight;
        },
        onDarkModeChange(val) {
            this.$vuetify.theme.dark = val;
            this.$store.commit('settings/handleDarkMode', val);
        },
        checkMenuGroupValue(path) {
            const arr = path.split('/');
            if (!arr[1]) {
                return false;
            }
            return this.loadPaths.includes(arr[1]);
        },
        handleMenuDrawer() {
            this.menuDrawer = !this.menuDrawer;
        },
        handleMiniMenu() {
            this.miniVariant = !this.miniVariant;
        },
        onAxios() {
            // const data = {
            //     title: this.name,
            //     desc: this.desc
            // };
            // axios.get(url,AxiosRequestConfig).then((res) => {
            //     console.log(res);
            // });
            // const token = this.token;
            // axios.request({
            //     url: '/news/create',
            //     method: 'post',
            //     baseURL: 'http://127.0.0.1:7001',
            //     data: data,
            //     headers: {
            //         'x-csrf-token': token
            //     }
            // });
            window.open('https://github.com/Groundhog-Chen/vue-material-admin');
        },
        tmyx() {
            this.$store.commit('handleChangeMlmlh');
        },
        tmlx() {
            this.$store.commit('handleChangeYmlmlh');
        },
        handleChangeColor(color, key) {
            this.$vuetify.theme.themes.light.primary = color;
            this.$store.commit('settings/handleSetColor', key);
            this.$vuetify.theme.dark = false;
            this.$store.commit('settings/handleDarkMode', false);
        },
        handleCutover(val) {
            this.$i18n.locale = val;
        },
        handleSignOut() {
            this.$store.commit('user/handleSignOut');
            setTimeout(() => {
                this.$router.push('/login');
            }, 300);
        },
        fullScreen() {
            const el = document.documentElement;
            const rfs =
                el.requestFullScreen ||
                el.webkitRequestFullScreen ||
                el.mozRequestFullScreen ||
                el.msRequestFullscreen;
            if (typeof rfs != 'undefined' && rfs) {
                rfs.call(el);
            }
            this.isFullScreen = true;
            return;
        },
    },

};
</script>
