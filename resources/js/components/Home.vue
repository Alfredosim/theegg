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
            	<h4 class="card-title">{{formatNum(stats.transCount)}}</h4>
            
            	<p class="card-text"><small class="text-muted">TRANSACCIONES</small></p>

        	</div>
    	</div>

    	<div class="card">
        	<div class="card-body text-center">
            	<i class="fas fa-piggy-bank fa-lg" aria-hidden="true"></i>
            	<h4 class="card-title">{{ formatNum(stats.depoCount)}}</h4>
            
            	<p class="card-text"><small class="text-muted">DEPOSITOS</small></p>

        	</div>
    	</div>
    
    	<div class="card">
	        <div class="card-body text-center">
	            <i class="fas fa-hand-holding-usd fa-lg" aria-hidden="true"></i>
	            <h4 class="card-title">{{formatNum(stats.retiroCount)}}</h4>
	            
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
						    <button class="dropdown-item" type="button" @click="day()">Dia</button>
						    <button class="dropdown-item" type="button" @click="week()">Semana</button>
						    <button class="dropdown-item" type="button" @click="year()">Año</button>
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
					            	
					            	<doughnut v-if="loaded && filtro == 0"
								      :chartdata="chartdata"
								      :options="options"/>


					            	<bar
								      v-if="loaded && filtro == 1"
								      :chartdata="chartdata"
								      :options="options"/>								    
								    
								    

									<line-chart
								      v-if="loaded && filtro == 2"
								      :chartdata="chartdata"
								      :options="options"/>
																		    
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
										    <h3 class="card-text">Transacciones</h3>
										</div>
									</div>
									<div class="card text-white bg-info mb-3 text-center">
								  
										<div class="card-body">
										    <h1 class="card-title">{{ formatNum(fill.avg) }}</h1>
										    <h3 class="card-text">Promedio</h3>
										</div>
									</div>
									<div class="card text-white bg-success mb-3 text-center">
								  
										<div class="card-body">
										    <h1 class="card-title">{{ formatNum(fill.max) }}</h1>
										    <h3 class="card-text">Monto Mayor</h3>
										</div>
									</div>
									<div class="card text-white bg-danger mb-3 text-center">
								  
										<div class="card-body">
										    <h1 class="card-title">{{ formatNum(fill.min) }}</h1>
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
import LineChart from './charts/Line.vue';
import Doughnut from './charts/Doughnut.vue';
import moment from 'moment';
import accounting from 'accounting';
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
			        labels: [],
			        datasets: [
			        	{
			        		label: '',
			        		backgroundColor: '', 
			        		data: []
			        	}
			        ]
		        },
    		}
		},
		components: { Bar, LineChart, Doughnut },
    	mounted() {
            this.buscar();
            this.day();
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
        	day() {
        		this.loaded = false
	    		axios.get('api/lastday').then(response => {	    		
		    		this.fill.avg = response.data.average;
		    		this.fill.max = response.data.max;
		    		this.fill.min = response.data.min;
		    		this.fill.count = response.data.count;
		    		if (this.fill.count > 0) {
		    			this.chartdata.labels = ['Retiros', 'Depositos'];
		    			this.chartdata.datasets[0].backgroundColor = ['#ec7063', '#2ecc71'];
		    			this.chartdata.datasets[0].data.push(response.data.countReti);
		    			this.chartdata.datasets[0].data.push(response.data.countDepo);
		    			this.loaded = true;
		    			this.filtro = 0;
		    		} else {
		    			this.loaded = false
		    		}		    		
	    		}).catch(error => {
	    			this.loaded = false
	    			console.log(error.response.data)
	    		});
        	},
        	week() {
        		this.loaded = false
	    		axios.get('api/lastweek').then(response => {	    		
		    		this.fill.avg = response.data.average;
		    		this.fill.max = response.data.max;
		    		this.fill.min = response.data.min;
		    		this.fill.count = response.data.count;
		    		if (this.fill.count > 0) {
		    			this.chartdata.labels = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
		    			this.chartdata.datasets[0].label = 'Transacciones Semanales';
		    			this.chartdata.datasets[0].backgroundColor = '#f87979';
		    			this.chartdata.datasets[0].data = response.data.data;
		    			this.loaded = true;
		    			this.filtro = 1;
		    		} else {
		    			this.loaded = false
		    		}		    		
	    		}).catch(error => {
	    			this.loaded = false
	    			console.log(error.response.data)
	    		});
        	},
        	year() {
        		this.loaded = false
	    		axios.get('api/lastyear').then(response => {
	    			console.log(response.data.data)    		
		    		this.fill.avg = response.data.average;
		    		this.fill.max = response.data.max;
		    		this.fill.min = response.data.min;
		    		this.fill.count = response.data.count;
		    		if (this.fill.count > 0) {
		    			this.chartdata.labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
		    			this.chartdata.datasets[0].label = 'Transacciones Mensuales';
		    			this.chartdata.datasets[0].backgroundColor = '#5dade2';
		    			this.chartdata.datasets[0].data = Object.values(response.data.data);
		    			this.loaded = true;
		    			this.filtro = 2;
		    		} else {
		    			this.loaded = false
		    		}		    		
	    		}).catch(error => {
	    			this.loaded = false
	    			console.log(error.response.data)
	    		});
        	},        	
			formatNum(num) {
				return accounting.formatMoney(num); 
			},
        }    
}
</script>
<style>

</style>