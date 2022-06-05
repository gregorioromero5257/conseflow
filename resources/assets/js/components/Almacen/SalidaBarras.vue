<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
        Salidas
        </div>
        <div class="card-body">

        <div class="form-row">

          <div class="col-md-4">
          <label>Articulos</label>
            <label>&nbsp;</label>
            <input type="text" v-if="codigoBarras" class="form-control" v-model="articulo_text" @keyup.enter="buscar" ref="codigo">
            <!-- <input type="text" v-if="!codigoBarras" class="form-control" v-model="articulo_text_lote" @keyup.enter="buscarlote" ref="codigolote"> -->
          </div>

        <div class="col-md-1">
          <label>&nbsp;</label>
          <br>
          <!-- <label>&nbsp;</label> -->
          <!-- <button class="btn btn-dark" @click="cambiarCondicion()">{{texto_boton}}</button> -->
        </div>
        <div class="col-md-2">
          <label>Tipo</label>
          <select class="form-control" v-model="tipo">
            <option value="1">Salida</option>
            <option value="2">Retorno</option>
          </select>
        </div>
      </div>


      <div class="card-body">

        <div class="form-row">
          <div class="form-group col-md-12" v-if="nombre_art === ''">
          <table>

            <tr>
              <th width="10%">Acción</th>
              <th width="50%">Descripción</th>
              <th width="20%">Proyecto</th>
              <th width="20%">Cantidad</th>
            </tr>
            <tr v-for="t in dataBusqueda" >
              <td><button type="button" class="btn btn-dark float-sm-right" @click="seleccionar(t)">
                <i class="fas fa-check"></i>&nbsp;Seleccionar
              </button> &nbsp;</td>
              <td>&nbsp;{{t.descripcion}}</td>
              <td>&nbsp;{{t.nombre_corto}}</td>
              <td>&nbsp;{{t.cantidad}}</td>
            </tr>
          </table>

          </div>
          <div class="form-group col-md-12" v-if="nombre_art != ''">
              <h6>{{nombre_art}}</h6>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-3">
            <div v-if="tipo == 1">
              <label><h6><b>Tipo Salida</b></h6></label><br>
            </div>
            <div v-if="tipo == 2">
              <label><h6><b>Retorno de</b></h6></label><br>
            </div>
            <select class="form-control" v-model="salida_tipo" >
              <option value="1">Taller</option>
              <option value="2">Sitio</option>
              <option value="3">Resguardo</option>
            </select>
          </div>

          <div class="form-group col-md-4">
            <label><h6><b>Proyecto</b></h6></label><br>
            <v-select :options="listaProyectos" label="name" v-model="proyecto_id" v-validate="'required'" data-vv-name="Proyecto"></v-select>
          </div>

          <div class="form-group col-md-3">
            <label><h6><b>Ubicación</b></h6></label>
            <br>
            <input type="text" class="form-control" v-model="ubicacion">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-3">
            <div v-if="tipo == 1">
            <label><h6><b>Cantidad existente</b></h6></label>      <br>
            </div>
            <div v-if="tipo == 2">
            <label><h6><b>Cantidad Salida</b></h6></label>      <br>
            </div>

            <span>{{salida.cantidad_existente}} {{salida.unidad}}</span>
          </div>
          <div class="form-group col-md-3">
            <div v-if="tipo == 1">
            <label><h6><b>Cantidad Salida</b></h6></label>  <br>
            </div>
            <div v-if="tipo == 2">
            <label><h6><b>Cantidad Retorno</b></h6></label>  <br>
            </div>

            <input type="text" v-validate="'decimal:2'" data-vv-name="cantidad" class="form-control" v-model="salida.cantidad_salida">
            <span class="text-danger">{{errors.first('cantidad')}}</span>
          </div>
          <div class="form-group col-md-6">
            <label><h6><b>Empleado Solicita</b></h6></label>
            <br>
            <v-select :options="listaEmpleados" v-model="empleado_id" label="name"></v-select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-3">
            <label>&nbsp;</label>
            <br><button class="btn btn-outline-dark" v-if="tipo == 1" @click="guardar()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button class="btn btn-outline-dark" v-if="tipo == 2" @click="guardarRetorno()"><i class="fas fa-save"></i>&nbsp;Guardar</button></div>
          </div>
        </div>
      </div>

      </div>

      <div class="card">
        <div class="card-header">
          Salidas del día
        </div>
        <div class="card-body">
          <v-client-table :options="options" :data="tableData" :columns="columns">
            </v-client-table>
        </div>
      </div>


    </div>



