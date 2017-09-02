<template>

    <section id="active-items" class="page">
        <header class="header">
            <create-item :list="list"></create-item>
        </header>

        <div class="content content--withfooter">
            <div v-show="items.length">
                <list-item :list="list"></list-item>
            </div>
            <div class="content--centered message message--empty" v-show="!items.length && !loading">
                <h1 class="message__title">Esta lista está vacía :(</h1>
                <p>Añade los productos que quieres comprar pulsando el botón <b>+</b></p>
            </div>
        </div>

        <footer class="footer" v-show="items.length">
            <transition name="fade">
                <a href="#active-items" :class="{'footer__link': true, 'footer__link--green': allDone}" v-on:click.prevent="finalize">
                    {{ completed.length }} de {{ items.length }}
                </a>
            </transition>
        </footer>
    </section>

</template>

<script>

    export default {

        props: {
            list: { required: true }
        },

        created () {
            this.fetchData();
            this.listen();
        },

        beforeRouteLeave (to, from, next) {
            Echo.leave(`lists.${this.list}`);
            next();
        },

        data () {
            return {
                loading: false
            }
        },

        components: {
            createItem: require('../components/create-item.vue'),
            listItem: require('../components/list-item.vue')
        },

        computed: {

            completed () {
                return this.$store.getters.completed;
            },

            items () {
                return this.$store.state.items.all;
            },

            allDone () {
                return this.$store.getters.allDone;
            }
        },

        methods: {

            fetchData () {
                this.loading = true;
                this.$store.dispatch('fetchItems', this.list)
                    .then(() => this.loading = false)
                    .catch(() => this.$router.push({ name: '404' }));
            },

            listen () {
                Echo.private(`lists.${this.list}`)
                    .listen('ItemCreated', (e) => {
                        this.$store.commit('ADD_ITEMS', {list: this.list, items: e.items});
                    })
                    .listen('ItemUpdated', (e) => {
                        this.$store.commit('UPDATE_ITEM', {list: this.list, item: e.item});
                    })
                    .listen('CartFinished', (e) => {
                        this.$store.commit('CART_FINISHED', {list: this.list, items: e.items});
                        sweetAlert({
                              title: '¡Lista actualizada!',
                              text: `Parece que ${e.user} acaba de eliminar los productos seleccionados`,
                              confirmButtonText: 'Vale',
                              type: 'info'
                            });
                    });
            },

            finalize () {

                if ( ! this.completedItems.length) {
                    return;
                }

                sweetAlert({
                      title: '¿Has terminado?',
                      text: 'Se eliminarán los productos que hayas seleccionado',
                      type: 'info',
                      showCancelButton: true,
                      closeOnConfirm: false,
                      showLoaderOnConfirm: true
                    },
                    () => {
                        this.$store.dispatch('deleteItems', {list: this.list}).then(response => {
                            sweetAlert({
                                title: response.data.message || '¡Genial!',
                                timer: 2000,
                                type: 'success',
                                showConfirmButton: false});
                        }, () => {
                            sweetAlert('Oops...', 'No he podido finalizar la compra... vuelve a intentarlo :(', 'error');
                        });
                    }
                );
            }
        }
    };

</script>
