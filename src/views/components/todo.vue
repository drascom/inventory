<template>
<v-theme-provider root :dark="isDark">
    <v-card>
        <v-overlay :value="isLoading" absolute>
            <v-progress-circular
                v-if="!completed"
                indeterminate
                rotate
                size="64"></v-progress-circular>
            <v-col v-else class="shrink">
                <v-icon size="70" class="mx-auto d-flex ">mdi-thumb-up</v-icon>
                <v-list-item-title>İşlem Tamam. </v-list-item-title>

            </v-col>
        </v-overlay>
        <v-toolbar color="blue-grey lighten-2" dark>
            <v-toolbar-title class="headline">Hatirlatıcı</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn
                        icon
                        @click="isDark = !isDark"
                        v-on="on">
                        <v-icon v-model="isDark">{{ !isDark ? 'mdi-weather-night' : 'mdi-weather-cloudy' }}</v-icon>
                    </v-btn>
                </template>
                <span>
                    {{ isDark ? 'light mode' : 'dark mode' }}
                </span>
            </v-tooltip>
        </v-toolbar>
        <v-list two-line subheader>
            <v-subheader class="headline"></v-subheader>
            <p class="mx-12 text-right"><b>{{waitingItemsCount}}</b> bekleyen, Toplam <b>{{todos.length}}</b> Not</p>
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title>
                        <v-text-field
                            class="mt-2"
                            outlined
                            v-model="newTodo"
                            id="newTodo"
                            name="newTodo"
                            label="Bir Not Yazın"
                            @keyup.enter="addTodo" />
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>

        </v-list>

        <v-list
            subheader
            two-line
            flat>
            <v-subheader class="subheading" v-if="todos.length == 0">Hiç Notunuz yok, bir not ekleyin</v-subheader>
            <v-subheader class="text-h5" v-else><span> Notlarınız</span>
                <v-spacer></v-spacer>
                <v-btn
                    v-if="selectedList.length > 0"
                    depressed
                    small
                    @click="removeSelectedTodos(selectedList)">Seçilenleri Sil <v-icon color="red">mdi-close</v-icon>
                </v-btn>
                <v-btn
                    v-if="completedItems.length > 0"
                    depressed
                    small
                    @click="removeSelectedTodos(completedItems)">Tamamlananları Sil <v-icon color="red">mdi-close</v-icon>
                </v-btn>
            </v-subheader>

            <v-list-item-group v-if="todos.length > 0 ">
                <v-list-item v-for="(todo, i) in todos" :key="i">
                    <template #default="{  }">
                        <v-list-item-action>
                            <v-checkbox @input="todo.done" @click="addSelectedList(todo)"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content @click="toggleTodo(todo)">
                            <v-list-item-title :class="todo.done? 'done':''">{{ todo.title | capitalize }}</v-list-item-title>
                            <v-list-item-subtitle>{{todo.date | moment("MMMM Do ")}}</v-list-item-subtitle>
                        </v-list-item-content>
                        <v-btn
                            disabled
                            small
                            text
                            v-if="todo.done">
                            Tamamlandı
                        </v-btn>
                    </template>
                </v-list-item>
            </v-list-item-group>
        </v-list>
    </v-card>
</v-theme-provider>
</template>

<script>
import {
    mapGetters
} from 'vuex';
import commonJS from '@/plugins/store/common.js';

export default {
    data() {
        return {
            selectedList: [],
            isLoading: false,
            completed: false,
            isDark: false,
            // todos: [],
            show: true,
            newTodo: '',
            date: new Date(),

        };
    },
    computed: {
        ...mapGetters({
            items: 'todo/items',
            messages: 'todo/messages',
        }),
        todos: {
            get() {
                return this.items;
            },
            set(newName) {
                return newName;
            }
        },
        completedItems() {
            return this.items.filter((item) => {
                return item.done;
            });
        },
        waitingItemsCount() {
            return this.items.filter((item) => {
                return !item.done;
            }).length;
        },
    },
    watch: {
        items(newValue, oldValue) {
            this.todos = newValue;
        }
    },
    created() {
        this.$store.dispatch('todo/getAllItems', {
            name: 'todo',
            data: {}
        });
    },
    methods: {
        async saveItem(item) {
            this.isLoading = true;
            await this.$store.dispatch('todo/save', {
                name: 'todo',
                data: item
            }).then((response) => {
                if (response) {
                    this.completed = true;
                    setTimeout(() => {
                        this.completed = false;
                        this.isLoading = false;
                    }, 1000);

                }
            });
        },
        async deleteItem(payload) {
            this.isLoading = true;
            await this.$store.dispatch('todo/delete', {
                name: 'todo',
                data: payload
            }).then((response) => {
                if (response.status == 200) {
                    this.$store.dispatch('todo/getAllItems', {
                        name: 'todo',
                        data: {}
                    });
                    this.completed = true;
                    setTimeout(() => {
                        this.completed = false;
                    }, 1000);
                    this.$store.dispatch('snackbar/setSnackbar', {
                        color: 'warning',
                        timeout: 3000,
                        message: ` ${payload._id} Numaralı Kayıt Silindi.`
                    });
                }
                this.isLoading = false;
            });
        },
        async updateItem(item) {
            this.isLoading = true;
            await this.$store.dispatch('todo/save', {
                name: 'todo',
                data: item
            }).then((response) => {
                this.completed = true;
                setTimeout(() => {
                    this.completed = false;
                    this.isLoading = false;
                }, 1000);
                if (response.done) {
                    this.$store.dispatch('snackbar/setSnackbar', {
                        color: 'success',
                        timeout: 3000,
                        message: ` ${item.title}  tamamlandı.`
                    });
                }else{
                     this.$store.dispatch('snackbar/setSnackbar', {
                        color: 'warning',
                        timeout: 3000,
                        message: ` ${item.title} Beklemede.`
                    });
                }
            }).then(this.$store.dispatch('todo/getAllItems', {
                name: 'todo',
                data: {}
            }));
        },
        CheckIfExists(id) {
            return this.selectedList.some((item) => {
                return item._id === id;
            });
        },
        addSelectedList(item) {
            if (!this.CheckIfExists(item._id)) {
                this.selectedList.push(item);
            } else {
                this.selectedList.splice(this.selectedList.indexOf(item), 1);
            }
        },
        addTodo() {
            var value = this.newTodo && this.newTodo.trim();
            if (!value) {
                return;
            }
            const item = {
                title: this.newTodo,
                date: this.$moment(String(this.date)).format('YYYY-MM-DD hh:mm:ss'),
                done: false
            };
            let respond = this.saveItem(item);
            if (respond) {
                this.todos.push(item);
            }
            this.newTodo = '';
        },
        removeSelectedTodos(payload) {
            let conf = window.confirm('Are you sure delete this Data?');
            if (conf) {
                Promise.all(
                    payload.map((item) => {
                        return this.deleteItem(item);
                    })
                ).finally(() => {
                    // using "finally" so even if there are errors, it stops "loading"
                    this.loading = false;
                    this.selectedList = [];
                });
            }
        },
        toggleTodo(todo) {
            todo.done = !todo.done;
            let respond = this.updateItem(todo);
        },
    },
    filters: {
        capitalize: function (value) {
            if (!value) return '';
            value = value.toString();
            return value.charAt(0).toUpperCase() + value.slice(1);
        }
    },
};
</script>

<style lang="scss" scoped>
.done {
    text-decoration: line-through;
}
</style>
