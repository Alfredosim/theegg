<template>
<div class="modal fade" id="editCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<form @submit.prevent="update()" autocomplete="off">
	    	<div class="modal-header">
	        	<h5 class="modal-title" id="editModal">
                	<i class="fas fa-briefcase" aria-hidden="true"></i>
                    Editar Categoría
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
	                    <label for="nombre">Nombre</label>
	                    <input type="text" class="form-control" v-model="editForm.nombre" id="nombre" required>
	                </div>	               
            	</div>        	
				
				<div class="form-row">
	                <div class="form-group col-md-12">
	                    <label for="descripcion">Descripcion</label>
	                    <textarea class="form-control" v-model="editForm.descripcion" id="descripcion" rows="3" required></textarea>

	                </div>
            	</div>
	      	</div>

	      	<div class="modal-footer">
	      		<button type="submit" class="btn btn-primary">Actualizar</button>
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
    	props: ['editForm'],
    	data: function () {
  			return {
    			errors: [],
    			boo: false
  			}
		},		
		components: {
        	ValidationErrors
    	}, 	
        methods: {
        	update() { 				
	    		axios.put('api/categoria/'+ this.editForm.id,this.editForm).then(response => {
	    			this.reset();
	    			$('#editCategoriaModal').modal('hide');	    			
	    			toastr.success(response.data.message);
	    			this.$emit('completed');
	    		}).catch(error => {
	    			this.boo = true;
	    			this.errors = error.response.data.errors;
	    		});
        	},        	
        	reset() {
				this.errors = [];
				this.boo = false;
        	}
        }
    }
</script>
