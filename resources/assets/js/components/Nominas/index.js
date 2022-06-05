const Xml = r => require.ensure([], () => r(require('../Nominas/Xml.vue')), 'nom')


const routes = [
    { path: '/xml/cargar', component: Xml },
  ]

export default routes
