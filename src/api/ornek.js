import Api from '../plugins/axios';

export default {
    allFiltered (url,query,sort='') {
        return Api.get(url, {
            params: {
                meta: 'total_count,result_count,filter_count',
                sort: sort,
                q:query,
                limit: 1000,
                fields: '*',
            },
        });
    },
    allDetailed (url,query,sort='') {
        return Api.get(url, {
            params: {
                meta: 'total_count,result_count,filter_count',
                sort: sort,
                limit: 1000,
                fields: '*.*.*',
            },
        });
    },
    save (url,payload) {
        return Api.post(url, payload);
    },
    update (url,payload) {
        return Api.patch(`${url}/${payload.id}`, payload).then((resp) => {
            return resp;
        });
    },
    deleteItem(url,id) {
        return Api.delete(`${url}/${id}`);
    }
};
