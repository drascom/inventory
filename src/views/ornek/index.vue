<template>
<v-container fluid>
    <div class="d-flex">
        <v-btn
            v-if="!$vuetify.breakpoint.smAndDown"
            small
            fab
            dark
            color="primary"
            class="mx-4 mb-6"
            @click="createNewitem">
            <v-icon dark>mdi-plus</v-icon>
        </v-btn>

        <template>
            <v-text-field
                class="mx-4  searchbar"
                v-model="search"
                :loading="isLoading"
                :items="filteredEntries"
                item-text="name"
                item-value="id"
                cache-items
                clearable
                @click:clear="handleResetEntries"
                hint="Kayıt Arayın"
                :messages="searchMessage"
                label="İsim Adı yada Barkod giriniz.."
                solo-inverted></v-text-field>
        </template>
        <v-btn
            v-if="!$vuetify.breakpoint.smAndDown"
            @click="printSection"
            small
            fab
            class="ml-5 mb-6">
            <v-icon dark>mdi-printer</v-icon>
        </v-btn>
        <v-overlay :value="isLoading">
            <v-progress-circular
                indeterminate
                rotate
                size="64"></v-progress-circular>
        </v-overlay>

    </div>
    <v-card id="printable">
        <div ref="printable" v-if="filteredEntries.length > 0 && schema">
            <itemList
                :items="filteredEntries"
                :schema="schema"
                :pageName="this.$options.name"
                :key="filteredEntries.length" />
        </div>
        <v-row
            v-else
            justify="center"
            align="center">
            <v-col class="shrink">
                <v-list-item-title>Ürün Bulunamadı.
                    <p> Arama kutusununa yeni kelime giriniz.</p>
                </v-list-item-title>
            </v-col>
        </v-row>
    </v-card>
    <v-navigation-drawer
        :stateless="true"
        v-model="sideBar"
        :width="$vuetify.breakpoint.smAndDown ? '100%' : '40%'"
        height="100vh"
        fixed
        right
        disable-click-watcher
        hide-overlay>
        <v-app-bar
            fixed
            dark
            color="secondary"
            src="https://picsum.photos/1920/1080?random">
            <template v-slot:img="{ props }">
                <v-img v-bind="props" gradient="to top right, rgba(19,84,122,.3), rgba(128,208,199,.7)"></v-img>
            </template>
            <v-btn
                large
                @click="handleCloseSideBar"
                icon>
                <v-icon>mdi-arrow-left</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn
                large
                @click="handleCloseSideBar"
                icon>
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-app-bar>
        <v-card flat height="64"></v-card>
        <v-container
            v-if="itemSelected"
            id="form"
            class=""
            height="90vh-70px">
            <itemEdit
                :isNewForm="isNewForm"
                :schema="schema"
                :pageName="this.$options.name"
                :formData="itemSelected"
                :key="!isNewForm ? itemSelected._id : 0 "></itemEdit>
        </v-container>
    </v-navigation-drawer>
    <v-app-bar
        v-if="$vuetify.breakpoint.smAndDown"
        class="bar pt-2"
        color="blue-grey lighten-4">
        <v-btn
            small
            fab
            dark
            color="primary"
            class="mx-4 mb-5"
            @click="createNewitem">
            <v-icon dark>mdi-plus</v-icon>
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
            @click.stop="printSection"
            small
            fab
            class="ml-5 mb-5">
            <v-icon dark>mdi-printer</v-icon>
        </v-btn>
        <v-btn
            small
            fab
            class="ml-5 mb-5">
            <v-icon dark>mdi-file-download</v-icon>
        </v-btn>
    </v-app-bar>
</v-container>
</template>

<script>
import {
    EventBus
} from '@/plugins/eventBus';

export default {
    props: {
        productName: {
            type: String,
            default: ''
        },
        sayfa: {
            type: String,
            default: 'x'
        }
    },
    data() {
        return {
            search: '',
            seachField: '',
            schema: {
                group: {

                }
            },
            test: null,
            getFunc: '',
            print: false,
            isNewForm: false,
            sideBar: false,
            isLoading: false,
            itemSelected: false,

        };
    },
    watch: {
        sideBar(newValue, oldValue) {
            if (newValue == false) {
                this.isNewForm = false;
            }
        },
        schema(newValue, oldValue) {
            this.seachField = newValue.group.searchField;
        }
    },
    components: {
        itemEdit: () => import('./itemEdit'),
        itemList: () => import('./itemList'),

    },
    computed: {
        items() {
            return this.$store.state[this.sayfa]['items'];
        },
        total() {
            return this.$store.state[this.sayfa]['total'];
        },
        apiUrl() {
            return this.$store.state['settings']['apiUrl'];
        },
        foundLength() {
            return this.filteredEntries.length;
        },
        searchMessage() {
            if (this.foundLength != 0) {
                return this.total +
                    ' kayıt  içinden ' +
                    this.foundLength +
                    ' kayıt bulundu.';
            }
            return 'Kayıt bulunamadı.';

        },
        filteredEntries() {
            const search = this.search;
            const seachField = this.seachField;
            let items = [];
            this.CriticalItems.length > 0 && (this.$router.currentRoute.name == 'kritik') ? items = this.CriticalItems : items = this.items;
            if (!search || search == '') return items;

            return items.filter((c) => {
                if (c[seachField] != null) {
                    let data = (typeof c[seachField] == 'string') ? c[seachField] : c[seachField]['display'];
                    return data.toLowerCase().indexOf(search.toLowerCase().trim()) > -1;
                }
            });
        },
        CriticalItems() {
            return this.items.filter((item) => {
                return item.kritik_miktar >= item.stok;
            });
        },
    },
    async created() {
        this.$options.name = this.sayfa;
        if (this.$options.name && this.$options.name != '') {
            this.getFunc = require(`@/views/${this.$options.name}/schema.js`).default;
        }
        await this.getFunc().then((res) => {
            this.schema = res;
            this.seachField = res.group.searchField;
        });
        this.search = this.productName;
        this.$store.dispatch(`${this.sayfa}/getAllItems`, {
            name: this.sayfa,
            data: {}
        });
        this.$on('itemSelected', (id) => {
            const targetItem = this.filteredEntries.find((item) => item._id === id);
            if (targetItem) {
                this.itemSelected = targetItem;
                this.isNewForm = false;
                this.sideBar = true;
            }
        });
        this.$on('closeSideBar', (status) => {
            if (status) {
                this.handleCloseSideBar();
            }
        });
    },
    methods: {
        delay(obj) {
            return new Promise((resolve, reject) => {
                setTimeout(() => resolve(obj), 500);
            });
        },
        printSection() {
            this.$htmlToPaper('printable', null, () => {
                console.log('Printing completed or was cancelled!');
            });
        },
        createNewitem() {
            this.isNewForm = true;
            this.itemSelected = {},
                this.sideBar = true;
        },
        handleCloseSideBar() {
            this.sideBar = false;
            this.itemSelected = {};
        },
        handleResetEntries() {
            this.search = '';
        },

    },
};
</script>

<style lang="scss" scoped>
@media screen and (max-width: 960px) and (min-width: 320px) {
    .bar {
        position: fixed;
        bottom: 0px;
    }

    .searchbar {
        width: 90%;
    }
}
</style>
