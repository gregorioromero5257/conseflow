<template>
  <div class="card border-dark">
    <!-- {{detalles_solicitudes}} -->
    <!-- {{nuevo}} -->
    <vue-element-loading :active="isLoading"/>
    <div class="card-header bg-dark text-white">
      <i class="fa fa-align-justify"> </i> Requisiciones por recibir:
      <button v-if="detalles_ver" type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="icon-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <div class="card-body">
      <v-client-table :data="tableData" :columns="columns" :options="options" v-show="!detalle">
        <template slot="id" slot-scope="props">
          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-grip-horizontal"></i> Acciones
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button type="button" @click="cargardetalle(props.row.id)" class="dropdown-item" >
                  <i class="fas fa-eye"></i>&nbsp; Ver partidas
                </button>
                <button class="dropdown-item" @click="descargar(props.row)">
                  <i class="fas fa-file-pdf"></i>&nbsp;Descargar Requisición
                </button>
              </div>
            </div>
          </div>
        </template>
        <template slot="condicion" slot-scope="props">
          <button class="btn btn-sm btn-success" @click="autorizar(4, props.row.id)"><i class="fas fa-check"></i> Si</button>
          <button class="btn btn-sm btn-danger" @click="autorizar(0, props.row.id)"><i class="fas fa-close"></i> No</button>
        </template>


      </v-client-table>

      <div v-show="detalles_ver">
        <v-client-table :data="tableDataDetalle" :options="optionsDetalle" :columns="columnsDetalle">
          <template slot="req.descripciona" slot-scope="props">
            <textarea name="name" rows="6" cols="40" :value="props.row.correccion != null ? props.row.correccion.comentario : props.row.req.descripciona + ' ' + (props.row.req.comentario == null ? '' : props.row.req.comentario)" @keyup.enter="guardarcorrecion($event, props.row.req)"></textarea>
          </template>
        </v-client-table>

      </div>
    </div>


    <!-- </div> -->
  </div>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data() {
    return {
      isLoading: false,
      solicitudes: [],
      detalle: false,
      nuevo : null,
      solicitud: [],
      detalles_ver : false,
      detalles_solicitudes : null,
      tipo_cambio : 0,
      moneda : 0,
      tableData : [],
      columns :['id','folio','nombrep','fecha_solicitud','nombre_solicita','nombre_autoriza','condicion'],
      options: {
        headings: {
          'id' : 'Acciones',
          'nombrep' : 'Proyecto',
          'nombre_solicita': 'Empleado que solicita',
          'nombre_autoriza' : 'Empleado que autoriza',
          'condicion' : 'Recibir',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
      },
      tableDataDetalle : [],
      columnsDetalle : ['req.descripciona','req.cantidad_compra','req.um','req.fecha_requerido'],
      optionsDetalle : {
        headings: {
          'req.descripciona' : 'Articulo/Servicio',
          'req.um' : 'Unidad',
          'req.fecha_requerido' : 'Fecha requerida',
          'req.cantidad_compra' : 'Cantidad a comprar',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
      }
    }
  },
  methods: {
    getData() {
      this.isLoading = true;
      let me=this;
      axios.get('/requisicionesrecibir').then(response => {
        me.solicitudes= response.data;
        me.tableData = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    cargarequisicion(id) {
      this.getData();
    },

    cargardetalle(id)
    {
      this.isLoading = true;
      this.detalles_ver = true;
      this.detalle = true;
      let me = this;
      axios.get('/requisicioncompserart/' + id ).then(response => {
        me.detalles_solicitudes = response.data;
        me.tableDataDetalle = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });

    },

    autorizar(estado , id)
    {
      this.isLoading = true;
      let me = this;
      var cadena = ['Almacén','Supervisor'];
      var cadenaid = [10,11];
      if (estado == 4) {
        swal({
          title: 'Esta seguro(a) autorizar la requisición?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#4dbd74',
          cancelButtonColor: '#f86c6b',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          reverseButtons: true
        }).then(result => {
          if (result.value) {
            axios.post('/autorizarequisicionproyectos',{
              id : id,
              estado : estado,
            }).then(function (response){
              me.isLoading = false;
              toastr.success('Correcto','Requisición recibida correctamente');
              me.getData();
            }).catch(function (error){
              console.error(error);
            });
          }else {
            me.isLoading = false;
          }
        });
      }else {
        swal({
          title: 'Direccionar a...',
          input: 'select',
          inputOptions: cadena,
          inputPlaceholder: 'Seleccionar Estado',
          confirmButtonText:
            'Continuar <i class="fa fa-arrow-right></i>',
          showCancelButton: true,
          customClass: 'form-check form-check-inline',
          inputValidator: (result) => {
            return !result && 'Se Requiere Elegir un Elemento'
          }
        }).then((result) => {
          me.isLoading = false;
          if (result.value) {
          axios.post('/autorizarequisicionproyectos',{
            id : id,
            estado : cadenaid[result.value],
          }).then(function (response){
            if (response == 4) {
              toastr.success('Correcto','Requisición recibida correctamente');
            }
            else {
              toastr.warning('Atención','Requisición no recibida');
            }
            me.getData();
          }).catch(function (error){
            console.error(error);
          });
        }
        }).catch(e => {
          console.error(e);
        });
      }



      //
      // axios.post('/autorizarequisicionproyectos',{
      //   id : id,
      //   estado : estado,
      // }).then(function (response){
      //   if (response == 4) {
      //     toastr.success('Correcto','Requisicion recibida correctamente');
      //   }
      //   else if (response == 0) {
      //     toastr.danger('Atención','Requisicion no recibida');
      //   }
      //   me.getData();
      // }).catch(function (error){
      //   console.error(error);
      // });
    },

    /**
    * [descargar description]
    * @param  {[type]} data [description]
    * @return {[type]}      [description]
    */
    descargar(data) {
      // window.open('descargar-requisicion/' + data.id, '_blank');
      window.open('descargar-requisicionew/' + data.id, '_blank');

    },

    guardarcorrecion(e ,data){
      console.log(e);
      console.log(data);
      axios.post('agregar/correciones/partidas',{
        requisicion_id : data.rid,
        pda : data.pda,
        articulo_servicio : (data.articulo_id != null ? 1 : 0),
        articulo_servicio_id : (data.articulo_id != null ? data.articulo_id : data.servicio_id),
        comentario : e.target.value,
      }).then(response => {
        console.log(response);
        toastr.success('Guardado');
      }).catch(e => {
        console.error(e);
      });
    },

    maestro(){
      this.detalles_ver = false;
      this.detalle = false;
      this.detalles_solicitudes = null;
    }
  },
  mounted() {
  }
}
</script>
