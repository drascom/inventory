<template>
<v-container fluid>
    <div class="d-flex">
        <v-btn
            fab
            dark
            :small="$vuetify.breakpoint.mobile"
            color="primary"
            class="mr-2"
            @click="sideBar = !sideBar">
            <v-icon dark>mdi-plus</v-icon>
        </v-btn>

        <v-autocomplete
            class="px-2"
            :items="filteredEntries"
            :loading="isLoading"
            :filter="customFilter"
            v-model="model"
            @change="handleAddSelected($event)"
            color="blue-grey lighten-2"
            label="Ürün adı yada Barkod giriniz"
            item-text="name"
            item-value="_id"
            return-object
            hide-selected
            hide-details
            solo-inverted
            append-icon="mdi-database-search">
            <template slot="item" slot-scope="{ item }">
                <v-list-item-title>
                    {{item.name}}
                </v-list-item-title>
                <v-list-item-subtitle>
                    <span>({{item.stok}}) Adet</span>
                </v-list-item-subtitle>
            </template>
        </v-autocomplete>
        <v-overlay :value="isLoading">
            <v-progress-circular
                indeterminate
                rotate
                size="64"></v-progress-circular>
        </v-overlay>

    </div>
    <transition-group
        name="slide"
        tag="div"
        class="container fluid">
        <v-row
            v-if="selectedList.length == 0 && Object.keys(task).length == 0 "
            justify="center"
            align="center"
            key="1">
            <v-col class="shrink">
                <v-icon
                    v-if="!loaded"
                    size="70"
                    class="mx-auto d-flex ">mdi-hand-pointing-up</v-icon>
                <v-list-item-title v-if="!loaded">Ürün Seçilmedi</v-list-item-title>
                <v-list-item-title v-else>Stok işlemi tamamlandı.
                    <p> Yeni işlem girmek için yukarından ürün seçiniz.</p>
                </v-list-item-title>
            </v-col>
        </v-row>
        <v-row
            v-if="Object.keys(task).length != 0"
            justify="center"
            align="center"
            key="2">
            <v-col>
                <v-card class="mx-auto" color="#eee" elevation="6">
                    <v-card-text class="text-center">
                        <v-form ref="myForm" v-model="formValid">
                            <v-container fluid class="">
                                <v-row
                                    no-gutters
                                    align="center"
                                    justify="space-between">
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        class="ml-1">
                                        <v-text-field v-model="task.name.display" label="Urun Adı">
                                        </v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="4"
                                        sm="2"
                                        class="ml-1">
                                        <v-menu
                                            v-model="menu"
                                            :close-on-content-click="false"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="290px">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field
                                                    v-model="task.date"
                                                    label="Tarih"
                                                    readonly
                                                    v-bind="attrs"
                                                    v-on="on"></v-text-field>
                                            </template>
                                            <v-date-picker
                                                locale="tr"
                                                v-model="task.date"
                                                @input="menu = false"></v-date-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col
                                        cols="7"
                                        sm="3"
                                        class="ml-1 d-flex">
                                        <v-text-field
                                            type="number"
                                            v-model="task.miktar"
                                            label="Adet"
                                            :rules="[rules.requiredField]">
                                        </v-text-field>
                                        <v-select
                                            v-model="task.tip"
                                            :items="paketCinsleri"
                                            item-text="name"
                                            item-value="value"
                                            label="Cinsi"
                                            :rules="[rules.requiredSel]"></v-select>
                                    </v-col>

                                </v-row>
                            </v-container>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            small
                            color="primary"
                            :disabled="!formValid"
                            @click="handleAddFilledForm">Listeye Ekle</v-btn>
                    </v-card-actions>
                </v-card>

            </v-col>
        </v-row>
        <v-row
            v-if="selectedList.length > 0"
            align="center"
            justify="center"
            key="3">
            <v-col cols="12">
                <v-card min-height="calc(100vh - 185px)" color="#eee" elevation="6">
                    <v-toolbar
                        dark
                        elevation-6
                        dense>
                        <v-toolbar-title :class="$vuetify.breakpoint.smAndDown ? 'text-body-2':''">{{this.tur == 'in' ? 'Giriş' : 'Çıkış'}} Listesi</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="success"
                            :small="$vuetify.breakpoint.smAndDown"
                            @click="submitForm"> Listeyi Kaydet <v-icon right dark>
                                mdi-cloud-upload
                            </v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-list two-line flat>
                        <v-list-item-group active-class="blue-grey--text">
                            <v-list-item color="blue-grey-lighten-2" flat elevation="6">
                                <v-list-item-content style="min-width: 50%;">
                                    <v-list-item-title class="text--primary"><b>Ürün</b></v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-content v-show="!$vuetify.breakpoint.smAndDown">
                                    <v-list-item-title><b>Miktar </b> </v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-content>
                                    <v-list-item-title><b>Tarih </b> </v-list-item-title>
                                </v-list-item-content>
                                <v-list-item-action>
                                    <v-icon color="grey lighten-1">
                                        mdi-cog
                                    </v-icon>
                                </v-list-item-action>
                            </v-list-item>
                            <v-divider></v-divider>
                            <template v-for="(item, index) in selectedList">
                                <v-list-item :key="item.title">
                                    <v-list-item-content style="min-width: 50%;">
                                        <v-list-item-title :class="$vuetify.breakpoint.smAndDown ? 'text-body-2':'text-body-1'">{{ item.name.display}}</v-list-item-title>
                                        <v-list-item-subtitle> {{ $vuetify.breakpoint.smAndDown ? item.miktar +' - ' +item.tip : 'Fiyat: '+item.price }}</v-list-item-subtitle>
                                    </v-list-item-content>
                                    <v-list-item-content v-show="!$vuetify.breakpoint.smAndDown">
                                        <v-list-item-title class="text--primary">{{item.miktar}} - {{ item.tip}}</v-list-item-title>
                                    </v-list-item-content>
                                    <v-list-item-content>
                                        <v-list-item-title class="text--primary">{{item.date}}</v-list-item-title>
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-btn
                                            outlined
                                            error
                                            :x-small="$vuetify.breakpoint.smAndDown"
                                            class="text-body-2"
                                            @click="handleRemoveItem(item)">
                                            <v-icon color="red lighten-1">
                                                mdi-cancel
                                            </v-icon> <span v-show="!$vuetify.breakpoint.smAndDown">Çıkart</span>
                                        </v-btn>
                                    </v-list-item-action>
                                </v-list-item>

                                <v-divider v-if="index < selectedList.length - 1" :key="index"></v-divider>
                            </template>
                        </v-list-item-group>
                    </v-list>
                </v-card>
            </v-col>
        </v-row>
    </transition-group>
    <v-navigation-drawer
        v-model="sideBar"
        :width="$vuetify.breakpoint.mobile ? '100%' : 'auto'"
        height="100vh"
        style="position:absolute;"
        fixed
        right
        persistent
        hide-overlay>
        <information
            v-if="sideBar"
            mode
            newproduct />
    </v-navigation-drawer>
