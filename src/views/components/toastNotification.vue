<template>
    <v-snackbars :objects.sync="objects" :distance="65" multi-line top right :auto-height="true"></v-snackbars>
</template>

<script>
import {
    mapState
} from 'vuex';
import VSnackbars from 'v-snackbars';

export default {
    name: 'toastNotification',
    data() {
        return {
            objects: [],
        };
    },
    watch: {
        snackbar(newValue,oldValue) {
            if(newValue.message !=oldValue.message){
                this.objects.push(newValue);
            }
        }
    },
    components: {
        'v-snackbars': VSnackbars,
    },
    computed: {
        ...mapState('snackbar', {
            // snackbars: (state) => state.snackbars,
            snackbar: (state) => state.snackbar,
        })
    },

};
</script>
