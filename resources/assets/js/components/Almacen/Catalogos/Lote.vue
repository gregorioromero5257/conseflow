<template>
<main class="main">
    <div class="container-fluid">
        <bus-articulo ref="busqueda" @clicked="onClickArticuloSeleccionado"></bus-articulo>
        <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Lotes
                <button type="button" @click="DescargarReporteAlmacen()" class="btn btn-success float-sm-right">
                    <i class="fas fa-file-excel"></i>&nbsp;Descargar reporte
                </button>
            </div>
            <!-- <div class="cotainer row mt-3 ml-2">
                <div class="col col-9">
                    <input type="text" class="form-control" placeholder="Descripción del artículo" v-model="buscar" v-on:keyup.enter="OnEnter">
                </div>
                <div class="col col-2">
                    <button class="btn btn-dark" @click="BuscarArticulos()">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                </div>
            </div> -->
            <div class="card-body">
              <div class="form-row">
                <div class="col-md-2 mb-3">
                  <label>Tipo</label>
                  <select class="form-control" v-model="tipo">
                    <option value="1">Proyecto</option>
                    <option value="2">Articulo</option>
                  </select>
                </div>
                <template v-if="tipo == 1">
                <div class="col-md-6 mb-3">
                  <label>Proyecto</label>
                    <v-select :options="listaProyectos"  v-validate="'required'" v-model="proyecto_id" label="name" name="proyecto" data-vv-name="proyecto" ></v-select> <!---->
                </div>
                <div class="col-md-1 mb-3">
                  <label>&nbsp;</label><br>
                  <button class="btn btn-dark" @click="listarComprasText()">Buscar</button>
                </div>
              </template>
              <template v-if="tipo == 2">
                <div class="col-md-6 mb-3">
                  <label>&nbsp;Artículo</label>
                  <input type="text" class="form-control" placeholder="Descripción del artículo" v-model="buscar" v-on:keyup.enter="OnEnter">
                </div>
              </template>
              </div>

                <v-client-table :data="tableData" :columns="columns" :options="options">

                    <template slot="data.id" slot-scope="props">

                        <div class="btn-group" role="group">
                            <div class="btn-group dropup" role="group">
                                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <button type="button" @click="AbrirModalUbicacion(props.row.data)" class="dropdown-item">
                                        <i class="fas fa-box"></i>&nbsp; Cambiar Ubicacion
                                    </button>
                                    <template v-if="props.row.data.total > 0 && is_numeric(props.row.salida.cs) == false">
                                    <button v-if="props.row.data.total > 0" type="button" @click="cambiarEntrada(props.row.data)" class="dropdown-item">
                                        <i class="fas fa-edit"></i>&nbsp; Cambiar Entrada
                                    </button>
                                    <button  type="button" @click="eliminar(props.row.data)" class="dropdown-item">
                                        <i class="fas fa-trash"></i>&nbsp; Eliminar
                                    </button>
                                  </template>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- <template slot="condicion" slot-scope="props" >
                              <template v-if="props.row.condicion">
                                 <button type="button" class="btn btn-outline-success">Activo</button>
                              </template>
                              <template v-else>
                                   <button type="button" class="btn btn-outline-danger">Desactivado</button>
                              </template>
                          </template>
                          <template slot="id" slot-scope="props">
                             <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                              <template v-if="props.row.condicion">
                                  <button type="button" class="dropdown-item" @click="activarLote(props.row.id, 0)" >
                                      <i class="fas fa-ban"></i>&nbsp; Desactivar
                                  </button>
                              </template>
                              <template v-else>
                                  <button type="button" class="dropdown-item" @click="activarLote(props.row.id, 1)" >
                                      <i class="icon-check"></i>&nbsp; Activar
                                  </button>
                              </template>
                                </div>
                                </div>
                             </div>
                          </template>-->
                </v-client-table>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalubicacion}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <vue-element-loading :active="isLoading" />
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cambiar ubicación</h4>
                    <button type="button" class="close" @click="CerrarModalUbicacion()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-inline mt-3">
                                <label class="col-3" for="">Ubicación actual:</label>
                                <label class="col-9" for="">{{ubicacion_actual}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <vue-element-loading :active="isalamcenes_loading" />
                            <div class="form-inline mt-3">
                                <label class="col-3" for="">Almacén</label>
                                <v-select class="col-6" label="nombre" :options="listAlamcenes" v-model="modal_ubicacion.almacen" v-validate="'required'" name="almacen" @input="ObtenerStands()"></v-select>
                                <span class="text-danger">{{ errors.first('almacen') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <vue-element-loading :active="isalamcenes_loading" />
                            <div class="form-inline mt-3">
                                <label class="col-3" for="">Stand</label>
                                <v-select class="col-6" name="stand" label="nombre" v-validate="'required'" :options="listStands" v-model="modal_ubicacion.stand" @input="ObtenerNiveles()"></v-select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <vue-element-loading :active="isalamcenes_loading" />
                            <div class="form-inline mt-3">
                                <label class="col-3" for="">Nivel</label>
                                <v-select class="col-6" name="nivel" label="nombre" v-validate="'required'" :options="listNiveles" v-model="modal_ubicacion.nivel"></v-select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" @click="CerrarModalUbicacion()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="GuardarUbicacion()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
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

export default
{
    data()
    {
        return {
          tipo : 0,
          listaProyectos : [],
          proyecto_id : '',
            lote:
            {
                id: 0,
                nombre: '',
                articulo_id: 0,
                descripcion: '',
                cantidad: 0,
                caducidad: null
            },
            tituloForm: 'Registrar lote',
            tipoAccion: 1,
            isLoading: false,
            columns: ['data.id', 'data.folio', 'data.nombre', 'data.descripcion', 'data.total_entrada', 'salida.cs','data.total'],
            //columns: ['id','nombre', 'descripcion', 'marca', 'caducidad', 'cantidad', 'condicion'],
            tableData: [],
            options:
            {
                headings:
                {
                  'data.id' : 'Acciones',
                  'data.folio' : 'Folio',
                  'data.nombre' : 'Nombre',
                  'data.descripcion' : 'Descripcion',
                  'data.total_entrada' : 'Entrada',
                  'salida.cs' : 'Salida',
                  'data.total' : 'Total',

                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            },
            buscar: "",
            lstLotes: [],
            // modal ubicacion
            modalubicacion: false,
            isalamcenes_loading: false,
            listAlamcenes: [],
            listStands: [],
            listNiveles: [],
            modal_ubicacion:
            {
                almacen: 0,
                stand: 0,
                nivel: 0,
            },
            ubicacion_actual: "",
            lote_aux: "",
        }
    },
    computed:
    {},
    methods:
    {
        getData(){
          axios.get('obtener/proyectos').then(response => {
              this.listaProyectos = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        },

        guardarLote(nuevo)
        {
            this.$validator.validate().then(result =>
            {
                if (result)
                {
                    this.isLoading = true;
                    let me = this;
                    axios(
                    {
                        method: nuevo ? 'POST' : 'PUT',
                        url: nuevo ? '/lote/registrar' : '/lote/actualizar',
                        data:
                        {
                            'id': this.lote.id,
                            'nombre': this.lote.nombre,
                            'articulo_id': this.lote.articulo_id,
                            'caducidad': this.lote.caducidad,
                            'cantidad': this.lote.cantidad,
                        }
                    }).then(function (response)
                    {
                        me.isLoading = false;
                        if (response.data.status)
                        {
                            me.cancelar();
                            me.$refs.myTable.setPage(me.$refs.myTable.Page);
                            if (!nuevo)
                            {
                                toastr.success('Lote Actualizado Correctamente');
                            }
                            else
                            {
                                toastr.success('Lote Registrado Correctamente');

                            }
                        }
                        else
                        {
                            swal(
                            {
                                type: 'error',
                                html: response.data.errors.join('<br>'),
                            });
                        }
                    }).catch(function (error)
                    {
                        console.log(error);
                    });
                }
            });
        },

        activarLote(id, activar)
        {
            swal(
            {
                title: activar ? 'Esta seguro de activar este Lote?' : 'Esta seguro de desactivar este Lote?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) =>
            {
                if (result.value)
                {
                    let me = this;

                    axios.put(activar ? '/lote/activar' : '/lote/desactivar',
                    {
                        'id': id
                    }).then(function (response)
                    {

                        me.$refs.myTable.setPage(me.$refs.myTable.Page);
                        if (activar)
                        {
                            toastr.success('Lote Activado con Exito');

                        }
                        else
                        {
                            toastr.error('Lote Desactivado con Exito');

                        }
                    }).catch(function (error)
                    {
                        console.log(error);
                    });
                }
            })
        },
        cancelar()
        {
            this.tituloForm = 'Registrar Lote';
            this.tipoAccion = 1;
            Utilerias.resetObject(this.lote);
            Utilerias.scrollMeTo(this, 'myTable');
        },
        capturar(modelo, accion, data = [])
        {
            switch (modelo)
            {
                case "lote":
                {
                    switch (accion)
                    {
                        case 'registrar':
                        {
                            this.tituloForm = 'Registrar lote';
                            this.tipoAccion = 1;
                            Utilerias.resetObject(this.lote);
                            Utilerias.scrollMeTo(this, 'formLote');
                            break;
                        }
                        case 'actualizar':
                        {
                            this.tituloForm = 'Actualizar lote';
                            this.tipoAccion = 2;
                            this.lote.id = data['id'];
                            this.lote.articulo_id = data['articulo_id'];
                            this.lote.nombre = data['nombre'];
                            this.lote.cantidad = data['cantidad'];
                            this.lote.caducidad = data['caducidad'];
                            this.lote.descripcion = data['descripcion'];
                            Utilerias.scrollMeTo(this, 'formLote');
                            break;
                        }
                    }
                }
            }
        },

        onClickArticuloSeleccionado(value)
        {
            this.lote.descripcion = value.descripcion;
            this.lote.articulo_id = value.id;
        },
        DescargarReporteAlmacen()
        {
            //descargar
            window.open('/reporteinventario_almacen/');
        },
        OnEnter()
        {
            if (this.buscar == "") return;
            this.Buscar(this.buscar);
        },
        Buscar(buscar)
        {
            axios.get("/lote/ubicacion/buscar/" + buscar).then(res =>
            {
                if (res.data.status)
                {
                    this.tableData = res.data.articulos;
                }
                else
                {
                    toastr.error("Error al obtener los registros");
                }
            });
        },
        listarComprasText(){
          axios.get("/lote/ubicacion/buscar/proyecto/" + this.proyecto_id.id).then(res =>
          {
              if (res.data.status)
              {
                  this.tableData = res.data.articulos;
              }
              else
              {
                  toastr.error("Error al obtener los registros");
              }
          });
        },
        BuscarArticulos()
        {
            axios.get("/lote/ubicacion/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.tableData = res.data.articulos;
                }
                else
                {
                    toastr.error("Error al obtener los registros");
                }
            });
        },
        AbrirModalUbicacion(model)
        {
            this.modalubicacion = true;
            this.lote_aux = model.lote_id;
            console.error(model);
            axios.get("/lote/ubicacion/obtenerubicacion/" + model.lote_id).then(res =>
            {
                if (res.data.status)
                {
                    this.ubicacion_actual = res.data.ubicacion;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
            this.ObtenerAlmacenes();
        },
        CerrarModalUbicacion()
        {
            this.modalubicacion = false;
        },
        GuardarUbicacion()
        {
            if (this.modal_ubicacion.almacen == null) return;
            if (this.modal_ubicacion.almacen.id == null) return;
            axios.put("/lote/ubicacion/cambiar",
            {
                "lote_id": this.lote_aux,
                "almacen": this.modal_ubicacion.almacen.id,
                "stand": this.modal_ubicacion.stand.id,
                "nivel": this.modal_ubicacion.nivel.id,
            }).then(res =>
            {
                if (res.data.status)
                {
                    toastr.success("Ubicación cambiada correctamente");
                    this.CerrarModalUbicacion();
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });

        },
        ObtenerAlmacenes()
        {
            this.listAlamcenes = [];
            this.listStands = [];
            this.listNiveles = [];
            this.isalamcenes_loading = true;
            axios.get("/almacen/ver").then(res =>
            {
                this.isalamcenes_loading = false;
                this.listAlamcenes = res.data;
            }).catch(r =>
            {
                toastr.error("Error al obtener los almacenes");
            })
        },
        ObtenerStands()
        {
            if (this.modal_ubicacion.almacen == null) return;
            this.listStands = [];
            this.listNiveles = [];
            this.isstands_loading = true;
            axios.get("/almacen/verstand/" + this.modal_ubicacion.almacen.id).then(res =>
            {
                this.isstands_loading = false;
                this.listStands = res.data;
            }).catch(r =>
            {
                toastr.error("Error al obtener los stands");
            });
        },
        ObtenerNiveles()
        {
            this.listNiveles = [];
            this.isniveles_loading = true;
            axios.get("/almacen/vernivel/" + this.modal_ubicacion.stand.id).then(res =>
            {
                this.isniveles_loading = false;
                this.listNiveles = res.data;
            }).catch(r =>
            {
                toastr.error("Error al obtener los niveles");
            })
        },
        cambiarEntrada(data){
          Swal.fire({
            title: 'Cantidad nueva',
            input: 'text',
            inputLabel: 'Cantidad',
            inputValue: data.total  ,
            showCancelButton: true,
            inputValidator: (value) => {
              if (!value) {
                return 'Escriba una cantidad!'
              }
              if (this.is_numeric(value) == false) {
                return 'Solo se permiten números'
              }
              if (value > data.total) {
                return 'Escriba una cantidad menor'
              }
            }
          }).then(result => {
            if (result.value) {
              axios.post('alm/cambiar/entrada',{
                lote_almacen_id : data.id,
                lote_id : data.lote_id,
                proyecto_id : data.proyectoi,
                stocke_id : data.stocke_id,
                cantidad : result.value,
                cantidad_original : data.total,
              }).then(response => {
                if(this.tipo == 1){
                  this.listarComprasText();
                }else if (this.tipo == 2) {
                  this.OnEnter();
                }
                toastr.success('Cambiado Correctamente !!!');
              }).catch(e => {
                console.error(e);
              })
            }
          })

        },
        is_numeric(value) {
	         return !isNaN(parseFloat(value)) && isFinite(value);
         },
        eliminar(data){
          axios.get('alm/eliminar/entrada/' + data.id).then(response => {
            if(this.tipo == 1){
              this.listarComprasText();
            }else if (this.tipo == 2) {
              this.OnEnter();
            }
            toastr.success('Cambiado Correctamente !!!');
          }).catch(e => {
            console.error(e);
          });
        }
    },
    components:
    {
        'bus-articulo': require('../BusArticulo.vue')
    },
    mounted()
    {
      this.getData();
        // this.BuscarArticulos();
    }
}
</script>