</main>
</template>
<script>

import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      texto_boton : 'Lote',
      codigoBarras : true,
      tipo : 1,
      datos_r : null,
      listaArticulos : [],
      listaProveedores : [],
      listaEmpleados : [],
      dataBusqueda : [],
      columns : ['folio','nombre_corto','empleado_name','articulo','lote','cantidad'],
      tableData : [],
      proyecto : '',
      proveedor : '',
      articulo : '',
      articulo_text : '',
      articulo_text_lote : '',
      nombre_art : '',
      almacenaje : '',
      optionsvs: [],
      salida_tipo : '1',
      proyecto_id : '',
      ubicacion : 'NAVE 1',
      empleado_id : '',
      salida : {
        cantidad_existente : '',
        cantidad_salida :  '',
        unidad : '',
        lote_id : '',
      },
      listaProyectos : [],
      options : {
        headings: {
          'nombre_corto' : 'Proyecto',
          'empleado_name' : 'Solicita',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['folio','nombre_corto','empleado_name','articulo','lote','cantidad'],
        filterable: ['folio','nombre_corto','empleado_name','articulo','lote','cantidad'],
        filterByColumn: true,
        texts:config.texts
      },


    }
  },
  watch: {

    proyecto(nuevoValor, valorAnterior){
      if (nuevoValor != '') {
        this.proveedor = '';
      }
    },
    proveedor(nuevoValor, valorAnterior){
      if (nuevoValor != '') {
        this.proyecto = '';
      }
    },
  },
  // directives : {
  //   focus : {
  //     inserted : function (el) {
  //       el.focus(true);
  //     }
  //   }
  // },
  methods:{

    /**
     * [getProyecto Obtiene todos los proyectos existentes]
     * @return {[type]} [description]
     */
    getProyecto() {
      let me=this;
      axios.get('/proyecto').then(response => {
        response.data.forEach(value =>{
          me.listaProyectos.push({
            id : value.proyecto.id,
            name : value.proyecto.nombre_corto
          });
        });
        // me.listaProyectos = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
      axios.get('/vertodosempleados').then(response => {
        me.listaEmpleados = [];
        response.data.forEach(data =>{
          me.listaEmpleados.push({id: data.id, name : data.nombre + ' ' + data.ap_paterno + ' ' + data.ap_materno});
        });
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getData(){
      let me = this;
      axios.get('articulos').then(response => {
        this.listaArticulos = [];
        response.data.forEach((item, i) => {
          this.listaArticulos.push({
            id : item.id,
            name : item.nombre + ' ' + item.descripcion
          });
        });
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    listarCompras(){

    },

    listarComprasText(){
      axios.get('buscar/salidas/dia/barras').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    descargar(id) {
      window.open('descargar-compran/' + id, '_blank');
      let me = this;
    },

    buscar(){
      if (this.tipo == 1) {
        this.dataBusqueda = [];
        this.salida.cantidad_existente = '';
        this.nombre_art = '';
        axios.get('get/barras/art/' + this.articulo_text).then(response => {
          // console.log(response.data);
          if (Object.keys(response.data).length == 0) {
            toastr.warning('No encontrado');
            Utilerias.resetObject(this.salida);
          }else {
            toastr.success('Correcto');
            this.dataBusqueda = response.data;
            // this.nombre_art = response.data.descripcion;
            // this.proyecto_id = {id : response.data.proyecto_id, name : response.data.nombre_corto};
            // this.salida.cantidad_existente = response.data.cantidad;
            // this.almacenaje = response.data.almacen_nombre + ' '
            // + (response.data.nivel_nombre == null ? '' : response.data.nivel_nombre)
            // + ' ' + (response.data.stand_nombre == null ? '' : response.data.nivel_nombre);
          }

          this.articulo_text = '';
        }).catch(e => {
          this.articulo_text = '';
          console.error();
        });
      }
      if (this.tipo == 2) {
        axios.get('get/barras/art/retorno/' + this.articulo_text).then(response => {
          console.log(response.data);
          if (Object.keys(response.data).length == 0) {
            toastr.warning('No encontrado');
            Utilerias.resetObject(this.salida);
          }else {
            toastr.success('Correcto');
            this.nombre_art = response.data[0].descripcion;
            this.proyecto_id = {id : response.data[0].proyecto_id, name : response.data[0].nombre_corto};
            this.salida.cantidad_existente = response.data[0].cantidad_r;
            this.almacenaje = response.data[0].almacen_nombre + ' '
            + (response.data[0].nivel_nombre == null ? '' : response.data[0].nivel_nombre)
            + ' ' + (response.data[0].stand_nombre == null ? '' : response.data[0].nivel_nombre);
          }
          this.salida.unidad = response.data[0].unidad;
          this.salida.lote_id = response.data[0].id;
          this.articulo_text = '';
        }).catch(e => {
          this.articulo_text = '';
          console.error();
        });
      }
    },

    guardar(){

      if (this.salida.cantidad_salida === '') {
        toastr.warning('Escriba la cantidad de salida');
      }else if (this.empleado_id === '') {
        toastr.warning('Seleccionar empleado');
      }else if((this.salida.cantidad_existente - this.salida.cantidad_salida) < 0) {
        toastr.warning('La cantidad de salida no cumple con la cantidad existente');
      }
      else {
        axios.post('get/barras/art/guardar',{
          salida_tipo : this.salida_tipo,
          lote_id :this.salida.lote_id,
          cantidad : this.salida.cantidad_salida,
          proyecto_id : this.proyecto_id.id,
          empleado_id : this.empleado_id.id,
          ubicacion : this.ubicacion,
        }).then(response => {
          toastr.success('Correcto');
          Utilerias.resetObject(this.salida);
          this.salida_tipo = '1';
          this.nombre_art = '';
          this.listarComprasText();
          this.almacenaje = '';
          this.$refs.codigo.focus();
        }).catch(e => {
          console.error(e);
        });
      }
    },

    cambiarCondicion(){
      this.articulo_text_lote = '';
      this.codigoBarras = this.codigoBarras == false ? true : false;
      this.texto_boton = this.texto_boton === 'Barras' ? 'Lote' : 'Barras';
    },

    buscarlote(){
      if (this.tipo == 1) {
        axios.get('get/lote/art/' + this.articulo_text_lote).then(response => {
          // console.log(response.data);
          if (Object.keys(response.data).length == 0) {
            toastr.warning('No encontrado');
            Utilerias.resetObject(this.salida);
          }else {
            toastr.success('Correcto');
            this.nombre_art = response.data.descripcion;
            this.proyecto_id = {id : response.data.proyecto_id, name : response.data.nombre_corto};
            this.salida.cantidad_existente = response.data.cantidad;
            this.almacenaje = response.data.almacen_nombre + ' '
            + (response.data.nivel_nombre == null ? '' : response.data.nivel_nombre)
            + ' ' + (response.data.stand_nombre == null ? '' : response.data.nivel_nombre);
          }
          this.salida.unidad = response.data.unidad;
          this.salida.lote_id = response.data.id;
          this.articulo_text = '';
        }).catch(e => {
          this.articulo_text = '';
          console.error();
        });
      }
    },

    seleccionar(data){
      console.log(data);
      this.nombre_art = data.descripcion;
      this.proyecto_id = {id : data.proyecto_id, name : data.nombre_corto};
      this.salida.cantidad_existente = data.cantidad;
      this.almacenaje = data.almacen_nombre + ' '
      + (data.nivel_nombre == null ? '' : data.nivel_nombre)
      + ' ' + (data.stand_nombre == null ? '' : data.nivel_nombre);

      this.salida.unidad = data.unidad;
      this.salida.lote_id = data.id;
      this.dataBusqueda = [];
    },



  },
  mounted(){
    this.$refs.codigo.focus();
    this.listarComprasText();
    this.getProyecto();
  }
}
</script>
