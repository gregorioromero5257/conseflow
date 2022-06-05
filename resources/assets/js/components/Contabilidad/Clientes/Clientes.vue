<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Clientes
          <button type="button" @click="abrirModal('clientes','registrar')" class="btn btn-primary float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>

          <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
            <template slot="id" slot-scope="props">
              <button type="button" @click="abrirModal('clientes','actualizar',props.row)" class="btn btn-warning btn-sm">
                <i class="icon-pencil"></i>
              </button>
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
                <label> Nombre</label>
                 <input type="text" class="form-control" v-validate="'required'" v-model="clientes.nombre" data-vv-name="nombre" placeholder="Nombre">
                 <span class="text-danger">{{ errors.first('nombre') }}</span>
              </div>
              <div class="form-group col-md-6">
                <label> RFC</label>
                <input type="text" class="form-control" v-validate="'required'" v-model="clientes.rfc" data-vv-name="rfc" placeholder="RFC">
                <span class="text-danger">{{ errors.first('rfc') }}</span>
              </div>
            </div>
            <div class="form-group">
              <label > Domicilio fiscal</label>
              <input type="text" class="form-control"  v-validate="'required'" v-model="clientes.domicilio_fiscal" data-vv-name="domicilio fiscal" placeholder="Domicilio Fiscal">
              <span class="text-danger">{{ errors.first('domicilio fiscal') }}</span>
            </div>
            <div class="form-group">
              <label > Domicilio alterno</label>
              <input type="text" class="form-control"  v-validate="'required'" v-model="clientes.domicilio_alterno" data-vv-name="domicilio alterno" placeholder="Domicilio Alterno">
              <span class="text-danger">{{ errors.first('domicilio alterno') }}</span>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label> Contacto</label>
                 <input type="text" class="form-control"  v-validate="'required'" v-model="clientes.contacto" data-vv-name="contacto" placeholder="Contacto">
                 <span class="text-danger">{{ errors.first('contacto') }}</span>
              </div>
              <div class="form-group col-md-6">
                <label> Teléfono</label>
                <input type="text" class="form-control"  v-validate="'required'" v-model="clientes.telefono" data-vv-name="telefono" placeholder="Teléfono">
                <span class="text-danger">{{ errors.first('telefono') }}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="inputCity">Ejecutivo asignado</label>
                <select class="form-control" id="empleado_id"  v-validate="'required'" data-vv-name="ejecutivo asignado" name="empleado_id" v-model="clientes.ejecutivo_asignado_id"  >
                  <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{ item.empleado.nombre }} {{ item.empleado.ap_paterno }} {{ item.empleado.ap_materno }}</option>
                </select>
                <span class="text-danger">{{ errors.first('ejecutivo asignado') }}</span>
              </div>
           </div>
            <div class="form-row">
              <!-- <div class="form-group col-md-6">
                <label for="inputCity">Banco</label>
                <select id="banco_contabilidad_id" name="banco_contabilidad_id" v-model="clientes.banco_contabilidad_id" class="form-control" @change="buscarBanco(clientes.banco_contabilidad_id)" >
                  <option v-for="item in listaBancos" :value="item.id" :key="item.id">{{ item.nombre}}</option>
                </select>
                <input type="text" class="form-control" v-model="clientes.banco_no_cuenta" placeholder="No. Cuenta" disabled>
              </div> -->

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
      columns : ['id','nombre','rfc','domicilio_fiscal','domicilio_alterno','contacto','telefono','nombre_empleado'],
      tableData : [],
      tipoAccion : 0,
      optionsvs: [],
      isLoading : false,
      listaBancos: [],
      listaEmpleados: [],
      clientes : {
        id : 0,
        nombre : '',
        rfc : '',
        domicilio_fiscal : '',
        domicilio_alterno : '',
        contacto: '',
        telefono: '',
        ejecutivo_asignado_id: '',
      },
      options: {
        headings: {
         id: 'Acción',
         rfc : 'RFC',
         domicilio_fiscal: 'Domicilio fiscal',
         domicilio_alterno: 'Domicilio alterno',
         nombre_empleado: 'Ejecutivo Asignado',
         telefono: 'Teléfono',
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
       axios.get('/clientes').then(response => {
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
       axios.get('/empleado').then(response => {
         me.listaEmpleados = response.data;
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
        me.clientes.banco_no_cuenta = response.data.numero_cuenta;
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
        case "clientes":
        {
          switch(accion){
            case 'registrar':
            {
              Utilerias.resetObject(this.clientes);
              this.modal= 1;
              this.tituloModal = 'Registrar Cliente';
              this.tipoAccion=1;
              break;
            }
            case 'actualizar':
            {
              // console.log(data);
              Utilerias.resetObject(this.clientes);
              this.modal = 1;
              this.tituloModal='Actualizar Cliente';
              this.tipoAccion = 2;
              this.clientes.id = data['id'];
              this.clientes.nombre = data['nombre'];
              this.clientes.rfc = data['rfc'];
              this.clientes.domicilio_fiscal = data['domicilio_fiscal'];
              this.clientes.domicilio_alterno = data['domicilio_alterno'];
              this.clientes.contacto = data['contacto'];
              this.clientes.telefono = data['telefono'];
              this.clientes.ejecutivo_asignado_id = data['ejecutivo_asignado_id'];

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
            url: nuevo ? '/clientes' : '/clientes/'+this.clientes.id,
            data: {
              'nombre': this.clientes.nombre,
              'rfc': this.clientes.rfc,
              'domicilio_fiscal': this.clientes.domicilio_fiscal,
              'domicilio_alterno': this.clientes.domicilio_alterno,
              'contacto' : this.clientes.contacto,
              'telefono' : this.clientes.telefono,
              'ejecutivo_asignado_id' : this.clientes.ejecutivo_asignado_id,
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
  },
  mounted() {
    this.getData();
  }
}
</script>
