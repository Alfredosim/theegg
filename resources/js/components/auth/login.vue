<template>
<div class="login row justify-content-center">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-sign-in-alt fa-lg" aria-hidden="true"></i> <strong>Iniciar Sesión</strong></div>
            <div class="card-body">
                  <form @submit.prevent="login" autocomplete="off">
                    <div class="form-group" v-if="authError">
                        <div class="alert alert-danger" role="alert">
                            <span class="help-block">
                                <strong>{{ authError }}</strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            
                            <input type="text" id="usuario" class="form-control form-control-lg" v-model="form.usuario" placeholder="Usuario" required autofocus>
                        
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            
                            <input type="password" id="password" class="form-control form-control-lg" v-model="form.password" placeholder="Contraseña" required>
                        
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Iniciar sesión</button>
                  </form>

                  <!-- <div class="text-center">
                    <a class="d-block small mt-3" href="register.html">Register an Account</a>
                    <a class="d-block small" href="forgot-password.html">Forgot Password?</a>
                  </div> -->
            </div>
        </div>
    </div>
</div> 
</template>

<script>
    import {login} from '../../helpers/auth';

    export default {
        name: "login",
        data() {
            return {
                form: {
                    usuario: '',
                    password: ''
                },
                error: null
            };
        },
        methods: {
            login() {
                this.$store.dispatch('login');

                login(this.$data.form)
                    .then((res) => {
                        this.$store.commit("loginSuccess", res);
                        this.$router.push({path: '/'});
                    })
                    .catch((error) => {
                        this.$store.commit("loginFailed", {error});
                    });
            }
        },
        computed: {
            authError() {
                return this.$store.getters.authError;         
            }          
        }
    }
</script>

<style>




</style>
