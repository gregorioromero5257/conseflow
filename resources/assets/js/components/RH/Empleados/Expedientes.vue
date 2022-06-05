<template>
    <main>
        <vue-element-loading :active="isLoading"/>
        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
        <div class="form-group row">
            <label class="col-md-3 form-control-label" for="folio">Folio</label>
            <div class="col-md-9">
                <input type="text" v-validate="'required'" name="folio" v-model="expediente.folio" class="form-control" placeholder="Folio" autocomplete="off" id="folio">
                <span class="text-danger">{{ errors.first('folio') }}</span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <div class="form-check checkbox">
                    <input class="" value="" id="check1" type="checkbox" v-model="expediente.solicitud">
                    <label class="form-check-label" for="check1">
                    Solicitud
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check2" type="checkbox" v-model="expediente.evaluacion_psicolaboral">
                    <label class="form-check-label" for="check2">
                    Evaluación Psicolaboral
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check3" type="checkbox" v-model="expediente.evaluacion_hab_tecnicas">
                    <label class="form-check-label" for="check3">
                    Evaluación hab. técnicas
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check4" type="checkbox" v-model="expediente.comprobante_domicilio">
                    <label class="form-check-label" for="check4">
                    Comprobante de domicilio
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check5" type="checkbox" v-model="expediente.cartilla_servicio_militar">
                    <label class="form-check-label" for="check5">
                    Cartilla servicio militar
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check6" type="checkbox" v-model="expediente.vales_resguardo">
                    <label class="form-check-label" for="check6">
                    Vales de resguardo
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check7" type="checkbox" v-model="expediente.comprobante_estudios">
                    <label class="form-check-label" for="check7">
                    Comprobante de estudios
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check checkbox">
                    <input class="" value="" id="check8" type="checkbox" v-model="expediente.foto">
                    <label class="form-check-label" for="check8">
                    Foto
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check9" type="checkbox" v-model="expediente.acta_nacimiento">
                    <label class="form-check-label" for="check9">
                    Acta de nacimiento
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check10" type="checkbox" v-model="expediente.credencial_elector">
                    <label class="form-check-label" for="check10">
                    Credencial de elector
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check11" type="checkbox" v-model="expediente.licencia_manejo">
                    <label class="form-check-label" for="check11">
                    Licencia manejo
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check12" type="checkbox" v-model="expediente.acta_matrimonio">
                    <label class="form-check-label" for="check12">
                    Acta de matrimonio
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check13" type="checkbox" v-model="expediente.carta_recomendacion">
                    <label class="form-check-label" for="check13">
                    Carta de recomendación
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check14" type="checkbox" v-model="expediente.retencion_credito_infonavit">
                    <label class="form-check-label" for="check14">
                    Retención crédito infonavit
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check checkbox">
                    <input class="" value="" id="check15" type="checkbox" v-model="expediente.curp">
                    <label class="form-check-label" for="check15">
                    CURP
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check16" type="checkbox" v-model="expediente.rfc">
                    <label class="form-check-label" for="check16">
                    RFC
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check17" type="checkbox" v-model="expediente.nss_imss">
                    <label class="form-check-label" for="check17">
                    NSS IMSS
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check18" type="checkbox" v-model="expediente.carta_infonavit">
                    <label class="form-check-label" for="check18">
                    Carta infonavit
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check19" type="checkbox" v-model="expediente.certificado_medico">
                    <label class="form-check-label" for="check19">
                    Certificado médico
                    </label>
                </div>
                <div class="form-check checkbox">
                    <input class="" value="" id="check20" type="checkbox" v-model="expediente.carta_no_enfermedades">
                    <label class="form-check-label" for="check20">
                    Carta de no enfermedades
                    </label>
                </div>
            </div>
        </div>
        <!-- </form> -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="guardarexpediente(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
        </div>
    </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                expediente: {
                    id: 0,
                    solicitud: 0,
                    evaluacion_psicolaboral: 0,
                    evaluacion_hab_tecnicas: 0,
                    foto: 0,
                    acta_nacimiento: 0,
                    credencial_elector: 0,
                    curp: 0,
                    rfc: 0,
                    nss_imss: 0,
                    comprobante_domicilio: 0,
                    cartilla_servicio_militar: 0,
                    licencia_manejo: 0,
                    acta_matrimonio: 0,
                    carta_infonavit: 0,
                    certificado_medico: 0,
                    carta_no_enfermedades: 0,
                    vales_resguardo: 0,
                    comprobante_estudios: 0,
                    carta_recomendacion: 0,
                    retencion_credito_infonavit: 0,
                    folio: '',
                    empleado_id: 0,
                },
                isLoading: false,
            }
        },
        computed:{
        },
        methods : {
            guardarexpediente(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/expediente/registrar' : '/expediente/actualizar',
                            data: {
                                'folio': this.expediente.folio,
                                'id': this.expediente.id,
                                'empleado_id': this.expediente.empleado_id,
                                'solicitud': this.expediente.solicitud,
                                'evaluacion_psicolaboral': this.expediente.evaluacion_psicolaboral,
                                'evaluacion_hab_tecnicas': this.expediente.evaluacion_hab_tecnicas,
                                'foto': this.expediente.foto,
                                'acta_nacimiento': this.expediente.acta_nacimiento,
                                'credencial_elector': this.expediente.credencial_elector,
                                'curp': this.expediente.curp,
                                'rfc': this.expediente.rfc,
                                'nss_imss': this.expediente.nss_imss,
                                'comprobante_domicilio': this.expediente.comprobante_domicilio,
                                'cartilla_servicio_militar': this.expediente.cartilla_servicio_militar,
                                'licencia_manejo': this.expediente.licencia_manejo,
                                'acta_matrimonio': this.expediente.acta_matrimonio,
                                'carta_infonavit': this.expediente.carta_infonavit,
                                'certificado_medico': this.expediente.certificado_medico,
                                'carta_no_enfermedades': this.expediente.carta_no_enfermedades,
                                'vales_resguardo': this.expediente.vales_resguardo,
                                'comprobante_estudios': this.expediente.comprobante_estudios,
                                'carta_recomendacion': this.expediente.carta_recomendacion,
                                'retencion_credito_infonavit': this.expediente.retencion_credito_infonavit,
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                toastr.success('Expediente Actualizado Correctamente.');
                            } else {
                                swal({
                                    type: 'error',
                                    html: response.data.errors.join('<br>'),
                                });
                            }
                        }).catch(function (error) {
                            console.log(error);
                        }); 
                    }
                });
            },
            cargarExpediente(dataEmpleado = []){
                Utilerias.resetObject(this.expediente);
                this.isLoading = true;
                let me=this;
                axios.get('/expediente/' + dataEmpleado.id).then(response => {
                    me.isLoading = false;
                    me.expediente.id=response.data['id'];
                    me.expediente.folio = response.data['folio'];
                    me.expediente.empleado_id = response.data['empleado_id'];
                    me.expediente.solicitud = response.data['solicitud'];
                    me.expediente.evaluacion_psicolaboral = response.data['evaluacion_psicolaboral'];
                    me.expediente.evaluacion_hab_tecnicas = response.data['evaluacion_hab_tecnicas'];
                    me.expediente.foto = response.data['foto'];
                    me.expediente.acta_nacimiento = response.data['acta_nacimiento'];
                    me.expediente.credencial_elector = response.data['credencial_elector'];
                    me.expediente.curp = response.data['curp'];
                    me.expediente.rfc = response.data['rfc'];
                    me.expediente.nss_imss = response.data['nss_imss'];
                    me.expediente.comprobante_domicilio = response.data['comprobante_domicilio'];
                    me.expediente.cartilla_servicio_militar = response.data['cartilla_servicio_militar'];
                    me.expediente.licencia_manejo = response.data['licencia_manejo'];
                    me.expediente.acta_matrimonio = response.data['acta_matrimonio'];
                    me.expediente.carta_infonavit = response.data['carta_infonavit'];
                    me.expediente.certificado_medico = response.data['certificado_medico'];
                    me.expediente.carta_no_enfermedades = response.data['carta_no_enfermedades'];
                    me.expediente.vales_resguardo = response.data['vales_resguardo'];
                    me.expediente.comprobante_estudios = response.data['comprobante_estudios'];
                    me.expediente.carta_recomendacion = response.data['carta_recomendacion'];
                    me.expediente.retencion_credito_infonavit = response.data['retencion_credito_infonavit'];
                })
                .catch(function (error) {
                    console.log(error);
                });
            }            
        },
        mounted() {
        }
    }
</script>