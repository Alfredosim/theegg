<template>
<div class="modal fade" id="createCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<div class="modal-header">
	        	<h5 class="modal-title" id="createModal">
                	<i class="fas fa-briefcase" aria-hidden="true"></i>
                    Registrar Categoría
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
	                    <label for="nombre">Nombre (*)</label>
	                    <input type="text" class="form-control" v-model="categoriaForm.nombre" id="nombre" required>
	                </div>	               
            	</div>        	
				
				<div class="form-row">
	                <div class="form-group col-md-12">
	                    <label for="direccion">Descripcion (*)</label>
	                    <textarea class="form-control" v-model="categoriaForm.descripcion" id="direccion" rows="3" required></textarea>

	                </div>
            	</div>
	      	</div>
	      	<div class="modal-footer">
	      		<button type="submit" @click="crear()" class="btn btn-primary">Registrar</button>
		      	<button type="reset" @click="reset()" class="btn btn-danger" data-dismiss="modal">Cancelar</button>	            
	      	</div>
	    </div>
	  	</div>
	</div>
</template>

<script>
import ValidationErrors from '../ValidationErrors.vue';
    export default {
    	data: function () {
  			return {
    			categoriaForm: {
    				nombre: '',
    				descripcion: ''			
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
	    		axios.post('api/categorias/crear',this.categoriaForm).then(response => {
	    			this.reset();
	    			$('#createCategoriaModal').modal('hide');
	    			toastr.success('Categoria registrada satisfactoriamente');
	    		}).catch(error => {
	    			this.boo = true;
	    			this.errors = error.response.data.errors;
	    		});
        	},        	
        	reset() {
        		this.categoriaForm.nombre = '';
				this.categoriaForm.descripcion = '';
				this.errors = [];
				this.boo = false;
        	}
        }
    }
</script>
