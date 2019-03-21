<template>
<div>
    <h1>
    	<i class="fas fa-home fa-lg" aria-hidden="true"></i> 
    	Dashboard
	</h1>

	<hr>

	<div class="card-deck">
    	<div class="card">
        	<div class="card-body text-center">
            	<i class="fas fa-dollar-sign fa-lg" aria-hidden="true"></i>
            	<h4 class="card-title">{{stats.transCount}}</h4>
            
            	<p class="card-text"><small class="text-muted">TRANSACCIONES</small></p>

        	</div>
    	</div>

    	<div class="card">
        	<div class="card-body text-center">
            	<i class="fas fa-piggy-bank fa-lg" aria-hidden="true"></i>
            	<h4 class="card-title">{{stats.depoCount}}</h4>
            
            	<p class="card-text"><small class="text-muted">DEPOSITOS</small></p>

        	</div>
    	</div>
    
    	<div class="card">
	        <div class="card-body text-center">
	            <i class="fas fa-hand-holding-usd fa-lg" aria-hidden="true"></i>
	            <h4 class="card-title">{{stats.retiroCount}}</h4>
	            
	            <p class="card-text"><small class="text-muted">RETIROS</small></p>

	        </div>
	    </div>
	</div>
	<br>
	<div class="row">
	    <div class="col-md-12">
	        <div class="card">
	            <div class="card-header">
	            	<i class="fas fa-chart-line fa-lg" aria-hidden="true"></i> Historico 
		            <div class="btn-group">
						  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    Filtro
						  </button>
						  <div class="dropdown-menu dropdown-menu-right">
						    <button class="dropdown-item" type="button" @change="lastWeek()">Semana</button>
						    <button class="dropdown-item" type="button" @change="lastMonth()">Mes</button>
						    <button class="dropdown-item" type="button" @change="lastYear()">Año</button>
						  </div>
					</div>				
	            </div>

	            <div class="card-body">
	            	<div class="row">
					    <div class="col-md-6">
					        <div class="card">
					            <div class="card-header">
					            	<i class="fas fa-calendar-alt fa-lg" aria-hidden="true"></i> Última Semana 
						            				
					            </div>

					            <div class="card-body">
					            	<bar
								      v-if="loaded"
								      :chartdata="chartdata"
								      :options="options"/>
								    
								    <div v-else class="alert alert-info" role="alert">
										  No tienes transacciones...
									</div>
																		    
					                </div>
					        </div>
					    </div>

					    <div class="col-md-6">
					        <div class="card">
					            <div class="card-header">
					            	<i class="fas fa-chart-pie fa-lg" aria-hidden="true"></i> Estadísticas
						            				
					            </div>

					            <div class="card-body">
					            	<div class="card text-white bg-primary mb-3 text-center">
								  
										<div class="card-body">
										    <h1 class="card-title">{{ fill.count }}</h1>
										    <h3 class="card-text">Cantidad Transacciones</h3>
										</div>
									</div>
									<div class="card text-white bg-info mb-3 text-center">
								  
										<div class="card-body">
										    <h1 class="card-title">{{ fill.avg }} $</h1>
										    <h3 class="card-text">Promedio</h3>
										</div>
									</div>
									<div class="card text-white bg-success mb-3 text-center">
								  
										<div class="card-body">
										    <h1 class="card-title">{{ fill.max }} $</h1>
										    <h3 class="card-text">Monto Mayor</h3>
										</div>
									</div>
									<div class="card text-white bg-danger mb-3 text-center">
								  
										<div class="card-body">
										    <h1 class="card-title">{{ fill.min }} $</h1>
										    <h3 class="card-text">Monto Menor</h3>
										</div>
									</div>		            		                
					            </div>
					        </div>
						    </div>
						</div>	            		                
	            </div>
	        </div>
	    </div>
	</div>	
</div>
</template>

<script>
import Bar from './charts/Bar.vue';
import moment from 'moment';
import 'moment/locale/es';
    export default {
        data: function () {
  			return {
  				loaded: false,
  				stats: {
  					transCount: '',
  					depoCount: '',
  					retiroCount: ''
  				},
  				fill: {
  					avg: '',
  					count: '',
  					max: '', 
  					min: ''
  				},
  				filtro: '',
  				options: null,
	  			chartdata: {
			        labels: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
			        datasets: [
			        	{
			        		label: 'Transacciones Semanales',
			        		backgroundColor: '#f87979', 
			        		data: []
			        	}
			        ]
		        },
    		}
		},
		components: { Bar },
    	mounted() {
            this.buscar();
            this.lastWeek();
        },
        methods: {
 			buscar() {
 				var urlDashboard = 'api/dashboard';
	    		axios.get(urlDashboard).then(response => {
		    		this.stats.transCount = response.data.transCount;			
		    		this.stats.depoCount = response.data.depoCount;
		    		this.stats.retiroCount = response.data.retiroCount;
	    		});
        	},
        	lastWeek() {
        		this.loaded = false
	    		axios.get('api/lastweek').then(response => {	    		
		    		this.fill.avg = response.data.average;
		    		this.fill.max = response.data.max;
		    		this.fill.min = response.data.min;
		    		this.fill.count = response.data.count;
		    		if (this.fill.count > 0) {
		    			this.chartdata.datasets[0].data = response.data.data;
		    			this.loaded = true;
		    		} else {
		    			this.loaded = false
		    		}		    		
	    		}).catch(error => {
	    			this.loaded = false
	    			console.log(error.response.data)
	    		});
        	},
        	lastMonth() {
	    		axios.get('api/lastmonth').then(response => {
		    		console.log(response.data);
		    		this.stats.transCount = response.data.transCount;			
		    		this.stats.depoCount = response.data.depoCount;
		    		this.stats.retiroCount = response.data.retiroCount;
	    		});
        	},
        	lastYear() {
	    		axios.get('api/lastyear').then(response => {
		    		console.log(response.data);
		    		this.stats.transCount = response.data.transCount;			
		    		this.stats.depoCount = response.data.depoCount;
		    		this.stats.retiroCount = response.data.retiroCount;
	    		});
        	}
        },
        computed: {
        	add: function () {

    		},
        }
    }
</script>
<style>
  .small {
    width: 500px; 
    height: 500px;
  }
  .prome {
    width: 300px; 
    height: 300px;
  }

</style>