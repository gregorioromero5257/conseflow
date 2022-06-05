<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Registro de pagos recurrentes
          <button v-show="PermisosCrud.Create" type="button" @click="abrirModal('solicitud-pagos-recurrentes','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">     
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
              <button v-show="PermisosCrud.Update" type="button" @click="abrirModal('solicitud-pagos-recurrentes','actualizar',props.row)" class="dropdown-item">
                <i class="icon-pencil"></i>&nbsp; Actualizar Pago
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
                <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                <div class="col-md-9">
                  <input type="text"  name="nombre" v-model="PagosRecurrentes.nombre" class="form-control" placeholder="Nombre" autocomplete="off" id="nombre" data-vv-as="Nombre" v-validate="'required'" >
                  <span class="text-danger">{{ errors.first('nombre') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="fecha_inicial">Fecha</label>
                <div class="col-md-9">
                  <input type="date"  name="fecha_inicial" v-model="PagosRecurrentes.fecha_inicial" class="form-control" placeholder="Fecha Inicial" autocomplete="off" id="fecha_inicial" data-vv-as="Fecha Inicial" v-validate="'required'">
                  <span class="text-danger">{{ errors.first('fecha_inicial') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="rango_dias">Rango de días</label>
                <div class="col-md-9">
                  <input type="number"  min="0" pattern="^[0-9]+" name="rango_dias" class="form-control"
                    v-model="PagosRecurrentes.rango_dias"  placeholder="Rango Días" autocomplete="off" 
                    id="rango_dias" data-vv-as="Rango Días" v-validate="'required'">
                    <span class="text-danger">{{ errors.first('rango_dias') }}</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="eventos">Número de eventos</label>
                <div class="col-md-9">
                  <input type="number"  min="0" pattern="^[0-9]+" name="eventos" v-model="PagosRecurrentes.eventos" class="form-control" placeholder="Eventos" autocomplete="off" id="eventos" data-vv-as="Eventos" >
                  <span class="text-danger">{{ errors.first('eventos') }}</span>
                </div>
              </div>

              <div class="form-group row">
      <label class="col-md-3 form-control-label" for="proveedor_id">Proveedores</label>
      <div class="col-md-9">
        <v-select id="proveedor" name="proveedor" v-model="proveedor" label="nombre" :options="listaProveedores"
          v-validate="'required'">
        </v-select>
        <span class="text-danger">{{ errors.first('proveedor') }}</span>
       </div>
      </div>

       <div class="form-group row">
      <label class="col-md-3 form-control-label" for="proyecto_id">Proyecto</label>
      <div class="col-md-9">
        <v-select id="proyectos" name="proyectos" label="nombre_corto" :options="listaProyectos" v-validate="'required'"
            v-model="proyecto">
        </v-select>
      <span class="text-danger">{{ errors.first('proyectos') }}</span>   
       </div>
      </div>

       <div class="form-group row">
      <label class="col-md-3 form-control-label" for="ordenes_comp_id">Ordenes de compra</label>
      <div class="col-md-9">
      <select class="form-control" id="ordenes_comp_id"  name="ordenes_comp_id" v-model="PagosRecurrentes.ordenes_comp_id"  data-vv-as="Ordenes de Compra" v-validate="''" >
      <option v-for="item in listaOrdenes" :value="item.compra.id" :key="item.compra.id">{{ item.compra.folio}}</option>
      </select>
      <span class="text-danger">{{ errors.first('ordenes_comp_id') }}</span>
     
       </div>
      </div>

              <!-- </form> -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarPagosRecurrentesMen(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarPagosRecurrentesMen(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
      url: '/pagosrecurrentesmen',
      PermisosCrud : {},
      proveedor:{},
      proyecto:{},
      PagosRecurrentes: {
        id : 0,
        nombre :'',
        fecha_inicial :'',
        rango_dias :'',
        eventos: '',
        proveedor_id: 0,
        proyecto_id : 0,
        ordenes_comp_id : 0,

      },
      listaPagosRecu: [],
      listaProveedores: [],
      listaProyectos: [],
      listaOrdenes: [],
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: ['id','nombre','fecha_inicial','rango_dias','eventos','NombreProveedor','NombreProyecto','FolioOrdenCompra'],
      tableData: [],
      options: {
        headings: {
          id: 'Acciones',
          nombre: 'Nombre del pago',
          fecha_inicial: 'Fecha del pago',
          rango_dias: 'Rango de días',
          NombreProveedor : 'Proveedor',
          NombreProyecto : 'Proyecto',
          FolioOrdenCompra : 'Folio orden de compra'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre','fecha_inicial','rango_dias'],
        filterable: ['nombre','fecha_inicial','rango_dias'],
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
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
      axios.get(me.url).then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getListaPagosRecunMen() {
      let me=this;
      axios.get('/pagosrecurrentesmen').then(response => {
        me.listaPagosRecu = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getListaProveedores(){
        let me=this;
      axios.get('/proveedores').then(response => {
        me.listaProveedores = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getListaProyectos()
    {let me=this;
    axios.get('proyecto').then(response => {
      // me.listaProyectos = response.data;
      response.data.forEach(p=>
      {
        me.listaProyectos.push({id:p.proyecto.id,nombre_corto:p.proyecto.nombre_corto});
      });
    })
    .catch(function (error){
      console.log(error);
    });
    },
     getListaOrdenesComp() {
      let me=this;
      axios.get('/compras/ver').then(response => {
        me.listaOrdenes = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    guardarPagosRecurrentesMen(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? me.url : me.url+'/'+this.id,
            data: {
              'id': this.PagosRecurrentes.id,
              'nombre': this.PagosRecurrentes.nombre,
              'fecha_inicial': this.PagosRecurrentes.fecha_inicial,
              'rango_dias': this.PagosRecurrentes.rango_dias,
              'eventos' : this.PagosRecurrentes.eventos,
              'proveedor_id' : this.proveedor.id,
              'proyecto_id' : this.proyecto.id,
              'ordenes_comp_id' : this.PagosRecurrentes.ordenes_comp_id,
              

            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              me.getData();
              if (!nuevo) {
                toastr.success('Pago Actualizado Correctamente');
              }else {
                toastr.success('Pago Agregado Correctamente');
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
    actdesact(id,activar){
      if(activar){ 
      }else{
      }
      swal({
        title: this.swal_titulo,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar!',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          let me = this;
          axios.get(me.url+'/'+id+'/edit').then(function (response) {
            toastr.options = {
            "closeButton": false,
            "closeButton": false,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
           }
            me.getData();
          }).catch(function (error) {
            console.log(error);
          });
        } else if (
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      })
    },
    cerrarModal(){
      this.modal=0;
      this.tituloModal='';
      Utilerias.resetObject(this.PagosRecurrentes);
    },
    abrirModal(modelo, accion, data = []){
      switch(modelo){
        case "solicitud-pagos-recurrentes":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Registrar Nuevo Pago';
              Utilerias.resetObject(this.PagosRecurrentes);
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar':
            {
              this.modal=1;
              this.tituloModal='Actualizar Pago';
              this.tipoAccion=2;
              this.PagosRecurrentes.id=data['id'];
              this.PagosRecurrentes.nombre = data['nombre'];
              this.PagosRecurrentes.fecha_inicial = data['fecha_inicial'];
              this.PagosRecurrentes.rango_dias = data['rango_dias'];
              this.PagosRecurrentes.eventos = data['eventos'];
              this.proveedor.id = data['proveedor_id'];
              this.proyecto.id = data['proyecto_id'];
              this.PagosRecurrentes.ordenes_comp_id = data['ordenes_comp_id'];

              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.getData();
    this.getListaPagosRecunMen();
    this.getListaProveedores();
    this.getListaProyectos();
    this.getListaOrdenesComp();
  }
}
</script>
