
const Empresa = r => require.ensure([], () => r(require('./Empresa.vue')), 'sgi')
const Sgi = r => require.ensure([], () => r(require('./Sgi.vue')), 'sgi')
const Finanzas = r => require.ensure([], () => r(require('./Finanzas.vue')), 'sgi')
const Administrativa = r => require.ensure([], () => r(require('./Admin.vue')), 'sgi')
const Negocios = r => require.ensure([], () => r(require('./Negocios.vue')), 'sgi')
const Costos = r => require.ensure([], () => r(require('./Costos.vue')), 'sgi')
const Calibracion = r => require.ensure([], () => r(require('./Calibracion.vue')), 'sgi')
const Operaciones = r => require.ensure([], () => r(require('./Operaciones.vue')), 'sgi')
const ProcedAlmacen = r => require.ensure([], () => r(require('./Procedimientos/Almacen/Almacen.vue')), 'sgi')

const routes = [

  { path: '/sgi/general', component: Empresa },
  { path: '/sgi/sgi', component: Sgi },
  { path: '/sgi/finanzas', component: Finanzas },
  { path: '/sgi/admin', component: Administrativa },
  { path: '/sgi/negocios', component: Negocios },
  { path: '/sgi/costos', component: Costos },
  { path: '/sgi/calibracion', component: Calibracion },
  { path: '/sgi/operaciones', component: Operaciones },
  // Procedimietos
  { path: '/sgi/procedimientos/almacen', component: ProcedAlmacen },

]

export default routes
