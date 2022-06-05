<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Impuestos
          <button type="button" @click="abrirModal('impuestos','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">

          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">

            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" @click="abrirModal('impuestos','actualizar',props.row)" class="dropdown-item" >
                      <i class="icon-pencil"></i>&nbsp; Actualizar
                    </button>
                    <button type="button" @click="eliminar(props.row.id)" class="dropdown-item" >
                      <i class="icon-trash"></i>&nbsp; Eliminar
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
              <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="tipo">Tipo</label>
                  <div class="col-md-9">
                    <select class="form-control" v-model="impuesto.tipo"  data-vv-name="Tipo">
                      <option value="Retenidos">Retenidos</option>
                      <option value="Trasladados">Trasladados</option>
                    </select>
                    <span class="text-danger">{{ errors.first('Tipo') }}</span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                  <div class="col-md-9">
                    <input type="text" v-validate="'required|max:20'" name="nombre" v-model="impuesto.nombre" class="form-control" placeholder="Nombre" data-vv-name="Nombre" autocomplete="off" >
                    <span class="text-danger">{{ errors.first('Nombre') }}</span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="porcentaje">Porcentaje</label>
                  <div class="col-md-9">
                    <input type="text" v-validate="'required|max:6'" name="porcentaje" v-model="impuesto.porcentaje" class="form-control" placeholder="Porcentaje" data-vv-name="Porcentaje" autocomplete="off" >
                    <span class="text-danger">{{ errors.first('Porcentaje') }}</span>
                  </div>
                </div>

              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarimpuestos(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarimpuestos(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
import utilerias from "../../Herramientas/utilerias";
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      PermisosCRUD : {},
      impuesto: {
        id: 0,
        tipo : '',
        nombre : '',
        porcentaje : '',
      },
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: ['id', 'tipo', 'nombre', 'porcentaje'],
      tableData: [],
      options: {
        headings: {
          id: 'Acciones',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: [ 'tipo', 'nombre', 'porcentaje'],
        filterable: [ 'tipo', 'nombre', 'porcentaje'],
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
      axios.get('/facturaimpuestos').then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    guardarimpuestos(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/facturaimpuestos/registrar' : '/facturaimpuestos/actualizar',
            data: {
              'tipo': this.impuesto.tipo,
              'nombre': this.impuesto.nombre,
              'porcentaje': this.impuesto.porcentaje,
              'id': this.impuesto.id
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              me.getData();
              if(!nuevo){
                toastr.success('Impuesto Actualizado Correctamente');
              }
              else
              {
                toastr.success('Impuesto Registrado Correctamente');

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
      Utilerias.resetObject(this.impuesto);
    },
    abrirModal(modelo, accion, data = []){
      switch(modelo){
        case "impuestos":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Registrar Impuestos';
              Utilerias.resetObject(this.impuesto);
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar':
            {
              this.modal=1;
              this.tituloModal='Actualizar Impuestos';
              this.tipoAccion=2;
              this.impuesto.id=data['id'];
              this.impuesto.tipo = data['tipo'];
              this.impuesto.nombre= data['nombre'];
              this.impuesto.porcentaje= data['porcentaje'];
              break;
            }
          }
        }
      }
    },

    eliminar(id){
      axios.get(`/facturaimpuestos/eliminar/${id}`).then(response =>{
        this.getData();
      }).catch(error =>{
        console.error(error);
      })
    }
  },
  mounted() {
    this.getData();
  }
}
</script>
