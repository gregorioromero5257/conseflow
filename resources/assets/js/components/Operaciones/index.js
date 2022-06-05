const Inspeccion = r => require.ensure([], () => r(require('./Inspeccion/Soldaduras.vue')), 'opera')
const Soldador = r => require.ensure([], () => r(require('./Catalogos/Soldadores.vue')), 'opera')
const Juntas = r => require.ensure([], () => r(require('./Catalogos/Juntas.vue')), 'opera')
const Caja = r => require.ensure([], () => r(require('./CajaChica/Solicitud.vue')), 'opera')
const Dashboard = r => require.ensure([], () => r(require('./CajaChica/Notificaciones.vue')), 'opera')
const Departamento = r => require.ensure([], () => r(require('./CajaChica/Departamento.vue')), 'opera')
const Solicitud = r => require.ensure([], () => r(require('./CajaChica/SolicitudRP.vue')), 'opera')
const Revision = r => require.ensure([], () => r(require('./CajaChica/RevisionCaja.vue')), 'opera')


const routes = [
    { path: '/inspecciones', component: Inspeccion },
    { path: '/soldadores', component: Soldador },
    { path: '/juntas', component: Juntas },
    { path: '/caja/chica/solicitud', component: Caja },
    { path: '/caja/chica/noticaciones', component: Dashboard },
    { path: '/caja/chica/departamentos', component: Departamento },
    { path: '/caja/chica/control', component: Solicitud },
    { path: '/caja/chica/revision', component: Revision },



  ]

export default routes
