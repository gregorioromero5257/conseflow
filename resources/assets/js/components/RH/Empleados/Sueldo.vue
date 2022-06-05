<template>
  <main>
    <!-- v-bind:disabled="desabilitado == 1"  -->
    <button type="button" @click="abrirModal('sueldos','registrar', empleado.id)" class="btn btn-dark float-sm-right">
      <i class="fas fa-plus"></i>&nbsp;Nuevo
    </button>

    <vue-element-loading :active="isLoadingDetalle"/>
    <v-client-table :columns="columnsdatosbancarios" :data="tableDatasueldos" :options="optionssueldos" ref="myTablesueldos">

      <template slot="id" slot-scope="props">
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <div class="btn-group dropup" role="group">
          <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-grip-horizontal"></i> Acciones
          </button>
          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <button type="button" @click="abrirModal('sueldos','actualizar',empleado.id,props.row)" class="dropdown-item">
          <i class="icon-pencil"></i>&nbsp; Actualizar Sueldo
        </button>
          </div>
        </div>
        </div>

      </template>


    </v-client-table>

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

              <div class="input-group mb-3">
                <label class="col-md-3 form-control-label" for="sueldo_diario_integral">Sueldo diario integrado</label>
                <div class="input-group-addon">
                  <span class="input-group-text">$</span>
                </div>
                <input type="text" min="0" pattern="^[0-9]+" v-validate="'required|decimal:2'" name="sueldo_diario_integral" v-model="sueldoscont.sueldo_diario_integral" class="form-control" autocomplete="off" id="sueldo_diario_integral" data-vv-as="sueldo_diario_integral">
                <span class="text-danger">{{ errors.first('sueldo_diario_integral') }}</span>
                <div class="input-group-addon">
                  <span class="input-group-text">MXN</span>
                </div>
              </div>



              <div class="input-group mb-3">
                <label class="col-md-3 form-control-label" for="sueldo_mensual">Sueldo Mensual</label>
                <div class="input-group-addon">
                  <span class="input-group-text">$</span>
                </div>
                <input type="text" min="0" pattern="^[0-9]+" v-validate="'required|decimal:2'" name="sueldo_mensual" v-model="sueldoscont.sueldo_mensual" class="form-control" autocomplete="off" id="sueldo_mensual" data-vv-as="sueldo_mensual">
                <span class="text-danger">{{ errors.first('sueldo_mensual') }}</span>
                <div class="input-group-addon">
                  <span class="input-group-text">MXN</span>
                </div>
              </div>

              <div class="input-group mb-3">
                <label class="col-md-3 form-control-label" for="infonavit">Infonavit</label>
                <div class="input-group-addon">
                  <span class="input-group-text">$</span>
                </div>
                <input type="text" min="0" pattern="^[0-9]+" v-validate="'required|decimal:2'" name="infonavit" v-model="sueldoscont.infonavit" class="form-control" autocomplete="off" id="infonavit" data-vv-as="infonavit">
                <span class="text-danger">{{ errors.first('infonavit') }}</span>
                <div class="input-group-addon">
                  <span class="input-group-text">MXN</span>
                </div>
              </div>


              <div class="input-group mb-3">
                <label class="col-md-3 form-control-label" for="viaticos_mensuales">Viaticos Mensuales</label>
                <div class="input-group-addon">
                  <span class="input-group-text">$</span>
                </div>
                <input type="text" min="0" pattern="^[0-9]+" v-validate="'required|decimal:2'" name="viaticos_mensuales" v-model="sueldoscont.viaticos_mensuales" class="form-control" autocomplete="off" id="viaticos_mensuales" data-vv-as="viaticos_mensuales">
                <span class="text-danger">{{ errors.first('viaticos_mensuales') }}</span>
                <div class="input-group-addon">
                  <span class="input-group-text">MXN</span>
                </div>
              </div>

              <div class="input-group mb-3">
                <label class="col-md-3 form-control-label" for="sueldo_diario_neto">Sueldo Diario Neto</label>
                <div class="input-group-addon">
                  <span class="input-group-text">$</span>
                </div>
                <input type="text" min="0" pattern="^[0-9]+" v-validate="'required|decimal:2'" name="sueldo_diario_neto" v-model="sueldoscont.sueldo_diario_neto" class="form-control" autocomplete="off" id="sueldo_diario_neto" data-vv-as="sueldo_diario_neto">
                <span class="text-danger">{{ errors.first('sueldo_diario_neto') }}</span>
                <div class="input-group-addon">
                  <span class="input-group-text">MXN</span>
                </div>
              </div>

              <div class="input-group mb-3">
                <label class="col-md-3 form-control-label" for="sueldo_diario_neto">Salario Diario Real</label>
                <div class="input-group-addon">
                  <span class="input-group-text">$</span>
                </div>
                <input type="text" min="0" pattern="^[0-9]+" v-validate="'required|decimal:2'" name="sueldo_diario_real" v-model="sueldoscont.sueldo_diario_real" class="form-control" autocomplete="off" id="sueldo_diario_real" data-vv-as="sueldo_diario_real">
                <span class="text-danger">{{ errors.first('sueldo_diario_real') }}</span>
                <div class="input-group-addon">
                  <span class="input-group-text">MXN</span>
                </div>
              </div>

              <div class="input-group mb-3">
                <label class="col-md-3 form-control-label" for="sueldo_diario_neto">Fecha Actualización</label>
                <div class="input-group-addon">
                  <span class="input-group-text">$</span>
                </div>
                  <input type="date"  v-validate="'required'" name="fecha_act" v-model="sueldoscont.fecha_act" class="form-control" autocomplete="off" id="fecha_act" data-vv-as="fecha_act">
                <span class="text-danger">{{ errors.first('fecha_act') }}</span>
              </div>

              <div v-show="false">
                <div class="form-group row">

                  <div class="col-md-9">
                    <input type="number" v-validate="'required'" name="contrato_id" v-model="sueldoscont.contrato_id" class="form-control" placeholder="Contrato"
                    autocomplete="off" id="Contrato" data-vv-as="Contrato">
                    <span class="text-danger">{{ errors.first('contrato_id') }}</span>
                  </div>
                </div>
              </div>

              <!-- </form> -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarDatosSueldos(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarDatosSueldos(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
      empresa : '',
      url: '/sueldocontra',
      empleado: null,
      desabilitado: 0,
      isLoadingDetalle: false,
      sueldoscont: {

        id: 0,
        infonavit : 0,
        sueldo_diario_integral : 0,
        sueldo_mensual : 0,
        viaticos_mensuales : 0,
        sueldo_diario_neto : 0,
        contrato_id : 0,
        sueldo_diario_real: 0,
        fecha_act : '',
      },
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      disabled: 0,
      isLoading: false,
      columnsdatosbancarios: [
        'id',
        'infonavit',
        'sueldo_diario_integral',
        'sueldo_mensual',
        'viaticos_mensuales',
        'sueldo_diario_neto',
        'sueldo_diario_real',
        'fecha_act',

      ],
      tableDatasueldos: [],
      optionssueldos: {
        headings: {
          'id' : 'Acción',
          'infonavit' : 'Infonavit',
          'sueldo_diario_integral' : 'Sueldo diario integrado',
          'sueldo_mensual' : 'Sueldo mensual',
          'viaticos_mensuales' : 'Viaticos mensuales',
          'sueldo_diario_neto' : 'Sueldo diario neto',
          'sueldo_diario_real' : 'Salario diario real',
          'fecha_act' :'Fecha de actualización',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: [
          'id',
          'infonavit',
          'sueldo_diario_integral',
          'sueldo_mensual',
          'viaticos_mensuales',
          'sueldo_diario_neto',
          'sueldo_diario_real','fecha_act'
        ],
        filterable: [
          'id',
          'infonavit',
          'sueldo_diario_integral',
          'sueldo_mensual',
          'viaticos_mensuales',
          'sueldo_diario_neto',
          'sueldo_diario_real',
          'fecha_act'
        ],
        filterByColumn: true,
        listColumns: {

        },
        texts:config.texts
      },
    }
  },
  computed:{
  },
  methods : {

    guardarDatosSueldos(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/sueldocontra/registrar' : '/sueldocontra/actualizar',
            data: {

              'sueldo_diario_integral':this.sueldoscont.sueldo_diario_integral,
              'sueldo_mensual':this.sueldoscont.sueldo_mensual,
              'infonavit' :this.sueldoscont.infonavit,
              'viaticos_mensuales':this.sueldoscont.viaticos_mensuales,
              'sueldo_diario_neto':this.sueldoscont.sueldo_diario_neto,
              'contrato_id':this.sueldoscont.contrato_id,
              'id':this.sueldoscont.id,
              'sueldo_diario_real':this.sueldoscont.sueldo_diario_real,
              'fecha_act' :this.sueldoscont.fecha_act,
              'empresa' : this.empresa,
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              me.cargarsueldo(me.empleado, me.empresa);
              if(!nuevo){
                toastr.success('Sueldos Actualizados Correctamente');
              }
              else
              {
                toastr.success('Sueldos Registrados Correctamente');
                // toastr.info('Este Contrato No Puede Tener Más De Un Sueldo!');
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
      Utilerias.resetObject(this.sueldoscont);
    },
    abrirModal(modelo, accion, id, data = []){
      switch(modelo){
        case "sueldos":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Registrar sueldos';
              Utilerias.resetObject(this.sueldoscont);
              this.sueldoscont.contrato_id = id;
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar':
            {
              this.modal=1;
              this.tituloModal='Actualizar sueldos';
              Utilerias.resetObject(this.sueldoscont);
              this.sueldoscont.id=data['id'];
              this.tipoAccion=2;

              this.sueldoscont.sueldo_diario_integral = data['sueldo_diario_integral'];

              this.sueldoscont.sueldo_mensual = data['sueldo_mensual'];

              this.sueldoscont.infonavit = data['infonavit'];

              this.sueldoscont.viaticos_mensuales = data['viaticos_mensuales'];

              this.sueldoscont.sueldo_diario_neto = data['sueldo_diario_neto'];

              this.sueldoscont.contrato_id = data['contrato_id'];

              this.sueldoscont.sueldo_diario_real = data['sueldo_diario_real'];

              this.sueldoscont.fecha_act = data['fecha_act'];

              break;
            }
          }
        }
      }
    },
    cargarsueldo(dataEmpleado = [], empresa) {
      this.empresa = empresa;
      Utilerias.resetObject(this.sueldoscont);
      this.isLoading = true;
      let me=this;
      this.empleado = dataEmpleado;
      axios.get(me.url + '/' + dataEmpleado.id + '&' + empresa + '/' +'sueldocontra').then(response => {
        var contador = response.data.length;

        if (contador >= 1){

          this.desabilitado = 1;
        }
        else{
          this.desabilitado = 0;
        }
        me.tableDatasueldos = response.data;
        me.isLoading = false;

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
