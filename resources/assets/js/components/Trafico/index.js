const Unidades = r => require.ensure([], () => r(require('../Trafico/Unidades.vue')), 'trafico')
const Conductores = r => require.ensure([], () => r(require('../Trafico/Conductores.vue')), 'trafico')
const TipoServicio = r => require.ensure([], () => r(require('../Trafico/TipoServicio.vue')), 'trafico')
const Servicios = r => require.ensure([], () => r(require('../Trafico/Servicios.vue')), 'trafico')
const Dashboard = r => require.ensure([], () => r(require('./Dashboard/Dashboard.vue')), 'trafico')
const Reporte = r => require.ensure([], () => r(require('../Trafico/Reporte.vue')), 'trafico')
const Asignacion = r => require.ensure([], () => r (require('../Trafico/AsignacionVehiculo.vue')), 'trafico')
const Caracteristicas = r => require.ensure([], () => r (require('../Trafico/CaracteristicasVehiculo.vue')), 'trafico')
const Vehiculos = r => require.ensure([], () => r (require('../Trafico/Vehiculos.vue')), 'trafico')
const EntregaRecepcion = r => require.ensure([], () => r (require('../Trafico/EntregaRecepcion.vue')), 'trafico')
const Combustible = r => require.ensure([], () => r (require('../Trafico/Combustibles.vue')), 'trafico')
const Solicitud = r => require.ensure([], () => r (require('./Solicitud/Solicitud.vue')), 'trafico')
const MantenimientoAnual = r => require.ensure([], () => r (require('./MantenimientoAnual.vue')), 'trafico')
const Mantenimiento = r => require.ensure([], () => r (require('./Mantenimiento/Mantenimiento.vue')), 'trafico')
// const ResguardoUnidad = r => require.ensure([], () => r (require('./ValeResguardo.vue')), 'trafico')

const routes = [
    { path: '/unidades', component: Unidades},
    { path: '/conductores', component: Conductores},
    { path: '/trafico/tiposervicio', component: TipoServicio},
    { path: '/trafico/servicios', component: Servicios},
    { path: '/trafico/dashboard/', component: Dashboard },
    { path: '/trafico/reportes/', component: Reporte },
    { path: '/trafico/asignacion/', component: Asignacion },
    { path: '/trafico/caracteristicas/', component: Caracteristicas },
    { path: '/trafico/vehiculos/', component: Vehiculos },
    { path: '/trafico/vehiculos/entrega/recepcion', component: EntregaRecepcion },
    { path: '/trafico/registro/combustibles', component: Combustible },
    { path: '/trafico/solicitud', component: Solicitud },
    { path: '/trafico/mantenimientoanual', component: MantenimientoAnual },
    { path: '/trafico/mantenimiento', component: Mantenimiento },

    // { path: '/trafico/valeresguardo', component: ResguardoUnidad },
  ]

export default routes
