<template>
<div>
    <h1>
    	<i class="fas fa-calculator fa-lg" aria-hidden="true"></i> 
    	Calculadora
	</h1>
	
	<hr>

	<div class="row">
	    <div class="col-md-6">
	        <div class="card">
	        	<div class="alert alert-primary" role="alert">
			  		<p><i class="fas fa-info-circle fa-lg" aria-hidden="true"></i><strong> Con la calculadora de préstamos puedes calcular lo que ganaras con el porcentaje (%) y el pago diario.</strong></p>
				</div>
	            
				<div class="card-body">	
				<form>	
					<div class="form-row">
					    <div class="form-group col-md-6">
							<i class="fas fa-dollar-sign" aria-hidden="true"></i>
					      	<label for="cantidad">Cantidad:</label>

					      	<input type="number" class="form-control form-control-lg" v-model="cantidad" id="cantidad" min="1" autofocus>
					      	<small id="cantidadHelp" class="form-text text-muted">Monto que se desea prestar.</small>
					    </div>

					   	<div class="form-group col-md-6">
							<i class="fas fa-percent" aria-hidden="true"></i>
					      	<label for="porcentaje">Porcentaje:</label>

					      	<input type="number" class="form-control form-control-lg" v-model="porcentaje" id="porcentaje" min="1">
					      	<small id="porcentajeHelp" class="form-text text-muted">Porcentaje que desea colocar.</small>
					    </div>

					<!-- ./form-row -->
				  	</div>

				  	<div class="form-row">

					    <div class="form-group col-md-6">
					    	<i class="fas fa-calendar-alt" aria-hidden="true"></i>
					      	<label for="dias">Días:</label>

					      	<input type="number" class="form-control form-control-lg" v-model="dias" id="dias">
					      	<small id="diasHelp" class="form-text text-muted">Días para pagar el préstamo.</small>
					    </div>

					<!-- ./form-row -->
				  	</div>

				  	<div class="form-row">

					    <div class="form-group col-md-6">
							<h3>
								<strong>
									<i class="fas fa-piggy-bank" aria-hidden="true"></i> Total:
								</strong>
							</h3>
					    	
					      	<h4><strong>$ {{total}}</strong></h4>
					      	
					    </div>

					    <div class="form-group col-md-6">					    	
					    	<h3>
					    		<strong>
					    			<i class="fas fa-calendar-check" aria-hidden="true"></i> Diario:
					    		</strong>
					    	</h3>
					    	
					      	<h4><strong>$ {{diario}} x día</strong></h4>
					      						      	
					    </div>
				  	</div>

				  	<div class="row justify-content-center">
					    <div class="col-3">
					     <button type="reset" @click="reset()" class="btn btn-primary btn-lg"><i class="fas fa-redo" aria-hidden="true"></i> Reiniciar</button>
					    </div>
					</div>

				</form>
				</div>
	    	</div>
	    </div>
	</div>
</div>

</template>

<script>
    export default {
    	data: function () {
  			return {
    			cantidad: '',
    			porcentaje: '',
    			dias: ''
  			}
		},
        methods: {
        	reset() {
        		this.cantidad = '';
    			this.porcentaje = '';
    			this.dias = '';
        	}
        },
        computed: {
            total() {
            	if (this.cantidad) {
            		var cant = parseInt(this.cantidad);
            		var porcen = parseFloat(this.porcentaje/100);
            		return (cant * porcen) + cant;
            	} else {
            		return 0;
            	}
            	
            },
            diario() {
            	if (this.dias > 0 && this.cantidad > 0 && this.porcentaje > 0) {
            		var dias = parseInt(this.dias);
            		return parseFloat(this.total / this.dias).toFixed(2);
            	} else {
            		return 0;
            	}
            	
            }
        }
    }
</script>
