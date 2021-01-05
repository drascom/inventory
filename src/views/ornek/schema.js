import store from '../../plugins/store/index';
import rules from '../../mixins/jsonFormFunctions';
const toUpper = ({ value }) => typeof value === 'string' ? value.toUpperCase() : value;
const firmalar = store.getters['companies/items'];
const urunler = store.getters['products/items'];

console.log ('urunler log',urunler);
const schema = {
    group: {
        type: 'group',
        label: 'Stok Hareketi Düzenleme',
        class: 'blue-grey lighten-5 pa-3',
        row: {
            noGutters: false
        },
        col: 12,
        schema: {
            id: {
                type: 'number',
                label: 'ID',
                inList: false,
                disabled:true,

            },
            created_on: {
                type: 'date',
                ext:'text',
                inList: true,
                locale: 'tr',
                appendIcon: 'mdi-event',
                label: 'Date',
                menu: {
                    closeOnContentClick: true,
                    nudgeRight: 200
                 },
            },
            urun_adi: {
                readonly:true,
                type: 'autocomplete',
                label: 'Urun Adı',
                inList: true,
                toCtrl: toUpper,
                returnObject: false,
                cacheItems: true,
                searchInput: '',
                itemText: 'urun_adi',
                itemValue: 'id',
                items: urunler,
            },
            miktar: {
                type: 'number',
                label: 'Miktar',
                inList: true,
                rules: [rules.requiredField],
            },
            in_out: {
                readonly:true,
                type: 'select',
                label: 'İşlem Türü',
                inList: true,
                itemText: 'label',
                itemValue: 'val',
                rules: [rules.requiredSel],
                items: [
                    {
                        label: 'Giriş',
                        val: ['in']
                    },
                    {
                        label: 'Çıkış',
                        val: ['out']
                    },

                ],
            }
        }
    }
};

export default schema;
