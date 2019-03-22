<template>
    <nav class="navbar navbar-expand-lg sticky-top navbar-dark bg-dark">

        <router-link class="navbar-brand" to="/">
            <i class="fas fa-apple-alt fa-lg blanco" aria-hidden="true"></i> Theegg
        </router-link>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item">

                    <router-link class="nav-link" active-class="active" to="/" exact>
                        <i class="fas fa-home fa-lg blanco" aria-hidden="true"></i> Inicio
                    </router-link>

                </li>

                <li class="nav-item">

                    <router-link class="nav-link" active-class="active" to="/transacciones">
                        <i class="fas fa-dollar-sign fa-lg verde" aria-hidden="true"></i>
                        Transacciones
                    </router-link>

                </li>

                <li class="nav-item">

                    <router-link class="nav-link" active-class="active" to="/categorias">
                        <i class="fas fa-briefcase fa-lg rojo" aria-hidden="true"></i>
                        Categorías
                    </router-link>

                </li>

                <li class="nav-item" v-if="currentUser.rol == 0">

                    <router-link class="nav-link" active-class="active" to="/usuarios">
                        <i class="fas fa-users fa-lg azul" aria-hidden="true"></i> Usuarios
                    </router-link>

                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                        {{ currentUser.usuario }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a href="#!" @click.prevent="logout" class="dropdown-item">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        name: 'app-header',
        methods: {
            logout() {
                this.$store.commit('logout');
                this.$router.push('/login');
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        }
    }
</script>
<style>

</style>
