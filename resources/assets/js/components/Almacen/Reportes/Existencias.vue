<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card" v-show="!detalleex">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Existenciass
          <br>
          <br>

          <div class="form-group row">
            <label class="col-md-2 form-control-label">Tipo de reporte</label>
            <div class="col-md-5">
              <v-select :options="listBusqueda" v-model="buscarpor" label="nombre"></v-select>
            </div>
          </div>

          <div class="form-group row">

            <template v-if="buscarpor.id == 1">
              <label class="col-md-2 form-control-label">Tipo</label>
              <div class="col-md-5">
                <v-select :options="listTipos" v-model="tipo" label="nombre"></v-select>
              </div>
            </template>

            <template v-if="buscarpor.id == 2">
              <label class="col-md-2 form-control-label">Almacen</label>
              <div class="col-md-5">
                <v-select :options="listAlmacenes" v-model="almacen" label="nombre"></v-select>
              </div>
            </template>

            <template v-if="buscarpor.id == 3">
              <label class="col-md-2 form-control-label">Mes</label>
              <div class="col-md-2">
                <select class="form-control" v-model="mes_b">
                  <option value="01">Enero</option>
                  <option value="02">Febrero</option>
                  <option value="03">Marzo</option>
                  <option value="04">Abril</option>
                  <option value="05">Mayo</option>
                  <option value="06">Junio</option>
                  <option value="07">Julio</option>
                  <option value="08">Agosto</option>
                  <option value="09">Septiembre</option>
                  <option value="10">Octubre</option>
                  <option value="11">Noviembre</option>
                  <option value="12">Diciembre</option>
                </select>
              </div>
              <label class="col-md-1 form-control-label">Año</label>
              <div class="col-md-2">
                <input type="number" class="form-control" v-model="year_b">
              </div>
            </template>

            <template v-if="buscarpor.id == 4">
              <label class="col-md-2 form-control-label">Mes</label>
              <div class="col-md-5">
                <v-select :options="listaProyectos" v-model="proyectos" label="name"></v-select>
              </div>
            </template>

            <button type="button" class="mr-2 btn btn-sm btn-dark" @click="crearBusqueda()">
              <i class="fas fa-search"></i>
              Buscar.
            </button>

            <button v-if="buscarpor!=null && buscarpor.id < 3" type="button" class="btn btn-sm btn-success" @click="descargar">
              <i class="fas fa-file-excel"></i>
              Descargar Reporte.
            </button>

            <button v-if="buscarpor!=null && buscarpor.id == 3" type="button" class="btn mt-2 btn-sm btn-success" @click="descargarmes">
              <i class="fas fa-file-excel"></i>
              Descargar Reporte Mensual.
            </button>

            <button v-if="buscarpor!=null && buscarpor.id ==4" type="button" class="btn btn-sm btn-success" @click="descargarproyecto">
              <i class="fas fa-file-excel"></i>
              Descargar Reporte Proyecto.
            </button>

            <button v-if="buscarpor!=null && buscarpor.id ==5" type="button" class="btn btn-sm btn-success" @click="descargarGeneral">
              <i class="fas fa-file-excel"></i>
              Descargar Reporte General
            </button>
          </div>
        </div>
        <div class="card-body">

          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">


          </v-client-table>
        </div>
      </div>
      <!--Se nombra la vista padre usando mostrar maestro  -->
      <!-- <div v-show="detalleex">
      <existenciasdetalle ref="existenciasdetalle" @existenciasdetalle:change="maestro"></existenciasdetalle>
    </div> -->

    <!-- Fin ejemplo de tabla Listado -->
  </div>
</main>
</template>

<script>
// const ExistenciasDetalle = r => require.ensure([], () => r(require('./ExistenciasDetalle.vue')), 'alm');

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);


