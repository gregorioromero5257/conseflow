<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
          <div class="card-header">
            <i class="fa fa-align-justify"></i> Bitacora
            <button type="button" @click="abrirModal('bitacora','registrar')" class="btn btn-dark float-sm-right">
              <i class="fas fa-plus"></i>&nbsp;Nuevo
            </button>
            <button type="button" @click="descargar()" class="btn btn-dark float-sm-right">
              <i class="fas fa-file-excel"></i>&nbsp;Descargar
            </button>
          </div>
          <div class="card-body">
            <v-client-table :data="tableData" :options="options" :columns="columns">
              <template slot="id" slot-scope="props">

                <button class="btn btn-outline-dark" @click.prevent="abrirModal('bitacora','actualizar',props.row)" >
                  <i class="fas fa-pencil-alt"></i>&nbsp;Actualizar
                </button>


              </template>
            </v-client-table>
          </div>
      </div>

      <div class="modal fade" tabindex="-1" :class="{ mostrar: modal }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title">{{tituloModal}}</h4>
                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-row">
                  <div class="col-md-6 mb-3">
                    <label>Fecha Entrada</label>
                    <v-select taggable multiple v-model="dataSelect" v-validate="'required'" data-vv-name="Fecha entrada"/>
                    <span class="text-danger">{{errors.first('Fecha entrada')}}</span>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Residuo</label>
                    <input type="text" class="form-control" v-model="residuo" v-validate="'required'" data-vv-name="Residuo">
                    <span class="text-danger">{{errors.first('Residuo')}}</span>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>Cantidad</label>
                    <input type="text" v-validate="'decimal:2'" data-vv-name="Cantidad" class="form-control" v-model="cantidad">
                    <span class="text-danger">{{errors.first('Cantidad')}}</span>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Unidad</label>
                    <select class="form-control" v-model="unidad">
                      <option value="M3">M3</option>
                      <option value="BOLSA">BOLSA</option>
                    </select>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Fecha Salida</label>
                    <input type="date" class="form-control" v-model="fecha_salida">
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-6 mb-3">
                    <label>Nombre personal entrega</label>
                    <v-select :options="listaEmpleados" label="name" v-model="empleado_entrega"></v-select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Proveedor que recolecto</label>
                    <input type="text" class="form-control" v-model="proveedor">
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>Folio</label>
                    <input type="text" v-model="folio" class="form-control">
                  </div>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="cerrarModal()">
                  <i class="fas fa-window-close"></i>&nbsp;Cerrar
                </button>
                <button v-show="tipoAccion == 1" type="button" class="btn btn-secondary" @click="Guardar(1)">
                  Guardar
                </button>
                <button v-show="tipoAccion == 2" type="button" class="btn btn-secondary" @click="Guardar(0)">
                  Actualizar
                </button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar documentos-->


    </div>
  </main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      tableData : [],
      columns : ['id','fechas','residuo','cantidad','unidad','fecha_salida','entrega','proveedor','folio'],
      options:
      {
        headings:
        {
          id : 'Acciones',
        }, // Headings,
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      }, //options
      dataSelect : '',
      residuo : '',
      cantidad : '',
      unidad : '',
      fecha_salida : '',
      empleado_entrega : '',
      proveedor : '',
      id : 0,
      listaEmpleados : [],
      folio : '',
    }
  },
  methods : {
    getLista(){
      axios.get('/residuos/urbanos/seguridad/get').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    abrirModal(modelo, accion, data = []){
      switch(modelo){
        case "bitacora":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Registrar';
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar':
            {
              console.log(data);
              var valores = data['fechas'].split(',');
              var fechas = valores.pop();
              this.modal=1;
              this.tituloModal='Actualizar';
              this.tipoAccion=2;
              this.residuo = data['residuo'];
              this.cantidad = data['cantidad'];
              this.unidad = data['unidad'];
              this.fecha_salida = data['fecha_salida'];
              this.empleado_entrega = {id : data['empleado_entrega_id'], name : data['entrega']};
              this.proveedor = data['proveedor'];
              this.id = data['id'];
              this.dataSelect = valores;
              this.folio = data['folio'];
              break;
            }
          }
        }
      }
    },

    Vaciar(){
      this.residuo = '';
      this.cantidad = '';
      this.unidad = '';
      this.fecha_salida = '';
      this.empleado_entrega = '';
      this.proveedor = '';
      this.id = 0;
      this.folio = '';
      this.dataSelect = '';
    },

    cerrarModal() {
      this.modal = 0;
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
      })
      .catch(function (error)
      {
        console.log(error);
      });
    },

    Guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? '/residuos/urbanos/seguridad/guardar' : '/residuos/urbanos/seguridad/actualizar',
            data : {
              id : this.id,
              residuo : this.residuo,
              cantidad : this.cantidad,
              unidad : this.unidad,
              fecha_salida : this.fecha_salida,
              empleado_entrega : this.empleado_entrega.id,
              proveedor : this.proveedor,
              fechas : this.dataSelect,
              folio : this.folio,
            }
          }).then(response => {
            console.log(response);
            this.cerrarModal();
            this.Vaciar();
            this.getLista();
            toastr.success(nuevo ? 'Guardado Correctamente' : 'Actualizado Correctamente');
          }).catch(e => {
            console.error();
          });
        }
      });
    },

    descargar(){
      window.open('residuos/urbanos/seguridad/descargar/' , '_blank');
    },
  },
  mounted(){
    this.getData();
    this.getLista();
  },
}
</script>
