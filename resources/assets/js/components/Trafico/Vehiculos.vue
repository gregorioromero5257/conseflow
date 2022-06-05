<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Vehículos.
          <button type="button" @click="abrirModal('vehiculo','registrar')" class="btn btn-dark float-sm-right">
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
              <button type="button" @click="abrirModal('vehiculo','actualizar',props.row)" class="dropdown-item">
                <i class="icon-pencil"></i>&nbsp;Actualizar vehiculo.
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
                <label class="col-md-3 form-control-label">Descripción</label>
                <div class="col-md-9">
                  <textarea  v-validate="'required'" v-model="vehiculo.descripcion" class="form-control"  data-vv-name="descripcion" placeholder="Nombre del Vehiculo" autocomplete="off" rows="8" cols="80"></textarea>
                  <!-- <input type="text" v-validate="'required'" v-model="vehiculo.descripcion" class="form-control"  data-vv-name="descripcion" placeholder="Nombre del Vehiculo" autocomplete="off" > -->
                  <span class="text-danger">{{ errors.first('descripcion') }}</span>
                </div>
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
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      url: '/catvehiculos',
      desabilitado : 0,
      vehiculo: {
        id : 0,
        descripcion : '',
        },
        centro_costo : [],
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: ['id','descripcion'],
      tableData: [],
      options: {
        headings: {
         'id': 'Acción',
        'descripcion': 'Descripción',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['descripcion'],
        filterable: ['descripcion'],
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
            'id': this.vehiculo.id,
            'descripcion': this.vehiculo.descripcion,
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
    Utilerias.resetObject(this.vehiculo);
  },

  abrirModal(modelo, accion, data = []){
    switch(modelo){
      case "vehiculo":
      {
        switch(accion){
          case 'registrar':
          {
            this.modal = 1;
            this.desabilitado = 1;
            this.tituloModal = 'Registrar Vehículo';
            Utilerias.resetObject(this.vehiculo);
            this.vehiculo.unidad_medida='Vehículo';
            this.tipoAccion = 1;
            break;
          }
          case 'actualizar':
          {
            this.modal=1;
            this.desabilitado = 1;
            this.tituloModal='Actualizar Vehículo';
            this.vehiculo.id=data['id'];
            this.tipoAccion=2;
            this.vehiculo.descripcion = data['descripcion'];
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
