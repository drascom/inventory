import Api from '../plugins/axios';
const END_POINT = '/api/collections';

export default {
    all (payload) {
        const params = {
            filter:payload.data,
            sort: { name: -1 },
        };
        return Api.post(END_POINT+'/get/companies',params);
    },
    save (payload) {
        return Api.post(END_POINT+'/save/companies', payload).then((res) => {
            return res;
        });
    },
    deleteItem(payload) {
        const params = {
            filter: {
                _id: payload._id
            },
        };
        return Api.post(END_POINT+'/remove/companies',params).then((resonse) => {
            return resonse;
        });
    }
};
