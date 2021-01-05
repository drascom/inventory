<template>
<div>
    <v-overlay :value="isLoading">
        <v-progress-circular
            v-if="!completed"
            indeterminate
            rotate
            size="64"></v-progress-circular>
        <v-col v-else class="shrink">
            <v-icon size="70" class="mx-auto d-flex ">mdi-thumb-up</v-icon>
            <v-list-item-title>İşlem Tamam. </v-list-item-title>

        </v-col>
    </v-overlay>
    <v-toolbar
        v-if="selectedList"
        color="blue elevation-5"
        class="fixed-bar"
        dark>
        <v-btn icon>
            <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
        <v-toolbar-title class="text-headline">{{selectedList.urun_adi.toUpperCase()}}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn
            class="mr-2"
            small
            dark
            fab
            right
            top
            raised
            color="red"
            @click.stop="handleCloseSideBar">
            <v-icon>mdi-close</v-icon>
        </v-btn>
    </v-toolbar>
    <v-container fluid>
        <v-card class="mx-auto" width="400">
            <v-card dark>
                <v-card-title dark class="justify-center">
                    <v-fade-transition>
                        <upload v-if="uploadAvatar" :key="selectedList.id"></upload>
                        <v-img
                            contain
                            v-else
                            :src="avatar">
                            <v-row class="fill-height align-end">
                                <v-spacer></v-spacer>
                                <v-card-title v-show="isFormValid" class="pl-12 pt-12">
                                    <div class="display-1 pl-12 pt-12">
                                        <v-btn outlined @click="uploadAvatar = !uploadAvatar">
                                            <v-icon>mdi-find-replace</v-icon> Resim Değiştir
                                        </v-btn>
                                    </div>
                                </v-card-title>
                            </v-row>
                        </v-img>

                    </v-fade-transition>
                </v-card-title>
            </v-card>
            <v-card-text class="pt-6 " v-if="!uploadAvatar">

                <v-card-title class="justify-space-between">
                    <span class="text-h6 ">Ürün Bilgileri</span>
                    <v-btn :to="`/stok/hareketi/${selectedList.urun_adi}`" depressed small>
                        Stok Kayıtları
                        <v-icon color="orange darken-4" right>
                            mdi-open-in-new
                        </v-icon>
                    </v-btn>
                </v-card-title>

                <v-form
                    ref="form"
                    class="pa-2 mt-2"
                    v-if="selectedList"
                    lazy-validation>
                    <v-text-field
                        placeholder="Ürün Adı yazın"
                        v-selectedList="selectedList.urun_adi"
                        counter="20"
                        :rules="nameRules"
                        label="Urun Adı"
                        required
                        :readonly="!editMode"
                        :outlined="editMode"></v-text-field>
                    <v-text-field
                        placeholder="Alış fiyatı yazın"
                        v-selectedList="selectedList.alis_fiyati"
                        type="number"
                        counter="20"
                        label="Alış Fiyatı"
                        :readonly="!editMode"
                        :outlined="editMode"></v-text-field>
                    <v-text-field
                        placeholder="Kritik Miktar girin"
                        v-selectedList="selectedList.kritik_miktar"
                        type="number"
                        counter="4"
                        label="Kritik Miktar"
                        :readonly="!editMode"
                        :outlined="editMode"></v-text-field>
                    <v-text-field
                        placeholder="Barkod Girin"
                        v-selectedList="selectedList.barkod"
                        type="number"
                        counter="20"
                        :rules="barkodRules"
                        label="Barkod"
                        :readonly="!editMode"
                        :outlined="editMode"></v-text-field>
                    <v-text-field
                        placeholder="Kdv Oranı Girin"
                        v-selectedList="selectedList.kdv"
                        type="number"
                        label="KDV"
                        :readonly="!editMode"
                        :outlined="editMode"></v-text-field>
                    <v-autocomplete
                        placeholder="Satıcı Firmayı seçin."
                        v-selectedList="selectedList.satici_firma"
                        :disabled="!editMode"
                        :items="firmalar"
                        color="white"
                        item-text="firma_adi"
                        label="Firma"></v-autocomplete>
                </v-form>
            </v-card-text>
            <v-card-text>
                <v-btn
                    v-if="newproduct"
                    :disabled="!isFormValid()"
                    block
                    color="orange"
                    @click="saveProduct(selectedList)">
                    <v-icon class="mr-2">mdi-send</v-icon>Kaydet
                </v-btn>

                <v-btn
                    v-else
                    :disabled="!isFormValid()"
                    block
                    color="success"
                    @click="updateProduct(selectedList)">
                    <v-icon class="mr-2">mdi-send</v-icon>Güncelle
                </v-btn>
                <v-btn
                    @click="deleteProduct(selectedList)"
                    block
                    class="error  text--white mt-5"> Ürünü Sil</v-btn>
            </v-card-text>
        </v-card>
    </v-container>
</div>
</template>

<script>
import {
    mapGetters,
    mapActions
} from 'vuex';
import commonJS from '@/plugins/store/common.js';

import mixins from '@/mixins';
import upload from '../components/upload';

