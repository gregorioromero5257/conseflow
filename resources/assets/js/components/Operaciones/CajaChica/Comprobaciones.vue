<template >
  <div>
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Comprobaciones caja chica

        <!-- <template v-if="tipo === 'solicitud'"> -->

        <button type="button" @click="maestroInicial()" class="btn btn-secondary float-sm-right" >
          <i class="fas fa-arrow-left"></i>&nbsp;Atras
        </button>
        <!-- v-show="contador == 0" -->
        <button type="button" v-show="data.estado != 9"  class="btn btn-dark float-sm-right" @click="abrirModal('comprobante','registrar')">
          <i class="fas fa-plus">&nbsp;</i>Nuevo
        </button>
        <!-- v-show="contador == 0" -->
        <button type="button" v-show="data.estado != 9"  class="btn btn-dark float-sm-right" @click="cerraCC()">
          <i class="fas fa-plus">&nbsp;</i>Cerrar Caja Chica
        </button>
      <!-- </template> -->
      </div>
      <div class="card-body">
        <table class="table table-bordered table-sm">
            <tr>
              <th width="33%" class="table-active">Total comprobado</th>
              <th width="33%" class="table-active">Fondo asignado</th>
              <th width="33%" class="table-active">Saldo Actual</th>
            </tr>
            <tr>
              <td style="text-align:right">$ {{new Intl.NumberFormat().format(total)}}</td>
              <td style="text-align:right">$ {{new Intl.NumberFormat().format(total_pagos)}}</td>
              <td style="text-align:right">$ {{new Intl.NumberFormat().format(total_pagos - total)}}</td>
            </tr>
        </table>
        <v-client-table :columns="columns" :options="options" :data="dataTable" ref="myTable" >
          <template slot="comprobacion.id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp;
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                  <button type="button" v-if="props.row.comprobacion.ccc_estado == 0 || props.row.comprobacion.ccc_estado == 3" @click="eliminar(props.row)" class="dropdown-item" >
                    <i class="fas fa-trash"></i>&nbsp;Eliminar
                  </button>

                </div>
              </div>
            </div>
          </template>

          <template slot="iva" slot-scope="props">
            <template v-if="props.row.impuestos != null">
              <template v-if="props.row.impuestos.impuesto === '002'">
                {{props.row.impuestos.importe}}
              </template>
            </template>
          </template>
          <template slot="ieps" slot-scope="props">
            <template v-if="props.row.impuestos != null">
            <template v-if="props.row.impuestos.impuesto == '003'">
                {{props.row.impuestos.importe}}
            </template>
          </template>
          </template>
          <template slot="tasa_0" slot-scope="props">
            <template v-if="props.row.impuestos != null">
            <template v-if="props.row.impuestos == []">
              -
            </template>
            </template>
          </template>
          <template slot="comprobacion.fecha" slot-scope="props">
            {{(props.row.comprobacion.fecha).substring(0,10)}}
          </template>
          <template slot="comprobacion.ccc_estado" slot-scope="props">
            <template v-if="props.row.comprobacion.ccc_estado === 0">
              <span class="text-warning">Nuevo</span>
            </template>
            <template v-if="props.row.comprobacion.ccc_estado === 2">
              <span class="text-success">Enviado</span>
            </template>
            <template v-if="props.row.comprobacion.ccc_estado === 1">
              <span class="text-primary">Aceptado</span>
            </template>
            <template v-if="props.row.comprobacion.ccc_estado === 3">
              <!-- <span class="text-danger">Rechazado</span> -->
              <button class="btn btn-outline-danger" @click="MotivoRechazo(props.row)">Rechazado</button>
            </template>
          </template>
          <template slot="comprobacion.comprobante" slot-scope="props">
            <button class="btn btn-outline-dark" @click="descargar(props.row.comprobacion.comprobante)"> <i class="fas fa-download"></i> <i class="fas fa-file-pdf"></i> </button>
          </template>
          <template slot="comprobacion.xml" slot-scope="props">
            <button class="btn btn-outline-dark" @click="descargar(props.row.comprobacion.xml)"> <i class="fas fa-download"></i> <i class="fas fa-file-excel"></i> </button>
          </template>
        </v-client-table>
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
                <label>&nbsp;Centro de costo</label>
                <select class="form-control" v-model="centro_costos_id" v-validate="'required'" data-vv-name="centro costos">
                  <option v-for="t in ts"  :key="t.id" :value="t.id">{{t.nombre_corto}}</option>
                </select>
                <span class="text-danger">{{errors.first('centro costos')}}</span>
              </div>
              <div class="form-group col-md-6">
                <label>&nbsp;Departamento</label>
                <select class="form-control" v-model="departamento" v-validate="'required'" data-vv-name="departamento">
                  <option value="1">Almacen</option>
                  <option value="2">Compras</option>
                  <option value="3">TI</option>
                </select>
                <span class="text-danger">{{errors.first('departamento')}}</span>
              </div>

            </div>

            <div class="form-row">
              <div class="form-group col-md-8">
                <template v-if="tipoAccion == 1">
                  <label >Comprobante</label>
                  <input type="file" class="form-control" name="comprobante" id="c_nuevo" @change="onChangeComprobante($event)">
                </template>
                <template v-if="tipoAccion == 2">
                  <template v-if="comprobante != ''">
                    <label>&nbsp;Comprobante</label>
                    <button type="button" class="form-control" @click="comprobante = ''">
                      <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                    </button>
                  </template>
                  <template v-if="comprobante == ''">
                    <label>Comprobante</label>
                    <input type="file" class="form-control" id="c_act" name="comprobante" @change="onChangeComprobante($event)">
                  </template>
                </template>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-8">
                <template v-if="tipoAccion == 1">
                  <label >XML</label>
                  <input type="file" accept="text/xml" id="x_nuevo" class="form-control" name="comprobante"  @change="onChangeXML($event)">
                </template>
                <template v-if="tipoAccion == 2">
                  <template v-if="xml != ''">
                    <label>&nbsp;XML</label>
                    <button type="button" class="form-control" @click="xml = ''">
                      <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                    </button>
                  </template>
                  <template v-if="xml === ''">
                    <label>XML</label>
                    <input type="file" accept="text/xml" id="x_act" class="form-control" name="comprobante" @change="onChangeXML($event)">
                  </template>
                </template>
              </div>
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
    <div v-show="ver == 1">
      {{total_comprobado}}
      <br>
    </div>
    <!-- Fin de modal  -->
  </div>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default{
  data(){
    return{
      contador : 0,
      tipo : '',
      ver : 0,
      total : 0,
      isLoading : false,
      tipoAccion : 0,
      tituloModal : '',
      total_pagos : 0,
      data : '',
      modal : 0,
      ts : [],
      xml : '',
      comprobante : '',
      id : 0,
      cajachica_id : '',
      departamento : '',
      centro_costos_id : 0,
      columns : [
      'comprobacion.id',
      'comprobacion.fecha',
      'comprobacion.ccc_uuid',
      'comprobacion.nombre_e',
      'comprobacion.rfc_e',
      'concepto.descripcion',
      'comprobacion.nombre_corto',
      'comprobacion.subtotal',
      'tasa_0','ieps','iva','retencion.importe',
      'comprobacion.total',
      'comprobacion.comprobante',
      'comprobacion.xml',
      'comprobacion.ccc_estado'],
      dataTable : [],
      options : {
        headings: {
          'comprobacion.id' :'Acciones',
          'comprobacion.fecha' : 'Fecha',
          'comprobacion.ccc_uuid' : 'UUID',
          'comprobacion.nombre_e' : 'Acreedor y/o Proveedor',
          'comprobacion.rfc_e' : 'RFC',
          'concepto.descripcion' : 'Concepto',
          'comprobacion.nombre_corto' : 'Centro Costo',
          'comprobacion.subtotal' : 'Importe',
          'retencion.importe' : 'Retencion',
          'comprobacion.total' : 'Total',
          'comprobacion.ccc_estado' : 'Estado',
          'comprobacion.comprobante' : 'Comprobante',
          'comprobacion.xml' : 'XML',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  computed : {
    total_comprobado(){
      var total = 0;
      this.dataTable.forEach((item, i) => {
        total += parseFloat(item.comprobacion.total);
      });
      return this.total = total;
    }
  },
  methods : {
    getData(){
      let me=this;
      axios.get('/proyectos/ap/').then(response => {
          me.ts = response.data;
      })
      .catch(function (error) {
          console.log(error);
      });
    },

    cargar(data,tipo){
      this.tipo = tipo;
      // console.log(tipo,'sdfghjk');
      this.data = data;
      console.log(data);

      axios.get('/caja/chica/comprobacion/' + data.id + '&' + data.empresa).then(response => {
        var contadorvalidos = 0;
        response.data[0].data.forEach((item, i) => {
          if (item.comprobacion.ccc_estado == 2) {
            contadorvalidos ++;
          }
        });

        if (contadorvalidos == response.data[0].contador && tipo === 'control') {
          this.dataTable = response.data[0].data;
        }else {
          this.dataTable = response.data[0].data;
        }

        this.contador = response.data[0].contador;
      }).catch(e => {
        console.error(e);
      });

      var p = 0;
      axios.get('caja/chica/pagos/' + data.id + '&' + data.empresa).then(response => {
        response.data.forEach((item, i) => {
          p += parseFloat(item.cantidad);
        });
        this.total_pagos = p;
      }).catch(e => {
        console.error(e);
      });

    },

    maestroInicial(){
      this.$emit('comprobaciones:change');
    },

    onChangeComprobante(e){
      // console.log(e);
      this.comprobante = e.target.files[0];
    },

    onChangeXML(e){
      this.xml = e.target.files[0];
    },


    abrirModal(modelo, accion, data = []){
      if (this.empresa == 0) {
        toastr.warning('Seleccione una empresa');
      }else {
        switch(modelo){
          case "comprobante":
          {
            switch(accion){
              case 'registrar':
              {
                Utilerias.resetObject(this.solicitud);
                this.modal= 1;
                this.tituloModal = 'Registrar comprobacion de caja chica';
                this.tipoAccion=1;
                // this.diaActual();
                break;
              }
              case 'actualizar' :
              {
                Utilerias.resetObject(this.solicitud);
                this.tituloModal = 'Actualizar comprobacion de caja chica';
                this.modal = 1;
                this.tipoAccion = 2;
                // console.log(data);
                break;
              }
            }
          }
        }
      }
    },

    cerrarModal(){
      this.modal = 0;
    },

    Vaciar(){
      this.centro_costos_id = '';
      this.comprobante = '';
      this.xml = '';
      $('#c_nuevo').val('');
      $('#x_nuevo').val('');
    },

    guardar(nuevo){
      if (this.comprobante === '') {
        toastr.warning('Seleccione un PDF');
      }else if (this.xml === '') {
        toastr.warning('Seleccione un XML');
      }else {

    this.$validator.validate().then(result => {
      if (result) {
        this.isLoading = true;

        let me = this;

        let formData = new FormData();

        formData.append('id',this.id);
        formData.append('cajachica_id',this.data.id);
        formData.append('comprobante',this.comprobante);
        formData.append('xml',this.xml);
        formData.append('centro_costos_id',this.centro_costos_id);
        formData.append('empresa',this.data.empresa);
        formData.append('departamento',this.departamento);
        axios({
          method: nuevo ? 'POST' : 'POST',
          url: nuevo ? 'caja/chica/comprobacion/store' : 'caja/chica/comprobacion/update',
          data: formData
        }).then(function (response) {
          me.isLoading = false;
          me.modal = 0;
          me.Vaciar();
          me.cargar(me.data);
          if(!nuevo){
            toastr.success('Actualizado Correctamente');
          }
          else
          {
            toastr.success('Registrado Correctamente');
          }
        }).catch(function (error) {
          console.log(error);
        });

      }
    });
  }
    },

    descargar(archivo){
      let me=this;
      axios({
        url: '/facturasentradasdescarga/' + archivo,
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
        axios.get('/facturasentradaseditar/' + archivo)
        .then(response => {
        })
        .catch(function (error) {
          console.log(error);
        });
        //--fin del metodo borrar--//
      });
    },

    eliminar(data){
      swal({
        title:  'Esta seguro(a) de eliminar?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4dbd74',
        cancelButtonColor: '#f86c6b',
        confirmButtonText: 'Aceptar!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          axios.get('caja/chica/comprobacion/eliminar/' + data.comprobacion.ccc_id).then(response => {
            toastr.success('Eliminado Correctamente!!!');
            this.cargar(this.data);
          }).catch(e => {
            console.error(e);
          });
        }
      });
    },

    cerraCC(){
      // console.log(this.data);
      swal({
        title:  'Esta seguro(a) de cerrar?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4dbd74',
        cancelButtonColor: '#f86c6b',
        confirmButtonText: 'Aceptar!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          axios.get('caja/chica/comprobacion/cerrar/' + this.data.id + '&' + this.data.empresa).then(response => {
            if (response.data.estatus == 2) {
              toastr.warning(response.data.mensaje);
            }else if (response.data.estatus == 1) {
              toastr.success(response.data.mensaje);
              this.cargar(this.data);
            }
          }).catch(e => {
            console.error(e);
          });
        }
      });
    },

    MotivoRechazo(data){
      console.log(data);
      axios.get('get/motivo/rechazo/comprobacion/caja/chica/' + data.comprobacion.ccc_id).then(result => {
        toastr.warning(result.data.comentario);
      }).catch(e => {
        console.error(e);
      });
    },

  },
  mounted(){
    this.getData();
  }
}

</script>