</v-container>
</template>

<script>
import {
    mapGetters
} from 'vuex';
import commonJS from '@/plugins/store/common.js';
import Information from './itemEdit.vue';
import rules from '@/mixins/jsonFormFunctions';

export default {
    name: 'inventory',
    components: {
        Information,
    },
    props: {
        tur: {
            type: String,
            default: 'in'
        },
    },
    data() {
        return {
            rules: rules,
            formValid: false,
            sideBar: false,
            menu: false,
            model: null,
            exist: null,
            dialog: false,
            isLoading: false,
            overlay: false,
            loaded: false,
            today: new Date().toISOString().substr(0, 10),
            paketCinsleri: [{
                    name: 'Adet',
                    value: 'adet'
                },
                {
                    name: 'Kutu',
                    value: 'kutu'
                },
                {
                    name: 'Paket',
                    value: 'paket'
                },
                {
                    name: 'Torba',
                    value: 'torba'
                },
                {
                    name: 'Şişe',
                    value: 'sise'
                },
            ],
            task: {},
            selectedList: [],
            entries: [],
        };
    },
    computed: {
        ...mapGetters({
            products: 'products/items',
            messages: 'products/messages'
        }),
        filteredEntries() {
            let tur = this.tur;
            return this.products.filter(function (item) {
                if (tur != 'out') {
                    return true;
                } else {
                    if (item.stok != 0) {
                        return true;
                    }
                }
                return false;
            });
        }
    },

    created() {
        this.$store.dispatch('products/getAllItems', {
            name: 'products',
            data: {}
        });
        this.$on('closeSideBar', (status) => {
            this.sideBar = status;
        });
        this.$on('submitAll', (status) => {
            if (status) {
                this.tasks = [];
                this.loaded = true;

            }

        });
        // this.$on('submitAll', (items) => {
        //     items.forEach((item) => {
        //         console.log('in out geldi', item);
        //     });
        // });
    },
    methods: {
        dateformat(date) {
            return this.$moment(this.today, 'YYYY-MM-DD').format('DD MMMM')
        },
        formValidate() {
            if (this.$refs.myForm) {
                this.formValid = this.$refs.myForm.validate();
            }
        },
        log(e) {
            console.log(e);
        },
        handeCloseSidebar() {
            this.sideBar = false;
        },
        customFilter(item, queryText, itemText) {
            const textOne = item.name.toLowerCase();
            const textTwo = item.barkod.toLowerCase();
            const searchText = queryText.toLowerCase();
            if (this.tur == 'out' && item.stok == 0) {
                console.log(item.name, ' ürün yok ');
            } else {
                return textOne.indexOf(searchText) > -1 ||
                    textTwo.indexOf(searchText) > -1;

            }
        },
        handleAddSelected(item) {
            this.handleCheckIfExists(item._id);
            if (!this.exists) {
                this.task = {
                    date: this.today,
                    in_out: this.tur,
                    miktar: '',
                    name: {
                        _id: item._id,
                        link: 'products',
                        display: item.name
                    },
                    price: item.fiyat,
                    status: true,
                };
            }
            this.formValidate();
        },
        handleAddFilledForm() {
            this.selectedList.push(this.task);
            this.$nextTick(() => {
                this.task = [];
                this.model = null;
            });
        },
        async handleRemoveItem(item) {
            await this.selectedList.splice(this.selectedList.indexOf(item), 1);
            if (this.selectedList.length == 0) {
                this.loaded = true;
            }
        },
        handleCheckIfExists(itemId) {
            this.exists = this.selectedList.some((item) => {
                console.log(item.name._id);
                return item.name._id === itemId;
            });
        },
        handleResetEntries() {
            this.entries = [];
        },
        async submitForm() {
            const postData = [];
            this.selectedList.map(async (item, index) => {
                if (item.miktar !== '') {
                    const _id = item._id;
                    item.miktar = this.tur == 'in' ? item.miktar : 0 - item.miktar;
                    const stok = Number(item.stok) + Number(item.miktar);
                    await this.$store.dispatch('movements/saveSingle', {
                        name: 'movements',
                        data: item
                    }).then((resp) => {
                        this.selectedList.splice(this.selectedList.indexOf(item), 1);
                        this.loaded = true;

                    });
                } else {
                    console.log('miktar boş itek kalacak');
                }
            });
        },
    },
};
</script>

<style lang="scss" scoped>
.slide-enter-active {
   -moz-transition-duration: 0.3s;
   -webkit-transition-duration: 0.3s;
   -o-transition-duration: 0.3s;
   transition-duration: 0.3s;
   -moz-transition-timing-function: ease-in;
   -webkit-transition-timing-function: ease-in;
   -o-transition-timing-function: ease-in;
   transition-timing-function: ease-in;
}

.slide-leave-active {
   -moz-transition-duration: 0.3s;
   -webkit-transition-duration: 0.3s;
   -o-transition-duration: 0.3s;
   transition-duration: 0.3s;
   -moz-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
   -webkit-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
   -o-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
   transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
}

.slide-enter-to, .slide-leave {
   max-height: 100px;
   overflow: hidden;
}

.slide-enter, .slide-leave-to {
   overflow: hidden;
   max-height: 0;
}
</style>
