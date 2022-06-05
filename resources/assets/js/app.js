
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
import Modulos from './components/Aplicacion/Modulos.vue';
// import BotonAgregar  from './components/Herramientas/Nuevo.vue';
import Vue from 'vue';
import Vuee from 'vue'
// import JsonExcel from './components/Herramientas/JsonExcel';
import {ClientTable, ServerTable, Event} from 'vue-tables-2';
import VeeValidate from 'vee-validate';
import VueExcelEditor from 'vue-excel-editor';

import { VueEditor } from "vue2-editor";

import VueGoodTablePlugin from 'vue-good-table';

// import VueElementLoading from './components/Herramientas/element-loading';
import moment from 'moment';
import vSelect from './components/Herramientas/Select';

// import the styles
import 'vue-good-table/dist/vue-good-table.css'

import router from './router';

Vue.component('v-select', vSelect)
Vue.use(ClientTable);
Vue.use(ServerTable);
Vue.use(moment);
Vue.use(VueGoodTablePlugin);

Vuee.use(VueExcelEditor);
// Vue.component('downloadExcel', JsonExcel);
// Vue.component('botonNuevo', BotonAgregar);
// Vue.component('VueElementLoading', VueElementLoading)

Vue.component('downloadExcel', r => require.ensure([], () => r(require('./components/Herramientas/JsonExcel')), 'herramientas') )
Vue.component('botonNuevo', r => require.ensure([], () => r(require('./components/Herramientas/Nuevo.vue')), 'herramientas') )
Vue.component('VueElementLoading', r => require.ensure([], () => r(require('./components/Herramientas/element-loading')), 'herramientas') )

const VueValidationEs = require('vee-validate/dist/locale/es');
Vue.use(VeeValidate,  {
    locale: 'es',
    dictionary: {
      es: VueValidationEs
    }
});

router.beforeEach((to, from, next) => {
    axios.post('/modulos/cargar',{'name':JSON.stringify(to.path)}).then(function (response) {
        if (response.data.permiso) {
            next()
        }else{
            next(false)
        }
    }).catch(function (error) {console.log(error)})
})
const app = new Vue({
    el: '#app',
    router,
    components: {Modulos},
    data: {
        nameApp: 'ConserFlow',
        id:0,
        elementosMenu: [],
        elSubMenu:   [],
        esSubMenu:0,
        showModal: true,
        RolId: 0,
        dashboard:[],
        localMenu:[],
    },
    methods: {
        abrirModalModulos() {
            this.showModal = true;
        },
        cerrarModalModulos() {
            if (localStorage.getItem('menu')) {
                  this.crearMenu();
            }
            this.showModal = false;
        },
        limpiarDashboard(){
            this.dashboard=0;
        },
        cargarMenuModulo(id,submenu,nombre, ruta) {
            let me=this;
            axios.post('/elementosmenu',{
                'id': id,
                'submenu': submenu
            }).then(function (response) {
                //carga todos los elementos al localStorage y manda a crear el menu
                const menuP = JSON.stringify(response.data.menu);
                const dashboardP = JSON.stringify(response.data.dashboard);
                const submenuP = submenu == 0 ?JSON.stringify('{}'):JSON.stringify(response.data.submenu);

                localStorage.setItem('menu', menuP);
                localStorage.setItem('submenu', submenuP);
                localStorage.setItem('dashboard', dashboardP);

                localStorage.setItem('esSubmenu', submenu);
                localStorage.setItem('nombre', nombre);
                localStorage.setItem('ruta', ruta);
                me.crearMenu();

                me.showModal = false;
            })
            .catch(function (error) {
                console.log(error);
            });
            router.push(ruta);
        },
        cerrarSesion(){
            //remueve todos los elementos al cerrar la sesion
            if (localStorage.getItem('menu')) {
                localStorage.removeItem('menu');
                localStorage.removeItem('submenu');
                localStorage.removeItem('dashboard');
                localStorage.removeItem('esSubmenu');
                localStorage.removeItem('nombre');
                localStorage.removeItem('ruta');
            }
        },
        crearMenu(respuesta){
            let me=this;
            var _menu = JSON.parse(localStorage.getItem('menu'));
            me.esSubmenu = localStorage.getItem('esSubmenu');
            var _submenu = me.esSubmenu === 0 ? false:JSON.parse(localStorage.getItem('submenu'));
            me.dashboard = localStorage.getItem('dashboard');
            me.nameApp = localStorage.getItem('nombre');


            me.elementosMenu = [];
            _menu.forEach(mn =>{
                me.elementosMenu.push(mn);
            });
            if (me.esSubmenu == 1) {
                me.elSubMenu=[];
                _submenu.forEach(sbmn =>{
                    me.elSubMenu.push(sbmn);
                });
            }
        },
    }
}
);
