<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Pagos
          <button type="button" @click="abrirModal('pagos','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>

          <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" @click="abrirModal('pagos','actualizar',props.row)" class="dropdown-item">
                      <i class="fas fa-pencil-alt"></i>&nbsp; Actualizar Pago
                    </button>
                    <button type="button" @click="eliminar(props.row.id)" class="dropdown-item">
                      <i class="fas fa-trash"></i>&nbsp; Eliminar
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
              <div class="form-group col-md-6">
                <label> Cliente</label>
                <select class="form-control" v-validate="'required'" v-model="pagos.cliente_id" data-vv-name="cliente">
                  <option v-for="item in listaClientes" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                </select>
                <span class="text-danger">{{ errors.first('cliente') }}</span>
              </div>
              <div class="form-group col-md-6">
                <label >Proyecto:</label>
                <v-select :options="listaProyectos" label="name" v-model="pagos.proyectoId"></v-select>

              </div>
            </div>


            <div class="form-row">
              <div class="form-group col-md-4">
                <label > Fecha</label>
                <input type="date" class="form-control"  v-validate="'required'" v-model="pagos.fecha" data-vv-name="fecha" >
                <span class="text-danger">{{ errors.first('fecha') }}</span>
              </div>
              <div class="form-group col-md-4">
                <label > Concepto</label>
                <input type="text" class="form-control"  v-validate="'required'" v-model="pagos.concepto" data-vv-name="concepto" >
                <span class="text-danger">{{ errors.first('concepto') }}</span>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Pago USD</label>
                <input type="text" class="form-control" v-validate="'decimal:2'" v-model="pagos.pago_usd" data-vv-name="Pago USD" placeholder="Pago USD">
                <span class="text-danger">{{ errors.first('Pago USD') }}</span>
              </div>
              <div class="form-group col-md-4">
                <label> Tipo cambio</label>
                <input type="text" class="form-control" v-validate="'decimal:2'" v-model="pagos.tipo_cambio" data-vv-name="Tipo cambio" placeholder="Tipo cambio">
                <span class="text-danger">{{ errors.first('tipo dias') }}</span>
              </div>
              <div v-show="ver == 1">
                {{total_mnx}}
              </div>
              <div class="form-group col-md-4">
                <label> Total</label>
                <input type="text" class="form-control"  v-validate="'required'" v-model="pagos.abonos" data-vv-name="total" placeholder="Total">
                <span class="text-danger">{{ errors.first('total') }}</span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
  computed:{

  },
  data (){
    return {
      ver : 0,
      PermisosCrud : {},
      tituloModal : '',
      modal : 0,
      columns : ['id','fecha','concepto','pago_usd','tipo_cambio','abonos','nombre_corto','nombre_cliente'],
      tableData : [],
      tipoAccion : 0,
      optionsvs: [],
      isLoading : false,
      listaBancos: [],
      listaClientes: [],
      listaProyectos : [],
      pagos : {
        id : 0,
        cliente_id : '',
        fecha : '',
        concepto : '',
        pago_usd : '',
        tipo_cambio: '',
        abonos: '',
        proyectoId : '',
      },
      options: {
        headings: {
          id: 'Acciones',
          proyecto_id : 'Proyecto',
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
    total_mnx(){
      var total = 0;
      total = parseFloat(this.pagos.pago_usd) * parseFloat(this.pagos.tipo_cambio);
      return this.pagos.abonos = total;
    }
  },
  methods : {
    /**
    * [getData Obtencion de datos por medio de peticiones axios al controlador]
    * @return {Response} [respuesta almacenada en diferentes variables]
    */
    getData(){
      let me = this;
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
      axios.get('/pagos/clientes').then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
      // axios.get('/bancoscontabilidad').then(response => {
      //   me.listaBancos = response.data;
      // })
      // .catch(function (error) {
      //   console.log(error);
      // });
      axios.get('/clientes').then(response => {
        me.listaClientes = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    /**
     * [getProyecto Obtiene todos los proyectos existentes]
     * @return {[type]} [description]
     */
    getProyecto() {
      let me=this;
      axios.get('/proyecto').then(response => {
        response.data.forEach(value =>{
          me.listaProyectos.push({
            id : value.proyecto.id,
            name : value.proyecto.nombre_corto
          });
        });
        // me.listaProyectos = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    /**
    * [buscarBanco description]
    * @param  {Int} id [description]
    * @return {Response}    [description]
    */
    buscarBanco(id)
    {
      let me= this;
      axios.get('/bancoscontabilidad/'+id).then(response =>{
        me.pagos.banco_no_cuenta = response.data.numero_cuenta;
      }).catch(function (error) {
        console.log(error);
      });
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
        case "pagos":
        {
          switch(accion){
            case 'registrar':
            {
              Utilerias.resetObject(this.pagos);
              this.modal= 1;
              this.tituloModal = 'Registrar Pago';
              this.tipoAccion=1;
              break;
            }
            case 'actualizar':
            {
              Utilerias.resetObject(this.pagos);
              this.modal = 1;
              this.tituloModal='Actualizar Pago';
              this.tipoAccion = 2;
              this.pagos.id = data['id'];
              this.pagos.cliente_id = data['cliente_id'];
              this.pagos.fecha = data['fecha'];
              this.pagos.concepto = data['concepto'];
              this.pagos.pago_usd = data['pago_usd'];
              this.pagos.tipo_cambio = data['tipo_cambio'];
              this.pagos.abonos = data['abonos'];
              this.pagos.proyectoId = {id : data['id_proyecto'], name : data['nombre_corto']};
              break;
            }
          }
        }
      }
    },

    cerrarModal()
    {
      this.modal = 0;
    },

    eliminar(id){
      axios.get('/pagos/clientes/delete/' + id).then(response =>{
        this.getData();
      }).catch(e => {
        console.error(e);
      });
    },

    /**
    * [guardar description]
    * @param  {Int} nuevo [description]
    * @return {Response}       [description]
    */
    guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/pagos/clientes/store' : '/pagos/clientes/update',
            data: {
              'id': this.pagos.id,
              'cliente_id': this.pagos.cliente_id,
              'fecha': this.pagos.fecha,
              'concepto': this.pagos.concepto,
              'pago_usd' : this.pagos.pago_usd,
              'tipo_cambio' : this.pagos.tipo_cambio,
              'abonos' : this.pagos.abonos,
              'proyecto_id' : this.pagos.proyectoId.id,
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              me.getData();
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
    pagado(id)
    {
      let me= this;
      axios.get('/clientepago/'+id).then(response => {
        me.getData();
        toastr.success('Pago Realizado Correctamente');

      }).catch(function (error) {
        console.log(error);
      });
    },
  },
  mounted() {
    this.getData();
    this.getProyecto();
  }
}
</script>
