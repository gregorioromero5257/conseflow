const Usuario = r => require.ensure([], () => r(require('../Sistema/Usuario.vue')), 'sistema')
const Auditoria = r => require.ensure([], () => r(require('../Sistema/Auditoria.vue')), 'sistema')
const Errors = r => require.ensure([], () => r(require('../Sistema/Errors.vue')), 'sistema')
const ModulosSistema = r => require.ensure([], () => r(require('../Sistema/ModulosSistema.vue')), 'sistema')
const PermisosUsuarios = r => require.ensure([],()=> r(require('../Sistema/PermisosUsuarios.vue')),'sistema')
const ElementosMenu = r => require.ensure([], () => r(require('../Sistema/ElementosMenu.vue')), 'sistema')
const ElementosDashboard = r => require.ensure([], () => r(require('../Sistema/ElementosDashboard.vue')), 'sistema')
const PermisosDashboard = r => require.ensure([], () => r(require('../Sistema/PermisosDashboard.vue')), 'sistema')
const Folio = r => require.ensure([], () => r(require('../Sistema/Folios.vue')), 'sistema')
const Controles = r => require.ensure([], () => r(require('../Sistema/ControlesSistema.vue')), 'sistema')
const PermisosCrud = r => require.ensure([], () => r(require('../Sistema/PermisosCrud.vue')), 'sistema')
const UsuarioCategoria = r => require.ensure([], () => r(require('../Sistema/UsuarioCategoria.vue')), 'sistema')
const Transferencia = r => require.ensure([], () => r (require('../Sistema/Transferencia.vue')), 'sistema')
const Correos = r => require.ensure([], () => r (require('../Sistema/Correos.vue')), 'sistema')
const CambiarRequis = r => require.ensure([], () => r (require('../Sistema/CambiarRequisicion.vue')), 'sistema')
const PermisosViaticos = r => require.ensure([], () => r (require('./PermisosViaticos.vue')), 'sistema')


const routes = [
    { path: '/usuario', component: Usuario},
    { path: '/auditoria', component: Auditoria},
    { path: '/errors', component: Errors},
    { path: '/modulos', component: ModulosSistema},
    { path: '/permisousuario', component: PermisosUsuarios},
    { path: '/elementosmenu', component: ElementosMenu},
    { path: '/elementosdashboard', component: ElementosDashboard},
    { path: '/permisosdashboard', component: PermisosDashboard},
    { path: '/folios', component: Folio},
    { path: '/controles', component: Controles},
    { path: '/permisocrud/', component: PermisosCrud},
    { path: '/usuariocategoria', component: UsuarioCategoria},
    { path: '/correos', component: Correos},
    { path: '/requis', component: CambiarRequis},
    { path: '/permisosviaticos', component: PermisosViaticos},
  ]

export default routes
