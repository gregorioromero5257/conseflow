<template>
  <main class="main">
    <div class="container-fluid">
      <br>
      <div class="card" >
        <div class="card-header">
          <i class="fa fa-align-justify"></i>Viaticos y gastos menores
          <button type="button" v-show="detalle == 2" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-backward"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body" v-show="detalle == 1">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>&nbsp;Subir --</label>
              <input class="form-control" type="file" v-validate="'ext:xls,xlsx'" data-vv-as="Archivo"
              name="import_file_emp" @change="getArchivo" accept="*/*" id="archivo" >
              <span class="text-danger">{{ errors.first('import_file_emp') }}</span>
            </div>
          </div>
        </div>
        <div class="card-footer" v-show="detalle == 1">
          <div class="form-row">
            <div class="form-group col-md-4">
              <button @click="update" class="btn btn-primary" >Subir Archivo</button>
            </div>
          </div>

        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <div v-show="detalle == 1">

          <v-client-table :data="tableData" :options="options" :columns="columns" ref="Mytable" >
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" @click="registra(props.row)" class="dropdown-item" >
                      <i class="fas fa-save"></i>&nbsp;Agregar comprobación
                    </button>
                    <button type="button" @click="ver(props.row)" class="dropdown-item" >
                      <i class="fas fa-eye"></i>&nbsp;Ver comprobaciones
                    </button>
                  </div>
                </div>
              </div>
            </template>
          </v-client-table>

        </div>

        <div v-show="detalle == 2">
          <v-client-table :data="dataComprobado" :options="optionsComprobado" :columns="columnsComprobado" ref="Mytable" >
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" @click="actualiza(props.row)" class="dropdown-item" >
                      <i class="fas fa-edit"></i>&nbsp;Actualizar comprobación
                    </button>
                  </div>
                </div>
              </div>
            </template>
            <template slot="adjunto" slot-scope="props">
              <button type="button" class="form-control" @click="descargarfactura(props.row.adjunto)">
                <i class="fas fa-file-download"></i>&nbsp;Descargar
              </button>
            </template>
            <template slot="catalogo_conceptos_viaticos_id" slot-scope="props">
              <template v-if="props.row.catalogo_conceptos_viaticos_id == 1">
                COMBUSTIBLE
              </template>
              <template v-if="props.row.catalogo_conceptos_viaticos_id == 2">
                PEAJE
              </template>
              <template v-if="props.row.catalogo_conceptos_viaticos_id == 3">
                HOSPEDAJE
              </template>
              <template v-if="props.row.catalogo_conceptos_viaticos_id == 4">
                ALIMENTOS
              </template>
              <template v-if="props.row.catalogo_conceptos_viaticos_id == 5">
                OTROS
              </template>
            </template>
          </v-client-table>
        </div>

        </div>
      </div>

      <!-- Inicio del modal agregar/actualizar -->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" arial-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">

          <div class="modal-content">

            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span arial-hidden="true">X</span>
              </button>
            </div>
            <div class="modal-body">
              <vue-element-loading :active="isLoading"/>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Fecha</label>
                  <input type="date" name="fecha" v-model="reporte.fecha" v-validate="'required'" class="form-control" data-vv-name="Fecha">
                  <span class="text-danger"> {{errors.first('Fecha')}}</span>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Concepto</label>
                  <input type="text" v-validate="'required'" name="concepto" v-model="reporte.concepto" class="form-control" data-vv-name="comcepto">
                  <span class="text-danger"> {{errors.first('concepto')}}</span>
                </div>
                <div class="form-group col-md-6">
                  <label>&nbsp;Catalogo</label>
                  <select class="form-control"  v-validate="'required'" name="concepto" v-model="reporte.catalogo_conceptos_viaticos_id" @change="habiltarpeaje($event)" data-vv-name="concepto">
                    <option v-for="item in catalogoConceptos" :key="item.id" :value="item.id">{{item.nombre}}</option>
                  </select>
                  <span class="text-danger">{{ errors.first('concepto')}}</span>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Folio SAT</label>
                  <input type="text" name="folio SAT" v-model="reporte.foliosat" class="form-control" data-vv-name="folio SAT">
                  <span class="text-danger"> {{errors.first('folio SAT')}}</span>
                </div>
                <template v-if="folio_peaje">
                  <div class="form-group col-md-6">
                    <label>&nbsp;Folio Peaje</label>
                    <input type="text" name="folio_peaje" class="form-control" v-model="reporte.folio_peaje">
                  </div>
                </template>
                <div class="form-group col-md-6">
                  <label>&nbsp;Factura Interna</label>
                  <input type="text" name="factura interna" v-model="reporte.facturainterna" class="form-control" data-vv-name="factura interna">
                  <span class="text-danger"> {{errors.first('factura interna')}}</span>
                </div>
              </div>


              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Proveedor/Acreedor</label>
                  <input type="text" name="proveedor acreedor" v-validate="'required'" v-model="reporte.proveedor_acreedor" class="form-control" data-vv-name="proveedor acreedor">
                  <span class="text-danger"> {{ errors.first('proveedor acreedor')}}</span>
                </div>

              </div>


              <hr>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>&nbsp;Importe 16%
                  </label>
                  <span class="badge badge-dark" title="Importe antes de impuesto" href="#"><b>?</b></span>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span class="input-group-text">$</span>
                    </div>
                    <input type="text" v-validate="'decimal:2'" name="importe 16%" v-model="reporte.importediecisies" class="form-control" data-vv-name="importe 16%">
                  </div>
                  <span class="text-danger"> {{errors.first('importe 16%')}}</span>
                </div>
                <div class="form-group col-md-4">
                  <label>&nbsp;IVA Calculado</label>
                  <span class="badge badge-dark" title="IVA calculado del importe 16%" href="#"><b>?</b></span>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span class="input-group-text">$</span>
                    </div>
                    <input type="text" v-validate="'decimal:2'" name="iva" v-model="reporte.iva" class="form-control" data-vv-name="iva" >
                  </div>
                  <span class="text-danger"> {{errors.first('iva')}}</span>
                </div>
              </div>
              <hr>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>&nbsp;Importe 0%</label>
                  <span class="badge badge-dark" title="Importe sin inpuesto" href="#"><b>?</b></span>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span class="input-group-text">$</span>
                    </div>
                    <input type="text" v-validate="'decimal:2'" name="importe 0%" v-model="reporte.importecero" class="form-control" data-vv-name="importe 0%">
                  </div>
                  <span class="text-danger"> {{errors.first('importe 0%')}}</span>
                </div>
              </div>
              <hr>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>&nbsp;Derechos</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span class="input-group-text">$</span>
                    </div>
                    <input type="text" v-validate="'decimal:2'" name="derechos" v-model="reporte.derechos" class="form-control" data-vv-name="derechos">
                  </div>
                  <span class="text-danger"> {{errors.first('derechos')}}</span>
                </div>
                <div class="form-group col-md-4">
                  <label>&nbsp;Otros Impuestos</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span class="input-group-text">$</span>
                    </div>
                    <input type="text" v-validate="'decimal:2'" name="otros impuestos" v-model="reporte.otros_impuestos" class="form-control" data-vv-name="otros impuestos">
                  </div>
                  <span class="text-danger"> {{ errors.first('otros impuestos')}}</span>
                </div>
                <div class="form-group col-md-4">
                  <label>&nbsp;ND (no deducible)</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span class="input-group-text">$</span>
                    </div>
                    <input type="text" v-validate="'decimal:2'" name="no deducible" v-model="reporte.no_deducible" class="form-control" data-vv-name="no deducible">
                  </div>
                  <span class="text-danger"> {{errors.first('no deducible')}}</span>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>&nbsp;Total</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <span class="input-group-text">$</span>
                    </div>
                    <input type="text" v-validate="'decimal:2'" name="total" v-model="reporte.total" class="form-control" data-vv-name="total" readonly>
                  </div>
                  <span class="text-danger"> {{errors.first('total')}}</span>
                </div>

                <template v-if="reporte.adjunto != ''">
                  <div class="form-group col-md-2">
                    <label>&nbsp;</label>
                    <button type="button" class="form-control" @click="descargarfactura(reporte.adjunto)">
                      <i class="fas fa-file-download"></i>&nbsp;Descargar
                    </button>
                  </div>
                  <div class="form-group col-md-2">
                    <label>&nbsp;</label>
                    <button type="button" class="form-control" @click="actualizaT()">
                      <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                    </button>
                  </div>
                </template>
                <template v-if="reporte.adjunto == ''">
                  <div class="form-group col-md-4">
                    <label>&nbsp;Adjunto</label>
                    <input type="file" name="adjunto" v-validate="'required'" data-vv-name="adjunto" class="form-control" id="adjunto" ref="adjunto" @change="subirAdjunto()">
                    <span class="text-danger"> {{errors.first('adjunto')}}</span>
                  </div>
                </template>

              </div>

              <div v-show="ver_total">
                {{total}}
                {{iva}}
                {{diferencia}}
              </div>

            </div>
            <div class="modal-footer">
              <vue-element-loading :active="isLoading"/>
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
            </div>


          </div>

        </div>
      </div>
      <!-- Fin de modal  -->


    </div>
  </main>

