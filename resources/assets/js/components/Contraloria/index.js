const Dashboard = r => require.ensure([], () => r(require('./Dashboard/Dashboard.vue')), 'contra')
const PartidasProyectos = r => require.ensure([], () => r(require('./Partidas/Proyectos.vue')), 'contra')
// const CodigoAgrupador = r => require.ensure([], () => r(require('./DigitoAgrupador/CodigoAgrupador.vue')), 'contra')
const ValidarFacturas = r => require.ensure([], () => r(require('../Contraloria/ValidarFacturas.vue')), 'contra')
const CatalogoCentroCostos = r => require.ensure([], () => r(require('./CentroCostos/CentroCostosCatalogo.vue')), 'contra')
const Asignacion = r => require.ensure([], () => r(require('./CentroCostos/Asignacion.vue')), 'contra')
// const PagosAutorizar = r => require.ensure([], () => r(require('./PagosAutorizar/PagosAutorizar.vue')), 'contra');
const CompraFacturaPago = r => require.ensure([], () => r(require('./Reportes/CompraFacturaPago.vue')), 'contra');
// const PrincipalRequisiciones = r => require.ensure([], () => r(require('./Requisiciones/PrincipalRequisiciones.vue')), 'contra');
const SueldosSemanal = r => require.ensure([], () => r(require('./Nomina/SueldosSemana.vue')), 'contra');
const GastosEmpresas = r => require.ensure([], () => r(require('./Gastos/Gastos.vue')), 'contra');
const ControlTiempo = r => require.ensure([], () => r(require('./ControlTiempo/ControlTiempo.vue')), 'contra');
const SueldosQuincenal = r => require.ensure([], () => r(require('./Nomina/SueldosQuincenal.vue')), 'contra');
const CodigoSat = r => require.ensure([], () => r(require('./CodigoSat.vue')), 'contra');
const Provedores = r => require.ensure([], () => r(require('./Proveedores.vue')), 'contra');
const FacturasProveedores = r => require.ensure([], () => r(require('./FacturasProveedores/FacturasProveedores.vue')), 'conta')
const Rces = r => require.ensure([], () => r(require('./RCES/Rces.vue')), 'conta')
const Asociacion = r => require.ensure([], () => r(require('./Asociacion/Asociacion.vue')), 'conta')
const Facturas = r => require.ensure([], () => r(require('./Facturas/Factura.vue')), 'conta')

const routes = [
      { path: '/contra/dashboard/', component: Dashboard },
      { path: '/contra/partidas/' , component: PartidasProyectos},
      // { path: '/contra/codigo/' , component: CodigoAgrupador},
      { path: '/contra/validarfacturas/' , component: ValidarFacturas},
      { path: '/contra/catalogo/centrocostos/' , component: CatalogoCentroCostos},
      { path: '/contra/asignacion/centrocostos/' , component: Asignacion},
      { path: '/contra/compra/factura/pago/' , component: CompraFacturaPago},
      // { path: '/contra/requisiciones/' , component: PrincipalRequisiciones},
      { path: '/contra/cargar/sueldos/semana/' , component: SueldosSemanal},
      { path: '/contra/cargar/sueldos/quincenal/' , component: SueldosQuincenal},
      { path: '/contra/cargar/gastos/empresas/' , component: GastosEmpresas},
      { path: '/contra/control/tiempo/reportes/' , component: ControlTiempo},
      { path: '/contra/codigosat/' , component: CodigoSat},
      { path: '/contra/proveedores/' , component: Provedores},
      { path: '/contra/facturasproveedores/', component:FacturasProveedores},
      { path: '/contra/req/con/ent/sal/', component:Rces},
      { path: '/contra/asociacion/xml/', component:Asociacion},
      { path: '/contra/subida/xml/', component:Facturas},


  ]

export default routes
