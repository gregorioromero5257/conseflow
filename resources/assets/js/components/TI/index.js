//const Inventario = r => require.ensure([], () => r(require('../Inventario.vue')), 'inventario')
const Herramienta = r => require.ensure([], () => r(require('./Inventario/Herramienta.vue')), 'ti')
const Computo = r => require.ensure([], () => r(require('./Inventario/Equipos.vue')), 'ti')
const Impresion = r => require.ensure([], () => r(require('./Inventario/Impresion.vue')), 'ti')
const Accesorios = r => require.ensure([], () => r(require('./Inventario/Accesorios.vue')), 'ti')
const Red = r => require.ensure([], () => r(require('./Inventario/Red.vue')), 'ti')
const Video = r => require.ensure([], () => r(require('./Inventario/Video.vue')), 'ti')
//const Impresion = r => require.ensure([], () => r(require('./Inventario/Impresion.vue')), 'ti')
//const Celulares = r => require.ensure([], () => r(require('./Inventario/Celulares.vue')), 'ti')
//const Proyeccion = r => require.ensure([], () => r(require('./Inventario/Proyeccion.vue')), 'ti')
//const Software = r => require.ensure([], () => r(require('./Inventario/Software.vue')), 'ti')

const TipoSW = r => require.ensure([], () => r(require('./Catalogos/TipoSW.vue')), 'ti')
const TipoEquipo = r => require.ensure([], () => r(require('./Catalogos/TipoEquipo.vue')), 'ti')
const Clasificacion = r => require.ensure([], () => r(require('./Catalogos/Clasificacion.vue')), 'ti')
const Accesorio = r => require.ensure([], () => r(require('./Catalogos/Accesorio.vue')), 'ti')
const TipoServicio = r => require.ensure([], () => r(require('./Catalogos/TipoServicio.vue')), 'ti')
const Solicutd = r => require.ensure([], () => r(require('./Soporte/Solicitud.vue')), 'ti')
const Control = r => require.ensure([], () => r(require('./Soporte/Control.vue')), 'ti')
const Dash = r => require.ensure([], () => r(require('./Dashborad/Dashboard.vue')), 'ti')
const Vales = r => require.ensure([], () => r(require('./Vales/Vale.vue')), 'ti')
const Bitacora  = r => require.ensure([], () => r(require('./Bitacora/Bitacora.vue')), 'ti')
const Programa  = r => require.ensure([], () => r(require('./ProgramaMantenimiento/Programa.vue')), 'ti')
const Historico  = r => require.ensure([], () => r(require('./Servicios/Historico.vue')), 'ti')
const RetornoResguardo  = r => require.ensure([], () => r(require('./Vales/Retorno.vue')), 'ti')

const routes = [
    { path: '/inventario/herramienta', component: Herramienta },
    { path: '/catalogos/tiposw', component: TipoSW },
    { path: '/catalogos/tipoequipo', component: TipoEquipo },
    { path: '/catalogos/clasificacion', component: Clasificacion },
    { path: '/catalogos/accesorio', component: Accesorio },
    { path: '/catalogos/tiposervicio', component: TipoServicio },
    { path: '/ti/solicitud', component: Solicutd },
    { path: '/ti/control', component: Control },
    { path: '/ti/dash', component: Dash },
    { path: '/ti/computo', component: Computo },
    { path: '/ti/impresion', component: Impresion },
    { path: '/ti/accesorios', component: Accesorios },
    { path: '/ti/red', component: Red },
    { path: '/ti/video', component: Video },
    { path: '/ti/vales', component: Vales },
    { path: '/ti/bitacora/resguardo/info', component: Bitacora },
    { path: '/ti/programa/mantenimiento', component: Programa },
    { path: '/ti/historico/servicios', component: Historico },
    { path: '/ti/retorno/resguardo', component: RetornoResguardo },
  ]

export default routes
