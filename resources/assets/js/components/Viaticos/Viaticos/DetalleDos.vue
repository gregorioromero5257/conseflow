<template>
  <div>
    <!--  -->
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Pagos del viatico : {{viatico == null ? '' :viatico.solicitud.folio}}
        <!-- <button type="button" @click="abrirModal('viaticos','registrar')" class="btn btn-dark float-sm-right" >
          <i class="fas fa-plus"></i>&nbsp;Registrar Pagos
        </button> -->
      </div>
      <div class="card-body">
        <!-- {{viatico}}ddd -->
        <v-client-table :data="data" :options="options" :columns="columns" ref="Mytable">
          <!-- <template slot="id" slot-scope="props" >
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" @click="abrirModal('viaticos','actualizar',props.row)" class="dropdown-item" v-show="estado_vista == 0">
                  <i class="fas fa-eye"></i>&nbsp; Actualizar
                </button>
                <button type="button" @click="abrirModal('viaticos','actualizar',props.row)" class="dropdown-item" v-show="estado_vista == 1">
                <i class="fas fa-eye"></i>&nbsp; Ver detalle
              </button>
              </div>
            </div>
          </div>
        </template> -->
        <template slot="descargar" slot-scope="props">
          <button type="button" @click="descargarfactura(props.row.adjunto)" class="btn btn-outline-dark" >
            <i class="fas fa-download"></i>&nbsp; Descargar
          </button>
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
          <!-- {{empresa}}gg -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label> Fecha de pago</label>
              <input type="date" class="form-control" data-vv-name="fecha" v-model="fecha_pago" :disabled="estado_vista == 1"><!--v-validate="'required'"-->
              <span class="text-danger">{{ errors.first('fecha') }}</span>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label> Importe TE requerido</label>
              <input type="text"  :value="viatico == null ? '' :viatico.detalle.transferencia - total_te" class="form-control" readonly>
            </div>
            <div class="form-group col-md-6">
              <label> Importe TE </label>
              <input type="text" required="'decimal:2'" name="importe_tea" data-vv-name="importe trasnferencia" v-model="importe_tea" :disabled="estado_vista == 1" class="form-control">
              <span>{{errors.first('importe transferencia')}}</span>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label> Importe efectivo requerido</label>
              <input type="text" :value="viatico == null ? '' :viatico.detalle.efectivo - total_e" class="form-control" readonly>
            </div>

            <div class="form-group col-md-6">
              <label> Importe efectivo </label>
              <input type="text" required="'decimal:2'" name="importe_ea" data-vv-name="importe efectivo" v-model="importe_ea" :disabled="estado_vista == 1" class="form-control">
              <span>{{errors.first('importe efectivo')}}</span>
            </div>
          </div>
          <hr>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label> Banco</label>
              <input type="text" v-model="banco" class="form-control">
            </div>
          </div>
          <!-- <hr> -->
          <!-- <div class="form-row">
          <template v-if="estado_vista == 0">
            <div class="form-group col-md-6">
              <label>Beneficiario</label>
              <select class="form-control" v-model="beneficiario_id">
                <option v-for="item in listaBeneficiarios" :key="item.id" :value="item.id">{{item.name}}</option>
              </select>
            </div>
          </template>
          </div> -->
          <hr>
          <!-- Adjunto Pagos -->
          <div class="form-row">


          <div class="form-group col-md-6" v-if="tipoAccion == 1">
            <label>&nbsp;Comprobante</label>
            <input type="file" name="adjunto" class="form-control" id="adjunto" ref="adjunto" @change="subirAdjunto()">
            <vue-editor id="editor"
            @text-change="agregandoImg" v-model="editorContent"
            :editorToolbar="customToolbar" >
          </vue-editor>
          </div>
          <div class="form-group col-md-6" v-if="tipoAccion == 2 && (adjunto == '' || (detalle.adjunto != adjunto))">
            <label>&nbsp;Comprobante</label>
            <div >
              <vue-editor id="editor"
              @text-change="agregandoImg" v-model="editorContent"
              :editorToolbar="customToolbar" >
            </vue-editor>
              <input type="file" name="adjunto" class="form-control" id="adjunto" ref="adjunto" @change="subirAdjunto()">
            </div>
          </div>
          <div class="form-group col-md-2" v-if="tipoAccion == 2">
            <div v-if="adjunto != '' && (detalle.adjunto == adjunto)">
              <label>&nbsp;Comprobante</label>
              <button type="button" name="button" @click="descargarfactura(detalle.adjunto)" class="form-control"><i class="fas fa-download"></i>Descargar</button>
            </div>
          </div>
          <div class="form-group col-md-2" v-if="tipoAccion == 2">
            <div v-if="adjunto != '' && (detalle.adjunto == adjunto)">
              <label>&nbsp;</label>
              <button type="button" name="button" @click="actualizarfactura(detalle.adjunto)" class="form-control"><i class="fas fa-retweet"></i>Actualizar</button>
            </div>
          </div>
        </div>
        <!-- Adjunto Pagos -->
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
          <button type="button" v-if="tipoAccion==1" v-show="estado_vista == 0" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
          <button type="button" v-if="tipoAccion==2" v-show="estado_vista == 0" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
