import Vue from 'vue'
import Router from 'vue-router'
import Almacen from '../components/Almacen'
import RH from '../components/RH'
import Proyecto from '../components/Proyecto'
import Sistema from '../components/Sistema'
import Aplicacion from '../components/Aplicacion'
import SubirArchivos from '../components/SubirArchivos'
import Compras from '../components/Compras'
import Trafico from '../components/Trafico'
import TI from '../components/TI'
import Contabilidad from '../components/Contabilidad'
import Contraloria from '../components/Contraloria'
import Costos from '../components/Costos'
import Ventas from '../components/Ventas'
import Viaticos from '../components/Viaticos'
import Operaciones from '../components/Operaciones'
import Calidad from '../components/Calidad'
import DireccionFinanciera from '../components/DireccionFinanciera'
import SGI from '../components/SGI'
// import Nominas from '../components/Nominas'
import Inventario from '../components/Inventario'
import Seguridad from '../components/Seguridad'


Vue.use(Router)


var allRoutes = []

allRoutes = allRoutes.concat(Almacen,RH,Proyecto,Sistema,Aplicacion,SubirArchivos,Compras,Trafico,TI,Contabilidad,Contraloria,Costos,Ventas,Viaticos,Operaciones,Calidad,DireccionFinanciera,SGI,Inventario,Seguridad)

const routes = allRoutes
export default new Router({
  routes,
  linkExactActiveClass: 'active',
})
