<template>
	<div>
    <h1>
    	<i class="fas fa-users fa-lg" aria-hidden="true"></i> 
    	Usuarios
    	<button type="button" class="btn btn-success" data-toggle="modal" data-target="#createClienteModal"><i class="fas fa-user-plus" aria-hidden="true"></i> Registrar</button>
    	
	</h1>

	<hr>
	<!-- Modal -->
	<create-form @completed="addUser"></create-form>

		<div class="card-deck">
    	<div class="card">
        	<div class="card-body text-center">
            	<i class="fas fa-users-cog fa-lg" aria-hidden="true"></i>
            	<h4 class="card-title">{{usersAdmi}}</h4>
            
            	<p class="card-text"><small class="text-muted">ADMINISTRADORES</small></p>

        	</div>
    	</div>
    
    	<div class="card">
	        <div class="card-body text-center">
	            <i class="fas fa-user-tie fa-lg" aria-hidden="true"></i>
	            <h4 class="card-title">{{usersRegu}}</h4>
	            
	            <p class="card-text"><small class="text-muted">NORMALES</small></p>

	        </div>
	    </div>

	    <div class="card">
	        <div class="card-body text-center">
	            <i class="fas fa-users fa-lg" aria-hidden="true"></i>
	            <h4 class="card-title">{{pagination.total}}</h4>
	            
	            <p class="card-text"><small class="text-muted">TOTAL</small></p>

	        </div>
	    </div>
	</div>
	<br>
	
	<div class="row">
	    <div class="col-md-12">
	        <div class="card">
	            <div class="card-header text-white bg-dark text-center"><strong>Usuarios</strong></div>
				
	            <div class="card-body">
				<div id="tabla">
	                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover" >
                        <thead class="text-center">
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Fecha creación</th>
                        </thead>                        
                        <tr v-for="user in usuarios" :key="user.id" class="text-center">
                            <td>{{ user.usuario }}</td>
                            <td>
                                
                                <span class="badge badge-primary" v-show="user.rol === 0">Administrador</span>
       
                                <span class="badge badge-danger" v-show="user.rol === 1">Normal</span>
        
                            </td>                            
                            <td>{{ formatDate(user.created_at) }}</td>
                            
                        </tr>
                    </table>                    
                </div>
                <div class="row" v-if="usuarios.length > 0">
                	<div class="col-md-1">
		                <span class="badge badge-primary">Total: {{pagination.total}}</span>
	            	</div>
		    		<div class="col-md-11">
		                <nav aria-label="Page navigation example">
		                    <ul class="pagination justify-content-end">
		                        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#!" @click="buscar(pagination.prev_page_url)">Anterior</a></li>
		                        <li class="page-item disabled"><a class="page-link text-dark" href="#!">{{ pagination.current_page }} de {{ pagination.last_page}}</a></li>
		                        <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#!" @click="buscar(pagination.next_page_url)">Siguiente</a></li>
		                    </ul>
		                </nav>
	            	</div>
        		</div>
	                    
	            </div>
				</div>
	        </div>
	    </div>
	</div>
	<br>	
</div>
</template>

<script>
import moment from 'moment';
import 'moment/locale/es';
import CreateForm from '../usuarios/CreateForm.vue';
    export default {
        data: function () {
  			return {
  				usuarios: [],
    			pagination: {},
    			usersAdmi: 0,
    			usersRegu: 0
    		}
		},
		components: {
        	CreateForm
    	},
    	mounted() {
            this.buscar();
        },
        methods: {
 			buscar(page_url) {
 				var urlUsuarios = page_url || 'api/usuarios';
	    		axios.get(urlUsuarios).then(response => {
	    			console.log(response);
	    			if (response.data.total > 0) {	    				
	    				this.usuarios = response.data.data;				
	    				this.makePagination(response.data);
	    				axios.get('api/usuarios/stats').then(response => {
	    					this.usersAdmi = response.data[0];
	    					this.usersRegu = this.pagination.total - this.usersAdmi;
	    				});
	    			} else {
	    				this.usuarios = [];					
	    				this.pagination = {};
	    			}
					
	    		});
        	},
        	addUser(){
        		this.buscar();
        	},
        	makePagination(data) {
				let pagination = {
					current_page: data.current_page,
					last_page: data.last_page,
					next_page_url: data.next_page_url,
					prev_page_url: data.prev_page_url,
					total: data.total
				}
				this.pagination = pagination;
			},
			formatDate(date) {
				// return moment(date).format('DD/MM/YYYY');
				return moment(date).fromNow();
			}
        },
    }
</script>

<style>


</style>
