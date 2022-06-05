<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Catálogo
          <button type="button" @click="abrirModal('catalogo','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
            <template slot="estado" slot-scope="props" >
                <template v-if="props.row.estado == 0">
                <button type="button" class="btn btn-outline-danger">Desactivado</button>
                </template>
                <template v-if="props.row.estado == 1">
                 <button type="button" class="btn btn-outline-success">Activo</button>
                </template>
            </template>
              <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <template v-if="props.row.estado == 1">
                      <button v-show="PermisosCrud.Delete" class="dropdown-item" @click="actdesact(props.row.id,0)" >
                          <i class="fas fa-flag"></i>&nbsp; Desactivar
                      </button>
                  </template>
                  <template v-if="props.row.estado == 0">
                      <button v-show="PermisosCrud.Delete" class="dropdown-item" @click="actdesact(props.row.id,1)" >
                          <i class="fas fa-flag"></i>&nbsp; Activar
                      </button>
                  </template>
                  <button v-show="PermisosCrud.Update && props.row.estado == 1" type="button" @click="abrirModal('catalogo','actualizar',props.row)" class="dropdown-item" >
                      <i class="icon-pencil"></i>&nbsp; Actualizar
                  </button>
              </div>

              </div>
              </div>
              </template>
          </v-client-table>
        </div>
      </div>

      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Nombre</label>
                    <input type="text" name="nombre"  class="form-control" placeholder="Nombre" v-model="nombre"
                    autocomplete="off" data-vv-name="Nombre" >
                    <span class="text-danger">{{ errors.first('Nombre') }}</span>
                  </div>
                </div>
                <div class="form-group" >
                  SubCatálogo
                  <label class="switch switch-default switch-pill switch-dark">
                    <input type="checkbox" class="switch-input" id="sub" name="customRadioInline1" v-model="chekc" @change="estado($event)">
                    <span class="switch-label"></span>
                    <span class="switch-handle"></span>
                  </label>
                </div>
                <template v-if="sub == 1">
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label>Pertenece a</label>
                      <select class="form-control" v-model="catalogo_id" >
                        <option v-for="t in principal" :value="t.id"  :key="t.id">{{t.nombre}}</option>
                      </select>
                    </div>
                  </div>
                </template>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-ouline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarCat(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarCat(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal-->

    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default{
  data(){
    return{
      PermisosCrud : {},
      principal : [],
      modal : 0,
      sub : 0,
      id : 0,
      nombre : '',
      catalogo_id : '',
      chekc : false,
      tituloModal : '',
      tipoAccion : 0,
      columns: ['id','nombre','nombre_sub','estado'],
      tableData: [],
      options: {
          headings: {
              'id': 'Acciones',
              'nombre_sub' : 'Pertenece',

          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['nombre','nombre_sub'],
          filterable: ['nombre','nombre_sub'  ],
          filterByColumn: true,
          listColumns: {
            'estado': [{
                id: 1,
                text: 'Activo'
            },
            {
                id: 0,
                text: 'Desactivado'
            },
            ]
          },
          texts:config.texts
      },
    }
  },
  methods :{
    getData(){
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path)

      axios.get('catalogos/centrocostos').then(response => {
        this.tableData = response.data;
      }).catch(error => {
        console.error(error);
      });

      axios.get('catalogos/centrocostos/show').then(response => {
        this.principal = response.data;
      }).catch(error => {
        console.error(error);
      });
    },

    abrirModal(modelo, accion, data = []){
      switch(modelo){
        case "catalogo":
        {
          switch(accion){
            case 'registrar':
            {
              this.reset();
              this.modal = 1;
              this.tituloModal = "Registrar Catálogo";
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar':
            {
              this.reset();
              this.modal = 1;
              this.tituloModal = "Actualizar Catálogo";
              this.tipoAccion = 2;
              this.id = data['id'];
              this.nombre = data['nombre'];
              this.sub = data['sub'];
              if (data['sub'] == 1) {
                this.check = true;
                this.catalogo_id = data['catalogo_centro_costos_id'];
                $("#sub").prop("checked", true);
              }
              break;
            }
          }
        }
      }
    },

    estado(e){
      e.target.checked == false ? this.sub = 0 : this.sub = 1;
    },

    cerrarModal(){
      this.modal = 0;
    },

    guardarCat(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          let me = this;
          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'catalogos/centrocostos/registrar' : 'catalogos/centrocostos/actualizar/' + this.id,
            data : {
              id : this.id,
              nombre : this.nombre,
              catalogo_centro_costos_id : this.catalogo_id,
              sub : this.sub,
            }
          }).then(response => {
            me.cerrarModal();
            me.getData();
          }).catch(error => {
            console.error(error);
          })
        }
      });
    },

    reset(){
      this.id = 0;
      this.nombre = '';
      this.catalogo_id = '';
      this.sub = 0;
      this.chekc = false;
      $("#sub").prop("checked", false);
    },

    actdesact(id, edo){
      axios.get(`activar/desactivar/catalogo/${id}&${edo}`).then(response => {
        this.getData();
      }).catch(error => {
        console.error(error);
      })
    },

  },
  mounted(){
    this.getData();
  }
}

</script>
