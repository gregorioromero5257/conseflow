<template>

  <main class="main">
    <div class="container-fluid">

      <br>
      <!-- Buscar proyecto-->
      <div class="card" v-if="seccionMostar==1">
        <div class="card-header">

          <i class="fa fa-align-justify"></i> Búsqueda por proyecto.
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-2">
              <label class="form-control-label">Proyecto:</label>
            </div>
            <div class="col-4">
              <!-- {{proyectoId}} -->
              <v-select :options="listaProyectos" label="nombre_corto" v-model="proyectoBuscar"></v-select>

            </div>
            <div class="col-2">
              <button class="btn btn-success btn-block" @click="BuscarSalidas()">Buscar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Ejemplo de tabla Listado -->
      <!--card principal  -->
      <div v-if="seccionMostar==1" class="card">

        <div class="card-header">
          <i class="fa fa-align-justify"></i> Entradas
          <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal(1,[])">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">

          <!-- Listad0 de entradas -->
          <v-client-table :columns="columnsSalida" :data="tableData" :options="optionsSalida" ref="tblSalidas">

            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" class="dropdown-item" @click="abrirModal(2,props.row)">
                      <i class="icon-pencil"></i>&nbsp; Actualizar Salida
                    </button>
                    <button type="button" class="dropdown-item" @click="MostarPartidas(props.row)">
                      <i class="fas fa-eye"></i>&nbsp; Detalles
                    </button>
                    <button type="button" class="dropdown-item" @click="descargar(props.row)">
                      <i class="fas fa-file-pdf"></i>&nbsp;Descargar
                    </button>
                  </div>
                </div>
              </div>
            </template>

            <template slot="tipo_retorno" slot-scope="props">
              <template v-if="props.row.tipo_retorno==1">
                <label>Taller</label>
              </template>
              <template v-if="props.row.tipo_retorno==2">
                <label>Sitio</label>
              </template>
              <template v-if="props.row.tipo_retorno==3">
                <label>Resguardo</label>
              </template>
            </template>

            <template slot="estado" slot-scope="props">
              <template v-if="props.row.estado == 1">
                <button type="button" class="btn btn-success btn-sm disabled"> Activo
                </button>
              </template>

              <template v-if="props.row.estado == 2">
                <button type="button" class="btn btn-warning btn-sm disabled"> Inactivo
                </button>
              </template>
            </template>

          </v-client-table>
          <!-- Fin de listado de entradas -->
        </div>
        <!-- Fin del card principal -->
        <br>

      </div>

      <!-- Partidas-->
      <div v-if="seccionMostar==2" class="card">
        <div class="card-header">
          <i class="fa fa-align-justify">{{tituloPartidas}}</i>
          <button type="button" class="btn btn-dark float-sm-right" @click="regresar()">
            <i class="icon-arrow-left"></i>&nbsp;Atrás
          </button>
        </div>
        <div class="card-body">
          <!-- Listad0 de entradas -->
          <div class="card-body">

            <v-client-table :columns="columnsEntradas" :options="optionsEntradas" ref="tblEntradas" :data="dataTableEntradas">

              <template slot="cantidad" slot-scope="props">
                <template v-if="props.row.pendiente==0">
                  <button type="button" class="btn btn-success btn-sm disabled"> Completado
                  </button>
                </template>

                <template v-else>
                  <button type="button" class="btn btn-warning btn-sm disabled"> Pendientes: {{props.row.pendiente}}
                  </button>
                </template>
              </template>
            </v-client-table>

            <br />
            <br />
            <form>
              <div class="form-group row">
                <label for="inputArticulo" class="col-md-2 form-control-label">Artículo</label>
                <div class="col-md-8">
                  <div class="input-group mb-3">
                    <input v-model="ArticuloRetorno_desc" type="text" class="form-control" placeholder="Articulo" disabled>
                    <div class="input-group-append">
                      <button class="btn btn-secondary" type="button" @click="mostrarArticulos()">Buscar</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-2 form-control-label">Lote</label>
                <div class="col-md-8">
                  <div class="input-group mb-3">
                    <input type="text" v-validate="'required'" v-model="loteArticulo" class="form-control disabled" disabled>
                  </div>
                </div>
              </div>

              <div class="form-group row disabled">
                <label for="inputArticulo" class="col-md-2 form-control-label">Cantidad Salida</label>
                <div class="col-md-8">
                  <div class="input-group mb-3">
                    <input type="number" class="form-control disabled" placeholder="Cantidad" min="0" step="1" v-model="articuloCantidaddSalida"
                    disabled>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="inputCantidad" class="col-md-2 form-control-label">Cantidad de entrada</label>
                <div class="col-md-8">
                  <div class="input-group mb-3">
                    <input type="number" class="form-control" placeholder="Cantidad" min="0" step="1" v-model="articuloCantidadEntrada">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="inputCantidad" class="col-md-2 form-control-label">Cantidad dañados/defectuosos</label>
                <div class="col-md-8">
                  <div class="input-group mb-3">
                    <input type="number" class="form-control" placeholder="Cantidad" min="0" step="1" v-model="defectuosos">
                  </div>
                </div>
              </div>
              <!--<div class="form-group row">
              <label for="inputCantidad" class="col-md-2 form-control-label">Fecha de entrada</label>
              <div class="col-md-8">
              <div class="input-group mb-3">
              <input type="date" v-validate="'required'" name="fecha" v-model="fechaRetorno" class="form-control" placeholder="Fecha de Entrada" autocomplete="off" id="fecha">
            </div>
          </div>
        </div>-->

        <div class="form-group row">
          <label class="col-md-2 form-control-label" for="nstock">Proyecto</label>
          <div class="col-md-8">
            <v-select v-model="proyectoPartidas" v-validate="'required'" label="nombre_corto" name="proyecto" data-vv-name="proyecto"
            disabled>
          </v-select>
        </div>
      </div>
    </form>
  </div>
  <div class="card-footer">
    <button type="button" class="btn btn-dark" @click="CancelarRetorno()">
      <i class="fas fa-clear"></i>&nbsp;Cancelar
    </button>
    <button type="button" class="btn btn-dark" @click="guardarRetorno()">
      <i class="fas fa-plus"></i>&nbsp;Agregar
    </button>

  </div>
  <!-- Fin de listado de entradas -->
