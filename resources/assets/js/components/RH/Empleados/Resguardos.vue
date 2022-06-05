<template>
<div class=''>
    <!-- Card Inicio-->
    <div class='card' v-show="tipo_card==1">
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Empleados - Equipo de Resguardo
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de empleados-->
                <div class=''>
                    <v-client-table :columns='columns_empleados' :data='list_empleados' :options='options_empleados'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='VerResguardos(props.row)' class='dropdown-item'>
                                            <i class='fas fa-eye'></i>&nbsp;Ver Resguardos
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

    <!-- Card equipos -->
    <div class="card" v-show="tipo_card==2">

        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Equipo de Resguardo - {{empleado.nombre}}
            <button class="btn btn-dark float-sm-right" @click="Regresar">
                <i class="fas fa-arrow-left"></i> Regresar
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <p class="h4 mb-2">Departamento de TI</p>
                <!-- Tabla de empleados-->
                <div v-if="list_equipos1.length>0" class=''>
                    <v-client-table :columns='columns_equipos' :data='list_equipos1' :options='options_equipos'>

                    </v-client-table>
                </div>

                <div v-else>
                    <p class="bg-success h5 py-2 px-2">Sin adeudos
                        <i class="fas fa-check"></i>
                    </p>
                </div>
                <!--Card body -->
            </div> <!-- card-->

            <br>
            <div class=''>
                <p class="h4 mb-2">Departamento de Seguridad</p>
                <!-- Tabla de empleados-->
                <div v-if="list_seguridad.length>0" class=''>
                    <v-client-table :columns='columns_equipos' :data='list_seguridad' :options='options_equipos'>

                    </v-client-table>
                </div>
                <div v-else>
                    <p class="bg-success h5 py-2 px-2">Sin adeudos
                        <i class="fas fa-check"></i>
                    </p>
                </div>
                <!--Card body -->
            </div> <!-- card-->
            
            <br>
            <div class=''>
                <p class="h4 mb-2">Almacén</p>
                <!-- Tabla de empleados-->
                <div v-if="list_almacen.length>0" class=''>
                    <v-client-table :columns='columns_equipos' :data='list_almacen' :options='options_equipos'>

                    </v-client-table>
                </div>
                <div v-else>
                    <p class="bg-success h5 py-2 px-2">Sin adeudos
                        <i class="fas fa-check"></i>
                    </p>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>
</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {

            tipo_card: 1,
            isEquiposLoading: true,
            // Tabla empleados
            columns_empleados: ['id', 'nombre'],
            list_asd: [],
            options_empleados:
            {
                headings:
                {
                    id: 'Acciones',
                    empleado: 'Empleado',
                    estado: "Estado"
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id', 'nombre'],
                filterable: ['id', 'nombre'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            list_empleados: [],
            titulo_modal_asd: '',
            tipoAccion_modal_asd: 0,
            empleado:
            {},
            // Card 2. Equipos
            columns_equipos: ["fecha", "cantidad", "descripcion","acesorios"],
            list_equipos1: [],
            list_seguridad: [],
            list_almacen: [],
            options_equipos:
            {
                headings:
                {
                    trm_id: 'Acciones',
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        /**
         * Obtiene todos los empleados
         */
        ObtenerEmpleados()
        {
            axios.get("/ti/resguardo/empleados").then(res =>
            {
                if (res.data.status)
                {
                    this.list_empleados = res.data.empleados;
                    console.error(this.list_empleados);
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },
        VerResguardos(empleado)
        {
            this.tipo_card = 2; // Cambiar card
            this.isEquiposLoading = true;
            this.empleado = empleado;
            this.list_equipos1 = [];
            axios.get("/rh/resguardos/obtener/" + empleado.id).then(res =>
            {
                this.isEquiposLoading = false;
                if (res.data.status)
                {

                    this.list_equipos1 = res.data.resguardos[0]; // TI
                    this.list_seguridad = res.data.resguardos[1]; // Seguridad
                    this.list_almacen = res.data.resguardos[2]; //Almacen
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },
        Regresar()
        {
            this.tipo_card = 1;
        }
    }, // Fin metodos
    mounted()
    {
        this.ObtenerEmpleados();
    }
}
</script>
