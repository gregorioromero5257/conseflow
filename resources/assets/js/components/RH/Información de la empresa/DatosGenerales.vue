<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Datos generales
          <button type="button" class="btn btn-dark float-sm-right" :disabled="desabilitar == 1" @click="abrirModal('datos','registrar')">
            <i class="fas fa-plus">&nbsp;</i>Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" >
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <!-- v-show="PermisosCrud.Update" -->
                    <button  type="button" @click="abrirModal('datos','actualizar',props.row)" class="dropdown-item">
                      <i class="icon-pencil"></i>&nbsp; Actualizar datos generales
                    </button>
                  </div>
                </div>
              </div>
            </template>
          </v-client-table>
        </div>
      </div>

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
                  <input type="text" class="form-control" v-validate="'required'" v-model="datos.nombre" data-vv-name="nombre" placeholder="Nombre">
                  <span class="text-danger">{{ errors.first('nombre') }}</span>
                </div>
                <div class="form-group col-md-6">
                  <label> RFC</label>
                  <input type="text" class="form-control" v-validate="'required'" v-model="datos.rfc" data-vv-name="rfc" placeholder="RFC">
                  <span class="text-danger">{{ errors.first('rfc') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label> Razón social</label>
                  <input type="text" class="form-control" v-validate="'required'" v-model="datos.razon_social" data-vv-name="nombre" placeholder="Nombre">
                  <span class="text-danger">{{ errors.first('nombre') }}</span>
                </div>
                <div class="form-group col-md-6">
                  <label> Regimen Fiscal</label>
                  <input type="text" class="form-control" v-validate="'required'" v-model="datos.regimenfiscal" data-vv-name="nombre" placeholder="Nombre">
                  <span class="text-danger">{{ errors.first('nombre') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Calle </label>
                  <input type="text" class="form-control" v-validate="'required'" v-model="datos.calle" data-vv-name="calle" placeholder="Calle">
                  <span class="text-danger">{{ errors.first('calle') }}</span>
                </div>
                <div class="form-group col-md-3">
                  <label>&nbsp;# Interior </label>
                  <input type="text" class="form-control"  v-model="datos.numero_interior" data-vv-name="numero_interior" placeholder="numero_interior">
                  <span class="text-danger">{{ errors.first('numero_interior') }}</span>
                </div>
                <div class="form-group col-md-3">
                  <label>&nbsp;# Exterior </label>
                  <input type="text" class="form-control" v-validate="'required'" v-model="datos.numero_exterior" data-vv-name="numero_exterior" placeholder="numero_exterior">
                  <span class="text-danger">{{ errors.first('numero_exterior') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-3">
                  <label>&nbsp;Código Postal </label>
                  <input type="text" class="form-control" v-validate="'required'" v-model="datos.codigo_postal" data-vv-name="codigo_postal" placeholder="codigo_postal">
                  <span class="text-danger">{{ errors.first('codigo_postal') }}</span>
                </div>
                <div class="form-group col-md-3">
                  <label>&nbsp;Colonia </label>
                  <input type="text" class="form-control" v-validate="'required'" v-model="datos.colonia" data-vv-name="municipio" placeholder="municipio">
                  <span class="text-danger">{{ errors.first('municipio') }}</span>
                </div>
                <div class="form-group col-md-3">
                  <label>&nbsp;Municipio </label>
                  <input type="text" class="form-control" v-validate="'required'" v-model="datos.municipio" data-vv-name="municipio" placeholder="municipio">
                  <span class="text-danger">{{ errors.first('municipio') }}</span>
                </div>
                <div class="form-group col-md-3">
                  <label>&nbsp;Entidad Federativa </label>
                  <input type="text" class="form-control" v-validate="'required'" v-model="datos.entidad_federativa" data-vv-name="entidad_federativa" placeholder="entidad_federativa">
                  <span class="text-danger">{{ errors.first('entidad_federativa') }}</span>
                </div>
              </div>




        </div>
        <div class="modal-footer">
          <!-- <vue-element-loading :active="isLoading"/> -->
          <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
          <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
          <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>



</div>
</main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      columns : ['id','rfc','razon_social','nombre','regimenfiscal'],
      tableData : [],
      modal : 0,
      tituloModal : '',
      desabilitar : 0,
      tipoAccion : 0,
      options : {
        headings: {
          'id' : 'Acciones',
          'rfc' : 'RFC',
          'regimenfiscal' : 'Regimen fiscal',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      datos : {
        id : '',
        nombre : '',
        rfc : '',
        razon_social : '',
        nombre : '',
        regimenfiscal : '601 General de Ley Personas Morales',
        calle : '',
        numero_interior : '',
        numero_exterior : '',
        codigo_postal : '',
        colonia : '',
        municipio : '',
        entidad_federativa : '',

      },
    }
  },
  methods : {
    /**
    * [getData función que se activo al momento de entrar a el]
    */
    getData(){

      axios.get('/datosgeneral').then(response => {

          this.tableData = response.data;
         response.data.length > 2 ? this.desabilitar = 1 : this.desabilitar = 0;
      }).catch(error => {
        console.error(error);
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
        case "datos":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal= 1;
              this.tituloModal = 'Registrar datos generales';
              this.tipoAccion=1;
              break;
            }
            case 'actualizar':
            {
              console.log(data);
              this.modal= 1;
              this.tituloModal = 'Actualizar datos generales';
              this.tipoAccion=2;
              this.datos.id = data['id'];
              this.datos.rfc = data['rfc'];
              this.datos.razon_social = data['razon_social'];
              this.datos.nombre = data['nombre'];
              this.datos.regimenfiscal = data['regimenfiscal'];
              this.datos.calle = data['calle'];
              this.datos.numero_interior = data['numero_interior'];
              this.datos.numero_exterior = data['numero_exterior'];
              this.datos.codigo_postal = data['codigo_postal'];
              this.datos.colonia = data['colonia'];
              this.datos.municipio = data['municipio'];
              this.datos.entidad_federativa = data['entidad_federativa'];

            }
          }
        }
      }
    },

    /**
    * [guardar Funcion que guarda o actualiza los encabezados de las facturas]
    * @param nuevo
    * @return
    */
    guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/datosgeneral' : '/datosgeneral/'+ this.datos.id,
            data: {
              // id : this.datos.id,
              rfc : this.datos.rfc,
              razon_social : this.datos.razon_social,
              nombre : this.datos.nombre,
              regimenfiscal : this.datos.regimenfiscal,
              calle : this.datos.calle,
              numero_interior : this.datos.numero_interior,
              numero_exterior : this.datos.numero_exterior,
              codigo_postal : this.datos.codigo_postal,
              colonia : this.datos.colonia,
              municipio : this.datos.municipio,
              entidad_federativa : this.datos.entidad_federativa,
            }
          }).then(function (response) {
            me.cerrarModal();
            me.getData();
          }).catch(function (error) {
            console.log(error);
          });
        }
      });
    },

    cerrarModal(){
      this.modal = 0;
    }
  },
  mounted(){
    this.getData();
  }
}
</script>
