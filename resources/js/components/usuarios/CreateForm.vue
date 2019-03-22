<template>
<div class="modal fade" id="createClienteModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<form @submit.prevent="crear()" autocomplete="off">
	    	<div class="modal-header">
	        	<h5 class="modal-title" id="createModal">
                	<i class="fas fa-user-plus" aria-hidden="true"></i>
                    Registrar Usuario
                </h5>
	        	<button type="button" @click="reset()" class="close" data-dismiss="modal" aria-label="Close">
	          		<span aria-hidden="true">&times;</span>
	        	</button>
	      	</div>
	      	<div class="modal-body">
	      		<validation-errors :errors="errors" 
	      							v-if="boo">	      						
	      		</validation-errors>
	      		
	        	<div class="form-row">
	                <div class="form-group col-md-6">
	                    <label for="usuario">Usuario (*)</label>
	                    <input type="text" class="form-control" v-model="usuarioForm.usuario" id="usuario" required autofocus>
					</div>

	                <div class="form-group col-md-6">
	                    <label for="password">Contraseña (*)</label>
	                    <input type="password" class="form-control" v-model="usuarioForm.password" id="password" required>
	                </div>
            	</div>

            	<div class="form-row">
	                <div class="form-group col-md-12">
	                    <label for="rol">Rol (*)</label>	                    
	                    <select class="form-control" id="rol" v-model="usuarioForm.rol" required>
	                    	<option value="0">Administrador</option>
	                    	<option value="1">Normal</option>
	                    </select>	                    
	                </div>
            	</div>            	
			</div>
	      	<div class="modal-footer">
	      		<button type="submit" class="btn btn-primary">Registrar</button>
		      	<button type="reset" @click="reset()" class="btn btn-danger" data-dismiss="modal">Cancelar</button>	            
	      	</div>
	      </form>
	    </div>
	  	</div>
	</div>
</template>
<script>
import ValidationErrors from '../ValidationErrors.vue';
    export default {
    	data: function () {
  			return {
    			usuarioForm: {
    				usuario: '',
    				password: '',
    				rol: 0,			
				},
    			errors: [],
    			boo: false
  			}
		},		
		components: {
        	ValidationErrors
    	},
        methods: {
        	crear() { 				
	    		axios.post('api/usuarios/crear',this.usuarioForm).then(response => {
	    			this.reset();
	    			$('#createClienteModal').modal('hide');	    			
	    			toastr.success(response.data.message);
	    			this.$emit('completed');
	    		}).catch(error => {
	    			this.boo = true;
	    			this.errors = error.response.data.errors;
	    		});
        	},        	
        	reset() {
        		this.usuarioForm.usuario = '';
				this.usuarioForm.password = '';
				this.usuarioForm.rol = 0;
				this.errors = [];
				this.boo = false;
        	}
        }
    }
</script>
