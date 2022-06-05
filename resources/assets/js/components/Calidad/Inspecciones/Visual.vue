<template>
<div class='main'>
    <!-- Card Inicio -->
    <div class='card' v-if="tipoCard==1">
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Inspecciones
            <span v-if="proyecto.id!=null">
                <button type='button' class='btn btn-dark float-sm-right' @click='AbrirCardNuevaInspeccion()'>
                    <i class='fas fa-plus'>&nbsp;</i>Nuevo
                </button>
            </span>
        </div>
        <div class='card-body'>
            <div class=''>
                <div class='form-group row'>
                    <label class='col-md-2 form-control-label'>Proyecto</label>
                    <div class='col-md-5'>
                        <vue-element-loading :active="isProyectosLoading" />
                        <v-select label="nombre" :options="listProyectos" v-model="proyecto"></v-select>
                    </div>
                    <button class="btn btn-sm  btn-dark" @click="VerInspecciones">
                        <i class="fas fa-search mr-1"></i> Buscar
                    </button>
                </div>
                <!-- Tabla de Inspeccion-->
                <div class=''>
                    <v-client-table :columns='columns_inspecciones' :data='list_inspecciones' :options='options_inspecciones'>
                        <template slot='civ_id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' class='dropdown-item' @click="Actualizar(props.row)">
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <button type='button' class='dropdown-item' @click="Reporte(props.row)">
                                            <i class='fas fa-file-pdf'></i>&nbsp;Descargar reporte
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <div v-if="tipoCard==2">
        <detalle-inspeccion :proyecto_nombre="proyecto_nombre" :proyecto_id="proyecto_id" :inspeccion_id="inspeccion_id" @CerrarCardInspeccion="CerrarCardInspeccion" :CerrarActualizar="CerrarActualizar" ref="juntas">
        </detalle-inspeccion>
    </div>
</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const DetalleInspeccion = r => require.ensure([], () => r(require('./DetalleInspeccionVisual.vue')), 'calidad');
export default
{
    components:
    {
        "detalle-inspeccion": DetalleInspeccion
    },
    data()
    {
        return {
            url: "/calidad/inspecciones/trazabilidad",
            tipoCard: 1,
            //Inicio
            isProyectosLoading: false,
            listProyectos: [],
            proyecto:
            {},
            isSistemasLoading: false,
            listSistemas: [],
            sistema:
            {},
            // Tabla 
            columns_inspecciones: [
                "civ_id",
                "folio",
                "clave",
                "inspecciona",
                "supervisa",
                "aprobado",
            ],
            list_inspecciones: [],
            options_inspecciones:
            {
                headings:
                {
                    civ_id: "Acciones",
                    clave: "Procedimiento",
                    inspecciona: "Inspeccionó",
                    supervisa: "Supervisó",
                    aprobado: "Aprobó",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [],
                filterable: [],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            ver_modal_inspeccion: 0,
            proyecto_nombre: "",
            // Modal
            titulo_modal_inspeccion: '',
            tipoAccion_modal_inspeccion: 0,
            servicio_sistema_id: 0,
            proyecto_id: 0,
            servicio_sistema:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        // Carga los proyectos de calidad existentes
        CargarProyectos()
        {
            this.isProyectosLoading = true;
            axios.get("/calidad/proyectos/proyectos").then(res =>
            {
                this.isProyectosLoading = false;
                if (res.data.status)
                {
                    this.listProyectos = res.data.proyectos;
                    this.sistema = {};
                }
                else
                {
                    this.Error(res.data);
                }
            });
        },
        // Carga los sistemas del proyecto seleccionado

        VerInspecciones()
        {
            this.isInspeccionesLoading = true;
            if (this.proyecto == null) return;
            axios.get(this.url + "/inspecciones/" + this.proyecto.id).then(res =>
            {
                this.isInspeccionesLoading = false;
                if (res.data.status)
                {
                    this.list_inspecciones = res.data.inspecciones;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },
        // Mostrar card para registrar inspección
        AbrirCardNuevaInspeccion()
        {
            this.inspeccion_id = null;
            if (this.proyecto == null) return;
            if (this.proyecto.id == null) return;
            this.proyecto_id = this.proyecto.id;
            this.tipoCard = 2;
            this.proyecto_nombre = this.proyecto.nombre;
        },
        Actualizar(inspeccion)
        {
            this.proyecto_nombre = this.proyecto.nombre;
            this.inspeccion_id = inspeccion.civ_id;
            this.proyecto_id = inspeccion.proyecto_id;
            this.tipoCard = 2;
        },
        CerrarCardInspeccion()
        {
            this.tipoCard = 1;
        },
        CerrarActualizar()
        {
            this.tipoCard = 1;
            this.VerInspecciones();
        },
        Reporte(inspeccion)
        {
            window.open(this.url + "/reporte/" + inspeccion.civ_id, '_blank');
        },
        Error(r)
        {
            console.error(r);
            toastr.error(r.mensaje);
        },
    }, // Fin metodos
    mounted()
    {
        this.CargarProyectos();
    }
}
</script>
