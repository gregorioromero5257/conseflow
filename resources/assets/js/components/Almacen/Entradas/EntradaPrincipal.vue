<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <vue-element-loading :active="isLoading" />
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Entradas
          <button type="button" @click="abrirModal('entrada','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-server-table :columns="columns" :url="'entrada'" :options="options" ref="myTablePrincipal">
            <template slot="fecha" slot-scope="props">
              {{fecha(props.row.fecha)}}
            </template>
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp;
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <template>
                      <!-- <button type="button" class="dropdown-item" @click="descargar(props.row)">
                        <i class="fas fa-file-pdf"></i>&nbsp;Descargar Entrada
                      </button> -->
                    </template>
                    <template>
                      <button type="button" @click="cargardetalle(props.row)" class="dropdown-item">
                        <i class="fas fa-eye"></i>&nbsp; Agregar Partidas
                      </button>
                      <button type="button" @click="cargardetalleliminar(props.row)" class="dropdown-item">
                        <i class="fas fa-trash"></i>&nbsp; Actualizar Partidas
                      </button>
                    </template>
                    <button type="button" @click="abrirModal('entrada','actualizar',props.row)" class="dropdown-item">
                      <i class="icon-pencil"></i>&nbsp;Actualizar
                    </button>
                    <template v-if="props.row.condicion">
                      <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 0)">
                        <i class="icon-trash"></i>&nbsp;Desactivar
                      </button>
                    </template>
                    <template v-else>
                      <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 1)">
                        <i class="icon-check"></i>&nbsp; Activar
                      </button>
                    </template>
                  </div>
                </div>
              </div>
            </template>
            <template slot="descarga" slot-scope="props">
              <button type="button" class="btn btn-outline-dark" @click="descargarnuevo(props.row)">
                <i class="fas fa-download"></i>&nbsp;<i class="fas fa-file-pdf"></i>
              </button>
            </template>
            <template slot="condicion" slot-scope="props">
              <template v-if="props.row.condicion == 1">
                <button type="button" class="btn btn-outline-success">Activo</button>
              </template>
              <template v-if="props.row.condicion == 0">
                <button type="button" class="btn btn-outline-danger">Dado de Baja</button>
              </template>
            </template>
            <template slot="estado" slot-scope="props">
              <template v-if="props.row.estado == 1">
                <button type="button" class="btn btn-outline-info">Nuevo</button>
              </template>
              <template v-if="props.row.estado == 2">
                <button type="button" class="btn btn-outline-success">Finalizada</button>
              </template>
              <template v-if="props.row.estado == 3">
                <button type="button" class="btn btn-outline-warning">En revisión</button>
              </template>

            </template>
          </v-server-table>
        </div>
      </div>

      <!--Inicio del modal agregar/actualizar Entradas-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <vue-element-loading :active="isLoading" />
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
                <div class="form-group row" v-show="tipoAccion != 2">
                  <label class="col-md-3 form-control-label" for="orden_compra_id">Orden de compra</label>
                  <div class="col-md-9">
                    <v-select id="orden_compra" :options="listaOC"  v-validate="'required'"
                    v-model="orden_compra" label="folio" name="folio"
                    data-vv-name="folio" >
                  </v-select>
                  <span class="text-danger">{{ errors.first('orden_compra_id') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
                <div class="col-md-9">
                  <input type="date" v-validate="'required'" name="fecha" v-model="entrada.fecha" class="form-control" placeholder="Fecha de Entrada" autocomplete="off" id="fecha">
                  <span class="text-danger">{{ errors.first('fecha') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="comentarios">Comentarios</label>
                <div class="col-md-9">
                  <input type="text" name="comentarios" v-model="entrada.comentarios" class="form-control" placeholder="Comentarios" autocomplete="off" id="comentarios">
                  <span class="text-danger">{{ errors.first('comentarios') }}</span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarEntrada(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarEntrada(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--Fin del modal Entradas-->

    <div v-show="modales == 1">
      <modalpartidas ref="modalpartidas" @atras:modalpartidas="cerrarmodalpartidas()"></modalpartidas>
    </div>

    <div v-show="modales == 2">
      <modalpartidasconsulta ref="modalpartidasconsulta" @atras:modalpartidasconsulta="cerrarmodalpartidasconsulta()"></modalpartidasconsulta>
    </div>



  </div>
</main>
</template>


<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const ModalPartidas = r => require.ensure([], () => r(require('./ModalPartidas.vue')), 'alm');
const ModalPartidasConsulta = r => require.ensure([], () => r(require('./ModalPartidasConsulta.vue')), 'alm');

export default {
  data() {
    return {
      modales : 0,
      modal : 0,
      tituloModal :  '',
      isLoading : false,
      tipoAccion : 0,
      orden_compra: {},
      columns: ['id', 'foliocompra', 'fecha', 'comentarios', 'descarga' ,'condicion', 'estado'],
      tableData: [],
      options: {
        headings: {
          'id': 'Acciones',
          'fecha': 'Fecha',
          'foliocompra': 'Folio',
          'comentarios': 'Comentarios',
          'formato_entrada': 'Formato de entrada',
          'condicion': 'Condición',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['foliocompra', 'fecha', 'comentarios'],
        filterable: ['foliocompra', 'fecha', 'comentarios'],
        filterByColumn: true,
        texts: config.texts,
        requestAdapter : function (data) {
          var arr = [];
          // console.log(data);
           arr.push({
           'OC.folio' : data.query.foliocompra,
           'fecha' : data.query.fecha,
           'comentarios' : data.query.comentarios,
         });
          data.query = arr[0];
          return data;
        },
      },
      entrada: {
        id: 0,
        fecha: '',
        comentarios: '',
        condicion: 0,
        disabled: true,
        orden_compra_id: '',
      },
      empleado_responsable : 0,
      disabled: 0,
      listaOC: [],
      listaAlmacenes: [],

    }
  },
  components: {
    'modalpartidas': ModalPartidas,
    'modalpartidasconsulta': ModalPartidasConsulta,
  },
  methods: {
    cargardetalleliminar(data){
      this.entrada.id = data;
      this.modales = 2;
      var childpartidas = this.$refs.modalpartidasconsulta;
      childpartidas.cargar(data,this.orden_compra, this.listaAlmacenes);
    },

    cargardetalle(data){
        var orden_compra = {id : data.orden_compra_id, folio : data.foliocompra};
      this.modales = 1;
      var childpartidas = this.$refs.modalpartidas;
      childpartidas.cargar(data.id,orden_compra, this.listaAlmacenes);
    },

    /**
    * [getData Metodo de consulta a la BD ]
    * @return {Response} [description]
    */
    getData() {
      // this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
      this.isLoading = true
      let me = this;
      // axios.get('/entrada').then(response => {
      //   me.tableData = response.data;
      //   this.isLoading = false;
      // })
      // .catch(function (error) {
      //   console.log(error);
      // });

      axios.get('/Solicitud').then(response => {
        this.empleado_responsable = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getListas(){
      let me = this;
      axios.get('/almacen/ver').then(response => {
        me.listaAlmacenes = response.data;
        me.isLoading = false;
      })

      .catch(function (error) {
        console.log(error);
      });

    },
    /**
    *@return
    **/
    abrirModal(modelo, accion, data = []) {
      this.getArticulosEntrada();
      switch (modelo) {
        case "entrada": {
          switch (accion) {
            case 'registrar': {
              let me = this;
              Utilerias.resetObject(me.entrada);
              this.modal = 1;
              this.tituloModal = 'Registrar entrada';
              this.tipoAccion = 1;
              this.disabled = 0;
              break;
            }
            case 'actualizar': {
              let me = this;
              Utilerias.resetObject(me.entrada);
              this.modal = 1;
              this.tituloModal = 'Actualizar entrada';
              this.entrada.id = data['id'];
              this.tipoAccion = 2;
              this.disabled = 1;
              this.entrada.fecha = data['fecha'];
              this.entrada.comentarios = data['comentarios'];
              this.entrada.orden_compra_id = data['orden_compra_id'];
              break;
            }
          }
        }
      }
    },

    /**
    * [getArticulosEntrada Metodo de consulta a la BD ]
    * @return {Response} [objeto almacenado en la variable dataTableArticulo]
    */
    getArticulosEntrada() {
      let me = this;
      axios.get('/verordenescompras').then(response => {
        me.listaOC = [];
        response.data.forEach(o => {
          me.listaOC.push({
            id: o.id,
            folio: `Folio: ${o.folio}`,
          });
        });
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    /**
    * @return {Response} [se obtiene la]
    */
    fecha(fecha){
      const meses = [
        "Enero", "Febrero", "Marzo",
        "Abril", "Mayo", "Junio", "Julio",
        "Agosto", "Septiembre", "Octubre",
        "Noviembre", "Diciembre"
      ];

      const date = new Date(fecha);
      const dia = date.getDate() + 1;
      const mes = date.getMonth();
      const ano = date.getFullYear();
      return `${dia} de ${meses[mes]} del ${ano}`;
    },

    descargar(data) {
      window.open('descargar-entrada/' + data.id, '_blank');
    },

    descargarnuevo(data) {
      window.open('descargar-entrada-nuevo-formato/' + data.id, '_blank');
    },

    /**
    * [cerrarModal description]
    */
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = '';
    },

    /**
    * [guardarEntrada Metodo que almacen o actualiza una entrada]
    * @param  {Int} nuevo [1 = si y 0 = no]
    * @return {Response}       [status = true]
    */
    guardarEntrada(nuevo) {
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? 'entrada' : 'entrada/' + this.entrada.id,
            data: {
              'id': this.entrada.id,
              'fecha': this.entrada.fecha,
              'comentarios': this.entrada.comentarios,
              'orden_compra_id': me.orden_compra.id
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              if (!nuevo) {
                me.cerrarModal();
                me.getData();
                toastr.success('Entrada Actualizada Correctamente');
              } else {
                toastr.success('Entrada Registrada Correctamente');
                me.registrarPartidaEntrada(response.data.id_entrada);
              }
              me.$refs.myTablePrincipal.refresh();
            } else {
              swal({
                type: 'error',
                html: response.data.errors,
              });
            }
          }).catch(function (error) {
            console.log(error);
          });
        }
      });
    },

    registrarPartidaEntrada(data){
      this.entrada.id = data;
      this.modales = 1;
      var childpartidas = this.$refs.modalpartidas;
      childpartidas.cargar(data,this.orden_compra, this.listaAlmacenes);
    },

    cerrarmodalpartidas(){
      this.modales = 0;
      this.modal = 0;
      this.tituloModal = '';
      this.orden_compra = {};
    },

    cerrarmodalpartidasconsulta(){
      this.modales = 0;
      this.modal = 0;
      this.tituloModal = '';
      this.orden_compra = {};
    },

  },

  mounted() {
    this.getData();
    this.getListas();
  }
}
</script>