export default
{
  data()
  {
    return {
      result: '',
      error: '',
      // busqueda
      listBusqueda: [],
      listTipos: [],
      listaProyectos : [],
      buscarpor:
      '',
      tipo:
      {},

      listAlmacenes: [],
      almacen:
      {},
      detalleex: false,
      proyecto_id: 0,
      almacen_id: 0,
      stand_id: 0,
      nivel_id: 0,
      id: 0,
      cantidad: 0,
      Precio: 0,
      descripcion: '',
      mes_b : '',
      year_b : '',
      columns: [],
      tableData: [],
      options:
      {
        headings:
        {
          id: 'Acción',
          descripcion: 'Articulo',
          alm_nombre: 'Almcén',
          cantidad: 'Cantidad',
          Precio: 'Precio total',
          c_nombre: 'Categoría'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      },

    }
  },
  computed:
  {},

  //se crea el nombre de la vista padre
  components:
  {
    // QrcodeStream,
        // 'existenciasdetalle': ExistenciasDetalle,
  },



  methods:
  {

    getDataPro(){
      axios.get('/proyecto-listar').then(response => {
        this.listaProyectos = [];
        response.data.forEach((item, i) => {
          this.listaProyectos.push({
            id : item.id,
            name : item.nombre_corto
          });
        });
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    CargarExistencias(tipo)
    {
      let query = "";
      let me = this;
      if (tipo == 1)
      {
        // Por almacen
        query = this.almacen.id + "&" + 0;
      }
      else
      {
        // Categoria
        query = 0 + "&" + this.tipo.id;
      }
      //se inserta la ruta que en el controlador consulta en el index los articulos que contiene la tabla existencia
      axios.get('/buscararticuloexistencia/' + query).then(response =>
        {
          me.tableData = response.data.articulos;
          me.isLoading = false;
        })
        .catch(function (error)
        {
          console.log(error);
        });
      },
      //cargar el componente de las ExistenciasDetalle
      // cargardetalleexistencias(dataExistencias = [])
      // {
      //     //se crea el componente hijo
      //     //ChildDetalleExistencias contiene el metodo cargardetalleexistencias y retorna el dataExistencias
      //     this.detalleex = true;
      //     var ChildDetalleExistencias = this.$refs.existenciasdetalle;
      //     ChildDetalleExistencias.cargardetalleexistencias(dataExistencias);
      // },

      descargar()
      {
        if(this.buscarpor==null) return;
        let query = "";
        if (this.buscarpor.id == 1)
        {
          // Categoria
          if(this.tipo==null) return;
          query = 0 + "&" + this.tipo.id;
          console.error("Categoria");
        }
        else
        {
          // Por almacen
          if(this.almacen==null) return;
          query = this.almacen.id + "&" + 0;
        }
        window.open('descargar-existencia/' + query, '_blank');
      },
      descargarGeneral()
      {
        window.open("descargar-existencia-general","_blank");
      },

      descargarmes(){
        // if(this.buscarpor==null) return;
        window.open('descargar-existencia-mes/' + this.mes_b + '&' + this.year_b, '_blank' );
      },

      descargarproyecto(){
        // if(this.buscarpor==null) return;
        window.open('descargar-existencia-proyecto/' + this.proyectos.id , '_blank' );
      },

      maestro()
      {
        //se utiliza para que no cargue el componente hijo
        this.detalleex = false;
      },
      // Obtiene todos los almacenes
      getData()
      {
        axios.get("/reportes/existencias/almacenes").then(res =>
          {
            if (res.data.status)
            {
              this.listAlmacenes = res.data.almacenes;
            }
            else
            {
              alert("Error");
            }
          });

          axios.get("/reportes/existencias/categorias").then(res =>
            {
              if (res.data.status)
              {
                this.listTipos = res.data.categorias;
              }
              else
              {
                alert("Error");
              }
            });
          },
          buscarporalmacen()
          {
            if (this.almacen == null)
            return;
            if (this.almacen.id == null)
            {
              toastr.warning("", "Seleccione un almacén");
              return;
            }
            this.CargarExistencias(1);
          },
          buscarporcategoria()
          {
            if (this.tipo == null)
            return;
            if (this.tipo.id == null)
            {
              toastr.warning("", "Seleccione un tipo");
              return;
            }
            this.CargarExistencias(2);
          },

          crearBusqueda(){
          if(this.buscarpor == ''){
            toastr.warning('Seleccione un tipo de reporte');
          }else if (this.buscarpor.id == 1) {//TIPO
            axios.get('existencias/busqueda/tipo/' + this.tipo).then(response => {
              this.columns = ['proyecto','articulo','almacen','grupo','categoria','existencia'];
              this.tableData = response.data;
            }).catch(e => {
              console.error(e);
            });
          }else if (this.buscarpor.id == 2) {//ALMACEN

          }else if (this.buscarpor.id == 3) {//MES

          }else if (this.buscarpor.id == 4) {//PROYECTO

          }else if (this.buscarpor.id == 5) {//GENERAL

          }
          },
        },
        mounted()
        {
          this.listBusqueda.push(
            {
              id: 1,
              nombre: "Tipo"
            });

            this.listBusqueda.push(
              {
                id: 2,
                nombre: "Almacén"
              });
              this.listBusqueda.push(
                {
                  id: 3,
                  nombre: "Mes"
                });
                this.listBusqueda.push(
                  {
                    id: 4,
                    nombre: "Proyecto"
                  });
                  this.listBusqueda.push({
                    id: 5,
                    nombre: "GENERAL"
                  });
                  this.getData();
                  this.getDataPro();
                }
              }
              </script>
