const Concentrados = r => require.ensure([], () => r(require('./Juntas/ConcentradoJuntas.vue')), 'calidad')
const Relacion = r => require.ensure([], () => r(require('./Relacionspool.vue')),'calidad')
const Coladas = r => require.ensure([], () => r(require('./Coladas.vue')),'calidad')
const EstatusDeMaterial = r => require.ensure([],()=> r(require('./Reporte/EstatusDeMaterial.vue')), 'calidad')
const Inspeccion = r => require.ensure([],()=> r(require('./Reporte/Inspeccion.vue')), 'calidad')
const InspeccionCliente = r => require.ensure([],()=> r(require('./Reporte/InspeccionCliente.vue')), 'calidad')
const DashBoard = r => require.ensure([],()=> r(require('./Dashboard/Dashborad.vue')), 'calidad')
const EquiposCalib = r => require.ensure([],()=> r(require('./Calibracion/EquiposCalibracion.vue')), 'calidad')
const Proyectos = r => require.ensure([],()=> r(require('./Proyecto/Proyectos.vue')), 'calidad')
const Procedimientos = r => require.ensure([],()=> r(require('./Catalogos/ProcedimSoldadura.vue')), 'calidad')
const Bridas = r => require.ensure([],()=> r(require('./Catalogos/ClaseBrida.vue')), 'calidad')
const Servicios = r => require.ensure([],()=> r(require('./Catalogos/Servicios.vue')), 'calidad')
const Soldadores = r => require.ensure([],()=> r(require('./Soldadores/Soldadores.vue')), 'calidad')
const Maquinas = r => require.ensure([],()=> r(require('./Catalogos/MaquinasSoldar.vue')), 'calidad')
const Inspecciones = r => require.ensure([],()=> r(require('./Inspecciones/Visual.vue')), 'calidad')
const Trazabilidad = r => require.ensure([],()=> r(require('./Inspecciones/Trazabilidad.vue')), 'calidad')
const Nomens = r => require.ensure([],()=> r(require('./Catalogos/Nomenclaturas.vue')), 'calidad')
const Resumen = r => require.ensure([],()=> r(require('./Resumen/Resumen.vue')), 'calidad')
const Torque = r => require.ensure([],()=> r(require('./Inspecciones/PruebaTorque.vue')), 'calidad')
const Recepcion = r => require.ensure([], () => r(require('./Recepcion/Recepcion.vue')),'calidad')


const rutas = [
	{ path: '/calidad/juntas/concentrado/', component: Concentrados },
	{ path: '/relacionspool/', component: Relacion },
	{ path: '/coladas/', component: Coladas },
	{ path: '/proyecto/reportes/estatus/material/calidad/', component: EstatusDeMaterial },
	{ path: '/calidad/inspeccion', component: Inspeccion },
	{ path: '/calidad/inspeccioncliente', component: InspeccionCliente },
	{ path: '/calidad/dashboard', component: DashBoard },
	{ path: '/calidad/calib/equipos', component: EquiposCalib },
	// Trazabildiad
	// Catalogos
	{ path: '/calidad/proyectos', component: Proyectos },
	{ path: '/calidad/procedimientos', component: Procedimientos },
	{ path: '/calidad/catalogos/bridas', component: Bridas },
	{ path: '/calidad/servicios', component: Servicios },
	{ path: '/calidad/soldadores', component: Soldadores },
	{ path: '/calidad/maquinas', component: Maquinas },
	{ path: '/calidad/inspec/visual', component: Inspecciones },
	{ path: '/calidad/inspec/trazab', component: Trazabilidad },
	{ path: '/calidad/nomenclaturas/', component: Nomens },
	{ path: '/calidad/proyectos/resumen/', component: Resumen },
	{ path: '/calidad/inspect/torque', component: Torque },
	{ path: '/calidad/recepcion', component: Recepcion },
]

export default rutas
