const RegistroProveedores = r => require.ensure([], () => r(require('./Proveedores/Proveedor.vue')), 'compras')
const CatalogoCotizacion = r => require.ensure([], () => r(require('./Catalogos/Cotizacion.vue')), 'compras')
const Compras = r => require.ensure([], () => r(require('./Compra/ProyectosCompras.vue')), 'compras')
const EstadoCompra = r => require.ensure([], () => r(require('./Catalogos/EstadoCompras.vue')), 'compras')
const Servicios = r => require.ensure([], () => r(require('./Catalogos/Servicios.vue')), 'compras')
const Dashboard = r => require.ensure([], () => r(require('./Dashboard/Dashboard.vue')), 'compras')
const PorProyecto = r => require.ensure([], () => r(require('./Reportes/Proyectos.vue')), 'compras')
const Trazabilidad = r => require.ensure([], () => r(require('./Reportes/Trazabilidad.vue')), 'compras')
const TrazabilidadGeneral = r => require.ensure([], () => r(require('./Reportes/TrazabilidadGeneral.vue')), 'compras')
const Servicio = r => require.ensure([], () => r(require('./ServiciosRealizados/Servicios.vue')), 'compras')
const RequisicionPendiente = r => require.ensure([], () => r(require('./Compra/ReportePendiente.vue')), 'compras')
const RequisitadoComprado = r => require.ensure([], () => r(require('./Reportes/RequisitadoComprado.vue')), 'compras')
const PrincipalRequisiciones = r => require.ensure([], () => r(require('./Requisiciones/PrincipalRequisiciones.vue')), 'compras')
const Requisicion = r => require.ensure([], () => r(require('./Requisiciones/Requisiciones.vue')), 'compras')
const Mantenimiento = r => require.ensure([], () => r(require('./Compra/MantenimientoVehiculos.vue')), 'compras')
const EstadoProveedores = r => require.ensure([], () => r(require('./Proveedores/ConsultaEstado.vue')), 'compras')
const ComprasGastos = r => require.ensure([], () => r(require('./ComprasGastos/SubirGastos.vue')), 'compras')
const OCGastos = r => require.ensure([], () => r(require('./ComprasGastos/OCPrincipal.vue')), 'compras')
const Evaluacion = r => require.ensure([], () => r(require('./Proveedores/Evaluacion.vue')), 'compras')
const Disponible = r => require.ensure([], () => r(require('./Almacen/Disponibles.vue')), 'compras')
const HistoricoCompras = r => require.ensure([], () => r(require('./Reportes/HistoricoCompras.vue')), 'compras')

const routes = [

  { path: '/comp/proveedor/proveedores/' , component: RegistroProveedores},
  { path: '/comp/catalogo/catalogos/' , component: CatalogoCotizacion},
  { path: '/comp/compra/compras/' , component: Compras},
  { path: '/comp/catalogo/estadocompras/' , component: EstadoCompra},
  { path: '/comp/catalogo/catservicios/' , component: Servicios},
  { path: '/comp/dashboard/', component: Dashboard },
  { path: '/comp/reportes/proyectos/', component: PorProyecto },
  { path: '/comp/reportes/trazabilidad/', component: Trazabilidad },
  { path: '/comp/reportes/trazabilidad/general/', component: TrazabilidadGeneral },
  { path: '/comp/reportes/compradorequisitado/', component: RequisitadoComprado },
  { path: '/comp/serviciosrealizados/servicios/', component: Servicio },
  { path: '/comp/requisicionespendientes/', component: RequisicionPendiente },
  { path: '/comp/requisiciones/', component: PrincipalRequisiciones},
  { path: '/comp/mantenimiento/vehiculo', component: Mantenimiento},
  { path: '/comp/consulta/estado/proveedores', component: EstadoProveedores},
  { path: '/comp/subir/gastosempresas/', component: ComprasGastos},
  { path: '/comp/ver/ocgastosempresas/', component: OCGastos},
  { path: '/compras/evaluacion/proveedores/', component: Evaluacion},
  { path: '/compras/disponibles/', component: Disponible},
  { path: '/comp/reportes/historico/compras/', component: HistoricoCompras},

  ]

export default routes
