<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Regreso de Equipo
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Retorno-->
                <div class=''>
                    <v-client-table :columns='columns_retorno' :data='list_retorno' :options='options_retorno' ref='tbl_retorno'>
                        <template slot='id' slot-scope='props'>
                            <button type='button' @click='Aceptar(props.row)' class='btn btn-sm btn-dark'>
                                <i class='fas fa-check-double'></i>
                            </button>
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
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            // Tabla 
            ver_modal_retorno: 0,
            columns_retorno: ['id', 'entrega', 'recibe', 'equipo', 'cantidad', 'fecha'],
            list_retorno: [],
            options_retorno:
            {
                headings:
                {
                    id: 'Aprobar',
                    equipo: 'Equipo',
                    empleado: 'Empleado',
                    cantidad: 'Cantidad',
                    fecha: 'Fecha'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id', 'entrega', 'recibe', 'equipo', 'cantidad', 'fecha'],
                filterable: ['id', 'entrega', 'recibe', 'equipo', 'cantidad', 'fecha'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_retorno: '',
            tipoAccion_modal_retorno: 0,
            retorno_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        /**
         * Obtiene todos los RESGUARDOS de TI
         */
        ObtenerResguardos()
        {
            axios.get("ti/resguardos/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.list_retorno = res.data.equipos;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**
         * Acepta un resguardo
         */
        Aceptar(resguardo)
        {
            Swal.fire(
            {
                title: 'Aceptar entrega de equipo',
                text: "asd",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancellButtonText: 'Cancelar'
            }).then((result) =>
            {
                if (result == null) return;
                if (result.value)
                {
                    axios.post("ti/resguardos/regresar",
                    {
                        "resguardo_id": resguardo.id
                    }).then(res =>
                    {
                        if (res.data.status)
                        {
                            this.ObtenerResguardos();
                            toastr.success("Equipo regresado correctamente");
                        }
                        else
                        {
                            toastr.error(res.data.mensaje);
                        }
                    });
                }
            });
        }
    }, // Fin metodos
    mounted()
    {
        this.ObtenerResguardos();
    }
}
</script>
