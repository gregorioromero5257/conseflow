const Proyecto = r => require.ensure([], () => r(require('../Proyecto/Proyecto.vue')), 'proyecto')
const Requisicion = r => require.ensure([], () => r(require('./Requisiciones/Requisiciones.vue')), 'proyecto')
const EstadoRequisicion = r => require.ensure([], () => r(require('./Catalogos/EstadoRequisicion.vue')), 'proyecto')
const Dashboard = r => require.ensure([], () => r(require('./Dashboard/Dashboard.vue')), 'proyecto')
const PrincipalRequisiciones = r => require.ensure([], () => r(require('./Requisiciones/PrincipalRequisiciones.vue')), 'proyecto')

const Control = r => require.ensure([],()=> r(require('./Reporte/Control.vue')), 'proyecto')
const ControlEntradas = r => require.ensure([], ()=> r(require('./Reporte/ControlEntradas.vue')), 'proyecto')
const Categoria = r => require.ensure([],()=> r(require('./Catalogos/Categoria.vue')), 'proyecto')
const Subcategoria = r => require.ensure([],()=> r(require('./Catalogos/Subcategoria.vue')), 'proyecto')
const Servicios = r => require.ensure([],()=> r(require('./Servicios/Servicios.vue')), 'proyecto')
const EstatusMaterial = r => require.ensure([],()=> r(require('./Reporte/EstatusMaterial.vue')), 'proyecto')
const EstatusMaterialRequisitado = r => require.ensure([],()=> r(require('./Reporte/EstatusDeMaterialRequisitado.vue')), 'proyecto')
const EstatusDeMaterial = r => require.ensure([],()=> r(require('./Reporte/EstatusDeMaterial.vue')), 'proyecto')
const RequisicionPorCompra = r => require.ensure([], ()=> r(require('./RequisicionporCompra/RequisicionOrdenCompra.vue')),'proyecto')
const TrazabilidadGeneral = r => require.ensure([], ()=> r(require('./Reporte/TrazabilidadGeneral.vue')),'proyecto')
const Supervisor = r => require.ensure([], ()=> r(require('./Supervisores/Supervisor.vue')),'proyecto')
const EstadoCategoia = r => require.ensure([], ()=> r(require('./EstadoCategoria/EstadoCategoria.vue')),'proyecto')
// const Principal = r => require.ensure([], ()=> r(require('./NewRequisiciones/Principal.vue')),'proyecto')

const PrincipalRequisicionesAsme = r => require.ensure([], () => r(require('./RequisicionesAsme/PrincipalRequisiciones.vue')), 'proyecto')


const routes = [
    { path: '/proyecto', component: Proyecto },
    { path: '/proyecto/requisicion', component: PrincipalRequisiciones },
    { path: '/proyecto/catalogos/estadorequisicion/' , component: EstadoRequisicion },
    { path: '/proyecto/dashboard/', component: Dashboard },
    { path: '/proyecto/reportes/control', component: Control},
    { path: '/proyecto/categoria/', component: Categoria },
    { path: '/proyecto/subcategoria/', component: Subcategoria },
    { path: '/proyecto/reportes/controlE/', component: ControlEntradas },
    { path: '/proyecto/servicios/', component: Servicios },
    { path: '/proyecto/estatusmaterial/', component: EstatusMaterial},
    { path: '/proyecto/estatus/material/requisitado/', component: EstatusMaterialRequisitado},
    { path: '/proyecto/reportes/estatus/material/', component: EstatusDeMaterial},
    { path: '/proyecto/requisicionporcompra/requisicion/', component: RequisicionPorCompra},
    { path: '/proyecto/reportes/trazabilidad/general/', component: TrazabilidadGeneral},
    { path: '/proyecto/supervisores/supervisor/', component: Supervisor},
    { path: '/proyecto/estadocaegoria', component: EstadoCategoia},
    // { path: '/proyecto/new/requisiciones/', component: Principal},
    { path: '/proyecto/asme/requisiciones/', component: PrincipalRequisicionesAsme},
  ]

export default routes
