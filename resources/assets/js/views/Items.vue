<template>

    <section id="active-items" class="page">
        <header class="header">
            <create-item :list="list"></create-item>
        </header>

        <div class="content content--withfooter">
            <div v-show="state.items.length">
                <list-item :list="list"></list-item>
            </div>
            <div class="content--centered message message--empty" v-show="!state.items.length && !state.loading">
                <h1 class="message__title">Esta lista está vacía :(</h1>
                <p>Añade los productos que quieres comprar pulsando el botón <b>+</b></p>
            </div>
        </div>

        <footer class="footer" v-show="state.items.length">
            <transition name="fade">
                <a href="#active-items" :class="{'footer__link': true, 'footer__link--green': allDone}" v-on:click.prevent="finalize">
                    {{ completedItems.length }} de {{ state.items.length }}
                </a>
            </transition>
        </footer>
    </section>

</template>

<script>

    import Item from '../models/Item';

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
                state: Item.state
            }
        },

        components: {
            createItem: require('../components/create-item.vue'),
            listItem: require('../components/list-item.vue'),
            loading: require('../components/loading.vue')
        },

        computed: {

            completedItems () {
                return Item.readCompletedItems();
            },

            allDone () {
                return this.state.items.length > 0 && (this.completedItems.length == this.state.items.length);
            }
        },

        methods: {

            fetchData (transition) {
                return Item.readItems(this.list);
            },

            listen () {
                Echo.private(`lists.${this.list}`)
                    .listen('ItemCreated', (e) => {
                        Item.state.items.push(e.item);
                    })
                    .listen('ItemUpdated', (e) => {
                        let index = _.findIndex(Item.state.items, ['id', e.item.id]);
                        Item.state.items.splice(index, 1, e.item);
                    })
                    .listen('CartFinished', (e) => {
                        Item.state.items = Item.state.items.filter(item => e.items.indexOf(item.id));
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
                        Item.deleteItems(this.list, this.completedItems).then(response => {
                            sweetAlert({
                                title: response.data.message || '¡Genial!',
                                timer: 2000,
                                type: 'success',
                                showConfirmButton: false});
                        }, (response) => {
                            sweetAlert('Oops...', 'No he podido finalizar la compra... vuelve a intentarlo :(', 'error');
                        });
                    }
                );
            }
        }
    };

</script>
