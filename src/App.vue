<template>
<v-app>
    <toastNotification />
    <router-view :key="$route.path"></router-view>
</v-app>
</template>

<script>
import toastNotification from '@/views/components/toastNotification';
import commonJS from '@/plugins/store/common.js';

export default {
    updated() {
        const dom = document.getElementById('loading');
        if (dom) {
            dom.remove();
        }
    },
    components: {
        toastNotification,
    },
    mounted() {

        // console.log(process.env);
    },

};
</script>
