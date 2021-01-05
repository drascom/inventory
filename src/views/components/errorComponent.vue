<template>
<v-row justify="center">
    <v-dialog
        v-model="errorStatus.status"
        persistent
        width="60%"
        class="mx-auto"
        max-width="">
        <v-card>
            <v-toolbar
                flat
                color="error"
                dark>
                <v-toolbar-title class="">Error {{errorStatus.code}}</v-toolbar-title>
                <v-spacer></v-spacer>

                <v-btn icon>
                    <v-icon @click="close">mdi-close</v-icon>
                </v-btn>
            </v-toolbar>

            <v-divider></v-divider>
            <v-card-text style="height: auto;">
                <v-row justify="center">
                    <v-col cols="12" align="center">
                        <v-card-text class="mx-auto text-body-1">{{errorStatus.message}}</v-card-text>
                    </v-col>

                </v-row>
            </v-card-text>
            <v-card-actions class="justify-space-around">
                <v-btn
                    color="success"
                    @click="goToDashboard"
                    to="/dashboard"> Ana Sayfa</v-btn>
                <v-btn color="warning" @click="refreshPage"> Yeniden Yükle</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-row>
</template>

<script>
export default {
    name: 'errorStatusComponent',
    data() {
        return {
        };
    },
    computed: {
        errorStatus() {
            return this.$store.state.connection.response;
        },
    },
    watch: {
        errorStatus(newValue, oldValue) {}
    },
    methods: {
        goToDashboard() {
            this.$store.dispatch('connection/setConnection', {
                status: false,
                code: null,
                message: null
            });
            this.$router.push('/dashboard');
        },
        refreshPage() {
            this.$store.dispatch('connection/setConnection', {
                status: false,
                code: null,
                message: null
            });
            this.$router.go(this.$router.currentRoute);
        },
        close(){
             this.$store.dispatch('connection/setConnection', {
                status: false,
                code: null,
                message: null
            });
        }
    },

};
</script>

<style lang="scss" scoped>

</style>
