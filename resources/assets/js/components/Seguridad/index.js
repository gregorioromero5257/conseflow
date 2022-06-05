
const Lista = r => require.ensure([], () => r(require('./ListaAsistencia.vue')), 'seg')
const Analisis = r => require.ensure([], () => r(require('./AnalisisSeguridad.vue')), 'seg')
const Permiso = r => require.ensure([], () => r(require('./PermisoSeguridad.vue')), 'seg')
const Catalogo = r => require.ensure([], () => r(require('./CatalogoAnalisis.vue')), 'seg')
const Bitacora = r => require.ensure([], () => r(require('./BitacoraResiduos.vue')), 'seg')
const Residuos = r => require.ensure([], () => r(require('./Residuos.vue')), 'seg')
const Vale = r => require.ensure([], () => r(require('./Vale.vue')), 'seg')
const CatalogosSecuencia = r => require.ensure([], () => r(require('./Secuencias.vue')), 'seg')
const CatalogosAnalisis = r => require.ensure([], () => r(require('./CatalogoAnalisis2.vue')), 'seg')
const NuevoAnalisisSeguridad = r => require.ensure([], () => r(require('./NuevoAnalisisSeguridad.vue')), 'seg')
const routes = [

    { path: '/seguridad/lista/asistencia/', component: Lista },
    { path: '/seguridad/analisis/seguridad/', component: Analisis },
    { path: '/seguridad/permiso/seguridad/', component: Permiso },
    { path: '/seguridad/catalogo/analisis/', component: Catalogo },
    { path: '/seguridad/bitacora/residuos/', component: Bitacora },
    { path: '/seguridad/residuos/', component: Residuos },
    { path: '/seguridad/vale/epp', component: Vale },
    { path: '/seguridad/catalogo/analisis2/', component: CatalogosAnalisis },
    { path: '/catalogos/secuencias', component: CatalogosSecuencia },
    { path: '/seguridad/nuevoanalisis', component: NuevoAnalisisSeguridad },


  ]

export default routes
