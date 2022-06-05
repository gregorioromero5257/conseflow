const UploadArticulos = r => require.ensure([], () => r(require('../SubirArchivos/UploadArticulo.vue')), 'subirarchivos')
const UploadEmpleados = r => require.ensure([], () => r(require('../SubirArchivos/UploadEmpleado.vue')), 'subirarchivos')
const UploadJuntas = r => require.ensure([], () => r(require('../SubirArchivos/UploadJuntas.vue')),'subirarchivos')

const routes = [
    { path: '/uploadarticulos', component: UploadArticulos},
    { path: '/uploadempleados', component: UploadEmpleados},
    { path: '/uploadjuntas', component: UploadJuntas},
  ]

export default routes