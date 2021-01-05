<template>
<v-list
    v-if="schema"
    dense
    :max-height="$vuetify.breakpoint.smAndDown ? '75vh':'80vh'"
    class="overflow-y-auto">
    <v-list-item class="text-weight-bold" :class="$vuetify.breakpoint.mobile ? 'text-caption':'text-body-2'">
        <v-container fluid class="pa-0">
            <v-row class="text-center">
                <v-col
                    v-for="(header,index) in headers"
                    :cols="header.listProps.cols || ''"
                    :sm="header.listProps.sm || ''"
                    :md="header.listProps.md || ''"
                    :key="index"
                    class="font-weight-bold"
                    :class="header.listClass">
                    {{header.label }}
                </v-col>

            </v-row>
        </v-container>
        <v-list-item-action class="hidden-sm-and-down">
            <v-btn
                icon
                small
                ripple>
                <v-icon small color="orange lighten-1">mdi-cogs</v-icon>
            </v-btn>
        </v-list-item-action>
    </v-list-item>
    <template v-for="(item,index) in items">
        <v-list-item
            height="40px"
            @click="onClickName(item._id)"
            :key="item.id"
            avatar
            :class="$vuetify.breakpoint.mobile ? 'text-caption':'text-body-2'">
            <v-container fluid class="pa-0">
                <v-row class="text-center align-center ">
                    <template v-for="(value,key,index) in mySchema">
                        <v-col
                            :cols="value.listProps.cols || ''"
                            :sm="value.listProps.sm || ''"
                            :md="value.listProps.md || ''"
                            v-if="value.inList"
                            :key="index"
                            :class="value.listClass">
                            {{checkType(item,value,key) }}
                        </v-col>
                    </template>

                </v-row>
            </v-container>
            <v-list-item-action class="hidden-sm-and-down">
                <v-btn
                    icon
                    small
                    ripple>
                    <v-icon small color="orange lighten-1">mdi-pencil</v-icon>
                </v-btn>
            </v-list-item-action>
        </v-list-item>
        <v-divider v-if="index + 1 < items.length" :key="`divider-${index}`"></v-divider>
    </template>
</v-list>
</template>

<script>
import mixins from '@/mixins';
// import veri from '@/views/${pageName}/schema.js';

import {
    mapGetters
} from 'vuex';

export default {
    name: 'itemList',
    mixins: [mixins],

    data() {
        return {
            // schema:{},
        };
    },
    computed: {
        ...mapGetters({
            apiUrl: 'settings/apiUrl',
            avatarPlaceholder: 'settings/avatarPlaceholder',
        }),
        mySchema() {
            return this.schema.group.schema;
        },
        headers() {
            if (this.schema.group.schema) {
                var items = this.schema.group.schema;
                return Object.keys(items) //isimleri al
                    .map((key) => items[key]) //isim ve verilerle yeni array oluştur
                    .filter((item) => { //array filtrele eşleşen kaydı bul
                        if (item.inList) {
                            return item;
                        }
                    });
            } else {
                return {};
            }

        }
    },
    props: {
        schema: {
            type: Object,
            default: () => {}
        },
        items: {
            type: Array,
            default: () => [],
        },
        pageName: {
            type: String,
            default: '',

        },
    },
    methods: {
        checkType(item, value, key) {
            let type = value.type;
            let field = value.itemText;
            // let ext = value.ext;
            // let search = ext ? ext :type;
            let respond = null;
            if (item[key] != null) {
                switch (type) {
                    case 'autocomplete':
                        respond = item[key][field] ? item[key][field] : item['display'];
                        break;
                    case 'select':
                        respond = item[key];
                        break;
                    case 'combobox':
                        respond = item[key];
                        break;
                    case 'date':
                        respond = this.$moment(item[key], 'YYYY-MM-DD').format('Do MMMM'); //item[key];
                        break;
                    default:
                        respond = item[key];
                }
            }
            return this.in_out(respond);
        },
        in_out(item) {
            if (item == 'in') {
                return 'giriş';
            } else if (item == 'out') {
                return 'çıkış';
            }
            return item;
        },
        onClickName(id) {
            this.dispatch(this.pageName, 'itemSelected', id);
        },
    },
    created() {
        // this.schema = require(`@/views/${this.pageName}/schema.js`).default;
    },
};
</script>

<style lang="scss" scoped>
</style>
