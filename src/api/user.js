import Api from '../plugins/axios';
const LOGIN_END_POINT = '/api/cockpit/authUser';
const END_POINT = '/users';
const FILES_END_POINT = '/files';

export default {
    login (payload) {
        return Api.post(LOGIN_END_POINT, payload);
    },

    getAvatar ( id ) {
        console.log( id );
        return Api.get(FILES_END_POINT + '/' + id, {
            params: {
                fields: 'private_hash',
            },
        });
    },
    one ( id ) {
        return Api.get(END_POINT + '/' + id, {
            params: {
                meta: 'total_count,result_count,filter_count',
                fields: '*.*',
            },
        });
    },
    save (url, payload) {
        return Api.post(url, payload);
    },
    update (url, payload) {
        return Api.patch(`${url}/${payload.id}`, payload).then((resp) => {
            return resp;
        });
    },
    deleteItem (url, id) {
        return Api.delete(`${url}/${id}`);
    }
};
