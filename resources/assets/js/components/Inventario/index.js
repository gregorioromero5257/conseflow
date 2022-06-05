const Articulo = r => require.ensure([], () => r(require('../Inventario/Articulo.vue')), 'inv')
const ArticuloP = r => require.ensure([], () => r(require('../Inventario/ArticuloP.vue')), 'inv')
const Factura = r => require.ensure([], () => r(require('../Inventario/Factura.vue')), 'inv')


const routes = [
  { path: '/articuloinv', component: Articulo },
  { path: '/articuloinvp', component: ArticuloP },
    { path: '/factura/subida', component: Factura },



  ]

export default routes
