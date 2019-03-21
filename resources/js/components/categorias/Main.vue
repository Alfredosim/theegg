<template>
	<div>
    <h1>
    	<i class="fas fa-briefcase fa-lg" aria-hidden="true"></i> 
    	Categorías
    	<button type="button" class="btn btn-success" data-toggle="modal" data-target="#createCategoriaModal"><i class="fas fa-plus" aria-hidden="true"></i> Registrar</button>
    	
	</h1>

	<hr>
	<!-- Modal Create-->
	<create-form></create-form>
	<!-- Modal Update-->
	<edit-form :editForm="editForm" @completed="updatedCat"></edit-form>	

	<div class="row">
	    <div class="col-md-12">
	        <div class="card">
	            <div class="card-header text-white bg-dark text-center">
	            	<strong>Buscar</strong>
	            </div>

	            <div class="card-body">
	                <form  @submit.prevent="buscar()" autocomplete="off">
	                	<div class="row justify-content-center">

		                	<div class="form-group col-md-4">
								<label for="nombre">Nombre:</label>
							    <input type="text" class="form-control" v-model="categoria.nombre" id="nombre">
							</div>

						    <div class="form-group col-md-6">
								<label for="descripcion">Descripción:</label>
						      	<input type="text" class="form-control" v-model="categoria.descripcion" id="descripcion">
						    </div>   
							
						</div>
						<div class="row justify-content-center">
							
						    <div class="form-group col-md-5">								
						      	<label for="fechadesde">Fecha desde:</label>
						      	<datepicker placeholder="dd/mm/aaaa" 
						      				input-class="form-control" 
						      				v-model="categoria.created_at_desde"
						      				@selected="disabledDate" 
						      				format="dd/MM/yyyy"
						      				id="fechadesde" 
						      				name="fechadesde"
						      				:disabledDates="state.disabledDates"
						      				:language="es"
						      				:highlighted="state.highlighted">  					
						      	</datepicker>
						    </div>

						    <div class="form-group col-md-5">								
						      	<label for="fechahasta">Fecha hasta:</label>
						      	<datepicker placeholder="dd/mm/aaaa"
						      				input-class="form-control"
						      				v-model="categoria.created_at_hasta"
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
	            <div class="card-header text-white bg-dark text-center"><strong>Categorias</strong></div>
				
	            <div class="card-body">
				<div id="tabla">
	                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover" >
                        <thead class="text-center">
                            <!-- <th>#</th> -->
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Fecha creación</th>
                            <th>Numero de transacciones</th>
                            <th>Opciones</th>
                        </thead>

                        
                        <tr v-for="cat in categorias" :key="cat.id" class="text-center">
                            <!-- <td>{{ cat.id }}</td> -->
                            <td>{{ cat.nombre }}</td>
                            <td>{{ cat.descripcion }}</td>                                
                            <td>{{ formatDate(cat.created_at) }}</td>
                            <td>{{ cat.transacciones_count }}</td>
                            <td class="text-center">
                            	<a href="#" class="btn btn-primary" v-on:click.prevent="editCat(cat)"><i class="fas fa-edit" aria-hidden="true"></i> Editar</a>
                            </td>
                            
                        </tr>
                    </table>                    
                </div>
                
                <div class="row" v-if="categorias.length > 0">
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
import CreateForm from '../categorias/CreateForm.vue';
import EditForm from '../categorias/EditForm.vue';
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
    			categoria: {
    				nombre: '',
    				descripcion: '',
    				created_at_desde: '',
    				created_at_hasta: ''		
				},
    			categorias: [],
    			pagination: {},
    			editForm: {
    				id: '',
    				nombre: '',
    				descripcion: ''			
				},   			
  			}
		},
		components: {
        	Datepicker,
        	CreateForm,
        	EditForm
    	},
        methods: {
 			buscar(page_url) {
 				var urlCategorias = page_url || 'api/categorias';
	    		axios.post(urlCategorias,this.categoria).then(response => {
	    			console.log(response.data);
	    			if (response.data.total > 0) {	    				
	    				this.categorias = response.data.data;					
	    				this.makePagination(response.data);
	    			} else {
	    				this.categorias = [];					
	    				this.pagination = {};
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
			editCat(cat) {
				this.editForm.id = cat.id;
				this.editForm.nombre = cat.nombre;
				this.editForm.descripcion = cat.descripcion;
				$('#editCategoriaModal').modal('show');
			},			
        	updatedCat() {
        		this.limpiar();
        	},
			formatDate(date) {
				return moment(date).format('DD/MM/YYYY');
			},
        	limpiar() {
        		this.categoria.nombre = '';
				this.categoria.descripcion = '';
				this.categoria.created_at_desde = '';
				this.categoria.created_at_hasta = '';
				this.categorias = [];
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
        },
        computed: {
        	
        }
    }
</script>

<style>


</style>
