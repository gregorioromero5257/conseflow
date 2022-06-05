<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card" v-show="!detallecontacto">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Clientes
          <button v-show="PermisosCRUD.Create" type="button" @click="abrirModal('clientes','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>

          <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                    <button v-show="PermisosCRUD.Update" type="button" @click="abrirModal('clientes','actualizar',props.row)" class="dropdown-item">
                      <i class="icon-pencil"></i>&nbsp; Actualizar cliente.
                    </button>

                    <button type="button" @click="cargardetallecontacto(props.row)" class="dropdown-item">
                      <i class="fas fa-eye"></i>&nbsp; Ver detalle contacto.
                    </button>

                  </div>
                </div>
              </div>
            </template>

          </v-client-table>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->

      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
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
                  <input type="text" class="form-control" v-validate="'required'" v-model="clientes.rfc" data-vv-name="RFC" placeholder="RFC">
                  <span class="text-danger">{{ errors.first('RFC') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Calle </label>
                  <input type="text" class="form-control" v-model="clientes.calle" data-vv-name="Calle" placeholder="Calle">
                  <span class="text-danger">{{ errors.first('Calle') }}</span>
                </div>

                <div class="form-group col-md-3">
                  <label>&nbsp;Número Exterior </label>
                  <input type="text" class="form-control" v-model="clientes.numero_exterior" data-vv-name="Número exterior" placeholder="Número exterior">
                  <span class="text-danger">{{ errors.first('Número exterior') }}</span>
                </div>
                <div class="form-group col-md-3">
                  <label>&nbsp;Número Interior </label>
                  <input type="text" class="form-control"  v-model="clientes.numero_interior" data-vv-name="Número interior" placeholder="Número interior">
                  <span class="text-danger">{{ errors.first('Número interior') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label>&nbsp;Código Postal </label>
                  <input type="text" class="form-control" v-model="clientes.codigo_postal" data-vv-name="Código postal" placeholder="Código postal">
                  <span class="text-danger">{{ errors.first('Código postal') }}</span>
                </div>
                <div class="form-group col-md-6">
                  <label>&nbsp;Colonia </label>
                  <input type="text" class="form-control" v-model="clientes.colonia" data-vv-name="Colonia" placeholder="Colonia">
                  <span class="text-danger">{{ errors.first('Colonia') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>&nbsp;Municipio </label>
                  <input type="text" class="form-control" v-model="clientes.municipio" data-vv-name="Municipio" placeholder="Municipio">
                  <span class="text-danger">{{ errors.first('Municipio') }}</span>
                </div>
                <div class="form-group col-md-4">
                  <label>&nbsp;Entidad federativa </label>
                  <input type="text" class="form-control" v-model="clientes.entidad_federativa" data-vv-name="Entidad federativa" placeholder="Entidad federativa">
                  <span class="text-danger">{{ errors.first('Entidad federativa') }}</span>
                </div>
              </div>
            <div class="form-group">
              <label > Domicilio alterno</label>
              <input type="text" class="form-control" v-model="clientes.domicilio_alterno" placeholder="Domicilio Alterno">
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
              <label for="inputCity">Contacto</label>
              <select class="form-control" id="contacto" data-vv-name="contacto" name="contacto" v-model="clientes.contacto"  >
                <option v-for="item in listaContacto" :value="item.nombre_contacto" :key="item.id">{{ item.nombre_contacto }}</option>
              </select>

            </div>
            <div class="form-group col-md-6">
              <label> Teléfono</label>
              <input type="text" class="form-control" v-model="clientes.telefono" data-vv-name="telefono" placeholder="Teléfono" >
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputCity">Ejecutivo asignado</label>
              <select class="form-control" id="empleado_id" data-vv-name="ejecutivo asignado" name="empleado_id" v-model="clientes.ejecutivo_asignado_id"  >
                <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{ item.empleado.nombre }} {{ item.empleado.ap_paterno }} {{ item.empleado.ap_materno }}</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
          <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
          <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--aqui va el otro componente-->
  <div v-show="detallecontacto">
    <contactodetalle ref = "contactodetalle" @contactodetalle:change="maestro"></contactodetalle>
  </div>

</div>
<!--Fin del modal-->
</main>
</template>

<script>

const Contactos = r => require.ensure([], () => r(require('./Contacto.vue')),'venta');

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  computed:{

  },
  data (){
    return {
      detallecontacto : false,
      PermisosCRUD : {},
      tituloModal : '',
      modal : 0,
      columns : ['id','nombre','rfc','contacto','telefono','nombre_empleado'],
      tableData : [],
      tipoAccion : 0,
      optionsvs: [],
      isLoading : false,
      listaBancos: [],
      listaEmpleados: [],
      listaContacto: [],
      clientes : {
        id : 0,
        nombre : '',
        rfc : '',
        domicilio_fiscal : '',
        domicilio_alterno : '',
        contacto: '',
        telefono: '',
        ejecutivo_asignado_id: '',
        calle : '',
        numero_interior : '',
        numero_exterior : '',
        codigo_postal : '',
        colonia : '',
        municipio : '',
        entidad_federativa : '',
      },
      options: {
        headings: {
          id: 'Acciones',
          rfc : 'RFC',
          domicilio_fiscal: 'Domicilio fiscal',
          domicilio_alterno: 'Domicilio alterno',
          nombre_empleado: 'Ejecutivo Asignado',
          telefono: 'Teléfono',
          cn: 'Contacto',
        },
        perPage: 10 ,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
    }
  },

  components:{
    'contactodetalle' : Contactos,
  },
  methods : {
    /**
    * [getData Obtencion de datos por medio de peticiones axios al controlador]
    * @return {Response} [respuesta almacenada en diferentes variables]
    */
    getData(){
      let me = this;
      this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
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

    cargardetallecontacto(dataContacto){
      this.detallecontacto = true;
      var ChildContacto = this.$refs.contactodetalle;
      ChildContacto.cargardetallecontacto(dataContacto);
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
              this.clientes.calle= data['calle'];
              this.clientes.numero_interior = data['numero_interior'];
              this.clientes.numero_exterior = data['numero_exterior'];
              this.clientes.codigo_postal = data['codigo_postal'];
              this.clientes.colonia = data['colonia'];
              this.clientes.municipio = data['municipio'];
              this.clientes.entidad_federativa = data['entidad_federativa'];
              this.clientes.ejecutivo_asignado_id = data['ejecutivo_asignado_id'];
              axios.get('/contactoc/'+data['id']).then(response =>{
                this.listaContacto = response.data;
              })
              .catch(function (error) {
                console.log(error);
              });

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
              'nombre': this.clientes.nombre.toUpperCase(),
              'rfc': this.clientes.rfc.toUpperCase(),
              // 'domicilio_fiscal': this.clientes.domicilio_fiscal.toUpperCase(),
              'domicilio_alterno': this.clientes.domicilio_alterno.toUpperCase(),
              'contacto' : this.clientes.contacto,
              'telefono' : this.clientes.telefono,
              'ejecutivo_asignado_id' : this.clientes.ejecutivo_asignado_id,
              'calle' : this.clientes.calle,
              'numero_interior' : this.clientes.numero_interior,
              'numero_exterior' : this.clientes.numero_exterior,
              'codigo_postal' : this.clientes.codigo_postal,
              'colonia' : this.clientes.colonia,
              'municipio' : this.clientes.municipio,
              'entidad_federativa' : this.clientes.entidad_federativa,
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              me.getData();
              if(!nuevo){
                toastr.success('Cliente Actualizado Correctamente');
              }
              else
              {
                toastr.success('Cliente Registrado Correctamente');
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
    maestro(){
      this.detallecontacto= false;
    },
  },
  mounted() {
    this.getData();
  }
}
</script>
