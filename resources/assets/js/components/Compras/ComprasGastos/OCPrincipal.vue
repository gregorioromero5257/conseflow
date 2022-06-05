<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card" v-show="estado == 1">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> OC de gastos {{usuario_texto}}
          <div class="dropdown float-sm-right" v-show="usuario_texto != ''">
            <button type="button" class="btn btn-secondary dropdown-toggle" id="dropmenub" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" name="button">
              Tipo
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdown" >
              <button class="dropdown-item" @click="vertipo('global')">Global</button>
              <button class="dropdown-item" @click="vertipo('general')">General</button>
              <!-- <button class="dropdown-item" @click="vertipo('fecha')">Fecha</button> -->
              <!-- <button class="dropdown-item" @click="vertipo('proacre')">Proveedor/Acreedor</button> -->
              <!-- <button class="dropdown-item" @click="vertipo('total')">Total</button> -->
            </div>
          </div>
          <div class="dropdown float-sm-right">
            <button type="button" class="btn btn-secondary dropdown-toggle" id="dropmenub" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" name="button">
              Empresa
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdown" >
              <div v-for="elem in empresas" :key="elem">
                <button class="dropdown-item" @click="verocs(elem)">{{elem}}</button>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <template v-if="tipo_listado === 'fecha'">
            <div class="form-row">
              <div class="form-group col-md-3">
                <label>Mes</label>
                <select class="form-control" v-model="busqueda.mes">
                  <option value="01">ENERO</option>
                  <option value="02">FEBRERO</option>
                  <option value="03">MARZO</option>
                  <option value="04">ABRIL</option>
                  <option value="05">MAYO</option>
                  <option value="06">JUNIO</option>
                  <option value="07">JULIO</option>
                  <option value="08">AGOSTO</option>
                  <option value="09">SEPTIEMBRE</option>
                  <option value="10">OCTUBRE</option>
                  <option value="11">NOVIEMBRE</option>
                  <option value="12">DICIEMBRE</option>
                </select>
              </div>
              <div class="form-group col-md-3">
                <label>Año</label>
                <input type="number" class="form-control" v-model="busqueda.anio" maxlength="4">
              </div>
              <div class="form-group col-md-6">
                <label>&nbsp;</label>
                <br>
                <button class="btn btn-outline-dark" @click="verocsfechas()">
                  <i class="fas fa-calendar-alt"></i>&nbsp;<i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </template>

          <template v-if="tipo_listado === 'proacre'">
            <div class="form-row">
              <div class="form-group col-md-3">
                <label>Proveedor/Acreedor</label>
                <input type="text" class="form-control" v-model="busqueda.proacre" >
              </div>
              <div class="form-group col-md-6">
                <label>&nbsp;</label>
                <br>
                <button class="btn btn-outline-dark" @click="verocsproacre()">
                  <i class="fas fa-users"></i>&nbsp;<i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </template>

          <template v-if="tipo_listado === 'total'">
            <div class="form-row">
              <div class="form-group col-md-3">
                <label>Total</label>
                <input type="text" class="form-control" v-model="busqueda.total" v-validate="'decimal:2'" data-vv-name="Total">
                <span>{{errors.first('Total')}}</span>
              </div>
              <div class="form-group col-md-6">
                <label>&nbsp;</label>
                <br>
                <button class="btn btn-outline-dark" @click="verocstotal()">
                  <i class="fas fa-money"></i>&nbsp;<i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </template>



          <v-client-table :data="tableDataUno" :options="optionsuno" :columns="columnsuno" v-if="tipo_listado === 'global'">

            <template slot="total" slot-scope="props">
              $ {{(parseFloat(props.row.total)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}}
            </template>

            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" data-placement="top" title="Acciones" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp;Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" @click="vercompras(props.row)" href="#">
                      <i class="fas fa-file-pdf"></i>&nbsp;Ver OC's
                    </a>
                  </div>
                </div>
              </div>
            </template>

          </v-client-table>

          <v-client-table :data="tableDataDos" :options="optionsdos" :columns="columnsdos" v-if="tipo_listado != 'global'">

            <template slot="cargo" slot-scope="props">
              $ {{(parseFloat(props.row.cargo)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}}
            </template>

            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" data-placement="top" title="Acciones" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <!-- <button type="button" @click="ver_facturas(props.row)" class="dropdown-item" >
                      <i class="fas fa-file-pdf"></i>&nbsp; Ver Facturas
                    </button>

                    <button type="button" @click="asociar_oc(props.row)" class="dropdown-item" >
                      <i class="fas fa-find"></i>&nbsp; Asociar OC
                    </button> -->

                    <button type="button" @click="subirxml(props.row)" class="dropdown-item" >
                      <i class="fas fa-file-xml"></i>&nbsp; Subir
                    </button>

                  </div>
                </div>
              </div>
            </template>

            <template slot="descargar" slot-scope="props">
              <button class="btn btn-outline-dark" @click="cargardetalle(props.row)" >
                <i class="fas fa-list"></i>&nbsp;
              </button>
            </template>

            <template slot="format" slot-scope="props">
              <button class="btn btn-outline-dark" @click="descargar(props.row,usuario_texto)" >
                <i class="fas fa-file-pdf"></i>&nbsp;&nbsp;<i class="fas fa-download"></i>
              </button>
            </template>

          </v-client-table>


        </div>
      </div>

      <div class="card" v-show="estado == 2">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> OC de gastos {{usuario_texto}}
          <button type="button" @click="estados()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <v-client-table :data="tableDataDos" :options="optionsdos" :columns="columnsdos">

            <template slot="cargo" slot-scope="props">
              $ {{(parseFloat(props.row.cargo)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}}
            </template>

            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" data-placement="top" title="Acciones" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <!-- <button type="button" @click="ver_facturas(props.row)" class="dropdown-item" >
                      <i class="fas fa-file-pdf"></i>&nbsp; Ver Facturas
                    </button>

                    <button type="button" @click="asociar_oc(props.row)" class="dropdown-item" >
                      <i class="fas fa-find"></i>&nbsp; Asociar OC
                    </button> -->

                    <button type="button" @click="subirxml(props.row)" class="dropdown-item" >
                      <i class="fas fa-file-xml"></i>&nbsp; Subir XML
                    </button>

                  </div>
                </div>
              </div>
            </template>

            <template slot="descargar" slot-scope="props">
              <button class="btn btn-outline-dark" @click="cargardetalle(props.row)" >
                <i class="fas fa-list"></i>&nbsp;
              </button>
            </template>

            <template slot="format" slot-scope="props">
              <button class="btn btn-outline-dark" @click="descargar(props.row,usuario_texto)" >
                <i class="fas fa-file-pdf"></i>&nbsp;&nbsp;<i class="fas fa-download"></i>
              </button>
            </template>

          </v-client-table>
        </div>
      </div>

      <div v-show="widgets.facturas">
        <facturas ref="facturas" @atras:facturas="cerrarfacturas()"></facturas>
      </div>

      <div v-show="widgets.partidas">
        <partidas ref="partidas" @partidas:click="maestro($event)"></partidas>
      </div>


      <!--Inicio del modal Mostrar Lote-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_aoc}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">

            <div>
              <!-- <vue-element-loading :active="isLoadingc"/> -->
              <div class="modal-header">
                <h4 class="modal-title">Asociar orden de compra</h4>
                <button type="button" class="close" @click="cerrarModalAOC()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="almacen_id">Folio</label>
                  <div class="col-md-9">
                    <input class="form-control" placeholder="Folio de la orden de compra" v-model="folio_oc_a" data-vv-as="folio">
                    <span class="text-danger">{{ errors.first('folio') }}</span>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="guardarFOC"><i class="fas fa-check"></i>&nbsp;Guardar</button>
                <button type="button" class="btn btn-outline-dark" @click="cerrarModalAOC()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar almacen-->

      <!--Inicio del modal Mostrar Lote-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_sxml}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">

            <div>
              <!-- <vue-element-loading :active="isLoadingc"/> -->
              <div class="modal-header">
                <h4 class="modal-title">Subir XML</h4>
                <button type="button" class="close" @click="cerrarModalXML()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="almacen_id">UUID</label>
                  <div class="col-md-9">
                    <input class="form-control" placeholder="UUID" v-model="uuid" data-vv-as="uuid">
                    <span class="text-danger">{{ errors.first('uuid') }}</span>
                  </div>
                </div>
                <div class="form-group row">

                  <template v-if="pdf != ''">

                    <label class="col-md-3 form-control-label">PDF</label>
                    <div class="col-md-2">
                      <button type="button" class="form-control" @click="pdf = ''">
                        <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                      </button>
                    </div>

                    <div class="col-md-2">
                      <button type="button" class="form-control" @click="descargarArch(pdf)">
                        <i class="fas fa-download"></i>&nbsp;Descargar
                      </button>
                    </div>

                  </template>
                  <template v-if="pdf === ''">
                    <label class="col-md-3 form-control-label" for="pdf">PDF</label>
                    <div class="col-md-6">
                      <input type="file" class="form-control" name="pdf"  @change="onChangeComprobantePDF($event)">
                    </div>
                  </template>
                </div>
                <!-- <input class="form-control" placeholder="PDF" v-model="pdf" data-vv-as="pdf"> -->
                <!-- <span class="text-danger">{{ errors.first('pdf') }}</span> -->
                <div class="form-group row">
                  <template v-if="xml != ''">

                    <label class="col-md-3 form-control-label">XML</label>
                    <div class="col-md-2">
                      <button type="button" class="form-control" @click="xml = ''">
                        <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                      </button>
                    </div>

                    <div class="col-md-2">
                      <button type="button" class="form-control" @click="descargarArch(xml)">
                        <i class="fas fa-download"></i>&nbsp;Descargar
                      </button>
                    </div>

                  </template>
                  <template v-if="xml === ''">
                    <label class="col-md-3 form-control-label" for="xml">XML</label>
                    <div class="col-md-6">
                      <input type="file" class="form-control" name="xml" accept=".xml"  @change="onChangeComprobanteUUID($event)">
                    </div>
                  </template>
                </div>

                <div class="form-group row">

                  <label class="col-md-3 form-control-label">Centro de costo</label>
                  <div class="col-md-6">
                    <select class="form-control" v-model="centro_costo_id" data-vv-name="Centro de costos">
                      <option v-for="t in centro_costo"  :key="t.id" :value="t.id">{{t.nombre}}-{{t.nombre_sub}}</option>
                    </select>
                    <span class="text-danger">{{ errors.first('Centro de costos') }}</span>
                  </div>
                </div>

                <hr>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="num_poliza"># Poliza</label>
                  <div class="col-md-4">
                    <input class="form-control" placeholder="# Poliza" v-model="num_poliza" data-vv-as="num_poliza">
                    <span class="text-danger">{{ errors.first('num_poliza') }}</span>
                  </div>
                </div>

                <div class="form-group row">



                  <template v-if="poliza != ''">

                    <label class="col-md-3 form-control-label">Poliza</label>
                    <div class="col-md-2">
                      <button type="button" class="form-control" @click="poliza = ''">
                        <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                      </button>
                    </div>

                    <div class="col-md-2">
                      <button type="button" class="form-control" @click="descargarArch(poliza)">
                        <i class="fas fa-download"></i>&nbsp;Descargar
                      </button>
                    </div>

                  </template>
                  <template v-if="poliza === ''">
                    <label class="col-md-3 form-control-label" for="pdf">Poliza</label>
                    <div class="col-md-6">
                      <input type="file" class="form-control" name="pdf"  @change="onChangeComprobantePoliza($event)">
                    </div>
                  </template>
                </div>

                <hr>
                <div class="form-group row">
                  <template v-if="pago != ''">
                    <label class="col-md-3 form-control-label">Pago</label>
                    <div class="col-md-2">
                      <button type="button" class="form-control" @click="pago = ''">
                        <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                      </button>
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="form-control" @click="descargarArch(pago)">
                        <i class="fas fa-download"></i>&nbsp;Descargar
                      </button>
                    </div>
                  </template>

                  <template v-if="pago === ''">
                    <label class="col-md-3 form-control-label">&nbsp;Pago</label>
                    <div class="col-md-6" >
                    <input type="file" name="adjunto" class="form-control" id="adjunto" ref="adjunto" @change="onChangeComprobantePago($event)">
                    <vue-editor id="editor"
                    @text-change="agregandoImg" v-model="editorContent"
                    :editorToolbar="customToolbar" >
                  </vue-editor>
                  </div>
                </template>


                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="guardarxml"><i class="fas fa-check"></i>&nbsp;Guardar</button>
                <button type="button" class="btn btn-outline-dark" @click="cerrarModalXML()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar almacen-->


    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Facturas = r => require.ensure([], () => r(require('./Facturas.vue')), 'compras');
const Partidas = r => require.ensure([], () => r(require('./Partidas.vue')), 'compras');
import { VueEditor } from "vue2-editor";


export default {
  data (){
    return {
      editorContent: "",
      customToolbar: [["bold", "italic", "underline"], [{ list: "ordered" }, { list: "bullet" }], ["image", "code-block"]],
      widgets : {
        facturas :false,
        partidas : false,
      },
      busqueda : {
        mes : '',
        anio : '',
        proacre : '',
        total : '',
      },
      tipo_listado  :'',
      uuid : '',
      pdf : '',
      poliza: '',
      num_poliza : '',
      xml : '',
      pago : '',

      centro_costo_id : '',
      data_dos : null,
      oc_gasto_id : 0,
      folio_oc_a : '',
      modal_aoc : 0,
      modal_sxml : 0,
      estado : 1,
      usuario_texto : '',
      empresas : [
        'Conserflow',
        'CSCT'],
        centro_costo : [],
        tableDataUno : [],
        columnsuno : ['id','orden_compra','total'],
        optionsuno: {
          headings: {
            'id': 'Acciones',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['orden_compra','total'],
          filterable: ['orden_compra','total'],
          filterByColumn: true,
          texts:config.texts
        },
        tableDataDos : [],
        columnsdos : ['id','descargar','folio','fecha','proveedor_acreedor','tipo','tipo_gasto','cargo','moneda','format'],
        optionsdos: {
          headings: {
            'id': 'Acciones',
            'descargar' : 'Partidas',
            'format' : 'Descargar',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['folio','fecha','proveedor_acreedor','tipo','tipo_gasto','cargo','moneda'],
          filterable: ['folio','fecha','proveedor_acreedor','tipo','tipo_gasto','cargo','moneda'],
          filterByColumn: true,
          texts:config.texts
        },
      }
    },
    components: {
      'facturas' : Facturas,
      'partidas' : Partidas,
      VueEditor
    },
    methods : {
      agregandoImg(file) {
        // console.log(file);
        if (file.ops[0].delete) {
          this.pago = '';
        }
        if (this.pago == '') {
          this.pago = file.ops[0].insert.image;
        }else {
          toastr.warning('Solo puede adjuntar una imagen');
        }

      },

      estados() {
        // alert('das');
        this.estado = 1;

      },

      getData(){
        axios.get(`/catalogos/centrocostos/`).then(response => {
          this.centro_costo = response.data;
        }).catch(error => {console.log(error)});
      },

      onChangeComprobantePago(e){
        this.pago = e.target.files[0];
      },

      onChangeComprobantePDF(e){
        this.pdf = e.target.files[0];
      },

      onChangeComprobantePoliza(e){
        this.poliza = e.target.files[0];
      },

      onChangeComprobanteUUID(e){
        this.xml = e.target.files[0];
      },

      verocs(data){
        this.usuario_texto = data;
      },

      verocsfechas(){
        axios.get(`get/oc/gastos/ocs/fechas/${this.usuario_texto}&${this.busqueda.mes}&${this.busqueda.anio}`).then(response => {
          this.tableDataDos = response.data;
        }).catch(error => {
          console.error(error);
        })
      },

      verocsproacre(){
        axios.get(`get/oc/gastos/ocs/proacre/${this.usuario_texto}&${this.busqueda.proacre}`).then(response => {
          this.tableDataDos = response.data;
        }).catch(error => {
          console.error(error);
        })
      },

      verocstotal(){
        axios.get(`get/oc/gastos/ocs/total/${this.usuario_texto}&${this.busqueda.total}`).then(response => {
          this.tableDataDos = response.data;
        }).catch(error => {
          console.error(error);
        })
      },


      vertipo(tipo){
        // console.log(tipo);
        switch (tipo) {
          case 'global':
          this.tipo_listado = 'global';
          axios.get(`get/oc/gastos/${this.usuario_texto}`).then(response => {
            this.tableDataUno = response.data;
          }).catch(err => {
            console.error(e);
          });

          break;

          case 'general':
          this.estado = 2;
          axios.get(`get/oc/gastos/general/${this.usuario_texto}`).then(response => {
            this.tableDataDos = response.data;
          }).catch(err => {
            console.error(e);
          });

          break;



          case 'fecha':
          this.tipo_listado = 'fecha';
          console.log('mes');
          break;

          case 'proacre':
          this.tipo_listado = 'proacre';
          console.log('proacre');

          break;

          case 'total':
          this.tipo_listado = 'total';
          console.log('total');

          break;
        }
      },

      vercompras(data){
        this.data_dos = data;
        this.estado = 2;
        axios.get(`get/oc/gastos/ocs/${this.usuario_texto}&${data.orden_compra}`).then(response => {
          this.tableDataDos = response.data;
        }).catch(error => {
          console.error(error);
        })
      },


      maestro(){
        // alert('de');
        this.estado = 1;
      },

      ver_facturas(data){
        this.widgets.facturas = true;
        this.estado = 0;
        var childfacturas = this.$refs.facturas;
        childfacturas.cargar(data, this.listaProvedores, this.UsoFactura);
      },

      cerrarfacturas(){
        this.widgets.facturas = false;
        this.estado = 2;
      },

      descargar(data,usuario_texto){
        // console.log(data);
        if (data.orden_compra_id != null) {
          window.open('descargar-compran/' + data.orden_compra_id, '_blank');
        }else {
          window.open('descargar-compra-gastos/' + data.id + '&' + usuario_texto, '_blank');
        }
      },

      asociar_oc(data){
        this.modal_aoc = 1;
        this.oc_gasto_id = data.id;
      },

      subirxml(data){
        this.modal_sxml = 1;
        this.oc_gasto_id = data.id;
        axios.get('/get/gastos/xml/datos/' + data.id).then(response => {
          // console.log();
          if (Object.keys(response.data).length != 0) {
          this.uuid = response.data.uuid;
          this.pdf = response.data.comprobante;
          this.xml = response.data.xml;
          this.centro_costo_id = response.data.catalogo_centro_costos_id;
          this.num_poliza = response.data.num_poliza;
          this.poliza = response.data.poliza;
          this.pago = response.data.pago_adj;
          }
        }).catch(e => {
          console.error(e);
        });
      },

      cerrarModalAOC(){
        this.modal_aoc = 0;
      },

      cerrarModalXML(){
        this.modal_sxml = 0;
      },

      guardarxml(nuevo = 1){
        if (this.uuid === '') {
          toastr.warning('Escriba el UUID');
        }else if (this.xml === '') {
          toastr.warning('Carge el archivo XML');
        }else if (this.centro_costo_id === '') {
          toastr.warning('Seleccione el centro de costos');
        }else{
          let formData = new FormData();
          formData.append('oc_id',this.oc_gasto_id);
          formData.append('uuid',this.uuid);
          formData.append('pdf',this.pdf);
          formData.append('xml',this.xml);
          formData.append('centro_costo',this.centro_costo_id);
          formData.append('num_poliza',this.num_poliza);
          formData.append('poliza',this.poliza);
          formData.append('pago',this.pago);
          formData.append('id',this.oc_gasto_id);

          axios({
            method : nuevo ? 'POST' : 'PUT',
            url :nuevo ? 'registrar/datos/gastos' : 'actualizar/datos/gastos',
            data : formData
          }).then(response => {
            // console.log(response);
            toastr.success('Correcto');
            this.modal_sxml = 0;
            this.uuid = '';
            this.pdf = '';
            this.xml = '';
            this.poliza = '';
            this.pago = '';
            this.centro_costos_id = '';
            this.num_poliza = '';
            this.editorContent = '';
          }).catch(e => {

            console.error(e);
          })
        }
      },

      guardarFOC(){
        if (this.folio_oc_a === '') {
          toastr.warning('Campo folio no puede ser vacio');
        }else {
          axios.post('guardar/folio/asociado',{
            oc_gasto_id : this.oc_gasto_id,
            folio : this.folio_oc_a,
          }).then(response => {
            this.modal_aoc = 0;
            if (response.request.response === '{}') {
              toastr.warning('No se encontro la OC, Revise Sintanxis');
            }else {
              toastr.success('Asociado Correctamente');
            }
            this.vercompras(this.data_dos);
          }).catch( e => {
            console.error(e);
          });
        }
      },

      cargardetalle(data = []){
        let me = this;
        me.estado = 3;
        me.widgets.partidas = true;
        var childpartidas = this.$refs.partidas;
        childpartidas.cargarpartidas(data);
      },

      maestro(data){
        // console.log(data);
        let me= this;
        me.estado = 2;
        me.widgets.partidas = false;
        me.isLoading = false;
        // me.getData(data);
      },

      descargarArch(archivo){

          let me=this;
          axios({
            url: 'descargarfacturareporte/' + archivo ,
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
            axios.get('eliminareportegastos/' + archivo)
            .then(response => {
            })
            .catch(function (error) {
              console.log(error);
            });
            //--fin del metodo borrar--//
          });

      },

    },
    mounted(){
      this.getData();
    }
  }

  </script>
