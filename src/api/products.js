import Api from '../plugins/axios';
const END_POINT = '/api/collections';

export default {
    all (payload) {
        const params = {
            filter: payload.data,
            sort: { name: -1 },
        };
        return Api.post(END_POINT+'/get/products', params);
    },
    save (payload) {
        console.log('api save payload: ', payload.data);
        return Api.post(END_POINT+'/save/products', { data: payload.data }).then((res) => {
            return res;
        });
    },
    softDelete (payload) {
        return Api.post(END_POINT+'/save/products', { data: {_id:payload.data._id,status:false} }).then((res) => {
            return res;
        });
    },

    updateAvatar (payload) {
        return Api.patch(`${END_POINT}/${payload.id}`, payload);
    },
    deleteItem (payload) {
        const params = {
            filter: {
                _id: payload._id
            },
        };
        return Api.post(END_POINT+'/remove/products', params).then((res) => {
            return res;
        });
    },
};
