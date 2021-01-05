const config = {
    apiUrl: process.env.VUE_APP_API_URL || 'http://dist:81',
    baseUrl: process.env.VUE_APP_BASE || 'http://localhost:8080',
    photoUrl: process.env.VUE_APP_PHOTO_URL || 'http://localhost/uploadtest',
    uploadUrl: process.env.VUE_APP_UPLOAD_URL || 'http://localhost/uploadtest',
    locale: process.env.VUE_APP_LOCALE || 'en',
    sso: {
        enabled: process.env.VUE_APP_SSO_ENABLED || false
    },
    features: {
        example: parse( process.env.VUE_APP_FEATURE_EXAMPLE, true ),
  },
};



function feature ( name ) {
    return config.features[ name ];
}
function parse ( value, fallback ) {
    if ( typeof value === 'undefined' ) {
        return fallback;
    }
    switch ( typeof fallback ) {
        case 'boolean':
            return !!JSON.parse( value );
        case 'number':
            return JSON.parse( value );
        default:
            return value;
    }
}
export {
    config
};
export default {
    install ( Vue ) {
        Vue.appConfig = config;
        Vue.feature = feature;
        Vue.prototype.$appConfig = config;
        Vue.prototype.$feature = feature;
    }
};
