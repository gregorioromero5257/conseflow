const NotFound = r => require.ensure([], () => r(require('../Aplicacion/NotFound.vue')), 'aplicacion')
const Home = r => require.ensure([], () => r(require('../Aplicacion/Home.vue')), 'aplicacion')

const routes = [
    { path: '/', component: Home },
    { path: '*', component: NotFound },
  ]

export default routes