import { VueEditor } from "vue2-editor";

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      editorContent: "",
      customToolbar: [["bold", "italic", "underline"], [{ list: "ordered" }, { list: "bullet" }], ["image", "code-block"]],
      empresa : '',
      columns : ['fecha_pago','importe_te','importe_efectivo','beneficiario','descargar'],
      data : [],
      detalle : null,
      listaBeneficiarios : [],
      options : {
        headings: {
          'id': 'Acciones',
          'fecha_pago': 'Fecha de pago',
          'importe_te': 'Importe TE',
          'importe_efectivo': 'Importe efectivo',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha_pago','importe_te','importe_efectivo','beneficiario'],
        filterable: ['fecha_pago','importe_te','importe_efectivo','beneficiario'],
        filterByColumn: true,
        texts:config.texts
      },
      modal : 0,
      viatico : null,
      detalle_viatico : null,
      id : '',
      solicitud_viaticos_id : '',
      fecha_pago : '',
      importe_TE : '',
      importe_efectivo : '',
      importe_ea : '',
      importe_tea : '',
      tituloModal : '',
      tipoAccion : 0,
      adjunto : '',
      pda : '',
      estado_vista : '',
      beneficiario_id : '',
      banco : '',
    }
  },
  computed : {
    total_te () {
      return this.data.reduce((sum, item) => {
        return (sum + parseFloat(item.importe_te))
      }, 0)
    },
    total_e () {
      return this.data.reduce((sum, item) => {
        return (sum + parseFloat(item.importe_efectivo))
      }, 0)
    }
  },
  components: {
    VueEditor
  },
  methods : {
    agregandoImg(file) {
      if (file.ops[0].delete) {
        this.adjunto = '';
      }
      if (this.adjunto == '') {
        this.adjunto = file.ops[0].insert.image;
      }else {
        toastr.warning('Solo puede adjuntar una imagen');
      }

    },

    getData(data, edo, empresa){
      this.empresa = empresa;
      this.estado_vista = edo;
      this.viatico = data;
      this.detalle_viatico = data.detalle;
      this.listaBeneficiarios = [];
      axios.get('/partidaviaticospagos/' + data.solicitud.id + '&' + empresa).then(response => {
        this.data = response.data;
      }).catch(error => {
        console.error(error);
      });
      if (edo == 0) {
        data.beneficiarios.forEach((item, i) => {
          this.listaBeneficiarios.push({id:item.empleado_beneficiario_id, name:item.nombre_beneficiario});
        });
      }
    },

    abrirModal(modelo, accion , data = []){
      switch(modelo){
        case "viaticos":
        {
          switch(accion){
            case 'registrar':
            {
              $('#adjunto').val('');
              let me = this;
              me.limpiar_partida();
              this.modal= 1;
              this.tituloModal = 'Registrar pagos';
              this.tipoAccion=1;
              break;
            }
            case 'actualizar' :
            {
              $('#adjunto').val('');
              this.limpiar_partida();
              this.modal = 1;
              this.tipoAccion = 2;
              this.tituloModal = 'Actualizar Viaticos';
              this.detalle = data;
              this.id = data['id'];
              this.pda = data['pda'];
              this.adjunto = data['adjunto'];
              this.viatico_id = data['viatico_id'];
              this.fecha_pago = data['fecha_pago'];
              this.importe_tea = data['importe_te'];
              this.importe_ea = data['importe_efectivo'];
              this.beneficiario_id = data['beneficiario_id'];
              this.banco = data['banco'];
              break;
            }


          }
        }
      }
    },

    cerrarModal(){
      this.modal = 0;
    },

    guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          var solicitud_viaticos_id = this.viatico.solicitud.id;
          var beneficiario_id = this.viatico.beneficiarios[0]['empleado_beneficiario_id'];
          // console.log(this.viatico.ben);
          let formData =new FormData();
          formData.append('solicitud_viaticos_id',solicitud_viaticos_id);
          formData.append('fecha_pago',this.fecha_pago);
          formData.append('importe_te',this.importe_tea == '' ? 0 : this.importe_tea);
          formData.append('importe_efectivo',this.importe_ea == '' ? 0 : this.importe_ea);
          formData.append('adjunto',this.adjunto);
          formData.append('banco',this.banco);
          formData.append('nuevo',nuevo);
          formData.append('pda',this.pda);
          formData.append('beneficiario_id',beneficiario_id);
          formData.append('empresa',this.empresa);
          let me = this;
          axios.post('/partidaviaticospagos',formData
          ).then(function (response) {
            if (response.data.status == false) {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            } else {
              me.modal = 0;
              toastr.success('Correcto',nuevo ? 'Pago registrado correctamente' : 'Pago actualizado correctamente');
              me.getData(response.data['viaticos'][0], 0 ,me.empresa);
              me.limpiar_partida();

              var ruta = response.data['viaticos'][0].solicitud.id;
              var efectivo = response.data.efectivo;
              var transferencia = response.data.transferencia;
            }
          }).catch(function (error) {
            console.log(error);
          });

        }
      });
    },

    limpiar_partida(){
      this.fecha_pago = '';
      this.importe_tea = '';
      this.importe_ea = '';
    },

    subirAdjunto(){
      this.adjunto = this.$refs.adjunto.files[0];
    },

    descargarfactura(archivo){
      Utilerias.descargarArc('/descargarfacturapago/','/eliminarfacturapago/',archivo);
    },

    actualizarfactura(){
      this.adjunto = '';
    }

  },
}
</script>
