// axios
import axios from 'axios';
import store from '../plugins/store/';


const baseURL = process.env.NODE_ENV === 'development' ?
    process.env.VUE_APP_API_URL :
    process.env.VUE_APP_WEB_API_URL;

const request = axios.create({
    baseURL,
    timeout: 5000,
    headers: {
        'Content-Type': 'application/json',
        'Cockpit-Token': '03cd01b9132958e1fe77e9922eb569'
    },
});

request.interceptors.request.use((request) => {
    // const token = store.state.token;
    // if ( token ) {
    //     request.headers.Authorization = 'Bearer ' + token;
    // }
    return request;

}, (error) => {
    return Promise.reject(error);
});
request.interceptors.response.use(
    function (response) {
        // console.log('axios response',response);
        if(response.error && response.error.length > 1) {
            for(let i = 0; i < response.error.length; i++) {
                store.dispatch('connection/setConnection', {
                    status: true,
                    code: 201,
                    message: response.error[i]
                });
            }
        }else if(response.error){
            store.dispatch('connection/setConnection', {
                status: true,
                code: 201,
                message: response.error
            });
        }
        // response data
        return response;
    },
    function (error) {
        console.log('axios error',error.response);
        let text = {};
        if(error.response) {
            text.code = error.response.status;
            text.message = error.response.data.error;
        } else if(error.message) {
            // network down
            text.code = 404;
            text.message = error.message + ' || Server is Down ?';
        } else {
            // http status code and data
            text.message = 'sayfa bulunamadı' ;
            text.code =404;
        }
        store.dispatch('connection/setConnection', {
            status: true,
            code: text.code,
            message: text.message,
        });
        if(isNetworkError(error)) {
            store.dispatch('connection/setConnection', {
                status: true,
                code: 404,
                message: error
            });
        }
        return Promise.reject(text);
    }
);

function isNetworkError (err) {
    return !!err.isAxiosError && !err.response;
}
export default request;