</div>
<!-- Fin del card principal -->
<br>

</div>
<!-- Fin Entradas-->

</div>

<!--Inicio del modal agregar/actualizar -->
<div class="modal fade"
tabindex="-1"
:class="{'mostrar' : mostrarModalEntrada}"
role="dialog"
aria-labelledby="myModalLabel"
style="display: none;"
aria-hidden="true">
<vue-element-loading :active="cargando" />
<div class="modal-dialog modal-dark modal-lg" role="document">
  <div class="modal-content">
    <div>
      <div class="modal-header">
        <h4 class="modal-title" v-text="tituloModalEntrada"></h4>
        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id">

        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="proyecto">Proyecto</label>
          <div class="col-md-9">
            <v-select :options="listaProyectos" v-validate="'required'" v-model="proyecto" label="nombre_corto" name="proyecto" data-vv-name="proyecto">
            </v-select>
            <span class="text-danger">{{ errors.first('proyecto') }}</span>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
          <div class="col-md-9">
            <input type="date"
            v-validate="'required'"
            name="fecha"
            v-model="fecha"
            class="form-control"
            placeholder="Fecha de Entrada"
            autocomplete="off"
            id="fecha">
            <span class="text-danger">{{ errors.first('fecha') }}</span>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="comentarios">Comentarios</label>
          <div class="col-md-9">
            <input type="text"
            name="comentarios"
            v-validate="'required'"
            v-model="comentarios"
            class="form-control"
            placeholder="Comentarios"
            autocomplete="off"
            id="comentarios">
            <span class="text-danger">{{ errors.first('comentarios') }}</span>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="tipoRetorno">Tipo de retorno</label>
          <div class="col-md-9">
            <select class="form-control" id="tipoRetorno" v-validate="'required'" name="tipoRetorno" v-model="tipoRetorno" data-vv-as="tipo de entrada">
              <option v-for="item in listaTiposRetorno" :value="item.id" :key="item.id">{{ item.nombre}}</option>
            </select>
            <span class="text-danger">{{ errors.first('tipoRetorno') }}</span>
          </div>
        </div>

        <!-- </form> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
        <button type="button"
        v-if="tipoAccion==1"
        class="btn btn-secondary"
        @click="guardarEntrada(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
        <button type="button"
        v-if="tipoAccion==2"
        class="btn btn-secondary"
        @click="guardarEntrada(2)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!--Fin del modal -->

