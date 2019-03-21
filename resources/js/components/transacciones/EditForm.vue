<template>
<div class="modal fade" id="editTransaccionModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
	    <div class="modal-content">
	    	<form @submit.prevent="update()" autocomplete="off">
	    	<div class="modal-header">
	        	<h5 class="modal-title" id="editModal">
                	<i class="fas fa-briefcase" aria-hidden="true"></i>
                    Editar Transacción
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
	                    <label for="asunto">Asunto (*)</label>
	                    <input type="text" class="form-control" v-model="editForm.asunto" id="asunto" required>
	                </div>	               
            	</div>        	
				
				<div class="form-row">
	                <div class="form-group col-md-12">
	                    <label for="cat">Categoria (*)</label>	                    
	                    <select class="form-control" id="cat" v-model="editForm.categoria_id" required>
	                    	<option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{cat.nombre}}
				            </option>
	                    </select>	                    
	                </div>
            	</div>

            	<div class="form-row">
	                <div class="form-group col-md-6">
	                    <label for="monto">Monto (*)</label>
	                    <input type="text" class="form-control" v-model="editForm.monto" id="monto" required>
	                </div>
	                
	                <div class="form-group col-md-6">								
				      	<label for="fecha">Fecha (*)</label>
				      	<datepicker placeholder="dd/mm/aaaa" 
				      				input-class="form-control" 
				      				v-model="editForm.fecha"
				      				format="dd/MM/yyyy"
				      				id="fecha" 
				      				name="fecha"				      				
				      				:language="es"
				      				:highlighted="state.highlighted"
				      				required>  					
				      	</datepicker>
					</div>
            	</div>

            	<div class="form-row">
	                <div class="form-group col-md-6">
	                    <label for="tipo">Tipo (*)</label>	                    
	                    <select class="form-control" id="tipo" v-model="editForm.tipo" required>
	                    	<option value="0">Retiro</option>
	                    	<option value="1">Deposito</option>
	                    </select>	                    
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
import Datepicker from 'vuejs-datepicker';
import { es } from 'vuejs-datepicker/dist/locale';
import ValidationErrors from '../ValidationErrors.vue';
import moment from 'moment';
    export default {
    	props: ['editForm', 'categorias'],
    	data: function () {
  			return {
    			errors: [],
    			boo: false,
    			state: {
					highlighted: {
					    dates: [
					      new Date()
					    ],
  					}
  				},
  				es
  			}
		},		
		components: {
			Datepicker,
        	ValidationErrors
    	}, 	
        methods: {
        	update() { 				
	    		axios.put('api/transaccion/'+ this.editForm.id,this.editForm)
	    		.then(response => {
	    			this.reset();
	    			$('#editTransaccionModal').modal('hide');	    			
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
