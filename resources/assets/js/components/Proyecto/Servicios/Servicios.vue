<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Catálogo de Servicios.
          <button type="button" @click="abrirModal('servicio','registrar')" class="btn btn-dark float-sm-right">
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">


            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
               <div class="btn-group dropup" role="group">
                 <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="fas fa-grip-horizontal"></i> Acciones
                 </button>
                 <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
              <button type="button" @click="abrirModal('servicio','actualizar',props.row)" class="dropdown-item">
                <i class="icon-pencil"></i>&nbsp;Actualizar servicio.
              </button>
              </div>
              </div>
              </div>

            </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <vue-element-loading :active="isLoading"/>
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>

            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
              <input type="hidden" name="id">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="nombre_servicio">Nombre del Servicio</label>
                <div class="col-md-9">
                  <input type="text" v-validate="'required'" name="nombre_servicio" v-model="servicio.nombre_servicio" class="form-control" placeholder="Nombre del Servicio" autocomplete="off" id="nombre_servicio">
                  <span class="text-danger">{{ errors.first('nombre_servicio') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="proveedor_marca">Marca/Proveedor</label>
                <div class="col-md-9">
                  <input type="text" v-validate="'required'" name="proveedor_marca" v-model="servicio.proveedor_marca" class="form-control" placeholder="Marca/Proveedor" autocomplete="off" id="proveedor_marca">
                  <span class="text-danger">{{ errors.first('proveedor_marca') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="unidad_medida">Unidad de Medida</label>
                <div class="col-md-9">
                  <input v-bind:disabled="desabilitado == 1" type="text" v-validate="'required'" name="unidad_medida" v-model="servicio.unidad_medida" class="form-control" placeholder="Unidad de Medida" autocomplete="off" id="unidad_medida">
                  <span class="text-danger">{{ errors.first('unidad_medida') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="trid">Centro de costo</label>
                <div class="col-md-9">
                  <select class="form-control" data-vv-name="Centro de costos" v-model="servicio.centro_costos_id">
                    <option v-for="t in centro_costo" :value="t.id">{{t.nombre_sub}}&nbsp;{{t.nombre}}</option>
                  </select>
                </div>
                <span class="text-danger">{{ errors.first('Centro de costos') }}</span>
              </div>
              <!-- </form> -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarServicio(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarServicio(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      url: '/catservicios',
      desabilitado : 0,
      servicio: {
        id : 0,
        nombre_servicio : '',
        proveedor_marca : '',
        unidad_medida : 'Servicio',
        centro_costos_id : '',
        },
        centro_costo : [],
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: ['id','nombre_servicio','proveedor_marca', 'unidad_medida'],
      tableData: [],
      options: {
        headings: {
         'id': 'Acción',
        'nombre_servicio': 'Nombre del Servicio',
        'proveedor_marca' : 'Marca/Servicio',
        'unidad_medida' : 'Unidad de Medida',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre_servicio', 'proveedor_marca'],
        filterable: ['nombre_servicio', 'proveedor_marca'],
        filterByColumn: true,
      texts:config.texts
  },
}
},
computed:{
},
methods : {
  getData() {
    let me=this;
    axios.get(me.url).then(response => {
      me.tableData = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
    axios.get(`/catalogos/centrocostos/`).then(response => {
      me.centro_costo = response.data;
    }).catch(error => {console.log(error)});
  },
  guardarServicio(nuevo){
    this.$validator.validate().then(result => {
      if (result) {
        this.isLoading = true;
        let me = this;
        axios({
          method: nuevo ? 'POST' : 'PUT',
          url: nuevo ? me.url : me.url+'/'+this.id,
          data: {
            'id': this.servicio.id,
            'nombre_servicio': this.servicio.nombre_servicio,
            'proveedor_marca': this.servicio.proveedor_marca,
            'unidad_medida': this.servicio.unidad_medida,
            'centro_costos_id' : this.servicio.centro_costos_id,
          }
        }).then(function (response) {
          me.isLoading = false;
          if (response.data.status) {
            if (response.data.status === 'error') {
              swal({
                type: 'error',
                html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
              });
            }else {
              me.cerrarModal();
              me.getData();
              if (!nuevo) {
                toastr.success('Estado Actualizado Correctamente');
              }else {
                toastr.success('Estado Agregado Correctamente');
              }
            }
          } else {
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

  cerrarModal(){
    this.modal=0;
    this.tituloModal='';
    Utilerias.resetObject(this.familiare);
  },

  abrirModal(modelo, accion, data = []){
    switch(modelo){
      case "servicio":
      {
        switch(accion){
          case 'registrar':
          {
            this.modal = 1;
            this.desabilitado = 1;
            this.tituloModal = 'Registrar Servicio';
            Utilerias.resetObject(this.servicio);
            this.servicio.unidad_medida='Servicio';
            this.tipoAccion = 1;
            break;
          }
          case 'actualizar':
          {
            this.modal=1;
            this.desabilitado = 1;
            this.tituloModal='Actualizar Servicio';
            this.servicio.id=data['id'];
            this.tipoAccion=2;
            this.servicio.nombre_servicio = data['nombre_servicio'];
            this.servicio.proveedor_marca = data['proveedor_marca'];
            this.servicio.unidad_medida = data['unidad_medida'];
            this.servicio.centro_costos_id = data['centro_costos_id'];
            break;
          }
        }
      }
    }
  }
},

mounted() {
  this.getData();
  this.$root.limpiarDashboard();
}
}
</script>
