<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Cambiar estado de requisición
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Servicios-->
                <div class=''>
                    <div class="form-row">
                        <label class='col-md-2 form-control-label'>Proyecto</label>
                        <div class='col-md-4'>
                            <v-select :options="list_proyectos" v-model="proyecto" label="nombre_corto"></v-select>
                        </div>
                        <button class="col-md-1 btn btn-sm btn-dark" @click="Buscar">Buscar</button>
                    </div>
                    <br>
                    <br>
                    <vue-element-loading :active="isdatosLoading" />
                    <v-client-table :columns='columns_requis' :data='list_requis' :options='options_requis'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_nombre' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='CambiarEstado(props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Cambiar estado
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template slot='estado_id' slot-scope='props'>
                            <template v-if="props.row.estado_id == 0">
                                <button type="button" class="btn btn-uno"><i class="fas fa-plus"></i>&nbsp;Nuevo</button>
                            </template>

                            <template v-if="props.row.estado_id == 1">
                                <button type="button" class="btn btn-dos"><i class="fa fa-exclamation-circle"></i>&nbsp;En espera de validación por</button>
                            </template>

                            <template v-if="props.row.estado_id == 2">
                                <button type="button" class="btn btn-tres"><i class="fa fa-exclamation-triangle"></i>&nbsp;En espera de autorización</button>
                            </template>

                            <template v-if="props.row.estado_id == 3">
                                <button type="button" class="btn btn-cuatro"><i class="fa fa-check-circle"></i>&nbsp;En revisión por almacén</button>
                            </template>

                            <template v-if="props.row.estado_id == 5">
                                <button type="button" class="btn btn-nueve"><i class="fa fa-check-circle"></i>&nbsp;Autorizado por compras</button>
                            </template>

                            <template v-if="props.row.estado_id == 4">
                                <button type="button" class="btn btn-ocho"><i class="fa fa-times"></i>&nbsp;No autorizado</button>
                            </template>

                            <template v-if="props.row.estado_id == 6">
                                <button type="button" class="btn btn-siete"><i class="fa fa-caret-square-o-left"></i>&nbsp;Espera autorización compras</button>
                            </template>

                            <template v-if="props.row.estado_id == 7">
                                <button type="button" class="btn btn-seis"><i class="fa fa-caret-square-o-left"></i>&nbsp;Por Equivalente</button>
                            </template>

                            <template v-if="props.row.estado_id == 8">
                                <button type="button" class="btn btn-cinco"><i class="fa fa-caret-square-o-left"></i>&nbsp;En Espera (Calidad)</button>
                            </template>

                        </template>

                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>
</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            // Tabla 
            url: 'calidad/servicios',
            list_proyectos: [],
            proyecto:
            {},
            list_requis: [],
            isdatosLoading: false,
            isGuardarLoading: false,
            columns_requis: ['id', 'folio', 'fecha_solicitud', 'descripcion_uso', 'estado_id'],
            options_requis:
            {
                headings:
                {
                    id: "Acciones",
                    fecha_solicitud: "Fecha sol.",
                    descripcion_uso: "Descripción",
                    folio: "Folio",
                    estado_id: "Estado"
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            servicio_id: 0,
            titulo_modal_servicios: '',
            tipoAccion_modal_servicios: 0,
            servicios_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        CargarProyectos()
        {
            axios.get("/proyectos").then(r =>
            {
                this.list_proyectos = r.data;
            }).catch(r =>
            {
                console.error(r);
            });
        },
        CambiarEstado(data)
        {
            swal(
                {
                    title: "Tipo de estado",
                    text: "",
                    inputValue: data.estado_id,
                    input: 'select',
                    inputOptions:
                    {
                        0: "Nuevo",
                        1: "En espera (Validación)",
                        2: "En espera (Autorización)",
                        3: "Autorizado(En Almacén)",
                        4: "No autorizado",
                        5: "Recibido (Compras)",
                        6: "Autorizado(En Compras)",
                        7: "Por Equivalente",
                        8: "En Espera (Calidad)",
                    },
                })
                .then((res) =>
                {
                    if (res.value)
                    {
                        if (res.value == null) return;
                        if (res.value != "")
                        {
                            axios.put("/cambiarestadorequi",
                            {
                                id: data.id,
                                estado_id: res.value,
                            }).then(res =>
                            {
                                if (res.data.status)
                                {

                                    toastr.success("Estado de requisición actualizado");
                                    this.Buscar();
                                }
                                else
                                {
                                    toastr.error("Error");
                                }
                            });
                        }
                    }
                });
        },
        Buscar()
        {
            if (this.proyecto == null) return;
            if (this.proyecto.id == null) return;
            axios.get("/requisicion/" + this.proyecto.id).then(res =>
            {
                this.list_requis = [];
                res.data.forEach(r =>
                {
                    this.list_requis.push(r.r);
                });
                console.error(this.list_requis);
            })

        }
    }, // Fin metodos
    mounted()
    {
        this.CargarProyectos();
    }
}
</script>

<style>
.btn-uno {
    color: rgb(255, 255, 255);
    background-color: #f6a152;
    border-color: #f6a152;
    border-radius: 2px;
}

.btn-dos {
    color: rgb(0, 0, 0);
    background-color: #e9b443;
    border-color: #e9b443;
    border-radius: 2px;
}

/* no */
.btn-tres {
    color: rgb(0, 0, 0);
    background-color: #8bbbef;
    border-color: #8bbbef;
    border-radius: 2px;
}

.btn-cuatro {
    color: rgb(255, 255, 255);
    background-color: #40d0d0;
    border-color: #40d0d0;
    border-radius: 2px;
}

.btn-cinco {
    color: rgb(255, 255, 255);
    background-color: #0d4da3;
    border-color: #0d4da3;
    border-radius: 2px;
}

/* no */
.btn-seis {
    color: rgb(255, 255, 255);
    background-color: #0d98a3;
    border-color: #0d98a3;
    border-radius: 2px;
}

.btn-siete {
    color: rgb(255, 255, 255);
    background-color: #25a30d;
    border-color: #25a30d;
    border-radius: 2px;
}

.btn-ocho {
    color: rgb(255, 255, 255);
    background-color: #d04040;
    border-color: #d04040;
    border-radius: 2px;
}

.btn-nueve {
    color: rgb(0, 0, 0);
    background-color: #40d088;
    border-color: #40d088;
    border-radius: 2px;
}
</style>
