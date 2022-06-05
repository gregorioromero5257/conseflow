const TipoPartida = r => require.ensure([], () => r(require('./Catalogos/Tipo-partida.vue')), 'cos')
const PartidasProyectos = r => require.ensure([], () => r(require('./Partidas/Proyectos.vue')), 'cos')
const ReporteProyectos = r => require.ensure([], () => r(require('./Reportes/Proyectos.vue')), 'cos')
const PrecioArt = r => require.ensure([], () => r(require('./Reportes/PrecioArticulo.vue')), 'cos')
const DetalleCotizacion = r => require.ensure([], () => r(require('./DetallesCotizaciones/Proyectos')), 'cos')
const ReporteTransferencia = r => require.ensure([], ()=> r(require('./Reportes/ReporteTransferencia')), 'cos')
const Calculadora = r => require.ensure([], () => r(require('../Costos/Calculadora.vue')), 'cos')
const ArticulosPrecios = r => require.ensure([], () => r(require('./Reportes/ArticulosOC.vue')), 'cos')

const routes = [
  { path: '/cos/catalogo/tipopartida/' , component: TipoPartida},
  { path: '/cos/partidas/' , component: PartidasProyectos},
  { path: '/cos/reportes/proyectos/' , component: ReporteProyectos},
  // { path: '/cos/reportes/precioventa/' , component: PrecioArt},
  { path: '/cos/detallecotizacion/', component : DetalleCotizacion},
  { path: '/cos/reportes/transferencia/', component : ReporteTransferencia},
  { path: '/cos/calculadora/', component : Calculadora},
  { path: '/cos/reportes/precioventa/', component : ArticulosPrecios},
  ]

export default routes
