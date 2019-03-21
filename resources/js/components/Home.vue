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
						    <button class="dropdown-item" type="button" @change="lastWeek()">7 Dias</button>
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
					            	<div class="small">					            	
					                <bar :dataWeek="dataWeek"></bar>
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
  				stats: {
  					transCount: '',
  					depoCount: '',
  					retiroCount: ''
  				},
  				fill: {
  					data: [],
  					avg: '',
  					count: '',
  					max: '', 
  					min: ''
  				},
  				filtro: '',
	  			dataWeek: {
			        labels: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
			        datasets: [
			        	{
			        		label: 'Transacciones Semanales',
			        		backgroundColor: '#f87979', 
			        		data: []
			        	}
			        ]
		        },
		        dataYear: {
			        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			        datasets: [
			        	{
			        		label: 'Transacciones Anuales',
			        		backgroundColor: '#f87979', 
			        		data: []
			        	}
			        ]
		        }

    		}
		},
		components: {
        	Bar
    	},
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
	    		axios.get('api/lastweek').then(response => {
	    		
	    		this.fill.data = response.data.data;
	    		this.fill.avg = response.data.average;
	    		this.fill.max = response.data.max;
	    		this.fill.min = response.data.min;
	    		this.fill.count = response.data.trans.length;
	    		const da = response.data.data;
	    		// this.dataWeek.datasets.data = response.data.data.map(item);
	    		console.log();
	    		const datasets = [
		        	{
			          	label: 'Transacciones Semanales',
				        backgroundColor: '#f87979',
			          	data: response.data.data
		        	}
      			]
      			this.dataWeek = {
        			datasets: datasets,        			
      			}	    		


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