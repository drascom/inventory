import rules from '../../mixins/jsonFormFunctions';
const toUpper = ({ value }) => typeof value === 'string' ? value.toUpperCase() : value;

const schema = {
    group: {
        searchField:'name',
        type: 'group',
        label: 'Firmalar',
        class: 'blue-grey lighten-5 pa-3',
        row: {
            noGutters: true,
            justify:'space-between',
            align:'center',
        },
        col: 12,
        schema: {
            name: {
                type: 'text',
                label: 'Firma Adı',
                inList: true,
                toCtrl: toUpper,
                fromCtrl: toUpper,
                rules: [rules.requiredField],
                listClass: 'text-start',
                listProps:{cols:'6',sm:'6',md:'4'},

            },
            irtibat_isim: {
                type: 'text',
                inList: true,
                label: 'Yetkili',
                listClass:'hidden-sm-and-down',
                listProps:{cols:'4',sm:'',md:''},
                class:''
            },

            irtibat_telefon: {
                type: 'number',
                label: 'Telefon',
                inList: true,
                listClass:'',
                listProps:{cols:'4',sm:'',md:''},
            },
        }
    }
};

async function getSchema() {
    var schemaResult = schema;
    return schemaResult;
}
export default getSchema;
