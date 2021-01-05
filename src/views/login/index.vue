<template>
<div class="login_container" :class="{login_mlmlh: mlmlh}">
    <v-card color="red" v-if="hata">
        <p class="text-center">{{hata}}</p>
    </v-card>
    <v-img :src="loginimg" gradient="rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)" />
    <v-toolbar
        absolute
        flat
        dark
        color="transparent"
        min-width="100%">
        <v-btn
            :small="$vuetify.breakpoint.mobile"
            text
            disabled>Login Page</v-btn>
        <v-spacer></v-spacer>
        <v-btn
            :small="$vuetify.breakpoint.mobile"
            text
            target="_blank"
            href="https://cockpit.drapirest.com">
            <v-icon style="margin-right: 10px">mdi-chart-areaspline</v-icon>Server
        </v-btn>
        <v-btn
            :small="$vuetify.breakpoint.mobile"
            text
            :to="{ name: 'login' }"
            style="margin: 0 20px">
            <v-icon style="margin-right: 10px">mdi-fingerprint</v-icon>Login
        </v-btn>
    </v-toolbar>
    <div class="wrap">
        <v-card class="inner_card">
            <v-card
                class="right"
                color="red"
                href=""
                target="_blank">
                <v-icon large color="white">mdi-vuetify</v-icon>
                <div class="title" color="white">Stok Takip</div>
            </v-card>
            <div class="left">
                <div class="row">
                    <v-text-field
                        label="Gorup"
                        min-width="100%"
                        dense
                        hint="Ederra Gorup"
                        persistent-hint
                        value="Ederra Group"
                        prepend-icon="mdi-incognito"></v-text-field>
                </div>
                <div class="row">
                    <v-text-field
                        label="Kullanıcı Adı"
                        min-width="100%"
                        dense
                        hint="İsim"
                        persistent-hint
                        v-model="loginData.user"
                        prepend-icon="mdi-account-outline"></v-text-field>
                </div>
                <div class="row">
                    <v-text-field
                        persistent-hint
                        :append-icon="showpass ? 'mdi-eye' : 'mdi-eye-off'"
                        label="Şifreniz"
                        min-width="100%"
                        hint="Şifre"
                        v-model="loginData.password"
                        :type="showpass ? 'text' : 'password'"
                        @click:append="showpass = !showpass"
                        v-on:keyup="pressEnter"
                        prepend-icon="mdi-lock-outline"></v-text-field>
                </div>
                <div class="row row_f">
                    <v-btn
                        x-large
                        rounded
                        color="primary"
                        style="margin-left: 30px"
                        @click="handleSignBtn">
                        <v-icon v-if="isLoading">mdi-loading mdi-spin</v-icon> <span v-else> Let's Go</span>
                    </v-btn>
                </div>
            </div>
        </v-card>
    </div>
    <toastNotification />
</div>
</template>

<script>
import userJS from '@/plugins/store/modules/users.js';
import toastNotification from '@/views/components/toastNotification';

export default {
    data() {
        return {
            hata: false,
            isLoading: false,
            showpass: false,
            mlmlh: false,
            loginData: {
                user: 'havvaergen',
                password: '',
            },
            loginimg: require('../../assets/sidebar-1.jpg'),
        };
    },
    components: {
        toastNotification,
    },
    computed: {
        token() {
            return JSON.parse(localStorage.getItem('api_key'));
        },
    },
    beforeCreate() {
        if (!(this.$store && this.$store.state && this.$store.state['user'])) {
            console.log('user store created');
            this.$store.registerModule('user', userJS);
        }
    },
    created() {
        // this.autoSignIn();
    },
    methods: {
        pressEnter(e) {
            if (e.keyCode === 13) {
                this.handleSignBtn();
            }
        },
        autoSignIn() {
            if (this.token) {
                this.$store.commit('user/handleSignIn');
                this.mlmlh = true;
                if (this.$route.params.nextUrl != null) {
                    this.$router.push(this.$route.params.nextUrl);
                } else {
                    this.$router.push('/anasayfa');
                }
            }
        },
        async handleSignBtn() {
            await this.$store.dispatch('user/login', this.loginData)
                .then((response) => {
                    this.hata = false;
                    this.isLoading = false;
                    this.$store.dispatch('snackbar/setSnackbar', {
                        color: 'success',
                        timeout: 1000,
                        message: response.name + ' hoşgeldin'
                    });
                    this.$store.dispatch('connection/setConnection', {
                        status: false,
                        code: '',
                        message: ''
                    });
                    this.mlmlh = true;
                    if (this.$route.params.nextUrl != null) {
                        this.$router.push(this.$route.params.nextUrl);
                    } else {
                        this.$router.push('/anasayfa');
                    }
                })
                .catch((error) => {
                    this.$store.dispatch('snackbar/setSnackbar', {
                        color: 'error',
                        timeout: 3000,
                        message: error.message
                    });
                    this.hata = error.message;
                    this.isLoading = false;
                    console.log(error.reponse);
                });
        }
    }
};
</script>

<style lang="scss" scoped>
.login_container {
    height: 100vh;
    overflow-y: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.5s;
    position: relative;

    .v-image {
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        z-index: 0;

        .v-image__image {
            background-position: center center;
            background-size: 100vh auto;
        }
    }

    .login_footer {
        position: absolute;
        left: 0;
        bottom: 0;
        line-height: 50px;
        width: 100%;
    }

    .wrap {
        position: relative;
        z-index: 1;

        .inner_card {
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.3s linear;
            padding-top: 100px;
            padding-bottom: 30px;

            .left {
                width: 400px;
                padding: 0 40px;
                margin: 0 10px;

                .row {
                    margin-top: 30px;
                }

                .row_f {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
            }

            .right {
                position: absolute;
                width: 86%;
                padding: 20px 0;
                left: 7%;
                top: -30px;
                text-align: center;

                .icon {
                    margin: 0 auto;
                }

                .title {
                    margin-top: 10px;
                    color: #fff;
                }
            }
        }
    }
}

.login_mlmlh {
    transform: scale(5);
    opacity: 0;
}

@media screen and (max-width: 960px) and (min-width: 320px) {
    .login_container {
        .wrap {
            .wrap_title {
                font-size: 36px;
                margin-bottom: 30px;
            }

            .inner_card {
                width: auto;
                margin: 30px;

                .left {
                    width: auto;
                    padding: 30px;
                }

                .right {
                    //display: none;
                }
            }
        }
    }
}
</style>
