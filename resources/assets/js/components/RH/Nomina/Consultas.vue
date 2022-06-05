<template>
    <main class="main">
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card" v-show="!detalle">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Nominas
            </div>
            <div class="card-body">

                <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                    <template slot="empleado.id" slot-scope="props">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                      <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i> Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <button type="button" @click="cargarnomina(props.row.empleado)" class="dropdown-item">
                            <i class="fas fa-eye"></i>&nbsp; Ver nomina
                        </button>
                        </div>
                      </div>
                        </div>
                    </template>
                </v-client-table>

            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
        <!-- Listado de nominas del empleado -->
        <br>
        <div class="card" v-show="detalle">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Nomina perteneciente a:  {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
                <!-- <button type="button" @click="abrirModal('contratos','registrar')" class="btn btn-primary float-sm-right">
                    <i class="fas fa-plus"></i>&nbsp;Nuevo
                </button> -->
                <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                    <i class="fa fa-arrow-left"></i>&nbsp;Atras
                </button>
            </div>
            <div class="card-body">
                <vue-element-loading :active="isLoadingDetalle"/>
                <v-client-table :columns="columnsnomina" :data="tableDatanomina" :options="optionsnomina" ref="myTablenomina">

                    <template slot="id" slot-scope="props">
                        <!-- <button type="button" @click="abrirModal('contratos','actualizar',props.row)" class="btn btn-warning btn-sm">
                            <i class="icon-pencil"></i>
                        </button> -->
                    </template>
                </v-client-table>

            </div>
        </div>
        <!-- Fin Listado de escolaridades del empleado -->
    </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <vue-element-loading :active="isLoading"/>
                <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">
                        <div>

                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->

                             <div class="form-group row">

                                    <label class="col-md-3 form-control-label" for="proyecto_id">Nombre Proyecto</label>
                                    <div class="col-md-3">
                                        <input type="text"  name="p_nombre" v-model="nomina.p_nombre" class="form-control" placeholder="Nombre Proyecto" autocomplete="off" id="p_nombre">
                                        <span class="text-danger">{{ errors.first('p_nombre') }}</span>
                                    </div>
                                    <label class="col-md-3 form-control-label" for="p_nombre_corto">Nombre Corto Proyecto</label>
                                    <div class="col-md-3">
                                        <input type="text"  name="p_nombre_corto" v-model="nomina.p_nombre_corto" class="form-control" placeholder="Nombre Corto Proyecto" autocomplete="off" id="p_nombre_corto">
                                        <span class="text-danger">{{ errors.first('p_nombre_corto') }}</span>
                                    </div>
                                   <label class="col-md-3 form-control-label" for="p_clave">Clave Proyecto</label>
                                    <div class="col-md-3">
                                        <input type="text"  name="p_clave" v-model="nomina.p_clave" class="form-control" placeholder="Clave Proyecto" autocomplete="off" id="p_clave">
                                        <span class="text-danger">{{ errors.first('p_clave') }}</span>
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="p_periodo">Periodo Proyecto</label>
                                    <div class="col-md-3">
                                        <input type="text" name="p_periodo" v-model="nomina.p_periodo" class="form-control" placeholder="Periodo" autocomplete="off" id="p_periodo">
                                        <span class="text-danger">{{ errors.first('p_periodo') }}</span>
                                    </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="empleado_id">Empleado</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="empleado_id"  name="empleado_id" v-model="nomina.empleado_id" v-validate="'excluded:0'" data-vv-as="empleado" :disabled="disabled == 1">
                                        <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{ item.empleado.nombre }} {{ item.empleado.ap_paterno }} {{ item.empleado.ap_materno }}</option>
                                    </select>
                                    <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                                </div>
                            </div>


                            <!-- </form> -->

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarnomina(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarnomina(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                        </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
                </div>
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                url: '/nomina',
                empleado: null,
                detalle: false,
                nomina: {
                    id: 0,
                    empleado: '',
                    p_periodo : '',
                    p_nombre: '',
                    p_nombre_corto : '',
                    p_clave : 0,
                    empleado_id : 0,
                    proyecto_id: 0,
                    recibo_id: 0,

                },

                listaEmpleados: [],
                listanomina: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                disabled: 0,
                isLoading: false,
                isLoadingDetalle: false,
                columns: [
                    'empleado.id',
                    'empleado.nombre',
                    'empleado.ap_paterno',
                    'empleado.ap_materno',
                    'puesto.nombre',
                    'departamento.nombre',
                    ],
                tableData: [],
                columnsnomina: [
                    'id',
                    'empleado',
                    'p_periodo',
                    'p_nombre',
                    'p_nombre_corto',
                    'p_clave',
                    // 'nomina',

                    ],
                tableDatanomina: [],
                options: {
                    headings: {
                        'empleado.id': 'Acciones',
                        'empleado.nombre': 'Nombre',
                        'empleado.ap_paterno': 'Apellido Paterno',
                        'empleado.ap_materno': 'Apellido Materno',
                        'puesto.nombre': 'Puesto',
                        'departamento.nombre': 'Departamento',
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
                optionsnomina: {
                    headings: {
                        'id': 'Acciones',
                        'empleado': 'Empleado',
                        'p_periodo': 'Periodo Proyecto',
                        'p_nombre': 'Nombre Proyecto',
                        'p_nombre_corto': 'Nombre Corto Proyecto',
                        'p_clave': 'Clave de Proyecto',
                        // 'nomina': 'Tipo de nomina',

                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['empleado','p_perido','p_nombre_corto', 'p_clave'],
                    filterable: ['empleado'],
                    filterByColumn: true,
                    listColumns: {
                        condicion: config.columnCondicion
                    },
                    texts:config.texts
                },
            }
        },
        computed:{
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
            getLista() {
                let me=this;
                axios.get('/empleado').then(response => {
                    me.listaEmpleados = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });

                // axios.get('/nomina').then(response => {
                //     me.listanomina = response.data;
                // })
                // .catch(function (error) {
                //     console.log(error);
                // });


            },
            guardarnomina(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? me.url+'/'+this.nomina.empleado_id+'/nomina' : me.url+'/'+this.nomina.id+'/'+'nomina/'+this.nomina.empleado_id ,
                            data: {
                                'empleado':this.nomina.empleado,
                                'p_periodo':this.nomina.p_periodo,
                                'p_nombre':this.nomina.p_nombre,
                                'p_nombre_corto':this.nomina.p_nombre_corto,
                                'p_clave': this.nomina.p_clave,
                                'empleado_id':this.nomina.empleado_id,
                                'id':this.nomina.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.cargarnomina(me.empleado);
                            } else {
                                swal({
                                    type: 'error',
                                    html: response.data.errors.join('<br>'),
                                });
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                });
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                Utilerias.resetObject(this.nomina);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "contratos":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar nomina';
                                Utilerias.resetObject(this.nomina);
                                this.tipoAccion = 1;
                                this.disabled=0;
                                break;
                            }
                            case 'actualizar':
                            {
                                Utilerias.resetObject(this.nomina);
                                this.modal=1;
                                this.tituloModal= 'Actualizar nomina';
                                this.tipoAccion=2;
                                this.disabled=1;
                                this.nomina.id=data['id'];
                                this.nomina.p_periodo = data['p_periodo'];
                                this.nomina.p_nombre=data['p_nombre'];
                                this.nomina.p_nombre_corto=data['p_nombre_corto'];
                                this.nomina.p_clave=data['p_clave'];
                                this.nomina.empleado_id = data['empleado_id'];
                                break;
                            }
                        }
                    }
                }
            },
            cargarnomina(dataEmpleado = []) {
                this.detalle = true;
                this.isLoadingDetalle = true;
                let me=this;
                this.empleado = dataEmpleado;



                axios.get(me.url + '/' + dataEmpleado.id + '/' +'nomina').then(response => {
                    me.tableDatanomina = response.data;
                })
                .catch(function (error){
                    console.log(error);
                });
                this.isLoadingDetalle = false;
            },
            maestro(){
                this.$refs.myTablenomina.setFilter({
                     'empleado': '','p_nombre': '','p_clave': '',
                });
                this.detalle = false;
            }
        },
        mounted() {
            this.getData();
            this.getLista();
        }
    }
</script>
