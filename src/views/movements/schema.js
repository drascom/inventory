import store from '../../plugins/store/index';
import rules from '../../mixins/jsonFormFunctions';
const toUpper = ({ value }) => typeof value === 'string' ? value.toUpperCase() : value;

const schema = {
    group: {
        searchField:'name',
        type: 'group',
        label: 'Stok Hareketi Düzenleme',
        class: 'blue-grey lighten-5 pa-3',
        row: {
            noGutters: false
        },
        col: 12,
        schema: {
            status:{
                type: 'switch',
                label: 'Active',
                col: 4
            },
            date: {
                type: 'date',
                ext:'text',
                inList: true,
                rules: [rules.requiredSel],
                locale: 'tr',
                appendIcon: 'mdi-event',
                label: 'Date',
                menu: {
                    closeOnContentClick: true,
                    nudgeRight: 200
                 },
                 listProps:{cols:'3',sm:'',md:''},
            },
            name: {
                required:true,
                type: 'autocomplete',
                label: 'Urun Adı',
                inList: true,
                rules: [rules.requiredSel],
                toCtrl: toUpper,
                returnObject: true,
                cacheItems: true,
                searchInput: '',
                itemText: 'display',
                itemValue: '_id',
                items: [],
                listProps:{cols:'4',sm:'',md:''},
            },
            miktar: {
                type: 'number',
                label: 'Miktar',
                inList: true,
                rules: [rules.requiredField],
                listProps:{cols:'3',sm:'',md:''},
            },
            price: {
                type: 'number',
                label: 'Fiyat',
                inList: false,
                listProps:{cols:'',sm:'',md:''},
                listClass: 'hidden-sm-and-down',
            },
            tip: {
                type: 'select',
                label: 'Paket',
                inList: false,
                itemText: 'label',
                itemValue: 'val',
                rules: [rules.requiredSel],
                listProps:{cols:'',sm:'',md:''},
                items: [
                    {
                        label: 'Adet',
                        val: 'adet'
                    },
                    {
                        label: 'Kutu',
                        val: 'kutu'
                    },
                    {
                        label: 'Paket',
                        val: 'paket'
                    },
                    {
                        label: 'Şişe',
                        val: 'sise'
                    },
                     {
                        label: 'Torba',
                        val: 'torba'
                    },

                ],
            },
            in_out: {
                required:true,
                type: 'select',
                label: 'İşlem Türü',
                inList: true,
                itemText: 'label',
                itemValue: 'val',
                rules: [rules.requiredSel],
                listProps:{cols:'2',sm:'',md:''},
                items: [
                    {
                        label: 'Giriş',
                        val: 'in'
                    },
                    {
                        label: 'Çıkış',
                        val: 'out'
                    },

                ],
            },

        }
    }
};
async function getSchema() {
    return new Promise((resolve, reject) => {
        this.$store.dispatch('products/getAllItems', {
            name: 'products',
            data:null,
        }).then((res) => {
            if(res){
                // donuşturmek gerekirse
                let newItems = res.data.entries.map((item) => {
                    return {
                      _id: item._id,
                      link:'products',
                      display: item.name
                    };
                  });
                schema.group.schema.name.items=newItems;
                resolve(schema);
            }else{
           reject(new Error('sema alınamadı'));
            }
       });
     });
   }
export default getSchema;
