<template>
    <v-container>
      <h1 class="display-2 primary--text">
        {{ `${$t('errors.whoops')}, ${errorCode}` }}
      </h1>
      <p>{{ $t(`errors.${errorCode}`) }}</p>
      <v-btn
        outlined
        color="primary"
        @click="$router.go(-1)"
      >
        {{ $t('errors.back') }}
      </v-btn>
    </v-container>
</template>

<script>

export default {
  name: 'ErrorPage',
  components: {
  },
  props: {
    errorCode: {
      type: Number,
      default: 404,
    },
  },
  data: () => ({}),
};
</script>
