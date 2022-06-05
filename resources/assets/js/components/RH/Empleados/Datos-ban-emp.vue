<template>
  <main>
    <button type="button" v-bind:disabled="desabilitado == 1" @click="abrirModal('bancos','registrar', empleado.id)" class="btn btn-dark float-sm-right">
      <i class="fas fa-plus"></i>&nbsp;Nuevo
    </button>

    <vue-element-loading :active="isLoadingDetalle"/>
    <v-client-table :columns="columnsdatosbancarios" :data="tableDatabancos" :options="optionsbancos" ref="myTablebancos">

      <template slot="id" slot-scope="props">
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <div class="btn-group dropup" role="group">
          <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
          </button>
          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">  
        <button type="button" @click="abrirModal('bancos','actualizar',empleado.id,props.row)" class="dropdown-item">
          <i class="icon-pencil"></i>&nbsp;Actualizar Datos Bancarios
        </button>
        <template v-if="props.row.condicion">
          <button type="button" class="dropdown-item"
          @click="actdesact(props.row.id,0)">
          <i class="fas fa-ban"></i>&nbsp;Eliminar
        </button>
      </template>
      <template v-else>
        <button type="button" class="dropdown-item"
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
      <button type="button" class="btn btn-outline-danger">Dado de Baja</button>
    </template>
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
          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="numero_tarjeta">Número de tarjeta</label>
            <div class="col-md-9">
              <input type="text" v-validate="'required|numeric|max:16'" size="5" name="numero_tarjeta" v-model="datbancos.numero_tarjeta" class="form-control" placeholder="Número de tarjeta" autocomplete="off" id="numero_tarjeta" data-vv-as="Número de tarjeta">
              <span class="text-danger">{{ errors.first('numero_tarjeta') }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="numero_cuenta">Número de cuenta</label>
            <div class="col-md-9">
              <input type="text" v-validate="'required|numeric|max:15'" name="numero_cuenta" v-model="datbancos.numero_cuenta" class="form-control" placeholder="Número de cuenta" autocomplete="off" id="numero_cuenta" data-vv-as="Número de cuenta">
              <span class="text-danger">{{ errors.first('numero_cuenta') }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="clabe">Clabe</label>
            <div class="col-md-9">
              <input type="text" name="clabe" v-validate="'numeric|max:18'" v-model="datbancos.clabe" class="form-control" placeholder="Clabe" autocomplete="off" id="clabe" data-vv-as="CLABE">
              <span class="text-danger">{{ errors.first('clabe') }}</span>
            </div>
          </div>



          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="banco" >Banco</label>
            <div class="col-md-9">
              <v-select :options="optionsvs" name="banco" v-model="datbancos.banco" label="name" v-validate="'required'" data-vv-as="Banco"></v-select>
              <span class="text-danger">{{ errors.first('banco') }}</span>
            </div>
          </div>

          <!-- </form> -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
          <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarDatosBancarios(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
          <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarDatosBancarios(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
      url: '/datosbanemp',
      empleado: null,
      desabilitado: 0,
      isLoadingDetalle: false,
      optionsvs: [],
      datbancos: {

        id: 0,
        empleado_id: 0,
        numero_tarjeta: '',
        numero_cuenta: '',
        clabe: '',
        banco: '',
        condicion: 0
      },
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      disabled: 0,
      isLoading: false,
      columnsdatosbancarios: [
        'id',
        'numero_tarjeta',
        'numero_cuenta',
        'clabe',
        'bnombre',
      ],
      tableDatabancos: [],
      optionsbancos: {
        headings: {
          'id' : 'Acciones',
          'numero_tarjeta' : 'Número de Tarjeta',
          'numero_cuenta' : 'Número de cuenta',
          'clabe' : 'Clabe',
          'bnombre' : 'Banco',
          'condicion' : 'Estado'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: [
          'empleado_id',
          'numero_tarjeta',
          'numero_cuenta',
          'clabe',
          'bnombre',
          'condicion'
        ],
        filterable: [
          'empleado_id',
          'numero_tarjeta',
          'numero_cuenta',
          'clabe',
          'bnombre',
          'condicion'
        ],
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
      axios.get('/bancos').then(response => {
        for (var i = 0; i < response.data.length; i++) {
          this.optionsvs.push(
            {
              id:response.data[i].id,
              name:response.data[i].nombre
            });          }
          })

          .catch(function (error) {
            console.log(error);
          });
    },
    actdesact(id,tipo){
      if(tipo){
        this.swal_titulo = 'Esta seguro de activar estos datos bancarios?';
        this.swal_tle = 'Activado';
        this.swal_msg = 'El registro ha sido activado con éxito.';
      }else{
        this.swal_titulo = 'Esta seguro de desactivar estos datos bancarios?';
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
            me.cargarDatosBan(me.empleado);
          }).catch(function (error) {
            console.log(error);
          });
        } else if (
          result.dismiss === swal.DismissReason.cancel
        ) {
        }
      })
    },
    guardarDatosBancarios(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/datosbanemp/registrar' : '/datosbanemp/actualizar',
            data: {
              'id': this.datbancos.id,
              'empleado_id' : this.datbancos.empleado_id,
              'numero_tarjeta': this.datbancos.numero_tarjeta,
              'numero_cuenta': this.datbancos.numero_cuenta,
              'clabe': this.datbancos.clabe,
              'banco_id': this.datbancos.banco.id,
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              me.cargarDatosBan(me.empleado);
              if(!nuevo){
                toastr.success('Datos Bancarios Actualizada Correctamente');
              }
              else
              {
                toastr.success('Datos Bancarios Registrado Correctamente');
              }
            } else {
              toastr.error("Notifique al administrador e intente más tarde","Error")
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
        case "bancos":
        {
          switch(accion){
            case 'registrar':
            {
              this.isLoading=false;
              this.modal = 1;
              this.tituloModal = 'Registrar datos bancarios';
              // Utilerias.resetObject(this.datbancos);
              this.datbancos.empleado_id = id;
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar':
            {
              this.modal=1;
              this.tituloModal='Actualizar datos bancarios';
              this.datbancos.id=data['id'];
              this.tipoAccion=2;
              this.datbancos.numero_tarjeta = data['numero_tarjeta'];
              this.datbancos.numero_cuenta = data['numero_cuenta'];
              this.datbancos.clabe = data['clabe'];
              this.datbancos.banco = {id:data['banco_id'], name:data['bnombre']};
              console.log(data);
              break;
            }
          }
        }
      }
    },
    cargarDatosBan(dataEmpleado = [], id) {
      Utilerias.resetObject(this.datbanemp);
      this.isLoading = true;
      let me=this;
      this.empleado = dataEmpleado;
      axios.get(me.url + '/' + dataEmpleado.id + '/' +'datosbanemp').then(response => {
        var contador = response.data.length;

        if (contador >= 5){

          this.desabilitado = 1;
        }
        else{
          this.desabilitado = 0;
        }
        me.tableDatabancos = response.data;
        me.isLoading = false;
        me.datbancos.id=response.data['id'];
        me.datbancos.empleado_id = response.data['empleado_id'];
        me.datbancos.numero_tarjeta = response.data['numero_tarjeta'];
        me.datbancos.numero_cuenta = response.data['numero_cuenta'];
        me.datbancos.clabe = response.data['clabe'];
        me.datbancos.banco = response.data['banco'];
      })
      .catch(function (error) {
        console.log(error);
      });
    },
  },
  mounted() {
    this.getData();

  }
}
</script>
