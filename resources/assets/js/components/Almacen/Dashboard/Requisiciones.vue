<template>
  <div class="card border-dark">
    <!-- {{detalles_solicitudes}} -->
    <!-- {{nuevo}} -->
    <vue-element-loading :active="isLoading"/>

    <div class="card-header bg-dark text-white">
      <i class="fa fa-align-justify"> </i> Requisiciones por completar:
      <button v-if="detalles_ver" type="button" @click="emitirEventoHijo(); maestro()" class="btn btn-secondary float-sm-right">
        <i class="icon-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <div class="card-body">
      <!-- <vue-element-loading :active="isLoading"/> -->
      <v-client-table :data="dataTable" :options="optionss" :columns="columnss" v-show="!detalle">
        <template slot="r.folio" slot-scope="props">
          <template v-if="props.row.comentario != null">
            <span class="text-danger">{{props.row.r.folio}}<br>{{props.row.comentario.comentario}} </span>
          </template>
          <template v-else>
            {{props.row.r.folio}}
          </template>
        </template>
        <template slot="r.id" slot-scope="props">
          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-grip-horizontal"></i> Acciones
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button type="button" @click="emitirEventoHijo(); cargardetalle(props.row.r.id);" class="dropdown-item" >
                  <i class="fas fa-eye"></i>&nbsp; Ver partidas
                </button>
              </div>
            </div>
          </div>
        </template>
        <template slot="r.condicion" slot-scope="props" >
          <button class="btn btn-sm btn-success" @click="autorizar(8, props.row.r.id)"><i class="far fa-check-square"></i>&nbsp; Si&nbsp;&nbsp;</button>
          <!-- <button class="btn btn-outline-success" @click="autorizar(8, props.row.r.id)"><i class="fas fa-check-square"></i></button> -->
          <button class="btn btn-sm btn-danger" @click="autorizar(0, props.row.r.id)"><i class="fas fa-window-close"></i>&nbsp; No</button>

        </template>

        <!-- <template slot="r.pendiente" slot-scope="props">
        <div v-if="props.row.r.pendiente == 1">
        <span class="text-danger">Pendiente</span>
      </div>
    </template> -->

  </v-client-table>



  <div v-show="detalles_ver">
    <table class="VueTables__table table table-hover table-sm">
      <thead class="table-light">
        <tr>
          <th scope="col" style="width:5%">Acciones</th>
          <th scope="col" style="width:30%">Artículo</th>
          <th scope="col" style="width:5%">Fecha requerida</th>
          <th scope="col" style="width:5%">Unidad</th>
          <th scope="col" style="width:5%">Cantidad</th>
          <th scope="col" style="width:5%">Cantidad a comprar</th>
          <th scope="col" style="width:20%">Cantidad en almacen</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,key) in detalles_solicitudes" :value="item.req.articulo_id" :key="item.req.articulo_id">
          <td>
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" @click="asignar(item.req)" class="dropdown-item">
                    <i class="fas fa-check"></i>Asignar
                  </button>
                  <button type="button" @click="quitar(item.req)" class="dropdown-item">
                    <i class="fas fa-close"></i>Corregir
                  </button>
                </div>
              </div>
            </div>
          </td>


          <td v-if="item.correccion != null"> <span class="text-danger">{{item.correccion.comentario}}</span> </td>

          <td v-if="item.correccion == null">{{item.req.descripcion}}</td>

          <td>{{item.req.frequerido}}</td>
          <td>{{item.req.unidad_articulo}}</td>
          <td>{{item.req.cantidades}}</td>
          <td>{{item.req.cantidad_compra}}</td>
          <td>
            <!-- {{item.req.cantidad_almacen}} -->
            <div class="input-group mb-2">
              <input type="number" v-validate="'decimal'" :value="item.req.cantidad_almacen" class="form-control" @input="updatecantidad(item,$event,key)" >
              <div class="input-group-append">
                <button type="button" class="btn btn-success" @click="guardaTemp(item.req,key)"> <i class="fas fa-check"></i>
                </button>
              </div>
            </div>

          </td>

        </tr>
      </tbody>
    </table>

  </div>
