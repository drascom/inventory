<template>
<v-row justify="center">
    <v-dialog
        v-model="dialog"
        persistent
        max-width="290">
        <v-card>
            <v-card-title class="red--text headline">
                Uyarı
            </v-card-title>
            <v-card-text>Uzun bir süredir işlem yapmadınız. </v-card-text>
            <v-card-text>Sizi çıkışa yönlendiriyorum. </v-card-text>
            <v-card-text>Şimdilik hoşçakalın. </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                outlined
                block
                    color="green darken-1"
                    
                   @click="handleSignOut()">
                    Yeniden giriş yap.
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-row>
</template>

<script>
export default {
    data() {
        return {
            dialog: false
        }
    },
computed: {
    idle() {
      return this.$store.state.idleVue.isIdle;
    }
  },
  watch: {
      idle(newValue, oldValue) {
          if(newValue == true) this.dialog= true;
      }
  },
  methods: {
     handleSignOut() {
            this.$store.commit('user/handleSignOut');
            setTimeout(() => {
                this.$router.push('/login');
            }, 300);
        },
  },
};
</script>

