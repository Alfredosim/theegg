<template>
    <div id="main">
        <template v-if="currentUser">
            <Header/>
        </template>
        
        <br>
        <div class="container-fluid">
            <router-view></router-view>   
        </div>
        <Footer/>
    </div>
</template>

<script>
    import Header from './Header.vue';
    import Footer from './Footer.vue';
    export default {
        name: 'main-app',
        components: {
            Header,
            Footer
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        }
    }
</script>

<style>
    .blanco {
        color: #f8fff9;
    }

    .azul {
        color: #30a5ff;
    }

    .rojo {
        color: #ff6056;
    }

    .verde {
        color: #72ff74;
    }

    .amarillo {
        color: #fff67f;
    }

</style>