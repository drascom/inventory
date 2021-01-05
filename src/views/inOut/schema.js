import rules from '../../mixins/jsonFormFunctions';
const toUpper = ({ value }) => typeof value === 'string' ? value.toUpperCase() : value;


const schema = {
    group: {
        searchField:'name',
        type: 'array',
        row: {
            noGutters: true
         },
        schema: {
            date: {
                type: 'text',
                ext:'date',
                inList: true,
                rules: [rules.requiredSel],
                locale: 'tr',
                appendIcon: 'mdi-event',
                label: 'Date',
                menu: {
                    closeOnContentClick: true,
                    nudgeRight: 200
                 },
            },
            name: {
                type: 'text',
                label: 'Urun',
                readonly:true,
            },
            miktar: {
                type: 'number',
                label: 'Miktar',
                rules: [rules.requiredField],
            },
            price: {
                type: 'number',
                label: 'Fiyat',
            },
            tip: {
                type: 'select',
                label: 'Paket',
                inList: true,
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
                type: 'text',
                label: 'in_out',
                hidden:true
            },

        }
    }
};


export default schema;
