<template>
<v-container class="dashboard_container">
    <v-row class="d-flex flex-row flex-sm-row-reverse flex-md-row-reverse">
        <v-col
            cols="12"
            sm="4"
            class="pb-8">
            <clock></clock>
        </v-col>
        <v-col cols="12" sm="8">
            <v-card
                class="d-flex flex-row mb-6"
                flat
                tile>
                <v-hover
                    v-for="(item,index) in menus"
                    :key="index"
                    v-slot:default="{ hover }"
                    close-delay="50"
                    class="">
                    <v-card
                        id="clock"
                        :to="item.link"
                        width="25%"
                        dark
                        :color="`blue-grey lighten-${index}`"
                        :elevation="hover ? 24 : 12"
                        :class="{ 'on-hover': hover }">
                        <v-responsive :aspect-ratio="4/4" class="clock d-flex  align-content-center justify-center align-center">
                            <v-card-text dark class="text-md-h6 text">
                                {{ item.name}}
                            </v-card-text>
                        </v-responsive>
                    </v-card>
                </v-hover>
            </v-card>
        </v-col>

    </v-row>
    <v-row class="card_box">
        <v-col clos="12" sm="8">

            <v-card flat tile>
                <to-do></to-do>
            </v-card>

        </v-col>
        <v-col cols="12" sm="4">
            <WeatherApp :class="period"></WeatherApp>

        </v-col>
    </v-row>
</v-container>
</template>

<script>
import WeatherApp from '../components/weather/WeatherApp';
import toDo from '../components/todo';
import clock from '../components/clock';

// import QRCode from 'qrcode';
export default {
    data() {
        return {
            menus: [{
                name: 'Tüm Ürünler',
                link: '/urunler/liste'
            }, {
                name: 'Azalanlar',
                link: '/urunler/kritik'
            }, {
                name: 'Stok Girişi',
                link: '/stok-giris'
            }, {
                name: 'Stok Çıkışı',
                link: '/stok-cikis'
            }],
            date: new Date,
            play: false, // 播放状态
            listVisible: true,
            listIn: false, // 列表进入动画
            listOut: false, // 列表消失动画
            slider: 0,
            media: 0,
            ex1: {
                label: 'color',
                val: 25,
                color: '#a16eff'
            },
            pieBoxVisible: false,
            reversal: false,
            url: 'http://akveo.com/ngx-admin/assets/images/camera1.jpg',
            items: [{
                    avatar: 'https://cdn.vuetifyjs.com/images/lists/4.jpg',
                    title: 'Birthday gift',
                    subtitle: 'Trevor Hansen Have any ideas',
                },
                {
                    avatar: 'https://cdn.vuetifyjs.com/images/lists/5.jpg',
                    title: 'Recipe to try',
                    subtitle: 'Britta Holt We should eat ',
                },
                {
                    avatar: 'https://cdn.vuetifyjs.com/images/lists/4.jpg',
                    title: 'Birthday gift',
                    subtitle: 'Trevor Hansen Have any ideas',
                },
                {
                    avatar: 'https://cdn.vuetifyjs.com/images/lists/5.jpg',
                    title: 'Recipe to try',
                    subtitle: 'Britta Holt We should eat ',
                }
            ],
            duration: 0, //
            curVal: 0
        };
    },
    components: {
        WeatherApp,
        toDo,
        clock
    },
    computed: {
        period() {
            let hour = this.date.getHours();

            return (hour > 5 && hour < 18) ? 'app--day' : 'app--night';
        }
    },
    watch: {
        play: function (bool) {
            if (bool) {
                this.listOut = true;
                setTimeout(() => {
                    this.listOut = false;
                    this.listVisible = false;
                }, 500);
            } else {
                this.listVisible = true;
                this.listIn = true;
                setTimeout(() => {
                    this.listIn = false;
                }, 1000);
            }

        }
    },
    methods: {
        snack() {
            this.$store.dispatch('snackbar/setSnackbar', {
                color: 'success',
                text: 'Numaralı müşteri'
            });
        },
        handlePieBoxVisibleStatus() {
            this.pieBoxVisible = !this.pieBoxVisible;
        },
        handleRunReversal() {
            this.reversal = !this.reversal;
        },
        handleZoomCamera(url) {
            this.url = url;
            this.reversal = true;
        },
        curTimeChange(val) {
            console.log(val);
            this.$refs.audio.currentTime = val;
            this.onPlay();
        },
    }
};
</script>

<style lang="scss">
#clock {
    font-family: 'Share Tech Mono', monospace;
    color: #ffffff;
    text-align: center;
    // position:relavitve;
    // left: 50%;
    // top: 50%;
    // transform: translate(-50%, -50%);
    color: #daf6ff;
    text-shadow: 0 0 20px rgba(10, 175, 230, 1), 0 0 20px rgba(10, 175, 230, 0.5);

    .text {
        letter-spacing: 0.1em;
        font-size: 12px;
        padding: 0 0 0;
    }
}
</style>
