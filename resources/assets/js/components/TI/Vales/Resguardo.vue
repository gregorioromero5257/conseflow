<template>
    <div>
        <div class="card">
            <div class="card-body">
                <v-client-table :data="tableData" :columns="columns" :options="options">
                    <template slot='data.id' slot-scope='props'>
                        <div class='btn-group' role='group'>
                            <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <i class='fas fa-grip-horizontal'></i>&nbsp;
                            </button>
                            <div class='dropdown-menu'>
                                <template>
                                    <button type='button' v-if="props.row.data.estado <= 1" @click='abrirModal(2, props.row)' class='dropdown-item'>
                                        <i class='fas fa-edit'></i>&nbsp;Actualizar
                                    </button>
                                    <!-- <button type='button' v-if="props.row.data.estado <= 1" @click='eliminar(props.row)' class='dropdown-item'>
                                        <i class='fas fa-trash'></i>&nbsp;Eliminar
                                    </button> -->
                                    <button type='button' v-if="props.row.data.estado <= 1" @click='Regresar(props.row)' class='dropdown-item'>
                                        <i class='fas fa-undo'></i>&nbsp;Retorno
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template slot="descargar" slot-scope="props">
                        <button type='button' @click='descargar(props.row)' class='btn btn-outline-dark'>
                            <i class='fas fa-download'></i>&nbsp;
                        </button>
                    </template>

                    <template slot="data.estado" slot-scope="props">
                        <template v-if="props.row.data.estado == 1">
                            <span class="btn btn-outline-success"> EN RESGUARDO</span>
                        </template>
                        <template v-if="props.row.data.estado == 2">
                            <span class="btn btn-outline-primary"> RETORNADO</span>
                        </template>
                        <template v-if="props.row.data.estado == 3">
                            <span class="btn btn-outline-warning"> RETORNADO CON OBSERVACIONES</span>
                        </template>
                    </template>

                    <template slot="data.tipo" slot-scope="props">
                        <template v-if="props.row.data.tipo == 1">
                            <span class="btn btn-outline-success"> Computo</span>
                        </template>
                        <template v-if="props.row.data.tipo == 2">
                            <span class="btn btn-outline-primary"> Accesorios</span>
                        </template>
                        <template v-if="props.row.data.tipo == 3">
                            <span class="btn btn-outline-warning"> Impresión</span>
                        </template>
                        <template v-if="props.row.data.tipo == 4">
                            <span class="btn btn-outline-success"> Video</span>
                        </template>
                    </template>
                </v-client-table>
            </div>
        </div>
        <!-- Modal -->
        <div class='modal fade' tabindex='-1' :class="{'mostrar' : modal}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
            <div class='modal-dialog modal-dark modal-lg' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h4 class='modal-title' v-text='tituloModal'></h4>
                        <button type='button' class='close' @click='CerrarModal()' aria-label='Close'>
                            <span aria-hidden='true'>×</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label>Fecha</label>
                                <input type="date" class="form-control" v-model="resguardo.fecha" v-validate="'required'" data-vv-name="fecha">
                                <span class="text-danger">{{errors.first('fecha')}}</span>
                            </div>
                            <div class="col-md-3 mb-3" v-show="tipoAccion != 3">
                                <label>Tipo</label>
                                <select class="form-control" v-validate="'required'" data-vv-name="tipo" v-model="resguardo.tipo">
                                    <option value="">Seleccione</option>
                                    <option value="1">Computo</option>
                                    <option value="2">Accesorios</option>
                                    <option value="3">Impresion</option>
                                    <option value="4">Video</option>
                                </select>
                                <span class="text-danger">{{errors.first('tipo')}}</span>
                            </div>
                            <div class="col-md-3 mb-3" v-show="tipoAccion != 3">
                                <label>Empresa</label>
                                <select class="form-control" v-model="resguardo.empresa">
                                    <option value="1">Conserflow</option>
                                    <option value="2">CSCT</option>
                                </select>
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label>Seleccione</label>
                                <v-select :options="listaCatalogo" :disabled="tipoAccion == 3" v-model="resguardo.caiv" label="descripcion" data-vv-name="material" @search="buscar" @input="asignar()">
                                </v-select>
                            </div>
                        </div>
                        <div class="form-row" v-show="tipoAccion != 3">
                            <div class="col-md-8 mb-3">
                                <label>Enlistar Acesorios Adicionales</label>
                                <v-select :options="listaCatalogoAccesorios" v-model="resguardo.accesorios_data"
                                label="descripcion" data-vv-name="material" @input="agregarCantidad()">
                                </v-select>
                                <!-- <textarea name="name" rows="2" cols="80" v-model="resguardo.acesorios" class="form-control"></textarea> -->
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>Cantidad</label>
                                <input type="text" class="form-control" v-model="resguardo.cantidad_accesorio">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>&nbsp;</label><br>
                                <button class="btn btn-outline-dark" @click="crearListado()">Agregar</button>
                            </div>
                        </div>
                        <div class="form-row" v-show="tipoAccion != 3">
                            <div class="form-group col-md-9">
                                <label><b>Descripción</b></label>
                            </div>
                            <div class="form-group col-md-2">
                                <label><b>Cantidad</b></label>
                            </div>
                            <div class="form-group col-md-1">
                                <label><b>.</b></label>
                            </div>
                        </div>
                        <li v-for="(vi, index) in listado_data_accesorios" class="list-group-item" v-show="tipoAccion != 3">
                            <div class="form-row">

                                <div class="form-group col-md-9">
                                    <label>{{vi.descripcion}}</label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>{{vi.cantidad}}</label>
                                </div>
                                <div class="form-group col-md-1">
                                    <a @click="deleteu(index, vi)">
                                        <span class="fas fa-trash" arial-hidden="true"></span>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <div class="form-row" v-show="tipoAccion != 3">
                            <div class="col-md-10 mb-3">
                                <label>Observaciones adicionales a la entrega</label>
                                <textarea name="name" rows="2" cols="80" v-model="resguardo.observacion_dos" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-row" v-show="tipoAccion != 3">
                            <div class="col-md-10 mb-3">
                                <label>Observaciones adicionales a la recepción</label>
                                <textarea name="name" rows="2" cols="80" v-model="resguardo.observacion_uno" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-row" >
                            <div class="col-md-4 mb-3">
                                <label>Cantidad</label>
                                <input type="text" v-validate="'required|decimal:2'" :disabled="tipoAccion == 3" data-vv-name="cantidad" class="form-control" v-model="resguardo.cantidad">
                                <span class="text-danger">{{errors.first('cantidad')}}</span>
                            </div>
                            <template v-if="tipoAccion == 3">
                                <div class="col-md-4 mb-3">
                                    <label>Cantidad Defectuoso</label>
                                    <input type="text" v-validate="'required|decimal:2'" data-vv-name="cantidad" class="form-control" v-model="resguardo.cantidad_defectuoso" @input="verificarCantidad()">
                                </div>
                            </template>
                        </div>
                        <template v-if="tipoAccion == 3">
                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label>Observaciones observados al retorno</label>
                                    <textarea name="name" rows="2" cols="80" v-model="resguardo.observacion_retorno" class="form-control"></textarea>
                                </div>
                            </div>
                        </template>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <div class="form-check checkbox" >
                                    <input type="checkbox" v-model="resguardo.check">
                                    <label class="form-check-label" >
                                        QR
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <span class="text-danger">{{error}}</span>
                            <template v-if="resguardo.check == true">
                                <label>{{empleado_recibe == '' ? '' : empleado_recibe.name}}</label>
                                <qrcode-stream @decode="onDecode" @init="onInit" />
                            </template>
                            <template v-if="resguardo.check == false">
                                <div class="col-md-6 mb-3">
                                    <label>Usuario Asignado</label>
                                    <v-select :options="listaEmpleados" label="name" v-model="empleado_recibe" data-vv-name="usuario" v-validate="'required'"></v-select>
                                    <span class="text-danger">{{errors.first('usuario')}}</span>
                                </div>
                            </template>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">

                                <label>Usuario Entrega</label>
                                <v-select :options="listaEmpleados" label="name" v-model="empleado_entrega" data-vv-name="usuario" v-validate="'required'"></v-select>
                                <span class="text-danger">{{errors.first('usuario')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <vue-element-loading :active="isLoading"/>
                        <button type='button' class='btn btn-outline-dark' @click='CerrarModal()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                        <button type='button' v-if='tipoAccion == 1' class='btn btn-secondary' @click='Guardar(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                        <button type='button' v-if='tipoAccion == 2' class='btn btn-secondary' @click='Guardar(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                        <button type='button' v-if='tipoAccion == 3' class='btn btn-secondary' @click='GuardarRetorno()'><i class='fas fa-save'></i>&nbsp;Aceptar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- Modal -->

    </div>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
import { QrcodeStream } from 'vue-qrcode-reader';


export default {
    data (){
        return {
            listado_data_accesorios : [],
            listaCatalogoAccesorios : [],

            isLoading : false,
            empresa : 0,
            error: '',
            listaCatalogo : [],
            resguardo : {
                id : 0,
                fecha : '',
                tipo : '',
                caiv : '',
                acesorios : '',
                observacion_uno : '',
                observacion_dos : '',
                check : false,
                cantidad : '',
                cantidad_temporal : '',
                empresa : '1',
                observacion_retorno : '',
                cantidad_defectuoso : 0,
                cantidad_accesorio : '',
                cantidad_accesorio_temporal : '',
                accesorios_data : '',
            },
            empleado_recibe : '',
            empleado_entrega : '',
            listaEmpleados : [],
            modal : 0,
            tipoAccion : 0,
            tituloModal : '',
            tableData : [],
            columns : ['data.id','data.fecha','descripcion','data.tipo','data.acesorios','data.observacion_uno','data.cantidad','data.empleado_r','data.estado','descargar'],
            options :
            {
                headings:
                {
                    'data.id': 'Acciones',
                    'data.fecha': 'Fecha',
                    'data.acesorios': 'Acesorios',
                    'data.observacion_uno': 'Observaciones adicionales a la recepción',
                    'data.cantidad' : 'Cantidad',
                    'data.empleado_r' :'Usuario asignado',
                    'data.estado' :'Estado',
                    'data.tipo' :'Tipo',

                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                listColumns: {
                  'data.estado': [{
                    id: 1,
                    text: 'En resguardo'
                  },
                  {
                    id: 2,
                    text: 'Retornado'
                  },
                ],
                'data.tipo': [{
                  id: 1,
                  text: 'Computo'
                },
                {
                  id: 2,
                  text: 'Accesorios'
                },
                {
                  id: 3,
                  text: 'Impresión'
                },
                {
                  id: 4,
                  text: 'Video'
                },
              ],
              },
                texts: config.texts
            }, //options
        }
    },
    methods : {
        async onInit (promise) {
            try {
                // console.log(promise,'dj');
                await promise
            } catch (error) {
                if (error.name === 'NotAllowedError') {
                    this.error = "ERROR: you need to grant camera access permisson"
                } else if (error.name === 'NotFoundError') {
                    this.error = "ERROR: no camera on this device"
                } else if (error.name === 'NotSupportedError') {
                    this.error = "ERROR: secure context required (HTTPS, localhost)"
                } else if (error.name === 'NotReadableError') {
                    this.error = "ERROR: is the camera already in use?"
                } else if (error.name === 'OverconstrainedError') {
                    this.error = "ERROR: installed cameras are not suitable"
                } else if (error.name === 'StreamApiNotSupportedError') {
                    this.error = "ERROR: Stream API is not supported in this browser"
                }
            }
        },

        onDecode (result) {
            this.result = result
            var porciones = this.result.split('|');
            this.empleado_recibe = {id : porciones[0], name : porciones[1]};
        },

        getInicial(empresa = 1){
            this.empresa = empresa;
            axios.get('resguardo/material/ti/get/' + empresa).then(response => {
                this.tableData = response.data;
            }).catch(e => {
                console.error(e);
            });
        },

        getData(){
            this.listaEmpleados = [];
            axios.get('/vertodosempleados').then(response =>{
                response.data.forEach(data =>{
                    this.listaEmpleados.push({
                        id: data.id,
                        name: data.nombre + ' ' + data.ap_paterno + ' ' + data.ap_materno
                    });
                });
            }).catch(function (error)
            {
                console.log(error);
            });

        },

        getAccesorios(){
          this.listaCatalogoAccesorios = [];
          axios.get('ti/inventario/accesorios/obtener/activos').then(response => {
              response.data.accesorios.forEach((item, i) => {
                  this.listaCatalogoAccesorios.push({
                      id : item.id,
                      descripcion : item.descripcion + ' ' + item.marca + ' ' + item.modelo + ' ' + item.no_serie,
                      cantidad : item.cantidad,
                  });
              });
          }).catch(e => {
              console.error(e);
          });
        },

        abrirModal(tipo, data = []){
            if (tipo == 1) {
                Utilerias.resetObject(this.resguardo);
                this.listado_data_accesorios = [];
                this.empleado_recibe = '';
                this.tituloModal = 'Guardar';
                this.modal = 1;
                this.tipoAccion = 1;
            }
            if (tipo == 2) {

                Utilerias.resetObject(this.resguardo);
                this.listado_data_accesorios = [];
                this.tituloModal = 'Actualizar';
                this.modal = 1;
                this.tipoAccion = 2;
                this.resguardo.id = data['data']['id'];
                this.resguardo.fecha = data['data']['fecha'];
                this.resguardo.cantidad =  data['data']['cantidad'];
                this.resguardo.caiv = {id : data['data']['caiv'], descripcion :  data['descripcion'] , cantidad : data['data']['cantidad']} ;
                this.resguardo.acesorios = data['data']['acesorios'];
                this.resguardo.tipo = data['data']['tipo'];
                this.resguardo.observacion_uno = data['data']['observacion_uno'];
                this.resguardo.observacion_dos = data['data']['observacion_dos'];
                this.empleado_recibe = {id :  data['data']['empleado_recibe'] , name :  data['data']['empleado_r']};
                this.resguardo.empresa = data['data']['empresa'];
                this.empleado_entrega = {id :  data['data']['empleado_entrega'] , name :  data['data']['empleado_e']};
                this.listado_data_accesorios = JSON.parse(data['data']['accesorios_listado']);
            }
        },

        CerrarModal(){
            this.modal = 0;
        },

        Guardar(nuevo){
          // if (this.resguardo.cantidad > this.resguardo.cantidad_temporal) {
          //   toastr.warning('No se puede agregar una cantidad mayor a la existente !!!');
          // }else {
            this.$validator.validate().then(result => {
              if (result) {
                this.isLoading = true;
                axios({
                  method : nuevo ? 'POST' : 'PUT',
                  url : nuevo ? 'resguardo/material/ti/guardar' : 'resguardo/material/ti/actualizar',
                  data : {
                    id : this.resguardo.id,
                    fecha : this.resguardo.fecha,
                    tipo : this.resguardo.tipo,
                    acesorios : this.resguardo.acesorios,
                    observacion_uno : this.resguardo.observacion_uno,
                    observacion_dos : this.resguardo.observacion_dos,
                    empleado_recibe : this.empleado_recibe.id,
                    empleado_entrega : this.empleado_entrega.id,
                    caiv : this.resguardo.caiv.id,
                    cantidad : this.resguardo.cantidad,
                    empresa : this.resguardo.empresa,
                    accesorios : this.listado_data_accesorios,
                  }
                }).then(response => {
                  if (response.data.estado == 1) {
                    toastr.success(nuevo ? 'Guardado Correctamente' : 'Actualizado Correctamente');
                    this.CerrarModal();
                    this.getInicial(this.empresa);
                    this.getAccesorios();
                    this.listado_data_accesorios = [];
                  }else if (response.data.estado == 2) {
                    toastr.warning('No hay stock suficiente del material !!!');
                  }else if (response.data.estado == 3) {
                    toastr.warning('No se puede cambiar el material principal se debe retornar !!!');
                  }
                  this.isLoading = false;
                }).catch(e => {
                  this.isLoading = false;
                  toastr.error('Ha ocurrido un error !!!');
                  console.error(e);
                });
              }
            });
          // }
        },

        buscar(search, loading){
            if (this.resguardo.tipo === '') {
                toastr.warning('Seleccione un tipo para realizar una busqueda');
            }
            else {

                if (search.length > 2) {

                    let me = this;

                    setTimeout(function(){
                        axios.post('get/material/ti/descripcion',{
                            des : search,
                            tipo : me.resguardo.tipo,
                        }).then(response => {
                            if(response.data.length==0)
                            {
                                toastr.warning("Sin artículos encontrados");
                                me.listaCatalogo=[];
                            }
                            else
                            {
                                me.listaCatalogo = response.data;
                            }
                        }).catch(e =>{
                            console.error(e);
                        });
                    }, 1000);

                }

            }
        },

        asignar(){
            if (this.resguardo.caiv != '') {
              this.resguardo.cantidad = this.resguardo.caiv.cantidad;
              this.resguardo.cantidad_temporal = this.resguardo.caiv.cantidad;
            }
        },

        eliminar(data){
            axios.get('resguardo/material/ti/eliminar/' + data.data.id).then(response => {
                toastr.success('Correcto');
                this.getInicial(this.empresa);
            }).catch(e => {
                console.error(e);
            });
        },

        /*
        * Regresa el equipo
        */
        Regresar(equipo)
        {
            this.tituloModal = 'Regresar equipo';
            this.modal = true;
            this.tipoAccion = 3;
            this.resguardo.id = equipo['data']['id'];
            this.resguardo.cantidad =  equipo['data']['cantidad'];
            this.resguardo.caiv = {id : equipo['data']['caiv'], descripcion :  equipo['descripcion'] , cantidad : equipo['data']['cantidad']} ;
            this.resguardo.acesorios = equipo['data']['acesorios'];
            this.resguardo.tipo = equipo['data']['tipo'];
            this.resguardo.observacion_uno = equipo['data']['observacion_uno'];
            this.empleado_recibe = {id :  equipo['data']['empleado_recibe'] , name :  equipo['data']['empleado_r']};
            this.resguardo.empresa = equipo['data']['empresa'];
            this.empleado_entrega = {id :  equipo['data']['empleado_entrega'] , name :  equipo['data']['empleado_e']};
            this.resguardo.cantidad_defectuoso = 0;
        },

        GuardarRetorno()
        {
            this.$validator.validate().then(result =>{
                if (result){
                    this.isLoading = true;
                    axios.post("ti/resguardos/regresar",{
                        id : this.resguardo.id,
                        observacion_recepcion : this.resguardo.observacion_recepcion,
                        cantidad_defectuoso :   this.resguardo.cantidad_defectuoso,
                        fecha : this.resguardo.fecha
                    }).then(res =>{
                        if (res.data.status){
                            toastr.success("Guardado correctamente");
                            this.CerrarModal();
                            this.getInicial(this.empresa);
                            this.getAccesorios();
                        }else{
                            toastr.error(res.data.mensaje);
                        }
                        this.isLoading = false;
                    }).catch(e =>{
                        this.isLoading = false;
                        toastr.error('Ha ocurrido un error !!!');
                        console.error(e);
                    });
                }
            });
        },

        descargar(data){
            window.open('resguardo/material/ti/descargar/' + data.data.id , '_blank');
        },

        verificarCantidad(){
            if (this.resguardo.cantidad_defectuoso > this.resguardo.cantidad) {
                toastr.warning('No se puede agregar una cantidad mayor a la inicial');
                this.resguardo.cantidad_defectuoso = 0;
            }
        },

        /**
        * Creacion de array asociativo que guarda el listado de accesorios seleccionados
        **/
        crearListado(){
          if (this.resguardo.cantidad_accesorio > this.resguardo.cantidad_accesorio_temporal) {
            toastr.warning('No se puede agregar una cantidad mayor a la existente !!!');
          }else {
            this.listado_data_accesorios.push({
              id: this.resguardo.accesorios_data.id,
              descripcion : this.resguardo.accesorios_data.descripcion,
              cantidad : this.resguardo.cantidad_accesorio,
              nuevo : 0,
            });
            this.resguardo.accesorios_data = '';
            this.resguardo.cantidad_accesorio = '';
            this.resguardo.cantidad_accesorio_temporal = '';
          }
        },

        agregarCantidad(){
          this.resguardo.cantidad_accesorio =  this.resguardo.accesorios_data.cantidad;
          this.resguardo.cantidad_accesorio_temporal =  this.resguardo.accesorios_data.cantidad;
        },

        deleteu(index , vi){
          if (this.tipoAccion == 1) {
            this.listado_data_accesorios.splice(index, 1);
          }else if (this.tipoAccion == 2) {
            axios.get('eliminar/accesorio/resguardo/ti/' + vi.id + '&' + this.resguardo.id).then(response => {
              toastr.success('Correcto !!!');
              this.listado_data_accesorios.splice(index, 1);
              this.getAccesorios();
              this.getInicial(this.empresa);
            }).catch(e => {
              console.error(e);
            });
          }
          // console.log(vi.id);
          // console.log(this.resguardo.id);

        },
    },
    mounted(){
        this.getData();
        this.getInicial();
        this.getAccesorios();
    }
}

</script>
