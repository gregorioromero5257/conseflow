<template >
  <div >
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Revisión de pagos del viatico : {{datos_uno == null ? '' :datos_uno.solicitud.folio}}
        <!-- <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right" >
          <i class="fas fa-arrow-left"></i>&nbsp;Atras
        </button> -->
      </div>
      <div class="card-body">
        <!-- {{datos_uno}} -->
        <!-- <table class="table table-bordered table-sm">
            <tr>
              <th width="25%" class="table-active">Total comprobado</th>
              <th width="25%" class="table-active">Fondo solicitado</th>
              <th width="25%" class="table-active">Fondo asignado</th>
              <th width="25%" class="table-active">Saldo Actual</th>
            </tr>
            <tr>
              <td style="text-align:right">$ {{new Intl.NumberFormat().format(total_total)}}</td>
              <td style="text-align:right">$ {{ datos == null ? '' :  new Intl.NumberFormat().format(datos.detalle.total)}}</td>
              <td style="text-align:right">$ {{datos_pagos == null ? '' : new Intl.NumberFormat().format(datos_pagos.total)}}</td>
              <td style="text-align:right">$ {{datos_pagos == null ? '' : new Intl.NumberFormat().format(datos_pagos.total - total_total)}}</td>
            </tr>
        </table> -->
        <v-client-table :data="data" :options="options" :columns="columns">
          <template slot="pda" slot-scope="props" >
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" @click="abrirModal('viaticos','actualizar',props.row)"
                  class="dropdown-item" >
                  <i class="fas fa-eye"></i>&nbsp; Revisar
                </button>
                <button type="button" @click="rechazar(props.row,3)"
                class="dropdown-item" :disabled="props.row.condicion > 1">
                <i class="fas fa-eye"></i>&nbsp; Rechazar
              </button>
              </div>
            </div>
          </div>
        </template>
          <template slot="condicion" slot-scope="props" >
        <template v-if="props.row.condicion == 0">
          <span class="btn btn-outline-info">Nuevo</span>
        </template>
        <template v-if="props.row.condicion == 1">
          <span class="btn btn-outline-warning">Por revisar</span>
        </template>
        <template v-if="props.row.condicion == 2">
          <span class="btn btn-outline-info">Revisado</span>
        </template>
        <template v-if="props.row.condicion == 3">
          <span class="btn btn-outline-danger">Rechazado</span>
        </template>
      </template>

      </v-client-table>


    </div>
  </div>

  <!--Inicio del modal agregar/actualizar-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dark modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" v-text="tituloModal"></h4>
          <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Fecha</label><br>
              <input class="form-control" type="text" :value="datos == null ? '' :datos['fecha']" readonly>
            </div>
            <div class="form-group col-md-4">
              <label>Folio SAT</label><br>
              <input class="form-control" type="text" :value="datos == null ? '' :datos['foliosat']" readonly>
            </div>
            <template v-if="datos == null ? '' :datos['catalogo_conceptos_viaticos_id'] == 2 ">
              <div class="form-group col-md-6">
                <label>&nbsp;Folio Peaje</label>
                <input type="text" name="folio_peaje" class="form-control" :value="datos == null ? '' :datos['folio_peaje']" readonly>
              </div>
            </template>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Factura Interna</label><br>
              <input class="form-control" type="text" :value="datos == null ? '' :datos['facturainterna']" readonly>
            </div>
            <div class="form-group col-md-4">
              <label>Proveedor</label><br>
              <input class="form-control" type="text" :value="datos == null ? '' :datos['proveedor_acreedor']" readonly>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Concepto</label><br>
              <input class="form-control" type="text" :value="datos == null ? '' :datos['concepto']" readonly>
            </div>
            <div class="form-group col-md-4">
              <label>Total</label><br>
              <input class="form-control" type="text" :value="datos == null ? '' :datos['total']" readonly>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Gastos comprobados deducibles</label>
              <input type="text" name="gastos comprobados deducibles" v-model="viaticos.gastoscd" class="form-control"
               v-validate="'required'" data-vv-name="gastos comprobados deducibles" placeholder="Gastos comprobados deducibles">
              <span class="text-danger">{{errors.first('gastos comprobados deducibles')}}</span>
            </div>
            <div class="form-group col-md-6">
              <label>Gastos comprobados no deducible</label>
              <input type="text" name="gastos comprobados no deducibles" v-model="viaticos.gastoscnd" class="form-control"
              v-validate="'required'" data-vv-name="gastos comprobados no deducibles" placeholder="Gastos comprobados no deducibles">
              <span class="text-danger">{{errors.first('gastos comprobados no deducibles')}}</span>
            </div>
          </div>
          <hr>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select class="form-control" v-model="viaticos.beneficiario_id" >
                <option v-for="item in listaBeneficiarios" :key="item.id" :value="item.id">{{item.name}}</option>
              </select>
            </div>
          </div>
          <hr>
          <div class="form-row">
            <div class="form-group col-md-2">
              <label>Factura :</label>
              <br>

              <button type="button" name="button" @click="descargarfactura(datos.adjunto)" class="form-control"><i class="fas fa-download"></i>Descargar</button>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label>
              <br>
              <button type="button" name="button" @click="verimg()" class="form-control"><i class="fas fa-eye"></i>Ver</button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
          <!-- <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button> -->
          <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="validacion(); guardar(1);"><i class="fas fa-save"></i>&nbsp;Guardar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal-->

  <!--Inicio del modal agregar/actualizar-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalver}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dark modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Detalle del comprobate de pago</h4>
          <button type="button" class="close" @click="cerrarModalVer()" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <div style="text-align: center;">
              <iframe :src="this.nombre_archivo" width="500" height="500" frameborder="0"></iframe>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" @click="cerrarModalVer()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal-->
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
      favor_empresa : '-',
      favor_empleado : '-',
      total_gral : '',
      modal : 0,
      modalver : 0,
      datos : null,
      datos_uno : null,
      revisados : 0,
      tituloModal : '',
      validar_guardar : '',
      listaBeneficiarios : [],
      viaticos : {
        total: '',
        gastoscd : '',
        gastoscnd : '',
        beneficiario_id : '',
      },
      titulo: '',
      nombre_archivo: '',
      tipoAccion : 0,
      data :[],
      options : {
        headings: {
          'pda': 'Acciones',
          'beneficiario' : 'Reporta',
          'foliosat': 'Folio SAT',
          'facturainterna': 'Factura interna',
          'proveedor_acreedor': 'Proveedor/Acreedor',
          'importediecisies' : 'Importe 16%',
          'importecero': 'Importe 0%',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['beneficiario','fecha','foliosat','facturainterna','proveedor_acreedor','concepto','importediecisies','importecero','iva','derechos','otros_impuestos','no_deducible','total','condicion'],
        filterable: ['beneficiario','fecha','foliosat','facturainterna','proveedor_acreedor','concepto','importediecisies','importecero','iva','derechos','otros_impuestos','no_deducible','total','condicion'],
        filterByColumn: true,
        texts:config.texts,
        listColumns : {
          'condicion' : [{
            id : 0,
            text : 'Nuevo'
          },
          {
            id : 1,
            text : 'Por revisar'
          },
          {
            id : 2,
            text :'Revisado'
          },
          {
            id : 3,
            text : 'Rechazado'
          }]
        }
      },
      columns : ['pda','beneficiario','fecha','foliosat','facturainterna','proveedor_acreedor','concepto','importediecisies',
      'importecero','iva','derechos','otros_impuestos','no_deducible','total','condicion'],
    }
  },
  computed : {

  },
  methods : {
    getData(data, empresa){
      this.empresa = empresa;
      this.listaBeneficiarios = [];
      this.datos_uno = data;
      axios.get('/reportepagosenviados/'+data.solicitud.id + '&' + empresa).then(response => {
        this.data = response.data;
      }).catch(error => {
        console.error(error);
      })
      data.beneficiarios.forEach((item, i) => {
        this.listaBeneficiarios.push({id:item.empleado_beneficiario_id, name:item.nombre_beneficiario});
      });
    },

    maestro()
    {
      this.$emit('reporterevision:maestro');
    },

    abrirModal(modelo,accion,data = []){
     this.revisados = data['condicion'];
      switch(modelo){
        case "viaticos":
        {
          switch(accion){
            case 'registrar':
            {
              Utilerias.resetObject(this.viaticos);
              this.modal= 1;
              this.tituloModal = 'Registrar Viaticos';
              this.tipoAccion=1;
              break;
            }
            case 'actualizar' :
            {
              this.obtenerfactura(data['solicitud_viaticos_id'],data['pda'],data['adjunto']);
              Utilerias.resetObject(this.viaticos);
              this.datos = data;
              this.modal = 1;
              this.tipoAccion = 2;
              this.tituloModal = 'Revisar reporte';
              this.viaticos.total = data['total'];
              this.viaticos.gastoscd = data['gcd'];
              this.viaticos.gastoscnd = data['gcnd'];
              this.viaticos.beneficiario_id = data['beneficiario_id'];
              break;
            }
          }
        }
      }
    },

    obtenerfactura(solicitud_id,pda,archivo){
      var method = 1;
      axios.get('/obtenerfacturareporte/'+ method + '&' + solicitud_id  + '&' + pda).then(response => {
      }).catch(error => {
          console.error(error);
      });
      this.nombre_archivo = 'FilesFTP/'+archivo;
    },

    cerrarModal(){
      this.modal = 0;
      this.revisados = 0;
    },

    cerrarModalVer(){
      this.modalver = 0;
      var method = 2;
      let me = this;
      axios.get('/obtenerfacturareporte/'+ method + '&' + me.datos.solicitud_viaticos_id  + '&' + me.datos.pda).then(response => {
      }).catch(error => {
          console.error(error);
      });
    },

    validacion(){
      var total = this.datos['total'];
      var suma_reportado = (parseFloat(this.viaticos.gastoscd)) + (parseFloat(this.viaticos.gastoscnd));
      if (parseFloat(suma_reportado) > parseFloat(total) || parseFloat(suma_reportado) < parseFloat(total)) {
        this.validar_guardar = 0;
      }
      else if (parseFloat(suma_reportado) == parseFloat(total)) {
        this.validar_guardar = 1;
      }
    },

    guardar(edo){
      if (this.validar_guardar === 1) {
      axios.post('/viaticos',{
        'solicitud_viaticos_id' : this.datos['solicitud_viaticos_id'],
        'gastos_comprobados_deducibles' : this.viaticos.gastoscd,
        'gastos_comprobados_no_deducibles' : this.viaticos.gastoscnd,
        'pda' : this.datos['pda'],
        'beneficiario_id' : this.viaticos.beneficiario_id,
      }).then(response => {
        this.cerrarModal();
        this.$emit('recargar', this.datos_uno);
        this.getData(this.datos_uno, this.empresa);
        toastr.success('Reporte registrado correctamente');
        Utilerias.resetObject(this.viaticos);

      }).catch(error => {
        console.error(error);
      });
    }else {
      toastr.warning('Se debe registrar todo lo reportado !!!','ATENCIÓN')
    }
    },

    rechazar(data, edo){
      axios.post('/rechazareporte',{
        'solicitud_viaticos_id' : data['solicitud_viaticos_id'],
        'pda' : data['pda'],
        'edo' : edo,
      }).then(response => {
        // this.getData(this.datos_uno);
        this.$emit('recargar', this.datos_uno);
        this.getData(this.datos_uno, this.empresa);
        toastr.warning('Reporte rechazado','ATENCION');
        Utilerias.resetObject(this.viaticos);

      }).catch(error => {
        console.error(error);
      })
    },

    descargarfactura(archivo){
      Utilerias.descargarArc('/descargarfacturareporte/','/eliminarfacturareporte/',archivo);

    },

    verimg(){
        this.modalver = 1;
    }

  }
}
</script>
