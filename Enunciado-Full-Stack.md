# Evaluación Técnica
Se desea que desarrolle una aplicación orientada a servicios que para la lógica del backend trabaje con (PHP/Laravel) y para la lógica del frontend web (Vue). El enunciado del ejercicio se encuentra más abajo.

# Rastreador de gastos
### Usuarios
> -- Registro de usuarios
> -- Login de usuarios 
> -- Cierre de sesión
### Categorías
> -- Creación de Categoría:
###### Datos a ingresar:
* Name
* Description

> -- Listado de Categorías
###### Usuarios pueden ver, ingresar y editar Categorías
###### Columnas:
* Name
* Descripcion
* Created at
* Number of transactions
### Transacciones
> -- Creación de transacción:
###### Datos a ingresar:
* Subject
* Category
* Amount
* Date
* Type: (withdrawal, deposit)

> -- Listado de transacciones
###### Usuarios pueden ver, editar y eliminar sus transacciones 
###### Columnas:
* Subject
* Category
* Amount 
* Date
* Type

###### Filtros:
* Filtro por rango de fechas.
* Filtro por categoría
* Filtro por tipo de transacción

### Dashboard
Mostrar la comparación entre la cantidad total / dinero de depósitos, retiros y promedio diario, dependiendo del filtro seleccionado, se debe representar visualmente en cajas para las variables temporales, un gráfico de barras o de lineas o de torta a elección del candidato para representar información histórica.
###### Filtros:
* Filtro por fecha, últimos 7 días, últimos 30 días, año en curso.


### Aspectos a evaluar
* Generales: Control de Versiones (Git), Correcto uso de Frameworks, Manejo de códigos de respuesta HTTP.
* Backend: Arquitectura Orientada a Servicios (REST).
* Frontend: Diseño web adaptable/responsivo.

### Consideraciones
* Todas las acciones deben ser desarrolladas del lado del cliente, usando llamadas asíncronas (AJAX). El refrescamiento total de la vista no es aceptable.
* Utilización de Repository Pattern para la comunicación de los modelos con la base de datos.
* Uso de vue-router para construir una aplicación de una sola pagina.
* Uso de transacciones de base de datos para la creación de transacciones.
* Aplicación de patrones de diseño para la resolución de los procesos

### Bonificaciones
* Implementar al menos dos roles con diferentes niveles de permiso (ejemplo: usuario regular que pueda crear sus propios registros, usuario admin que pueda gestionar usuarios y registros).
* Manejo de errores/excepciones
* Organización/limpieza del código
* Documentación de código y aplicación.
* El diseño visual no será evaluado pero se exhorta a esforzarse por crear una interfaz limpia y agradable
* Las pruebas unitarias aportarán puntuación adicional. Si se desarrolla tanto frontend como backend las pruebas unitarias de frontend serán opcionales pero deseables y aportarán otra bonificación adicional.