<template>
  <div>
    <h4 v-show="tipo == 1">{{text_manto}}</h4>
    <h4 v-show="tipo == 2">{{text_serv}}</h4>
    <div class="card">
      <div class="card-body">
        <div class="form-group" >

          Mantenimiento
          <label class="switch switch-default switch-pill switch-dark">
            <input type="radio" class="switch-input" name="customRadioInline1" @change="formMantenimiento($event)">
            <span class="switch-label"></span>
            <span class="switch-handle"></span>
          </label>
          &nbsp;
          Eventual
          <label class="switch switch-default switch-pill switch-dark">
            <input type="radio" class="switch-input" name="customRadioInline1" @change="formEventual($event)">
            <span class="switch-label"></span>
            <span class="switch-handle"></span>
          </label>

        </div>
        <!--Inicio form de mantenimiento  -->
        <template v-if="tipo == 1">

          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Operacion</label>
              <select class="form-control" name="operacion" data-vv-name="Operacion" v-model="mantenimiento.operacion" @change="cambiar()">
                <option :value="0" selected>--Seleccione--</option>
                <option :value="1">Verificación de niveles</option>
                <option :value="2">Sustituciones</option>
                <option :value="3">Comprobación y ajuste de componentes</option>
                <option :value="4">Refacciones</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Tipo</label>
              <v-select :options="operacion_array" v-model="mantenimiento.tipo" label="name"></v-select>


            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label>
              <button type="button" class="form-control" name="button" @click="confirmar()">Confirmar</button>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Servicios</label>
              <v-select multiple :options="servicios_totales"  v-validate="'required'" data-vv-name="Servicios" label="name" v-model="mantenimiento.tipo_servicios"></v-select>
              <span class="text-danger">{{ errors.first('Servicios') }}</span>
            </div>
          </div>
          <hr>
          <div class="form-row">
            <div class="form-group col-md-5">
              <label>Proveedor</label>
              <input type="text" class="form-control"  v-validate="'required'" data-vv-name="Proveedor" name="proveedor" v-model="mantenimiento.proveedor">
              <span class="text-danger">{{ errors.first('Proveedor') }}</span>
            </div>
            <div class="form-group col-md-4">
              <label>Responsable</label>
              <input type="text" class="form-control"  v-validate="'required'" data-vv-name="Responsable" name="responsable" v-model="mantenimiento.responsable">
              <span class="text-danger">{{ errors.first('Responsable') }}</span>
            </div>
            <div class="form-group col-md-3">
              <label>Costo total</label>
              <input type="text" class="form-control"  v-validate="'required|decimal:2'" data-vv-name="total" name="total" v-model="mantenimiento.total">
              <span class="text-danger">{{ errors.first('total') }}</span>
            </div>
          </div>
          <div class="form-group col-md-4">
            <label>Fecha</label>
            <input type="date" class="form-control"  v-validate="'required'" data-vv-name="Fecha" name="fecha_servicio" v-model="mantenimiento.fecha_servicio">
            <span class="text-danger">{{ errors.first('Fecha') }}</span>
          </div>
          <hr>
          <div class="form-row">
            <div class="form-group col-md-2">
              <label>Kilometraje</label>
              <input type="number" class="form-control"  v-validate="'required'" data-vv-name="Kilometraje" name="kilometraje" v-model="mantenimiento.kilometraje">
              <span class="text-danger">{{ errors.first('Kilometraje') }}</span>
            </div>
            <div class="form-group col-md-3">
              <label>Fecha entrega</label>
              <input type="date" class="form-control"  v-validate="'required'" data-vv-name="Fecha entrega" name="fecha_entrega" v-model="mantenimiento.fecha_entrega">
              <span class="text-danger">{{ errors.first('Fecha entrega') }}</span>
            </div>
            <div class="form-group col-md-3">
              <label>Fecha proxíma revisión</label>
              <input type="date" class="form-control"  v-validate="'required'" data-vv-name="Fecha proxíma revisión" name="fecha_prox_rev" v-model="mantenimiento.fecha_prox_rev">
              <span class="text-danger">{{ errors.first('Fecha proxíma revisión') }}</span>
            </div>
            <div class="form-group col-md-2">
              <label>Kilometraje revisión</label>
              <input type="number" class="form-control" v-validate="'required'" data-vv-name="Kilometraje revisión"  name="kilometraje_revision" v-model="mantenimiento.kilometraje_revision">
              <span class="text-danger">{{ errors.first('Kilometraje revisión') }}</span>
            </div>
          </div>
          <div class="form-row">

            <template v-if="mantenimiento.factura != ''">
              <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <button type="button" class="form-control" @click="descargar(mantenimiento.factura)">
                  <i class="fas fa-file-download"></i>&nbsp;Descargar
                </button>
              </div>
              <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <button type="button" class="form-control" @click="actualizaMto()">
                  <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                </button>
              </div>
            </template>
            <template v-if="mantenimiento.factura == ''">
              <div class="form-group col-md-5">
                <label>Comprobante</label>
                <input type="file" class="form-control" name="comprobante"  @change="onChangeMto($event)">
              </div>
            </template>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarMto()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="Tipo_Manto_Edo == 1" class="btn btn-secondary" @click="guardarMto(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="Tipo_Manto_Edo == 2" class="btn btn-secondary" @click="guardarMto(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
          </div>
        </template>
        <!-- Fin de form de mantenimiento -->
        <!-- Inicio de form de servcio -->
        <template v-if="tipo == 2" >
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Fecha</label>
              <input type="date" class="form-control" v-model="servicio.fecha_servicio" data-vv-name="Fecha servicio_" v-validate="'required'">
              <span class="text-danger">{{errors.first('Fecha servicio_')}}</span>
            </div>
            <div class="form-group col-md-4">
              <label>Fecha entrega</label>
              <input type="date" class="form-control"  v-model="servicio.fecha_entrega" data-vv-name="Fecha entrega_" v-validate="'required'">
              <span class="text-danger">{{errors.first('Fecha entrega_')}}</span>
            </div>
            <div class="form-group col-md-3">
              <label>Kilometraje</label>
              <input type="number" class="form-control"  v-model="servicio.kilometraje" data-vv-name="Kilometraje_" v-validate="'required'">
              <span class="text-danger">{{errors.first('Kilometraje_')}}</span>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-5">
              <label>Proveedor</label>
              <input type="text" class="form-control"  v-model="servicio.proveedor" data-vv-name="Proveedor_" v-validate="'required'">
              <span class="text-danger">{{errors.first('Proveedor_')}}</span>
            </div>
            <div class="form-group col-md-4">
              <label>Responsable</label>
              <input type="text" class="form-control" v-model="servicio.responsable" data-vv-name="responsable_" v-validate="'required'">
              <span class="text-danger">{{errors.first('responsable_')}}</span>
            </div>
            <div class="form-group col-md-3">
              <label>Total</label>
              <input type="text" class="form-control" v-model="servicio.total" data-vv-name="Total_" v-validate="'required'">
              <span class="text-danger">{{errors.first('Total_')}}</span>
            </div>
          </div>
          <hr>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Operacion</label>
              <select class="form-control"  v-model="servicio.operacion" @change="cambiar()">
                <option :value="0" selected>--Seleccione--</option>
                <option :value="1">Verificación de niveles</option>
                <option :value="2">Sustituciones</option>
                <option :value="3">Comprobación y ajuste de componentes</option>
                <option :value="4">Refacciones</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Tipo</label>
              <v-select :options="operacion_array" v-model="servicio.tipo" label="name"></v-select>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label>
              <button type="button" class="form-control" name="button" @click="confirmar()">Confirmar</button>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label>Servicios</label>
              <v-select multiple :options="servicios_totales" label="name" v-model="servicio.tipo_servicios" data-vv-name="Servicio" v-validate="'required'"></v-select>
              <span class="text-danger">{{errors.first('Servicio')}}</span>
            </div>
          </div>
          <hr>
          <div class="form-row">

            <template v-if="servicio.comprobante != ''">
              <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <button type="button" class="form-control" @click="descargar(servicio.comprobante)">
                  <i class="fas fa-file-download"></i>&nbsp;Descargar
                </button>
              </div>
              <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <button type="button" class="form-control" @click="actualizaServ()">
                  <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                </button>
              </div>
            </template>
            <template v-if="servicio.comprobante == ''">
              <div class="form-group col-md-5">
                <label>Comprobante</label>
                <input type="file" class="form-control" name="comprobante_dos" @change="onChangeServ($event)" >
              </div>
            </template>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarSrv()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="Tipo_Serv_Edo == 1" class="btn btn-secondary" @click="guardarServ(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="Tipo_Serv_Edo == 2" class="btn btn-secondary" @click="guardarServ(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
          </div>
        </template>
        <!-- Fin de form de servicio -->
        <!-- Inicio de tabla de mantenimiento -->
        <template v-if="tipo == 1">
          <v-client-table :data="datamto" :options="optionsmto" :columns="columnsmto" ref="tableMto">
            <template slot="mantenimiento.id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" @click="ActualizarMto(props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp;Actualizar.
                  </button>
                </div>
              </div>
            </div>
          </template>
          </v-client-table>
        </template>
        <!-- Fin de tabla de mantenimiento -->
        <!-- Inicio de tabla de servicio -->
        <template v-if="tipo == 2">
          <v-client-table :data="dataserv" :options="optionsserv" :columns="columnsserv" ref="tableServ">
            <template slot="servicio.id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" @click="ActualizarServ(props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp;Actualizar.
                  </button>
                </div>
              </div>
            </div>
          </template>
        </v-client-table>
        </template>

        <!-- Fin de tabla de servicio -->
      </div>
    </div>
  </div>
</template>
<script>
const Mantenimiento = r => require.ensure([], () => r(require('./Mantenimiento.vue')), 'trafico');
const Eventual = r => require.ensure([], () => r(require('./Eventual.vue')), 'trafico');
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default {
  data (){
    return{
      tipo : 0,
      Tipo_Manto_Edo : 1,
      Tipo_Serv_Edo : 1,
      operacion_array : [],
      text_manto : 'Nuevo registro de mantenimiento',
      text_serv : 'Nuevo registro de servicio',
      id : '',
      catalogos : [],
      servicios_totales : [],
      mantenimiento : {
        operacion : '0',
        tipo_operacion : '',
        fecha_servicio : '',
        kilometraje : '',
        proveedor : '',
        responsable : '',
        total : '',
        descripcion : '',
        comprobante : '',
        fecha_entrega : '',
        fecha_prox_rev : '',
        kilometraje_revision : '',
        tipo : '',
        tipo_servicios : [],
        factura : '',
        id : 0,
      },
      servicio : {
        id : 0,
        fecha_servicio : '',
        fecha_entrega : '',
        kilometraje : '',
        proveedor : '',
        responsable : '',
        total :'',
        descripcion : '',
        comprobante : '',
        operacion : '0',
        tipo: '',
        tipo_servicios : [],
      },
      datamto: [],
      columnsmto: ['mantenimiento.id','mantenimiento.proveedor','mantenimiento.responsable','mantenimiento.total',
      'mantenimiento.fecha_servicio','mantenimiento.kilometraje','mantenimiento.fecha_entrega'],
      optionsmto: {
          headings: {
            'mantenimiento.id':'Acciones',
            'mantenimiento.proveedor':'Proveedor',
            'mantenimiento.responsable':'Responsable',
            'mantenimiento.total':'Total',
            'mantenimiento.fecha_servicio':'Fecha servicio',
            'mantenimiento.kilometraje':'Kilometraje',
            'mantenimiento.fecha_entrega' :'Fecha entrega',

          },
          perPage: 5,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['mantenimiento.proveedor','mantenimiento.responsable','mantenimiento.total',
          'mantenimiento.fecha_servicio','mantenimiento.kilometraje','mantenimiento.fecha_entrega'],
          filterable: ['mantenimiento.proveedor','mantenimiento.responsable','mantenimiento.total',
          'mantenimiento.fecha_servicio','mantenimiento.kilometraje','mantenimiento.fecha_entrega' ],
          filterByColumn: true,
          listColumns: { },
          texts:config.texts
      },
      dataserv: [],
      columnsserv: ['servicio.id','servicio.proveedor','servicio.responsable','servicio.total',
      'servicio.fecha_servicio','servicio.total','servicio.fecha_entrega'],
      optionsserv: {
          headings: {
            'servicio.id':'Acciones',
            'servicio.proveedor':'Proveedor',
            'servicio.responsable':'Responsable',
            'servicio.total':'Total',
            'servicio.fecha_servicio':'Fecha servicio',
            'servicio.fecha_entrega' :'Fecha entrega',
          },
          perPage: 5,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['servicio.proveedor','servicio.responsable','servicio.total',
          'servicio.fecha_servicio','servicio.total','servicio.fecha_entrega'],
          filterable: ['servicio.proveedor','servicio.responsable','servicio.total',
          'servicio.fecha_servicio','servicio.total','servicio.fecha_entrega'],
          filterByColumn: true,
          listColumns: { },
          texts:config.texts
      },
    }
  },
  components : {
    'mantenimiento' : Mantenimiento,
    'eventual' : Eventual,
  },
  methods :{
    getDataMto(id){
      axios.get('mantenimientounidades/'+id).then(response => {
        this.datamto = response.data;
      }).catch(err => {
        console.error(err);
      });

    },

    getDataServ(id){
      axios.get('serviciounidades/'+id).then(response => {
        this.dataserv = response.data;
      }).catch(err => {
        console.error(err);
      });
    },

    onChangeMto(e){
      this.mantenimiento.factura = e.target.files[0];
    },

    onChangeServ(e){
      this.servicio.comprobante = e.target.files[0];
    },

    actualizaMto(){
      this.mantenimiento.factura = '';
    },

    actualizaServ(){
      this.servicio.comprobante = '';
    },
    formMantenimiento(e){
      this.tipo = 1;
      // Utilerias.resetObject(this.servicio);
      this.servicio.tipo_servicios = [];
      this.servicio.operacion = 0;
      this.getDataMto(this.id);
    },

    formEventual(e){
      this.tipo = 2;
      // Utilerias.resetObject(this.mantenimiento);
      this.mantenimiento.tipo_servicios = [];
      this.mantenimiento.operacion = 0;
      this.getDataServ(this.id);
    },

    cambiar(){
      var array = [];
      if (this.mantenimiento.operacion == 0 || this.servicio.operacion == 0) {
      array = [];
      }

      if (this.mantenimiento.operacion == 1 || this.servicio.operacion == 1) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 1){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }

      if (this.mantenimiento.operacion == 2 || this.servicio.operacion == 2) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 2){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }
      if (this.mantenimiento.operacion == 3 || this.servicio.operacion == 3) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 3){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }
      if (this.mantenimiento.operacion == 4 || this.servicio.operacion == 4) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 4){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }
      this.operacion_array = [];
      this.operacion_array = array;
    },

    getId(id){
      this.id = id;
    },

    setCatalogo(catalogos){
      this.catalogos = catalogos;
    },

    confirmar(){
      // if(this.mantenimiento.tipo_servicios.length == 0 || this.mantenimiento.tipo_servicios == null){
      //   this.mantenimiento.tipo_servicios.push({
      //       id : this.mantenimiento.tipo.id, name :  this.mantenimiento.tipo.name
      //   });
      // }else {
      // this.mantenimiento.tipo_servicios.forEach((item, i) => {
      //     if(item.id != this.mantenimiento.tipo.id){
      //       this.mantenimiento.tipo_servicios.push({
      //         id : this.mantenimiento.tipo.id, name :  this.mantenimiento.tipo.name
      //       });
      //     }else {
      //       toastr.warning('ATENCIÓN','Registro ya agregado');
      //     }
      //   });
      // }

        if (this.tipo == 1) {
          this.mantenimiento.tipo_servicios.push({
              id : this.mantenimiento.tipo.id, name :  this.mantenimiento.tipo.name
          });
        }
        if (this.tipo == 2) {
          this.servicio.tipo_servicios.push({
            id : this.servicio.tipo.id, name :  this.servicio.tipo.name
          });
        }
    },

    guardarMto(nuevo){
      this.$validator.validate().then(result => {
        if(result){
          // this.isLoading = true;
          var array = [];
          this.mantenimiento.tipo_servicios.forEach((item, i) => {
            array.push(item.id);
          });

          let me = this;
          let formData = new FormData();
          formData.append('metodo', nuevo);
          formData.append('proveedor', this.mantenimiento.proveedor);
          formData.append('responsable', this.mantenimiento.responsable);
          formData.append('total', this.mantenimiento.total);
          formData.append('fecha_servicio', this.mantenimiento.fecha_servicio);
          formData.append('kilometraje', this.mantenimiento.kilometraje);
          formData.append('fecha_entrega', this.mantenimiento.fecha_entrega);
          formData.append('fecha_prox_rev', this.mantenimiento.fecha_prox_rev);
          formData.append('kilometraje_revision', this.mantenimiento.kilometraje_revision);
          formData.append('factura', this.mantenimiento.factura);
          formData.append('id', this.mantenimiento.id);
          formData.append('unidad_id', this.id);
          formData.append('tipo_servicios',array);
          axios.post('mantenimientounidades',formData).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              if(!nuevo){
                toastr.success('Mantenimiento Actualizada Correctamente');
                 me.cerrarMto();
                me.getDataMto(me.id);
              }
              else
              {
                toastr.success('Mantenimiento Registrado Correctamente');
                me.cerrarMto();
                me.getDataMto(me.id);
              }
            }
            else {
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

    ActualizarMto(data){
      this.mantenimiento.tipo_servicios = [];

      data.partidas.forEach((item, i) => {
        this.mantenimiento.tipo_servicios.push({
            id : item.catalogo_trafico_id, name :  item.nombre
        });
      });
      this.mantenimiento.proveedor = data['mantenimiento']['proveedor'];
      this.mantenimiento.responsable = data['mantenimiento']['responsable'];
      this.mantenimiento.total = data['mantenimiento']['total'];
      this.mantenimiento.fecha_servicio = data['mantenimiento']['fecha_servicio'];
      this.mantenimiento.kilometraje = data['mantenimiento']['kilometraje'];
      this.mantenimiento.fecha_entrega = data['mantenimiento']['fecha_entrega'];
      this.mantenimiento.fecha_prox_rev = data['mantenimiento']['fecha_prox_rev'];
      this.mantenimiento.kilometraje_revision = data['mantenimiento']['kilometraje_revision'];
      this.mantenimiento.factura = data['mantenimiento']['factura'];
      this.mantenimiento.id = data['mantenimiento']['id'];
      this.Tipo_Manto_Edo = 2;
    },

    cerrarMto(){
      Utilerias.resetObject(this.mantenimiento);
      this.Tipo_Manto_Edo = 1;
      this.mantenimiento.tipo_servicios = [];
      this.mantenimiento.operacion = 0;
    },

        guardarServ(nuevo){
          this.$validator.validate().then(result => {
            if(result){
              // this.isLoading = true;
              var array = [];
              this.servicio.tipo_servicios.forEach((item, i) => {
                array.push(item.id);
              });

              let me = this;
              let formData = new FormData();
              formData.append('metodo', nuevo);
              formData.append('fecha_servicio', this.servicio.fecha_servicio);
              formData.append('fecha_entrega', this.servicio.fecha_entrega);
              formData.append('kilometraje', this.servicio.kilometraje);
              formData.append('proveedor', this.servicio.proveedor);
              formData.append('responsable', this.servicio.responsable);
              formData.append('total', this.servicio.total);
              formData.append('comprobante', this.servicio.comprobante);
              formData.append('id', this.servicio.id);
              formData.append('unidad_id', this.id);
              formData.append('tipo_servicios',array);
              axios.post('serviciounidades',formData).then(function (response) {
                me.isLoading = false;
                if (response.data.status) {
                  if(!nuevo){
                    toastr.success('Servicio Actualizada Correctamente');
                     me.cerrarServ();
                    me.getDataServ(me.id);
                  }
                  else
                  {
                    toastr.success('Servicio Registrado Correctamente');
                    me.cerrarServ();
                    me.getDataServ(me.id);
                  }
                }
                else {
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

        ActualizarServ(data){
          this.servicio.tipo_servicios = [];

          data.partidas.forEach((item, i) => {
            this.servicio.tipo_servicios.push({
                id : item.catalogo_trafico_id, name :  item.nombre
            });
          });
          this.servicio.fecha_servicio = data['servicio']['fecha_servicio'];
          this.servicio.fecha_entrega = data['servicio']['fecha_entrega'];
          this.servicio.kilometraje = data['servicio']['kilometraje'];
          this.servicio.proveedor = data['servicio']['proveedor'];
          this.servicio.responsable = data['servicio']['responsable'];
          this.servicio.total = data['servicio']['total'];
          this.servicio.comprobante = data['servicio']['comprobante'];
          this.servicio.id = data['servicio']['id'];
          this.Tipo_Serv_Edo = 2;
        },


        cerrarServ(){
          Utilerias.resetObject(this.servicio);
          this.Tipo_Serv_Edo = 1;
          this.servicio.tipo_servicios = [];
          this.servicio.operacion = 0;
        },

    descargar(archivo){
      let me=this;
      axios({
        url: '/UnidadesStore/' + archivo,
        method: 'GET',
        responseType: 'blob', // importante
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
        document.body.appendChild(link);
        link.click();
        //--Llama el metodo para borrar el archivo local una ves descargado--//
        axios.get('/UnidadesStore/' + archivo + '/edit')
        .then(response => {
        })
        .catch(function (error) {
          console.log(error);
        });
        //--fin del metodo borrar--//
      });
    },



  },

  mounted () {

  }
}
</script>
