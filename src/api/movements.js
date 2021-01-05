import Api from '../plugins/axios';
import store from '@/plugins/store/index.js';
const END_POINT = '/api/collections';

export default {
    all (payload) {
        const params = {
            filter: payload.data,
            sort: { date: -1 },
        };
        return Api.post(END_POINT+'/get/movements', params);
    },

    // SAVE NEW && UPDATE aynı fonksiyon id var yok kendisi ayırıyor
    async save (payload) {
        //hesaplamalar
        let oldRecord = await this.all({ data: { _id: payload.data._id } }); //işlemdeki önceki ürün miktarını apiden çek
        let oldProductName = oldRecord.data.entries[0].name._id;
        let oldProcessType = oldRecord.data.entries[0].in_out;
        let prevCount = oldRecord.data.entries[0].miktar;
        let newProductName = payload.data.name._id;
        let newCount = payload.data.miktar;
        let newProcessType = payload.data.in_out;

        // tur değişimi varsa hesaplama
        if(newProcessType == 'out') {
            prevCount = ~prevCount + 1;
            newCount = ~newCount + 1;
        }
        let Fark = Number(newCount) + Number(prevCount);

        //tek ürün ise işlem
        const productA = await store.dispatch('products/getAllItems', { name: 'products', data: { _id: oldProductName } }, { root: true });
        let yeniStokA = '';
        if(newProductName && newProductName != oldProductName) {
            yeniStokA = Number(productA.data.entries[0].stok) + Number(prevCount);
        } else {
            yeniStokA = Number(productA.data.entries[0].stok) + Number(Fark);
        }
        const updateA = store.dispatch('products/save', { name: 'products', data: { _id: oldProductName, stok: yeniStokA } }, { root: true })
            .then((res) => { return res.data._id; });

        //iki ürün ise işlem
        if(newProductName && newProductName != oldProductName) {
            const productB = await store.dispatch('products/getAllItems', { name: 'products', data: { _id: newProductName } }, { root: true });
            let yeniStokB = Number(productB.data.entries[0].stok) + Number(newCount);
            const updateB = store.dispatch('products/save', { name: 'products', data: { _id: newProductName, stok: yeniStokB } }, { root: true })
                .then((res) => { return res.data._id; });
            if(updateA && updateB) {
                return Api.post(END_POINT+'/save/movements', payload).then((res) => {
                    return res;
                });
            }
        } else {
            if(updateA) {
                return Api.post(END_POINT+'/save/movements', payload).then((res) => {
                    return res;
                });
            }
        }
    },
    async saveSingle (payload) {
        let fark = payload.data.miktar;
        const productA = await store.dispatch('products/getAllItems', { name: 'products', data: { _id: payload.data.name._id } }, { root: true });
        let yeniStok = Number(productA.data.entries[0].stok) + Number(fark);
        const updateA = await store.dispatch('products/save', { name: 'products', data: { _id: payload.data.name._id, stok: yeniStok } }, { root: true })
            .then((res) => {
                return Api.post(END_POINT+'/save/movements', payload).then((res) => {
                    return res;
                });
            });
        return updateA;

    },
    // async save (payload) {
    //     return Api.post(END_POINT+'/save/movements', payload).then((res) => {
    //         return res;
    //     });
    // },
    async deleteItem (payload) {
        console.log('delete api payload', payload);
        payload.miktar = ~payload.miktar + 1;
        if(payload.name._id) {
            let product = await store.dispatch('products/getAllItems', { name: 'products', data: { _id: payload.name._id } }, { root: true });
            console.log('orjinal stok: ', product.data.entries[0].stok);
            let yeniStok = Number(product.data.entries[0].stok) + Number(payload.miktar);
            console.log('yeni stok : ', product.data.entries[0].stok, yeniStok);
            const updateProduct = await store.dispatch('products/save', { name: 'products', data: { _id: payload.name._id, stok: yeniStok } }, { root: true });
            console.log('update product', updateProduct);
        }
        const params = {
            filter: {
                _id: payload._id
            },
        };
        return Api.post(END_POINT+'/remove/movements', params).then((res) => {
            return res.data;
        });
    }
};
