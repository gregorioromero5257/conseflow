const PagosAutorizar = r => require.ensure([], () => r(require('./PagosAutorizar/PagosAutorizar.vue')), 'dir');

const routes = [
   { path: '/dir/autorizar/pagos/' , component: PagosAutorizar},
  ]

export default routes
