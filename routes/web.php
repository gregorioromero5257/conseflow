<?php

use App\PermisosEmpleados;

// Cargar unidades de proveedores
Route::post('/calidad/recepciones/cargarunidades', 'Calidad\RecepcionController@CargarUnidades');

// Rutas para supervisor de proyecto
Route::get('/proyectos/supervisor/obtenerproyectos', 'SupervisorProyectoController@ObtenerProyectos');
Route::get('/proyectos/supervisor/obtenersupervisores', 'SupervisorProyectoController@ObtenerSupervisores');
Route::put('/proyectos/supervisor/cambiarestado', 'SupervisorProyectoController@CambiarEstado');
Route::post('/proyectos/supervisor/guardarsuper', 'SupervisorProyectoController@GuardarSuper');

// Rutas precio articulos compras
Route::get('/compras/precioalmacen/obtener', 'PrecioArticulosController@ObtenerArticulos');
Route::post('/compras/precioalmacen/registrar', 'PrecioArticulosController@Registrar');
Route::get('/compras/precioalmacen/obtenerprecios', 'PrecioArticulosController@ObtenerPrecios');

// Rutas Calidad
Route::get('/calidad/inspecciones/folio/{p_id}', 'Calidad\InspeccionesController@ObtenerFolio');
Route::get('/calidad/inspecciones/reportes/{id}', 'Calidad\InspeccionesController@Reportes');
Route::get('/calidad/inspecciones/obtenerproyectos', 'Calidad\InspeccionesController@Proyectos');
Route::get('/calidad/inspecciones/obteneroc/{id}', 'Calidad\InspeccionesController@ObtenerOCS');
Route::get('/calidad/inspecciones/obtenerrime/{id}', 'Calidad\InspeccionesController@ObtenerRimes');
Route::get('/calidad/inspecciones/articulos/{id}', 'Calidad\InspeccionesController@ObtenerPartidasOC');
Route::get('/calidad/inspecciones/buscar/{datos}', 'Calidad\InspeccionesController@Buscar');
Route::post('/calidad/inspecciones/guardar', 'Calidad\InspeccionesController@Guardar');

Route::get('/calidad/inspeccionescliente/proyectos', 'Calidad\InspeccionClienteController@Proyectos');
Route::get('/calidad/inspeccionescliente/rime/{proyecto}', 'Calidad\InspeccionClienteController@Index');
Route::get('/calidad/inspeccionescliente/partidas/{rime}', 'Calidad\InspeccionClienteController@Inspecciones');
Route::get('/calidad/inspeccionescliente/cliente/{proyecto}', 'Calidad\InspeccionClienteController@Cliente');
Route::get('/calidad/inspeccionescliente/empledos', 'Calidad\InspeccionClienteController@Empleados');
Route::post('/calidad/inspeccionescliente/guardar', 'Calidad\InspeccionClienteController@Guardar');
Route::post('/calidad/inspeccionescliente/crearrime', 'Calidad\InspeccionClienteController@CrearRime');
Route::get('/calidad/inspeccionescliente/certificado/{id}', 'Calidad\InspeccionClienteController@Certificado');
Route::get('/calidad/inspeccionescliente/descargar/{id}', 'Calidad\InspeccionClienteController@DescargarRime');

// Rutas de API Proveedores
Route::get('/api/proveedores/test', 'APIControllers\ProveedoresController@Test');
Route::get('/api/proveedores/ocs/{rfc}', 'APIControllers\ProveedoresController@ObterOCS');
Route::get('/api/proveedores/obtener/{rfc}', 'APIControllers\ProveedoresController@ObtenerDatos');
Route::get('/api/proveedores/partidas/{id}', 'APIControllers\ProveedoresController@ObtenerPartidasOc');
Route::post('/api/proveedores/descargar/', 'APIControllers\ProveedoresController@Descargar');
Route::post('/api/proveedores/aceptaracuerdo/', 'APIControllers\ProveedoresController@AceptarAcuerdo');
Route::post('/api/proveedores/actualizar', 'APIControllers\ProveedoresController@ActualizarDatos');
Route::post('/api/proveedores/registrar', 'APIControllers\ProveedoresController@RegistrarProveedor');
Route::post('/api/proveedores/ingresar', 'APIControllers\ProveedoresController@Ingresar');
Route::post('/api/proveedores/subirfactura', 'APIControllers\ProveedoresController@SubirFactura');
Route::post('/api/proveedores/obtenerfacturas', 'APIControllers\ProveedoresController@ObtenerFacturas');
Route::post('/api/proveedores/registrarproveedor', 'APIControllers\ProveedoresController@RegistrarProveedor');

// Rutas de API Almacen
Route::get('/api/almacen/test', 'APIControllers\AlmacenController@Test');
Route::post('/api/almacen/ingresar', 'APIControllers\AlmacenController@Ingresar');
Route::get('/api/almacen/obtenerproyectos', 'APIControllers\AlmacenController@ObtenerProyectos');
Route::get('/api/almacen/obtenerocs/{id}', 'APIControllers\AlmacenController@ObtenerOCS');
Route::get('/api/almacen/obtenerpartidasoc/{id}', 'APIControllers\AlmacenController@ObtenerPartidasOC');
Route::get('/api/almacen/obtenerarticulo/{id}', 'APIControllers\AlmacenController@ObtenerArticulo');
Route::get('/api/almacen/obteneralmacenes', 'APIControllers\AlmacenController@ObtenerAlmacenes');
Route::get('/api/almacen/obtenerstand/{id}', 'APIControllers\AlmacenController@ObtenerStand');
Route::get('/api/almacen/obtenernivel/{id}', 'APIControllers\AlmacenController@ObtenerNivel');
Route::post('/api/almacen/guardar', 'APIControllers\AlmacenController@GuardarCodigo');
Route::post('/api/almacen/registrarentrada', 'APIControllers\AlmacenController@RegistrarEntrada');
Route::get('/api/almacen/buscaractu/{version}', 'APIControllers\AlmacenController@BuscarActualizacion');

// Salidas
Route::get('/api/almacen/salidas/obtenerempleados', 'APIControllers\SalidasController@ObtenerEmpleados');
Route::get('/api/almacen/salidas/obtenerproyectos', 'APIControllers\SalidasController@ObtenerProyectos');
Route::get('/api/almacen/salidas/obtenerarticulos/{codigo}', 'APIControllers\SalidasController@ObtenerArticulos');
Route::post('/api/almacen/salidas/registra', 'APIControllers\SalidasController@RegistrarSalida');
Route::post('/api/almacen/salidas/resguardo', 'APIControllers\SalidasController@SalidaResguardo');
Route::get('/api/almacen/salidas/obtenersalidas/{id}', 'APIControllers\SalidasController@VerSalidas');
Route::get('/api/almacen/salidas/buscararticulos/{texto}', 'APIControllers\SalidasController@BuscarArticulo');
Route::post('/api/almacen/salidas/actualizarcodigo', 'APIControllers\SalidasController@ActualizarCodigo');

Route::post('/checador', 'ChecadorController@index');
Route::post('guardar/reg/checador','NewChecadorController@store');
Route::post('buscar/proyecto/sabana/qr','ControlTiempoQRController@getProyecto');
Route::post('guardar/reg/control/tiempo/qr','ControlTiempoQRController@guardar');

// Reporte de almacen
Route::get('inventario/test', 'InventarioController@Test');
Route::get('inventario/datos', 'InventarioController@RestoreFromServer');
Route::get('proveedores/cargar', 'ProveedoresController@Cargar');
Route::get('/reporteinventario_almacen', 'ReporteAlmacenController@Descargar');
Route::get('barcode/{nombre}', 'BarCodeController@Generate');
Route::get('codigo/{nombre}', 'BarCodeController@Codigo');
Route::get('barcode/imprimir', 'BarCodeController@Zebra');
Route::get('zebra', 'BarCodeController@Zebra');

Route::get('proyectossalidas', 'EntradaSalidaController@getProyectos');
Route::post('guardarproyectossalidas', 'EntradaSalidaController@Guardar');
Route::get('getSalidasRetorno/{proyectoId}', 'EntradaSalidaController@GetSalidas');
Route::get('partidassalida/{proyectoId}', 'EntradaSalidaController@GetPartidas');
Route::get('getpartidasretorno/{request}', 'EntradaSalidaController@GetPartidasRetorno');
Route::post('guardarRetorno', 'EntradaSalidaController@GuardarRetorno');
Route::get('descargar-codigos/inventarios/{id}', 'InventarioController@descargarC');

Route::post('store/facturas/inventario', 'InventarioController@store_facturas');
Route::post('update/facturas/inventario', 'InventarioController@update_facturas');
Route::get('get/facturas/inventario', 'InventarioController@get_facturas');
// Route::get('/factura/inventario/descarga/{id}','InventarioController@descargar');
// Route::get('/factura/inventario/editar/{id}','InventarioController@edit');
// Route::get('descargar-codigos/inventarios','InventarioController@descargarC');

Route::get('/inv/art', 'InventarioController@articulos');
Route::post('/inv/art/store', 'InventarioController@store');
Route::put('/inv/art/update', 'InventarioController@update');
Route::get('/inv/art/categoria', 'InventarioController@categoria');
Route::get('naves/{id}', 'InventarioController@getNaves');
Route::post('/primer/reg/guardar', 'InventarioController@reguardar');
Route::get('inv/get/datainitial', 'InventarioController@initial');
Route::post('guardarphotos', 'InventarioController@guardarphotos');




//Rutas ocupadas para romper la sesion al cerrar navegador/pestaña de la aplicación
Route::get('CerrarSesion', 'Auth\LoginController@cerrarnavegador');
Route::get('ActualizarVista', 'Auth\LoginController@actualizarnavegador');
Route::get('Salir', 'Auth\LoginController@inactividad');
Route::get('Exit', 'Auth\LoginController@logout');
Route::get('Filtro', 'Auth\LoginController@bloquearacceso');
Route::get('Solicitud', 'Auth\LoginController@solicitud');
Route::get('/api/testconser', 'APIControllers\TestController@get');
// CArgar datos
Route::get('/api/load', 'APIControllers\SyncDbController@Load');
// Test
Route::get('/api/test', 'SyncDbController@TestDB');
Route::get('/api/test/barras', 'SyncDbController@barras');
Route::get('/api/test/datos', 'SyncDbController@RestoreFromServer');
Route::get('/api/test/naves', 'SyncDbController@naves');

// Route::get('/api/proyectos/{id}','ProyectosSController@show');
// Route::put('/api/proyectos/','ProyectosSController@update');
//FIN


Route::get('Sistema', 'SistemaController@index')->name('Sistema');

//Login del sistema
Route::get('/', 'Auth\LoginController@showLoginForm');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');

Route::post('login', 'Auth\LoginController@login')->name('login');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Código de productos SAT
Route::get('/codigosat/{desc}', 'ProductosSatController@descripcion');
Route::get('/codigosat/gettipos', 'ProductosSatController@tipos');
Route::get('/codigosat/getdivisiones/{tipo}', 'ProductosSatController@divisiones');
Route::get('/codigosat/getgrupos/{division}', 'ProductosSatController@grupos');
Route::get('/codigosat/getclases/{grupo}', 'ProductosSatController@clases');
Route::get('/codigosat/getcodigos/{clase}', 'ProductosSatController@codigos');
Route::get('/codigosat/getproducto/{id}', 'ProductosSatController@producto');
Route::get('/articulos_sat_contraloria', 'ProductosSatController@getPtoductos');
Route::get('/codigosat_centrocostos', 'ProductosSatController@centroCostos');
Route::put('/codigosat/centrocostos/{ids}', 'ProductosSatController@ActualizarCentro');
Route::put('/codigosat/codigo/{ids}', 'ProductosSatController@actualizarCentroCosto');
// Tipo de proveedor | acreeedor
Route::put('/proveedores/tipo/{ids}', 'ProveedoresController@actualizarTipo');
Route::get('/proveedores/server', 'ProveedoresController@servertable');


//Rutas para visualizar Ayuda.pdf y Politica.pdf
Route::post('privacidad', 'DashboardController@privacidad')->name('privacidad');
Route::post('ayuda', 'DashboardController@ayuda')->name('ayuda');
Route::get('/descargararchivos/{modulo?}/{menu?}/{nombre?}/{tipo?}', 'DashboardController@descargarArchivos');
Route::get('/traerarchivos/{modulo?}/{menu?}/{carpeta?}', 'DashboardController@traerArchivos');
/****************/
Route::get('apis/proyectos', 'ProyectosSController@get');
Route::post('apis/proyectos/guardar', 'ProyectosSController@store');

