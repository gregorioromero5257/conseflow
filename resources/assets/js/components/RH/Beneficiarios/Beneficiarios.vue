<template>
  <main>
    <button type="button" v-bind:disabled="deshabilitado == 1" @click="abrirModal('beneficiario','registrar', empleado.id)" class="btn btn-dark float-sm-right">
        <i class="fas fa-plus"></i>&nbsp;Nuevo
    </button>
      <vue-element-loading :active="isLoadingDetalle"/>
      <v-client-table :columns="columnsbeneficiario" :data="tableDatabeneficiario" :options="optionsbeneficiario" ref="myTabledireccion">

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
        <button type="button" @click="abrirModal('beneficiario','actualizar',empleado.id,props.row)" class="dropdown-item">
            <i class="icon-pencil"></i>&nbsp; Actualizar Beneficiario
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

                      <div class="form-group row" hidden>

                          <div class="col-md-9">
                            <input type="text"  name="empleado_id" v-model="beneficiario.empleado_id" class="form-control" placeholder="Nombre Completo del Beneficiario" autocomplete="off" id="nombre">

                              <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                          </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="nombre">Nombre del Beneficiario</label>
                        <div class="col-md-9">
                          <input type="text" v-validate="'required'" name="nombre" v-model="beneficiario.nombre" class="form-control" placeholder="Nombre Completo del Beneficiario" autocomplete="off" id="nombre">
                          <span class="text-danger">{{ errors.first('nombre') }}</span>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="telefono">Teléfono (10 Digitos)</label>
                        <div class="col-md-9">
                          <input type="number" name="telefono" v-model="beneficiario.telefono" class="form-control" placeholder="Teléfono Particular" autocomplete="off" id="telefono">
                          <span class="text-danger">{{ errors.first('telefono') }}</span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="tel_celular">Teléfono (10 Digitos)</label>
                        <div class="col-md-9">
                          <input type="number" name="tel_celular" v-model="beneficiario.tel_celular" class="form-control" placeholder="Teléfono Celular" autocomplete="off" id="tel_celular">
                          <span class="text-danger">{{ errors.first('tel_celular') }}</span>
                        </div>
                      </div>

                      <div class="form-group row">
                              <label class="col-md-3 form-control-label" for="parentesco">Parentesco</label>
                              <div class="col-md-9">
                                  <input type="text" v-validate="'required'" name="parentesco" v-model="beneficiario.parentesco" class="form-control" placeholder="Parentesco" autocomplete="off" id="parentesco">
                                  <span class="text-danger">{{ errors.first('parentesco') }}</span>
                              </div>
                          </div>

                       <div class="form-group row">
                              <label class="col-md-6 form-control-label" for="porcentaje">Porcentaje</label>
                              <div class="col-md-9 ml-auto">
                                  <input type="number" min="1" max="100" v-validate="'required'" name="porcentaje" v-model="beneficiario.porcentaje" class="form-control" placeholder="Porcentaje" autocomplete="off" id="porcentaje">
                                  <span class="text-danger">{{ errors.first('porcentaje') }}</span>
                              </div>
                          </div>
                      </div>
                      </div>


                      <!-- </form> -->

                  <div class="modal-footer">
                     <vue-element-loading :active="isLoading"/>
                      <button type="button" class="btn btn-outine-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                      <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarbeneficiario(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                      <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarbeneficiario(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                  </div>
                  </div>
              </div>
              <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->

      <!--Fin del modal-->
  </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      url: '/beneficiario',
      empleado: null,
      deshabilitado: null,
      detalle: false,
      beneficiario: {
          id: 0,
          telefono: '',
          tel_celular : '',
          parentesco : '',
          porcentaje : 0,
          nombre: '',
          condicion: 0,
          empleado_id : 0,


      },
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      disabled: 0,
      isLoading: false,
      isLoadingDetalle: false,
      columnsbeneficiario: [  'id',
        'nombre',
        'telefono',
        'tel_celular',
        'parentesco',
        'porcentaje',
        'condicion',],
      tableDatabeneficiario: [],
      optionsbeneficiario: {
        headings: {
            'id': 'Acciones',
            'nombre': 'Nombre del Beneficiario',
            'telefono': 'Teléfono',
            'tel_celular': 'Télefono Celular',
            'parentesco': 'Parentesco',
            'porcentaje': 'Porcentaje',
            'condicion': 'Condición',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre','empleado','porcentaje', 'condicion'],
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
    guardarbeneficiario(nuevo){
        this.$validator.validate().then(result => {
            if (result) {
                this.isLoading = true;
                let me = this;
                axios({
                    method: nuevo ? 'POST' : 'PUT',
                    url: nuevo ? me.url+'/'+this.beneficiario.empleado_id+'/beneficiario' : me.url+'/'+this.beneficiario.id+'/'+'beneficiario/'+this.beneficiario.empleado_id,
                    data: {
                        'nombre':this.beneficiario.nombre,
                        'telefono':this.beneficiario.telefono,
                        'tel_celular':this.beneficiario.tel_celular,
                        'parentesco':this.beneficiario.parentesco,
                        'porcentaje':this.beneficiario.porcentaje,
                        'empleado_id':this.beneficiario.empleado_id,
                        'id':this.beneficiario.id
                    }
                }).then(function (response) {
                    me.isLoading = false;
                    if (response.data.status) {
                        me.cerrarModal();
                        me.cargarbeneficiario(me.empleado);
                        if(!nuevo){
                        toastr.success('Beneficiario Actualizado Correctamente');
                        }
                        else
                        {
                        toastr.success('Beneficiario Registrado Correctamente');

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
            this.swal_titulo = 'Esta seguro de activar este beneficiario?';
            this.swal_tle = 'Activado';
            this.swal_msg = 'El registro ha sido activado con éxito.';
        }else{
            this.swal_titulo = 'Esta seguro de desactivar este beneficiario?';
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
                    me.cargarbeneficiario(me.empleado);
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

                me.cargarbeneficiario(data);
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
      Utilerias.resetObject(this.beneficiario);
    },
    abrirModal(modelo, accion,ide,data = []){
        switch(modelo){
            case "beneficiario":
            {
                switch(accion){
                    case 'registrar':
                    {
                        this.modal = 1;
                        this.tituloModal = 'Registrar beneficiario';
                        Utilerias.resetObject(this.beneficiario);
                        this.tipoAccion = 1;
                        this.beneficiario.empleado_id = ide;
                        this.disabled=0;
                        break;
                    }
                    case 'actualizar':
                    {
                        Utilerias.resetObject(this.beneficiario);
                        this.modal=1;
                        this.tituloModal= 'Actualizar beneficiario';
                        this.tipoAccion=2;
                        this.disabled=1;
                        this.beneficiario.id=data['id'];
                        this.beneficiario.nombre=data['nombre'];
                        this.beneficiario.empleado_id = data['empleado_id'];
                        this.beneficiario.telefono = data['telefono'];
                        this.beneficiario.tel_celular=data['tel_celular'];
                        this.beneficiario.parentesco=data['parentesco'];
                        this.beneficiario.porcentaje=data['porcentaje'];
                        this.disabled=0;
                        break;
                    }
                }
            }
        }
    },
    cargarbeneficiario(dataEmpleado = []) {
      this.detalle = true;
      this.isLoadingDetalle = true;
      let me=this;
      this.empleado = dataEmpleado;

      axios.get('/beneficiario' + '/' + dataEmpleado.id + '/' +'beneficiario').then(response => {
          var contador = response.data.length;

        if (contador >= 5){

          this.deshabilitado = 1;
        }
        else{
          this.deshabilitado = 0;
        }

        me.tableDatabeneficiario = response.data;
        me.isLoadingDetalle = false;
        me.beneficiario.id=response.data['id'];
        me.beneficiario.empleado_id = response.data['empleado_id'];
        me.beneficiario.nombre = response.data['nombre'];
        me.beneficiario.telefono = response.data['telefono'];
        me.beneficiario.tel_celular = response.data['tel_celular'];
        me.beneficiario.parentesco = response.data['parentesco'];
        me.beneficiario.porcentaje = response.data['porcentaje'];
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
