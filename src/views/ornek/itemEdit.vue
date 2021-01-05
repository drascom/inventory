<template>
<vContainer>
    <v-card height="90%">
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
    <v-form
        ref="myForm"
        v-model="formValid">
        <v-form-base :model="myModel" :schema="mySchema" />
    </v-form>
    <v-card-actions>
        <v-btn
            :disabled="!formValid"
            right
            top
            color="success"
            @click="saveItem(myModel.group)">
            <v-icon class="mr-2">mdi-send</v-icon>
            <span v-if="isNewForm">Kaydet</span>
            <span v-else>Güncelle</span>
        </v-btn>
        <v-btn  :disabled="isNewForm" @click="deleteItem(myModel.group)" class="error  text--white">
            <v-icon class="mr-2">mdi-cancel</v-icon> Kaydı Sil
        </v-btn>
    </v-card-actions>
</v-card>

</vContainer>

</template>

<script>
import mixins from '@/mixins';
import VFormBase from 'vuetify-form-base';

export default {
    name: 'EditForm',
    components: {
        VFormBase
    },
    mixins: [mixins],
    data() {
        return {
            formValid: false,
            isLoading: false,
            completed: false,
            myModel: {},
        };
    },
    props: {
        formData: {
            type: Object,
            default: () => {}
        },
        schema: {
            type: Object,
            default: () => {}
        },
        pageName: {
            type: String,
            default: '',

        },
        isNewForm: {
            type: Boolean,
            default: false,

        },
    },
    computed: {
        mySchema() {
            return this.schema;
        }
    },
    methods: {
        async saveItem(payload) {
            this.isLoading = true;
            await this.$store.dispatch(`${this.pageName}/save`, {
                name: this.pageName,
                data: payload
            }).then((response) => {
                if (response.status == 200) {
                    this.completed = true;
                    this.$store.dispatch('snackbar/setSnackbar', {
                        color: 'success',
                        timeout: 3000,
                        message: 'işlem tammalandı'
                    });
                    setTimeout(() => {
                        this.completed = false;
                        this.isLoading = false;
                        this.dispatch(this.pageName, 'closeSideBar', true);
                    }, 1000);
                }

            });
        },
        async deleteItem(payload) {
            this.isLoading = true;
            await this.$store.dispatch(`${this.pageName}/delete`, {
                name: this.pageName,
                data: payload
            }).then((response) => {
                if (response.status == 200) {
                    this.completed = true;
                    this.$store.dispatch('snackbar/setSnackbar', {
                        color: 'warning',
                        timeout: 3000,
                        message: ` ${payload._id} Numaralı Kayıt Silindi.`
                    });
                    setTimeout(() => {
                        this.completed = false;
                        this.isLoading = false;
                    }, 1500);
                }
            });
            this.dispatch(this.pageName, 'closeSideBar', true);
        },
    },
    created() {
        // this.schema = require(`@/views/${this.pageName}/schema.js`).default;
    },
    mounted() {
        if (!this.isNewForm) {
            this.myModel.group = this.formData;
        }
    },
};
</script>