<!--Inicio del modal ver articulos -->
<div class="modal fade"
tabindex="-1"
:class="{'mostrar' : modalArticulos}"
role="dialog"
aria-labelledby="myModalLabel"
style="display: none;"
aria-hidden="true">
<div class="modal-dialog modal-dark modal-lg" role="document">
  <div class="modal-content">
    <div>
      <div class="modal-header">
        <h4 class="modal-title"> Registrar regreso de artículo {{this.tipoRetornoId==1? "Taller":this.tipoRetornoId==2? "Sitio" : "Resguardo"}}</h4>
        <button type="button" class="close" @click="cerrarModalArticulos()" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">

        <v-client-table ref="myTable" :data="dataTableArticulo" :columns="columnsArticulo" :options="optionsArticulo" @row-click="seleccionarArticulo">
        </v-client-table>

      </div>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!--Fin del modal -->

</main>

</template>

<script>

import Utilerias from '../Herramientas/utilerias.js'
var config = require('../Herramientas/config-vuetables-client').call(this)

export default {
  data() {
    return {
      SalidaRetornoId : 0,
      defectuosos: 0,
      ArticuloIdAux: 0,
      PartidaId: '',
      salidaId: 0,
      tipoRetornoId: 0,
      ArticuloRetorno_desc: '',
      loteArticulo: '',
      fechaRetorno: '',
      proyectoPartida: {},
      tituloPartidas: '',
      proyectoBuscar: {},
      listaProyectosPartidas: [],
      columnsEntradas: ['articulo', 'cantidad_retorno', 'fecha', 'almacen', 'cantidad'],
      dataTableEntradas: [],
      optionsEntradas: {
        headings: {
          articulo: 'Artículo',
          cantidad_retorno: 'Cantidad',
          fecha: 'Fecha',
          almacen: 'Almacén',
          cantidad: 'Estado'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        //  sortable: ['empleado.folio'],
        //  filterable: ['empleado.folio'],
        filterByColumn: true,
        texts: config.texts
      },
      articuloCantidaddSalida: 0,
      articuloCantidadEntrada: 0,
      proyectoPartidas: {},
      dataTableArticulo: [],
      columnsArticulo: ['nombre_corto', 'articulo', 'cantidad', 'fecha', 'salida_folio'],
      optionsArticulo: {
        headings: {
          nombre_corto: 'Proyecto',
          articulo: 'Artículo',
          cantidad: 'Cantidad',
          fecha: 'Fecha de salida',
          salida_folio: 'Folio de salida'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        //  sortable: ['empleado.folio'],
        //  filterable: ['empleado.folio'],
        filterByColumn: true,
        texts: config.texts
      },
      modalArticulos: 0,
      seccionMostar: 1, //1: Entradas. 2: Partidas
      mostrarModalEntrada: 0,
      cargando: false,
      idEntrada: 0,
      fecha: '',
      comentarios: '',
      proyecto: {},
      tipoRetorno: {
        id: 0,
        nomre: ''
      },
      tituloModalEntrada: '',
      tipoAccion: 1,
      fecha: '',
      listaProyectos: [],
      listaTiposRetorno: [],
      columnsSalida: ['id', 'nombre_corto', 'tipo_retorno', 'fecha', 'comentarios', , 'estado'],
      tableData: [],
      optionsSalida: {
        headings: {
          id: 'Acciones',
          proyectoId: 'Proyecto',
          tipo_retorno: 'Tipo de retorno',
          fecha: 'Fecha',
          comentarios: 'Comentarios',
          estado: 'Estado'
          // 'condicion': 'Condición',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['id', 'nombre_corto', 'fecha', 'comentarios', 'tipo_retorno', 'estado'],
        filterable: ['id', 'nombre_corto', 'fecha', 'comentarios', 'tipo_retorno', 'estado'],
        filterByColumn: false,
        texts: config.texts
      }
    }
  },
  methods: {
    descargar(data){
      window.open('descargar-entrada-salida-vale/' + data.id, '_blank');
    },
    /**
    * @vuese
    * Obtiene todos los retornos de salida existentes en la BD
    */
    getData(proyectoId) {
      axios
      .get('getSalidasRetorno/' + proyectoId)
      .then(response => {
        this.tableData = response.data
      })
      .catch(e => {
        console.error(e)
        toastr.error(e)
      })
    },
    abrirModal(accion, entradaActualizar) {
      let t = this
      t.tipoAccion = 1
      this.limpiar()
      t.tituloModalEntrada = accion == 1 ? 'Registrar entrada' : 'Actualizar entrada'
      t.mostrarModalEntrada = 1

      // Cargar proeyctos
      this.CargarProyectos()

      t.listaTiposRetorno = []
      // Tipos de retorno
      t.listaTiposRetorno.push({
        id: 1,
        nombre: 'Taller'
      })
      t.listaTiposRetorno.push({
        id: 2,
        nombre: 'Sitio'
      })
      t.listaTiposRetorno.push({
        id: 3,
        nombre: 'Resguardo'
      })

      // Mostar datos de la entrada
      if (accion == 2) {
        //actualizar
        t.tipoAccion = 2
        this.idEntrada = entradaActualizar['id']
        console.error(entradaActualizar)
        this.fecha = entradaActualizar['fecha']
        this.comentarios = entradaActualizar['comentarios']
        this.proyecto = {
          id: entradaActualizar['proyectoId'],
          nombre_corto: entradaActualizar['nombre_corto']
        }
        this.tipoRetorno = entradaActualizar['tipo_retorno']
      }
    },
    cerrarModal() {
      let t = this
      t.mostrarModalEntrada = 0
      this.limpiar()
    },
    limpiar() {
      this.fecha = ''
      this.comentarios = ''
      this.proyecto = {}
      this.tipoRetorno = {}
    },

    /**
    * @vuese
    * Registra o actualiza la entrada seleccionada
    * @arg entrada Array Datos de la entrada
    */
    guardarEntrada(entrada) {
      this.$validator.validate().then(res => {
        if (!res) return
        let formData = new FormData()
        let t = this

        formData.append('id', t.idEntrada)
        formData.append('accion', t.tipoAccion)
        formData.append('proyectoId', t.proyecto.id)
        formData.append('fecha', t.fecha)
        formData.append('tipoRetornoId', t.tipoRetorno)
        formData.append('comentarios', t.comentarios)

        axios
        .post('guardarproyectossalidas', formData)
        .then(res => {
          toastr.success('Salida Registrada')
          this.refreshTable()
          this.cerrarModal()
        })
        .catch(ex => {
          toastr.error(ex, 'Error')
          console.error(ex)
        })
      })
    },
    refreshTable() {
      this.getData()
    },
    /**
    * @vuese
    * Obtiene todas las partidas con salida del proyecto seleccionado
    *
    */
    MostarPartidas(proyecto) {
      console.log(proyecto);
      this.tipoRetornoId = proyecto.tipo_retorno
      this.proyectoPartidas.nombre_corto = proyecto.nombre_corto
      this.proyectoPartida.id = proyecto.proyectoId
      this.SalidaRetornoId = proyecto.id;

      let retorno = this.tipoRetornoId == 1 ? 'Taller' : this.tipoRetornoId == 2 ? 'Sitio' : 'Resguardo'
      this.tituloPartidas = ' ' + proyecto.nombre_corto + ' - Retorno a ' + retorno
      this.seccionMostar = 2
      this.defectuosos = 0

      // Mostrar retornadas
      this.MostrarRetorno()
    },
    regresar() {
      this.seccionMostar = 1
      this.CancelarRetorno()
      this.tipoRetornoId = 0
      this.dataTableArticulo = []
    },

    MostrarRetorno() {
      axios
      .get('getpartidasretorno/' + this.SalidaRetornoId + '&' + this.tipoRetornoId)
      .then(res => {
        this.dataTableEntradas = res.data.salidas
      })
      .catch(ex => {
        toastr.error('Error 501', ex)
        console.error(ex)
      })
    },
    /**
    * @vuese
    *  Obtiene todas las partidas que han salido, del proyecto y tipo de salida ingresado
    */
    mostrarArticulos() {
      if (this.proyectoPartida == null) {
        toastr.error('Proyecto no seleccionado', 'Error 501')
        return
      }
      this.modalArticulos = 1

      axios
      .get('partidassalida/' + this.proyectoPartida.id + '&' + this.tipoRetornoId)
      .then(res => {
        // console.error(this.proyectoPartida);
        // console.error(this.proyectoPartida.id)
        // console.error(this.tipoRetornoId)
        this.dataTableArticulo = res.data.salidas
        // console.error(res.data.salidas)
      })
      .catch(ex => {
        toastr.error(ex)
      })
    },
    cerrarModalArticulos() {
      this.modalArticulos = 0
    },
    /**
    * @vuese
    * Obtiene todos los regresos de salida del proyecto seleccionado
    */
    BuscarSalidas() {
      let id = this.proyectoBuscar.id
      this.getData(id)
    },
    CargarProyectos() {
      let t = this
      let auxProyecto = {
        id: 0,
        nombre_corto: 'Todos los proyectos'
      }
      t.listaProyectos = [auxProyecto]
      this.proyectoBuscar = auxProyecto
      axios
      .get('proyectossalidas')
      .then(res => {
        res.data.proyectos.forEach(p => {
          t.listaProyectos.push({
            id: p.id,
            nombre_corto: p.nombre_corto
          })
        })
        t.listaProyectos.sort()
      })
      .catch(e => {
        toastr.error('501', e)
        console.error(e)
      })
    },
    cerrarModalArticulos() {
      this.modalArticulos = 0
    },

    seleccionarArticulo(articulo) {
      this.PartidaId = articulo.row.partidaId
      this.ArticuloRetorno_desc = articulo.row.articulo
      this.articuloCantidaddSalida = articulo.row.cantidad
      this.loteArticulo = articulo.row.lote
      this.loteArticuloId = articulo.row.lote_id
      this.salidaId = articulo.row.salida_id
      this.dataTableArticulo = []
      this.ArticuloIdAux = articulo.row.articuloId
      this.cerrarModalArticulos()
    },

    guardarRetorno() {
      let articuloId = this.ArticuloIdAux
      let cSalida = this.articuloCantidaddSalida
      let cEntrada = this.articuloCantidadEntrada
      let fecha = this.fechaRetorno
      let proyectoId = this.proyectoPartida.id
      let loteId = this.loteArticuloId
      let salida = this.salidaId
      let partidaId = this.PartidaId
      let defectuoso = this.defectuosos

      if (articuloId == '') {
        toastr.warning('Seleccione un artículo')
        return
      }
      if (cEntrada > cSalida || cEntrada < 1) {
        toastr.warning('Ingrese una cantidad válida')
        return
      }
      if (defectuoso > cEntrada || defectuoso < 0) {
        toastr.warning('Verifique la cantidad de artículos dañados')
        return
      }
      // let cantidadValida=cEntrada - defectuoso;
      // cEntrada=cantidadValida;
      let formData = new FormData()
      let t = this

      formData.append('articuloId', articuloId)
      formData.append('cSalida', cSalida)
      formData.append('cEntrada', cEntrada)
      formData.append('proyectoId', proyectoId)
      formData.append('tipoSalida', this.tipoRetornoId)
      formData.append('salidaId', this.salidaId)
      formData.append('loteId', loteId)
      formData.append('defectuoso', defectuoso)
      formData.append('partidaId', partidaId)
      formData.append('SalidaRetornoId', this.SalidaRetornoId);
      axios
      .post('guardarRetorno', formData)
      .then(res => {
        if (res.data.status) {
          this.CancelarRetorno()
          this.MostrarRetorno()
          toastr.success('Artículo regresado correctamente', 'Correcto')
        } else {
          toastr.error(res.data.error, 'Error')
        }
      })
      .catch(ex => {
        console.error(ex)
        toastr.error(ex, 'Error 501')
      })
    },

    CancelarRetorno() {
      this.ArticuloRetorno_desc = '' //articulo.row.articulo;
      this.articuloCantidaddSalida = '' //articulo.row.cantidad;
      this.articuloCantidadEntrada = ''
      this.fechaRetorno = ''
      this.loteArticulo = ''
      this.defectuosos = 0
      // this.fechaRetorno=auxFecha;
    }
  },
  mounted() {
    // this.getData(0);
    this.CargarProyectos()
    this.getData(0) //De todos los proyectos
  }
}

</script>
