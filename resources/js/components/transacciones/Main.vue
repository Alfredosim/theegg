<template>
	<div>
    <h1>
    	<i class="fas fa-dollar-sign fa-lg" aria-hidden="true"></i> 
    	Transacciones
    	<button type="button" class="btn btn-success" data-toggle="modal" data-target="#createTransModal"><i class="fas fa-hand-holding-usd" aria-hidden="true"></i> Nueva Transacción</button>
    	
	</h1>

	<hr>
	<!-- Modal Create-->
	<create-form :categorias="categorias"></create-form>
	<!-- Modal Update-->
	<edit-form :categorias="categorias" :editForm="editForm" @completed="updatedTran"></edit-form>

	<div class="row">
	    <div class="col-md-12">
	        <div class="card">
	            <div class="card-header text-white bg-dark text-center">
	            	<strong>Buscar</strong>
	            </div>

	            <div class="card-body">
	                <form  @submit.prevent="buscar()" autocomplete="off">
	                	<div class="row justify-content-center">

							<div class="form-group col-md-3">
								<label for="categoria">Categoría</label>	                    
			                    <select class="form-control" id="categoria" v-model="transaccion.categoria_id">
				                    
				                    <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{cat.nombre}}
				                    </option>
				                                              
			                    	
			                    </select>
							</div>

						    <div class="form-group col-md-3">
								<label for="tipo">Tipo de transacción</label>	                    
			                    <select class="form-control" id="tipo" v-model="transaccion.tipo">
			                    	<option value="0">Retiro</option>
			                    	<option value="1">Deposito</option>
			                    </select>
							</div>
							
						    <div class="form-group col-md-3">								
						      	<label for="fechadesde">Fecha desde:</label>
						      	<datepicker placeholder="dd/mm/aaaa" 
						      				input-class="form-control" 
						      				v-model="transaccion.created_at_desde"
						      				@selected="disabledDate" 
						      				format="dd/MM/yyyy"
						      				id="fechadesde" 
						      				name="fechadesde"
						      				:disabledDates="state.disabledDates"
						      				:language="es"
						      				:highlighted="state.highlighted">  					
						      	</datepicker>
						    </div>

						    <div class="form-group col-md-3">								
						      	<label for="fechahasta">Fecha hasta:</label>
						      	<datepicker placeholder="dd/mm/aaaa"
						      				input-class="form-control"
						      				v-model="transaccion.created_at_hasta"
						      				@selected="disabledDate2"
						      				format="dd/MM/yyyy"
						      				id="fechahasta"
						      				name="fechahasta"
						      				:disabledDates="state.disabledDates2"
						      				:language="es"
						      				:highlighted="state.highlighted">				
						      	</datepicker>
						    </div>						   	
						</div>
							
						<div class="text-center">
							<button type="submit" class="btn btn-primary">
								<i class="fas fa-search" aria-hidden="true"></i> Buscar
							</button>

							<button type="reset" @click="limpiar()" class="btn btn-primary">
								<i class="fas fa-eraser" aria-hidden="true"></i> Limpiar
							</button>
						</div>
						
					</form>	                    
	            </div>
	        </div>
	    </div>
	</div>
	<br>
	<div class="row">
	    <div class="col-md-12">
	        <div class="card">
	            <div class="card-header text-white bg-dark text-center"><strong>Transacciones</strong></div>
				
	            <div class="card-body">
				<div id="tabla">
	                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover" >
                        <thead class="text-center">
                            <!-- <th>#</th> -->
                            <th>Asunto</th>
                            <th>Categoría</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Opciones</th>
                        </thead>

                        
                        <tr v-for="tran in transacciones" :key="tran.id" class="text-center">
                            <!-- <td>{{ cat.id }}</td> -->
                            <td>{{ tran.asunto }}</td>
                            <td>{{ tran.categoria.nombre }}</td>
                            <td>{{ tran.monto }}</td>                              
                            <td>{{ formatDate(tran.fecha) }}</td>
                            <td>
                            	<span class="badge badge-danger" v-show="tran.tipo === 0">Retiro</span>
       
                                <span class="badge badge-success" v-show="tran.tipo === 1">Deposito</span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-primary" v-on:click.prevent="editTran(tran)"><i class="fas fa-edit" aria-hidden="true"></i> Editar</a>                                
                                <a href="#" class="btn btn-danger" v-on:click.prevent="deleteTran(tran.id)"><i class="fas fa-trash" aria-hidden="true"></i> Eliminar</a>
                            </td>
                        </tr>
                        
                        <!-- <tr v-if="transacciones.length === 0">
                            <td colspan="10" class="text-center">
                               No se encontraron registros
                            </td>
                        </tr> -->
                    </table>                    
                </div>
                <div class="row" v-if="transacciones.length > 0">
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
	<div>
		{{ this.$data }}
	</div>
	
