<template>

    <section id="login" class="page">
        <div class="content content--centered content--withfooter">
            <div class="form">

                <img src="/images/favicon-touch.png" alt="Logo" class="logo" width="80" height="80">
                <h1 class="title">Sopplis</h1>
                <h2 class="subtitle">Hacer la lista de la compra en papel es cosa del pasado</h2>

                <form v-on:submit.prevent="login">
                    <p>
                        <label class="form__label" for="email">Email:</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            autocomplete="email"
                            class="form__input"
                            placeholder="Email"
                            maxlength="100"
                            v-model="email"
                            required></p>
                    <p>
                        <label class="form__label" for="password">Password:</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form__input"
                            placeholder="Password"
                            maxlength="100"
                            v-model="password"
                            required></p>
                    <p><input type="submit" class="form__button" value="Entrar en Sopplis"></p>
                </form>

            </div>

            <div class="footer footer--transparent copyright">
                Hecho por <a href="https://twitter.com/jrodalo" rel="author">@jrodalo</a>
            </div>
        </div>
    </section>

</template>

<script>

    import User from '../models/User'

    export default {

        data () {
            return {
                email: '',
                password: ''
            }
        },

        methods: {

            login () {

                let email = this.email && this.email.trim();
                let password = this.password;

                if ( ! email || ! password) { return; }

                User.login({email, password}).then((response) => {

                    if (response.data.success === true) {
                        const next = this.$route.query.redirect ? { path: this.$route.query.redirect } : { name: 'lists' };
                        this.$router.push(next);
                    }

                }).catch((response) => {

                    sweetAlert({
                        title: 'Usuario no válido',
                        text: 'Parece que el usuario o contraseña que has puesto no son válidos.',
                        type: 'error',
                        confirmButtonText: 'Vale'
                    });

                });
            }
        }

    }
</script>

<style lang="sass">

    .copyright {color: #AAA; font-size: 0.7em;}
    .copyright a {color: inherit;}

</style>
