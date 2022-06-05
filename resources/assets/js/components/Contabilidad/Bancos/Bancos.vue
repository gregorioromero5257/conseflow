<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Bancos
          <button type="button" @click="abrirModal('bancos','registrar')" class="btn btn-dark float-sm-right">
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
              <button type="button" @click="abrirModal('bancos','actualizar',props.row)" class="dropdown-item">
                <i class="icon-pencil"></i>&nbsp; Actualizar Banco
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
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <label for="text-input">Nombre banco</label>
                <v-select :options="optionsvs" v-model="bancos.catalogo_banco_id" label="name" v-validate="'required'" ></v-select>
                <span class="text-danger">{{ errors.first('catalogo_banco_id') }}</span>
              </div>
              <div class="form-group">
                <label for="text-input">Número cuenta</label>
                <input type="number" v-validate="'required'" name="numero_cuenta" v-model="bancos.numero_cuenta" class="form-control" placeholder="Clave">
                <span class="text-danger">{{ errors.first('numero_cuenta') }}</span>
              </div>
              <div class="form-group">
                <label for="text-input">Número clabe</label>
                <input type="number" v-validate="'required'" name="numero_clave" v-model="bancos.numero_clave" class="form-control" placeholder="Ciudad">
                <span class="text-danger">{{ errors.first('numero_clave') }}</span>
              </div>
              <div class="form-group">
                <label for="text-input">Total</label>
                <input type="number" v-validate="'required'" name="total" v-model="bancos.total" class="form-control" placeholder="Total">
                <span class="text-danger">{{ errors.first('total') }}</span>
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
      columns : ['id','nombre','numero_cuenta','numero_clave','total'],
      tableData : [],
      tipoAccion : 0,
      optionsvs: [],
      isLoading : false,
      bancos : {
        id : 0,
        catalogo_banco_id : '',
        numero_cuenta : 0,
        numero_clave : 0,
        total : '',
      },
      options: {
        headings: {
         id: 'Acciones',
         numero_cuenta : 'No. Cuenta',
         numero_clave: 'No. Clabe',
         total: 'Total',
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
      axios.get('/bancoscontabilidad').then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
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
        case "bancos":
        {
          switch(accion){
            case 'registrar':
            {
              Utilerias.resetObject(this.bancos);
              this.modal= 1;
              this.tituloModal = 'Registrar Banco';
              this.tipoAccion=1;
              break;
            }
            case 'actualizar':
            {
              Utilerias.resetObject(this.bancos);
              this.modal=1;
              this.tituloModal='Actualizar Banco';
              this.tipoAccion=2;
              this.bancos.id = data['id'];
              // this.bancos.catalogo_banco_id = data['nombre'];
              this.bancos.numero_cuenta = data['numero_cuenta'];
              this.bancos.numero_clave = data['numero_clave'];
              this.bancos.total = data['total'];
              this.bancos.catalogo_banco_id = {id:data['catalogo_banco_id'], name:data['nombre']};

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
            url: nuevo ? '/bancoscontabilidad' : '/bancoscontabilidad/'+this.bancos.id,
            data: {
              'catalogo_banco_id': this.bancos.catalogo_banco_id.id,
              'numero_cuenta': this.bancos.numero_cuenta,
              'numero_clave': this.bancos.numero_clave,
              'total': this.bancos.total,
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
