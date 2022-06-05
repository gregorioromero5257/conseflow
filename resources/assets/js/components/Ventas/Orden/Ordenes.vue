<template>
    <main class="main">
    <div class="container-fluid">
      <br>
      <div class="card" v-show="detalle == 1">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Ordenes de venta
          <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal('venta','registrar')">
            <i class="fas fa-plus">&nbsp;</i>Nuevo
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>

          <v-client-table :data="tableData" :columns="columns" :options="options">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
               <div class="btn-group dropup" role="group">
                 <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="fas fa-grip-horizontal"></i> Acciones
                 </button>
                 <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                   <!-- v-show="PermisosCrud.Update" -->
              <button  type="button" @click="abrirModal('venta','actualizar',props.row)" class="dropdown-item">
                <i class="icon-pencil"></i>&nbsp; Actualizar Orden Venta
              </button>
              <button  type="button" @click="agregar(props.row)" class="dropdown-item">
                <i class="fas fa-list-ol"></i>&nbsp; Agregar Conceptos
              </button>
              <button  type="button" v-show="props.row.timbrado == 0" @click="timbrar(props.row)" class="dropdown-item">
                <i class="fas fa-bell"></i>&nbsp; Timbrar
              </button>
              <button  type="button" v-show="props.row.timbrado == 1" @click="descargar(props.row)" class="dropdown-item">
                <i class="fas fa-download"></i>&nbsp; Descargar
              </button>
            </div>
          </div>
        </div>
            </template>
            <template slot="timbrado" slot-scope="props">
              <template v-if="props.row.timbrado == 0">
                <button type="button" class="btn btn-outline-info"><i class="fa fa-files-o"></i>&nbsp;Nuevo</button>
              </template>
              <template v-if="props.row.timbrado == 1">
                <button type="button" class="btn btn-outline-success"><i class="fa fa-exclamation-circle"></i>&nbsp;Timbrado</button>
              </template>
            </template>
          </v-client-table>

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
                  <div class="form-group col-md-3">
                  <label>Serie </label>
                  <input type="text" class="form-control" v-model="oventa.serie"
                  placeholder="Serie" readonly>
                  </div>
                  <div class="form-group col-md-3">
                  <label>Folio </label>
                  <input type="text" class="form-control" v-model="oventa.folio"
                  placeholder="Folio" readonly>
                  </div>
                  <div class="form-group col-md-6">
                  <label>Fecha </label>
                  <input type="date" class="form-control" v-model="oventa.fecha"
                  placeholder="Fecha">
                  </div>
              </div>

              <div class="form-row">
                  <div class="form-group col-md-6">
                  <label>Cliente </label>
                  <select class="form-control" v-model="oventa.cliente_id" @change="llenarDatosCliente()">
                      <option v-for="item in Clientes" :value="item.id" :key="item.id">{{item.nombre}}</option>
                  </select>
                  </div>
                  <div class="form-group col-md-6">
                  <label>RFC receptor </label>
                  <input type="text" class="form-control" v-model="Receptor.rfc"
                  placeholder="RFC receptor" readonly>
                  </div>
              </div>
              <div class="form-row">
                  <div class="form-group col-md-12">
                  <label>Nombre o razon social</label>
                  <input type="text" class="form-control" v-model="Receptor.nombre"
                  placeholder="Nombre o razon social" readonly>
                  </div>
              </div>

            </div>
            <div class="modal-footer">
              <vue-element-loading :active="isLoading"/>
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
            </div>
          </div> <!-- /.modal-content -->
        </div> <!-- /.modal-dialog -->
      </div> <!-- Fin modal -->

      </div> <!-- Fin card -->

      <div v-show="detalle == 2">
        <conceptos ref="conceptos" @maestro:conceptos="maestro()"></conceptos>
      </div>

    </div> <!-- Fin container fluid -->
  </main>
</template>
<script type="javascript">
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Conceptos = r => require.ensure([], () => r(require('./Conceptos.vue')), 'conta');

export default {
  data(){
    return{
        modal: 0,
        tituloModal: '',
        tipoAccion: 0,
        isLoading: false,
        detalle: 1,
        tableData : [],
        columns : ['id','serie','folio','rfc_receptor','razon_social','fecha','estado'],
        options: {
            headings: {
                'id' : 'Acciones',
                'rfc_receptor' : 'RFC Receptor',
                'razon_social' : 'Nombre o razón social receptor',
                'fecha' : 'Fecha venta',
                'estado' : 'Estado',
            },
            perPage: 10,
            perPageValues: [],
            skin: config.skin,
            sortIcon: config.sortIcon,
            filterByColumn: true,
            texts:config.texts
        },
        oventa: {
            cliente_id: '',
        },
        Clientes: [],
        Receptor: {
            rfc: '',
            nombre: '',
        },
    }
  },
  methods: {

    getData() {
        axios.get('/ordenventa').then(response => {
            this.tableData = response.data;
        }).catch(error => {console.error(error);});

        axios.get('/clientes').then(response => {
            this.Clientes = response.data;
        }).catch(error => {console.error(error);});
    },

    llenarDatosCliente(){
      axios.get('/clientes/' + this.oventa.cliente_id).then(response => {
        this.Receptor.rfc = response.data.rfc;
        this.Receptor.nombre = response.data.nombre;
        this.Receptor.contacto = response.data.contacto;
        // console.log(response.data);
      }).catch(error => {
        console.error(error);
      });
    },

    abrirModal(modelo, accion, data = [])
    {
      switch(modelo){
        case "venta":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal= 1;
              this.tituloModal = 'Registrar orden venta';
              this.tipoAccion=1;
              Utilerias.resetObject(this.oventa);
              Utilerias.resetObject(this.Receptor);
              break;
            }
            case 'actualizar':
            {
              
              this.modal= 1;
              this.tituloModal = 'Actualizar orden venta';
              this.tipoAccion=2;
              this.oventa.id = data['id'];
              this.oventa.cliente_id = data['cliente_id'];
              this.Receptor.rfc = data['rfc_receptor'];
              this.Receptor.nombre = data['razon_social'];
              this.oventa.folio = data['folio'];
              this.oventa.fecha = data['fecha'];
              this.oventa.serie = data['serie'];
              break;
            }
          }
        }
      }
    },

    guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/ordenventa/registrar' : '/ordenventa/actualizar',
            data: {
              id : this.oventa.id,
              cliente_id : this.oventa.cliente_id,
              fecha : this.oventa.fecha,
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              toastr.success(nuevo ? 'Orden de venta agregada correctamente' : 'Orden de venta actualizada correctamente','Correcto');
              me.getData();
            }else {
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
      this.modal = 0;
      Utilerias.resetObject(this.oventa);
      Utilerias.resetObject(this.Receptor);
    },

    agregar(data) {
      this.detalle = 2;
      var childConceptos = this.$refs.conceptos;
      childConceptos.getData(data);
    },

    maestro(){
      this.detalle = 1;
    },

  },
  components : {
    'conceptos' : Conceptos,
  },
  mounted(){
    this.getData();
  }
}
</script>