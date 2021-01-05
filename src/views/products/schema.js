import store from '../../plugins/store/index';
import rules from '../../mixins/jsonFormFunctions';
const toUpper = ({ value }) => typeof value === 'string' ? value.toUpperCase() : value;


const schema = {
    group: {
        searchField: 'name',
        type: 'group',
        label: 'Urun Kayıtları',
        class: 'blue-grey lighten-5 pa-3',
        row: {
            noGutters: false
        },
        col: 12,
        schema: {
             name: {
                type: 'text',
                label: 'Urun Adı',
                inList: true,
                rules: [rules.min6,rules.max50,rules.requiredField],
                toCtrl: toUpper,
                listClass: 'text-start',
                listProps: { cols: '6', sm: '6', md: '4' },
                description: '30 Karaktere kadar ürün adı giriniz.'
            },
            fiyat: {
                type: 'number',
                label: 'Alış Fiyatı',
                inList: false,
                listClass: 'd-none d-sm-flex',
                listProps: { cols: '', xs: '', sm: '', md: '' },
            },
            kritik_miktar: {
                type: 'number',
                label: 'Kritik Miktar',
                inList: true,
                listClass: 'hidden-sm-and-down ',
                listProps: { cols: '2', sm: '2', md: '' },
            },
            stok: {
                disabled: true,
                type: 'number',
                label: 'Stok Miktar',
                inList: true,
                listClass: '',
                listProps: { cols: '3', sm: '', md: '' },
            },
            barkod: {
                type: 'number',
                label: 'Barkod No',
                inList: false,
                maximum: 20,
                listProps: { cols: '2', sm: '', md: '' },
            },
            kdv: {
                type: 'number',
                label: 'Kdv',
                inList: true,
                maximum: 2,
                listClass: '',
                listProps: { cols: '2', sm: '2', md: '' },
            },
            satici_firma: {
                type: 'autocomplete',
                label: 'Satıcı Firma',
                inList: true,
                returnObject: true,
                cacheItems: true,
                searchInput: '',
                itemText: 'display',
                itemValue: '_id',
                items: [],
                listClass: 'hidden-sm-and-down',
                listProps: { cols: '2', sm: '', md: '' },
            },
        }
    }
};


async function getSchema() {
    return new Promise((resolve, reject) => {
        this.$store.dispatch('companies/getAllItems', {
            name: 'companies',
            data:{},
        }).then((res) => {
            if(res){
                let newItems = res.data.entries.map((item) => {
                    return {
                      _id: item._id,
                      link:'companies',
                      display: item.name
                    };
                  });
                schema.group.schema.satici_firma.items=newItems;
                resolve(schema);
            }else{
           reject(new Error('sema alınamadı'));
            }
       });
     });
   }
export default getSchema;
