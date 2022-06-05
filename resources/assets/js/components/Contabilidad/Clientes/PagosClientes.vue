<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Pagos Clientes
          <button type="button" @click="abrirModal('pagos','registrar')" class="btn btn-primary float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>

          <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
            <template slot="id" slot-scope="props">
              <button type="button" @click="abrirModal('pagos','actualizar',props.row)" class="btn btn-warning btn-sm">
                <i class="icon-pencil"></i>
              </button>
            </template>
            <template slot="tipo_dias" slot-scope="props">
              <template v-if="props.row.tipo_dias == 1">
                Naturales
              </template>
              <template v-if="props.row.tipo_dias == 2">
              Hábiles
              </template>
            </template>
            <template slot="condicion" slot-scope="props">
              <template v-if="props.row.condicion == 1">
                <button type="button" class="btn btn-success btn-sm" @click="pagado(props.row.id)">
                  <i class="far fa-square"></i></button>
              </template>
              <template v-if="props.row.condicion == 0">
                <button type="button" class="btn btn-success btn-sm" disabled>
                  <i class="far fa-check-square"></i></button>
              </template>
            </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-primary modal-lg" role="document">
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
                <label for="inputCity">Banco</label>
                <select id="banco_contabilidad_id" name="banco_contabilidad_id" v-model="pagos.banco_contabilidad_id" class="form-control"
                data-vv-name="banco" v-validate="'required'" @change="buscarBanco(pagos.banco_contabilidad_id)" >
                  <option v-for="item in listaBancos" :value="item.id" :key="item.id">{{ item.nombre}}</option>
                </select>
                <input type="text" class="form-control" v-model="pagos.banco_no_cuenta" placeholder="No. Cuenta" disabled>
                <span class="text-danger">{{ errors.first('banco') }}</span>

              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label > Fecha Entrega</label>
                <input type="date" class="form-control"  v-validate="'required'" v-model="pagos.fecha_entrega" data-vv-name="fecha entrega" >
                <span class="text-danger">{{ errors.first('fecha entrega') }}</span>
              </div>
              <div class="form-group col-md-4">
                <label > Fecha Emisión</label>
                <input type="date" class="form-control"  v-validate="'required'" v-model="pagos.fecha_emision" data-vv-name="fecha emisión" >
                <span class="text-danger">{{ errors.first('fecha emisión') }}</span>
              </div>
              <div class="form-group col-md-4">
                <label for="inputCity">Fecha corte</label>
                <input type="date" class="form-control"  v-validate="'required'" v-model="pagos.fecha_corte" data-vv-name="fecha corte" >
                <span class="text-danger">{{ errors.first('fecha corte') }}</span>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-4">
                <label> Días crédito</label>
                 <input type="text" class="form-control"  v-validate="'required'" v-model="pagos.dias_credito" data-vv-name="dias credito" placeholder="Dias de crédito">
                 <span class="text-danger">{{ errors.first('dias credito') }}</span>
              </div>
              <div class="form-group col-md-4">
                <label> Tipo Días</label>
                <select class="form-control" name="" v-model="pagos.tipo_dias" data-vv-name="tipo dias" v-validate="'required'"  >
                  <option value="1">Naturales</option>
                  <option value="2">Hábilies</option>
                </select>
                <span class="text-danger">{{ errors.first('tipo dias') }}</span>
              </div>
                <div class="form-group col-md-4">
                  <label> Total</label>
                   <input type="text" class="form-control"  v-validate="'required'" v-model="pagos.total" data-vv-name="total" placeholder="Total">
                   <span class="text-danger">{{ errors.first('total') }}</span>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="guardar(1)">Guardar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="guardar(0)">Actualizar</button>
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
      tituloModal : '',
      modal : 0,
      columns : ['id','nombre_cliente','nombre_banco','fecha_entrega','fecha_corte','fecha_emision','dias_credito','tipo_dias','total','condicion'],
      tableData : [],
      tipoAccion : 0,
      optionsvs: [],
      isLoading : false,
      listaBancos: [],
      listaClientes: [],
      pagos : {
        id : 0,
        cliente_id : '',
        banco_contabilidad_id : '',
        fecha_entrega : '',
        fecha_emision : '',
        dias_credito: '',
        tipo_dias: '',
        fecha_corte: '',
        total : '',
        banco_no_cuenta: '',
      },
      options: {
        headings: {
         id: 'Acción',
         nombre_cliente : 'Cliente',
         nombre_banco : 'Banco',
         dias_credito : 'Días crédito',
         tipo_dias : 'Tipo',
         fecha_emision : 'Fecha emisión',
         condicion : 'Pagado'
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
  methods : {
    /**
     * [getData Obtencion de datos por medio de peticiones axios al controlador]
     * @return {Response} [respuesta almacenada en diferentes variables]
     */
     getData(){
       let me = this;
       axios.get('/registropagos').then(response => {
         me.tableData = response.data;
       })
       .catch(function (error) {
         console.log(error);
       });
       axios.get('/bancoscontabilidad').then(response => {
         me.listaBancos = response.data;
       })
       .catch(function (error) {
         console.log(error);
       });
       axios.get('/clientes').then(response => {
         me.listaClientes = response.data;
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
               // console.log(data);
              Utilerias.resetObject(this.pagos);
              this.modal = 1;
              this.tituloModal='Actualizar Pago';
              this.tipoAccion = 2;
              this.pagos.id = data['id'];
              this.pagos.cliente_id = data['cliente_id'];
              this.pagos.banco_contabilidad_id = data['banco_contabilidad_id'];
              this.pagos.fecha_entrega = data['fecha_entrega'];
              this.pagos.fecha_emision = data['fecha_emision'];
              this.pagos.fecha_corte = data['fecha_corte'];
              this.pagos.dias_credito = data['dias_credito'];
              this.pagos.tipo_dias = data['tipo_dias'];
              this.pagos.total = data['total'];
              this.pagos.banco_no_cuenta = data['numero_cuenta'];

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
            url: nuevo ? '/registropagos' : '/registropagos/'+this.pagos.id,
            data: {
              'cliente_id': this.pagos.cliente_id,
              'banco_contabilidad_id': this.pagos.banco_contabilidad_id,
              'fecha_entrega': this.pagos.fecha_entrega,
              'fecha_emision': this.pagos.fecha_emision,
              'dias_credito' : this.pagos.dias_credito,
              'tipo_dias' : this.pagos.tipo_dias,
              'fecha_corte' : this.pagos.fecha_corte,
              'total' : this.pagos.total,
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
  }
}
</script>
