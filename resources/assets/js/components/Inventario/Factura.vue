<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <!-- <vue-element-loading :active="isLoading"/> -->
        <div class="card-header">
          <i class="fa fa-align-justify"></i> FACTURA.
          <button class="btn btn-dark float-sm-right" @click="abrirModal(1)">Agregar</button>
          <button type="button" v-show="detalle" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body" v-if="!detalle">
          <v-client-table :data="tableData" :options="options" :columns="columns">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" @click="abrirModal(2,props.row)" class="dropdown-item" >
                      <i class="icon-pencil"></i>&nbsp; Actualizar
                    </button>
                    <button type="button" @click="detalles(props.row)" class="dropdown-item" >
                      <i class="icon-eye"></i>&nbsp; Detalles
                    </button>
                  </div>
                </div>
              </div>
            </template>

            <template slot="pdf" slot-scope="props">
              <template v-if="props.row.pdf != ''">
                <button type="button" class="form-control" @click="descargar(props.row.pdf)">
                  <i class="fas fa-file-download"></i>&nbsp;Descargar
                </button>
              </template>
              <template v-if="props.row.pdf == ''">
                <span>Vacio</span>
              </template>
            </template>

            <template slot="xml" slot-scope="props">
              <template v-if="props.row.xml != ''">
                <button type="button" class="form-control" @click="descargar(props.row.xml)">
                  <i class="fas fa-file-download"></i>&nbsp;Descargar
                </button>
              </template>
              <template v-if="props.row.xml == ''">
                <span>Vacio</span>
              </template>
            </template>

          </v-client-table>
        </div>
        <div class="card-body" v-if="detalle">
          <v-client-table :data="tableDataDetalle" :options="optionsdetalle" :columns="columnsdetalle">
          </v-client-table>
          <hr>
          <div>
            {{data_detalle}}
            <div class="form-group row">
              <label for="inputArticulo" class="col-md-2 form-control-label">Artículo</label>
              <div class="col-md-8">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" v-model="partida.nombre_articulo" placeholder="Articulo" >
                  <div class="input-group-append">
                    <button class="btn btn-secondary" type="button" @click="abrirModalCodigos">Buscar</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label for="inputArticulo" class="col-md-2 form-control-label">Precio Unitario</label>
              <div class="col-md-3">
                <div class="input-group mb-3">
                  <input type="text" v-validate="'decimal:2'"  data-vv-name="Precio" class="form-control"  v-model="partida.precio_unitario" placeholder="Precio" >
                </div>
                <span class="text-danger">{{errors.first('Precio')}}</span>
              </div>
            </div>



          </div>
        </div>
        <div class="card-footer" v-if="detalle">
          <button type="button" class="btn btn-outline-dark" @click="cancelar()"><i class="fas fa-window-close"></i>&nbsp;Cancelar</button>
          <button type="button" class="btn btn-dark" @click="guardarPartida()"><i class="fas fa-plus"></i>&nbsp;Agregar</button>
        </div>

      </div>




      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <vue-element-loading :active="isLoading"/>
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>

              <div class="modal-header">
                <h4 class="modal-title" >Agregar Factura</h4>
                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->

                <div class="form-row">
                  <div class="col-md-6">
                    <label>UUID</label>
                    <input type="text" v-validate="'required'"
                    v-model="factura.uuid"
                    class="form-control" placeholder="UUID"
                    autocomplete="off" data-vv-name="UUID">
                    <span class="text-danger">{{ errors.first('UUID') }}</span>
                  </div>
                  <div class="col-md-4">
                    <label>Folio</label>
                    <input type="text" v-validate="'required'"
                    v-model="factura.folio"
                    class="form-control" placeholder="Folio"
                    autocomplete="off" data-vv-name="Folio">
                    <span class="text-danger">{{ errors.first('Folio') }}</span>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-4">
                    <label>Monto</label>
                    <input type="text" v-validate="'required|decimal:2'"
                    v-model="factura.monto"
                    class="form-control" placeholder="Monto"
                    autocomplete="off" data-vv-name="Monto">
                    <span class="text-danger">{{ errors.first('Monto') }}</span>
                  </div>
                </div>
                <br>
                <div class="form-row">

                  <!-- <div class="col-md-6"> -->
                  <template v-if="factura.pdf != ''">
                    <!-- <label>&nbsp;PDF</label> -->
                    <div class="form-group col-md-1">
                      <label>&nbsp;PDF</label>
                    </div>
                    <div class="form-group col-md-2">
                      <!-- <label>&nbsp;PDF</label> -->
                      <button type="button" class="form-control" @click="descargar(factura.pdf)">
                        <i class="fas fa-file-download"></i>&nbsp;Descargar
                      </button>
                    </div>
                    <div class="form-group col-md-2">
                      <!-- <label>&nbsp;PDF</label> -->
                      <button type="button" class="form-control" @click="cambiarpdf">
                        <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                      </button>
                    </div>
                  </template>
                  <template v-if="factura.pdf == ''">
                    <div class="form-group col-md-4">
                      <label>PDF</label>
                      <input type="file" class="form-control" name="pdf" @change="onChangeUno($event)">
                    </div>
                  </template>
                  <!-- </div> -->

                  <!-- <div class="col-md-6"> -->
                  <template v-if="factura.xml != ''">
                  <div class="form-group col-md-1">
                    <label>&nbsp;XML</label>
                  </div>
                  <br>
                    <div class="form-group col-md-2">
                      <button type="button" class="form-control" @click="descargar(factura.xml)">
                        <i class="fas fa-file-download"></i>&nbsp;Descargar
                      </button>
                    </div>
                    <div class="form-group col-md-2">
                      <!-- <label>&nbsp;XML</label> -->
                      <button type="button" class="form-control" @click="cambiarxml">
                        <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                      </button>
                    </div>
                  </template>
                  <template v-if="factura.xml == ''">
                    <div class="form-group col-md-4">
                      <label>XML</label>
                      <input type="file" class="form-control" name="xml" @change="onChangeDos($event)">
                    </div>
                  </template>

                  <!-- </div> -->
                </div>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Proveedor</label>
                    <v-select :options="listaProvedores"  id="proveedore_id"
                    name="proveedore_id"
                    v-model="factura.proveedor" label="name" data-vv-name="Proveedor" ></v-select>
                    <span class="text-danger">{{ errors.first('Proveedor') }}</span>
                  </div>
                  <div class="col-md-4">
                    <label>Comprador</label>
                    <select class="form-control" v-model="factura.comprador" >
                      <option v-for="t in comprador" :value="t.id" :key="t.id">{{t.nombre}}</option>
                    </select>
                  </div>
                </div>




                <!-- </form> -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarArt(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarArt(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                <!-- v-if="tipoAccion==1" -->
                <!-- <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarServicio(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button> -->
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal-->

      <!--Inicio del modal agregar articulos-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_codigos}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <vue-element-loading :active="isLoading"/>
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>

              <div class="modal-header">
                <h4 class="modal-title">Seleccionar</h4>
                <button type="button" class="close" @click="cerrarModalCodigos()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id">
                <div class="modal-body">
                  <v-client-table ref="myTable" :data="dataTableCodigos" :columns="columnscodigos" :options="optionscodigos" @row-click="seleccionarCodigo">
                  </v-client-table>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="cerrarModalCodigos()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar articulo-->

    </div>
  </main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default{

  data () {
    return {
      isLoading : false,
      modal : 0,
      modal_codigos : 0,
      detalle : false,
      tipoAccion : 0,
      comprador : [],////
      listaProvedores : [],////
      data_detalle : null,
      factura : {
        uuid : '',
        folio : '',
        monto : '',
        pdf : '',
        xml : '',
        proveedor : '',
        comprador : '',
        id : 0,
      },
      partida : {
        nombre_articulo : '',
        precio_unitario : '',
        idcod : '',
        idfac : '',
      },
      columns: ['id','uuid','folio','monto','pdf','xml','p_nombre','c_nombre'],
      tableData: [],
      options: {
        headings: {
          'id': 'Acción',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['uuid','folio','monto'],
        filterable: ['uuid','folio','monto'],
        filterByColumn: true,
        texts:config.texts
      },
      columnsdetalle: [],
      tableDataDetalle: [],
      optionsdetalle: {
        headings: {
          'id': 'Acción',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: [],
        filterable: [],
        filterByColumn: true,
        texts:config.texts
      },
      columnscodigos: ['codigo','nombre_a','nombre_u','nombre_n','nombre_c'],
      dataTableCodigos: [],
      optionscodigos: {
        headings: {
          'nombre_a': 'Nombre',
          'nombre_u': 'Ubicación',
          'nombre_n' : 'Nave',
          'nombre_c' : 'Categoria',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: [],
        filterable: [],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },

  methods : {


    getData(){

      axios.get('get/facturas/inventario').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });


    },

    getList(){
      axios.get('get/proveedores/facturas/inventario').then(response => {
        this.listaProvedores = [];
        response.data.forEach(data =>{
          this.listaProvedores.push({
            id : data.id,
            name : data.nombre + ' ' + data.rfc,
          });
        });
      }).catch(e => {
        console.error(e);
      });

      axios.get('get/compradores/facturas/inventario').then(response => {
        this.comprador = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('get/codigos/facturas/inventario').then(response => {
        this.dataTableCodigos = response.data;
      }).catch(e => {
        console.error(e);
      });

    },

    abrirModal(id, data = [] ){
      if(id == 1){
        Utilerias.resetObject(this.factura);
        this.modal = 1;
        this.tipoAccion = 1;
      }
      if (id == 2) {
        Utilerias.resetObject(this.factura);
        this.modal = 1;
        this.tipoAccion = 2;
        this.factura.id = data['id'];
        this.factura.uuid = data['uuid'];
        this.factura.folio = data['folio'];
        this.factura.monto = data['monto'];
        this.factura.pdf = data['pdf'];
        this.factura.xml = data['xml'];
        this.factura.proveedor = {id : data['proveedor_id'], name : data['p_nombre'] + ' ' +data['p_rfc']};
        this.factura.comprador = data['comprador_id'];
        console.log(data);
      }
    },

    cerrarModal(){
      this.modal = 0;
    },

    onChangeUno(e){
      this.factura.pdf = e.target.files[0];
    },
    onChangeDos(e){
      this.factura.xml = e.target.files[0];
    },

    cambiarpdf(){
      this.factura.pdf = '';
    },

    cambiarxml(){
      this.factura.xml = '';
    },

    guardarArt(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          let formData = new FormData();
          formData.append('id',this.factura.id);
          formData.append('uuid',this.factura.uuid);
          formData.append('folio',this.factura.folio);
          formData.append('monto',this.factura.monto);
          formData.append('pdf',this.factura.pdf);
          formData.append('xml',this.factura.xml);
          formData.append('proveedor',this.factura.proveedor == null ? '' : this.factura.proveedor.id);
          formData.append('comprador',this.factura.comprador == null ? '' : this.factura.comprador);
          axios({
            method: nuevo ? 'POST' : 'POST',
            url: nuevo ? 'store/facturas/inventario' : 'update/facturas/inventario',
            data: formData
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              if (response.data.status === 'error') {
                swal({
                  type: 'error',
                  html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
                });
              }else {
                me.cerrarModal();
                me.getData();
                if (!nuevo) {
                  toastr.success('Actualizado Correctamente');
                }else {
                  toastr.success('Agregado Correctamente');
                }
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

    descargar(archivo){
      if (archivo === null) {
        toastr.warning('No existe archivo a descargar');
        return false;
      }
      let me=this;
      axios({
        url: '/factura/inventario/descarga/' + archivo,
        method: 'GET',
        responseType: 'blob', // importante
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
        document.body.appendChild(link);
        link.click();
        //--Llama el metodo para borrar el archivo local una ves descargado--//
        axios.get('/factura/inventario/editar/' + archivo)
        .then(response => {
        })
        .catch(function (error) {
          console.log(error);
        });
        //--fin del metodo borrar--//
      });
    },


    detalles(data){
     this.detalle = true;
     this.data_detalle = data;
     // console.log(data);
    },

    maestro(){
      this.detalle = false;
    },

    abrirModalCodigos(){
      this.modal_codigos = 1;
    },

    cerrarModalCodigos(){
      this.modal_codigos = 0;
    },

    seleccionarCodigo(data){
      // console.log(data,'row');
      this.partida.nombre_articulo = data.row.codigo + ' ' + data.row.nombre_a + ' ' + data.row.nombre_u + ' ' + data.row.nombre_n + ' ' + data.row.nombre_c;
      this.partida.idcod = data.row.id;
      this.modal_codigos = 0;
    },




  },
  mounted() {
    this.getData();
    this.getList();
  }
}

</script>
