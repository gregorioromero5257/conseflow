<template>
        <main class="main">
        <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card" v-show="!detalle">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Curriculum Vitae Interno
            </div>
        <div class="card-body">

        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="empleado.id" slot-scope="props">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" class="dropdown-item" @click="descargar(props.row.empleado)">
                        <i class="icon-cloud-download"></i>&nbsp; Descargar PDF CV
                    </button>
                    <button type="button" @click="cargarExperiencias(props.row.empleado)" class="dropdown-item">
                        <i class="far fa-file-alt"></i>&nbsp; Agregar Datos al CV
                    </button>
                </div>
                </div>
                </div>
            </template>
        </v-client-table>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            
            <!-- Listado de componentes hijos -->
            <div class="card" v-show="detalle">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> CV - {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
                    <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                        <i class="fa fa-arrow-left"></i>&nbsp;Atras
                    </button>
                </div>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab" @click="setTab('tab1')">Experiencias laborales</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab2" role="tab" @click="setTab('tab2')">Escolaridades</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" :class="(activeTab == 'tab1') ? 'active show': ''" id="tab1" role="tabpanel">
                        <experienciaslaborales ref="explab"></experienciaslaborales>
                    </div>
                    <div class="tab-pane" :class="(activeTab == 'tab2') ? 'active show': ''" id="tab2" role="tabpanel">
                        <escolaridades ref="escolaridades"></escolaridades>
                    </div>
                </div>
            </div>
            <!-- Fin de listado componentes -->
            </div>
        </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const ExperienciasLaborales = r => require.ensure([], () => r(require('./Exp-laborales.vue')), 'rh');
const Escolaridades = r => require.ensure([], () => r(require('./Escolaridades.vue')), 'rh');

    export default {
        data (){
            return {
                activeTab: 'tab1',
                detalle: false,
                empleado: null,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: [
                    'empleado.id',
                    'empleado.nombre',
                    'empleado.ap_paterno',
                    'empleado.ap_materno',
                    'puesto.nombre',
                    'departamento.nombre',
                    ],
                tableData: [],
                options: {
                    headings: {
                        'empleado.id': 'Acciones',
                        'empleado.nombre': 'Nombre',
                        'empleado.ap_paterno': 'A Paterno',
                        'empleado.ap_materno': 'A Materno',
                        'puesto.nombre': 'Puesto',
                        'departamento.nombre': 'Departamento',
                        'empleado.condicion':'condicion',
                        'empleado.updated_at' : 'Ultima Actualización',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
                    filterable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
                    filterByColumn: true,
                    texts:config.texts
                },
            }
        },
        computed:{
        },
        components: {
            'experienciaslaborales' : ExperienciasLaborales,
            'escolaridades': Escolaridades
        },
        methods : {
            getData() {
                let me=this;
                axios.get('/empleado').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            descargar(data) {
                console.log(data);
                window.open('descargar-cv/' + data.id, '_blank');
            },
            cargarExperiencias(dataEmpleado = []) {
                var child = this.$refs.explab;
                child.cargarExperiencias(dataEmpleado);
                var childEsc = this.$refs.escolaridades;
                childEsc.cargarEscolaridades(dataEmpleado);
                this.detalle = true;
                this.empleado = dataEmpleado;
            },
            maestro(){
                this.$refs.myTable.setFilter({
                    'empleado.nombre': '', 'empleado.ap_paterno': '', 'empleado.ap_materno': '', 'puesto.nombre': '', 'departamento.nombre': ''
                });
                this.detalle = false;
            },
            setTab(tabName) {
                this.activeTab = tabName;
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>