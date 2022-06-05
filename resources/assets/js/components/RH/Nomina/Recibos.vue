<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card" v-show="!detalle">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Empleados
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
                                <button type="button" @click="cargarTipoContrato(props.row.empleado)" class="dropdown-item">
                                    <i class="fas fa-eye"></i>&nbsp; Ver contratos del Empleado
                                </button>
                            </div>
                            </div>
                            </div>
                            </template>
                        </v-client-table>
                        
                    </div>
                </div>
                <br>

                <!--Listar Contratos-->
                <div class="card" v-show="detalleContrato">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Contratos pertenecientes a:  {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
                        <button type="button" @click="maestroContratos()" class="btn btn-secondary float-sm-right">
                            <i class="fa fa-arrow-left"></i>&nbsp;Atras
                        </button>
                    </div>
                    <div class="card-body">
                        <vue-element-loading :active="isLoadingDetalle"/>
                        <v-client-table :columns="columnsTipoContrato" :data="tableDataTipoContrato" :options="optionsTipoContrato" ref="myTableTipoContrato">
                            <template slot="condicion" slot-scope="props" >
                                <template v-if="props.row.condicion">
                                    <button type="button" class="btn btn-outline-success">Activo</button>
                                </template>
                                <template v-else>
                                   <button type="button" class="btn btn-outline-danger">Dado de Baja</button>
                                </template>
                            </template>
                        <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <button type="button" @click="cargarRecibos(props.row)" class="dropdown-item">
                                    <i class="fas fa-eye"></i>&nbsp; Ver recibos
                                </button>
                            </div>
                            </div>
                            </div>
                            </template>
                        </v-client-table>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
                <!-- Listado de escolaridades del empleado -->
                <br>
                <div class="card" v-show="detalleRecibo">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Recibos pertenecientes al contrato de: {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
                        
                        <button type="button" @click="maestroRecibos()" class="btn btn-secondary float-sm-right">
                            <i class="fa fa-arrow-left"></i>&nbsp;Atras
                        </button>
                    </div>
                    <div class="card-body">
                        <vue-element-loading :active="isLoadingDetalleRecibo"/>
                        <v-client-table :columns="columnsRecibos" :data="tableDataRecibos" :options="optionsRecibos" ref="myTableRecibos">
                            <template slot="id" slot-scope="props">
                              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group dropup" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-grip-horizontal"></i> Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">   
                                <button type="button" @click="abrirModal('recibos','detalles',props.row)" class="dropdown-item">
                                    <i class="fas fa-eye"></i>&nbsp; Ver recibo
                                </button>
                                    </div>
                                </div>  
                              </div>
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
                            <!--<form action="">-->
                                <input type="hidden" name="id">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="periodo">Periodo</label>
                                    <div class="col-md-3">
                                        <input type="text" v-validate="'required'" name="periodo" v-model="recibos.periodo" class="form-control" placeholder="Periodo" autocomplete="off" id="periodo" :disabled="disabled == 1">
                                        <span class="text-danger">{{ errors.first('periodo') }}</span>
                                    </div>
                                    <label class="col-md-3 form-control-label" for="empleado_id">Empleado</label>
                                    <div class="col-md-3">
                                        <select class="form-control" id="empleado_id"  name="empleado_id" v-model="recibos.empleado_id" v-validate="'excluded:0'" data-vv-as="Empleado" :disabled="disabled == 1">
                                            <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{ item.empleado.nombre }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="periodo_inicial">Periodo Inicial</label>
                                    <div class="col-md-3">
                                        <input type="date" v-validate="'required'" name="periodo_inicial" v-model="recibos.periodo_inicial" class="form-control" placeholder="Periodo Inicial" autocomplete="off" id="periodo_inicial" :disabled="disabled == 1">
                                        <span class="text-danger">{{ errors.first('periodo_inicial') }}</span>
                                    </div>
                                    <label class="col-md-3 form-control-label" for="periodo_final">Periodo Final</label>
                                    <div class="col-md-3">
                                        <input type="date" v-validate="'required'" name="periodo_final" v-model="recibos.periodo_final" class="form-control" placeholder="Perido Final" autocomplete="off" id="periodo_final" :disabled="disabled == 1">
                                        <span class="text-danger">{{ errors.first('periodo_final') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="periodo">Observaciones</label>
                                    <div class="col-md-6">
                                    <textarea name="observaciones" v-validate="'required'" v-model="recibos.observaciones" class="form-control" placeholder="Observaciones" autocomplete="off" id="observaciones" :disabled="disabled == 1" rows="4" cols="70"></textarea>
                                    <span class="text-danger">{{ errors.first('observaciones') }}</span>
                                    </div>
                                </div>
                                <!--Datos Pertenecientes a Percepciones-->
                                <div class="form-group row" v-for="tipos in listaPerDed">
                                    <strong class="col-md-3 form-control-label">Percepción:</strong>
                                    <div class="col-md-3" v-for="percepciones in tipos.percepcion">
                                        <input type="text" name="total_a_pagar" v-model="percepciones.nombre" class="form-control" id="total_a_pagar" :disabled="disabled == 1">
                                    </div>
                                </div>
                                <div class="form-group row" v-for="tipos in listaPerDed">
                                    <strong class="col-md-3 form-control-label">Unidad:</strong>
                                    <div class="col-md-3" v-for="percepciones in tipos.percepcion">
                                        <input type="text" name="total_a_pagar" v-model="percepciones.unidad" class="form-control" id="total_a_pagar" :disabled="disabled == 1">
                                    </div>
                                </div>
                                <div class="form-group row" v-for="tipos in listaPerDed">
                                    <strong class="col-md-3 form-control-label">Costo:</strong>
                                    <div class="col-md-3" v-for="percepciones in tipos.percepcion">
                                        <input type="text" name="total_a_pagar" v-model="percepciones.costo" class="form-control" id="total_a_pagar" :disabled="disabled == 1">
                                    </div>
                                </div>
                                <div class="form-group row" v-for="tipos in listaPerDed">
                                    <strong class="col-md-3 form-control-label">Importe:</strong>
                                    <div class="col-md-3" v-for="percepciones in tipos.percepcion">
                                        <input type="text" name="total_a_pagar" v-model="percepciones.importe" class="form-control" id="total_a_pagar" :disabled="disabled == 1">
                                    </div>
                                </div>
                                <!--Fin de Percepciones-->

                                <!--Datos Pertenecientes a Deducciones-->
                                <div class="form-group row" v-for="tipos2 in listaPerDed">
                                    <strong class="col-md-3 form-control-label">Deducción:</strong>
                                    <div class="col-md-3" v-for="deducciones in tipos2.deduccion">
                                        <input type="text" name="total_a_pagar" v-model="deducciones.nombre" class="form-control" id="total_a_pagar" :disabled="disabled == 1">
                                    </div>
                                </div>
                                <div class="form-group row" v-for="tipos2 in listaPerDed">
                                    <strong class="col-md-3 form-control-label">Unidad:</strong>
                                    <div class="col-md-3" v-for="deducciones in tipos2.deduccion">
                                        <input type="text" name="total_a_pagar" v-model="deducciones.unidad" class="form-control" id="total_a_pagar" :disabled="disabled == 1">
                                    </div>
                                </div>
                                <div class="form-group row" v-for="tipos2 in listaPerDed">
                                    <strong class="col-md-3 form-control-label">Costo:</strong>
                                    <div class="col-md-3" v-for="deducciones in tipos2.deduccion">
                                        <input type="text" name="total_a_pagar" v-model="deducciones.costo" class="form-control" id="total_a_pagar" :disabled="disabled == 1">
                                    </div>
                                </div>
                                <div class="form-group row" v-for="tipos2 in listaPerDed">
                                    <strong class="col-md-3 form-control-label">Importe:</strong>
                                    <div class="col-md-3" v-for="deducciones in tipos2.deduccion">
                                        <input type="text" name="total_a_pagar" v-model="deducciones.importe" class="form-control" id="total_a_pagar" :disabled="disabled == 1">
                                    </div>
                                </div>
                                <!--Fin de Deducciones-->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="fecha_pago">Fecha de Pago</label>
                                    <div class="col-md-3">
                                        <input type="date" v-validate="'required'" name="fecha_pago" v-model="recibos.fecha_pago" class="form-control" placeholder="Fecha de Pago" autocomplete="off" id="fecha_pago" :disabled="disabled == 1">
                                        <span class="text-danger">{{ errors.first('fecha_pago') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="total_a_pagar">Total a Pagar</label>
                                    <div class="col-md-3">
                                        <input type="text" v-validate="'required'" name="total_a_pagar" v-model="recibos.total_a_pagar" class="form-control" placeholder="Total a Pagar" autocomplete="off" id="total_a_pagar" :disabled="disabled == 1">
                                        <span class="text-danger">{{ errors.first('total_a_pagar') }}</span>
                                    </div>
                                </div>
                            <!--</form>-->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="descargar()">
                            &nbsp;Imprimir&nbsp;<i class="fas fa-file-pdf"></i>&nbsp;
                            </button>
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
                url: '/recibo',
                empleado: null,
                detalle: false,
                detalleContrato: false,
                detalleRecibo: false,
                tipoContrato: {
                },
                recibos: {
                    id: 0,
                    periodo : 0,
                    periodo_inicial: '',
                    periodo_final: '',
                    observaciones: '',
                    fecha_pago: '',
                    total_a_pagar: 0,
                    recibo_idP: 0,
                    percepcione_id: 0,
                    percepcionesU: 0,
                    percepcionesC: 0,
                    percepcionesI: 0,
                    tipo_percepcione_id: 0,
                    recibo_idD: 0,
                    deduccione_id: 0,
                    deduccionesU: 0,
                    deduccionesC: 0,
                    deduccionesI: 0,
                    tipo_deduccione_id: 0,
                    recibo_idC: 0,
                    contrato_id: 0,
                    empleado_id: 0,
                },
                listaTipoDeducciones: [],
                listaTipoPercepciones: [],
                listaEmpleados: [],
                listaPerDed: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                disabled: 0,
                isLoading: false,
                isLoadingDetalle: false,
                isLoadingDetalleRecibo: false,
                columns: [
                    'empleado.id',
                    'empleado.nombre',
                    'empleado.ap_paterno',
                    'empleado.ap_materno',
                    'puesto.nombre',
                    'departamento.nombre',
                    ],
                tableData: [],
                columnsTipoContrato: [
                    'id',
                    'e_nombre',
                    'p_nombre',
                    'fecha_ingreso',
                    'fecha_fin',
                    'tc_nombre',
                    'condicion',
                    ],
                tableDataTipoContrato: [],
                columnsRecibos: [
                    'id',
                    'periodo',
                    'periodo_inicial',
                    'periodo_final',
                    'observaciones',
                    'fecha_pago',
                    'total_a_pagar',
                    ],
                tableDataRecibos: [],
                options: {
                    headings: {
                        'empleado.id': 'Contratos',
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
                optionsTipoContrato: {
                    headings: {
                        'id': 'Recibos',
                        'e_nombre': 'Nombre',
                        'p_nombre': 'Proyecto',
                        'fecha_ingreso': 'Fecha de Inicio',
                        'fecha_fin': 'Fecha de Finalización',
                        'tc_nombre': 'Tipo de Contrato',
                        'condicion': 'Estado',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['tipo_nomina_id', 'fecha_ingreso', 'fecha_fin', 'tc_nombre', 'p_nombre'],
                    filterable: ['tipo_nomina_id', 'fecha_ingreso', 'fecha_fin', 'tc_nombre', 'p_nombre', 'condicion'],
                    filterByColumn: true,
                    listColumns: {
                        condicion: config.columnCondicion
                    },
                    texts:config.texts
                },
                optionsRecibos: {
                    headings: {
                        'periodo': 'Periodo',
                        'periodo_inicial': 'Periodo Inicial',
                        'periodo_final': 'Periodo Final',
                        'observaciones': 'Observaciones',
                        'fecha_pago': 'Fecha de Pago',
                        'total_a_pagar': 'Monto Total',
                        'id': 'Detalles',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['periodo'],
                    filterable: ['periodo'],
                    filterByColumn: true,
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
            descargar(data) {
                window.open('descargar-recibo/' + this.recibos.id, '_blank');
            },
            getLista() {
                let me=this;
                axios.get('/empleado').then(response => {
                    me.listaEmpleados = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });

                axios.get('/tipodeducciones').then(response => {
                    me.listaTipoDeducciones = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });

                axios.get('/tipopercepciones').then(response => {
                    me.listaTipoPercepciones = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarRecibos(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/contrato/'+this.recibos.empleado_id+'/contratos' : '/contrato/'+this.recibos.id+'/'+'contratos/'+this.recibos.empleado_id,
                            data: {
                                'periodo': this.recibos.periodo,
                                'periodo_inicial' : this.recibos.periodo_inicial,
                                'periodo_final' : this.recibos.periodo_final,
                                'tipo_percepcione_id': this.recibos.tipo_percepcione_id,
                                'id': this.recibos.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.cargarTipoContrato(me.empleado);
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
                Utilerias.resetObject(this.recibos);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "recibos":
                    {
                        switch(accion){
                            
                            case 'detalles':
                            {
                                Utilerias.resetObject(this.recibos);
                                this.modal=1;
                                this.tituloModal= 'Detalles del Recibo';
                                this.tipoAccion=2;
                                this.disabled=1;
                                this.recibos.id=data['id'];
                                this.recibos.periodo = data['periodo'];
                                this.recibos.periodo_inicial = data['periodo_inicial'];
                                this.recibos.periodo_final = data['periodo_final'];
                                this.recibos.observaciones = data['observaciones'];
                                this.recibos.fecha_pago = data['fecha_pago'];
                                this.recibos.total_a_pagar = data['total_a_pagar'];
                                this.recibos.recibo_idP = data['recibo_idP'];
                                this.recibos.percepcione_id = data['percepcione_id'];
                                this.recibos.percepcionesU = data['percepcionesU'];
                                this.recibos.percepcionesC = data['percepcionesC'];
                                this.recibos.percepcionesI = data['percepcionesI'];
                                this.recibos.tipo_percepcione_id = data['tipo_percepcione_id'];
                                this.recibos.recibo_idD = data['recibo_idD'];
                                this.recibos.deduccione_id = data['deduccione_id'];
                                this.recibos.deduccionesU = data['deduccionesU'];
                                this.recibos.deduccionesC = data['deduccionesC'];
                                this.recibos.deduccionesI = data['deduccionesI'];
                                this.recibos.tipo_deduccione_id = data['tipo_deduccione_id'];
                                this.recibos.recibo_idC = data['recibo_idC'];
                                this.recibos.contrato_id = data['contrato_id'];
                                this.recibos.empleado_id = data['empleado_id'];
                                break;
                            }
                        }
                    }
                }
                let me=this;
                axios.get(me.url + '/' + this.recibos.id + '/recibos/' + this.recibos.empleado_id).then(response => {
                    me.listaPerDed = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cargarTipoContrato(dataEmpleado = []) {
                this.detalle = true;
                this.detalleContrato = true;
                this.isLoadingDetalle = true;
                let me=this;
                this.empleado = dataEmpleado;
                axios.get('/contrato/' + dataEmpleado.id + '/' +'contratos').then(response => {
                    me.tableDataTipoContrato = response.data;
                    me.isLoadingDetalle = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cargarRecibos(dataContrato = []) {
                this.detalleContrato = false;
                this.detalleRecibo = true;
                this.isLoadingDetalleRecibo = true;
                let me=this;
                axios.get(me.url + '/' + dataContrato.id + '/recibos').then(response => {
                    me.tableDataRecibos = response.data;
                    me.isLoadingDetalleRecibo = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            maestroContratos(){
                this.$refs.myTableTipoContrato.setFilter({
                    'fecha_ingreso': '', 'fecha_fin': '', 'tc_nombre': '', 'condicion': '',
                });
                this.detalle = false;
                this.detalleContrato = false;
            },
            maestroRecibos(){
                this.$refs.myTableRecibos.setFilter({
                    'periodo': '', 'periodo_inicial': '', 'periodo_final': '', 'observaciones': '',
                });
                this.detalleContrato = true;
                this.detalleRecibo = false;
            }
        },
        mounted() {
            this.getData();
            this.getLista();
            this.$root.limpiarDashboard();
        }
    }
</script>