</div>
<!--Inicio del modal Mostrar Lote-->
<div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}"
role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
<div class="modal-dialog modal-dark modal-lg" role="document">
  <div class="modal-content">

    <div>
      <vue-element-loading :active="isLoading"/>
      <div class="modal-header">
        <h5 class="modal-title"> {{articulo_especifico == null ? '' : articulo_especifico.descripcion}}</h5>
        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" v-model="articulo" @keyup.enter="buscarArticulo()">
        <!-- {{articulo_especifico}} -->
        <!-- :data="data" -->
        <v-client-table ref="myTableArticulo" :data="data"  :columns="columns" :options="options" @row-click="seleccionarArticulo">
        </v-client-table>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary" @click="">Imprimir</button> -->
        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!--Fin del modal agregar almacen-->

<!--Inicio del modal Mostrar Lote-->
<div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_articulo}"
role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
<div class="modal-dialog modal-dark modal-lg" role="document">
  <div class="modal-content">

    <div>
      <vue-element-loading :active="isLoading"/>
      <div class="modal-header">
        <h5 class="modal-title"> </h5>
        <button type="button" class="close" @click="cerrarModalArticulo()" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <p class="card-text">Cantidad solicitada : <b>{{articulo_especifico == null ? '' : articulo_especifico.cantidades}}</b> </p>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="cantidad_existente">Cantidad existente </label>
                <input type="number" class="form-control" v-model="cantidad_existente" disabled id="cantidad_existente" placeholder="Cantidad existente">
              </div>
              <div class="form-group col-md-6">
                <label for="cantidad">Cantidad</label>
                <input type="number" class="form-control" id="cantidad" v-model="cantidad" @blur="validacioncantidad" placeholder="cantidad">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button"  class="btn btn-primary" @click="validacioncantidaduno(); validacioncantidados(); guardar();">Guardar</button>
        <button type="button" class="btn btn-secondary" @click="cerrarModalArticulo()">Cerrar</button>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!--Fin del modal agregar almacen-->

<!--Inicio del modal Mostrar Lote-->
<div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_quitar}"
role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
<div class="modal-dialog modal-dark modal-lg" role="document">
  <div class="modal-content">

    <div>
      <vue-element-loading :active="isLoading"/>
      <div class="modal-header">
        <h4 class="modal-title"> Actualizar articulos</h4>
        <button type="button" class="close" @click="cerraModalQuitar()" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <!-- {{result}} -->
            <!-- {{requisicione_id}} -->
            <p class="card-text">Agrege la cantidad de artículos a actualizar de <b>{{articulos_quitar == null ? '' : articulos_quitar.descripcion}}</b><br>
            </p>
            <div class="input_fields" id="input_impuesto"></div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="actualizar();">Actualizar</button>
        <button type="button" class="btn btn-outline-dark" @click="cerraModalQuitar()">Cerrar</button>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!--Fin del modal agregar almacen-->


</div>


