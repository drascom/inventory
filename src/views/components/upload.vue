<template>
<div id="profile-pic-demo" class="bg-light pt-3">
    <VueFileAgent
        class="profile-pic-upload"
        ref="profilePicRef"
        :multiple="false"
        :deletable="true"
        :meta="false"
        :compact="true"
        :accept="'image/*'"
        @select="upload()"
        :helpText="'Resimi buraya sürükleyin'"
        :errorText="{
        type: 'Bir resim seçin',
      }"
        v-model="profilePic">
        <template v-slot:after-outer>

        </template>
    </VueFileAgent>
    <div class="justify-center">
        <v-btn
            text
            class="error"
            v-if="!profilePic"
            @click="cancelUpload()">Vazgeç</v-btn>
    </div>

</div>
</template>

<script>
import Vue from 'vue';
import VueFileAgent from 'vue-file-agent';
import VueFileAgentStyles from 'vue-file-agent/dist/vue-file-agent.css';
Vue.use(VueFileAgent);
import mixins from '@/mixins';

export default {
    data: function () {
        return {
            name: 'avatarUploader',
            profilePic: null,
            uploaded: false,
            uploadUrl: process.env.NODE_ENV === 'development' ?
                process.env.VUE_APP_API_URL + '/files' :
                process.env.VUE_APP_WEB_API_URL + '/files',
            uploadHeaders: {},
        };
    },
    mixins: [mixins],
    methods: {
        removePic: function () {
            var profilePic = this.profilePic;
            this.$refs.profilePicRef.deleteUpload(this.uploadUrl, this.uploadHeaders, [profilePic]);
            this.profilePic = null;
            this.uploaded = false;
        },
        cancelUpload() {
            this.dispatch('productInformation', 'setAvatar', false, false);
        },
        upload: function () {
            this.uploaded = false;
            var self = this;
            this.$refs.profilePicRef.upload(
                this.uploadUrl,
                this.uploadHeaders,
                [this.profilePic],
                function createFormData(fileData) {
                    var formData = new FormData();
                    formData.append('filename', fileData.file);
                    return formData;
                }
            ).then(function (response) {
                let returnData = response[0].data.data;
                self.uploaded = true;
                self.dispatch('productInformation', 'setAvatar', false, returnData);
            });
        },

    }
};
</script>

<style>
#profile-pic-demo .drop-help-text {
    display: none;
}

#profile-pic-demo .is-drag-over .drop-help-text {
    display: block;
}

#profile-pic-demo .profile-pic-upload-block {
    border: 2px dashed transparent;
    padding: 20px;
    padding-top: 0;
}

#profile-pic-demo .is-drag-over.profile-pic-upload-block {
    border-color: #AAA;
}

#profile-pic-demo .vue-file-agent {
    width: 180px;
    float: left;
    margin: 0 15px 5px 0;
    border: 0;
    box-shadow: none;
}
</style>
