const Dashboard = r => require.ensure([], () => r(require('./Dashboard/Dashboard.vue')), 'venta')
const Clientes = r=> require.ensure([], () => r(require('./Clientes/Clientes.vue')), 'venta')
const PagosClientes = r=> require.ensure([], () => r(require('./Clientes/PagosClientes.vue')), 'venta')
const Ordenes = r=> require.ensure([], () => r(require('./Orden/Ordenes.vue')), 'venta')
const Pagos = r=> require.ensure([], () => r(require('./Clientes/Pagos.vue')), 'venta')

const routes = [
    { path: '/venta/dashboard/', component: Dashboard },
    { path: '/venta/clientes/', component: Clientes},
    { path: '/venta/pagosclientes/', component: PagosClientes},
    { path: '/venta/orden/', component: Ordenes},
    { path: '/venta/pagos/', component: Pagos},
  ]

export default routes
