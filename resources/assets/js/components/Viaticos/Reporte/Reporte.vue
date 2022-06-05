<template>
  <div >

    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Reporte de gastos de
        {{datos == null ? '' : datos.solicitud.nombre_elabora}}  del proyecto <br>
        {{datos == null ? '' : datos.solicitud.nombre_proyecto}} con folio
        {{datos == null ? '' : datos.solicitud.folio}}
        <button type="button" class="btn btn-dark float-sm-right" @click="enviarReporteParcial()">
          <i class="fas fa-send">&nbsp;</i>Enviar Reporte
        </button>
        <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal('reporte','registrar')">
          <i class="fas fa-plus">&nbsp;</i>Nuevo
        </button>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-sm">
            <tr>
              <th width="25%" class="table-active">Total comprobado</th>
              <th width="25%" class="table-active">Fondo solicitado</th>
              <th width="25%" class="table-active">Fondo asignado</th>
              <th width="25%" class="table-active">Saldo Actual</th>
            </tr>
            <tr>
              <td style="text-align:right">$ {{datos == null ? '' :  new Intl.NumberFormat().format(datos.comprobacion.total)}}</td>
              <td style="text-align:right">$ {{ datos == null ? '' :  new Intl.NumberFormat().format(datos.detalle.total)}}</td>
              <td style="text-align:right">$ {{datos_pagos == null ? '' : new Intl.NumberFormat().format(datos_pagos.total)}}</td>
              <td style="text-align:right">$ {{datos_pagos == null ? '' : new Intl.NumberFormat().format(datos_pagos.total - datos.comprobacion.total)}}</td>
            </tr>
        </table>

        <v-client-table :data="reporteGastos" :options="options" :columns="columns" ref="reporteTable">
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp;
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" @click="abrirModal('reporte','actualizar',props.row)" class="dropdown-item" :disabled="props.row.condicion >= 1 && props.row.condicion < 3">
                    <i class="fas fa-eye"></i>Actualizar
                  </button>
                  <button type="button" @click="eliminar(props.row)" class="dropdown-item" :disabled="props.row.condicion >= 1 && props.row.condicion < 3">
                    <i class="fas fa-eye"></i>Eliminar
                  </button>
                </div>
              </div>
            </div>
          </template>
          <template slot="adjunto" slot-scope="props">
            <button type="button" class="form-control" @click="descargarfactura(props.row.adjunto)">
              <i class="fas fa-file-download"></i>&nbsp;
            </button>
          </template>
          <template slot="xml" slot-scope="props">
            <button type="button" class="form-control" @click="descargarfactura(props.row.xml)">
              <i class="fas fa-file-download"></i>&nbsp;
            </button>
          </template>
          <template slot="condicion" slot-scope="props">
            <template v-if="props.row.condicion == 0">
              <span class="btn btn-outline-info">Nuevo</span>
            </template>
            <template v-if="props.row.condicion == 1">
              <span class="btn btn-outline-warning">Enviado</span>
            </template>
            <template v-if="props.row.condicion == 2">
              <span class="btn btn-outline-info">Revisado</span>
            </template>
            <template v-if="props.row.condicion == 3">
              <span class="btn btn-outline-danger">Rechazado</span>
            </template>
          </template>
          <template slot="beneficiario" slot-scope="props">
            <template v-if="props.row.beneficiario == null">
              {{props.row.beneficiario_externo}}
            </template>
            <template v-if="props.row.beneficiario != null">
              {{props.row.beneficiario}}
            </template>
          </template>
        </v-client-table>
        <template slot="fecha" slot-scope="props">
          {{fecha(props.row.fecha)}}
        </template>

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
            <!-- {{reporte}} -->
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>&nbsp;Fecha</label>
                <input type="date" name="fecha" v-model="reporte.fecha" v-validate="'required'" class="form-control" data-vv-name="Fecha">
                <span class="text-danger"> {{errors.first('Fecha')}}</span>
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
              <div class="form-group col-md-2">
                <div class="form-check checkbox" >
                  <input type="checkbox" v-model="reporte.check">
                  <label class="form-check-label" >
                    XML
                  </label>
                </div>
              </div>
            </div>

            <template v-if="!reporte.check">

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Concepto</label>
                  <input type="text" name="concepto" v-model="reporte.concepto" class="form-control" data-vv-name="concepto">
                  <span class="text-danger"> {{errors.first('concepto')}}</span>
                </div>

                <div class="form-group col-md-6">
                  <label>&nbsp;Proveedor/Acreedor</label>
                  <input type="text" name="proveedor acreedor" v-model="reporte.proveedor_acreedor" class="form-control" data-vv-name="proveedor acreedor">
                  <span class="text-danger"> {{ errors.first('proveedor acreedor')}}</span>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Iva</label>
                  <input type="text" name="subtotal" v-validate="'decimal:2'"  v-model="reporte.iva" class="form-control" data-vv-name="iva">
                  <span class="text-danger"> {{ errors.first('iva')}}</span>
                </div>
                <div class="form-group col-md-6">
                  <label>&nbsp;No deducible</label>
                  <input type="text" name="total" v-validate="'decimal:2'" v-model="reporte.no_deducible" class="form-control" data-vv-name="no deducible">
                  <span class="text-danger"> {{ errors.first('no deducible')}}</span>
                </div>
              </div>

              <div class="form-row">
                <!-- <div class="form-group col-md-6">
                  <label>&nbsp;SubTotal</label>
                  <input type="text" name="subtotal" v-validate="'decimal:2'"  v-model="reporte.subtotal" class="form-control" data-vv-name="subtotal">
                  <span class="text-danger"> {{ errors.first('subtotal')}}</span>
                </div> -->
                <div class="form-group col-md-6">
                  <label>&nbsp;Total</label>
                  <input type="text" name="total" v-validate="'decimal:2'" v-model="reporte.total" class="form-control" data-vv-name="total">
                  <span class="text-danger"> {{ errors.first('total')}}</span>
                </div>
              </div>

            </template>


            <div class="form-row">

              <template v-if="reporte.adjunto != ''">
                <div class="form-group col-md-4">
                  <label>&nbsp;Adjunto</label>
                  <button type="button" class="form-control" @click="descargarfactura(reporte.adjunto)">
                    <i class="fas fa-file-download"></i>&nbsp;Descargar
                  </button>
                </div>
                <div class="form-group col-md-4">
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

            <template v-if="reporte.check">
              <div class="form-row" >
                <template v-if="reporte.xml != ''">
                  <div class="form-group col-md-4">
                    <label>&nbsp;XML</label>
                    <button type="button" class="form-control" @click="descargarfactura(reporte.xml)">
                      <i class="fas fa-file-download"></i>&nbsp;Descargar
                    </button>
                  </div>
                  <div class="form-group col-md-4">
                    <label>&nbsp;</label>
                    <button type="button" class="form-control" @click="actualizaX()">
                      <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                    </button>
                  </div>
                </template>
                <template v-if="reporte.xml == ''">
                  <div class="form-group col-md-4">
                    <label>&nbsp;XML</label>
                    <input type="file" name="xml" class="form-control" id="xml" ref="xml" accept=".xml" @change="subirXml()">
                    <span class="text-danger"> {{errors.first('xml')}}</span>
                  </div>
                </template>
              </div>
            </template>

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
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      empresa : '',
      ver_total : false,
      tipoAccion : 0,
      isLoading : false,
      tituloModal : '',
      datos : null,
      datos_pagos : null,
      modal : 0,
      valid : '',
      reporte : {
        check : true,
        fecha : '',
        proveedor_acreedor : '',
        concepto : '',
        total : '',
        subtotal : '',
        adjunto : '',
        id : '',
        metodo : '',
        pda : '',
        solicitud_viaticos_id : '',
        catalogo_conceptos_viaticos_id : '',
        beneficiario_id : '',
        folio_peaje : '',
        xml : '',
        iva : '',
        no_deducible : '',
      },
      cadena : '',
      variable : '',
      reporteGastos : [],
      catalogoConceptos :[],
      listaBeneficiarios : [],
      favor_empresa : '-',
      favor_empleado : '-',
      total_gral : '',
      folio_peaje : false,
      options : {
        headings: {
          'id': 'Acciones',
          'beneficiario' : 'Reporta',
          'foliosat' : 'Folio SAT',
          'facturainterna' : 'Factura Interna',
          'proveedor_acreedor' : 'Proveedor',
          'importediecisies' : 'Importe 16%',
          'importecero' : 'Importe 0%',
          'iva' : 'Iva',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['beneficiario','fecha','foliosat','facturainterna','proveedor_acreedor','concepto','importediecisies','importecero','iva','derechos','otros_impuestos','no_deducible','total','condicion'],
        filterable: ['beneficiario','fecha','foliosat','facturainterna','proveedor_acreedor','concepto','importediecisies','importecero','iva','derechos','otros_impuestos','no_deducible','total','condicion'],
        filterByColumn: true,
        texts:config.texts,
        listColumns: {
          condicion: [{
            id: 0,
            text: 'Nuevo'
          },
          {
            id: 1,
            text: 'Enviado'
          },
          {
            id: 2,
            text: 'Revisado'
          },
          {
            id: 3,
            text: 'Rechazado'
          }
        ]
      },
    },
    columns : ['id','beneficiario','proveedor_acreedor','concepto','fecha','foliosat','total','adjunto','xml','condicion'],
  }
},
computed : {



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





},
methods :{

  fecha(fecha){
    const meses = [
      "Enero", "Febrero", "Marzo",
      "Abril", "Mayo", "Junio", "Julio",
      "Agosto", "Septiembre", "Octubre",
      "Noviembre", "Diciembre"
    ];

    const date = new Date(fecha);
    const dia = date.getDate() + 1;
    const mes = date.getMonth();
    const ano = date.getFullYear();
    return `${dia} de ${meses[mes]} del ${ano}`;
  },

  abrirModal(modelo, accion, data = []){
    switch (modelo) {
      case "reporte":
      {
        switch (accion) {
          case "registrar":
          {
            Utilerias.resetObject(this.reporte);
            $("#adjunto").val('');
            this.reporte.metodo = 'Nuevo';
            this.modal = 1;
            this.tituloModal = "Registrar reporte de viaticos";
            this.tipoAccion = 1;
            break;
          }
          case "actualizar":
          {
            $("#adjunto").val('');
            Utilerias.resetObject(this.reporte);
            this.reporte.metodo = "Actualizar";
            this.modal = 1;
            this.tituloModal = "Actualizar reporte viaticos";
            this.tipoAccion = 2;
            this.reporte.fecha = data['fecha'];
            this.reporte.pda = data['pda'];
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
            this.reporte.solicitud_viaticos_id = data['solicitud_viaticos_id'] == null ? '' : data['solicitud_viaticos_id'];
            this.reporte.total = parseFloat(data['total']);
            this.reporte.beneficiario_id = data['beneficiario_id'];
            this.reporte.folio_peaje = data['folio_peaje'];
            this.reporte.catalogo_conceptos_viaticos_id = data['catalogo_conceptos_viaticos_id'];
            this.reporte.xml = data['xml'];
            if (this.reporte.xml != null) {
              this.reporte.check = true;
            }else {
              this.reporte.check = false;
            }
            // this.descargar();
          }
        }
      }
    }
  },

  cerrarModal()
  {
    this.modal =  0;
    this.reporte.adjunto = '';
    this.reporte.xml = '';
  },

  subirAdjunto(){
    this.reporte.adjunto = this.$refs.adjunto.files[0];
  },

  subirXml(){
    this.reporte.xml = this.$refs.xml.files[0];
  },

  descargarfactura(archivo){
    if (typeof(this.reporte.adjunto) === 'object') {
      toastr.warning('ATENCION','No se puede descargar un archivo que aun no a sido subido');
    }else {
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
    }
  },

  guardar(nuevo){
    // if(this.cadena === 'ABC'){
    this.$validator.validate().then(result => {
      if (result) {
        let formData = new FormData();
        formData.append('solicitud_viaticos_id', this.datos.solicitud.id);
        formData.append('metodo', this.reporte.metodo);
        formData.append('fecha', this.reporte.fecha);
        formData.append('adjunto', this.reporte.adjunto);
        formData.append('xml', this.reporte.xml);
        formData.append('empresa', this.empresa);
        formData.append('concepto', this.reporte.concepto);
        formData.append('proveedor_acreedor', this.reporte.proveedor_acreedor);
        formData.append('subtotal', this.reporte.subtotal);
        formData.append('total', this.reporte.total);
        formData.append('iva', this.reporte.iva);
        formData.append('no_deducible', this.reporte.no_deducible);
        formData.append('check', this.reporte.check);
        formData.append('catalogo_conceptos_viaticos_id',this.reporte.catalogo_conceptos_viaticos_id);
        axios.post('/reportegastosviaticos',formData).then(response => {
          if (response.data.estado == 0) {
            toastr.warning('No se puede agregar un XML ya agregado');
          }else if (response.data.estado == 1) {
            toastr.success(nuevo ? 'Reporte agregado correctamente' : 'Reporte actualizado correctamente', 'Correcto');
            let me = this;
            me.cerrarModal();
            me.setDatos(me.datos,me.empresa);
          }
        }).catch(error => {
          console.log(error);
        })
      }

    });
    // }

  },

  setDatos(data, empresa){
    this.empresa = empresa;
    this.favor_empresa = '-';
    this.favor_empleado = '-';
    this.total_gral = '';
    let me = this;
    me.datos = data;
    me.listaBeneficiarios = [];
    axios.get('/reportegastosviaticos/' + data.solicitud.id + '&' + empresa).then(response => {
      this.reporteGastos = response.data;

    }).catch(error => {
      console.error(error);
    });
    axios.get('/conceptosviaticos').then(response => {
      this.catalogoConceptos = response.data;
    }).catch(error => {
      console.error(error);
    });
    axios.get('/pagosviaticos/'+ data.solicitud.id).then(response => {
      this.datos_pagos = response.data;
    }).catch((err) => {console.error(err);});
    if (data.beneficiarios[0].empleado_beneficiario_id == 0) {
      me.listaBeneficiarios.push({id : data.beneficiarios[0].empleado_beneficiario_id, name : data.beneficiarios[0].beneficiario_externo});
    }else {
      data.beneficiarios.forEach((item, i) => {
        me.listaBeneficiarios.push({id:item.empleado_beneficiario_id, name:item.nombre_beneficiario});
      });
    }

  },

  eliminar(data)
  {
    let me = this;
    axios.get('/eliminareportegastosviaticos/' + data.solicitud_viaticos_id + '&' + data.pda + '&' + me.empresa).then(response => {
      this.setDatos(me.datos, me.empresa);
      this.$emit('recargar',this.datos);
    }).catch(error => {
      console.error(error);
    })
  },

  enviarReporteParcial(){
    Swal.fire({
      title: 'Esta seguro(a) de enviar reporte?',
      text: "Esta opción no se podrá desahacer!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si',
      cancelButtonText : 'No',
    }).then((result) => {
      if (result.value) {
        axios.get('/enviarreporteparcial/' + this.datos.solicitud.id + '&' + this.empresa).then(response => {
          this.setDatos(this.datos,this.empresa);
          this.$emit('recargar',this.datos);
          toastr.success('Reporte de gastos enviado ','Correcto');
        }).catch(error => {
          console.error(error);
        });
      }
    });
  },

  valido(edo){

  },

  habiltarpeaje(data){
    if (data.target.value == '2') {
      this.folio_peaje = true;
    }
    else {
      this.folio_peaje = false;
    }
  },

  actualizaT(){
    this.reporte.adjunto = '';
  },

  actualizaX(){
    this.reporte.xml = '';
  },





}
}
</script>