export default {
    name: 'productInformation',
    data() {
        return {
            uploadAvatar: false,
            completed: false,
            editMode: false,
            isLoading: false,
            nameRules: [
                (v) => !!v || 'Ïsim Boş Olamaz',
                (v) => (v && v.length <= 30) || 'İsim 30 karakteri geçemez !',
            ],
            kritikRules: [
                (v) => (v.length <= 4) || 'Miktar 4 karakteri geçemez !',
            ],
            fiyatRules: [
                (v) => (v && v.length <= 4) || 'Miktar 4 karakteri geçemez !',
            ],
            barkodRules: [
                (v) => (v.length <= 20) || 'Barkod 20 karakteri geçemez !',
            ],
            apiUrl: process.env.NODE_ENV === 'development' ?
                process.env.VUE_APP_API_URL : process.env.VUE_APP_WEB_API_URL,
            avatarPlaceholder: require('../../assets/usericon.jpg'),
            selectedList: {
                barkod: '',
                urun_adi: '',
                alis_fiyati: '',
                stokta: '',
                satici_firma: '',
                kritik_miktar: '',
                status: 'published'
            }
        };
    },
    mixins: [mixins],
    props: {
        newproduct: {
            type: Boolean,
            default: false,
            required: false
        },
        mode: {
            type: Boolean,
            default: true,
            required: false
        },
        product: {
            type: Object,
            default: () => {},
            required: false,
        },
    },
    components: {
        upload,
    },
    computed: {
        ...mapGetters({
            firmalar: 'companies/Items',
            messages: 'products/messages'
        }),
        avatar() {
            return this.selectedList.avatar ? this.apiUrl + '/assets/' + this.selectedList.avatar.private_hash + '?key=directus-medium-crop' : this.avatarPlaceholder;
        }
    },
    methods: {
        isFormValid() {
            if (!this.$refs.form) {
                return false;
            } else {
                return this.$refs.form.validate();
            }
        },
        handleCloseSideBar() {
            this.dispatch('inventory', 'closeSideBar', false);
            this.dispatch('products', 'closeSideBar', false);
        },
        setproductDetails() {
            this.editMode = false;
            this.dispatch('products', 'setProduct', this.selectedList);
            if (this.$refs.form.validate()) {
                this.dispatch('in_out', 'newProduct', this.selectedList);
            }
        },
        async saveProduct(item) {
            this.isLoading = true;
            await this.$store.dispatch('products/saveNew', {
                name: 'products',
                data: item
            }).then((response) => {
                console.log('product save component response',response);
                if (response.public) {
                    this.completed = true;
                    setTimeout(() => {
                        this.completed = false;
                        this.isLoading = false;
                        this.handleCloseSideBar();
                    }, 1000);
                }
            });
        },
        async deleteProduct(payload) {
            this.isLoading = true;
            await this.$store.dispatch('products/delete', {
                name: 'products',
                data: payload
            }).then((response) => {
                console.log('information delete response',response);
                if (response.public) {
                    this.$store.dispatch('products/getAllItems', {
                        name: 'products'
                    });
                    this.completed = true;
                    setTimeout(() => {
                        this.completed = false;
                        this.handleCloseSideBar();
                    }, 1000);
                    this.$store.dispatch('snackbar/setSnackbar', {
                        color: 'warning',
                        timeout: 3000,
                        message: ` ${payload.id} Numaralı Ürün Silindi.`
                    });
                }
                this.isLoading = false;
            });
        },
        async updateProduct(item) {
            this.isLoading = true;
            this.$delete(item, 'avatar');
            await this.$store.dispatch('products/update', {
                name: 'products',
                data: item
            }).then((response) => {
                if (response.public) {
                    this.completed = true;
                    setTimeout(() => {
                        this.completed = false;
                        this.isLoading = false;
                        this.handleCloseSideBar();
                    }, 1000);
                    this.$store.dispatch('snackbar/setSnackbar', {
                        color: 'success',
                        timeout: 3000,
                        message: ` ${item.id} Numaralı Ürün kaydedildi.`
                    });
                }
            });
        },
        async updateAvatar(data) {
            this.isLoading = true;
            await this.$store.dispatch('products/updateAvatar', {
                name: 'products',
                data:{
                    avatar: data.id,
                    id: this.selectedList.id
                }
            }).then((response) => {
                if (response.id === this.selectedList.id) {
                    this.selectedList.avatar = response.data.data.avatar;
                    this.$store.dispatch('snackbar/setSnackbar', {
                        color: 'success',
                        message: ` ${this.selectedList.id} Numaralı Ürün Resmi Yüklendi.'`
                    });
                    this.isLoading = false;
                    this.uploadAvatar = false;
                }
            }).catch((error) => {
                console.log(error);
            });
        },
    },
    beforeCreate() {
        if (!(this.$store && this.$store.state && this.$store.state['companies'])) {
            this.$store.registerModule('companies', commonJS);
        }
    },
    created() {
        this.$store.dispatch('companies/getAllItems', {
            name: 'companies'
        });
        this.editMode = this.mode || false;
        this.$on('setAvatar', (status, returnData) => {
            if (returnData) {
                this.updateAvatar(returnData);
            }
            this.isLoading = status;
            this.uploadAvatar = status;
        });
    },
    mounted() {
        if (this.product) {
            if (Object.keys(this.product).length > 0) {
                this.selectedList = Object.assign({}, this.product);
            }
        }
        // this.isFormValid();
    },
};
</script>

<style lang="scss" scoped>
</style>
