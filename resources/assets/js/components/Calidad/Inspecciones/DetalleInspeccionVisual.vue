<template>
<div class=''>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Inspección
            <button type='button' class='btn btn-dark float-sm-right ml-1' @click='CerrarCardInspeccion'>
                <i class='fas fa-arrow-left'>&nbsp;</i>Regresar
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Inspeccion-->
                <vue-element-loading :active="isCargandoLoading" />
                <div class=''>
                    <div class='modal-body'>
                        <div action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                            <!-- Proyecto -->
                            <div class='form-group row'>
                                <label class='col-md-2 form-control-label'>Proyecto</label>
                                <div class='col-md-9'>
                                    <input :disabled="true" type='text' class='form-control' v-model='proyecto_nombre_aux'>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label class='col-md-2 form-control-label'>Lugar de fabricación</label>
                                <div class='col-md-5'>
                                    <input type='text' name="lugar" v-validate="'required'" class='form-control' v-model="inspeccion_modal.lugar">
                                    <span class="text-danger">{{errors.first("lugar")}}</span>
                                </div>
                                <label class='col-md-1 form-control-label'>Folio</label>
                                <div class='col-md-3'>
                                    <input type='text' class='form-control' v-validate="'required'" name="folio" v-model='inspeccion_modal.folio'>
                                    <span class="text-danger">{{errors.first("folio")}}</span>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label class='col-md-2 form-control-label'>Fecha</label>
                                <div class='col-md-2'>
                                    <input type='date' name="fecha" class='form-control' v-validate="'required'" v-model="inspeccion_modal.fecha">
                                    <span class="text-danger">{{errors.first("fecha")}}</span>
                                </div>
                            </div>

                            <!-- Documentos aplicables -->
                            <hr class="mx-5">
                            <p class="font-weight-bold h5 mb-4">Documento aplicable</p>
                            <div class='form-group row'>
                                <label class='col-md-2 form-control-label'>Procedimiento de soldadura</label>
                                <div class='col-md-5'>
                                    <v-select v-validate="'required'" data-vv-name="procedimiento" v-model="procedimiento" label="nombre_aux" :options="listProcedimientos"></v-select>
                                    <span class="text-danger">{{errors.first("procedimiento")}}</span>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label class='col-md-2 form-control-label'>Sistema</label>
                                <div class='col-md-7'>
                                    <v-select v-validate="'required'" data-vv-name="sistema" v-model="servicio_sistema" label="nombre" :options="listSistemas"></v-select>
                                    <span class="text-danger">{{errors.first("sistema")}}</span>
                                </div>
                            </div>
                            <div class='form-group row'>
                                <label class='col-md-2 form-control-label'>Procedimiento WPS</label>
                                <div class='col-md-4'>
                                    <input type='text' v-validate="'required'" data-vv-name="wps" :disabled="true" class='form-control' v-model='procedimiento.wps'>
                                    <span class="text-danger">
                                        {{errors.first("wps")}}
                                    </span>
                                </div>
                                <label class='col-md-2 form-control-label'>Procedimiento PQR:</label>
                                <div class='col-md-4'>
                                    <input type='text' :disabled="true" class='form-control' v-model='procedimiento.pqr'>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label class='col-md-2 form-control-label'>No. de Plano</label>
                                <div class='col-md-4'>
                                    <input type='text' :disabled="true" class='form-control' v-model='servicio_sistema.plano'>
                                </div>
                                <label class='col-md-1 form-control-label'>Elemento</label>
                                <div class='col-md-5'>
                                    <input type='text' :disabled="true" class='form-control' v-model='servicio_sistema.sistema_nombre'>
                                </div>
                            </div>

                            <hr class="mx-5">
                            <p class="font-weight-bold h5 mb-4">Espécimen</p>
                            <div class='form-group row'>
                                <label class='col-md-2 form-control-label'>Recipiente a presión</label>
                                <div class='col-md-1'>
                                    <input type="checkbox" data-vv-name="Espécimen" class='form-control' v-model='inspeccion_modal.especimen_presion'>
                                </div>
                                <label class='col-md-1 form-control-label'>Tubería</label>
                                <div class='col-md-1'>
                                    <input type="checkbox" class='form-control' v-model='inspeccion_modal.especimen_tuberia'>
                                </div>
                                <label class='col-md-1 form-control-label'>Placa</label>
                                <div class='col-md-1'>
                                    <input type="checkbox" class='form-control' v-model='inspeccion_modal.especimen_placa'>
                                </div>
                                <label class='col-md-1 form-control-label'>Perfil</label>
                                <div class='col-md-1'>
                                    <input type="checkbox" class='form-control' v-model='inspeccion_modal.especimen_perfil'>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-control-label col-md-2">Especificación de Materiales</label>
                                <div class="col-8">
                                    <input :disabled="true" type="text" class="form-control" v-model="servicio_sistema.espeificaciones_material">
                                </div>
                            </div>
                            <hr class="mx-5 mt-2">
                            <p class="font-weight-bold h5 mb-4">Datos de examinación</p>

                            <div class="form-row">
                                <div class="form-group col-lg-3">
                                    <label for="">Distancia al Objeto</label>
                                    <input type="text" class="form-control" name="distancia" v-validate="'required'" v-model="inspeccion_modal.distancia">
                                    <span class="text-danger">{{errors.first("distancia")}}</span>
                                </div>
                                <div class="form-group col-lg-3">
                                    <label for="">Ángulo aproximado a la superficie</label>
                                    <input type="text" data-vv-name="Ángulo" class="form-control" v-validate="'required'" v-model="inspeccion_modal.angulo">
                                    <span class="text-danger">{{errors.first("Ángulo")}}</span>
                                </div>
                                <div class="form-group col-lg-6 text-center">
                                    <label class="" for="">Acabado Superficial</label>
                                    <div class='form-group row border mx-2 py-1 my-auto'>
                                        <label class='col-md-2 form-control-label'>Burdo</label>
                                        <div class='col-md-1'>
                                            <input type="checkbox" value="1" class='form-control' v-model='inspeccion_modal.acabado_burdo'>
                                        </div>
                                        <label class='col-md-3 form-control-label'>Maquinado</label>
                                        <div class='col-md-1'>
                                            <input type="checkbox" value="2" class='form-control' v-model='inspeccion_modal.acabado_maquinado'>
                                        </div>
                                        <label class='col-md-3 form-control-label'>Esmerilado</label>
                                        <div class='col-md-1'>
                                            <input type="checkbox" value="3" class='form-control' v-model='inspeccion_modal.acabado_esmerilado'>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Técnica de examinación visual -->
                            <div class="form-row">
                                <div class="form-group col-lg-3">
                                    <label>Técnica de examinación visual</label>
                                    <br>
                                    <div class="form-inline mt-2">
                                        <input type="checkbox" value="1" class="mr-2 form-control" v-model="inspeccion_modal.examinacion_directa">
                                        <label class="col-form-label" for="">Directa</label>
                                    </div>
                                    <div class="form-inline mt-2">
                                        <input type="checkbox" value="2" class="form-control mr-2" v-model="inspeccion_modal.examinacion_remota">
                                        <label class="col-form-label" for="">Remota</label>
                                    </div>
                                    <div class="form-inline mt-2">
                                        <input type="checkbox" value="3" class="form-control mr-2" v-model="inspeccion_modal.examinacion_translucida">
                                        <label class="col-form-label" for="">Translucida</label>
                                    </div>
                                </div>

                                <!-- Iluminación -->
                                <div class="form-group col-lg-3">
                                    <label>Iluminación</label>
                                    <div class="form-inline">
                                        <input type="checkbox" value="1" class="mr-2 form-control" v-model="inspeccion_modal.iluminacion_natural">
                                        <label class="col-form-label" for="">Natural</label>
                                    </div>
                                    <div class="form-inline">
                                        <input type="checkbox" value="2" class="mr-2 form-control" v-model="inspeccion_modal.iluminacion_mano">
                                        <label class="col-form-label" for="">Lámpara de mano</label>
                                    </div>
                                    <div class="form-inline">
                                        <input type="checkbox" value="3" class="mr-2 form-control" v-model="inspeccion_modal.iluminacion_incan">
                                        <label class="col-form-label" for="">Lámpara Incandescente</label>
                                    </div>
                                    <div class="form-inline">
                                        <input type="checkbox" value="4" class="mr-2 form-control" v-model="inspeccion_modal.iluminacion_reflector">
                                        <label class="col-form-label" for="">Reflector</label>
                                    </div>
                                </div>
                                <!-- Accesorios y Herramientas -->
                                <div class="form-group col-lg-3">
                                    <label for="">Accesorios y Herramientas</label>
                                    <div class="form-inline">
                                        <input type="checkbox" value="1" class="mr-2 form-control" v-model="inspeccion_modal.accesorios_camara">
                                        <label class="col-form-label" for="">Cámara fotográfica</label>
                                    </div>
                                    <div class="form-inline">
                                        <input type="checkbox" value="2" class="mr-2 form-control" v-model="inspeccion_modal.accesorios_bridge">
                                        <label class="col-form-label" for="">Bridge cam</label>
                                    </div>
                                    <div class="form-inline">
                                        <input type="checkbox" value="3" class="mr-2 form-control" v-model="inspeccion_modal.accesorios_seven">
                                        <label class="col-form-label" for="">Seven Fillet</label>
                                    </div>
                                    <div class="form-inline">
                                        <input type="checkbox" value="4" class="mr-2 form-control" v-model="inspeccion_modal.accesorios_hilo">
                                        <label class="col-form-label" for="">Hi/Lo</label>
                                    </div>
                                </div>

                                <!-- metodos de Limpieza -->
                                <div class="form-group col-lg-3">
                                    <label for="">Método de Limpieza de la Superficie</label>
                                    <div class="form-inline mt-2">
                                        <input type="checkbox" value="1" class="mr-2 form-control" v-model="inspeccion_modal.limpieza_meca">
                                        <label class="col-form-label" for="">Limpieza Mecánica</label>
                                    </div>
                                    <div class="form-inline mt-2">
                                        <input type="checkbox" value="2" class="mr-2 form-control" v-model="inspeccion_modal.limpieza_solvente">
                                        <label class="col-form-label" for="">Limpieza con Solvente</label>
                                    </div>
                                    <div class="form-inline mt-2">
                                        <input type="checkbox" value="3" class="mr-2 form-control" v-model="inspeccion_modal.limpieza_maquina">
                                        <label class="col-form-label" for="">Maquinado</label>
                                    </div>
                                </div>
                                <hr class="mx-5 mt-2">
                                <!-- Criterio -->
                            </div>
                            <hr class="mx-5">
                            <p class="font-weight-bold h5 mb-4">Criterio de aceptación</p>
                            <div class="form-row">
                                <div class="col-4">
                                    <label class="col-form-label col-8" for="">Criterio de aceptación</label>
                                    <input type="text" name="criterio" class="form-control col-8" v-model="inspeccion_modal.criterio_aceptacion" v-validate="'required'">
                                    <span class="text-danger">{{errors.first("criterio")}}</span>
                                </div>
                                <div class="col-4">
                                    <label class="col-form-label col-8" for="">Tabla</label>
                                    <input type="text" name="tabla" class="form-control col-8" v-model="inspeccion_modal.tabla" v-validate="'required'" placeholder="Tabla x.x">
                                    <span class="text-danger">{{errors.first("tabla")}}</span>
                                </div>
                            </div>
                            <div v-if="inspeccion_id!=null">
                                <br>
                                <hr class="mx-5 mt-2">
                                <div class="form-inline">
                                    <p class="font-weight-bold h5">Nomenclatura</p>
                                    <button class="btn btn-sm btn-dark ml-2" @click="AbrirModalNomeclatura">
                                        <i class="fas fa-plus"></i> Añadir
                                    </button>
                                </div>
                                <br>
                                <div class="container col-sm-6 ml-0">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Clave</th>
                                                <th scope="col">Nombre</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(nom,i) in list_nomenclaturas">
                                                <th scope="row">{{i+1}}</th>
                                                <td>{{nom.clave}}</td>
                                                <td>{{nom.nombre}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <p class="font-weight-bold h5"></p>
                                <br>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="">Clave inspector</label>
                                    <input type="text" class="form-control col-md-8" v-model="inspeccion_modal.clave_inspector" v-validate="'required'" name="clave">
                                    <span class="text-danger">{{errors.first("clave")}}</span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-lg-4">
                                    <label for="">Inspeccionó</label>
                                    <v-select label="nombre" data-vv-name="Inspecciona" :options="listEmpleados" v-model="inspeccion_modal.inspecciona" v-validate="'required'">
                                    </v-select>
                                    <span class="text-danger">{{errors.first("Inspecciona")}}</span>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="">Supervisó</label>
                                    <v-select label="nombre" data-vv-name="Supervisa" :options="listEmpleados" v-model="inspeccion_modal.supervisa" v-validate="'required'">
                                    </v-select>
                                    <span class="text-danger">{{errors.first("Supervisa")}}</span>
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="">Aprobó</label>
                                    <input type="text" data-vv-name="Aprueba" class="form-control" v-model="inspeccion_modal.aprueba" v-validate="'required'">
                                    <span class="text-danger">{{errors.first("Aprueba")}}</span>
                                </div>
                            </div>
                            <!--Card body -->
                        </div>
                    </div>
                </div>
                <button class="btn btn-secondary  float-sm-right" @click="GuardarVisual">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Spools -->
    <div v-if="ver_modal_nomenclatura" class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_nomenclatura}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Registrar nomenclatura</h4>
                    <button type='button' class='close' @click='CerrarModalNomeclatura' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Nomenclatura</label>
                            <div class='col'>
                                <v-select v-validate="'required'" data-vv-name="nomenclatura" v-model="nomenclatura" :options="list_nomens" label="aux_nombre"></v-select>
                                <span class="text-danger">{{errors.first("nomenclatura")}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalNomeclatura()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' class='btn btn-secondary' @click='GuardarNomenclatura(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
export default
{
    props: ["inspeccion_id", "proyecto_id", "proyecto_nombre"],
    data()
    {
        return {
            // Tabla 
            listEmpleados: [],
            listProcedimientos: [],
            inspeccion_modal:
            {},
            procedimiento:
            {},
            listSistemas: [],
            servicio_sistema:
            {},
            proyecto:
            {},
            cambiarFolio: true,
            isCargandoLoading: false,
            list_nomenclaturas: [],
            ver_modal_nomenclatura: false,
            modal_nomencaltura:
            {},
            proyecto_nombre_aux: "",
            isGuardarNomenclaturaLoading: false,
            list_nomens: [],
            nomenclatura:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        ObtenerDetalles()
        {
            this.isCargandoLoading = true;
            axios.get("/calidad/inspecciones/trazabilidad/detalles/" + this.inspeccion_id).then(res =>
            {
                this.isCargandoLoading = false;
                if (res.data.status)
                {
                    let inspeccion = res.data.inspeccion;
                    this.cambiarFolio = false;
                    // Mostrar datos
                    // inspeccion
                    this.inspeccion_modal = {
                        clave_inspector:inspeccion.clave_inspector,
                        criterio_aceptacion: inspeccion.criterio_aceptacion,
                        tabla: inspeccion.tabla_aceptacion,
                        folio: inspeccion.folio,
                        fecha: inspeccion.fecha,
                        lugar: inspeccion.lugar,
                        distancia: inspeccion.examinacion_distancia,
                        angulo: inspeccion.examinacion_angulo,
                        inspecciona:
                        {
                            id: inspeccion.empleado_inspecciona_id,
                            nombre: inspeccion.empleado_inspecciona
                        },
                        supervisa:
                        {
                            id: inspeccion.empleado_supervisa_id,
                            nombre: inspeccion.empleado_supervisa
                        },
                        aprueba: inspeccion.aprobado,
                        especimen_presion: inspeccion.especimen_presion,
                        especimen_tuberia: inspeccion.especimen_tuberia,
                        especimen_placa: inspeccion.especimen_placa,
                        especimen_perfil: inspeccion.especimen_perfil,
                        acabado_burdo: inspeccion.acabado_burdo,
                        acabado_maquinado: inspeccion.acabado_maquinado,
                        acabado_esmerilado: inspeccion.acabado_esmerilado,
                        examinacion_directa: inspeccion.examinacion_directa,
                        examinacion_remota: inspeccion.examinacion_remota,
                        examinacion_translucida: inspeccion.examinacion_translucida,
                        iluminacion_natural: inspeccion.iluminacion_natural,
                        iluminacion_mano: inspeccion.iluminacion_mano,
                        iluminacion_incan: inspeccion.iluminacion_incan,
                        iluminacion_reflector: inspeccion.iluminacion_reflector,
                        accesorios_camara: inspeccion.accesorios_camara,
                        accesorios_bridge: inspeccion.accesorios_bridge,
                        accesorios_seven: inspeccion.accesorios_seven,
                        accesorios_hilo: inspeccion.accesorios_hilo,
                        limpieza_meca: inspeccion.limpieza_meca,
                        limpieza_solvente: inspeccion.limpieza_solvente,
                        limpieza_maquina: inspeccion.limpieza_maquina,
                    };
                    // this.procedimiento
                    this.procedimiento = {
                        id: inspeccion.cps_id,
                        clave: inspeccion.cps_clave,
                        descripcion: inspeccion.cps_descripcion,
                        wps: inspeccion.wps,
                        pqr: inspeccion.pqr,
                        tipo_preparacion: inspeccion.cps_tipo_preparacion,
                        material_aporte: inspeccion.cps_material_aporte,
                        nombre_aux: inspeccion.procedimiento_nombre
                    };
                    // sistema
                    this.servicio_sistema = {
                        nombre: inspeccion.ss_nombre,
                        ss_id: inspeccion.servicios_sistema_id,
                        plano: inspeccion.plano,
                        sistema_id: inspeccion.cs_id,
                        servicio_id: inspeccion.cs2_id,
                        proyecto_nombre: inspeccion.p_nombre,
                        sistema_nombre: inspeccion.sistema_nombre,
                        espeificaciones_material: inspeccion.espeificaciones_material,
                        p_id: inspeccion.proyecto_id,
                    }
                    this.ObtenerNomenclaturas();
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**
         * Obtiene las nomenclaturas registradas de la inspección actual
         */
        ObtenerNomenclaturas()
        {
            this.isGuardarNomenclaturaLoading = true;
            axios.get("/calidad/inspecciones/trazabilidad/obtenernomen/" +
                this.inspeccion_id).then(res =>
            {
                this.isGuardarNomenclaturaLoading = false;
                if (res.data.status)
                {
                    // Nomenclaturas
                    this.list_nomenclaturas = res.data.nomenclaturas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },
        ObtenerDatos()
        {
            axios.get("/calidad/soldadores/empleados").then(res =>
            {
                if (res.data.status)
                {
                    this.listEmpleados = res.data.empleados;
                }
                else
                {
                    alert("Error al obtener los empleados");
                }
            })
        },
        ObtenerProcediminetos()
        {
            axios.get("/calidad/procedimientos/cargarprocedimientos").then(res =>
            {
                if (res.data.status)
                {
                    this.listProcedimientos = res.data.procedimietos;
                }
            })
        },
        CerrarModalInspeccion()
        {
            this.ver_modal_inspeccion = false;
            Utilerias.resetObject(this.inspeccion_modal);
        },

        // Regresar a card principal
        CerrarCardInspeccion()
        {
            this.$emit("CerrarCardInspeccion");
        },
        GuardarVisual()
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                if (this.procedimiento == null) return;
                if (this.procedimiento.id == null)
                {
                    toastr.warning("Selecione un procedimineto");
                    return;
                }
                this.isCargandoLoading = true;
                axios.post("/calidad/inspecciones/trazabilidad/guardar",
                {
                    id: this.inspeccion_id,
                    fecha: this.inspeccion_modal.fecha,
                    clave_inspector: this.inspeccion_modal.clave_inspector,
                    tabla_aceptacion: this.inspeccion_modal.tabla,
                    criterio_aceptacion: this.inspeccion_modal.criterio_aceptacion,
                    folio: this.inspeccion_modal.folio,
                    lugar: this.inspeccion_modal.lugar,
                    proyecto_id: this.proyecto_id,
                    servicios_sistema_id: this.servicio_sistema.ss_id,
                    procedimiento_id: this.procedimiento.id,
                    examinacion_distancia: this.inspeccion_modal.distancia,
                    examinacion_angulo: this.inspeccion_modal.angulo,
                    empleado_inspecciona_id: this.inspeccion_modal.inspecciona.id,
                    empleado_supervisa_id: this.inspeccion_modal.supervisa.id,
                    aprobado: this.inspeccion_modal.aprueba,
                    especimen_presion: this.inspeccion_modal.especimen_presion,
                    especimen_tuberia: this.inspeccion_modal.especimen_tuberia,
                    especimen_placa: this.inspeccion_modal.especimen_placa,
                    especimen_perfil: this.inspeccion_modal.especimen_perfil,
                    acabado_burdo: this.inspeccion_modal.acabado_burdo,
                    acabado_maquinado: this.inspeccion_modal.acabado_maquinado,
                    acabado_esmerilado: this.inspeccion_modal.acabado_esmerilado,
                    examinacion_directa: this.inspeccion_modal.examinacion_directa,
                    examinacion_remota: this.inspeccion_modal.examinacion_remota,
                    examinacion_translucida: this.inspeccion_modal.examinacion_translucida,
                    iluminacion_natural: this.inspeccion_modal.iluminacion_natural,
                    iluminacion_mano: this.inspeccion_modal.iluminacion_mano,
                    iluminacion_incan: this.inspeccion_modal.iluminacion_incan,
                    iluminacion_reflector: this.inspeccion_modal.iluminacion_reflector,
                    accesorios_camara: this.inspeccion_modal.accesorios_camara,
                    accesorios_bridge: this.inspeccion_modal.accesorios_bridge,
                    accesorios_seven: this.inspeccion_modal.accesorios_seven,
                    accesorios_hilo: this.inspeccion_modal.accesorios_hilo,
                    limpieza_meca: this.inspeccion_modal.limpieza_meca,
                    limpieza_solvente: this.inspeccion_modal.limpieza_solvente,
                    limpieza_maquina: this.inspeccion_modal.limpieza_maquina,
                }).then(res =>
                {
                    this.isCargandoLoading = false;
                    if (res.data.status)
                    {
                        toastr.success("Inspección guardara correctamente");
                        this.CerrarActualizar();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                });
            });
        },
        ObtenerSistemas()
        {
            axios.get("calidad/servsis/obtener/" + this.proyecto_id).then(res =>
            {
                this.isSistemasLoading = false;
                if (res.data.status)
                {
                    this.listSistemas = res.data.sistemas;
                }
            });
        },
        CerrarActualizar()
        {
            this.$emit("CerrarActualizar");
        },
        // Abrir modal de Nomenclatura
        AbrirModalNomeclatura()
        {
            this.ver_modal_nomenclatura = true;
            // Obtener las nomenclaturas registradas
            axios.get("/calidad/nomen/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.list_nomens = res.data.nomenclaturas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },
        CerrarModalNomeclatura()
        {
            this.ver_modal_nomenclatura = false;
            this.modal_nomencaltura = {};
        },
        GuardarNomenclatura()
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                this.isGuardarNomenclaturaLoading = true;
                axios.post("/calidad/inspecciones/trazabilidad/registrarnomen",
                {
                    "inspec_visual_id": this.inspeccion_id,
                    "nomen_id": this.nomenclatura.id,
                }).then(res =>
                {
                    this.isGuardarNomenclaturaLoading = false;
                    if (res.data.status)
                    {
                        toastr.success("Guardado correctamete");
                        this.CerrarModalNomeclatura();
                        this.ObtenerNomenclaturas();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                })
            });
        },
        Limpiar()
        {
            this.inspeccion_modal = {
                "especimen_tipo": 1,
                "distancia": "",
                "angulo": "",
                "acabado": 1,
                "examinacion": 1,
                "iluminacion": 1,
                "accesorios": 1,
                "limpieza": 1,
            };
        },
    }, // Fin metodos
    mounted()
    {
        // Limpiar
        this.ObtenerDatos();
        this.proyecto_nombre_aux = this.proyecto_nombre;
        this.ObtenerProcediminetos();
        this.ObtenerSistemas();
        if (this.inspeccion_id != null)
        {
            this.ObtenerDetalles();
        }
        else
        {
            this.Limpiar();
        }
    }
}
</script>
