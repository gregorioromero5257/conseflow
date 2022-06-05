<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Pagos prestamos
        </div>
        <div class="card-body">

          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="empleado.id" slot-scope="props">
              <button type="button" @click="cargarprestamo(props.row.empleado)" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i>
              </button>
            </template>
          </v-client-table>

        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
      <!-- Listado empleado -->
      <br>
      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Pagos pertenecientes a:  {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
          <button type="button" @click="abrirModal('prestamos','registrar',empleado.id)" class="btn btn-primary float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
          <button type="button" @click="maestro()" class="btn btn-primary float-sm-right">
            <i class="fas fa-backward"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoadingDetalle"/>
          <v-client-table :columns="columnsprestamo" :data="tableDataprestamo" :options="optionsprestamo" ref="myTableprestamo">

            <template slot="id" slot-scope="props">
              <button type="button" @click="abrirModal('prestamos','actualizar',empleado.id,props.row)" class="btn btn-warning btn-sm">
                <i class="icon-pencil"></i>
              </button>
            </template>
          </v-client-table>

        </div>
      </div>
      <!-- Fin Listado empleado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <vue-element-loading :active="isLoading"/>
      <div class="modal-dialog modal-primary modal-lg" role="document">
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
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="prestamo_id">Prestamo</label>
                <div class="col-md-9">
                  <select class="form-control" id="prestamo_id"  name="prestamo_id" v-model="prestamo.prestamo_id" v-validate="'excluded:0'" data-vv-as="prestamo" >
                    <option v-for="item in listaprestamo" :value="item.id" :key="item.id"> prestamo de la fecha {{ item.fecha }}</option>
                  </select>
                  <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="fecha_pago">Fecha</label>
                <div class="col-md-9">
                  <input type="date" v-validate="'required'" name="fecha_pago" v-model="prestamo.fecha_pago" class="form-control" placeholder="Fecha" autocomplete="off" id="fecha_pago">
                  <span class="text-danger">{{ errors.first('fecha_pago') }}</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="cantidad_a_pagar">Cantidad</label>
                <div class="col-md-9">
                  <input type="number" v-validate="'required'" name="cantidad_a_pagar" v-model="prestamo.cantidad_a_pagar" class="form-control" placeholder="Cantidad" autocomplete="off" id="cantidad_a_pagar">
                  <span class="text-danger">{{ errors.first('cantidad_a_pagar') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="numero_pago">Numero de pago</label>
                <div class="col-md-9">
                  <input type="number" v-validate="'required'" name="numero_pago" v-model="prestamo.numero_pago" class="form-control" placeholder="Numero de Pago" autocomplete="off" id="numero_pago">
                  <span class="text-danger">{{ errors.first('numero_pago') }}</span>
                </div>
              </div>

              <!-- </form> -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="guardarprestamo(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="guardarprestamo(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
      url: '/pagoprestamo',
      empleado: null,
      detalle: false,
      prestamo: {
        id: 0,
        fecha_pago: '',
        cantidad_a_pagar : 0,
        numero_pago : 0,
        prestamo_id : 0,
        empleado_id :0,

      },

      listaEmpleados: [],
      listaprestamo: [],
      // listaTipoprestamo: [],
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      disabled: 0,
      isLoading: false,
      isLoadingDetalle: false,
      columns: [
        'empleado.id',
        'empleado.nombre',
        'empleado.ap_paterno',
        'empleado.ap_materno',
        'puesto.nombre',
        'departamento.nombre',
      ],
      tableData: [],
      columnsprestamo: [
        'id',
        'fecha_pago',
        'Pfecha',
        'numero_pago',
        'cantidad_a_pagar',

      ],
      tableDataprestamo: [],
      options: {
        headings: {
          'empleado.id': 'Acciones',
          'empleado.nombre': 'Nombre',
          'empleado.ap_paterno': 'Apellido Paterno',
          'empleado.ap_materno': 'Apellido Materno',
          'puesto.nombre': 'Puesto',
          'departamento.nombre': 'Departamento',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
        filterable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
        filterByColumn: true,
        texts:config.texts
      },
      optionsprestamo: {
        headings: {
          'id': 'Accion',
          'fecha_pago': 'Fecha de pago',
          'Pfecha': 'Fecha del prestamo',
          'Pcantidad': 'Cantidad',
          'numero_pago': 'Número de pago',
          'Ptotal': 'Total deuda',
          'cantidad_a_pagar': 'Cantidad pagada',


        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha_pago','Ptotal','Pfecha'],
        filterable: ['fecha_pago','Pfecha'],
        filterByColumn: true,
        listColumns: {
          condicion: config.columnCondicion
        },
        texts:config.texts
      },
    }
  },
  computed:{
  },
  methods : {
    getData() {
      let me=this;
      axios.get('/pagoprestamo').then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getLista() {
      let me=this;
      axios.get('/empleado').then(response => {
        me.listaEmpleados = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('/prestamo').then(response => {
        me.listaprestamo = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });



    },
    guardarprestamo(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            // url: nuevo ? me.url : '/pagoprestamo'+'/'+this.prestamo.id,
            url: nuevo ? me.url+'/'+this.prestamo.empleado_id+'/pagoprestamo' : me.url+'/'+this.prestamo.id+'/'+'pagoprestamo/'+this.prestamo.empleado_id,

            //url: nuevo ? me.url+'/'+this.prestamo.id+'/pagoprestamo' : me.url+'/'+this.prestamo.id+'/'+'pagoprestamo/'+this.prestamo.prestamo_id,
            data: {
              'id':this.prestamo.id,
              'fecha_pago':this.prestamo.fecha_pago,
              'cantidad_a_pagar':this.prestamo.cantidad_a_pagar,
              'numero_pago':this.prestamo.numero_pago,
              'prestamo_id':this.prestamo.prestamo_id,
              'empleado_id':this.prestamo.empleado_id

            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              me.cargarprestamo(me.empleado);
              if(!nuevo){
                toastr.success('Pago Actualizado Correctamente');
              }
              else
              {
                toastr.success('Pago Registrado Correctamente');

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
      Utilerias.resetObject(this.prestamo);
    },
    abrirModal(modelo, accion, ide,data = []){
      switch(modelo){
        case "prestamos":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Registrar Pago';

              Utilerias.resetObject(this.prestamo);
              this.tipoAccion = 1;
              this.prestamo.empleado_id=ide;
              let me=this;
              axios.get('/prestamo/'+ide+'/prestamo').then(response => {
                me.listaprestamo = response.data;
              })
              .catch(function (error) {
                console.log(error);
              });
              this.disabled=0;
              break;
            }
            case 'actualizar':
            {
              Utilerias.resetObject(this.prestamo);
              this.modal=1;
              this.tituloModal= 'Actualizar Pago';
              this.tipoAccion=2;
              this.disabled=1;
              this.prestamo.id=data['id'];
              this.prestamo.fecha_pago = data['fecha_pago'];
              this.prestamo.cantidad_a_pagar = data['cantidad_a_pagar'];
              this.prestamo.numero_pago = data['numero_pago'];
              this.prestamo.prestamo_id=data['prestamo_id'];
              this.prestamo.empleado_id=ide;


              break;
            }
          }
        }
      }
    },
    cargarprestamo(dataEmpleado = []) {
      this.detalle = true;
      this.isLoadingDetalle = true;
      let me=this;
      this.empleado = dataEmpleado;
      this.prestamo.empleado_id = dataEmpleado.id;
      axios.get('/pagoprestamo' + '/' + dataEmpleado.id ).then(response => {
        me.tableDataprestamo = response.data;
        me.isLoadingDetalle = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    maestro(){
      this.$refs.myTableprestamo.setFilter({
        'fecha_pago': '','cantidad_a_pagar': '',
      });
      this.detalle = false;
    }
  },
  mounted() {
    this.getData();
    this.getLista();
    this.$root.limpiarDashboard();
  }
}
</script>
