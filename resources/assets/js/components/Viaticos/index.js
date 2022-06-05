const Viaticos = r => require.ensure([], () => r(require('./Viaticos/Viaticos.vue')), 'via')
const Solicitud = r => require.ensure([], () => r(require('./Solicitud/Solicitud.vue')), 'via')
const Dashboard = r => require.ensure([], () => r(require('./Dashboard/Dashboard.vue')), 'via')
const ViaticosGastos = r => require.ensure([], () => r(require('../Viaticos/ViaticosGastosMenores.vue')), 'via')
const RegiRegistroViaticos = r => require.ensure([], () => r(require('./Viaticos/RegistroViaticos.vue')), 'via')

const routes = [
    { path: '/via/dashboard/', component: Dashboard },
    { path: '/via/viaticos/', component: Viaticos },
    { path: '/via/solicitud/', component: Solicitud },
    { path: '/via/viaticos/gastos/menores/', component: ViaticosGastos },
    { path: '/via/registro/viaticos/', component: RegiRegistroViaticos},

  ]

export default routes
