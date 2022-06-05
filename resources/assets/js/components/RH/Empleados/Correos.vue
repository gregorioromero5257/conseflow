<template>
  <main>
    <button type="button" v-bind:disabled="deshabilitado == 1" @click="abrirModal('correo','registrar', empleado.id)" class="btn btn-dark float-sm-right">
      <i class="fas fa-plus"></i>&nbsp;Nuevo
    </button>

    <vue-element-loading :active="isLoadingDetalle"/>
    <v-client-table :columns="columnscorreo" :data="tableDataCorreo" :options="optionscorreo" ref="myTabledireccion">

  <template slot="condicion" slot-scope="props">
        <template v-if="props.row.condicion == 1">
          <button type="button" class="btn btn-outline-success">Activo</button>
        </template>
        <template v-if="props.row.condicion == 0">
          <button type="button" class="btn btn-outline-danger">Desactivado</button>
        </template>
</template>

      <template slot="id" slot-scope="props">
       <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <div class="btn-group dropup" role="group">
          <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
          </button>
          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">  
        <button type="button" @click="abrirModal('correo','actualizar',empleado.id,props.row)" class="dropdown-item">
          <i class="icon-pencil"></i>&nbsp; Actualizar Correo
        </button>
        <template v-if="props.row.condicion">
            <button type="button" class="dropdown-item"
            @click="actdesact(props.row.id, 0, empleado.id)">
                <i class="fas fa-ban"></i>&nbsp; Desactivar
            </button>
        </template>
        <template v-else>
            <button type="button" class="dropdown-item"
            @click="actdesact(props.row.id, 1, empleado.id)">
                <i class="icon-check"></i>&nbsp; Activar
            </button>
        </template>
        </div>
        </div>
        </div>
      </template>

    </v-client-table>

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
              <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
              
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="nombre">Correo</label>
                <div class="col-md-9">
                  <input type="email" v-validate="'required|max:45'" name="correo" v-model="correo.nombre" class="form-control" placeholder="Correo" autocomplete="off" id="nombre">
                  <span class="text-danger">{{ errors.first('correo') }}</span>
                </div>
              </div>
              


              <!-- </form> -->
            </div>
            <div class="modal-footer">
              <vue-element-loading :active="isLoading"/>
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarCorreos(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarCorreos(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
      url: '/correo',
      empleado: null,
      deshabilitado: null,
      detalle: false,
      correo: {
        id : 0,
        nombre : '',
        condicion: 0,
        empleado_id : 0,
      },
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      disabled: 0,
      isLoading: false,
      isLoadingDetalle: false,
      columnscorreo: [
        'id',
        'nombre',
        'condicion',

      ],
      tableDataCorreo: [],
      optionscorreo: {
        headings: {
          'id': 'Acciones',
          'nombre': 'Correo Electronico',
          'condicion' : 'Condición',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre'],
        filterable: ['nombre'],
        filterByColumn: true,
        listColumns: {
          condicion: [{
                id: 1,
                text: 'Activo'
            },
            {
                id: 0,
                text: 'Baja'
            }]
        },
        texts:config.texts
      },
    }
  },
  computed:{
  },
  methods : {
    guardarCorreos(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? me.url : me.url+'/'+this.correo.id,
            data: {
              'nombre': this.correo.nombre,
              'empleado_id' : this.correo.empleado_id,
              'id': this.correo.id

            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              me.cargarCorreos(me.empleado);
              if(!nuevo){
                toastr.success('Correo Actualizado Correctamente');
                }
                else
                {
                toastr.success('Correo Registrado Correctamente');

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
    actdesact(id,activar,idb,n,ap,am){

        if(activar){
            this.swal_titulo = 'Esta seguro de activar este correo?';
            this.swal_tle = 'Activado';
            this.swal_msg = 'El registro ha sido activado con éxito.';
        }else{
            this.swal_titulo = 'Esta seguro de desactivar este correo?';
            this.swal_tle = 'Desactivado!';
            this.swal_msg = 'El registro ha sido desactivado con éxito.';
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
                if(activar){
                    me.cargarCorreos(me.empleado);
                toastr.success(me.swal_tle,me.swal_msg,'success');

                }else{
                toastr.error(me.swal_tle,me.swal_msg,'error');

                }
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
                var data = [];
                data['id'] = idb;

                me.cargarCorreos(data);
                empleado = '';
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
      Utilerias.resetObject(this.correo);
    },
    abrirModal(modelo, accion, id, data = []){
      switch(modelo){
        case "correo":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Registrar correo del empleado';
              Utilerias.resetObject(this.correo);
              this.tipoAccion = 1;
              this.correo.empleado_id = id;
              this.disabled = 0;
              break;
            }
            case 'actualizar':
            {
              Utilerias.resetObject(this.correo);
              this.modal=1;
              this.tituloModal='Actualizar correo';
              this.tipoAccion=2;
              this.disabled=1;
              this.correo.id=data['id'];
              this.correo.nombre = data['nombre'];
              this.disabled=0;
              break;
            }
          }
        }
      }
    },
    cargarCorreos(dataEmpleado = []) {
      this.detalle = true;
      this.isLoadingDetalle = true;
      let me=this;
      this.empleado = dataEmpleado;

      axios.get('/correo' + '/' + dataEmpleado.id + '/' +'correo').then(response => {
        var contador = response.data.length;

        if (contador >= 2){

          this.deshabilitado = 1;
        }
        else{
          this.deshabilitado = 0;
        }
        me.tableDataCorreo = response.data;
        me.isLoadingDetalle = false;
        me.correo.id=response.data['id'];
        me.correo.empleado_id = response.data['empleado_id'];
        me.correo.nombre =response.data['nombre'];
        
      })
      .catch(function (error) {
        console.log(error);
      });
    },
  },
  mounted() {
  }
}
</script>
