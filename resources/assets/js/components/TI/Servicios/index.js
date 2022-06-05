const Proyecto = r => require.ensure([], () => r(require('../Proyecto/Proyecto.vue')), 'proyecto')
const Requisicion = r => require.ensure([], () => r(require('../Proyecto/Requisiciones.vue')), 'proyecto')

const routes = [
    { path: '/proyecto', component: Proyecto },
    { path: '/requisicion', component: Requisicion },
  ]

export default routes