</template>
<script>

import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      viaticos_gastos_menores : null,
      detalle : 1,
      ver_total : false,
      tipoAccion : 0,
      isLoading : false,
      tituloModal : '',
      datos : null,
      datos_pagos : null,
      modal : 0,
      valid : '',
      reporte : {
        fecha : '',
        foliosat : '',
        facturainterna : '',
        proveedor_acreedor : '',
        concepto : '',
        importediecisies : '',
        importecero : '',
        iva : '',
        derechos : '',
        otros_impuestos : '',
        no_deducible : '',
        total : '',
        adjunto : '',
        id : '',
        metodo : '',
        pda : '',
        solicitud_viaticos_id : '',
        catalogo_conceptos_viaticos_id : '',
        beneficiario_id : '',
        folio_peaje : '',
      },
      reporteGastos : [],
      catalogoConceptos :[],
      listaBeneficiarios : [],
      favor_empresa : '-',
      favor_empleado : '-',
      total_gral : '',
      folio_peaje : false,
      archivo : null,
      tableData : [],
      dataComprobado : [],
      columns : ['id','fecha','descripcion','cargo','proveedor_acredor','nombre_corto','tipo_n','nombre_centro'],
      columnsComprobado : ['id','fecha','foliosat','facturainterna','proveedor_acredor','concepto','importediecisies','importecero','iva','derechos','otros_impuestos','no_deducible','total','catalogo_conceptos_viaticos_id','folio_peaje','adjunto'],
      options: {
        headings: {
          id : 'Acciones',
          tipo_n : 'Tipo',
          nombre_corto : 'Proyecto',
          },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha','descripcion','cargo','proveedor_acredor','nombre_corto','tipo_n','nombre_centro'],
        filterable: ['fecha','descripcion','cargo','proveedor_acredor','nombre_corto','tipo_n','nombre_centro'],
        filterByColumn: true,
        texts:config.texts
      },
      optionsComprobado: {
        headings: {
          id : 'Acciones',
          foliosat : 'Folio SAT',
          facturainterna : 'Factura Interna',
          proveedor_acredor : 'Proveedor',
          concepto : 'Concepto',
          importediecisies : 'Importe antes de impuesto',
          importecero : 'Importe sin impuesto',
          iva : 'IVA',
          derechos : 'Derechos',
          otros_impuestos : 'Otros Impuestos',
          no_deducible : 'No deducible',
          total : 'Total',
          catalogo_conceptos_viaticos_id : 'Catalogo',
            folio_peaje : 'Folio Peaje',
            adjunto : 'Adjunto',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,

        sortable: ['fecha','foliosat','facturainterna','proveedor_acredor','concepto','importediecisies','importecero','iva','derechos','otros_impuestos','no_deducible','total','folio_peaje'],
        filterable: ['fecha','foliosat','facturainterna','proveedor_acredor','concepto','importediecisies','importecero','iva','derechos','otros_impuestos','no_deducible','total','folio_peaje'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  computed : {
    total(){
      var importe_d = this.reporte.importediecisies == null ? 0 :
      this.reporte.importediecisies == '' ? 0 : parseFloat(this.reporte.importediecisies);
      var importe_c = this.reporte.importecero == null ? 0 :
      this.reporte.importecero == '' ? 0 : parseFloat(this.reporte.importecero);
      var iva = this.reporte.iva == null ? 0 :
      this.reporte.iva == '' ? 0 : parseFloat(this.reporte.iva);
      var derechos = this.reporte.derechos == null ? 0 :
      this.reporte.derechos == '' ? 0 : parseFloat(this.reporte.derechos);
      var otros_impuestos = this.reporte.otros_impuestos == null ? 0 :
      this.reporte.otros_impuestos == '' ? 0 : parseFloat(this.reporte.otros_impuestos);
      var no_deducible = this.reporte.no_deducible == null ? 0 :
      this.reporte.no_deducible == '' ? 0 : parseFloat(this.reporte.no_deducible);
      return this.reporte.total=(parseFloat(importe_d + importe_c + iva + derechos + otros_impuestos + no_deducible)).toFixed(2);
    },

    iva(){
      var importe_d = this.reporte.importediecisies == '' ? 0 : parseFloat(this.reporte.importediecisies);
      return this.reporte.iva = (parseFloat(importe_d * 0.16)).toFixed(2);
    },

    total_importediesiseis()
    {
      return this.reporteGastos.reduce((sum, item) => {
        return  (item.condicion != '2') ? sum : (sum + parseFloat(item.importediecisies));
      }, 0)
    },

    total_importecero()
    {
      return this.reporteGastos.reduce((sum, item) => {
        return (item.condicion != '2') ? sum :(sum + parseFloat(item.importecero));
      }, 0)
    },

    total_iva()
    {
      return this.reporteGastos.reduce((sum, item) => {
        return (item.condicion != '2') ? sum :(sum + parseFloat(item.iva))
      }, 0)
    },

    total_derechos()
    {
      return this.reporteGastos.reduce((sum, item) => {
        return (item.condicion != '2') ? sum :(sum + parseFloat(item.derechos))
      }, 0)
    },

    total_otros_impuestos()
    {
      return this.reporteGastos.reduce((sum, item) => {
        return (item.condicion != '2') ? sum :(sum + parseFloat(item.otros_impuestos))
      }, 0)
    },

    total_no_deducible()
    {
      return this.reporteGastos.reduce((sum, item) => {
        return (item.condicion != '2') ? sum :(sum + parseFloat(item.no_deducible))
      }, 0)
    },

    total_total()
    {
      return this.reporteGastos.reduce((sum, item) => {
        return this.total_gral = (item.condicion != '2') ? sum : (sum + parseFloat(item.total))
      }, 0)
    },

    diferencia() {
      var total= 0;
      total = this.datos_pagos == null ? 0 : parseFloat(this.datos_pagos.total);
      if (parseFloat(this.total_gral) > total) {
        return this.favor_empleado = (parseFloat(this.total_gral) - total).toFixed(2);
      }else {
        if (parseFloat(this.total_gral) < total) {
          return this.favor_empresa = (total - parseFloat(this.total_gral)).toFixed(2);
        }
      }
    }
  },
  methods : {
    getData(){
      axios.get('/registroviaticosgastosmenores/ver').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('/conceptosviaticos').then(response => {
        this.catalogoConceptos = response.data;
      }).catch(error => {
        console.error(error);
      });



    },

    cerrarModal()
    {
      this.modal =  0;
      this.valid = 0;
      this.reporte.adjunto = '';
    },
    getArchivo(event){
      //Asignamos el archivo a  nuestra data
      this.archivo = event.target.files[0];
      // console.log(event);
    },

    registra(data){
      Utilerias.resetObject(this.reporte);
      $("#adjunto").val('');
      this.reporte.metodo = 'Nuevo';
      this.reporte.id =data.id;
      this.modal = 1;
      this.tituloModal = "Registrar reporte de viaticos";
      this.tipoAccion = 1;
    },

    update(){

      this.$validator.validate().then(result => {
        if (result) {
          //Creamos el formData
          var data = new  FormData();
          //Añadimos la imagen seleccionada
          data.append('import_file', this.archivo);
          //Añadimos el método PUT dentro del formData
          // Como lo hacíamos desde un formulario simple _(no ajax)_
          data.append('_method', 'PUT');
          //Enviamos la petición
          axios.post('/registroviaticosgastosmenores/upload',data)
          .then(response => {
            this.getData();
            this.archivo = null;
            if (response.status == 200){
              swal(
                'agregadas',
                'correcto!!!'
              );
              $('#archivo').val('');
            }
            else
            swal(
              'Articulos',
              'Ocurrio un error.',
              'error'
            );
          })
          .catch(function (error) {
            this.archivo = null;
            console.log(error);
            swal(
              'Articulos',
              'Ocurrio un error al leer el archivo.',
              'error'
            );
          });
        }
      })
    },

    habiltarpeaje(data){
      if (data.target.value == '2') {
        this.folio_peaje = true;
      }
      else {
        this.folio_peaje = false;
      }
    },

    subirAdjunto(){
      this.reporte.adjunto = this.$refs.adjunto.files[0];
    },

    guardar(nuevo){

        this.$validator.validate().then(result => {
          if (result) {
            let formData = new FormData();
            formData.append('viaticos_gastos_menores_id', this.reporte.id);
            formData.append('metodo', this.reporte.metodo);
            formData.append('fecha', this.reporte.fecha);
            formData.append('foliosat', this.reporte.foliosat);
            formData.append('facturainterna', this.reporte.facturainterna);
            formData.append('proveedor_acreedor', this.reporte.proveedor_acreedor);
            formData.append('concepto', this.reporte.concepto);
            formData.append('importediecisies', this.reporte.importediecisies);
            formData.append('importecero', this.reporte.importecero);
            formData.append('iva', this.reporte.iva);
            formData.append('derechos', this.reporte.derechos);
            formData.append('otros_impuestos', this.reporte.otros_impuestos);
            formData.append('no_deducible', this.reporte.no_deducible);
            formData.append('total', this.reporte.total);
            formData.append('adjunto', this.reporte.adjunto);
            formData.append('pda', this.reporte.pda);
            formData.append('catalogo_conceptos_viaticos_id', this.reporte.catalogo_conceptos_viaticos_id);
            formData.append('folio_peaje', this.reporte.folio_peaje);

            axios.post('/reportegastosmenoresviaticos',formData).then(response => {
              if (response.data.status) {
                toastr.success(nuevo ? 'Reporte agregado correctamente' : 'Reporte actualizado correctamente', 'Correcto');
                this.cerrarModal();
                let me = this;
                // this.setDatos(me.datos);
                if (nuevo) {
                  me.getData();
                }else {
                  me.ver(me.viaticos_gastos_menores);
                }
              }else {
                swal({
                  type: 'error',
                  html: response.data.errors.join('<br>'),
                });
              }
            }).catch(error => {
              console.log(error);
            })
          }

        });


    },


    ver(data) {
      this.viaticos_gastos_menores= data;
      this.detalle = 2;
       axios.get(`ver/comprobaciones/gastosmenoresviaticos/${data.id}`).then(response => {
         this.dataComprobado = response.data;
       }).catch(e => {
         console.error(e);
       })
    },

    maestro(){
      this.detalle = 1;
    },

    actualiza(data){
      $("#adjunto").val('');
      Utilerias.resetObject(this.reporte);
      this.reporte.metodo = "Actualizar";
      this.modal = 1;
      this.tituloModal = "Actualizar reporte viaticos";
      this.tipoAccion = 2;
      this.reporte.id =data.id;
      this.reporte.fecha = data['fecha'];
      this.reporte.adjunto =data['adjunto'];
      this.reporte.concepto = data['concepto'];
      this.reporte.derechos = data['derechos'] == null ? '' : data['derechos'];
      this.reporte.facturainterna = data['facturainterna'] == null ? '' : data['facturainterna'];
      this.reporte.foliosat = data['foliosat'] == null ? '' : data['foliosat'];
      this.reporte.importecero = data['importecero'] == null ? '' : data['importecero'];
      this.reporte.importediecisies = data['importediecisies'] == null ? '' : data['importediecisies'];
      this.reporte.iva = data['iva'] == null ? '' :data['iva'];
      this.reporte.no_deducible = data['no_deducible'] == null ? '' : data['no_deducible'];
      this.reporte.otros_impuestos = data['otros_impuestos'] == null ? '' : data['otros_impuestos'];
      this.reporte.proveedor_acreedor = data['proveedor_acreedor'] == null ? '' : data['proveedor_acreedor'];
      this.reporte.total = parseFloat(data['total']);
      this.reporte.folio_peaje = data['folio_peaje'];
      this.reporte.catalogo_conceptos_viaticos_id = data['catalogo_conceptos_viaticos_id'];
    },

    actualizaT(){
      this.reporte.adjunto = '';
    },

    descargarfactura(archivo){
      if (typeof(this.reporte.adjunto) === 'object') {
        toastr.warning('ATENCION','No se puede descargar un archivo que aun no a sido subido');
      }else {
        let me=this;
        axios({
            url: 'descargareporte/gastosmenores/' + archivo ,
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
            axios.get('eliminareporte/gastosmenores/' + archivo)
            .then(response => {
            })
            .catch(function (error) {
                    console.log(error);
            });
            //--fin del metodo borrar--//
        });
      }
    },



  },
  mounted(){
    this.getData();
  }
}
</script>
