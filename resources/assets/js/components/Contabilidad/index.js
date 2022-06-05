const Contabilidad = r => require.ensure([], () => r(require('../Contabilidad/Agenda/Agenda.vue')), 'conta')
const PagosRecuMen = r => require.ensure([], () => r(require('../Contabilidad/Catalogo/Recurrentes.vue')), 'conta')
const PagosNoRecu = r => require.ensure([], () => r(require('../Contabilidad/Catalogo/NoRecurrentes.vue')), 'conta')
const Credito = r => require.ensure([], () => r(require('../Contabilidad/Credito/Credito.vue')), 'conta')
const Dashboard = r => require.ensure([], () => r(require('./Dashboard/Dashboard.vue')), 'conta')
const Bancos = r => require.ensure([], () => r(require('./Bancos/Bancos.vue')), 'conta')
const Clientes = r=> require.ensure([], () => r(require('./Clientes/Clientes.vue')), 'conta')
const PagosClientes = r=> require.ensure([], () => r(require('./Clientes/PagosClientes.vue')), 'conta')
const CatalogosRN = r=> require.ensure([], () => r(require('./CatalogosRN/Principal.vue')), 'conta')
const Factura = r=> require.ensure([], () => r(require('./Factura/Factura.vue')), 'conta')
const Proveedores = r=> require.ensure([], () => r(require('./Proveedores/Proveedores.vue')), 'conta')
const SatCatFormapago = r => require.ensure([], () => r(require('../Contabilidad/Catalogo/SatCatFormapago.vue')), 'conta')
const SatCatProdser = r => require.ensure([], () => r(require('../Contabilidad/Catalogo/SatCatProdser.vue')), 'conta')
const PartidasProyectos = r => require.ensure([], () => r(require('./Partidas/Proyectos.vue')), 'conta')
const UploadUserEdenRed = r => require.ensure([], () => r(require('./ControlEdenred/UploadUserEdenRed.vue')), 'conta')
const OCPagoFactura = r => require.ensure([], () => r(require('./Trazabilidad/OCPagoFactura.vue')), 'conta')
const CatalogoCuentas = r => require.ensure([], () => r(require('./Catalogo/CatalogoCuentas.vue')), 'conta')
const Materiales = r => require.ensure([], () => r(require('./Materiales.vue')), 'conta')

const routes = [
    { path: '/agenda', component: Contabilidad},
    { path: '/pagos/recurrentes', component: PagosRecuMen},
    { path: '/pagos/norecurrentes/', component: PagosNoRecu},
    { path: '/credito/credito/', component: Credito},
    { path: '/conta/dashboard/', component: Dashboard },
    { path: '/conta/banco/', component: Bancos},
    { path: '/conta/clientes', component: Clientes},
    { path: '/conta/pagosclientes', component: PagosClientes},
    { path: '/conta/catalogosrecibosnomina/', component: CatalogosRN},
    { path: '/conta/satcatprodser', component: SatCatProdser},
    { path: '/conta/satcatformapago', component: SatCatFormapago},
    { path: '/conta/factura/', component: Factura},
    { path: '/conta/proveedores/', component: Proveedores},
    { path: '/conta/partidas/' , component: PartidasProyectos},
    { path: '/conta/edenredempleados/', component:UploadUserEdenRed},
    { path: '/conta/consulta/ocpagofactura/', component:OCPagoFactura},
    { path: '/conta/catalogo/cuentas/', component:CatalogoCuentas},
    { path: '/conta/listado/materiales/', component:Materiales},

  ]

export default routes
