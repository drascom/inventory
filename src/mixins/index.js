export default {
    methods: {
        dispatch (componentName, eventName, ...rest) {
            let parent = this.$parent || this.$root;
            let name = parent.$options.name;

            while(parent && (!name || name !== componentName)) {
                parent = parent.$parent;
                if(parent) {
                    name = parent.$options.name;
                }
            }

            if(parent) {
                parent.$emit.apply(parent, [eventName].concat(rest));
            }
        },
        sortFunc: function ( array, q ) {
            console.log( 'sort çalıştı' );
            this.dispatch('products', 'refresh');
            return array.slice().sort(function (a, b) {
                return (a[q] > b[q]) ? 1 : -1;
            });
        }
    }
};
