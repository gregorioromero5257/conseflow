<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Entradas Pendientes

        </div>
        <div class="card-body">
          <v-client-table :data="tableData" :columns="columns" :options="options">

            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <!-- <button v-show="PermisosCrud.Delete"  type="button" class="dropdown-item" @click="eliminarpartidaentrada(props.row)" >
                      <i class="icon-trash"></i>&nbsp; Eliminar partida
                    </button> -->
                    <button  type="button" class="dropdown-item" @click="asociarpartida(props.row)" >
                      <i class="icon-trash"></i>&nbsp; Asociar partida
                    </button>
                    <button  type="button" class="dropdown-item" @click="actualizarpartida(props.row)" >
                      <i class="icon-trash"></i>&nbsp; Actualizar partida
                    </button>
                  </div>
                </div>
              </div>
            </template>

            <template slot="status" slot-scope="props">
              <!-- {{props.row}} -->
              <template v-if="props.row.almacene_id == null" >
                <button type="button" class="btn btn-success btn-sm" @click="abrirModalAlmacen(props.row)" >
                  <i class="fas fa-boxes"></i>&nbsp;Almacenar en ...&nbsp;&nbsp;</button>
                </template>
                <template v-if="props.row.almacene_id != null" >
                  <button type="button" class="btn btn-greg btn-sm" @click="abrirModalAlmacen(props.row)" >
                    <i class="fas fa-boxes"></i>&nbsp;Almacenado en ...</button>
                  </template>
                </template>

              </v-client-table>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <fieldset>
                <legend>Registrar entrada</legend>
                <br>

                <div class="form-group row">
                  <label for="inputArticulo" class="col-md-1 form-control-label">Artículo</label>
                  <div class="col-md-8">
                    <div class="input-group mb-3">
                      <textarea name="name" rows="1" cols="80" class="form-control" v-model="partida_entrada.nombre_articulo" placeholder="Articulo" readonly></textarea>
                      <!-- <input type="text" class="form-control" v-model="partida_entrada.nombre_articulo" placeholder="Articulo" readonly> -->
                      <div class="input-group-append">
                        <button  class="btn btn-secondary" type="button" @click.prevent="abrirBusArticulo()">Buscar</button>
                        <button class="btn btn-secondary" type="button" @click="agregarArt()">Agregar</button>
                      </div>
                    </div>
                    <bus-articulo ref="busqueda" @enviar="editarArt($event)" @clicked="onClickArticuloSeleccionado"></bus-articulo>
                    <articulo ref="articulo" @cerrarAbrir="abrirBusArticulo()" @cerrar="abrirBusArticulo()"></articulo>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-2 mb-3">
                    <label>Cantidad</label>
                    <input class="form-control" type="text" v-validate="'decimal:2|required'" v-model="partida_entrada.cantidad" data-vv-name="Cantidad">
                    <span class="text-danger">{{errors.first('Cantidad')}}</span>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Proyecto</label>
                    <v-select :options="listaStocks" v-model="partida_entrada.stock" label="name" name="proyecto" data-vv-name="proyecto" v-validate="'required'"></v-select>
                    <span class="text-danger">{{errors.first('proyecto')}}</span>
                  </div>
                </div>

                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>Folio</label>
                    <input class="form-control" type="text" v-model="partida_entrada.folio" data-vv-name="Cantidad">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Proveedor</label>
                    <v-select :options="listaProveedor" v-validate="'required'" v-model="partida_entrada.proveedor" label="name" name="proveedor" data-vv-name="Proveedor" ></v-select>
                    <span class="text-danger">{{errors.first('Proveedor')}}</span>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="card-footer">
              <vue-element-loading :active="isLoading"/>
              <button type="button" class="btn btn-secondary" @click="cancelar()"><i class="fas fa-window-close"></i>&nbsp;Cancelar</button>
              <button type="button" v-if="partida_entrada.nombre_articulo != '' && boton_actualizar == 0" class="btn btn-dark" @click="guardarPR();"><i class="fas fa-plus"></i>&nbsp;Agregar</button>
              <button type="button" v-if="boton_actualizar == 1" class="btn btn-dark" @click="actualizarPR();"><i class="fas fa-plus"></i>&nbsp;Actualizar</button>
            </div>
          </div>

          <!--Inicio del modal agregar/actualizar almacen-->
          <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_almacen}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
              <div class="modal-content">

                <div>
                  <vue-element-loading :active="isLoadingAlmacen"/>
                  <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModalAlmacen"></h4>
                    <button type="button" class="close" @click="cerrarModalAlmacen()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="almacen_id">Almacen</label>
                      <div class="col-md-9">
                        <select class="form-control" id="almacen_id"  name="almacen_id"  v-model="almacen.id" @change="almac"  data-vv-as="Stand">
                          <option v-for="item in listaAlmacenes" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                        <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="stand_id">Stand</label>
                      <div class="col-md-9">
                        <select class="form-control" id="stand_id"  name="stand_id"  v-model="almacen.stand_id" @change="niv" data-vv-as="Stand">
                          <option v-for="item in listaStand" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                        <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="nivel_id">Nivel</label>
                      <div class="col-md-9">
                        <select class="form-control" id="nivel_id"  name="nivel_id"  v-model="almacen.nivel_id"  data-vv-as="Nivel">
                          <option v-for="item in listaNivel" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                        <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="guardarDatosAlmacen()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                    <button type="button" class="btn btn-outline-dark" @click="cerrarModalAlmacen()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                  </div>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!--Fin del modal agregar almacen-->

          <!--Inicio del modal agregar/actualizar almacen-->
          <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_asc}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
              <div class="modal-content">

                <div>
                  <div class="modal-header">
                    <h4 class="modal-title">Asociar partida</h4>
                    <button type="button" class="close" @click="cerrarModalAsc()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <v-client-table :data="dataAsc" :options="optionAsc" :columns="columnsAsc" @row-click="seleccionarArticuloAsc">
                      </v-client-table>
                      <div class="form-row">
                        <div class="col-md-12 mb-3">
                          <label> <b>Articulo a asociar : </b> </label>
                          <label>{{nombre_art_org}}</label>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-12 mb-3">
                          <label> <b>Articulo seleccionado : </b> </label>
                          <label>{{nombre_art}}</label>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-3 mb-3">
                          <label>Cantidad</label>
                          <input type="text" v-model="cantidad_asc" class="form-control">
                        </div>
                      </div>
                  </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="guardarDatosAsc()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                    <button type="button" class="btn btn-outline-dark" @click="cerrarModalAsc()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                  </div>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!--Fin del modal agregar almacen-->


        </div>
      </main>
    </template>
    <script>
    import Utilerias from '../Herramientas/utilerias.js';
    var config = require('../Herramientas/config-vuetables-client').call(this);

    export default {
      data(){
        return {
          isLoading : false,
          boton_actualizar : 0,
          PermisosCrud : {},
          ep_id : '',
          id_par_req_oc : '',
          cantidad_asc : '',
          nombre_art : '',
          nombre_art_org : '',
          modal_asc : 0,
          tableData : [],
          columns : ['id','status','descripcion','cantidad','nombre_corto'],
          options: {
            headings: {
              id: 'Acciones',
              status : '-',
              nombre_corto : 'Proyecto',
            },
            perPage: 5,
            perPageValues: [],
            skin: config.skinBusqueda,
            sortIcon: config.sortIcon,
            filterByColumn: true,
            texts:config.texts,
          },
          dataAsc : [],
          columnsAsc : ['descripcion','cantidad_entrada','folio'],
          optionAsc: {
            headings: {
            },
            perPage: 3,
            perPageValues: [],
            skin: config.skinBusqueda,
            sortIcon: config.sortIcon,
            filterByColumn: true,
            texts:config.texts,
          },
          listaStocks: [],
          listaProveedor: [],
          partida_entrada : {
            nombre_articulo : '',
            cantidad : '',
            stock : '',
            articulo_id : '',
            folio : '',
            proveedor : '',
            id : 0,
          },
          articulo_dato : '',
          modal_almacen : 0,
          tituloModalAlmacen : '',
          almacen : {
            id : 0,
            ida: '',
            nombre : '',
            stand_id : 0,
            stand : '',
            nivel_id : 0,
            nivel : '',
          },
          listaAlmacenes : [],
          listaStand : [],
          listaNivel : [],
          isLoadingAlmacen : false,
          datospartida : null,
        }
      },
      components: {
        'bus-articulo': require('./BusArticuloUno.vue'),
        'articulo' : require('./ArticuloUno.vue')
      },
      methods : {
        InitCrud(){
          this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
        },

        getData(){
          this.tableData = [];
          axios.get('almacen/entradas/pendientes/get/').then(response => {
            this.tableData = response.data;
          }).catch(e => {
            console.error(e);
          });
        },

        getListas(){
          this.listaStocks = [];
          axios.get('/stock').then(response => {
            for (var i = 0; i < response.data.length; i++) {
              this.listaStocks.push({
                id: response.data[i].id,
                name : response.data[i].nombre,
              });
            }
          })
          .catch(function (error) {
            console.log(error);
          });

          let me = this;
          axios.get('/proveedores/activos/1').then(response =>{
            me.listaProveedor = [];
            response.data.forEach(data =>{
              me.listaProveedor.push({
                id: data.id,
                name: data.nombre + ' ' + data.razon_social,
                nombre : data.nombre,
              });
            });
            me.listaProveedor.push({
              id: 0,
              name: 'OTRO',
              nombre : '--',
            });
          }).catch(function (error){
            console.log(error);
          });

          axios.get('/almacen/ver').then(response => {
            me.listaAlmacenes = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        },

        abrirBusArticulo() {
          let me = this;
          me.$refs.busqueda.mostrarBusArticulo();
          // me.$refs.myTable.refresh();
        },

        agregarArt() {
          let me = this;
          me.$refs.articulo.abrirModal('articulo','registrar');
        },

        editarArt(data){
          let me = this;
          me.$refs.articulo.abrirModal('articulo','actualizar',data);
        },


        onClickArticuloSeleccionado(value) {
          // console.log(value,'dsdas');
          this.articulo_dato = value;
          this.partida_entrada.articulo_id = value.id;
          this.partida_entrada.nombre_articulo = value.descripcion;
          this.partida_entrada.cantidad = 1;
        },

        cancelar(){
          this.partida_entrada.nombre_articulo = '';
          this.partida_entrada.articulo_id = '';
          this.partida_entrada.cantidad = '';
          this.partida_entrada.stock = '';
          this.partida_entrada.folio = '';
          this.partida_entrada.proveedor = '';
        },

        guardarPR(){
          this.isLoading = true;
          this.$validator.validate().then(result =>{
            if (result) {

              axios.post('almacen/entradas/pendientes/guardar/',{
                articulo_id : this.partida_entrada.articulo_id,
                cantidad : this.partida_entrada.cantidad,
                stock : this.partida_entrada.stock.id,
                folio : this.partida_entrada.folio,
                proveedor_id : this.partida_entrada.proveedor.id,
              }).then(response => {
                this.getData();
                toastr.success('Correcto !!!');
                this.cancelar();
                this.isLoading = false;
              }).catch(e => {
                console.error(e);
                this.isLoading = false;
              });

            }
          });
        },

        abrirModalAlmacen(data = []){
          // console.log(data);
          this.modal_almacen = 1;
          this.tituloModalAlmacen = 'Agregar a almacen';
          this.datospartida = data;
          this.almacen.id = data['almacene_id'];

          let me=this;
          var idalmacen =data['almacene_id'];

          axios.get('/almacen/verstand/'+idalmacen).then(response => {
            me.listaStand = response.data;
            this.almacen.stand_id = data['stand_id'];
            var idstand = data['stand_id'];
            axios.get('/almacen/vernivel/'+idstand).then(response => {
              me.listaNivel = response.data;
              this.almacen.nivel_id = data['nivel_id'];
            })
          })
          .catch(function (error) {
            console.log(error);
          });

        },

        cerrarModalAlmacen(){
          this.modal_almacen = 0;
        },


        /**
        * [almac Metodo de consulta a la BD ]
        * @return {Response} [description]
        */
        almac(){
          let me = this;
          this.isLoadingAlmacen = true;
          axios.get('almacen/verstand/' + this.almacen.id).then(response => {
            me.listaStand = response.data;
            me.isLoadingAlmacen = false;
          })
          .catch(function (error) {
            console.log(error);
          });
        },

        /**
        * [niv Metodo de consulta a la BD ]
        * @return {Response} [description]
        */
        niv(){
          let me = this;
          this.isLoadingAlmacen = true;
          axios.get('almacen/vernivel/' + this.almacen.stand_id).then(response => {
            me.listaNivel = response.data;
            me.isLoadingAlmacen = false;
          })
          .catch(function (error) {
            console.log(error);
          });
        },

        /**
        * [guardarDatosAlmacen Metodo que actualiza la partida de la entrada agregando los datos de almacen]
        * @return {Response} [status = true]
        */
        guardarDatosAlmacen(){

          let me = this;
          axios.put( 'almacen/entradas/almacen/put/',{
            'id': me.datospartida.id,
            'almacene_id': this.almacen.id,
            'nivel_id': this.almacen.nivel_id,
            'stand_id': this.almacen.stand_id,
          }).then(function (response) {

              toastr.success('Guardado Correctamente')
              me.getData();
              me.cerrarModalAlmacen();
               Utilerias.resetObject(me.almacen);

          }).catch(function (error) {
            console.log(error);
          });

        },

        eliminarpartidaentrada(data){
          // console.log(data);
          axios.get('almacen/entradas/pendientes/delete/' + data.id).then(response => {
            if (response.data.estado == 2) {
              toastr.warning('No se puede eliminar porque ya contiene tiene salidas');
            }else if (response.data.estado == 1) {
              toastr.success('Eliminado Correctamente');
              this.getData();
            }
          }).catch(e => {
            console.error(e);
          });
        },

        asociarpartida(data){
            // console.log(data);
            this.modal_asc = 1;
            this.cantidad_asc = data.cantidad;
            this.nombre_art_org = data.descripcion;
            this.ep_id = data.id;
            axios.get('get/partidas/oc/pendientes/' + data.proveedor_id + '&' + data.proyecto_id).then(response => {
              this.dataAsc = response.data;
            }).catch(e => {
              console.error(e);
            });

        },

        cerrarModalAsc(){
          this.modal_asc = 0;
        },


        seleccionarArticuloAsc(data){
          // console.log(data,'dsd');
          this.nombre_art = data.row.descripcion + ' ' + data.row.folio;
          this.id_par_req_oc = data.row.id;
        },


        guardarDatosAsc(){
          axios.post('guardar/partidas/oc/pendientes/asociado',{
            poc : this.id_par_req_oc,
            cantidad : this.cantidad_asc,
            ep_id : this.ep_id,
          }).then(response => {
              this.getData();
              toastr.success('Correcto !!!');
              this.cerrarModalAsc();
              this.nombre_art = '';
          }).catch(e => {
            console.error(e);
          });
        },

        actualizarpartida(data){
          this.boton_actualizar = 1;
          this.partida_entrada.nombre_articulo = data.descripcion;
          this.partida_entrada.stock = {id : data.proyecto_id ,name :data.nombre_corto };
          this.partida_entrada.id = data.id;
          this.partida_entrada.proveedor = {id: data.proveedor_id, name : data.proveedor_name};
          this.partida_entrada.cantidad = data.cantidad;
          console.log(data);
        },

        actualizarPR(){
          axios.post('actualizar/entrada/pendiente',{
            id : this.partida_entrada.id,
            proyecto : this.partida_entrada.stock.id,
            proveedor : this.partida_entrada.proveedor.id,
          }).then(response => {
            this.boton_actualizar = 0;
            toastr.success('Correcto !!!');
            this.getData();
            this.cancelar();
          }).catch(e => {
            console.error(e);
          });
        },


      },
      mounted(){
        this.getListas();
        this.getData();
        this.InitCrud();
      }
    }
    </script>
    <style>

    .btn-greg {
      color: #fff;
      background-color: #e9b443;
      border-color: #e9b443; }

      </style>
