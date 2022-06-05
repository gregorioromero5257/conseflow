<template >
  <div>
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Conceptos de la factura {{ datos == null ? '' : datos.serie + ' ' + datos.folio}}
        <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
          <i class="fas fa-arrow-left"></i>&nbsp;Atras
        </button>
        <button type="button" v-show="datos == null ? '' :datos.tipo_factura_id == 4" class="btn btn-dark float-sm-right" @click="abrirModal('partidas','registrar_pagos')">
          <i class="fas fa-plus">&nbsp;</i>Nuevo
        </button>

      </div>
      <div class="card-body" >
        <!-- <v-client-table :data="tableData" :columns="columns" :options="options" v-show="datos == null ? '' :datos.tipo_factura_id == 2">
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button  type="button" :disabled="datos.timbrado == 1" @click="abrirModal('partidas','actualizar',props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp; Actualizar Concepto
                  </button>
                  <button  type="button" :disabled="datos.timbrado == 1" @click="eliminar(props.row)" class="dropdown-item">
                    <i class="fas fa-list-ol"></i>&nbsp; Eliminar
                  </button>
                </div>
              </div>
            </div>
          </template>
        </v-client-table> -->
        <v-client-table :data="tableDataUno" :columns="columnsUno" :options="optionsUno" >
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button  type="button" :disabled="datos.timbrado == 1" @click="abrirModal('partidas','actualizar',props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp; Actualizar Concepto
                  </button>
                  <button  type="button" :disabled="datos.timbrado == 1" @click="eliminar(props.row)" class="dropdown-item">
                    <i class="fas fa-list-ol"></i>&nbsp; Eliminar
                  </button>
                </div>
              </div>
            </div>
          </template>
        </v-client-table>
      </div>
    </div>

    <!--Inicio del modal agregar/actualizar-->
    <!-- <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
              <div class="form-group col-md-8">
                <label>Producto y servicios</label>
                <v-select :options="optionsvsps" id="productos servicios" name="productos servicios"
                v-model="partidas.productos_servicios_id" label="name" data-vv-name="productos servicios" ></v-select>
                <span class="text-danger">{{errors.first('productos servicios')}}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Clave unidad</label>
                <select class="form-control" v-model="partidas.unidad_id" data-vv-name="clave unidad" >
                  <option v-for="item in Unidad" :value="item.id">{{item.c_unidad}} {{item.nombre}}</option>
                </select>
                <span class="text-danger">{{errors.first('clave unidad')}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Cantidad </label>
                <input type="text" class="form-control" v-model="partidas.cantidad"
                placeholder="Cantidad" @input="multiplicar()" v-validate="'decimal:2'" data-vv-name="cantidad">
                <span class="text-danger">{{errors.first('cantidad')}}</span>
              </div >
              <div class="form-group col-md-4">
                <label>Unidad </label>
                <input type="text" class="form-control" v-model="partidas.unidad"
                placeholder="Unidad" data-vv-name="unidad" >
                <span class="text-danger">{{errors.first('unidad')}}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Número de identificación </label>
                <input type="text" class="form-control" v-model="partidas.numero_identificacion"
                placeholder="Número de identificación" data-vv-name="numero de identificacion" >
                <span class="text-danger">{{errors.first('numero de identificacion')}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>Descripción</label>
                <input type="text" class="form-control" v-model="partidas.descripcion"
                placeholder="Descripción" data-vv-name="descripcion">
                <span class="text-danger">{{errors.first('descripcion')}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label>Valor Unitario</label>
                <input type="text" class="form-control" v-model="partidas.valor_unitario"
                placeholder="Valor unitario" @input="multiplicar()" data-vv-name="valor unitario" v-validate="'decimal:2'">
                <span class="text-danger">{{errors.first('valor unitario')}}</span>
              </div>
              <div class="form-group col-md-3">
                <label>Importe</label>
                <input type="text" class="form-control" v-model="partidas.importe"
                placeholder="Importe" readonly>
              </div>
              <div class="form-group col-md-3">
                <label>Descuento</label>
                <input type="text" class="form-control" v-model="partidas.descuento"
                placeholder="Descuento" data-vv-name="descuento" v-validate="'decimal:2'" @input="multiplicar()">
                <span class="text-danger">{{errors.first('descuento')}}</span>
              </div>
              <div class="form-group col-md-3">
                <label>Iva</label>
                <input type="text" class="form-control" v-model="partidas.impuesto_iva"
                placeholder="Iva" data-vv-name="iva">
                <span class="text-danger">{{errors.first('descuento')}}</span>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <vue-element-loading :active="isLoading"/>
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
          </div>
        </div> -->
        <!-- /.modal-content -->
      <!-- </div> -->
      <!-- /.modal-dialog -->
    <!-- </div> -->

    <!-- ************************************************************  -->
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modaluno}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModalUno"></h4>
            <button type="button" class="close" @click="cerrarModalUno()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">


            <div class="form-row">
              <div class="form-group col-md-2">
                <label class="form-check-label">
                  <input class="form-check-input" name="exampleRadios" id="anterior" type="radio" @change="estado"/> Anterior
                </label>
              </div>
              <div class="form-group col-md-2">
                <label class="form-check-label">
                  <input class="form-check-input" name="exampleRadios" id="existente" type="radio" @change="estado"/> Existente
                </label>
              </div>
            </div>
            <div v-show="verc">
              {{saldos}}
            </div>

            <!--  -->
            <div class="form-row">
              <div class="form-group col-md-7"  v-show="anterior">
                <label>UUID</label>
                <input type="text" class="form-control" v-model="partidas_pagos.uuid">
              </div>
              <div class="form-group col-md-7"  v-show="existente">
                <label>UUID</label>
                <v-select :options="optionsvsu" id="relaccionados" name="relaccionados"
                 label="name" data-vv-name="relacionados" v-model="partidas_pagos.factura_id" @input="verfactura"></v-select>
              </div>
              <div class="form-group col-md-2">
                <label>Serie</label>
                <input type="text" class="form-control" v-model="partidas_pagos.serie" :disabled="existente">
              </div>
              <div class="form-group col-md-2">
                <label>Folio</label>
                <input type="text" class="form-control" v-model="partidas_pagos.folio" :disabled="existente">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-2">
                <label>Parcialidad</label>
                <input type="text" class="form-control" name="numero parcialidad" data-vv-name="numero parcialidad"
                v-validate="'integer'" v-model="partidas_pagos.num_parcialidad">
                <span class="text-danger">{{errors.first('numero parcialidad')}}</span>
              </div>
              <div class="form-group col-md-3">
                <label>Saldo anterior</label>
                <input type="text" class="form-control" name="saldo anterior" data-vv-name="saldo anterior"
                v-validate="'decimal:2'" v-model="partidas_pagos.saldo_anterior">
                <span class="text-danger">{{errors.first('saldo anterior')}}</span>
              </div>
              <div class="form-group col-md-3">
                <label>Importe pagado</label>
                <input type="text" class="form-control" name="importe pagado" data-vv-name="importe pagado"
                 v-validate="'decimal:2'" v-model="partidas_pagos.importe_pagado" v-on:blur="validaMonto()">
                <span class="text-danger">{{errors.first('importe pagado')}}</span>
              </div>
              <div class="form-group col-md-3">
                <label>Saldo insoluto</label>
                <input type="text" class="form-control" name="saldo insoluto" data-vv-name="saldo insoluto"
                 v-validate="'decimal:2'" v-model="(partidas_pagos.saldo_insoluto).toFixed(2)">
                <span class="text-danger">{{errors.first('saldo insoluto')}}</span>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <vue-element-loading :active="isLoading"/>
            <button type="button" class="btn btn-outline-dark" @click="cerrarModalUno()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="tipoAccionUno==1" class="btn btn-secondary" @click="validaUno(); guardarUno(1);"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="tipoAccionUno==2" class="btn btn-secondary" @click="guardarUno(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

  </div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      verc : false,
      modal : 0,
      modaluno : 0,
      tituloModal : '',
      tituloModalUno : '',
      tipoAccion : 0,
      tipoAccionUno  : 0,
      isLoading : false,
      anterior : false,
      existente :false,
      optionsvsu : [],
      validadouno : '',
      abc : '',
      partidas : {
        id : '',
        productos_servicios_id : '',
        unidad_id : '',
        cantidad : '',
        unidad : '',
        numero_identificacion : '',
        descripcion : '',
        valor_unitario : '',
        importe : '',
        descuento : '0',
        impuesto_iva : '',
        factura_id : '',
      },
      partidas_pagos : {
        id : '',
        factura_id : '',
        saldo_anterior : '',
        importe_pagado : '',
        saldo_insoluto : '',
        partidas_facturas_id : '',
        uuid : '',
        serie : '',
        folio : '',
        num_parcialidad : '1',
      },
      ProductoServicio : [],
      datos : null,
      tableData : [],
      columns : ['id','scps_descripcion','scu_nombre','cantidad','unidad','numero_identificacion','descripcion','valor_unitario','importe','descuento'],
      options: {
        headings: {
          'id' : 'Acciones',
          'scps_descripcion' : 'Productos/Servicios',
          'scu_nombre' : 'Clave unidad',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      tableDataUno : [],
      columnsUno : ['id','uuid','serie','folio','saldo_anterior','importe_pagado','saldo_insoluto'],
      optionsUno: {
        headings: {
          'id' :'Acciones',
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
    saldos(){
      var resul;
      resul = this.partidas_pagos.saldo_anterior - this.partidas_pagos.importe_pagado;
      return this.partidas_pagos.saldo_insoluto = resul;
    },
  },
  methods : {
    getData(data){
      this.datos = data;
      this.partidas.factura_id = data.id;

      axios.get('/partidafactura/' + data.id + '?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1')
           .then(response => {
             this.tableData = response.data;
         }).catch(error => {console.error(error);});

        axios.get('/partidafacturapagos/' + data.id).then((response) => {
          // console.log(response);
          this.tableDataUno = response.data;
        }).catch((err) => {console.error(err);})

        axios.get('/factura').then(response => {
          // this.tableDatas = response.data;

          if (response.data.length != 0) {
            for (var i = 0; i < response.data.length; i++) {

              if (response.data[i].timbrado == '1') {
                this.optionsvsu.push({
                  id : response.data[i].id,
                  name : response.data[i].serie + ' ' + response.data[i].folio + ' ' + response.data[i].uuid,
                });

                this.contador += 1;
              }
            }
          }
        }).catch(error => {console.error(error);});
      },



      /**
      * [abrirModal description]
      * @param  {String} modelo    [description]
      * @param  {String} accion    [description]
      * @param  {Array}  [data=[]] [description]
      * @return {Response}           [description]
      */
      abrirModal(modelo, accion, data = [])
      {
        switch(modelo){
          case "partidas":
          {
            switch(accion){
              case 'registrar':
              {
                this.modal= 1;
                this.tituloModal = 'Registrar conceptos';
                this.tipoAccion=1;
                this.vaciar();
                this.datos.rfc_receptor == 'XEXX010101000' ? this.partidas.impuesto_iva = '0.000000' : this.partidas.impuesto_iva = '0.160000';
                break;
              }
              case 'registrar_pagos':
              {
                this.vaciar();
                this.modaluno= 1;
                this.tituloModalUno = 'Registrar pago';
                this.tipoAccionUno=1;
                // this.vaciar();
                // this.datos.rfc_receptor == 'XEXX010101000' ? this.partidas.impuesto_iva = '0.000000' : this.partidas.impuesto_iva = '0.160000';
                break;
              }
              case 'actualizar':
              {
                this.vaciar();
                this.modaluno= 1;
                this.tituloModalUno = 'Actualizar pagos';
                this.tipoAccionUno=2;
                data['factura_id'] == null ? $("#anterior").prop("checked", true) : $("#existente").prop("checked", true);
                this.estado();
                this.abc = data;
                this.partidas_pagos.id = data['id'];
                this.partidas_pagos.saldo_anterior = data['saldo_anterior'];
                this.partidas_pagos.importe_pagado = data['importe_pagado'];
                this.partidas_pagos.saldo_insoluto = data['saldo_insoluto'];
                this.partidas_pagos.num_parcialidad = data['num_parcialidad'];
                this.partidas_pagos.serie = data['serie'];
                this.partidas_pagos.folio = data['folio'];
                this.partidas_pagos.uuid = data['uuid'];
                this.partidas_pagos.factura_id =  data['factura_id'] == null ? '' : {id : data['factura_id'],name : data['serie']+ ' '+ data['folio']+ ' '+data['uuid']};
                // alert(data['saldo_anterior']);
                this.partidas_pagos.partidas_facturas_id = data['partidas_facturas_id'];
                console.log(data);
                break;
              }
            }
          }
        }
      },

      maestro(){
        this.$emit('maestro:conceptospagos');
      },

      cerrarModal(){
        this.modal = 0;
      },

      cerrarModalUno()
      {
        this.modaluno = 0;
        this.vaciar();
      },

      multiplicar(){
        this.partidas.importe = (this.partidas.cantidad * this.partidas.valor_unitario);
      },

      vaciar(){
        this.partidas_pagos.uuid = '';
        this.partidas_pagos.serie = '';
        this.partidas_pagos.folio = '';
        this.partidas_pagos.saldo_anterior = '';
        this.partidas_pagos.importe_pagado = '';
        this.partidas_pagos.saldo_insoluto = '';
        this.partidas_pagos.factura_id ='';
        // this.partidas.importe = '';
        // this.partidas.descuento = '';
        // this.partidas.factura_id = '';
      },

      eliminar(data){
        // console.log(data);
        let me = this;
        axios.delete('/partidafacturapagos/' + data.id + '&' + data.partidas_facturas_id).then(response => {
          toastr.info('Registro eliminado correctamente','Atención');
          me.getData(me.datos);
          console.log(response);
        }).catch(error => {
          console.error(error);
        });
      },

      guardar(nuevo){
        this.$validator.validate().then(result => {
          if (result) {
            this.isLoading = true;
            let me = this;
            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? '/partidafactura' : '/partidafactura/'+ this.partidas.id,
              data: {
                // id : this.factura.id,
                productos_servicios_id : this.partidas.productos_servicios_id.id,
                unidad_id : this.partidas.unidad_id,
                cantidad : this.partidas.cantidad,
                unidad : this.partidas.unidad,
                numero_identificacion : this.partidas.numero_identificacion,
                descripcion : this.partidas.descripcion,
                valor_unitario : this.partidas.valor_unitario,
                importe : this.partidas.importe,
                descuento : this.partidas.descuento,
                impuesto_iva : this.partidas.impuesto_iva,
                factura_id : this.partidas.factura_id,
              }
            }).then(function (response) {
              me.isLoading = false;
              // if (response.data.status) {

              me.cerrarModal();
              toastr.success(nuevo ? 'Concepto agregada correctamente' : 'Concepto actualizada correctamente','Correcto');
              me.getData(response.data);

              // }else {
              //   swal({
              //     type: 'error',
              //     html: response.data.errors.join('<br>'),
              //   });
              // }

            }).catch(function (error) {
              console.log(error);
            });
          }
        });
      },

      guardarUno(nuevo){
        if (this.validadouno == '1') {

        this.$validator.validate().then(result => {

          if (result) {
            this.isLoading = true;
            let me = this;
            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? '/partidafacturapagos' : '/partidafacturpagos/'+ this.partidaspagos.id,
              data: {
                // id : this.factura.id,
                factura_id : this.partidas_pagos.factura_id === '' ? '' :this.partidas_pagos.factura_id.id,
                num_parcialidad : this.partidas_pagos.num_parcialidad,
                saldo_anterior : this.partidas_pagos.saldo_anterior,
                importe_pagado : this.partidas_pagos.importe_pagado,
                saldo_insoluto : this.partidas_pagos.saldo_insoluto,
                uuid : this.partidas_pagos.uuid,
                serie : this.partidas_pagos.serie,
                folio : this.partidas_pagos.folio,
                partidas_facturas_id : this.tableData.data[0].id,


              }
            }).then(function (response) {
              me.isLoading = false;
              // // if (response.data.status) {
              me.cerrarModalUno();
              toastr.success(nuevo ? 'Concepto agregada correctamente' : 'Concepto actualizada correctamente','Correcto');
              me.getData(response.data);
              // }else {
              //   swal({
              //     type: 'error',
              //     html: response.data.errors.join('<br>'),
              //   });
              // }

            }).catch(function (error) {
              console.log(error);
            });
          }
        });
      }
      },

      estado(){
        if ($("#anterior").prop("checked")) {
          this.anterior = true;
          this.existente = false;
          this.partidas_pagos.factura_id = '';
          this.partidas_pagos.serie = '';
          this.partidas_pagos.folio = '';

        }
        else {
          this.existente = true;
          this.anterior = false;
          // this.partidas_pagos.uuid = '';
          this.partidas_pagos.serie = '';
          this.partidas_pagos.folio = '';
        }
      },

      verfactura(){
        // console.log(this.factura.factura_id);
        axios.get('/factura/' + (this.partidas_pagos.factura_id == '' ? 0 : this.partidas_pagos.factura_id.id)).then(response => {
          this.partidas_pagos.serie = response.data.serie;
          this.partidas_pagos.folio = response.data.folio;
          this.partidas_pagos.uuid = response.data.uuid;
          this.partidas_pagos.saldo_anterior = response.data.total;
        }).catch((err) => {console.log(err);})
      },

      validaMonto(){
        if (parseFloat(this.partidas_pagos.importe_pagado) > parseFloat(this.partidas_pagos.saldo_anterior)) {
          toastr.warning('No se puede pagar una cantidad mayor a el saldo anterior','Error');
          this.partidas_pagos.importe_pagado = '';
        }
      },

      validaUno(){
        var estado = 0;
        var serie = this.partidas_pagos.serie;
        var folio =this.partidas_pagos.folio;
        var uuid = this.partidas_pagos.uuid;
        var anterior = this.partidas_pagos.saldo_anterior;
        var pagado = this.partidas_pagos.importe_pagado;
        var insoluto = this.partidas_pagos.saldo_insoluto;
        var parcial = this.partidas_pagos.num_parcialidad;
        if(serie === '' || folio === '' || uuid === '' || anterior === '' || pagado === '' || insoluto === '' || parcial === '' ){
          estado = 0;
          toastr.warning('Complete todos los campos','Atención');
        }
        else {
          estado = 1;
        }
        this.validadouno = estado;
      },


    }
  }
  </script>
