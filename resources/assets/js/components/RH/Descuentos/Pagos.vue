<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card" v-show="!detalle">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Pagos descuentos
                    </div>
                    <div class="card-body">

                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                            <template slot="empleado.id" slot-scope="props">
                                <button type="button" @click="cargardescuento(props.row.empleado)" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </template>
                        </v-client-table>

                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
                <!-- Listado de escolaridades del empleado -->
                <br>
                <div class="card" v-show="detalle">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Pagos pertenecientes a:  {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
                        <button type="button" @click="abrirModal('descuentos','registrar',empleado.id)" class="btn btn-primary float-sm-right">
                            <i class="fas fa-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="maestro()" class="btn btn-primary float-sm-right">
                            <i class="fas fa-backward"></i>&nbsp;Atras
                        </button>
                    </div>
                    <div class="card-body">
                        <vue-element-loading :active="isLoadingDetalle"/>
                        <v-client-table :columns="columnsdescuento" :data="tableDatadescuento" :options="optionsdescuento" ref="myTabledescuento">

                            <template slot="id" slot-scope="props">
                                <button type="button" @click="abrirModal('descuentos','actualizar',empleado.id,props.row)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button>
                            </template>
                        </v-client-table>

                    </div>
                </div>
                <!-- Fin Listado de escolaridades del empleado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <vue-element-loading :active="isLoading"/>
                <div class="modal-dialog modal-primary modal-lg" role="document">
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
                              <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
                              <div class="col-md-9">
                                <input type="date" v-validate="'required'" name="fecha" v-model="descuento.fecha" class="form-control" placeholder="Fecha" autocomplete="off" id="fecha">
                                <span class="text-danger">{{ errors.first('fecha') }}</span>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="cantidad">Cantidad</label>
                              <div class="col-md-9">
                                <input type="number" v-validate="'required'" name="cantidad" v-model="descuento.cantidad" class="form-control" placeholder="Unidad" autocomplete="off" id="cantidad">
                                <span class="text-danger">{{ errors.first('cantidad') }}</span>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="numero_pago">Numero de pago</label>
                              <div class="col-md-9">
                                <input type="number" v-validate="'required'" name="numero_pago" v-model="descuento.numero_pago" class="form-control" placeholder="Unidad" autocomplete="off" id="numero_pago">
                                <span class="text-danger">{{ errors.first('numero_pago') }}</span>
                              </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="descuento_id">Descuento</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="descuento_id"  name="descuento_id" v-model="descuento.descuento_id" v-validate="'excluded:0'" data-vv-as="Tipo Descuento" :disabled="disabled == 1">
                                        <option v-for="item in listaTipoDescuento" :value="item.Did" :key="item.Did">Descuento de la fecha {{ item.Dfecha }}</option>
                                    </select>
                                    <span class="text-danger">{{ errors.first('descuento_id') }}</span>
                                </div>
                            </div>



                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="guardardescuento(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="guardardescuento(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                        </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
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
                url: '/pagodescuento',
                empleado: null,
                detalle: false,
                descuento: {
                    id: 0,
                    fecha: '',
                    cantidad : 0,
                    numero_pago : 0,
                    descuento_id : 0,
                    empleado_id :0,

                },
                listaTipoNomina: [],
                listaEmpleados: [],
                listadescuento: [],
                listaTipoDescuento: [],
                listaHorarios: [],
                listaProyectos: [],
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
                columnsdescuento: [
                    'id',
                    'fecha',
                    'Dfecha',
                    'numero_pago',
                    'TDnombre',
                    'Dtotal',
                    'cantidad',

                    ],
                tableDatadescuento: [],
                options: {
                    headings: {
                        'empleado.id': 'Acción',
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
                optionsdescuento: {
                    headings: {
                        'id': 'Acción',
                        'fecha': 'Fecha de pago',
                        'Dfecha': 'Fecha del descuento',
                        'numero_pago': 'Número de pago',
                        'TDnombre': 'Tipo de Descuento',
                        'Dtotal': 'Total deuda',
                        'cantidad': 'Cantidad pagada',

                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['fecha','unidad','total','descuento'],
                    filterable: ['fecha','descuento'],
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
                axios.get('/pagodescuento').then(response => {
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

                axios.get('/descuento').then(response => {
                    me.listadescuento = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });



            },
            guardardescuento(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? me.url : '/pagodescuento'+'/'+this.descuento.id,

        //url: nuevo ? me.url+'/'+this.descuento.id+'/pagodescuento' : me.url+'/'+this.descuento.id+'/'+'pagodescuento/'+this.descuento.descuento_id,
                            data: {
                              'id':this.descuento.id,
                                'fecha':this.descuento.fecha,
                                'cantidad':this.descuento.cantidad,
                                'numero_pago':this.descuento.numero_pago,
                                'descuento_id':this.descuento.descuento_id,

                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.cargardescuento(me.empleado);
                                if(!nuevo){
                                toastr.success('Pago Actualizado Correctamente');
                                }
                                else
                                {
                                toastr.success('Pago Registrado Correctamente');

                                }
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
                Utilerias.resetObject(this.descuento);
            },
            abrirModal(modelo, accion, id,data = []){
                switch(modelo){
                    case "descuentos":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Pago';
                                Utilerias.resetObject(this.descuento);
                                this.tipoAccion = 1;
                                this.descuento.empleado_id=id;
                                let me=this;
                                axios.get('/pagodescuento/'+id).then(response => {
                                    me.listaTipoDescuento = response.data;
                                })
                                .catch(function (error) {
                                    console.log(error);
                                });
                                this.disabled=0;
                                break;
                            }
                            case 'actualizar':
                            {
                                Utilerias.resetObject(this.descuento);
                                this.modal=1;
                                this.tituloModal= 'Actualizar Pago';
                                this.tipoAccion=2;
                                this.disabled=1;
                                this.descuento.id=data['id'];
                                this.descuento.fecha = data['fecha'];
                                this.descuento.cantidad = data['cantidad'];
                                this.descuento.numero_pago = data['numero_pago'];
                                this.descuento.descuento_id=data['descuento_id'];

                                break;
                            }
                        }
                    }
                }
            },
            cargardescuento(dataEmpleado = []) {
                this.detalle = true;
                this.isLoadingDetalle = true;
                let me=this;
                this.empleado = dataEmpleado;
                this.descuento.empleado_id = dataEmpleado.id;
                axios.get(me.url + '/' + dataEmpleado.id ).then(response => {
                    me.tableDatadescuento = response.data;
                    me.isLoadingDetalle = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            maestro(){
                this.$refs.myTabledescuento.setFilter({
                     'fecha': '','unidad': '','total': '','descuento': '',
                });
                this.detalle = false;
            }
        },
        mounted() {
            this.getData();
            this.getLista();
            this.$root.limpiarDashboard();
        }
    }
</script>
