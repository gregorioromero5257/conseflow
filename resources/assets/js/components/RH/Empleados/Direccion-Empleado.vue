<template>
  <main>
    <button type="button" v-bind:disabled="deshabilitado == 1" @click="abrirModal('direccion','registrar', empleado.id)" class="btn btn-dark float-sm-right">
      <i class="fas fa-plus"></i>&nbsp;Nuevo
    </button>

    <vue-element-loading :active="isLoadingDetalle"/>
    <v-client-table :columns="columnsdireccion" :data="tableDatadireccion" :options="optionsdireccion" ref="myTabledireccion">

      <template slot="id" slot-scope="props">
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <div class="btn-group dropup" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-grip-horizontal"></i>&nbsp;Acciones
            </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <button type="button" @click="abrirModal('direccion','actualizar',empleado.id,props.row)" class="dropdown-item">
          <i class="icon-pencil"></i>Actualizar Direccion
        </button>
        <template v-if="props.row.condicion">
            <button type="button" class="dropdown-item"
            @click="actdesact(props.row.id,0)">
                <i class="fas fa-ban"></i>&nbsp;Desactivar
            </button>
        </template>
        <template v-else>
            <button type="button" class="btn btn-info btn-sm"
            @click="actdesact(props.row.id,1)">
                <i class="icon-check"></i>&nbsp;Activar
            </button>
        </template>
        </div>
        </div>
        </div>
      </template>

      <template slot="condicion" slot-scope="props" >
          <template v-if="props.row.condicion">
              <button type="button" class="btn btn-outline-success">Activo</button>
          </template>
          <template v-else>
              <button type="button" class="btn btn-outline-danger">Desactivado</button>
          </template>
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
              <input type="hidden" name="id">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="calle">Calle</label>
                <div class="col-md-9">
                  <input type="text" size="5" name="calle" v-model="direccione.calle" class="form-control" placeholder="Calle" autocomplete="off" id="calle">
                  <span class="text-danger">{{ errors.first('calle') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="numero_exterior">No. Exterior</label>
                <div class="col-md-9">
                  <input type="text" name="numero_exterior" v-model="direccione.numero_exterior" class="form-control" placeholder="Número Exterior" autocomplete="off" id="numero_exterior">
                  <span class="text-danger">{{ errors.first('numero_exterior') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="numero_interior">No. Interior</label>
                <div class="col-md-9">
                  <input type="number" name="numero_interior" step="1" min="0" v-model="direccione.numero_interior" class="form-control" placeholder="Número Interior" autocomplete="off" id="numero_interior">
                  <span class="text-danger">{{ errors.first('numero_interior') }}</span>
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="codigo_postal">Código Postal</label>
                <div class="col-md-9">
                  <input type="number" v-validate="'required'" name="codigo_postal" v-model="direccione.codigo_postal" class="form-control" placeholder="Codigo Postal" autocomplete="off" id="codigo_postal">
                  <span class="text-danger">{{ errors.first('codigo_postal') }}</span>
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="localidad">Localidad</label>
                <div class="col-md-9">
                  <input type="text" name="localidad" v-model="direccione.localidad" class="form-control" placeholder="Localidad" autocomplete="off" id="localidad">
                  <span class="text-danger">{{ errors.first('localidad') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="entidad_federativa">Entidad Federativa</label>
                <div class="col-md-9">
                  <input type="text" name="entidad_federativa" v-model="direccione.entidad_federativa" class="form-control" placeholder="Entidad Federativa" autocomplete="off" id="entidad_federativa">
                  <span class="text-danger">{{ errors.first('entidad_federativa') }}</span>
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="entre_que_calles">Entre calles</label>
                <div class="col-md-9">
                  <input type="text" name="entre_que_calles" v-model="direccione.entre_que_calles" class="form-control" placeholder="Entre que calles" autocomplete="off" id="entre_que_calles">
                  <span class="text-danger">{{ errors.first('entre_que_calles') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="tipo_avenida">Tipo de Vialidad</label>
                <div class="col-md-9">
                  <input type="text" name="tipo_avenida" v-model="direccione.tipo_avenida" class="form-control" placeholder="Tipo de Vialidad" autocomplete="off" id="tipo_avenida">
                  <span class="text-danger">{{ errors.first('tipo_avenida') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="colonia">Colonia</label>
                <div class="col-md-9">
                  <input type="text" name="colonia" v-model="direccione.colonia" class="form-control" placeholder="Colonia" autocomplete="off" id="colonia">
                  <span class="text-danger">{{ errors.first('colonia') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="municipio">Municipio</label>
                <div class="col-md-9">
                  <input type="text" name="municipio" v-model="direccione.municipio" class="form-control" placeholder="Municipio" autocomplete="off" id="municipio">
                  <span class="text-danger">{{ errors.first('municipio') }}</span>
                </div>
              </div>


              <!-- </form> -->
            </div>
            <div class="modal-footer">
              <vue-element-loading :active="isLoading"/>
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarDirecciones(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarDirecciones(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
      url: '/direccionempleado',
      empleado: null,
      deshabilitado: null,
      isLoadingDetalle: false,
      direccione: {
        id : 0,
        codigo_postal : 0 ,
        numero_exterior : 0,
        numero_interior : 0,
        localidad : '',
        entidad_federativa : '',
        calle : '',
        entre_que_calles : '',
        tipo_avenida :'',
        colonia : '' ,
        municipio : '',
        condicion: 0,
        empleado_id : 0,
      },
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      disabled: 0,
      isLoading: false,
      columnsdireccion: [
        'id',
        'codigo_postal',
        'numero_exterior',
        'numero_interior',
        'localidad',
        'entidad_federativa',
        'calle',
        'entre_que_calles',
        'tipo_avenida',
        'colonia',
        'municipio',
        'condicion'

      ],
      tableDatadireccion: [],
      optionsdireccion: {
        headings: {
          id: 'Acciones',
          nombre: 'nombre',
          codigo_postal : 'CP',
          numero_exterior : '# Exterior',
          numero_interior : '# Interior',
          localidad : 'Localidad',
          entidad_federativa : 'Entidad Federativa',
          calle : 'Calle',
          entre_que_calles : 'Entre que calles',
          tipo_avenida :'Tipo de Avenida',
          colonia : 'Colonia' ,
          municipio : 'Municipio',
          condicion : 'Condición',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['codigo_postal','numero_exterior','numero_interior','localidad','entidad_federativa','calle','entre_que_calles','tipo_avenida','colonia','municipio','condicion'],
        filterable: ['entidad_federativa','municipio'],
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
    actdesact(id,tipo){
        if(tipo){
            this.swal_titulo = 'Esta seguro de activar esta dirección?';
            this.swal_tle = 'Activado';
            this.swal_msg = 'El registro ha sido activado con éxito.';
        }else{
            this.swal_titulo = 'Esta seguro de desactivar esta dirección?';
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
                swal(me.swal_tle,me.swal_msg,'success')
                me.cargarDirecciones(me.empleado);
            }).catch(function (error) {
                console.log(error);
            });
        } else if (
            result.dismiss === swal.DismissReason.cancel
        ) {

        }
        })
    },
    guardarDirecciones(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? me.url : me.url+'/'+this.direccione.id,
            data: {
              'id': this.direccione.id,
              'codigo_postal': this.direccione.codigo_postal,
              'numero_exterior': this.direccione.numero_exterior,
              'numero_interior': this.direccione.numero_interior,
              'localidad': this.direccione.localidad,
              'entidad_federativa': this.direccione.entidad_federativa,
              'calle': this.direccione.calle,
              'entre_que_calles': this.direccione.entre_que_calles,
              'tipo_avenida': this.direccione.tipo_avenida,
              'colonia': this.direccione.colonia,
              'municipio': this.direccione.municipio,
              'empleado_id' : this.direccione.empleado_id,

            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              me.cargarDirecciones(me.empleado);
              if(!nuevo){
                toastr.success('Dirección Actualizada Correctamente');
                }
                else
                {
                toastr.success('Dirección Registrado Correctamente');

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
      Utilerias.resetObject(this.descuento);
    },
    abrirModal(modelo, accion, id, data = []){
      switch(modelo){
        case "direccion":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Registrar domicilio del empleado';
              Utilerias.resetObject(this.direccione);
              this.direccione.empleado_id = id;
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar':
            {
              this.modal=1;
              this.tituloModal='Actualizar dirección';
              this.direccione.id=data['id'];
              this.tipoAccion=2;
              this.direccione.codigo_postal = data['codigo_postal'];
              this.direccione.numero_exterior = data['numero_exterior'];
              this.direccione.numero_interior = data['numero_interior'];
              this.direccione.localidad = data['localidad'];
              this.direccione.entidad_federativa = data['entidad_federativa'];
              this.direccione.calle = data['calle'];
              this.direccione.entre_que_calles = data['entre_que_calles'];
              this.direccione.tipo_avenida = data['tipo_avenida'];
              this.direccione.entidad_federativa = data['entidad_federativa'];
              this.direccione.colonia = data['colonia'];
              this.direccione.municipio = data['municipio'];

              break;
            }
          }
        }
      }
    },
    cargarDirecciones(dataEmpleado = [], id) {
      this.detalle = true;
      this.isLoadingDetalle = true;
      let me=this;
      this.empleado = dataEmpleado;

      axios.get(me.url + '/' + dataEmpleado.id + '/' +'direccionempleado').then(response => {
        var contador = response.data.length;

        if (contador >= 1){

          this.deshabilitado = 1;
        }
        else{
          this.deshabilitado = 0;
        }
        me.tableDatadireccion = response.data;
        me.isLoadingDetalle = false;
        me.direccione.id=response.data['id'];
        me.direccione.empleado_id = response.data['empleado_id'];
        me.direccione.codigo_postal =response.data['codigo_postal'];
        me.direccione.numero_exterior = response.data['numero_exterior'];
        me.direccione.numero_interior = response.data['numero_interior'];
        me.direccione.localidad = response.data['localidad'];
        me.direccione.entidad_federativa = response.data['entidad_federativa'];
        me.direccione.calle =response.data['calle'];
        me.direccione.entre_que_calles = response.data['entre_que_calles'];
        me.direccione.tipo_avenida = response.data['tipo_avenida'];
        me.direccione.colonia = response.data['colonia'];
        me.direccione.municipio = response.data['municipio'];
        
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
