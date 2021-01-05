<template>
<v-data-table
    disable-sort
    :headers="headers"
    :items="records"
    :loading="tableLoading"
    :options="{itemsPerPage: 15}"
    hide-default-footer
    class="elevation-1 mt-2">
    <template v-slot:item.date="{ item }">
        <v-chip >{{ item.date  | moment('DD-MM-YYYY') }}</v-chip>
    </template>

    <template v-slot:items="props">
        <td v-for="(tr, index) in headers" :key="index">
            {{ props.item[tr.value] }}
        </td>
    </template>
</v-data-table>
</template>

<script>
export default {
    name: 'simpleTable',
    props: ['records', 'headers'],
    data() {
        return {
            loading: false,
            tableLoading: false,
            search: '',
        };
    },
    created() {

    },
    mounted() {},
    methods: {}
};
</script>

<style lang="scss" scoped>
.table_page {
    padding: 20px;
}
</style>
