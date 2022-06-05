<template>
<div class="main">
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list"></i> Caltálogo de análisis
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalAnalisis(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class="card-body">
            <v-client-table :columns='columns_analisis' :data='list_analisis' :options='options_analisis' ref='tbl_analisis'>
                <template slot='id' slot-scope='props'>
                    <div class='btn-group' role='group'>
                        <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                        </button>
                        <div class='dropdown-menu'>
                            <template>
                                <button type='button' @click='AbrirModalAnalisis(false, props.row)' class='dropdown-item'>
                                    <i class='icon-pencil'></i>&nbsp;Actualizar
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </v-client-table>
        </div>
    </div>

    <!-- Modal Analisis -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_analisis}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_analisis'></h4>
                    <button type='button' class='close' @click='CerrarModalAnalisis()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='form-group row'>
                        <label class='col-md-3 form-control-label'>Secuencia</label>
                        <div class='col-md-9'>
                            <v-select label="nombre" @input="SecuenciaInput" :options="listSecuencias" v-model="analisis_modal.secuencia"></v-select>
                        </div>
                    </div>

                    <div class='form-group row'>
                        <label class='col-md-3 form-control-label'>Riesgo</label>
                        <div class='col-md-9'>
                            <v-select label="nombre" @input="RiesgoInput" :options="listRiesgos" v-model="analisis_modal.riesgo"></v-select>
                        </div>
                    </div>

                    <div class='form-group row'>
                        <label class='col-md-3 form-control-label'>Recomendación</label>
                        <div class='col-md-9'>
                            <v-select label="nombre" @input="RecomendacionInput" :options="listRecomendaciones" v-model="analisis_modal.recomendacion"></v-select>
                        </div>
                    </div>

                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalAnalisis()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_analisis== 1' class='btn btn-secondary' @click='GuardarAnalisis(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_analisis==2' class='btn btn-secondary' @click='GuardarAnalisis(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</div>
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
            url: "/seguridad/catalogos",
            tipo: 0,
            ver_modal_analisis: 0,
            ver_modal_residuos: 0,
            columns_analisis: ['id', 'secuencia', 'riesgo', 'recomendacion'],
            columns_residuos: ['residuo.id', 'residuo.residuo', 'fuente'],
            list_analisis: [],
            list_residuos: [],
            options_analisis:
            {
                headings:
                {
                    id: 'Acciones',
                    secuencia: 'Secuencia',
                    riesgo: 'Riesgo',
                    recomendacion: 'Recomendación'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id', 'secuencia', 'riesgo', 'recomendacion'],
                filterable: ['id', 'secuencia', 'riesgo', 'recomendacion'],
                filterByColumn: true,
                texts: config.texts
            }, //options
            options_residuos:
            {
                headings:
                {
                    'residuo.id': 'Acciones',
                    'residuo.residuo': 'Residuo',
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                // sortable: ['id','secuencia','riesgo','recomendacion'],
                // filterable: ['id','secuencia','riesgo','recomendacion'],
                filterByColumn: true,
                texts: config.texts
            }, //options
            // Modal
            titulo_modal_analisis: '',
            tipoAccion_modal_analisis: 0,
            analisis_modal:
            {},
            titulo_modal_residuos: '',
            tipoAccion_modal_residuos: 0,
            residuos_modal:
            {
                residuo: '',
                fuente: '',
                tipo: '',
                id: 0,
            },
            nuevo_analisis: false,
            id_analisis: 0,
            listRecomendaciones: [],
            listSecuencias: [],
            listRiesgos: [],

        } // return
    }, //data
    computed:
    {},
    methods:
    {
        /**
         * Obtener los analisis de seguridad
         */
        CargarAnalisis()
        {
            axios.get(this.url + "/analisis/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.list_analisis = res.data.analisis;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            }).catch(e =>
            {
                console.error(e);
            });
        },
        /**
         * Obtiene los residuos
         */
        CargarResiduos()
        {
            axios.get('get/lista/catalogo/residuos').then(response =>
            {
                this.list_residuos = response.data;
            }).catch(e =>
            {
                console.error(e);
            });
        },

        /**
         * Secuencia input
         */
        SecuenciaInput()
        {
            let secuencia = this.analisis_modal.secuencia;
            if (secuencia == null) return;
            if (secuencia.id == null) return;
            if (secuencia.id == -99)
            {
                // Registrar nuevo
                this.RegistrarTipo(1); // Secuencia
            }
            else
            {
                this.CargarRiesgos();
            }
        },

        /**
         * Riesgo input
         */
        RiesgoInput()
        {
            let riesgo = this.analisis_modal.riesgo;
            if (riesgo == null) return;
            if (riesgo.id == null) return;
            if (riesgo.id == -99)
            {
                // Registrar nuevo
                this.RegistrarTipo(2, this.analisis_modal.secuencia.id); // Riesgo
            }
            else
            {
                this.CargarRecomendaciones();
            }
        },

        /**
         * Recomendacion input
         */
        RecomendacionInput()
        {
            let recomendacion = this.analisis_modal.recomendacion;
            if (recomendacion == null) return;
            if (recomendacion.id == null) return;
            if (recomendacion.id == -99)
            {
                // Registrar nuevo
                this.RegistrarTipo(3, this.analisis_modal.riesgo.id); // Recomendacion
            }
            else
            {
                this.CargarRecomendaciones();
            }
        },

        /**
         * Obtener las secuencias de seguridad
         */
        CargarSecuencias()
        {
            axios.get(this.url + "/secuencia/cargar").then(res =>
            {
                if (res.data.status)
                {
                    this.listSecuencias = res.data.secuencias;
                    // this.listSecuencias.push(
                    // {
                    //     id: -99,
                    //     nombre: "Nueva secuencia"
                    // });
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },

        /**
         * Obtener las riesgos de la secuencia ingresada
         */
        CargarRiesgos()
        {
            axios.get(this.url + "/riesgo/cargar/").then(res =>
            {
                if (res.data.status)
                {
                    this.listRiesgos = res.data.riesgos;
                    // this.listRiesgos.push(
                    // {
                    //     id: -99,
                    //     nombre: "Nuevo riesgo"
                    // });
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },

        /**
         * Obtener las recomendaciones del riesgo selecionado
         */
        CargarRecomendaciones()
        {
            axios.get(this.url + "/recomendacion/cargar/").then(res =>
            {
                if (res.data.status)
                {
                    this.listRecomendaciones = res.data.recomendaciones;
                    // this.listRecomendaciones.push(
                    // {
                    //     id: -99,
                    //     nombre: "Nueva recomendación"
                    // });
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },

        /**
         * Registra un nuevo tipo (secuencia,riesgo,recomenación)
         */
        RegistrarTipo(tipo, idTipo)
        {
            let nombre = tipo == 1 ? "Secuencia" : tipo == 2 ? "Riesgo" : "Recomendación";
            let aux_url = tipo == 1 ? "secuencia" : tipo == 2 ? "riesgo" : "recomendacion";
            let aux_id = tipo == 2 ? "secuencia" : "riesgo";
            Swal.fire(
            {
                title: nombre,
                input: 'text',
                inputAttributes:
                {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Registrar',
            }).then((res) =>
            {
                if (res.value == null) return;
                if (res.value.length == 0) return;
                let data = new FormData();
                data.append("nombre", res.value);
                data.append(aux_id + "_id", idTipo);
                axios.post(this.url + "/registrar" + aux_url, data).then(res =>
                {
                    if (res.data.status)
                    {
                        if (tipo == 1)
                            this.CargarSecuencias();
                        else if (tipo == 2)
                            this.CargarRiesgos();
                        else
                            this.CargarRecomendaciones();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                })
            })
        },

        AbrirModalAnalisis(nuevo, model = [])
        {
            // Cargar todas las Secuencias
            this.CargarSecuencias();
            this.ver_modal_analisis = true;
            this.nuevo_analisis = nuevo;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_analisis = 'Registrar Análisis';
                this.tipoAccion_modal_analisis = 1;
            }
            else
            {
                this.id_analisis = model.id;
                // Actualizar
                this.titulo_modal_analisis = 'Actualizar Análisis';
                this.tipoAccion_modal_analisis = 2;

                this.analisis_modal.secuencia = {
                    id: model.secuencia_id,
                    nombre: model.secuencia
                };
                this.analisis_modal.riesgo = {
                    id: model.riesgo_id,
                    nombre: model.riesgo
                };
                this.analisis_modal.recomendacion = {
                    id: model.recomendacion_id,
                    nombre: model.recomendacion
                };
                this.analisis_modal.id = model.id;
            } // Fin if
        },

        AbrirModalResiduos(nuevo, model = [])
        {
            this.ver_modal_residuos = true;
            if (nuevo)
            {
                this.titulo_modal_residuos = 'Registrar';
                this.tipoAccion_modal_residuos = 1;
            }
            else
            {
                let array = [];
                model.fuente.forEach((item, i) =>
                {
                    array.push(item.fuente_generacion);
                });

                this.titulo_modal_residuos = 'Actualizar';
                this.tipoAccion_modal_residuos = 2;
                this.residuos_modal.residuo = model.residuo.residuo;
                this.residuos_modal.id = model.residuo.id;
                this.residuos_modal.tipo = model.residuo.tipo;
                this.residuos_modal.fuente = array;
            }
        },

        CerrarModalAnalisis()
        {
            this.ver_modal_analisis = false;
            Utilerias.resetObject(this.analisis_modal);
        },

        CerrarModalResiduos()
        {
            this.ver_modal_residuos = false;
            Utilerias.resetObject(this.residuos_modal);
        },

        GuardarAnalisis(nuevo)
        {
            if (!this.ValidarModal())
            {
                toastr.warning("Seleccione todos los campos");
                return;
            }

            let data = new FormData();
            if (!this.nuevo_analisis)
                data.append("id", this.id_analisis);
            data.append("riesgo_id", this.analisis_modal.riesgo.id);
            data.append("recomendacion_id", this.analisis_modal.recomendacion.id);
            data.append("secuencia_id", this.analisis_modal.secuencia.id);
            axios.post(this.url + "/analisis/guardar", data).then(res =>
            {
                if (res.data.status)
                {
                    toastr.success("Analisis registrado");
                    this.CerrarModalAnalisis();
                    this.CargarAnalisis();
                }
            })
        },

        ValidarModal()
        {
            if (this.analisis_modal.secuencia == null) return false;
            if (this.analisis_modal.secuencia.id == null) return false;
            if (this.analisis_modal.riesgo.id == null) return false;
            if (this.analisis_modal.recomendacion.id == null) return false;
            return true;
        },

        GuardarResiduos(nuevo)
        {
            axios(
            {
                method: nuevo ? 'POST' : 'PUT',
                url: nuevo ? 'guardar/lista/catalogo/residuos' : 'actualizar/lista/catalogo/residuos',
                data:
                {
                    id: nuevo ? 0 : this.residuos_modal.id,
                    residuo: this.residuos_modal.residuo,
                    tipo: this.residuos_modal.tipo,
                    fuente: this.residuos_modal.fuente,
                },
            }).then(response =>
            {
                this.CargarResiduos();
                toastr.success('Correcto!!!');
                this.CerrarModalResiduos();
            }).catch(e =>
            {
                console.error(e);
            });
        },

    }, // Fin metodos
    mounted()
    {
        this.CargarAnalisis();
    }
}
</script>
