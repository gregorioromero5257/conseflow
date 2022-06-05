<template>
  <main class="main">

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
                <div class="col-md-6">
                  <textarea  v-validate="'required'" v-model="catalogo.descripcion" class="form-control"  data-vv-name="descripcion" placeholder="Descripción" autocomplete="off" rows="8" cols="80"></textarea>
                  <!-- <input type="text" v-validate="'required'" v-model="vehiculo.descripcion" class="form-control"  data-vv-name="descripcion" placeholder="Nombre del Vehiculo" autocomplete="off" > -->
                  <span class="text-danger">{{ errors.first('descripcion') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Codigo</label>
                <div class="col-md-6">
                  <input type="text" v-validate="'required'" v-model="catalogo.codigo" class="form-control"  data-vv-name="codigo"
                   placeholder="Codígo" autocomplete="off" >
                  <span class="text-danger">{{ errors.first('codigo') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Marca</label>
                <div class="col-md-6">
                  <input type="text" v-validate="'required'" v-model="catalogo.marca" class="form-control"  data-vv-name="marca"
                   placeholder="Marca" autocomplete="off" >
                  <span class="text-danger">{{ errors.first('marca') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label">Comentario</label>
                <div class="col-md-6">
                  <input type="text" v-validate="'required'" v-model="catalogo.comentario" class="form-control"  data-vv-name="comentario"
                   placeholder="Comentario" autocomplete="off" >
                  <span class="text-danger">{{ errors.first('comentario') }}</span>
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
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      url: '/catmantenimientovehiculos',
      desabilitado : 0,
      catalogo: {
        id : 0,
        descripcion : '',
        codigo : '',
        marca : '',
        comentario : '',
        },
        centro_costo : [],
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: ['id','descripcion','codigo','marca','comentario'],
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
        sortable: ['descripcion','codigo','marca','comentario'],
        filterable: ['descripcion','codigo','marca','comentario'],
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
            'id': this.catalogo.id,
            'descripcion': this.catalogo.descripcion,
            'codigo': this.catalogo.codigo,
            'marca': this.catalogo.marca,
            'comentario': this.catalogo.comentario,
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
              // me.getData();
              if (!nuevo) {
                toastr.success('Estado Actualizado Correctamente');
              }else {
                toastr.success('Estado Agregado Correctamente');
                me.cerrarModalAbrir();
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
    Utilerias.resetObject(this.catalogo);
  },

  cerrarModalAbrir(){
    this.$emit('cerrarAbrir');
  },

  abrirModal(modelo, accion, data = []){
    switch(modelo){
      case "catalogo":
      {
        switch(accion){
          case 'registrar':
          {
            this.modal = 1;
            this.desabilitado = 1;
            this.tituloModal = 'Registrar Catalogo';
            Utilerias.resetObject(this.catalogo);
            // this.codigo.unidad_medida='Catalogo';
            this.tipoAccion = 1;
            break;
          }
          case 'actualizar':
          {
            this.modal=1;
            this.desabilitado = 1;
            this.tituloModal='Actualizar Catalogo';
            this.catalogo.id=data['id'];
            this.tipoAccion=2;
            this.catalogo.descripcion = data['descripcion'];
            this.catalogo.codigo = data['codigo'];
            this.catalogo.marca = data['marca'];
            this.catalogo.comentario = data['comentario'];
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