Route::group(['middleware' => 'auth'], function ()
{
  //nias rutas inventario
  Route::get('/inv/art', 'InventarioController@articulos');
  Route::post('/inv/art/store', 'InventarioController@store');
  Route::get('/inv/art/categoria', 'InventarioController@categoria');
  Route::get('naves/{id}', 'InventarioController@getNaves');
  Route::post('/primer/reg/guardar', 'InventarioController@reguardar');
  Route::get('inv/get/datainitial', 'InventarioController@initial');
  Route::post('guardarphotos', 'InventarioController@guardarphotos');





  //Ruta de FechaActualController
  Route::resource('FechaActual', 'FechaActualController');


  //rutas para permisos de usuario
  Route::resource('PermisoUser', 'PermisoUserController');
  Route::put('PermisoUser/{id}/actualizar', 'PermisoUserController@actualizar');
  Route::put('PermisoUser/{id}/actualizarsub', 'PermisoUserController@actualizarsub');
  Route::get('todospermisos/{id}', 'PermisoUserController@allPermisos');
  Route::resource('permisou/{id}/permisou', 'PermisoUserController');
  Route::get('menusidemp/{id}', 'PermisoUserController@menusidemp');

  //Ruta para asignar permisos de CRUD
  Route::get('elementospormodulos/{id}', 'PermisosCrudController@elementospormodulos');
  Route::get('arreglos', 'PermisosCrudController@obtenerSubmenu');
  Route::get('/permisocrud', 'PermisosCrudController@Permisocrud');
  Route::put('PermisosCrud/{id}/actualizar', 'PermisosCrudController@actualizar');
  Route::post('menusidempc', 'PermisosCrudController@menusidempc');
  Route::post('permisocrud/todos', 'PermisosCrudController@todos');



  //Ruta para validar permisos del CRUD
  Route::resource('CRUD', 'ControlesSistemaController');

  Route::get('elementospormodulo/{id}', 'ElementosMenuController@elementosPorModulo');
  Route::get('elementosmenupormodulo/{id}', 'ElementosMenuController@elementosMenuPorModulo');
  Route::put('eliminarmenu', 'ElementosMenuController@eliminarMenu');
  Route::put('eliminarsubmenu', 'ElementosMenuController@eliminarSubmenu');
  Route::post('agregarmenu', 'ElementosMenuController@agregarMenu');
  Route::put('actualizarmenu', 'ElementosMenuController@actualizarMenu');
  Route::post('agregarsubmenu', 'ElementosMenuController@agregarSubmenu');
  Route::put('actualizarsubmenu', 'ElementosMenuController@actualizarSubmenu');

  //Rutas del ElementosDashboardController para utilizar en ElementosDashboard.vue
  Route::get('/elementosdashboard', 'ElementosDashboardController@index');
  Route::post('/elementosdashboard/registrar', 'ElementosDashboardController@store');
  Route::put('/elementosdashboard/actualizar', 'ElementosDashboardController@update');
  Route::put('/elementosdashboard/eliminar', 'ElementosDashboardController@eliminar');


  //Rutas del PermisosDashboardController para utilizar en PermisosDashboard.vue
  Route::get('/permisosdashboard', 'PermisosDashboardController@index');
  Route::post('/permisosdashboard/registrar', 'PermisosDashboardController@store');
  Route::put('/permisosdashboard/eliminar', 'PermisosDashboardController@eliminar');
  Route::post('/permisosdashboard/porusuariomodulo', 'PermisosDashboardController@permisosDashboardPorUsuarioModulo');

  Route::resource('directorio', 'DirectorioController');
  Route::resource('directoriotelefonico', 'DirectorioTelefonicoController');
  Route::resource('contrato', 'ContratoController');
  Route::get('contratosproxvencer/ver', 'ContratoController@contratosproxvencer');
  Route::get('contrato/{id}/condicionc', 'ContratoController@condicionc');
  Route::get('contrato/{id}/contratosactivos', 'ContratoController@contratosactivos');

  //Ruta para generar el pdf de contratos
  Route::get('descargar-contratop/{id}', 'ContratoPdfController@pdf');
  Route::get('descargar-contratop-csct/{id}', 'ContratoPdfController@pdfCSCT');

  Route::resource('empleado', 'EmpleadoController');
  Route::get('get/contratos/empleado/cfw', 'EmpleadoController@getCFW');
  Route::get('get/contratos/empleado/csct', 'EmpleadoController@getCSCT');
  Route::post("empleado/asignarchecador", "EmpleadoController@AsignarChecador");
  Route::get('vertodosempleados', 'EmpleadoController@vertodosempleados');
  Route::resource('proyecto', 'ProyectoController');
  Route::get('proyecto-listar', 'ProyectoController@listar');
  Route::get('proyecto-pausar/{id}', 'ProyectoController@pausar');
  Route::get('proyecto-rechazar/{id}', 'ProyectoController@rechazar');
  Route::get('proyectos/buscar', 'ProyectoController@buscar');
  Route::get('proyectos/buscar/centrocostos', 'ProyectoController@buscarcentrocostos');
  Route::get('proyectos/buscarcompras', 'ProyectoController@buscarcompras');
  Route::get('proyectos/buscarviaticos/{id}', 'ProyectoController@buscarproyectosviaticos');
  Route::get('/proyectos/ap/', 'ProyectoController@buscarap');
  Route::resource('usuario', 'UserController');
  Route::resource('tipofinalizacion', 'TipoFinalizacionController');
  Route::post('finalizar/contrato/', 'ContratoController@finalizar');
  Route::resource('tipopercepciones', 'TipoPercepcionesController');
  Route::resource('tipodeducciones', 'TipoDeduccionesController');
  Route::resource('tipohorario', 'TipoHorarioController');
  Route::resource('grados', 'GradoController');
  //Rutas del BeneficiarioController para utilizar en Beneficiarios.vue
  Route::resource('beneficiario', 'BeneficiarioController');
  Route::resource('beneficiario/{id}/beneficiario', 'BeneficiarioController');
  //Rutas del BancoController para utilizar en Banco.vue
  Route::resource('banco', 'BancoController');
  //Rutas del FamiliareController para utilizar en Familiares.vue
  Route::resource('familiare', 'FamiliareController');
  Route::resource('familiare/{id}/familiare', 'FamiliareController');
  //Rutas del CorreosController para utilizar en Correos.vue
  Route::resource('correo', 'CorreosController');
  Route::resource('correo/{id}/correo', 'CorreosController');
  //Rutas del TelefonosController para utilizar en Telefonos.vue
  Route::resource('telefono', 'TelefonosController');
  Route::resource('telefono/{id}/telefono', 'TelefonosController');
  //Rutas del EstadoCivilController para utilizar en Empleado.vue
  Route::resource('edocivil', 'EstadoCivilController');
  //Rutas del ExtencionController para utilizar en Pendiente
  Route::resource('extension', 'ExtensionController');
  //Rutas del DescuentoController para utilizar en Descuento.vue
  Route::resource('descuento', 'DescuentoController');
  //Rutas del PagoDescuentoController para utilizar en PagoDescuento.vue
  Route::resource('pagodescuento', 'PagoDescuentoController');
  Route::resource('pagodescuento/{id}/pagodescuento', 'PagoDescuentoController');
  //Rutas del DireccionesEmpleadosController para utilizar en Direccion-Empleado.vue
  Route::resource('direccionempleado', 'DireccionEmpleadoController');
  Route::resource('direccionempleado/{id}/direccionempleado', 'DireccionEmpleadoController');
  //Rutas del DiaController para utilizar en Asistencias.vue
  Route::resource('dia', 'DiaController');
  Route::resource('vacaciones', 'SolicitudesVacacionalesController');
  Route::resource('vacaciones/{id}/vacaciones', 'SolicitudesVacacionalesController');
  Route::get('vacaciones/{id}/autoriza', 'SolicitudesVacacionalesController@autoriza');
  Route::get('vacacionesemp', 'SolicitudesVacacionalesController@empleadosconvacaciones');
  Route::get('descargar-vacaciones/{id}', 'VacacionesPdfController@pdf');
  Route::resource('horario', 'HorarioController');
  //Rutas de ReferenciaController para utilizar en Referencia.vue
  Route::resource('referencia', 'ReferenciaController');
  Route::resource('referencia/{id}/referencia', 'ReferenciaController');
  //Rutas del AsistenciaController para utilizar en Asistencias.vue
  Route::resource('asistencia', 'AsistenciaController');
  Route::get('asistencia/registros/estados', 'AsistenciaController@getEstados');
  Route::get('asistencia/registros/detalles/{id}', 'AsistenciaController@DetallesHorario');
  Route::put('asistencia/registros/actualizar', 'AsistenciaController@Actualizar');
  Route::post('retardosbuscar', 'RetardoController@retardosbuscar');
  Route::post('retardosbuscarempleado', 'RetardoController@retardosBuscarEmpleado');
  Route::get('descargaretardo/{id}', 'RetardoController@descargaRetardo');
  Route::post('horasextrabuscar', 'HorasExtraController@horasExtraBuscar');
  Route::post('horasextrabuscarempleado', 'HorasExtraController@horasextraBuscarEmpleado');
  Route::get('descargarhorasextra/{id}', 'HorasExtraController@descargarHorasExtra');
  Route::resource('registro/{id}/registro', 'RegistroController');
  Route::post('guardar/estado/registros', 'AsistenciaController@guardarEstado');
  Route::post('guardar/nuevo/estado/registros', 'AsistenciaController@guardarNuevoEstado');
  Route::post('guardar/nuevo/observacion/registros', 'AsistenciaController@guardarNuevoObservacion');
  //Rutas del RegistroController para utilizar en Registro.vue
  Route::resource('registro', 'RegistroController');
  //Ruta para obtener Horarios de usuarios
  Route::get('obtenerHorarios', 'RegistroController@clasificarHorarios');
  //Rutas del IndentificacionController para utilizar en Identificacion.vue
  Route::resource('identificacion', 'IdentificacionController');
  Route::resource('identificacion/{id}/identificacion', 'IdentificacionController');
  //Rutas de ContratoController para utilizar en Contratos.vue
  Route::resource('/contratosrenovar', 'ContratoRenovarController');
  Route::get('contrato/{id}/contratobusqueda', 'ContratoController@contratobusqueda');
  Route::resource('contrato/{id}/contratos', 'ContratoController');
  Route::post('renovar/contrato', 'ContratoController@renovar');
  Route::post('norenovar/contrato', 'ContratoController@norenovar');
  Route::post('porenovar/contrato', 'ContratoController@porenovar');
  Route::post('nuevo/contrato', 'ContratoController@nuevoc');
  Route::resource('descuento/{id}/descuento', 'DescuentoController');
  Route::resource('sueldo/{id}/sueldo', 'SueldoController');
  //Rutas de EscolaridadesController para utilizar en Escolaridades.vue
  Route::resource('escolaridad/{id}/escolaridades', 'EscolaridadesController');

  //Rutas de PercepcionController para utilizar en Percepciones.vue
  Route::resource('percepciones', 'PercepcionController');
  //Rutas de DeduccionController para utilizar en Deducciones.vue
  Route::resource('deducciones', 'DeduccionController');

  //Rutas para PermisosEmpleados.vue
  Route::resource('permisoem', 'PermisosEmpController');
  Route::post('busqueda/', 'PermisosEmpController@busquedaPermisos');

  Route::resource('permisos', 'PermisosEmpleadosController');
  Route::resource('permisos/{id}/permisos', 'PermisosEmpleadosController');
  Route::get('permisosver/{id}', 'PermisosEmpleadosController@grv');
  Route::get('descargar-permisosemp/{id}', 'PermisoEmpleadoPDFController@pdf');
  //Rutas de BajasController para utilizar en Bajas.vue
  Route::resource('bajas', 'BajasController');
  //Ruta Ocupada en Empleados.vue
  Route::resource('estadosciviles', 'EdoCivilController');
  //Ruta SueldosController ocupada en Sueldos.vue
  Route::resource('sueldo', 'SueldoController');
  Route::get('buscarsueldocontrato/{id}', 'SueldoController@buscarsueldocontrato');
  Route::post('sueldo', 'SueldoController@store');

  //Ruta del controlador  ExistenciasController para Almacen->Reportes->Existencias.vueformatos
  Route::resource('existencias', 'ExistenciasController');
  Route::get('nivel', 'ExistenciasController@nivelIndex');
  Route::get('stand', 'ExistenciasController@standIndex');
  Route::get('ubicacion', 'ExistenciasController@ubicacionIndex');
  Route::post('existencia', 'ExistenciasController@busquedaExistencias');
  Route::get('descargar-existencia/{id}', 'ExistenciasController@excel');
  Route::get('descargar-existencia-mes/{id}', 'ExistenciasController@excelMes');
  Route::get('descargar-existencia-proyecto/{id}', 'ExistenciasController@excelProyecto');
  Route::get('descargar-existencia-general', 'ExistenciasController@excelGeneral');

  Route::get('/existencia/getlist', 'ExistenciasController@getList');
  Route::get('/existencia/getlist/stand/{id}', 'ExistenciasController@getListStand');
  Route::get('/existencia/getlist/nivele/{id}', 'ExistenciasController@getListNivel');
  Route::get('busquedaE/{id}', 'BusquedaExistenciaController@buscar');
  //Ruta para usar en ExistenciasPrincipal.vue
  Route::resource('articuloexistencia', 'ExistenciasPrincipalController');

  Route::resource('/proyectos', 'ControlDocumentosController');
  Route::post('busquedaproyectocontrol/', 'ControlDocumentosController@busquedaControlP');
  Route::get('busquedaarticulocontrolB/{id}', 'ControlDocumentosController@busquedaControlB');
  Route::get('documentosEntradas/{id}', 'ControlDocumentosController@documentosEntradas');


  //Rutas del EstadoCompraController para utilizar en .vue
  Route::resource('estadocompra', 'EstadoCompraController');
  //Rutas del CatServiciosController para utilizar en .vue
  Route::resource('catservicios', 'CatServiciosController');
  Route::get('/catservicio/busqueda', 'CatServiciosController@busqueda');
  Route::resource('catvehiculos', 'CatVehiculosController');
  Route::get('/catvehiculos/busqueda', 'CatServiciosController@busquedav');

  Route::resource('catmantenimientovehiculos', 'CatManVehiculosController');
  Route::get('/catmantenimientovehiculos/busqueda', 'CatManServiciosController@busquedav');
  //Rutas del EntradaController para utilizar en Entrada.vue
  Route::resource('entrada', 'EntradaController');
  Route::get('entradas/partidas/oc/{id}', 'EntradaController@GetPartidas');
  Route::get('entradas/partidas/oc/actualiza/{id}', 'EntradaController@ActPartidas');
  Route::get('busqueda/entradainterna', 'EntradaController@busquedaEntradaInterna');
  Route::get('descargar-entrada/{id}', 'EntradaPdfController@pdf');
  Route::get('descargar-entrada-nuevo-formato/{id}', 'EntradaPdfController@pdfnew');
  Route::get('verordenescompras', 'EntradaController@verordenescompras');
  Route::get('verfinalizacionpartida/{id}', 'EntradaController@verfinalizacionpartida');
  Route::get('entradas/revisionfactura/{id}', 'EntradaController@revisionfactura');

  //Rutas del TipoEntradaController para utilizar en Entrada.vue
  Route::resource('tipoentrada', 'TipoEntradaController');
  Route::post('/tipoentrada/registrar', 'TipoEntradaController@store');
  Route::put('/tipoentrada/actualizar', 'TipoEntradaController@update');
  //Rutas del TipoAdquisicionController para utilizar en Entrada.vue
  Route::resource('tipoadquisicion', 'TipoAdquisicionController');
  //Rutas del TipoAdquisicionController para utilizar en Entrada.vue
  Route::resource('salida', 'SalidaController');
  Route::get('salida/{id}/sitios', 'SalidaController@sitios');
  Route::get('salida/{id}/resguardo', 'SalidaController@resguardo');

  Route::get('descargar-salida/{id}', 'SalidaPdfController@pdf');
  Route::get('descargar-salida-new/{id}', 'SalidaPdfController@pdfnew');
  Route::get('descargar-salidasitio/{id}', 'SalidaPdfController@pdfsitio');
  Route::get('descargar-salidasitio-new/{id}', 'SalidaPdfController@pdfsitionew');
  Route::resource('partidasalida', 'PartidasSalidasController');
  Route::get('partidasalida/{id}/ver', 'PartidasSalidasController@ver');
  Route::get('getlotetemporal/{id}', 'PartidasSalidasController@getLoteTemporal');
  Route::post('buscarlotenombre', 'PartidasSalidasController@buscarlotenombre');
  Route::get('obtenerarticulog/{id}', 'PartidasSalidasController@obtenerarticulog');
  Route::get('obtenerarticuloa/{id}', 'PartidasSalidasController@obtenerarticuloa');
  Route::get('requisicionbusqueda/{id}', 'PartidasSalidasController@requisicionbusqueda');
  Route::get('eliminar/partida/salida/{id}', 'PartidasSalidasController@eliminarPartida');
  //Rutas del TipoAdquisicionController para utilizar en Entrada.vue
  Route::resource('tiposalida', 'TipoSalidaController');

  Route::resource('partidasalidaapartados', 'PartidasSalidasApartadosController');

  //Rutas de RequisicionController para utilizar en requisiciones.vue
  Route::resource('requisicion', 'RequisicionController');
  Route::resource('requisicioncom', 'RequisicioncomController');
  Route::post('descargaRequi', 'RequisicionController@descargarArchivo');
  Route::resource('requisicion/{id}/requisicion', 'RequisicionController');
  Route::get('vercantidadpartidas/{id}', 'RequisicionController@vercantidadpartidas');
  Route::get('requisicionesporautorizar', 'RequisicionController@requisicionesporautorizar');
  Route::get('requisicionesporvalidar', 'RequisicionController@requisicionesporvalidar');
  Route::get('requisicionescalidadver', 'RequisicionController@requisicionescalidadver');
  Route::post('guardarcantidadalmacen', 'RequisicionController@guardarcantidadalmacen');
  Route::post('autorizarequisicionproyectos', 'RequisicionController@autorizarequisicionproyectos');
  Route::post('requisicionalmacenquitar', 'RequisicionController@requisicionalmacenquitar');
  Route::get('obtenerlotetemporal/{id}', 'RequisicionController@obtenerlotetemporal');
  Route::get('ducumentosrequeridos', 'RequisicionController@ducumentosrequeridos');
  Route::get('ducumentosrequeridosb/{id}', 'RequisicionController@ducumentosrequeridosb');
  Route::get('partidadocumentos/{id}', 'RequisicionController@partidadocumentos');
  Route::resource('documentosrequeridosarticulos', 'DocumentoProveedorController');
  Route::post('/certificadosdocumentos/cargar', 'DocumentoProveedorController@load');
  Route::post('guardarqvalidadocumentos', 'DocumentoProveedorController@guardarqvalidadocumentos');
  Route::get('requisicionesdocumentospendientes', 'DocumentoProveedorController@requisicionesdocumentospendientes');
  Route::get('documentospendientes/{id}', 'DocumentoProveedorController@documentospendientes');
  Route::get('descargardocumentos/{id}', 'DocumentoProveedorController@descargardocumentos');
  Route::get('finalizarequisicion/{id}', 'RequisicionController@finalizarequisicion');
  Route::get('requisicionespendientes', 'RequisicionController@requisicionespendientes');
  Route::get('partidasrequisicionespendientes/{id}', 'RequisicionController@partidasrequisicionespendientes');
  Route::post('cancelarpartidasrequisiciones', 'RequisicionController@cancelarpartidasrequisiciones');
  Route::get('requisicioncompserart/{id}', 'PartidasReController@requisicioncompserart');


  //Rutas del Controller para utilizar en .vueformatos almacen
  Route::resource('tipocompra', 'TipoCompraController');
  //Rutas del Controller para utilizar en .vue
  Route::resource('areasolicitante', 'AreaSolicitanteController');
  //Rutas del Controller para utilizar en .vue
  Route::resource('estadorequisiones', 'EstadoRequisicionController');

  //Ruta Ocupa en Compras ---> Proveedores----> Ordenes.vue
  Route::resource('proveedores', 'ProveedoresController');
  Route::get('get/proveedores/cfw', 'ProveedoresController@proveedoresCFW');
  Route::get('proveedores/activos/{id}', 'ProveedoresController@proveedoresActivos');
  Route::get('get/evaluacion/proveedor/{id}', 'ProveedoresController@getEvaluacion');
  Route::post('guardar/evaluacion/proveedor', 'ProveedoresController@guardarEvaluacion');
  Route::get('descargar/evaluacion/proveedor/{id}', 'ProveedoresController@descargarEvaluacion');
  Route::get('get/datos/bancarios/proveedor/{id}', 'ProveedoresController@getDataBankProveedor');
  // Rutas para portal de proveedores
  Route::get('/proveedores/validaciondatos/obtener', 'ProveedoresController@ObtenerRFCPendientes');
  Route::get('/proveedores/validaciondatos/descargaropinion/{id}', 'ProveedoresController@DescargarOpinion');
  Route::get('/proveedores/validaciondatos/descargarrfc/{id}', 'ProveedoresController@DescargarRFC');
  Route::post('/proveedores/validaciondatos/rechazar', 'ProveedoresController@RechazarDocumento');
  Route::post('/proveedores/validaciondatos/aceptar', 'ProveedoresController@AceptarDocumento');
  //Ruta Ocupa en Compras ---> Catalogos ---> Cotizacion.vue
  Route::resource('cotizacion', 'CotizacionController');
  Route::get('cotizacionesrequeridas/{id}', 'CotizacionController@cotizacionesrequeridas');
  Route::get('obtenerarchivos/{id}', 'CotizacionController@obtenerarchivos');
  Route::get('vercotizacionordencompra/{id}', 'CotizacionController@vercotizacionordencompra');
  //Rutas del PrestamoController para utilizar en Prestamo.vue
  Route::resource('prestamo', 'PrestamoController');
  Route::resource('prestamo/{id}/prestamo', 'PrestamoController');
  Route::get('prestamo/{id}/politica', 'PrestamoController@politica');
  Route::get('prestamo-solicitud/{id}', 'PrestamoController@pdf');
  //Rutas del PagoPrestamoController para utilizar en Pagos.vue
  Route::resource('pagoprestamo', 'PagoPrestamoController');
  //Ruta para Recibos.vue
  Route::resource('recibo/{id}/recibos', 'RecibosController');

  //Ruta para Compras.vue
  Route::resource('compras/{id}/compras', 'ComprasController');
  Route::post('guardarpartidacompracosto', 'ComprasController@guardarpartidacosto');
  Route::get('asignacion_costos_admin/{id}', 'ComprasController@getAsignaciones');
  Route::get('asignacion_costos_admin/eliminar/{id}', 'ComprasController@deleteAsignacion');
  Route::get('condicion_pago/ver', 'ComprasController@condicionpago');
  Route::get('descargar-compra/{id}', 'CompraPdfController@pdf');
  Route::get('descargar-compra-precios/{id}', 'CompraPdfController@pdfcostos');
  Route::get('descargar-compra-gastos/{id}', 'CompraPdfController@gastospdf');
  Route::get('descargar-compran/{id}', 'CompraPdfController@pdfnew');
  Route::get('compras/busqueda/{id}', 'ComprasController@busqueda');
  Route::get('condiciones/pago/asme/{id}', 'ComprasController@condicionesAsme');

  //Ruta para generar el pdf Resguardo de herrameintas.
  Route::get('descargar-resguardo/{id}', 'ResguardoPdfController@pdf');

  //Ruta para compras.vue
  Route::resource('compras', 'ComprasController');
  Route::post('subfactu', 'ComprasController@subFactura');
  Route::get('comprasobtener/{id}', 'ComprasController@ConsultarTotal');
  Route::resource('compraslist', 'ComprasController@Listado');
  Route::get('comprabusquedaimpuesto/{id}', 'ComprasController@impuesto');
  Route::get('impuestoeliminar/{id}', 'ComprasController@impuestoeliminar');
  Route::get('compras-por-proyecto/{id}', 'ComprasController@porProyecto');
  Route::get('trazabilidadcompras/{id}', 'ComprasController@trazabilidad');
  Route::get('trazabilidadcompras/historico/{id}', 'ComprasController@historico');
  Route::get('trazabilidadcompras/costo/{id}', 'ComprasController@trazabilidadcostos');
  Route::get('descargar-excel-compras/{id}', 'ComprasController@Excel');
  Route::get('descargar-excel-trazabilidadcompras/{id}', 'ComprasController@ExcelTrazabilidad');
  Route::get('descargar-excel-historico-compras/{id}', 'ComprasController@HistoricoPdf');
  Route::get('descargar-excel-historico-compras-final/{id}', 'ComprasController@HistoricoExcel');
  Route::get('descargar-excel-trazabilidadcomprasgeneral/{id}', 'ComprasController@ExcelTrazabilidadGeneral');
  Route::get('comprasporproveedor/{id}', 'ComprasController@porProveedor');
  Route::get('compraspartidasproyecto/{id}', 'ComprasController@partidasCompraProyecto');
  Route::get('requisitadocomprado/{id}', 'ComprasController@requisitadocomprado');
  Route::get('compradoscancelados/{id}', 'ComprasController@compradoscancelados');
  //Rutas para ocupar en el dashboard de compras
  Route::get('requisicionesrecibir', 'ComprasController@requisicionesrecibir');
  Route::get('serviciospendientes', 'ComprasController@serviciospendientes');
  Route::post('terminarservicio', 'ComprasController@terminarservicio');
  Route::get('serviciosfinalizados/{id}', 'ComprasController@serviciosfinalizados');
  Route::get('compraseleccionarpartida/{id}', 'ComprasController@compraseleccionarpartida');
  Route::get('buscarocpf/proyecto/{id}', 'ComprasController@buscarocpf');
  Route::post('buscar/articulo/oc', 'ComprasController@compraArticulo');
  Route::post('buscar/articulo/lotes', 'ComprasController@LoteArticulo');
  Route::post('buscar/articulo/requisicion', 'ComprasController@requiArticulo');
  Route::post('buscar/articulo/salidas', 'ComprasController@salidasArticulo');
  Route::post('buscar/proyecto/salidas', 'ComprasController@salidasProyecto');

  //ruta para utilizar en credito.vue
  Route::resource('credito', 'CreditoController');
  //Ruta para requisiciones
  Route::resource('requisicioncompra', 'RequisicionComprasController');
  Route::post('requisicioncompra/apartados', 'RequisicionComprasController@apartadosGuardar');
  Route::post('comprarequivalente', 'RequisicionComprasController@comprarequivalente');
  //Ruta para partidas.vue
  Route::resource('partidare', 'PartidasReController');
  Route::get('/partidare/apartados/{id}', 'PartidasReController@getApartados');
  Route::get('partidare/{id}/activ', 'PartidasReController@activ');
  Route::put('partidare/{id}/updatedoc', 'PartidasReController@updatedoc');
  Route::resource('partidaentrada', 'PartidaEntradaController');
  Route::get('eliminar/partida/entrada/{id}', 'PartidaEntradaController@eliminarPartidaEntrada');
  Route::put('partidaentrada/update/almacen/{id}', 'PartidaEntradaController@updatepr');
  Route::put('partidaentrada/update/factura/{id}', 'PartidaEntradaController@update');
  Route::post('guardarpartidainterna', 'PartidaEntradaController@guardarPartidaInterna');
  // Route::post('partidaentrada/eliminarpartidaentrada', 'PartidaEntradaController@eliminarPartidaEntrada');
  Route::post('partidaentrada/calidad', 'PartidaEntradaController@calidad');
  Route::get('partidaentrada/{id}/calidadsalida', 'PartidaEntradaController@calidadsalida');
  Route::post('partidaentradainterna/actualizar', 'PartidaEntradaController@actualizarPrecioEntradaInterna');
  Route::post('partidaentradainterna/otenercomentario', 'PartidaEntradaController@obtenerComentarioLoteAlmacen');
  Route::get('verordencompra/{id}', 'PartidaEntradaController@verordencompra');
  Route::get('/catpendiente/busqueda', 'PartidaEntradaController@busqueda');
  Route::post('entradas/guardar/actualizacion/almacen', 'PartidaEntradaController@almacenActualizacion');
  //Ruta que permite generar el PDF de Recibos
  Route::get('descargar-recibo/{id}', 'RecibosController@pdf');
  //Rutas de NominaController para utilizar en Nomina.vue
  Route::resource('nomina', 'NominaController');
  Route::resource('nomina/{id}/nomina', 'NominaController');

  // Route::get('nominaejemplo/{id}', 'NominaEjemploController@ver');

  //Rutas del PagoPrestamoController para utilizar en Pagos.vue
  Route::resource('pagoprestamo', 'PagoPrestamoController');
  Route::resource('pagoprestamo/{id}/pagoprestamo', 'PagoPrestamoController');

  //Ruta para el Organigrama
  Route::resource('organigramageneral', 'OrganigramaController');
  //Ruta para utilizar en ModulosSistema.vue
  Route::resource('ModulosSistema', 'ModuloSistemaController');

  // Cargan los modulos del usuario autenticado desde los permisos
  Route::get('/modulos/por-usuario', 'ModuloController@getModulosByUAuthUser');
  Route::post('/modulos/cargar', 'ModuloController@loadModulos');
  // Cargan el menu y submenu desde los permisos del usuario autenticado
  Route::post('/elementosmenu', 'ElementosMenuController@index');

  //Rutas del AlmaceneController para utilizar en Categoria.vue
  Route::get('/almacen/ver', 'AlmaceneController@ver');
  Route::get('/almacen/verstand/{id}', 'AlmaceneController@verstand');
  Route::get('/almacen/vernivel/{id}', 'AlmaceneController@vernivel');
  Route::get('/almacen', 'AlmaceneController@index');
  Route::get('/almacen/inventario', 'AlmaceneController@inventario');
  Route::post('/almacen/registrar', 'AlmaceneController@store');
  // Route::get('/almacen/inventario', 'AlmaceneController@inventario');
  Route::get('/descargar-inventario/{id}', 'AlmaceneController@excel');
  Route::get('descargar-inventarios-general', 'AlmaceneController@excelGeneral');
  Route::get('descargar-inventarios-por-proyecto', 'AlmaceneController@excelProyecto');
  Route::get('/almacen/inventario/buscar/{id}', 'AlmaceneController@buscarInventario');
  Route::post('/almacen/registrar', 'AlmaceneController@store');
  Route::put('/almacen/actualizar', 'AlmaceneController@update');
  Route::put('/almacen/desactivar', 'AlmaceneController@desactivar');
  Route::put('/almacen/activar', 'AlmaceneController@activar');

  Route::post('/almacen/registrar/stand', 'AlmaceneController@storeStand');
  Route::put('/almacen/actualizar/stand', 'AlmaceneController@updateStand');
  Route::post('/almacen/registrar/nivel', 'AlmaceneController@storeNivel');
  Route::put('/almacen/actualizar/nivel', 'AlmaceneController@updateNivel');

  Route::get('/almacen/getlist', 'AlmaceneController@getList');
  Route::get('/almacen/getlist/stand/{id}', 'AlmaceneController@getListStand');

  //rutas para utilizr en el dashboard de Almacen
  Route::get('requisicionesalmacen', 'AlmaceneController@requisicionesalmacen');
  Route::get('requisicionesalmacenpendientes', 'AlmaceneController@requisicionesalmacenpendientes');

  //Rutas del GrupoController para utilizar en Categoria.vue
  Route::get('/grupo', 'GrupoController@index');
  Route::post('/grupo/registrar', 'GrupoController@store');
  Route::put('/grupo/actualizar', 'GrupoController@update');
  Route::put('/grupo/desactivar', 'GrupoController@desactivar');
  Route::put('/grupo/activar', 'GrupoController@activar');
  Route::get('/grupo/getlist/{id}', 'GrupoController@getList');
  Route::get('/grupo/find/{id}', 'GrupoController@find');

  //Rutas del LoteController para utilizar en Lote.vue
  Route::get('/lote', 'LoteController@index');
  Route::post('/lote/registrar', 'LoteController@store');
  Route::put('/lote/actualizar', 'LoteController@update');
  Route::put('/lote/desactivar', 'LoteController@desactivar');
  Route::put('/lote/activar', 'LoteController@activar');
  Route::post('/lote/articulo', 'LoteController@getByArticulo');

  //Rutas del CategoriaController para utilizar en Categoria.vue
  Route::get('/categoria', 'CategoriaController@index');
  Route::post('/categoria/registrar', 'CategoriaController@store');
  Route::put('/categoria/actualizar', 'CategoriaController@update');
  Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
  Route::put('/categoria/activar', 'CategoriaController@activar');
  Route::get('/categoria/getlist', 'CategoriaController@getList');

  //Rutas del ModuloController para utilizar en Usuario.vue
  Route::get('/modulo', 'ModuloController@index');
  Route::post('/modulo/registrar', 'ModuloController@store');
  Route::put('/modulo/actualizar', 'ModuloController@update');
  Route::put('/modulo/desactivar', 'ModuloController@desactivar');
  Route::put('/modulo/activar', 'ModuloController@activar');


  //Rutas del Articulocontroller para utilizar en Articulo.vue
  Route::resource('/articulos', 'ArticuloController');
  Route::get('/articulo', 'ArticuloController@index');
  Route::post('/articulo/registrar', 'ArticuloController@store');
  // Route::put('/articulo/actualizar', 'ArticuloController@update');
  Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
  Route::put('/articulo/activar', 'ArticuloController@activar');
  Route::put('/articulo/upload', 'ArticuloController@uploadArticulos');
  Route::get('/articulo/busqueda', 'ArticuloController@busqueda');
  Route::post('/articulo/existencias', 'ArticuloController@existencias');
  Route::get('/movimientos/kardex', 'ArticuloController@kardex');
  Route::get('/articulo/maximos', 'ArticuloController@maximos');
  Route::get('/articulo/minimos', 'ArticuloController@minimos');
  Route::get('/articulo/proximoscaducar', 'ArticuloController@proximosCaducar');
  Route::get('/articulo/caducados', 'ArticuloController@caducados');

  //Rutas del proyecto para utilizar en proyecto.vue
  Route::get('/proyecto', 'ProyectoController@index');
  Route::get('/proyecto/permisos/categoria', 'ProyectoController@proyectosPermisos');
  Route::post('/proyecto/registrar', 'ProyectoController@store');
  Route::put('/proyecto/actualizar/{id}', 'ProyectoController@update');
  Route::put('/proyecto/eliminar/{id}', 'ProyectoController@eliminar');
  Route::put('/proyecto/desactivar', 'ProyectoController@desactivar');
  Route::put('/proyecto/activar', 'ProyectoController@activar');
  Route::get('/proyecto-todos', 'ProyectoController@todos');
  Route::get('/proyecto-listar-todos', 'ProyectoController@listarTodos');
  // Route::get('/proyecto-listar-todos-empresa/{id}', 'ProyectoController@listarTodosEmpresa');
  Route::get('/principales', 'ProyectoController@principales');
  Route::get('/proyectosmaster', 'ProyectoController@proyectosMaster');
  Route::post('proyecto-obtenerarchivos/', 'ProyectoController@obtenerarchivos');
  Route::get('/proyecto/sum/{id}', 'ProyectoController@sumamontos');
  Route::post('/proyecto/subir/documento/','ProyectoController@subirDocumento');
  Route::get('/proyecto-obtener-documentos/{id}','ProyectoController@getDocumentosProyectos');
  Route::get('delete/documento/proyecto/{id}','ProyectoController@deleteDocumentosProyectos');
  Route::get('proyecto-delete-temporal/{id}','ProyectoController@deleteDocTempProyectos');


  //Rutas del StockController para utilizar en Stock.vue
  Route::get('/stock', 'StockController@index');
  Route::post('/stock/registrar', 'StockController@store');
  Route::put('/stock/actualizar', 'StockController@update');
  Route::put('/stock/desactivar', 'StockController@desactivar');
  Route::put('/stock/activar', 'StockController@activar');
  Route::get('/stock/getlist', 'StockController@getList');

  //Rutas del TipoNominaController para utilizar en TipoNomina.vue
  Route::get('/tiponomina', 'TipoNominaController@index');
  Route::post('/tiponomina/registrar', 'TipoNominaController@store');
  Route::put('/tiponomina/actualizar', 'TipoNominaController@update');
  Route::get('/tiponomina/getlist', 'TipoNominaController@getList');

  //Rutas del TipoUbicacionController para utilizar en TipoUbicacion.vue
  Route::get('/tipoubicacion', 'TipoUbicacionController@index');
  Route::post('/tipoubicacion/registrar', 'TipoUbicacionController@store');
  Route::put('/tipoubicacion/actualizar', 'TipoUbicacionController@update');
  Route::get('/tipoubicacion/getlist', 'TipoUbicacionController@getList');

  //Rutas del TipoContratoController para utilizar en TipoContrato.vue
  Route::get('/tipocontrato', 'TipoContratoController@index');
  Route::post('/tipocontrato/registrar', 'TipoContratoController@store');
  Route::put('/tipocontrato/actualizar', 'TipoContratoController@update');
  Route::get('/tipocontrato/getlist', 'TipoContratoController@getList');
  Route::get('descargar-canoen/{id}', 'ContratoController@pdfcne');
  Route::get('descargar-cainin/{id}', 'ContratoController@pdfcii');
  Route::get('descargar-cacomp/{id}', 'ContratoController@pdfcom');
  Route::get('descargar-voluntaria/{id}', 'ContratoController@pdfvol');

  //Rutas del DireccionesAdministrativasController para utilizar en DireccionesAdministrativas.vue
  Route::get('/direccionadmin', 'DireccionesAdministrativasController@index');
  Route::post('/direccionadmin/registrar', 'DireccionesAdministrativasController@store');
  Route::put('/direccionadmin/actualizar', 'DireccionesAdministrativasController@update');
  Route::get('/direccionadmin/getlist', 'DireccionesAdministrativasController@getList');

  //Rutas del TipoDescuentoController para utilizar en TipoDescuento.vue
  Route::get('/tipodescuento', 'TipoDescuentoController@index');
  Route::post('/tipodescuento/registrar', 'TipoDescuentoController@store');
  Route::put('/tipodescuento/actualizar', 'TipoDescuentoController@update');
  Route::get('/tipodescuento/getlist', 'TipoDescuentoController@getList');

  //Rutas del ExpedienteController para utilizar en Expedientes.vue
  Route::put('/expediente/actualizar', 'ExpedienteController@update');
  Route::get('/expediente/{id}', 'ExpedienteController@get');

  Route::put('/expediente/upload', 'ExpedienteController@uploadEmpleados');

  Route::get('/descargar-exp/{id}', 'ExpedienteController@expedientePdf');

  //Rutas del DepartamentoController para utilizar en Departamento.vue
  Route::get('/departamento', 'DepartamentoController@index');
  Route::post('/departamento/registrar', 'DepartamentoController@store');
  Route::put('/departamento/actualizar', 'DepartamentoController@update');
  Route::put('/departamento/desactivar', 'DepartamentoController@desactivar');
  Route::put('/departamento/activar', 'DepartamentoController@activar');
  Route::get('/departamento/getlist', 'DepartamentoController@getList');
  Route::get('/departamento/get/all', 'DepartamentoController@getAll');

  //Rutas del PuestoController para utilizar en puesto.vue
  Route::get('/puesto', 'PuestoController@index');
  Route::post('/puesto/registrar', 'PuestoController@store');
  Route::put('/puesto/actualizar', 'PuestoController@update');
  Route::put('/puesto/desactivar', 'PuestoController@desactivar');
  Route::put('/puesto/activar', 'PuestoController@activar');
  Route::get('/puesto/getlist', 'PuestoController@getList');
  //Rutas del BeneficiarioController para utilizar en Beneficiario.vue
  //Rutas de BeneficiarioController para utilizar en Beneficiario.vue
  Route::get('/beneficiario', 'BeneficiarioController@index');
  Route::post('/beneficiario/registrar', 'BeneficiarioController@store');
  Route::put('/beneficiario/actualizar', 'BeneficiarioController@update');
  Route::get('/beneficiario/getlist', 'BeneficiarioController@getList');
  Route::get('/beneficiario/activar', 'BeneficiarioController@activar');
  Route::get('/beneficiario/desactivar', 'BeneficiarioController@desactivar');

  //Rutas de SolicitudesVacacionalesController para utilizar en Solicitudes.vue

  //Rutas de ReferenciaController para utilizar en Referencias.vue
  Route::get('/referencia', 'ReferenciaController@index');
  Route::post('/referencia/registrar', 'ReferenciaController@store');
  Route::put('/referencia/actualizar', 'ReferenciaController@update');
  Route::get('/referencia/getlist', 'ReferenciaController@getList');
  Route::get('/referencia/activar', 'ReferenciaController@activar');
  Route::get('/referencia/desactivar', 'ReferenciaController@desactivar');
  //Rutas del ExperienciasLaboralesController para utilizar en Escolaridades.vue
  Route::get('/explab/{id}', 'ExperienciasLaboralesController@index');
  Route::post('/explab/registrar', 'ExperienciasLaboralesController@store');
  Route::put('/explab/actualizar', 'ExperienciasLaboralesController@update');
  Route::put('/explab/activar', 'ExperienciasLaboralesController@activar');
  Route::put('/explab/desactivar', 'ExperienciasLaboralesController@desactivar');

  //Rutas del ContactoController para utilizar en Contactos.vue
  Route::get('/contacto', 'ContactoController@index');
  Route::get('/contacto/{id}', 'ContactoController@get');
  Route::put('/contactoemp/actualizar', 'ContactoController@update');

  //Rutas del ConocidoController para utilizar en Conocidos.vue
  Route::get('/conocido/{id}', 'ConocidoController@index');
  Route::post('/conocido/registrar', 'ConocidoController@store');
  Route::put('/conocido/actualizar', 'ConocidoController@update');
  Route::put('/conocidos/eliminar','ConocidoController@Eliminar');

  //Rutas del DatosBancariosEmpleadoController para utilizar en DatosBancariosEmpleados.vue
  Route::resource('datosbanemp', 'DatosBancariosEmpleadoController');
  Route::get('/bancos', 'DatosBancariosEmpleadoController@bancos');
  Route::get('/datosbanemp', 'DatosBancariosEmpleadoController@index');
  Route::put('/datosbanemp/actualizar', 'DatosBancariosEmpleadoController@update');
  Route::post('/datosbanemp/registrar', 'DatosBancariosEmpleadoController@store');
  Route::get('/datosbanemp/{id}/datosbanemp', 'DatosBancariosEmpleadoController@get');
  Route::get('/datosbanempreportepdf/{id}', 'DatosBancariosEmpleadoController@pdfDatosBancarios');
  Route::post('/datosbanempreporte', 'DatosBancariosEmpleadoController@datosBancariosReporte');

  //Ruta SueldosController ocupada en Sueldos.vue
  Route::resource('sueldocontra', 'SueldoController');
  Route::get('/sueldocontra', 'SueldoController@index');
  Route::put('/sueldocontra/actualizar', 'SueldoController@update');
  Route::post('/sueldocontra/registrar', 'SueldoController@store');
  Route::get('/sueldocontra/{id}/sueldocontra', 'SueldoController@get');

  //Rutas del PrestamoController para utilizar en Prestamo.vue
  Route::get('/prestamo', 'PrestamoController@index');
  Route::post('/prestamo/registrar', 'PrestamoController@store');
  Route::put('/prestamo/actualizar', 'PrestamoController@update');
  Route::get('/prestamo/getlist', 'PrestamoController@getList');
  Route::get('/pdfprestamosdepto/{pagado}/{fecha_inicial}/{fecha_final}/{fecha_pago}/{departamento_id}', 'PrestamoController@pdfPrestamosPorDepto');
  Route::post('/prestamoreportedepto', 'PrestamoController@reportePrestamosPorDepto');

  //Rutas del PagoPrestamoController para utilizar en Pagos.vue
  Route::get('/pagoprestamo', 'PagoPrestamoController@index');
  Route::post('/pagoprestamo/registrar', 'PagoPrestamoController@store');
  Route::put('/pagoprestamo/actualizar', 'PagoPrestamoController@update');
  Route::get('/pagoprestamo/getlist', 'PagoPrestamoController@getList');

  Route::get('descargar-cv/{id}', 'EmpleadoController@pdf');
  Route::get('descargar-alt/{id}', 'EmpleadoController@pdfalt');
  Route::get('descargar-requisicionew/{id}', 'RequisicionPdfController@pdfnew');
  Route::get('descargar-requisicion/{id}', 'RequisicionPdfController@pdf');

  Route::get('descargar-requisicion-asme/{id}', 'RequisicionPdfAsmeController@pdfasme');

  Route::post('/auditoria', 'AuditoriaController@index');
  Route::post('/errors', 'ErrorsController@index');
  Route::get('/historico/{id}', 'EmpleadoController@historico');

  //Rutas del módulo Tráfico
  Route::resource('UnidadesStore', 'UnidadesController');
  Route::resource('mantenimientounidades', 'MantenimientoUnidadesController');
  Route::resource('serviciounidades', 'ServicioUnidadesController');
  Route::get('catalogoclasetipo', 'UnidadesController@catalogoclasetipo');
  Route::get('catalogocombustible', 'UnidadesController@catalogocombustible');
  // Route::resource('ConductoresStore', 'ConductoresController');
  Route::get('get/conductores', 'ConductoresController@index');
  Route::post('guardar/conductor', 'ConductoresController@create');
  Route::put('actualizar/conductor', 'ConductoresController@actualizar');
  Route::get('get/imagenes/conductores/{id}', 'ConductoresController@getImagenes');
  Route::get('delete/imagenes/conductores/{id}', 'ConductoresController@deleteImagenes');
  Route::get('licenciadescarga/{id}', 'ConductoresController@descargar');
  Route::get('licenciaeditar/{id}', 'ConductoresController@editar');


  Route::resource('polizasunidades', 'PolizaUnidadesController');
  Route::get('polizasunidadesdescarga/{id}', 'PolizaUnidadesController@descarga');
  Route::get('polizaeditar/{id}', 'PolizaUnidadesController@editar');

  Route::resource('verificacionunidades', 'VerificacionUnidadesController');
  Route::get('verificacionunidadesdescarga/{id}', 'VerificacionUnidadesController@descarga');
  Route::get('verificacioneditar/{id}', 'VerificacionUnidadesController@editar');

  Route::resource('tenenciaunidades', 'TenenciaUnidadesController');
  Route::get('tenenciaunidadescarga/{id}', 'TenenciaUnidadesController@descarga');
  Route::get('tenenciaeditar/{id}', 'TenenciaUnidadesController@editar');

  Route::get('propietariosUnidades', 'UnidadesController@propietariosUnidades');
  Route::get('unidadesbuscarusr/{id}', 'UnidadesController@unidadesbuscarusr');

  Route::get('unidadreportebuscar/{id}', 'UnidadesReporteController@buscar');

  Route::get('/porautorizar/vacaciones', 'SolicitudesVacacionalesController@getListaEnEsperaPorAutorizar');
  Route::post('/autorizado/vacacion', 'SolicitudesVacacionalesController@Autorizado');
  Route::post('/noautorizado/vacacion', 'SolicitudesVacacionalesController@NoAutorizado');

  //Cumpleaños
  Route::resource('cumpleañosmes', 'CumpleañosController');
  Route::get('cumplemespdf', 'CumpleañosController@pdf');

  //Rutas de BajasController para utilizar en Bajas.vue
  //Rutas de BajasController para utilizar en Bajas.vue
  Route::resource('bajas', 'BajasController');
  Route::resource('bajas/{id}/baja', 'BajasController');

  Route::get('/porautorizar/permisos', 'PermisosEmpleadosController@getListaEnEsperaPorAutorizar');
  Route::post('/autorizado/permiso', 'PermisosEmpleadosController@Autorizado');
  Route::post('/noautorizado/permiso', 'PermisosEmpleadosController@NoAutorizado');

  Route::post('/congoce/permiso', 'PermisosEmpleadosController@ConGoce');
  Route::post('/singoce/permiso', 'PermisosEmpleadosController@SinGoce');

  //Rutas de TipoCalidadController pars utilizar en TipoCalidad.vue
  Route::resource('tipocalidad', 'TipoCalidadController');
  Route::post('/tipocalidad/registrar', 'TipoCalidadController@store');
  Route::put('/tipocalidad/actualizar', 'TipoCalidadController@update');

  //Rutas de TipoResguardoController para usar en TipoResguardo.vue
  Route::resource('tipoResguardo', 'TipoResguardoController');
  Route::post('/tipoResguardo/registrar', 'TipoResguardoController@store');
  Route::put('/tipoResguardo/actualizar', 'TipoResguardoController@update');

  //Rutas del módulo TI.
  Route::resource('/equipos', 'EquiposController');
  Route::resource('/impresion', 'ImpresionController');
  Route::resource('/celulares', 'CelularesController');
  Route::resource('/proyeccion', 'ProyeccionController');
  Route::resource('/herramienta', 'HerramientaController');
  Route::resource('/valesalida', 'ValeSalidaController');
  Route::resource('/software', 'SoftwareController');

  Route::resource('/accesorio', 'AccesorioController');
  Route::resource('/tiposw', 'TipoSWController');
  Route::resource('/tipoequipo', 'TipoEquipoController');
  Route::resource('/clasificacion', 'ClasificacionController');
  Route::resource('/tiposervicio', 'TipoServicioController');

  //Rutas de EmpresasController para utilizar en Empresas.vue
  Route::resource('/empresas', 'EmpresasController');

  Route::resource('/preventivos', 'PreventivosController');
  Route::resource('/correctivos', 'CorrectivosController');

  //Nomina Semanal Empleados
  Route::resource('nominasemanal', 'NominaSemanalController');
  Route::resource('nominasemanal/{id}/nominasemanal', 'NominaSemanalController');
  Route::put('nominasemanal/{id}/editarnominasemanal', 'NominaSemanalController@editarnominasemanal');
  Route::get('descargar-excel-semanal/{id}', 'NominaSemanalController@excel');
  Route::get('descargar-excel-semanal-confin/{id}', 'NominaSemanalController@exceluno');
  Route::get('descargar-excel-semanal-con/{id}', 'NominaSemanalController@exceldos');
  Route::get('nominasemanal/proyecto/{id}', 'NominaSemanalController@proyecto');
  Route::get('nominasemanal/busqueda_por_proyecto/{id}', 'NominaSemanalController@busqueda_por_proyecto');
  Route::get('nominaexcels/{id}', 'NominaSemanalController@nominaexcel');
  Route::get('nominasbusqueda/{id}', 'NominaSemanalBController@busqueda');
  Route::get('sueldos/semanales', 'NominaSemanalController@getSemanas');
  Route::get('sueldos/semanales/empleados/{id}', 'NominaSemanalController@getSemanasEmpleado');

  Route::post('guardar/sd/nomina/semanal','NominaSemanalController@saveSD');
  Route::post('guardar/dias/trabajados/nomina/semanal','NominaSemanalController@saveDT');
  Route::post('guardar/descuento/nomina/semanal','NominaSemanalController@saveD');
  Route::post('guardar/infonavit/nomina/semanal','NominaSemanalController@saveI');
  Route::post('guardar/viatico/alimentos/nomina/semanal','NominaSemanalController@saveVA');
  Route::post('guardar/total/nomina/semanal','NominaSemanalController@saveT');
  Route::post('guardar/banco/nomina/semanal','NominaSemanalController@saveB');
  Route::post('guardar/nuevo/banco/nomina/semanal','NominaSemanalController@saveNB');
  Route::post('agregar/nuevo/banco/nomina/semanal','NominaSemanalController@saveANB');
  // Route::post('cambiar/banco/nomina/semanal','No');





  //Nomina Quincenal Empleados
  Route::resource('nominaquincenal', 'NominaQuincenalController');
  Route::resource('nominaquincenal/{id}/nominaquincenal', 'NominaQuincenalController');
  Route::put('nominaquincenal/{id}/editarnominaquincenal', 'NominaQuincenalController@editarnominaquincenal');
  Route::get('descargar-excel-quincenal/{id}', 'NominaQuincenalController@excel');
  Route::get('descargar-excel-quincenal-confin/{id}', 'NominaQuincenalController@exceluno');
  Route::get('descargar-excel-quincenal-con/{id}', 'NominaQuincenalController@exceldos');
  Route::get('nominaquincenal/proyecto/{id}', 'NominaQuincenalController@proyecto');
  Route::get('nominaquincenal/busqueda_por_proyecto/{id}', 'NominaQuincenalController@busqueda_por_proyecto');
  Route::get('nominaexcelq/{id}', 'NominaQuincenalController@nominaexcel');
  Route::get('nominaqbusqueda/{id}', 'NominaQuincenalBController@busqueda');
  Route::get('nominagbusqueda/{id}', 'NominaGeneralBController@busqueda');
  Route::get('nominaexcelg/{id}', 'NominaGeneralController@nominaexcel');
  Route::get('sueldos/quincenal', 'NominaQuincenalController@getQuincenas');
  Route::get('sueldos/quincenal/empleados/{id}', 'NominaQuincenalController@getQuincenasEmpleado');

  Route::post('guardar/sd/nomina/quincena','NominaQuincenalController@saveSD');
  Route::post('guardar/dias/trabajados/nomina/quincena','NominaQuincenalController@saveDT');
  Route::post('guardar/descuento/nomina/quincena','NominaQuincenalController@saveD');
  Route::post('guardar/infonavit/nomina/quincena','NominaQuincenalController@saveI');
  Route::post('guardar/viatico/alimentos/nomina/quincena','NominaQuincenalController@saveVA');
  Route::post('guardar/total/nomina/quincena','NominaQuincenalController@saveT');
  Route::post('guardar/banco/nomina/quincena','NominaQuincenalController@saveB');
  Route::post('guardar/nuevo/banco/nomina/quincena','NominaQuincenalController@saveNB');
  Route::post('agregar/nuevo/banco/nomina/quincena','NominaQuincenalController@saveANB');

  /*Catálogo Tipos de Finalizacion de Contrato*/
  // Route::resource('tipofinalizacion', 'TipoFinalizacionController');

  // Finiquito
  Route::post('/finiquito', 'FiniquitoController@finiquito');
  Route::get('/finiquitoporempleado/{id}', 'FiniquitoController@finiquitosPorEmpleado');
  Route::get('finiquitos/{id}', 'FiniquitoController@pdf');
  Route::resource('finiquito', 'FiniquitoController');
  Route::get('/empleadosfiniquitos', 'FiniquitoController@index');
  Route::post('/finiquitosproyecto', 'FiniquitoController@reporteFiniquitosProyecto');
  Route::get('/finiquitosexcel/{fecha_inicial}/{fecha_final}/{proyecto_id}', 'FiniquitoController@excel');

  //Agenda
  Route::resource('/agendasrecurrentes', 'AgendaController');
  Route::post('/agendapagosnr', 'AgendaController@GuardarAgendaPagoNR');
  //Catálogo de pagos recurrentes
  Route::resource('/pagosrecurrentesmen', 'PagosRecurrentesController');

  //RegistroResguardoController
  Route::resource('/registroresguardo', 'RegistroResguardoController');
  //Pagos no recurrentes
  Route::resource('/pagosnorecurrentes', 'PagosNoRecurrentesController');
  Route::get('pagoNoRecurrentepago/{id}', 'PagosNoRecurrentesController@pagoNoRecurrentepago');
  Route::get('/comprasautorizadasdasboard', 'PagosNoRecurrentesController@comprasautorizadasdasboard');
  Route::get('/comprasautorizadas', 'PagosNoRecurrentesController@comprasautorizadas');
  Route::get('comprasautorizadasid/{id}', 'PagosNoRecurrentesController@comprasautorizadas_by_id');
  Route::get('compraporautorizar', 'PagosNoRecurrentesController@compraporautorizar');
  Route::get('compraporautorizarid/{id}', 'PagosNoRecurrentesController@compraporautorizar_by_id');
  Route::post('/autorizarcompra', 'PagosNoRecurrentesController@autorizarcompra');
  Route::get('/comprasasignadas', 'PagosNoRecurrentesController@comprasasignadas');
  Route::get('get/tipos/facturapago', 'PagosComprasController@getfacurapago');


  //Rutas para utilizar en Materiales.vue
  Route::resource('salidasporproyecto', 'PreciosController');
  Route::get('descargar-materiales/{id}', 'PreciosController@descargar');

  //Rutas para utilizar en Bancos_Contabilidad
  Route::resource('bancoscontabilidad', 'BancosContabilidadController');

  //Ratas para utilizar en Clientes.vue
  Route::resource('clientes', 'ClientesController');
  //Rutas del PagosComprasController para utilizar en ElementosDashboard.vue
  Route::get('/pagoscompras/{id}', 'PagosComprasController@index');
  Route::post('/pagoscompras/registrar', 'PagosComprasController@store');
  Route::put('/pagoscompras/actualizar', 'PagosComprasController@update');
  Route::put('/pagoscompras/eliminar', 'PagosComprasController@eliminar');

  //Rutas para utilizar en PagosClientes.vue
  Route::resource('registropagos', 'RegistroPagosController');
  Route::get('clientepago/{id}', 'RegistroPagosController@clientepago');
  Route::get('registropagosdasboard', 'RegistroPagosController@registropagosdasboard');

  //Rutas para utilizar en pagos Clientes
  Route::get('/pagos/clientes', 'PagosClientesController@index');
  Route::post('/pagos/clientes/store', 'PagosClientesController@store');
  Route::put('/pagos/clientes/update', 'PagosClientesController@update');
  Route::get('/pagos/clientes/delete/{id}', 'PagosClientesController@delete');


  //Rutas del TipoPartidaController para utilizar en Tipo-partida.vue
  Route::get('/tipopartidacostos', 'TipoPartidaController@index');
  Route::post('/tipopartidacostos/registrar', 'TipoPartidaController@store');
  Route::put('/tipopartidacostos/actualizar', 'TipoPartidaController@update');

  //Rutas del PartidasCostosController para utilizar en costos/Partidas.vue
  Route::get('/partidascostos/{id}', 'PartidasCostosController@index');
  Route::post('/partidascostos/registrar', 'PartidasCostosController@store');
  Route::put('/partidascostos/actualizar', 'PartidasCostosController@update');
  Route::get('/partidasnodoscostos/{id}', 'PartidasCostosController@partidasnodoscostos');
  Route::post('/partidascostos/registrarnodo', 'PartidasCostosController@insertar');
  Route::put('/partidascostos/actualizarnodo', 'PartidasCostosController@actualizarnodo');
  Route::get('/partidascostosnodos/{id}', 'PartidasCostosController@partidascostosnodos');
  Route::get('/partidascostos/sum/{id}', 'PartidasCostosController@sum');
  Route::get('partidascostosno/{id}', 'CostosNodosController@arbol');
  Route::get('/centrocostosgraficas/{id}', 'PartidasCostosController@centrocostosgraficas');

  //Rutas para utilizar en Catalogos Recibos nominas
  Route::resource('factorintegracion', 'FactorIntegracionController');

  //Rutas para utilizar en Catalogos Recibos nominas
  Route::resource('imss', 'ImssController');

  //Rutas para utilizar en Catalogos Recibos nominas
  Route::resource('subsidioempleo', 'SubsidioEmpleoController');

  //Rutas para utilizar en Catalogos Recibos nominas
  Route::resource('tarifamensual', 'TarifaMensualController');

  //Rutas para ocupar en Traspasos.vue
  Route::resource('traspaso', 'TraspasoController');
  Route::get('stockubicacion/{id}', 'TraspasoController@stockubicacion');
  Route::get('ubicaciontraspaso', 'TraspasoController@ubicaciontraspaso');
  Route::get('enviartraspaso/{id}', 'TraspasoController@enviartraspaso');
  Route::get('verpartidasvalidacion/{id}', 'TraspasoController@verpartidasvalidacion');

  //Rutas para ocupar en DetalleTraspasos.vue
  Route::resource('partidatraspasos', 'PartidasTraspasoController');
  Route::post('corregirpartidatraspaso', 'PartidasTraspasoController@corregirpartidatraspaso');
  Route::get('eliminarpartidatraspaso/{id}', 'PartidasTraspasoController@eliminarpartidatraspaso');


  //Rutas del ElementosDashboardController para utilizar en Folio.vue
  Route::get('/folio', 'FolioController@index');
  Route::post('/folio/registrar', 'FolioController@store');
  Route::put('/folio/actualizar', 'FolioController@update');
  Route::put('/folio/eliminar', 'FolioController@eliminar');

  //Rutas para utilizar en transito.vue
  Route::get('traspasosentransito', 'TraspasoController@traspasosentransito');
  Route::post('almacenartraspaso', 'TraspasoController@almacenartraspaso');
  Route::get('perdido/{id}', 'TraspasoController@perdido');
  Route::get('daniado/{id}', 'TraspasoController@daniado');
  Route::get('finalizar/{id}', 'TraspasoController@finalizar');
  Route::get('veratendidos/{id}', 'TraspasoController@veratendidos');

  //Rutas para cupar en perdododaniado
  Route::resource('traspasosdaoper', 'TraspasosDaniadoPerdidoController');

  //rutas para ocupar en precio.vue ddentro de articulos (precio de venta)
  Route::resource('precioventa', 'PrecioVentaController');
  Route::get('precioventabusqueda', 'PrecioVentaController@obtenerPreciosVenta');
  Route::get('busquedapv', 'PrecioVentaController@busqueda');

  //rutas para utilizar en los dasboard de proyectos
  Route::resource('consultadashp', 'ConsultaDashProyectosController');
  Route::post('agregar/correciones/partidas', 'ConsultaDashProyectosController@comentarioPartidas');
  Route::get('documentosdashproyectos/{id}', 'ConsultaDashProyectosController@documentosdashproyectos');
  Route::get('retornopartidasporproyecto/{id}', 'RetornoController@partidasPorProyecto');
  Route::get('retornoporproyecto/{id}', 'RetornoController@getRetornoProyecto');
  Route::post('guardarpartidaretorno', 'RetornoController@guardarPartidaRetorno');
  Route::post('enviarcomentariorechazorequi','RequisicionController@comentarioGral');


  //Rutas del ProyectoCategoriasController para utilizar en ProyectoCategoria.vue
  Route::get('/procategoria', 'ProyectoCategoriasController@index');
  Route::post('/procategoria/registrar', 'ProyectoCategoriasController@store');
  Route::put('/procategoria/actualizar', 'ProyectoCategoriasController@update');
  Route::get('/procategoria/getlist', 'ProyectoCategoriasController@getList');

  //Rutas del ProyectoSubcategoriasController para utilizar en ProyectoCategoria.vue
  Route::get('/prosubcategoria', 'ProyectoSubcategoriasController@index');
  Route::post('/prosubcategoria/registrar', 'ProyectoSubcategoriasController@store');
  Route::put('/prosubcategoria/actualizar', 'ProyectoSubcategoriasController@update');
  Route::get('/prosubcategoria/getlist', 'ProyectoSubcategoriasController@getList');
  Route::get('/prosubcategoria/getlistbycat/{id}', 'ProyectoSubcategoriasController@getListByCategoria');

  //Rutas del UsuarioCategoriaController para utilizar en PermisosDashboard.vue
  Route::get('/usuariocategoria', 'UsuarioCategoriaController@index');
  Route::post('/usuariocategoria/registrar', 'UsuarioCategoriaController@store');
  Route::put('/usuariocategoria/eliminar', 'UsuarioCategoriaController@eliminar');
  Route::post('/usuariocategoria/todos', 'UsuarioCategoriaController@todos');

  //ruta para Control de tiempo
  /*    Route::resource('busquedaCt/{id}','ControlTiempoController@getControl');*/

  Route::get('/controltiempo', 'ControlTiempoController@index');
  Route::get('/controltiempo/busqueda', 'ControlTiempoController@busqueda');
  Route::get('/controltiempo/busqueda/getEmp', 'ControlTiempoController@getEmp');
  Route::get('/controltiempo/busqueda/th/{id}', 'ControlTiempoController@getTH');
  Route::post('guardar/horas/hr', 'ControlTiempoController@horas');
  Route::post('guardar/proyecto/hr', 'ControlTiempoController@proyecto');
  Route::post('guardar/tiempo/extra', 'ControlTiempoController@GuardarHE');
  Route::post('buscar/registros/supervisor/', 'ControlTiempoController@buscarSuper');
  Route::get('/descargar-control/{id}', 'ControlTiempoController@excel');
  Route::get('/descargar-control-completo/{id}', 'ControlTiempoController@excelCompleto');
  Route::get('/descargar-control-general/{id}', 'ControlTiempoController@excelGeneral');
  Route::get('/descargar-control-general-proyecto/{id}', 'ControlTiempoController@excelGeneralProyecto');
  Route::get('/descargar-control-general-proyecto-admin/{id}', 'ControlTiempoController@excelGeneraAdmin');
  Route::get('/descargar-control-general-proyecto-sin/{id}', 'ControlTiempoController@excelGeneraProsin');
  Route::get('/descargar-reporte-empleados-super/{id}', 'ControlTiempoController@excelReporteListas');
  Route::get('/descargar-reporte-empleados-tiempos/{id}', 'ControlTiempoController@excelReporteTiempo');
  Route::get('descargar/excel/trazabilidadcomprasgeneral/totales/{id}', 'ComprasController@excelTotales');
  Route::get('/descargar-control-semanal/{id}', 'ControlTiempoController@excelSemanal');
  Route::get('personalaCargoEmpleados', 'ControlTiempoController@personalaCargoEmpleados');
  Route::post('intervalo', 'ControlTiempoController@compararFechas');
  Route::get('empleados/fecha/ct/{id}', 'ControlTiempoController@getEmpleadoste');

  Route::get('pdas', 'RequisicionController@pdas');

  //rutas para utilizar en viaticos
  Route::resource('viaticos', 'ViaticosController');
  Route::get('viaticos/ver/solicitudes/{id}', 'ViaticosController@getSol');
  Route::get('verviaticos/{id}', 'ViaticosController@verviaticos');
  Route::post('rechazareporte', 'ViaticosController@rechazareporte');
  Route::get('pagosviaticos/{id}', 'ViaticosController@pagosviaticos');
  Route::get('verfacturasduplicadas/{id}', 'ViaticosController@verfacturasduplicadas');
  Route::get('facturaduplicadaguardar', 'ViaticosController@facturaduplicadaguardar');
  Route::get('obtenerfacturareporte/{id}', 'ViaticosController@obtenerfacturareporte');
  Route::get('descargar/reporte/general/viaticos/{id}', 'ViaticosController@descargarReporteGral');
  Route::get('descargar/reporte/general/viaticos/pdf/{id}', 'ViaticosController@descargarReporteGralPdf');

  //rutas para utilizar en partidas_viaticos
  Route::resource('partidaviaticospagos', 'PartidasViaticosPagosController');
  Route::put('partidaviaticospagos/actualizar/{id}', 'PartidasViaticosPagosController@actualizarpago');
  Route::delete('eliminarfacturapago/{id}', 'PartidasViaticosPagosController@elimina');
  Route::get('descargarfacturapago/{id}', 'PartidasViaticosPagosController@descargarfactura');
  Route::get('enviarcorreopago/{id}', 'PartidasViaticosPagosController@enviarCorreosPago');
  Route::get('partidasviaticospagos/revisiones/{id}', 'PartidasViaticosPagosController@revisionpagos');



  //rutas para utilizar en solicitud Viaticos
  Route::get('conceptosviaticos', 'CatalogoConceptosViaticosController@listaConceptos');
  Route::resource('solicitudviaticos', 'SolicitudViaticosController');
  Route::get('solicitud/viaticos/csct', 'SolicitudViaticosController@getCSCT');
  Route::get('solicitud/viaticos/conserflow', 'SolicitudViaticosController@getConserflow');
  Route::post('estadosviaticos', 'SolicitudViaticosController@estados');
  Route::get('viaticosenrevision', 'SolicitudViaticosController@viaticosenrevision');
  Route::get('viaticosporautorizar', 'SolicitudViaticosController@viaticosporautorizar');
  Route::get('proyectos/viaticos/{id}', 'SolicitudViaticosController@proyectos');
  Route::get('empleados/viaticos/{id}', 'SolicitudViaticosController@empleados');
  Route::get('eliminar/solicitud/viaticos/{id}', 'SolicitudViaticosController@eliminar');

  //rutas para ocupar en ReporteGastosViaticos
  Route::resource('reportegastosviaticos', 'ReporteGastosViaticosController');
  Route::get('eliminareportegastos/{id}', 'ReporteGastosViaticosController@eliminararch');
  Route::get('eliminareportegastosviaticos/{id}', 'ReporteGastosViaticosController@eliminar');
  Route::get('enviarreporteparcial/{id}', 'ReporteGastosViaticosController@enviarReporteParcial');
  Route::get('reportepagosenviados/{id}', 'ReporteGastosViaticosController@reportepagosenviados');
  Route::get('descargarfacturareporte/{id}', 'ReporteGastosViaticosController@descargarfactura');
  Route::get('reportegastosviaticos/porproyecto/{id}', 'ReporteGastosViaticosController@viaticoProyecto');

  // CRUD de viaticos
  Route::get("/viaticos/registro/obtener","ReporteViaticosController@Obtener");
  Route::post("/viaticos/registro/registrar","ReporteViaticosController@Registrar");
  //rutas para utilizar en solicitud Viaticos
  Route::get('conceptosviaticos', 'CatalogoConceptosViaticosController@listaConceptos');
  //Rutas del TipoServicioTraficoController para utilizar en Tipo-partida.vue
  Route::get('/tiposerviciotrafico', 'TipoServicioTraficoController@index');
  Route::post('/tiposerviciotrafico/registrar', 'TipoServicioTraficoController@store');
  Route::put('/tiposerviciotrafico/actualizar', 'TipoServicioTraficoController@update');

  //Rutas del ServicioTraficoController para utilizar en ServicioTrafico.vue
  Route::get('/serviciotrafico/{id}', 'ServicioTraficoController@index');
  Route::post('/serviciotrafico/registrar', 'ServicioTraficoController@store');
  Route::put('/serviciotrafico/actualizar', 'ServicioTraficoController@update');

  //Rutas del PartidaServicioTraficoController para utilizar en PartidaServicioTrafico.vue
  Route::get('/parservtrafico/{id}', 'PartidaServicioTraficoController@index');
  Route::post('/parservtrafico/registrar', 'PartidaServicioTraficoController@store');
  Route::put('/parservtrafico/actualizar', 'PartidaServicioTraficoController@update');
  Route::post('/parservtrafico/borrar', 'PartidaServicioTraficoController@delete');

  Route::get('catalogostrafico', 'CatalogoTraficoController@ver');

  //Ruta para ContactoCompraController
  Route::get('/contactoc/{id}', 'ContactoCompraController@index');
  Route::post('/contacto', 'ContactoCompraController@store');
  Route::put('/contacto/actualizar', 'ContactoCompraController@update');

  //Ruta para subida de transferencia csv
  Route::put('/transfer/upload', 'TransferenciaController@uploadTransferencia');
  Route::get('transferencia/', 'TransferenciaController@listaArticulos');
  Route::get('partidasTaller/', 'TransferenciaController@obtenerporProyecto');
  Route::get('compratransfer', 'TransferenciaController@partidasCompras');

  //Rutas del SatCatFormapagoController para utilizar en SatCatFormaPago.vue
  Route::get('/satcatformpago', 'SatCatFormapagoController@index');
  Route::post('/satcatformpago/registrar', 'SatCatFormapagoController@store');
  Route::put('/satcatformpago/actualizar', 'SatCatFormapagoController@update');

  //Rutas del SatCatProdserController para utilizar en SatCatProdser.vue
  Route::get('/satcatprodser', 'SatCatProdserController@index');
  Route::post('/satcatprodser/registrar', 'SatCatProdserController@store');
  Route::put('/satcatprodser/actualizar', 'SatCatProdserController@update');


  //Rutas del SatCatUsoCfdi para utilizar en ...
  Route::get('/datosgenerales', 'CatalogoSatFacturaController@datosgenerales');
  Route::get('/satcatusocfdi', 'CatalogoSatFacturaController@usocfdi');
  Route::get('/satcatmonedas', 'CatalogoSatFacturaController@satcatalogomonedas');
  Route::get('/satcatmetodopago', 'CatalogoSatFacturaController@satcatmetodopago');
  Route::get('/satcattipofactura', 'CatalogoSatFacturaController@satcattipofactura');
  Route::get('/satcatprodser', 'CatalogoSatFacturaController@satcatprodser');
  Route::get('/satcatunidades', 'CatalogoSatFacturaController@satcatunidades');
  Route::get('/satcattiporelacion', 'CatalogoSatFacturaController@satcattiporelacion');
  Route::get('/catfactura', 'CatalogoSatFacturaController@catfactura');
  Route::get('/satcatdeduccion', 'CatalogoSatFacturaController@satcatdeduccion');
  Route::get('/satcatpercepcion', 'CatalogoSatFacturaController@satcatpercepcion');

  Route::resource('/partidafactura', 'PartidasFacturaController');

  Route::get('/sellartimbrarfactura/{id}', 'FacturaSellarTimbrarController@sellartimbrar');
  Route::get('/descargarfactura/{id}', 'FacturaSellarTimbrarController@descargarfactura');
  Route::get('/descargarprefactura/{id}', 'FacturaSellarTimbrarController@descargarprefactura');
  Route::get('/partidafactura/cancelarfactura/{id}', 'FacturaSellarTimbrarController@cancelarfactura');
  //Ratas para utiizar en facturas
  Route::resource('factura', 'FacturaController');
  Route::get('verfacturauno/{id}', 'FacturaController@verfacturauno');
  Route::get('clientextranjero', 'FacturaController@clientextranjero');
  Route::resource('datosgeneral', 'DatosGeneralesController');
  Route::resource('partidafacturapagos', 'PartidasFacturasPagosController');
  Route::get('timbresrestantes', 'FacturaController@timbresrestantes');
  Route::get('descargarfacturareportexml/{id}', 'FacturaController@descargarfacturaxml');
  Route::delete('eliminarfacturareportexml/{id}', 'FacturaController@destroyxml');
  // Route::get('/leerxml','TelefonosController@leerxml');

  //Rutas para Control de Nómina
  Route::get('empleadoNominaC/', 'ControlNominaController@principalEmpleado');
  Route::post('/controlNom/registrar', 'ControlNominaController@store');
  Route::put('/controlNom/actualizar/{id}', 'ControlNominaController@update');
  Route::get('/empleadoPrincipal/{id}', 'ControlNominaController@index');
  Route::get('empleadoReporte/{id}', 'ControlNominaController@generaReporte');
  Route::get('/verReporte/{id}', 'ControlNominaController@verReporte');
  Route::get('empleadosmuchos', 'ControlNominaController@nominaSupervisor');


  Route::get('/informe/{id}', 'InformeController@index');
  Route::get('descargarformatoviatico/{id}', 'ViaticosController@descargarViaticos');
  Route::get('descargarnformatofij/{id}', 'ViaticosController@descargarnFij');


  //Rutas del OrdenVentaController para utilizar en OrdenVenta.vue
  Route::get('/ordenventa', 'OrdenVentaController@index');
  Route::post('/ordenventa/registrar', 'OrdenVentaController@store');
  Route::put('/ordenventa/actualizar', 'OrdenVentaController@update');

  //Rutas para modulo Capacitación en RH
  Route::get('capacitacion', 'PlanCapacitacionController@index');
  Route::post('/capacitacion/registrar', 'PlanCapacitacionController@store');
  Route::put('/capacitacionUpd/actualizar/{id}', 'PlanCapacitacionController@update');
  Route::resource('infEmp', 'InfCapacitacionController');
  Route::resource('infEmpresa', 'InfCapEmpresaController');
  Route::put('/capacitacionEl/eliminar/', 'PlanCapacitacionController@eliminar');

  //Rutas para el componente Detalle de Capacitacion
  Route::get('/detalles/{id}', 'DetalleCapacitacionController@index');
  Route::post('/detallec/registrar', 'DetalleCapacitacionController@store');
  Route::put('/detalleactualizacion/actualizar/{id}', 'DetalleCapacitacionController@update');
  Route::put('/eliminardetalle/eliminar/', 'DetalleCapacitacionController@eliminar');
  Route::post('/detallecap/documento', 'DetalleCapacitacionController@subdocumentos');
  Route::resource('detallesc', 'DetalleCapacitacionController');

  Route::get('/ventasarticulos', 'VentasArticulosController@getArticulos');

  //Rutas ṕara utilizar en inspeccion
  Route::resource('inspeccion', 'InspeccionController');
  Route::get('inspeccion/proyecto/{id}', 'InspeccionController@proyecto');
  Route::get('servicioselementos', 'InspeccionController@servicioselementos');
  Route::put('/inspeccion/update/{id}', 'InspeccionController@actualizar');
  Route::get('/partidas/juntas/{id}', 'PartidasInspeccionController@juntas');
  Route::get('soldadores/obtener', 'SoldadoresController@obtener');
  Route::get('partidasinpeccion/get/{id}', 'PartidasInspeccionController@get');
  Route::post('partidasinpeccion', 'PartidasInspeccionController@store');
  Route::put('/partidasinpeccion/update/{id}', 'PartidasInspeccionController@actualizar');
  // Route::get('de');
  Route::get('/trazabilidad/{id}', 'TrazabilidadSoldadurasController@pdf');
  Route::get('/visual/{id}', 'VisualSoldadurasController@pdf');
  Route::post('/soldador/registrar', 'SoldadoresController@registrar');
  Route::put('/soldador/actualizar/{id}', 'SoldadoresController@actualizar');
  Route::get('materialservicios', 'MaterialServicioSoldaduraController@get');
  Route::post('/juntas/registrar', 'MaterialServicioSoldaduraController@registrar');
  Route::put('/juntas/actualizar/{id}', 'MaterialServicioSoldaduraController@actualizar');

  //ordenes de compras sin requisiciones
  Route::resource('partidacomprasinrequisicion', 'CompraSinRequiController');
  Route::get('partidacomprasinrequisicion/eliminar/{id}', 'CompraSinRequiController@eliminar');

  Route::resource('correosprogramados', 'CorreosProgramadosController');

  Route::resource('digito', 'DigitoController');
  Route::get('codigoagrupador', 'DigitoController@codigoagrupador');
  Route::get('codigoagrupador/{id}', 'DigitoController@get');
  Route::post('codigoagrupador', 'DigitoController@guardar');
  Route::put('codigoagrupador/actualizar/', 'DigitoController@put');
  Route::post('digitoniveldos', 'DigitoController@digitoniveldosguardar');
  Route::put('digitoniveldos/actualizar', 'DigitoController@digitoniveldosactualizar');
  Route::post('guardarasignacion', 'DigitoController@guardarasignacion');
  Route::get('eliminarasignacion/{id}', 'DigitoController@eliminarasignacion');

  //Rutas para Supervisor de empleado
  Route::get('supervisorAsigna/', 'SupervisorController@index');
  Route::resource('supervisor', 'SupervisorController');
  Route::get('supervisorCT/', 'ControlTiempoController@supervisor');
  Route::resource('controltiemporesource', 'ControlTiempoController');
  Route::get('/get/incidencia/tiempos/', 'ControlTiempoController@getIncidencias');
  Route::get('controltiempo/busqueda', 'ControlTiempoController@busqueda');

  //Ruta para guardar proyecto agrupador
  Route::post('guardarAgrupador', 'ProyectoAgrupadorController@guardaAgrupador');
  Route::get('listaAgrupador/', 'ProyectoAgrupadorController@listaProyectos');

  Route::get('estatusmaterial/{id}', 'EstatusMaterialController@estatus');
  //
  // Route::get('ejemploe','EjemploController@index');
  // Route::post('/ejemplo/guardar','EjemploController@store');
  // Route::put('/ejemplo/actualizar','EjemploController@update');

  Route::put('/edenredempleados/upload', 'EdenRedUserController@uploadEmpleados');
  Route::put('/edenredmoviemientos/upload', 'EdenRedUserController@uploadMovimientos');
  Route::put('/edenredmoviemientosenc/upload', 'EdenRedUserController@uploadEnc');
  Route::get('edenredempleados/get', 'EdenRedUserController@get');
  Route::get('edenredmovimientos/show/{id}', 'EdenRedUserController@show');

  Route::put('/juntas/upload', 'JuntasController@uploadJuntas');
  Route::get('/listarjuntas', 'JuntasController@index');

  // Route::resource('/entrada/salidas/sitio','EntradaSitoController');

  Route::post('/registrajunta', 'JuntasController@store');
  Route::put('/actualizajunta/{id}', 'JuntasController@update');

  Route::get('/relacionspool', 'RelacionSpoolController@index');
  Route::post('/registraspool', 'RelacionSpoolController@store');
  Route::put('/actualizaspool/{id}', 'RelacionSpoolController@update');

  Route::get('listacolada', 'ColadasController@index');
  Route::post('/registracolada', 'ColadasController@store');
  Route::put('/actualizacolada/{id}', 'ColadasController@update');


  Route::resource('FacturasStore', 'SubirfacturasController');
  Route::get('ordenproyecto/{id}', 'SubirfacturasController@ordenporProyecto');

  Route::get('/descripcion', 'ColadasController@descripcion');
  Route::resource('ColadaStore', 'ColadasController');

  Route::get('facturasentradas/{id}', 'FacturasEntradasController@show');
  Route::get('facturasentradasdescarga/{id}', 'FacturasEntradasController@descargar');
  Route::get('facturasentradaseditar/{id}', 'FacturasEntradasController@edit');
  Route::post('facturasentradas', 'FacturasEntradasController@store');
  Route::post('facturasentradas/update', 'FacturasEntradasController@actualizarfactura');
  Route::get('facturasentradas/delete/{id}', 'FacturasEntradasController@delete');
  Route::get('facturasentradas', 'FacturasEntradasController@index');
  Route::get('facturaimpuestoseliminar/{id}', 'FacturasEntradasController@eliminarimpuesto');
  Route::get('facturasentradas/obtener/impuesto/{id}', 'FacturasEntradasController@getimpuestos');


  Route::get('facturaimpuestos', 'ImpuestosFacturasController@index');
  Route::post('/facturaimpuestos/registrar', 'ImpuestosFacturasController@store');
  Route::put('/facturaimpuestos/actualizar', 'ImpuestosFacturasController@update');
  Route::get('/facturaimpuestos/eliminar/{id}', 'ImpuestosFacturasController@eliminar');



  Route::resource('cuadrillaresource', 'CuadrillaController');
  Route::get('/supervisores/cuadrilla', 'CuadrillaController@supervisores');
  Route::get('/listacuadrilla', 'CuadrillaController@index');
  Route::get('empleadosinasignar', 'CuadrillaController@sinasignar');
  Route::get('cuadrillaresource/delete/{id}', 'CuadrillaController@eliminar');

  Route::post('sueldos_costos', 'SueldoCostosController@store');
  Route::get('get/sueldos/costos', 'SueldoCostosController@index');
  Route::get('eliminar/sueldos/costos/{id}','SueldoCostosController@delete');

  Route::get('/reporte', 'ReporteUuidController@reporte');

  Route::get('/reportefactura', 'ReporteFacturaController@reporteFacturas');

  Route::get('/consulta/articulos', 'RequisicionComprasController@articulosconsulta');

  Route::get('catalogos/centrocostos', 'CatalogoCentroCostosController@index');
  Route::get('catalogos/centrocostos/show', 'CatalogoCentroCostosController@show');
  Route::post('catalogos/centrocostos/registrar', 'CatalogoCentroCostosController@store');
  Route::get('activar/desactivar/catalogo/{id}', 'CatalogoCentroCostosController@activardesactivar');

  Route::post('centrocostos/asignar', 'CentroCostosController@store');
  Route::get('centrocostos/ver/{id}', 'CentroCostosController@show');
  Route::get('centrocostos/eliminar/{id}', 'CentroCostosController@destroy');

  // Route::get('prueba','PruebaController@index');
  // Route::post('prueba/guardar','PruebaController@store');
  // Route::put('prueba/actualizar/','PruebaController@update');
  // Route::get('prueba/activardesactivar/{id}','PruebaController@edit');
  // Route::put('/prueba/upload','PruebaController@upload');
  // Route::put('/prueba/uploaduno','PruebaController@upload_uno');

  Route::put('/registronominasemanal/upload', 'AsistenciaController@upload');
  Route::get('buscar/nomina/{id}', 'AsistenciaController@show');
  Route::post('buscar/nomina/empleado/', 'AsistenciaController@findE');
  Route::get('resgistro/empleado/{id}', 'AsistenciaController@registro');

  Route::post('guardaregistros', 'RegistroController@post');
  Route::get('descargar-excel-rh/{id}', 'AsistenciaController@generareporte');

  Route::get('/listaasignacion', 'AsignacionVehiculoController@listadoEmpleado');
  Route::get('/listapoliza', 'AsignacionVehiculoController@resguardovehiculo');
  Route::get('descargar-valeresguardoT/{id}', 'AsignacionVehiculoController@pdfcne');

  Route::get('/listarcaracteristicas', 'CaracteristicasVehiculoController@listarCaracteristicasV');
  Route::get('/listadounidades', 'CaracteristicasVehiculoController@listadoUnidades');
  Route::post('/registracaracteristicas', 'CaracteristicasVehiculoController@store');
  Route::put('/actualizacaracteristicas/{id}', 'CaracteristicasVehiculoController@update');

  Route::put('/partidasfacturasexcel/upload', 'PartidasFacturaController@subirExcel');
  Route::put('actualizarcompra/fp', 'ComprasController@actualizarfp');
  Route::get('pagosporautorizar', 'PagosAutorizarController@index');
  Route::get('autorizarpagos/{id}', 'PagosAutorizarController@autorizarpagos');
  Route::get('pagosvencidos', 'PagosAutorizarController@pagosvencidos');

  Route::get('controltiempo/centrocostos/master/{id}', 'CentroCostosController@controltiempoMaster');

  Route::put('/registroviaticosgastosmenores/upload', 'ViaticosController@registro');
  Route::get('/registroviaticosgastosmenores/ver', 'ViaticosController@ver');

  Route::post('reportegastosmenoresviaticos', 'ViaticosController@guardarComprobacion');
  Route::get('ver/comprobaciones/gastosmenoresviaticos/{id}', 'ViaticosController@verComprobaciones');
  Route::get('descargareporte/gastosmenores/{id}', 'ViaticosController@descargarfactura');
  Route::get('eliminareporte/gastosmenores/{id}', 'ViaticosController@eliminararch');

  Route::get('busquedaentradainternas/', 'HelpController@busqueda');

  Route::get('requisicionefixed', 'RepetidosController@requisiciones');
  Route::get('facturasentradasfixed/{id}', 'RepetidosController@facturasentradasfixed');

  Route::get('/compras/trazabilidad/{id}', 'ComprasController@comprastrazabilidad');
  Route::get('/compras/trazabilidad/pro/{id}', 'ComprasController@comprastrazabilidadproveedores');
  Route::get('/compras/status/pro/{id}', 'ComprasController@estatus');
  Route::get('agregar/proveedor/real','ComprasController@agregar_proveedor_real');

  //Rutas para las requisiciones espejo
  Route::get('requisicion/espejo/{id}', 'RequisicionEspejoController@show');


  Route::put('/registrosueldosimss/upload', 'SueldosImssController@upload');
  Route::put('/registrosueldossemanales/upload', 'SueldosImssController@uploadsemana');
  Route::put('/registrosueldosquincenal/upload', 'SueldosImssController@uploadquincena');
  Route::put('/registrogastosesemanales/upload', 'SueldosImssController@uploadsemanagastose');
  Route::put('/registrogastoquincenales/upload', 'SueldosImssController@uploadquincenagastose');
  Route::put('/registrogastosempresas/upload', 'SueldosImssController@uploadgastos');
  Route::get('/descargar/reporte/gastos/{id}', 'SueldosImssController@descargarReporteGastos');


  Route::get('descargar-requisicionporcompra/{id}', 'RequisicionporCompraController@pdfGenerado');
  Route::get('listadorequisicionporcompra/{id}', 'RequisicionporCompraController@listadoCompras');

  Route::get('buscaroc/proveedor/{id}', 'ComprasController@buscarproveedor');
  Route::get('buscar/estado/proveedor/{id}', 'ComprasController@busquedaestadoproveedor');
  Route::get('descargar-consulta-estado-proveedor/{id}', 'ComprasController@descargarEstadoPro');


  //Rutas para el calculos de gastos, sueldos y movimientos de proyectos
  Route::get('registro/encabezado/sueldostiempo/gp/{id}', 'RegistrosFixedController@generalpro');
  Route::get('registro/encabezado/sueldostiempo/admin/{id}', 'RegistrosFixedController@admin');
  Route::get('registro/encabezado/sueldostiempo/sinpro/{id}', 'RegistrosFixedController@sinpro');

  Route::get('registro/encabezado/cargotopo/{id}', 'RegistrosFixedController@cargotopo');


  Route::get('registro/encabezado/gastos/{id}', 'RegistrosFixedController@gastos');
  Route::get('registro/encabezado/gastos/administracion/{id}', 'RegistrosFixedController@gastosAdministracion');
  Route::get('registro/encabezado/gastos/finanzas/{id}', 'RegistrosFixedController@gastosFinanzas');
  Route::get('registro/encabezado/gastos/seguridad/{id}', 'RegistrosFixedController@gastosSeguridad');
  Route::get('registro/encabezado/gastos/vehiculos/{id}', 'RegistrosFixedController@gastosVehiculos');
  Route::get('registro/encabezado/gastos/ventas/{id}', 'RegistrosFixedController@gastosVentas');
  Route::post('/registros/sueldos/tiempos/', 'RegistrosFixedController@Actualizar');

  Route::get('/registros/caja/chica/proyectos/{id}', 'RegistrosFixedController@cajachicaproyectos');
  Route::get('/registros/caja/chica/{id}', 'RegistrosFixedController@cajachica');

  Route::get('/registros/combustible/proyectos/{id}', 'RegistrosFixedController@combustibleproyecto');
  Route::get('/registros/combustible/{id}', 'RegistrosFixedController@combustible');

  Route::get('/registros/pro/estatus/{id}', 'RegistrosFixedController@cargarPro');



  Route::put('guardaractualizapartida', 'RequisicionComprasController@actualizarpartidacompra');

  Route::post('/guardar/cantidad/almacen/', 'RequisicionController@guardarCA');

  Route::get('entradas/suministros', 'EntradasSuministrosController@index');
  Route::post('entradas/suministros/guardar', 'EntradasSuministrosController@create');
  Route::put('entradas/suministros/actualizar', 'EntradasSuministrosController@update');

  Route::get('descargar-excel-comprado-requisitado/{id}', 'ReporteriaController@requisitadocomprado');
  Route::get('descargar-excel-estatus-de-material/{id}', 'ReporteriaController@estatusmaterial');
  Route::get('descargar-excel-estatus-de-material-requisitado/{id}', 'ReporteriaController@estatusmaterialrequisitado');

  Route::get('get/barras/art/{id}', 'LotesBarrasSalidasController@getN');
  Route::get('get/lote/art/{id}', 'LotesBarrasSalidasController@getNL');
  Route::get('get/barras/art/retorno/{id}', 'LotesBarrasSalidasController@getNR');
  Route::get('get/lote/art/retorno/{id}', 'LotesBarrasSalidasController@getNLR');
  Route::post('get/barras/art/guardar', 'LotesBarrasSalidasController@store');
  Route::get('buscar/salidas/dia/barras', 'LotesBarrasSalidasController@getSalidasDia');
  Route::get('descargar/codigo/barras/{id}', 'LotesBarrasSalidasController@getBarras');
  Route::get('/generar/codigos/{id}', 'LotesBarrasSalidasController@getBarrasC');

  //Rutaas para gastos empresas
  Route::put('/registrosgastosempresa/upload', 'GastosEmpresasController@upload');
  Route::get('get/oc/gastos/{id}', 'GastosEmpresasController@getoc');
  Route::get('get/oc/gastos/general/{id}', 'GastosEmpresasController@getocGral');
  Route::get('get/oc/gastos/ocs/{id}', 'GastosEmpresasController@getocs');
  Route::get('facturasgastosoc/{id}', 'GastosEmpresasController@getfacturasoc');
  Route::post('facturasgastos/create', 'GastosEmpresasController@create');
  Route::post('guardar/folio/asociado', 'GastosEmpresasController@guardarfaoc');
  Route::post('registrar/datos/gastos', 'GastosEmpresasController@guardar');
  Route::get('/get/gastos/xml/datos/{id}','GastosEmpresasController@getData');

  //rutas para gastos empresa
  Route::get('compras/gastos/{id}', 'PartidasOrdenCompraGastoController@show');
  Route::post('partida/compra/gasto/create', 'PartidasOrdenCompraGastoController@create');
  Route::get('partida/orden/compra/gasto/eliminar/{id}', 'PartidasOrdenCompraGastoController@delete');

  Route::put('/xml/upload', 'XmlController@cargar');
  Route::get('/semana/get/reporte/{id}', 'XmlController@getReporte');
  Route::get('/semana/get/report/calcular', 'XmlController@calcular');
  Route::get('descargar-reporte-semana/{id}', 'XmlController@generar');
  Route::get('descargar-reporte-quincena/{id}', 'XmlController@generarq');

  Route::get('descargar-entrada-salida-vale/{id}', 'AlmaceneController@ValeRetorno');

  Route::get('obtener/mano/obra/operativo/{id}', 'RegistrosFixedController@getObraOperativos');
  Route::get('obtener/mano/obra/administrativos/{id}', 'RegistrosFixedController@getSumaAdministrativos');
  Route::get('obtener/mano/obra/sinproyecto/{id}', 'RegistrosFixedController@getSumaSinProyecto');
  Route::get('obtener/total/gastos/{id}', 'RegistrosFixedController@getSumaGastos');
  Route::get('obtener/total/gastos/admin/{id}', 'RegistrosFixedController@getSumaGastosAdmin');
  Route::get('obtener/total/gastos/finanzas/{id}', 'RegistrosFixedController@getSumaGastosFinan');
  Route::get('obtener/total/gastos/seguridad/{id}', 'RegistrosFixedController@getSumaGastosSegu');
  Route::get('obtener/total/gastos/vehiculos/{id}', 'RegistrosFixedController@getSumaGastosVehi');
  Route::get('obtener/total/gastos/ventas/{id}', 'RegistrosFixedController@getSumaGastosVen');
  Route::get('obtener/total/gastos/caja/chica/{id}', 'RegistrosFixedController@getSumaCajaChica');
  Route::get('obtener/total/gastos/combustible/{id}', 'RegistrosFixedController@getSumaCombustible');
  Route::get('obtener/material/almacen/{id}', 'RegistrosFixedController@getMaterialAlmacen');
  Route::get('obtener/material/almacen/suma/{id}', 'RegistrosFixedController@getSumaMaterialAlmacen');

  Route::get('generar/codigos/qr/emp/{id}', 'CodigosQRController@generar');
  Route::put('cambiarestadorequi', 'RequisicionController@cambiarestado');

  // Rutas Calidad
  Route::get('/yolo/{id}', 'Calidad\InspeccionesController@Folio');
  Route::get('/calidad/inspecciones/reportes/{id}', 'Calidad\InspeccionesController@Reportes');
  Route::get('/calidad/inspecciones/obtenerproyectos', 'Calidad\InspeccionesController@Proyectos');
  Route::get('/calidad/inspecciones/obteneroc/{id}', 'Calidad\InspeccionesController@ObtenerOCS');
  Route::get('/calidad/inspecciones/obtenerrime/{id}', 'Calidad\InspeccionesController@ObtenerRimes');
  Route::get('/calidad/inspecciones/articulos/{id}', 'Calidad\InspeccionesController@ObtenerPartidasOC');
  Route::post('/calidad/inspecciones/guardar', 'Calidad\InspeccionesController@Guardar');
  Route::get('/calidad/inspecciones/descargar/{id}', 'Calidad\InspeccionesController@Descargar');
  Route::get('/calidad/inspecciones/descargar-certificado/{id}', 'Calidad\InspeccionesController@DescargarCertificado');

  Route::post('caja/chica/guardar/solicitud/', 'CajaChicaController@post');
  Route::put('caja/chica/actualizar/solicitud/', 'CajaChicaController@update');
  Route::get('get/listado/caja/chica/cfw', 'CajaChicaController@getCFW');
  Route::get('get/listado/caja/chica/csct', 'CajaChicaController@getCSCT');
  Route::get('enviar/revision/caja/chica/{id}', 'CajaChicaController@revision');
  Route::get('descargar/solicitud/caja/chica/{id}', 'CajaChicaController@descarga');
  Route::get('/caja/chica/validar', 'CajaChicaController@validar');
  Route::get('/caja/chica/autoriza', 'CajaChicaController@validar');
  Route::get('/caja/chica/comprobacion/{id}', 'CajaChicaController@comprobacion');
  Route::post('caja/chica/comprobacion/store', 'CajaChicaController@store');
  Route::put('caja/chica/comprobacion/update', 'CajaChicaController@actualiza');
  Route::get('caja/chica/comprobacion/eliminar/{id}', 'CajaChicaController@eliminacomprobacion');
  Route::get('caja/chica/comprobacion/cerrar/{id}', 'CajaChicaController@cerrarcomprobacion');
  Route::get('caja/chica/comprobacion/validar/', 'CajaChicaController@validarcomprobacion');
  Route::get('caja/chica/pagos/{id}', 'CajaChicaController@pagos');
  Route::get('caja/chica/revision','CajaChicaController@revisionDep');
  Route::get('validar/material/cajachica/{id}','CajaChicaController@validarMaterial');
  Route::post('rechazar/material/cajachica/','CajaChicaController@rechazarMaterial');
  Route::get('get/motivo/rechazo/comprobacion/caja/chica/{id}','CajaChicaController@getMotivoRechazo');
  Route::get('caja/chica/get/empleado','CajaChicaController@getEmpleado');
  Route::get('get/listado/caja/chica/cfw/control', 'CajaChicaController@getControlCFW');
  Route::get('get/listado/caja/chica/csct/control', 'CajaChicaController@getControlCSCT');
  Route::post('caja/chica/guardar/pago/','CajaChicaController@guardarPago');
  // Reporte de existencias en almacen
  Route::get('/reportes/existencias/almacenes', 'PartidaEntradaController@Almacenes');
  Route::get('/reportes/existencias/categorias', 'PartidaEntradaController@Categorias');
  Route::get('buscararticuloexistencia/{id}','ExistenciasPrincipalController@Existencia');

  // Reporte de categorías de proyectos
  Route::get('proyectos1/estado','ProyectoEstadoCategoriaController@ObtenerProyectos');
  Route::get('documentos/empleados/{id}','DocumentosEmpleadosController@getDocumentos');
  Route::get('/estatusmaterial/descargar/foto/{id}','DocumentosEmpleadosController@descargarfoto');
  Route::get('/descargar/fotos/{id}','DocumentosEmpleadosController@descargar');
  Route::get('/descargar/fotos/editar/{id}','DocumentosEmpleadosController@edit');
  Route::get('eliminar/documento/{id}','DocumentosEmpleadosController@eliminar');

  Route::get('/empleados/documentos','DocumentosEmpleadosController@get');
  Route::get('/empleados/documentos/buscar','DocumentosEmpleadosController@buscar');
  Route::post('guardar/clave/ine','DocumentosEmpleadosController@guardarCI');
  Route::post('guardar/clave/nc','DocumentosEmpleadosController@guardarNC');
  Route::post('guardar/clave/rfc','DocumentosEmpleadosController@guardarRFC');
  Route::post('guardar/clave/curp','DocumentosEmpleadosController@guardarCURP');
  Route::post('guardar/clave/nss','DocumentosEmpleadosController@guardarNSS');
  Route::post('guardar/documentos','DocumentosEmpleadosController@guardarDocumentos');

  Route::get('arreglar/relacionados','FacturaController@agregar');
  Route::get('relaciones/eliminar/{id}','FacturaController@eliminarAsignacion');
  Route::get('get/relacionados/{id}','FacturaController@getAsignacion');

  Route::get('/sellartimbrarfactura/prueba/{id}','FacturaSellarTimbrarController@sellartimbrarprueba');
  // Route::get('/descargarfactura/{id}','FacturaSellarTimbrarController@descargarfactura');
  Route::get('/descargarfactura/prueba/{id}','FacturaSellarTimbrarController@descargarfacturaprueba');
  Route::get('cancelar/prueba/{id}','FacturaSellarTimbrarController@cancelarPrueba');

  Route::post('estatusmaterial/subir/foto/','EstatusMaterialController@subirfoto');
  Route::post('estatusmaterial/subir/foto/req','EstatusMaterialController@subirfotorequi');
  Route::get('estatusmaterial/descargar/foto/{id}','EstatusMaterialController@descargarfoto');
  Route::get('get/imagenes/partidas/oc/{id}','EstatusMaterialController@verimagenes');
  Route::get('get/imagenes/partidas/oc/req/{id}','EstatusMaterialController@verimagenesreq');
  Route::get('get/imagenes/save/temp/{id}','EstatusMaterialController@guardarTmp');
  Route::get('get/imagenes/delete/temp/{id}','EstatusMaterialController@eliminarTmp');
  Route::get('delete/imagenes/partidas/{id}','EstatusMaterialController@eliminarImg');
  Route::get('delete/imagenes/partidas/req/{id}','EstatusMaterialController@eliminarImgReq');
  Route::get('/descargar/fotos/calidad/{id}','EstatusMaterialController@descargar');
  Route::get('descargar-excel-proveedores/','ProveedoresController@DescargarReporte');
  Route::get('/conta/facturasproveedores/obtener','FacturasProveedoresController@ObtenerPagos');
  Route::put('/conta/facturasproveedores/asignarfecha','FacturasProveedoresController@AsignarFecha');
  Route::put('/conta/facturasproveedores/autorizar','FacturasProveedoresController@Autorizar');
  Route::get('/conta/facturasproveedores/obtenerautorizar','FacturasProveedoresController@ObtenerFacturasPorAutorizar');
  Route::get('/conta/facturasproveedores/obtenerprogramados/{ids}','FacturasProveedoresController@AuxObtenerFacturasProgramadas');
  Route::put('/conta/facturasproveedores/cambiarestado','FacturasProveedoresController@CambiarEstado');


  Route::get('lista/asistencia/seguridad/{id}','SeguridadController@getLista');
  Route::get('seguridad/listado/proyectos','SeguridadController@getListaProyectos');

  Route::get('get/lista/catalogo/analisis','SeguridadController@getCatalogo');
  Route::post('guardar/lista/catalogo/analisis','SeguridadController@guardarCatalogo');
  Route::put('actualizar/lista/catalogo/analisis','SeguridadController@actualizarCatalogo');

  Route::get('get/lista/catalogo/residuos','SeguridadController@getCatalogoR');
  Route::post('guardar/lista/catalogo/residuos','SeguridadController@guardarCatalogoR');
  Route::put('actualizar/lista/catalogo/residuos','SeguridadController@actualizarCatalogoR');

  Route::post('abc','SeguridadController@abc');
  Route::post('/guardar/analisis/seguridad','SeguridadController@guardar');
  Route::put('/actualizar/analisis/seguridad','SeguridadController@actualizar');
  Route::get('get/analisis/seguridad','SeguridadController@index');
  Route::get('eliminar/analisis/seguridad/{id}','SeguridadController@eliminarAnalisis');
  Route::get('get/lista/evaluacion/riesgo/{id}','SeguridadController@getRiesgos');
  Route::get('get/participantes/analisis/seguridad/{id}','SeguridadController@getParticipantes');
  Route::post('guardar/participantes/analisis/seguridad','SeguridadController@guardarParticipantes');
  Route::get('eliminar/participantes/analisis/seguridad/{id}','SeguridadController@eliminarParticipante');
  Route::get('descargar/analisis/seguridad/{id}','SeguridadController@descargar');

  Route::post('seguridad/permiso/general/guardar', 'SeguridadController@guardarPermiso');
  Route::put('seguridad/permiso/general/actualizar', 'SeguridadController@actualizarPermiso');
  Route::get('seguridad/permiso/general/descargar/{id}', 'SeguridadController@descargarPermiso');
  Route::get('get/permisos/seguridad','SeguridadController@getPermiso');
  Route::get('seguridad/permiso/general/eliminar/{id}','SeguridadController@eliminarPermiso');


  Route::get('descargar/trazabilidad/almacen/{id}','AlmaceneController@trazabilidad');

  Route::get('calculadora/neto/bruto/{id}','CalculadoraController@index');
  Route::post('guardar/foto/example','CalculadoraController@gua');

  Route::get('uodate/user/viatico','SolicitudViaticosController@updateEmp');

  Route::get('/almacen/unidadesm/obtener','UnidadesMedidaController@Obtener');
  Route::get('/almacen/unidadesm/obtenerdesc','UnidadesMedidaController@ObtenerDescripcion');
  Route::post('almacen/unidadesm/registrar','UnidadesMedidaController@Guardar');
  Route::put('almacen/unidadesm/actualizar','UnidadesMedidaController@Actualizar');
  // Solicitudes de TI
  Route::post("/ti/solicitudes/registrar","SolicitudTIController@Registrar");
  Route::get("/ti/solicitudes/obtener","SolicitudTIController@Obtener");
  Route::get("/ti/solicitudes/obtenertodas/{id}","SolicitudTIController@ObtenerTodas");
  Route::put("/ti/solicitudes/cambiarestado","SolicitudTIController@CambiarEstado");
  Route::put("/ti/solicitudes/asignarprioridad","SolicitudTIController@AsignarPrioridad");
  Route::get("/ti/solicitudes/usuarios","SolicitudTIController@Empleados");
  Route::get("/ti/solicitudes/cargarimagenes/{soli}","SolicitudTIController@CargarImagenes");
  Route::get("/rh/resguardos/obtener/{emp_id}","RHResguardosController@ObtenerPorEmpleado");

  //Solictud de viaticos
  Route::get('/sis/soliviaticos/empleados', 'PermisosViaticosController@ObtenerEmpleados');
  Route::resource('/sis/soliviaticos', 'PermisosViaticosController');
  // TI. Inventario
  Route::get('/ti/inv/equipos/obtener/{id}', 'TiComputoController@Obtener');
  Route::post('/ti/inv/equipos/registrar', 'TiComputoController@Registrar');
  Route::post('/ti/inv/equipos/actualizar', 'TiComputoController@Actualizar');
  Route::post('/ti/inv/equipos/cambiarestado', 'TiComputoController@Activar');
  Route::get('ti/inv/equipos/descargar/inv/comp/{id}', 'TiComputoController@Descargar');
  Route::get('ti/inv/equipos/eliminar/{id}', 'TiComputoController@Eliminar');

  Route::get('ti/inventario/impresoras/obtener', 'TiImpresionController@Obtener');
  Route::post('ti/inventario/impresoras/guardar', 'TiImpresionController@Guardar');
  Route::post('ti/inventario/impresoras/activar', 'TiImpresionController@Activar');
  Route::get('ti/inventario/impresoras/eliminar/{id}', 'TiImpresionController@Eliminar');

  Route::get('ti/inventario/accesorios/obtener', 'TiAccesoriosController@Obtener');
  Route::get('ti/inventario/accesorios/obtener/activos', 'TiAccesoriosController@ObtenerActivos');
  Route::post('ti/inventario/accesorios/guardar', 'TiAccesoriosController@Guardar');
  Route::post('ti/inventario/accesorios/activar', 'TiAccesoriosController@Guardar');
  Route::get('ti/inventario/accesorios/eliminar/{id}', 'TiAccesoriosController@Eliminar');

  Route::get('ti/inventario/red/obtener', 'TiRedController@Obtener');
  Route::post('ti/inventario/red/guardar', 'TiRedController@Guardar');
  Route::post('ti/inventario/red/activar', 'TiRedController@Activar');
  Route::get('ti/inventario/red/eliminar/{id}', 'TiRedController@Eliminar');

  Route::get('ti/inventario/video/obtener', 'TiVideoController@Obtener');
  Route::post('ti/inventario/video/guardar', 'TiVideoController@Guardar');
  Route::post('ti/inventario/video/activar', 'TiVideoController@Activar');
  Route::get('ti/inventario/video/eliminar/{id}', 'TiVideoController@Eliminar');
  // REsguardo
  Route::get('/ti/resguardo/obtenerequipos/{tipo}', 'TiResguardoController@ObtenerEquipos');
  Route::get('/ti/resguardo/empleados', 'RHResguardosController@ObtenerEmpleados');
  Route::get('/ti/resguardo/obtener', 'TiResguardoController@Obtener');
  Route::post('/ti/resguardo/guardar', 'TiResguardoController@Guardar');

  Route::get('get/calibracion/equipos/','EquiposCalibracionController@index');
  Route::post('guardar/calibracion/equipos/','EquiposCalibracionController@guardar');
  Route::put('actualizar/calibracion/equipos/','EquiposCalibracionController@actualizar');
  Route::get("/calidad/calib/pendientes","EquiposCalibracionController@Yolo");
  Route::get("/calidad/calib/equipos","EquiposCalibracionController@Obtener");
  Route::get("/calidad/calib/historial/{id}","EquiposCalibracionController@Historial");
  Route::post("/calidad/calib/regcalib","EquiposCalibracionController@GuardarCalib");
  Route::get("/calidad/calib/descargarcert/{id}","EquiposCalibracionController@DescargarCert");
  Route::get("/calidad/calib/descreporte","EquiposCalibracionController@DescargarReporte");
  // Documentos SGI
  Route::get('/procedsgi/archivos/{ruta}','SGIController@ObtenerArchivos');
  Route::get('/procedsgi/descargar/{nombre}','SGIController@Descargar');
  // Cambio de ubicación de Almacén
  Route::get('/lote/ubicacion/obtener','LoteController@Obtener');
  Route::get('/lote/ubicacion/buscar/{busacar}','LoteController@Buscar');
  Route::get('/lote/ubicacion/buscar/proyecto/{busacar}','LoteController@BuscarProyecto');
  Route::get('/lote/ubicacion/obtenerubicacion/{id}','LoteController@ObtenerActual');
  Route::put('/lote/ubicacion/cambiar','LoteController@Cambiar');

  Route::post('alm/guardar/entrada/interna','EntradaInternaController@store');

  Route::post('seguridad/residuos/guardar/bitacora','BitacoraResiduosController@store');
  Route::put('seguridad/residuos/actualizar/bitacora','BitacoraResiduosController@update');
  Route::post('seguridad/residuos/guardar/bitacora/gral','BitacoraResiduosController@storeGral');
  Route::put('seguridad/residuos/actualizar/bitacora/gral','BitacoraResiduosController@updateGral');
  Route::get('seguridad/residuos/get/bitacora/gral','BitacoraResiduosController@getGral');
  Route::get('seguridad/residuos/get/bitacora/{id}','BitacoraResiduosController@get');
  Route::get('descargar/bitacora/residuos/{id}','BitacoraResiduosController@descargar');
  Route::get('eliminar/bitacora/residuos/{id}','BitacoraResiduosController@eliminar');

  Route::post('vehiculos/subir/foto/unidad','VehiculosFotosController@guardar');
  Route::get('vehiculos/get/foto/unidad/{id}','VehiculosFotosController@getFotos');
  Route::get('eliminar/unidades/foto/{id}','VehiculosFotosController@eliminarTemp');

  Route::get('/residuos/urbanos/seguridad/get','ResiduosUrbanosController@get');
  Route::post('/residuos/urbanos/seguridad/guardar','ResiduosUrbanosController@guardar');
  Route::put('/residuos/urbanos/seguridad/actualizar','ResiduosUrbanosController@actualizar');
  Route::get('residuos/urbanos/seguridad/descargar/','ResiduosUrbanosController@descargar');

  Route::post('/buscar/articulos/','ArticuloController@getArt');

  Route::post('almacen/entradas/pendientes/guardar/','EntradasPendientesController@guardar');
  Route::get('almacen/entradas/pendientes/get/','EntradasPendientesController@get');
  Route::put('almacen/entradas/almacen/put/','EntradasPendientesController@changeAlmacen');
  Route::get('almacen/entradas/pendientes/delete/{id}','EntradasPendientesController@delete');
  Route::get('get/partidas/oc/pendientes/{id}','EntradasPendientesController@asociar');
  Route::post('guardar/partidas/oc/pendientes/asociado','EntradasPendientesController@guardarAsc');
  Route::post('actualizar/entrada/pendiente','EntradasPendientesController@actualizarPartida');

  Route::get('get/catalogo/cuentas/iniciales','CatalogoCuentasController@getI');
  Route::get('get/catalogo/cuentas/childs/{id}','CatalogoCuentasController@getC');
  Route::post('guardar/catalogos/cuentas','CatalogoCuentasController@Guardar');
  Route::put('actualizar/catalogos/cuentas','CatalogoCuentasController@Actualizar');

  Route::get('/vale/epp/seguridad/emp','ValeEppController@get');
  Route::get('/vale/epp/seguridad/emp/eliminar/{id}','ValeEppController@eliminar');
  Route::get('/vale/epp/seguridad/emp/detalle/{id}','ValeEppController@getDetalle');
  Route::post('get/articulos/descripcion/','ValeEppController@getArt');
  Route::post('/vale/epp/seguridad/guardar','ValeEppController@guardar');
  Route::get('/vale/epp/seguridad/salidas','ValeEppController@ObtenerSalidas');
  Route::get('/vale/epp/seguridad/emp/descargar/{id}','ValeEppController@descargar');
  Route::post('/guardar/act/partida/vale/epp','ValeEppController@Actualizar');
  Route::get('/get/partidas/epp/almacen','ValeEppController@getAlmacen');
  Route::post('entrega/almacen/vale/epp/','ValeEppController@guardarEntrega');
  Route::post('get/partida/vale/epp/asignacion','ValeEppController@getPartidas');
  Route::get('/vale/epp/seguridad/ver/articulos','ValeEppController@getArticulos');
  Route::get('buscar/historico/art/vale/epp/{id}','ValeEppController@getArticulosEpp');
  Route::get('exportar/historico/art/vale/epp/{id}','ValeEppController@ExportarArtEpp');

  Route::get('get/data/inicial/cb','SeguridadController@getCB');
  Route::post('guardar/data/seg/cb','SeguridadController@guardarCB');
  Route::get('get/data/seg/cb/fechas/{id}','SeguridadController@getFechasCB');
  Route::get('get/data/seg/cb/fechas/excel/{id}','SeguridadController@excel');

  Route::post('get/material/ti/descripcion','TIController@getPorTipo');
  Route::post('get/material/ti/descripcion/programa','TIController@getPorTipoPrograma');
  Route::post('ti/resguardos/regresar','TIController@Regresar');
  Route::post('get/material/ti/gral','TIController@getGral');
  Route::post('resguardo/material/ti/guardar','TIController@Guardar');
  Route::put('resguardo/material/ti/actualizar','TIController@Actualizar');
  Route::get('resguardo/material/ti/get/{id}','TIController@getR');
  Route::get('resguardo/material/ti/eliminar/{id}','TIController@deleteR');
  Route::get('resguardo/material/ti/descargar/{id}','TIController@descargarR');
  Route::post('material/ti/sitio/guardar','TIController@GuardarS');
  Route::put('material/ti/sitio/actualizar','TIController@ActualizarS');
  Route::get('material/ti/sitio/get/{id}','TIController@getDataSalida');
  Route::get('get/partidas/sitio/ti/act/{id}','TIController@getPartidasTI');
  Route::get('get/partidas/sitio/ti/activos/{id}','TIController@getPartidasTIActivos');
  Route::get('sitio/material/ti/descargar/{id}','TIController@descargarS');
  Route::get('eliminar/accesorio/resguardo/ti/{id}','TIController@eliminarAccesorio');
  Route::get('retornar/material/partidas/sitio/{id}','TIController@retornarPartida');

  // Route::post('guardar/reg/checador','NewChecadorController@store');


  Route::get('get/data/timbre/viaticos/{id}','ViaticoTimbradoController@getData');
  Route::get('get/timbres/viaticos/save/{id}','ViaticoTimbradoController@getDataPartidas');
  Route::post('get/sat/clave/','ViaticoTimbradoController@claveSat');
  Route::get('get/sat/cat/otropago','ViaticoTimbradoController@otroPagoCat');
  Route::post('guardar/datos/timbre/','ViaticoTimbradoController@guardarDatosTimbre');
  Route::get('viaticos/timbrado/prueba/{id}','ViaticoTimbradoController@timbraPrueba');
  Route::get('/viaticos/timbrado/descargar/pre/{id}','ViaticoTimbradoController@DescargarPre');
  Route::get('/viaticos/timbrado/descargar/tim/{id}','ViaticoTimbradoController@Descargar');
  Route::get('/descargar/factura/xml/{id}', 'ViaticoTimbradoController@descargarfacturaxml');
  Route::delete('/eliminar/factura/reporte/xml/{id}', 'ViaticoTimbradoController@destroyxml');

  //////////////////////////////////////
  Route::get('get/otros/pagos/update/{id}','ViaticoTimbradoController@getOtroPagos');
  Route::get('get/deducciones/update/{id}','ViaticoTimbradoController@getDeducciones');
  Route::get('get/percepciones/update/{id}','ViaticoTimbradoController@getPercepciones');


// ##### Trazabilidad calidad #######
// Proyectos
Route::get('/calidad/proyectos/proyectos','Calidad\ProyectosController@ObtenerProyectos');
Route::get('/calidad/proyectos/proyectos_sis','Calidad\ProyectosController@ObtenerProySistema');
Route::post('/calidad/proyectos/guardarproyecto','Calidad\ProyectosController@GuardarProyecto');
// Procedimientos
Route::get('/calidad/procedimientos/cargarprocedimientos','Calidad\ProcedimientosController@Obtener');
Route::post('/calidad/procedimientos/guardar','Calidad\ProcedimientosController@Guardar');
// Servicios
Route::get('/calidad/servicios/cargar','Calidad\ServiciosController@Obtener');
Route::post('/calidad/servicios/guardar','Calidad\ServiciosController@Guardar');
// Sistema
Route::get('/calidad/sistemas/obtener/{id}','Calidad\SistemasController@Obtener');
Route::post('/calidad/sistemas/guardar','Calidad\SistemasController@Guardar');
// Servicios de sistema
Route::get('/calidad/servsis/cargar/{id}','Calidad\ServiciosSistemaController@Obtener');
Route::post('/calidad/servsis/asignarservicio','Calidad\ServiciosSistemaController@AsignarServicio');
// Sistemas del proyecto
Route::get('/calidad/servsis/porproyecto/{proeyctoid}','Calidad\ServiciosSistemaController@ObtenerSistemas');
// Servicios del sistema
Route::get('/calidad/servsis/porsistema/{sistemaid}','Calidad\ServiciosSistemaController@ObtenerServicios');
Route::get('/calidad/servsis/spools/mostrar/{ss_id}','Calidad\ServiciosSistemaController@VerSpools');
Route::get('/calidad/servsis/obtener/{p_id}','Calidad\ServiciosSistemaController@ObtenerServiciosSistema');
Route::post('/calidad/servsis/spools/guardar','Calidad\ServiciosSistemaController@GuardarSpool');
// Maquinas
Route::get('/calidad/maquinas/obtener','Calidad\MaquinasController@Obtener');
Route::get('/calidad/maquinas/historialcalib/{id}','Calidad\MaquinasController@Historial');
Route::get('/calidad/maquinas/descargarcert/{id}','Calidad\MaquinasController@DescargarCert');
Route::post('/calidad/maquinas/guardar','Calidad\MaquinasController@Guardar');
Route::post('/calidad/maquinas/guardarcalibracion','Calidad\MaquinasController@GuardarCalibracion');
// Bridas
Route::get('/calidad/bridas/obtener','Calidad\BridasController@Obtener');
Route::post('/calidad/bridas/guardar','Calidad\BridasController@Guardar');

// Soldadores
Route::get('/calidad/soldadores/empleados','Calidad\SoldadoresController@Empleados');
Route::get('/calidad/soldadores/obtener','Calidad\SoldadoresController@Obtener');
Route::post('/calidad/soldadores/guardar','Calidad\SoldadoresController@Guardar');
Route::post('/calidad/soldadores/guardarproced','Calidad\SoldadoresController@GuardarProcedimiento');
Route::get('/calidad/soldadores/verproceds/{soldador_id}','Calidad\SoldadoresController@VerProcedimientos');
Route::get('/calidad/soldadores/verproced/{soldador_id}','Calidad\SoldadoresController@DetallesProcedimiento');
// Soldadores proyecto
Route::get('/calidad/sold_proy/obtener/{p_id}','Calidad\SoldadoresProyectoController@Obtener');
Route::get('/calidad/sold_proy/detalles/{sp_id}','Calidad\SoldadoresProyectoController@Detalles');
Route::get('/calidad/sold_proy/maquinas','Calidad\SoldadoresProyectoController@ObtenerMaquinas');
Route::get('/calidad/sold_proy/certificados/{maquina_id}','Calidad\SoldadoresProyectoController@ObtenerCertificados');
Route::get('/calidad/sold_proy/obtenersoldadores','Calidad\SoldadoresProyectoController@ObtenerSoldadores');
Route::get('/calidad/sold_proy/obtenercertificaciones/{s_id}','Calidad\SoldadoresProyectoController@ObtenerCertificaciones');
Route::post('/calidad/sold_proy/guardar','Calidad\SoldadoresProyectoController@Guardar');
// Asignación de maquinas
Route::get('/calidad/maquinas/obtenerasginacion','Calidad\MaquinasController@ObtenerMaquinas');
// Spools
Route::get('/calidad/spools/obtener/{sds_id}','Calidad\SpoolsController@Obtener');
Route::post('/calidad/spools/guardar','Calidad\SpoolsController@Guardar');
// Juntas
Route::get('/calidad/juntas/obtener/{sp_id}','Calidad\JuntasController@Obtener');
Route::get('/calidad/juntas/materiales/{ss_id}','Calidad\JuntasController@ObtenerMateriales');
Route::get('/calidad/juntas/coladas/{inspec_id}','Calidad\JuntasController@ObtenerColada');
Route::get('/calidad/juntas/obtenersoldadores/{datos}','Calidad\JuntasController@ObtenerSoldadores');
Route::get('/calidad/juntas/materialesjunta/{j_id}','Calidad\JuntasController@ObtenerMaterialesJutna');
Route::post('/calidad/juntas/guardar','Calidad\JuntasController@Guardar');
Route::post('/calidad/juntas/habilitar','Calidad\JuntasController@Habilitar');
Route::post('/calidad/juntas/soldar','Calidad\JuntasController@Soldar');
Route::post('/calidad/juntas/registarinspeccion','Calidad\JuntasController@Inspeccion');
// Inspeccion visual
Route::get('/calidad/inspecciones/trazabilidad/detalles/{id}','Calidad\InspeccionVisualController@ObtenerDetalles');
Route::get('/calidad/inspecciones/trazabilidad/inspecciones/{p_id}','Calidad\InspeccionVisualController@ObtenerInspeccciones');
Route::get('/calidad/inspecciones/trazabilidad/reporte/{id}','Calidad\InspeccionVisualController@GenerarReporte');
Route::get('/calidad/inspecciones/trazabilidad/obtenernomen/{id}','Calidad\InspeccionVisualController@ObtenerNomen');
Route::post('/calidad/inspecciones/trazabilidad/guardar','Calidad\InspeccionVisualController@Guardar');
Route::post('/calidad/inspecciones/trazabilidad/registrarnomen','Calidad\InspeccionVisualController@RegistrarNomen');
// Nomenclaturas
Route::get("/calidad/nomen/obtener","Calidad\NomenclaturaController@Obtener");
Route::post("/calidad/nomen/registrar","Calidad\NomenclaturaController@Registrar");
// Trazabilidad
Route::get("/calidad/trazab/obtener/{p_id}","Calidad\TrazabilidadController@Obtener");
Route::get("/calidad/trazab/obtenernomens/{ispec_id}","Calidad\TrazabilidadController@ObtenerNomens");
Route::post("/calidad/trazab/registrar","Calidad\TrazabilidadController@Registrar");
Route::post("/calidad/trazab/nomens/registrar","Calidad\TrazabilidadController@RegistrarNomen");
Route::get("/calidad/trazab/reporte/{ispec_id}","Calidad\TrazabilidadController@Reporte");
// Resumen de proyecto
Route::get("/calidad/resumen/rime/{p_id}","Calidad\ResumenController@ObtenerRimes");
Route::get("/calidad/resumen/riepc/{p_id}","Calidad\ResumenController@ObtenerRimesPC");
Route::get("/calidad/resumen/spools/{p_id}","Calidad\ResumenController@ObtenerJuntas");
Route::get("/calidad/resumen/soldadores/{p_id}","Calidad\ResumenController@ObtenerSoladadores");
Route::get("/calidad/resumen/avancesoldadura/{p_id}","Calidad\ResumenController@AvenceSoldadura");
Route::get("/calidad/resumen/juntasporestado/{id}","Calidad\ResumenController@JuntasPorEstado");
// Torque
Route::get("/calidad/torque/obtener/{p_id}","Calidad\TorqueController@Obtener");
Route::get("/calidad/torque/reporte/{t_id}","Calidad\TorqueController@GenerarReporte");
Route::get("/calidad/torque/obtenerequiposcalib","Calidad\TorqueController@ObtenerEquiposCalib");
Route::get("/calidad/torque/obtenersistemas/{p_id}","Calidad\TorqueController@ObtenerSistemas");
Route::post("/calidad/torque/guardar","Calidad\TorqueController@Guardar");
Route::get("/calidad/torque/partidas/obtener/{t_id}","Calidad\TorqueController@ObtenerPartidas");
Route::post("/calidad/torque/partidas/guardar","Calidad\TorqueController@GuardarPartida");
Route::get("/calidad/torque/obtenerimagenes/{t_id}","Calidad\TorqueController@ObtenerImagenes");
Route::get("/calidad/torque/obtenerbridas","Calidad\TorqueController@ObtenerBridas");
Route::post("/calidad/torque/guardarimagenes","Calidad\TorqueController@GuardarImagenes");


Route::get('get/data/bitacora/resguardo/info','ReguardoInfoTIController@get');
Route::post('guardar/data/bitacora/resguardo/info','ReguardoInfoTIController@guardar');
Route::put('actualizar/data/bitacora/resguardo/info','ReguardoInfoTIController@actualizar');
Route::get('descargar/data/bitacora/resguardo/info','ReguardoInfoTIController@descargar');

Route::get('get/inicial/data/programa/ti/{id}','ProgramaTIController@getInicial');
Route::get('get/data/programa/ti/{id}','ProgramaTIController@getDetalle');
Route::get('delete/data/programa/ti/{id}','ProgramaTIController@delete');
Route::post('guardar/data/programa/ti/','ProgramaTIController@guardar');
Route::put('actualizar/data/programa/ti/','ProgramaTIController@actualizar');
Route::get('descargar/data/programa/ti/{id}','ProgramaTIController@descargar');

Route::get('historico/servicios/ti/get','HistoricoServicioTIController@get');
Route::post('historico/servicios/ti/guardar','HistoricoServicioTIController@guardar');
Route::put('historico/servicios/ti/actualizar','HistoricoServicioTIController@actualizar');
Route::get('historico/servicios/ti/delete/{id}','HistoricoServicioTIController@delete');
Route::get('historico/servicios/ti/descargar','HistoricoServicioTIController@descargar');

Route::post('get/lotes/salida/resguardo','SalidaResguardoController@getLotes');
Route::post('salida/reguardo/guardar/data','SalidaResguardoController@Guardar');
Route::get('get/encabezados/salida/resguardo','SalidaResguardoController@getEncabezados');
Route::get('partidas/salida/resguardo/{id}','SalidaResguardoController@getPartidas');
Route::get('partidas/salida/resguardo/eliminar/{id}','SalidaResguardoController@EliminarP');
Route::post('guardar/retorno/reguardo','SalidaResguardoController@RetornoResguardo');
Route::get('descargar/salida/resguardo/{id}','SalidaResguardoController@Descargar');

Route::post('entrega/vehiculos/guardar/','TraficoEntregaRecepcionController@Guardar');
Route::put('entrega/vehiculos/actualizar/','TraficoEntregaRecepcionController@Actualizar');
Route::post('recepcion/vehiculos/guardar/','TraficoEntregaRecepcionController@GuardarRep');
Route::put('recepcion/vehiculos/actualizar/','TraficoEntregaRecepcionController@ActualizarRep');
Route::get('entrega/vehiculos/get/','TraficoEntregaRecepcionController@get');
Route::get('get/recepcion/trafico/{id}','TraficoEntregaRecepcionController@getRecepcion');
Route::get('delete/imagenes/entrega/vehiculos/{id}','TraficoEntregaRecepcionController@deleteImg');
Route::get('get/imagenes/entrega/vehiculos/{id}','TraficoEntregaRecepcionController@getImg');
Route::get('trafico/descargar/entrega/recepcion/{id}','TraficoEntregaRecepcionController@Descargar');

Route::get('trafico/descargar/inventario/','TraficoController@descargarInv');

Route::post('combustible/guardar','CombustibleController@guardar');
Route::put('combustible/actualizar','CombustibleController@actualizar');
Route::get('combustible/eliminar/{id}','CombustibleController@eliminar');
Route::get('combustible/get','CombustibleController@get');
Route::get('get/imagenes/combustible/{id}','CombustibleController@getImg');
Route::get('delete/imagenes/combustible/{id}','CombustibleController@deleteImg');

Route::get('get/proyectos/rces','RCESController@getProyectos');
Route::get('get/proyecto/rces/{id}','RCESController@getProyecto');
Route::get('get/partidas/oc/rces/{id}','RCESController@getPartidas');
Route::get('generar/requisicion/ordenc/csct/{id}','RCESController@generarCSCT');
Route::get('generar/entrada/salida/csct/{id}','RCESController@generarESCSCT');

Route::get('get/proyectos/xml','AsociacionXmlController@getProyectos');
Route::get('get/ocs/xml/{id}','AsociacionXmlController@getOcs');
Route::get('get/partidas/oc/xml/{id}','AsociacionXmlController@getPartidas');
Route::get('get/conceptos/facturas/xml/{id}','AsociacionXmlController@getConceptos');
Route::post('asiociar/partida/oc/xml','AsociacionXmlController@guardarAsociacion');

Route::get('tesoreria/get/articulos/facturas/{id}','TesoreriaFacturasController@getPartidasFacturasLI');
Route::get('descargar/salidas/materiales/{id}','TesoreriaFacturasController@descargarMateriales');

//Credenciales RH
Route::get('/rh/credenciales/generar/{ids}','RHCredencialesController@Generar');
Route::post('/rh/credenciales/guardarimagen','RHCredencialesController@GuardarImagenes');

///rUTAS PARA NUEVO PROCESO REQUISICION
Route::get('obtener/proyectos','ProyectoController@getAll');
Route::post('buscar/concepto/articulo/requisicion','RequisicionNewController@buscarArticulo');


Route::get('descargar/salidas/excel/{id}','SalidaController@descargarExcel');

//RUTAS PARA NUEVO PROCESO DE VIATICOS
Route::get('get/solicitudes/viaticos/{id}','SolicitudViaticosNewController@getSolicitudes');
Route::post('create/solicitudes/viaticos','SolicitudViaticosNewController@createSolicitudes');
Route::put('update/solicitudes/viaticos','SolicitudViaticosNewController@updateSolicitudes');
Route::get('get/beneficiario/viatico/{id}','SolicitudViaticosNewController@getBeneficiario');
Route::get('get/detalles/viaticos/{id}','SolicitudViaticosNewController@getDetalles');
/** Nuevo análisis de seguridad **/
// catalogos
Route::get("/seguridad/catalogos/secuencia/cargar","CatalogoSeguridadController@ObtenerSecuencias");
Route::post("/seguridad/catalogos/secuencia/registrar","CatalogoSeguridadController@RegistrarSecuencia");
Route::get("/seguridad/catalogos/riesgo/cargar","CatalogoSeguridadController@ObtenerRiesgos");
Route::get("/seguridad/catalogos/riesgo/porsecuencia/{s_id}","CatalogoSeguridadController@RiesgosDeSecuencia");
Route::post("/seguridad/catalogos/riesgo/registrar","CatalogoSeguridadController@RegistrarRiesgo");
Route::get("/seguridad/catalogos/recomendacion/cargar","CatalogoSeguridadController@ObtenerRecomendaciones");
Route::get("/seguridad/catalogos/recomendacion/porriesgo/{r_id}","CatalogoSeguridadController@RecomendacionesDeRiesgo");
Route::post("/seguridad/catalogos/recomendacion/registrar","CatalogoSeguridadController@RegistrarRecomendacion");

//analisis
Route::get("/seguridad/catalogos/analisis/obtener","CatalogoSeguridadController@ObtenerAnalisis");
Route::post("/seguridad/catalogos/analisis/guardar","CatalogoSeguridadController@GuardarAnalisis");

Route::get("seguridad/nuevoanalisis/obtenersecuencias","NuevoSeguridadController@ObtenerSecuencias");
Route::get("seguridad/nuevoanalisis/obtener","NuevoSeguridadController@ObtenerAnalisis");
Route::get("seguridad/nuevoanalisis/obtenerrecomedaciones/{s_id}","NuevoSeguridadController@ObtenerRecomendaciones");
Route::post("seguridad/nuevoanalisis/guardar","NuevoSeguridadController@Guardar");
Route::get("seguridad/nuevoanalisis/obtenerpartidas/{a_id}","NuevoSeguridadController@ObtenerPartidas");
Route::post("seguridad/nuevoanalisis/eliminarpartida","NuevoSeguridadController@EliminarPartidas");
Route::post("seguridad/nuevoanalisis/eliminar","NuevoSeguridadController@EliminarAnalisis");
Route::get("seguridad/nuevoanalisis/descargar/{a_id}","NuevoSeguridadController@DescargarFormato");

Route::get("seguridad/nuevoanalisis/participantes/obtener/{a_id}","NuevoSeguridadController@ObtenerParticipantes");
Route::post("seguridad/nuevoanalisis/participantes/guardar","NuevoSeguridadController@GuardarParticipantes");
Route::post("seguridad/nuevoanalisis/participantes/eliminar","NuevoSeguridadController@EliminarParticipantes");

Route::get("/compras/precioalmacensalidas/obtenerarticulos/{p_id}","PrecioArticulosController@ObtenerArticulosSalida");
Route::post("compras/precioalmacensalidas/registrar","PrecioArticulosController@Registrar2");

/** VEHICULOS **/
Route::get("/vehiculos/solicitud/obtener/{e_id}","SolicitudVehiculoController@Obtener");
Route::post("/vehiculos/solicitud/guardar","SolicitudVehiculoController@Guardar");
Route::get("/vehiculos/solicitud/obtenerresponsables/{id}","SolicitudVehiculoController@ObtenerResponsables");
Route::get("/vehiculos/solicitud/portipo/{data}","SolicitudVehiculoController@UnidadesPorTipo");
Route::get("/vehiculos/solicitud/descargar/{id}","SolicitudVehiculoController@GenerarReporte");

Route::get("/vehiculos/mttoanual/obtener/{dts}","VehiculosMttoAnualControlller@ObtenerMtto");
Route::get("/vehiculos/mttoanual/obteneranios/{empresa}","VehiculosMttoAnualControlller@ObtenerAnios");
Route::get("/vehiculos/mttoanual/obtenerunidades/{e_id}","VehiculosMttoAnualControlller@ObtenerUnidades");
Route::post("/vehiculos/mttoanual/guardar","VehiculosMttoAnualControlller@Guardar");
Route::get("/vehiculos/mttoanual/descargar/{dts}","VehiculosMttoAnualControlller@Descargar");

Route::get("/vehiculos/valeresguardo/obtener/{emp}","ValeSoliVehiculoController@Obtener");
Route::get("/vehiculos/valeresguardo/obenersolicitudes/{emp}","ValeSoliVehiculoController@ObtenerSolicitudes");
Route::get("/vehiculos/valeresguardo/obenerpolizas/{u_id}","ValeSoliVehiculoController@ObtenerPolizas");
Route::post("/vehiculos/valeresguardo/guardar","ValeSoliVehiculoController@Guardar");
Route::get("/vehiculos/valeresguardo/descargar/{id}","ValeSoliVehiculoController@Descargar");

Route::get("mantenimiento/vehiculo/get","VehiculosMantenimientoController@getAll");
Route::get("mantenimiento/vehiculo/get/{id}","VehiculosMantenimientoController@getById");
Route::post("mantenimiento/vehiculo/guardar","VehiculosMantenimientoController@store");
Route::put("mantenimiento/vehiculo/actualizar","VehiculosMantenimientoController@update");
Route::get("mantenimiento/vehiculo/eliminar/{id}","VehiculosMantenimientoController@delete");

Route::get('/proyecto/salidas/get','SalidaController@getProyectos');

Route::post('alm/cambiar/entrada','LoteAlmacenController@cambiarEntrada');
Route::get('alm/eliminar/entrada/{id}','LoteAlmacenController@eliminarPartidaEntrada');

Route::post('/calidad/recepciones/generar', 'Calidad\RecepcionController@Generar');
Route::get('/calidad/recepciones/obtener/{p_id}', 'Calidad\RecepcionController@Obtener');
Route::get('/calidad/recepciones/partidas/{r_id}', 'Calidad\RecepcionController@ObtenerPartidas');
Route::get('/calidad/recepciones/descargar/{r_id}', 'Calidad\RecepcionController@Descargar');

Route::get('/rh/controltiempoemp/obtener', 'RHControlTiempoEmpController@Obtener');
Route::post('rh/controltiempoemp/guardar', 'RHControlTiempoEmpController@Guardar');
Route::post('rh/controltiempoemp/eliminar', 'RHControlTiempoEmpController@Eliminar');

Route::get('get/asistencias/sitio','AsistenciaSitioController@get');
Route::post('save/asistencias/sitio','AsistenciaSitioController@save');
Route::put('update/asistencias/sitio','AsistenciaSitioController@update');
Route::get('delete/asistencias/sitio/{id}','AsistenciaSitioController@delete');

});