</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data() {
    return {
      articulo : '',
      id_ : 0,
      isLoading: false,
      solicitudes: [],
      detalle: false,
      nuevo : null,
      articulo : null,
      solicitud: [],
      detalles_ver : false,
      detalles_solicitudes : null,
      cantidad_existente : 0,
      articulos_quitar : null,
      cantidad : '',
      tipo_cambio : 0,
      moneda : 0,
      validado : 1,
      modal : 0,
      modal_articulo : 0,
      modal_quitar : 0,
      requisicione_id : 0,
      articulo_especifico : null,
      datos_quitar : null,
      result : null,
      validar_quitar : 0,
      data : [],
      columns : ['nombre','descr','comentario','unidad','cantidad','folio'],
      options : {
        headings: {
          'descr' : 'Descripcion',
          'folio' : 'Proyecto'
        },
        perPage: 5,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        //  sortable: ['empleado.folio'],
        //  filterable: ['empleado.folio'],
        filterByColumn: true,
        texts:config.texts
      },
      dataTable : [],
      columnss : ['r.id','r.folio','r.nombrep','r.fecha_solicitud','r.nombre_solicita','r.nombre_autoriza','r.condicion','r.pendiente'],
      optionss :{
        headings: {
          'r.id': 'Acciones',
          'r.folio' : 'Folio',
          'r.pendiente' : 'Pendiente',
          'r.nombrep' : 'Proyecto',
          'r.nombre_solicita': 'Empleado que solicita',
          'r.nombre_autoriza' : 'Empleado que autoriza',
          'r.condicion' : 'Finalizar',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['r.id','r.folio','r.nombrep','r.fecha_solicitud','r.nombre_solicita','r.nombre_autoriza','r.condicion','r.pendiente'],
        filterable: ['r.id','r.folio','r.nombrep','r.fecha_solicitud','r.nombre_solicita','r.nombre_autoriza','r.condicion','r.pendiente'],
        filterByColumn: true,
        texts:config.texts
      }
    }
  },
  methods: {
    /**
    **
    */
    buscarArticulo(){
      axios.post('buscar/articulo/lotes',{articulo : this.articulo}).then(response =>{
        console.log(response,'data');
        this.data = response.data;
      }).catch(e => {
        console.error(e);
      })
      // this.$refs.buscarArticulo.refresh();
    },

    /**
    * [emitirEventoHijo Metodo que comunica con el componente padre al ser llamado]
    * @return {Response} [description]
    */
    emitirEventoHijo()
    {
      this.$emit('requisicionesalmacen:change');
    },

    /**
    * [getData Obtener las requisiciones que necesitan obtener la cantidad de articulos en almacen ]
    * @return {Response} [description]
    */
    getData() {
      this.isLoading = true;
      let me=this;
      axios.get('/requisicionesalmacen').then(response => {
        me.solicitudes= response.data;
        me.dataTable = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });

    },

    cargarcompras() {
      this.getData();
    },

    /**
    * [cargardetalle detalles de la requisicion selecionada]
    * @param  {Int} id [description]
    * @return {Response}    [description]
    */
    cargardetalle(id)
    {
      this.isLoading = true;
      this.detalles_ver = true;
      this.detalle = true;
      this.id_ = id;
      let me = this;
      axios.get('/requisicion/' + id + '/requisicion').then(response => {
        me.detalles_solicitudes = response.data;
        me.requisicione_id = response.data[0].req.id;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });

    },

    /**
    * [autorizar description]
    * @param  {Int} estado [description]
    * @param  {Int} id     [description]
    * @return {Response}        [description]
    */
    autorizar(estado , id)
    {

      this.isLoading = true;
      let me = this;
      var cadena = ['Almacén','Supervisor'];
      var cadenaid = [12,14];
      if (estado == 8) {
        swal({
          title: estado != 0  ? 'Esta seguro(a) autorizar la requisición?' : 'Esta seguro(a) de rechazar la requisición',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#4dbd74',
          cancelButtonColor: '#f86c6b',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          reverseButtons: true
        }).then(result =>{
          if (result.value) {
            axios.post('/autorizarequisicionproyectos',{
              id : id,
              estado : estado,
            }).then(function (response){
              me.isLoading = false;
              if (response.data == 8) {
                toastr.success('Correcto','Requisicion autorizada !!!');
              }
              else if (response.data == 0) {
                toastr.warning('Atención','Requisicion no autorizada !!!');
              }
              me.getData();
              me.isLoading = false;
            }).catch(function (error){
              console.error(error);
            });
          }else {
            me.isLoading = false;
          }
        });
      }else {



        swal({
          title: 'Motivo del rechazo de la requisición',
          input: 'textarea',
          // inputAttributes: {
          //   autocapitalize: 'off'
          // },
          inputValue : 'Motivo',
          showCancelButton: true,
          confirmButtonText: 'Continuar',
          showLoaderOnConfirm: true,
        }).then((result) => {
          if (result.value) {

            axios.post('enviarcomentariorechazorequi',{
              id : id,
              data : result.value,
            }).then(response => {

              console.log('Guardado correctamente');
              axios.post('/autorizarequisicionproyectos',{
                id : id,
                estado : estado,
              }).then(function (response){
                me.isLoading = false;
                if (response == 8) {
                  toastr.success('Correcto','Requisición recibida correctamente');
                }
                else {
                  toastr.warning('Atención','Requisición rechazada');
                }
                me.getData();
              }).catch(function (error){
                console.error(error);
              });

            });



          }
          console.log(result);
          me.isLoading = false;

        })



      }


      // swal({
      //   title:  'Esta seguro(a) de finalizar la revisión de articulos??',
      //   type: 'warning',
      //   showCancelButton: true,
      //   confirmButtonColor: '#4dbd74',
      //   cancelButtonColor: '#f86c6b',
      //   confirmButtonText: 'Aceptar!',
      //   cancelButtonText: 'Cancelar',
      //   reverseButtons: true
      // }).then(result =>{
      //   if (result.value) {
      //     this.isLoading = true;
      //     let me = this;
      //     axios.post('/autorizarequisicionproyectos',{
      //       id : id,
      //       estado : estado,
      //     }).then(function (response){
      //       toastr.success('Correcto','Requisición finalizada correctamente');
      //       me.getData();
      //       me.isLoading = false;
      //     }).catch(function (error){
      //       console.error(error);
      //     });
      //   }
      // });
    },

    guardar()
    {

      if (this.validado == 1) {
        this.isLoading = true;

        let me= this;
        axios.post('/guardarcantidadalmacen',{
          requisicion_id : this.id_,
          articulo_id : this.articulo_especifico.articulo_id,
          lote_almacen_id : this.articulo.lote_id,
          cantidad : this.cantidad,
        }).then(function (response){
          me.isLoading = false;
          toastr.success('Correcto','Cantidad agregada correctamente');
          me.cerrarModalArticulo();
          me.cargardetalle(me.requisicione_id);
          me.asignar(me.articulo_especifico);
          me.modal = 0;
        }).catch(function (error){
          console.error(error);
        });
      }
    },

    /**
    * [asignar --Devuelve el resultado de la busqueda de los articulos que se encuentre en el lote general
    * y el lote del proyecto ]
    * @param  {Array} data [description]
    * @return {Response}      [description]
    */
    asignar(data)
    {
      // console.log(data,'agsahsg');
      this.modal = 1;
      let me = this;
      me.articulo_especifico = data;

      // axios.get('/requisicionbusqueda/'+ data.proyecto_id + '&' + data.articulo_id).then(response => {
      //   me.data = response.data;
      // })
      // .catch(function (error) {
      //   console.log(error);
      // });
      // console.log(data['req'].descripcion);
    },

    /**
    * [quitar description]
    * @param  {[type]} data [description]
    * @return {[type]}      [description]
    */
    quitar(data){
      console.log(data);
      this.modal_quitar = 1;
      let me = this;
      me.articulos_quitar = data;
      // console.log(data);
      axios.get('/obtenerlotetemporal/'+ data.articulo_id + '&' + data.id).then(function (response){
        me.datos_quitar = response.data;
        // console.log(response.data);
        for (var i = 0; i < response.data.length; i++) {
          var result = {};
          //creamos el input
          var $input = "<b>"+response.data[i].lote_nombre+" "+response.data[i].nombre+"</b><div class='form-row'><div class='form-group col-md-4'><label for='cantidad_existente'>Cantidad actual en almacen apartado</label><input type='number' value='"+response.data[i].cantidad+"' class='form-control' disabled  placeholder='Cantidad existente'></div><div class='form-group col-md-4'><label for='cantidad'>Cantidad nueva</label><input type='number' class='form-control' value='0' placeholder='cantidad'></div></div>";
          //lo añadimos al contenedor
          $('.input_fields').append($input);

          //recorremos los inputs y los añadimos al objeto 'result'
          $('.input_fields input').each(function(i, e){
            result['text'+(i+1)] = $(e).val();
          });
          me.result = result;
        }
      }).catch(function (error){
        console.error(error);
      });

    },

    p(){
      alert('ww');
    },

    actualizar(){
      let me = this;
      var result = {};
      var cantidad = [];
      var cantidad_almacen = [];
      var ids = [];
      //recorremos los inputs y los añadimos al objeto 'result'
      $('.input_fields input').each(function(i, e){
        result['text'+(i+1)] = $(e).val();
      });
      me.result = result;

      for (var i=1; i <= Object.keys(me.result).length; i= i+2) {
        if (me.result['text'+i] != '') {
          cantidad_almacen.push(me.result['text'+i]);
        }
      }

      for (var j=2; j <= Object.keys(me.result).length; j= j+2) {
        if (me.result['text'+j] != '') {
          cantidad.push(me.result['text'+j]);
        }
      }

      for (var k = 0; k < (Object.keys(me.result).length)/2; k++) {
        if (parseFloat(cantidad[k]) > parseFloat(cantidad_almacen[k])) {
          toastr.warning('Atención','Existe una cantidad mayor que la cantidad de almacen');
          me.validar_quitar = 0;
          break;
        }
        else {
          me.validar_quitar = 1;
        }
      }
      if (me.validar_quitar == 1) {
        me.guardarActualizacion(cantidad,cantidad_almacen,ids);
      }
      ids.push(me.datos_quitar);

    },

    guardarActualizacion(cantidad,cantidad_almacen,ids){
      let me = this;
      // console.log('Actualizando');
      axios.post('/requisicionalmacenquitar',{
        cantidad : cantidad,
        cantidad_almacen : cantidad_almacen,
        ids : ids,
      }).then(function (response){
        toastr.success('Correcto','Datos actualizados correctamente');
        me.cerraModalQuitar();
        me.cargardetalle(me.requisicione_id);
      }).catch(function (error){
        console.error(error);
      })

    },

    cerrarModal() {
      this.modal = 0;
    },
    cerrarModalArticulo(){
      this.modal_articulo = 0;
      this.cantidad = '';
      // this.modal = 0;
      this.articulo = '';
    },
    cerraModalQuitar(){
      this.modal_quitar = 0;
      $("#input_impuesto").html('');

    },

    /**
    * [seleccionarArticulo Seleciona los elementos de una columna de un vue-table]
    * @param  {Array} data [description]
    * @return       [description]
    */
    seleccionarArticulo(data){
      // console.log(data,'Seleccionarondo delte ');
      this.articulo = data.row;
      this.modal_articulo = 1;
      this.cantidad_existente = data.row.cantidad;
    },

    /**
    * [validacioncantidad validacion de que la cantidad se menor que la cantidad exixistente]
    * @return {[type]} [description]
    */
    validacioncantidad(){
      if (parseFloat(this.cantidad) > parseFloat(this.cantidad_existente)) {
        swal('Error!','No se puede pedir una cantidad mayor a la existente!','warning' );
        this.validado = 0;
      }
      else {
        this.validado = 1;
      }
    },

    /**
    * [validacioncantidaduno Valida que el canpo cantidad no se pueda guardar en nulo]
    * @return  [description]
    */
    validacioncantidaduno(){
      if (this.cantidad == '') {
        swal('Error!','Complete los campos','warning' )
        this.validado = 0;
        // this.clasescantidad = ' is-invalid';
      }
      else {
        this.validado = 1;
      }
    },

    /**
    * [validacioncantidaduno Valida que el canpo cantidad no se pueda guardar en nulo]
    * @return  [description]
    */
    validacioncantidados(){
      let me = this;
      // var cantidad_validacion = parseFloat(me.cantidad) + parseFloat(me.articulo_especifico.cantidad_almacen);
      // console.log(cantidad_validacion);
      if (parseFloat(me.cantidad) > parseFloat(me.articulo_especifico.cantidades)  ) {
        toastr.warning('Atención','No se puede agregar una cantidad mayor a la solicitada');
        me.validado = 0;
      }
      else {
        me.validado = 1;
      }
    },

    /**
    * [maestro --Devuelve a un estado inicial la variables definidas]
    * @return [description]
    */
    maestro(){
      this.detalles_ver = false;
      this.detalle = false;
      this.detalles_solicitudes = null;
    },

    updatecantidad(i,data,k){
      this.detalles_solicitudes[k].req.cantidad_almacen = data.target.value;
    },

    guardaTemp(i,k){
      if (parseFloat(i.cantidad_almacen) > parseFloat(i.cantidades)) {
        toastr.warning('No se puede pedir mas de los solicitado');
        this.detalles_solicitudes[k].req.cantidad_almacen = '';
      }else {
        axios.post('/guardar/cantidad/almacen/',{
          requisicion : i.id,
          articulo_id : i.articulo_id,
          pda : i.pda,
          cantidad : parseFloat(i.cantidad_almacen),
          cantidad_i : parseFloat(i.cantidades),
        }).then(response => {
          toastr.success('Agregado Correctamente');
          this.cargardetalle(this.id_);
        }).catch(e => {
          console.error(e);
        });
      }
    }
  },
  mounted() {
  }
}
</script>