</div>
</template>

<script>

import Datepicker from 'vuejs-datepicker';
import { es } from 'vuejs-datepicker/dist/locale';
import moment from 'moment';
import 'moment/locale/es';
import CreateForm from '../transacciones/CreateForm.vue';
import EditForm from '../transacciones/EditForm.vue';
    export default {
        data: function () {
  			return {
  				state: {
					highlighted: {
					    dates: [
					      new Date()
					    ],
  					},
  					disabledDates: {
					    // to: new Date(), // Disable all dates up to specific date
					    from: '', // DESDE Disable all dates after specific date
					},
					disabledDates2: {
					    to: '', // HASTA Disable all dates up to specific date
					    // from: '', // Disable all dates after specific date
					}
				},
  				es,
    			transaccion: {
    				categoria_id: '',
    				tipo: '',
    				created_at_desde: '',
    				created_at_hasta: ''		
				},
    			transacciones: [],
    			categorias: [],
    			pagination: {},    			
				editForm: {
					id: '',
    				asunto: '',
    				categoria_id: '',
    				monto: '',
    				tipo: '',
    				fecha: ''    				
				},			
  			}
		},
		components: {
        	Datepicker,
        	CreateForm,
        	EditForm
    	},
    	mounted() {
            this.listCat();
        },
        methods: {
 			buscar(page_url) {
 				var urlTransacciones = page_url || 'api/transacciones';
	    		axios.post(urlTransacciones,this.transaccion).then(response => {
	    			if (response.data.total > 0) {
	    				this.transacciones = response.data.data;					
	    				this.makePagination(response.data);
	    			} else {
	    				this.transacciones = [];					
	    				this.pagination = {};
	    			}					
	    		});
        	},
        	deleteTran(id) {
        		axios.delete('api/transaccion/'+ id).then(response => {
	    			this.buscar();	    			
	    			toastr.info(response.data.message);
	    		}).catch(error => {
	    			toastr.warning(error.responde.data.message);
	    		});
        	},
        	listCat() { 				
	    		axios.get('api/categoriasv2').then(response => {
	    			if (response.data.data.length > 0) {
	    				this.categorias = response.data.data;
	    			} else {
	    				this.categorias = [];
	    			}					
	    		});
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
			editTran(tran) {
				this.editForm.id = tran.id;
    			this.editForm.asunto = tran.asunto;
    			this.editForm.categoria_id = tran.categoria_id;
    			this.editForm.monto = tran.monto;
    			this.editForm.tipo = tran.tipo;
    			this.editForm.fecha = moment(tran.fecha).format('MM/DD/YYYY');
				$('#editTransaccionModal').modal('show');
			},			
        	updatedTran() {
        		this.limpiar();
        	},
			formatDate(date) {
				return moment(date).format('DD/MM/YYYY');				
			},
        	limpiar() {
        		this.transaccion.categoria_id = '';
				this.transaccion.tipo = '';
				this.transaccion.created_at_desde = '';
				this.transaccion.created_at_hasta = '';
				this.transacciones = [];
				this.pagination = {};
				this.state.disabledDates2.to = '';
				this.state.disabledDates.from = '';
        	},
        	disabledDate(date) {
        		// var desde = this.cliente.created_at_desde;
        		this.state.disabledDates2.to = new Date(date);
        	},
        	disabledDate2(date) {
        		// var hasta = this.cliente.created_at_desde;
        		this.state.disabledDates.from = new Date(date);
        	},
        }
    }
</script>

<style>


</style>
