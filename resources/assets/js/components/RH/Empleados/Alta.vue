<template>
    <main>
    <vue-element-loading :active="isLoading"/>
    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="form" >
            <div class="form-group row">
                <label class="col-md-1 " for="text-input">Nombre(s)</label>
                <div class="col-md-3">
                <input type="text" v-validate="'required|max:60'" name="nombre" data-toggle="tooltip" title="Agrega Nombre(s) Ej. 'Carlos'" v-model="empleado.nombre" class="form-control" autocomplete="off">
                <span class="text-danger">{{ errors.first('nombre') }}</span>
                </div>
                <label class="col-md-1 " for="text-input">Apellido Paterno</label>
                <div class="col-md-3">
                <input type="text" v-validate="'required|max:40'" name="paterno" data-toggle="tooltip" title="Agrega Apellido Paterno" v-model="empleado.ap_paterno" class="form-control"  autocomplete="off">
                <span class="text-danger">{{ errors.first('paterno') }}</span>
                </div>
                <label class="col-md-1 " for="text-input">Apellido Materno</label>
                <div class="col-md-3">
                <input type="text" v-validate="'required|max:40'" name="materno" data-toggle="tooltip" title="Agrega Apellido Materno" v-model="empleado.ap_materno" class="form-control"  autocomplete="off">
                <span class="text-danger">{{ errors.first('materno') }}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-1" for="text-input">Fecha de alta IMSS</label>
                <div class="col-md-3">
                <input type="date" v-model="empleado.fech_alta_imss" name="fech_alta_imss" v-validate="'required'" class="form-control" placeholder="Fecha de Alta IMSS">
                <span class="text-danger">{{ errors.first('fech_alta_imss') }}</span>
                </div>
                <label class="col-md-1" for="text-input">Fecha Real de Ingreso</label>
                <div class="col-md-3">
                <input type="date" v-model="empleado.fech_ing" name="fech_ing" v-validate="'required'" class="form-control" placeholder="Fecha Real de Ingreso">
                <span class="text-danger">{{ errors.first('fech_ing') }}</span>
                </div>
                <label class="col-md-1 " for="text-input">CURP</label>
                <div class="col-md-3">
                <input type="text" v-validate="'required'" maxlength="18" name="curp" data-toggle="tooltip" title="Completa los 18 dígitos de la CURP" v-model="empleado.curp" class="form-control" placeholder="Curp" autocomplete="off">
                <span class="text-danger">{{ errors.first('curp') }}</span>
                </div>
                <label class="col-md-1 " for="text-input">RFC:</label>
                <div class="col-md-3">
                <input type="text" v-validate="'required'" maxlength="13" name="rfc" data-toggle="tooltip" title="Completa los 13 dígitos del RFC" v-model="empleado.rfc" class="form-control" placeholder="Rfc" autocomplete="off">
                <span class="text-danger">{{ errors.first('rfc') }}</span>
                </div>
                <label class="col-md-1 " for="text-input">NSS</label>
                <div class="col-md-3">
                <input type="text" v-validate="'required'" maxlength="15" name="nss_imss" data-toggle="tooltip" title="Completa los 11 dígitos del N° de seguro" v-model="empleado.nss_imss" class="form-control" placeholder="NSS" autocomplete="off">
                <span class="text-danger">{{ errors.first('nss_imss') }}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-1 " for="text-input">Lugar de Nacimiento</label>
                <div class="col-md-3">
                <input type="text" v-model="empleado.lug_nac" v-validate="'required|max:45'" class="form-control" id="lug_nac" name="lug_nac" placeholder="Lugar de nacimiento" autocomplete="off" required data-vv-as="lugar de nacimiento">
                <span class="text-danger">{{ errors.first('lug_nac') }}</span>
                </div>
                <label class="col-md-1" for="text-input">Fecha de Nacimiento</label>
                <div class="col-md-3">
                <input type="date" v-model="empleado.fech_nac" name="fech_nac" v-validate="'required'" class="form-control" placeholder="Fecha de Nacimiento">
                <span class="text-danger">{{ errors.first('fech_nac') }}</span>
                </div>

                <label class="col-md-1 " for="text-input" >Sexo</label>
                <div class="col-md-3">
                <p>
                    <select name="text-input" id="text-input" v-model="empleado.sexo" data-toggle="tooltip" title="Selecciona sexo" class="form-control"  v-validate="'excluded:'" data-vv-as="sexo">
                        <option value="1">Masculino</option>
                        <option value="0">Femenino</option>
                    </select>
                    <span class="text-danger">{{ errors.first('text-input') }}</span>
                    </p>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <label class="col-md-1 " for="text-input">Edo. Civil</label>
                <div class="col-md-3">
                <select class="form-control" id="edo_civil_id"  name="edo_civil_id" data-toggle="tooltip" title="Selecciona estado civil" v-model="empleado.edo_civil_id" v-validate="'excluded:0'" data-vv-as="estado civil">
                    <option value="0">--- Seleccionar ---</option>
                        <option v-for="item in listaEstados" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                    </select>
                    <span class="text-danger">{{ errors.first('edo_civil_id') }}</span>
                </div>
                <label class="col-md-1 " for="text-input">Tipo sanguíneo</label>
                <div class="col-md-3">
                <select v-model="empleado.tipo_sangre" class="form-control">

                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                </select>
                </div>
                <label class="col-md-1 " for="text-input">Overol</label>
                <div class="col-md-3">
                <input type="number" min="0" step="1" v-model="empleado.talla_overol" data-toggle="tooltip" title="Inserta talla de overol" class="form-control" placeholder="Talla overol">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-1 " for="text-input">Botas</label>
                <div class="col-md-3">
                <input type="number" min="0" step="0.5" v-model="empleado.talla_botas" data-toggle="tooltip" title="Inserta talla de botas" class="form-control" placeholder="Talla botas">
                </div>

                <label class="col-md-1 " for="text-input">Amortización</label>
                <div class="col-md-3">
                <input type="text" v-model="empleado.amortizacion" class="form-control" placeholder="Amortizacion">
                </div>

                <label class="col-md-1 " for="text-input">Número Credito</label>
                <div class="col-md-3">
                <input type="number" v-model="empleado.numero_credito" class="form-control" data-toggle="tooltip" title="Ingresa N° de crédito" placeholder="Numero de Credito">
                </div>

                <label class="col-md-1 form-control-label" for="puesto_id">Puesto</label>
                <div class="col-md-3">
                    <select class="form-control" id="puesto_id"  name="puesto_id" data-toggle="tooltip" title="Selecciona puesto" v-model="empleado.puesto_id" v-validate="'excluded:0'" data-vv-as="puesto">
                        <option value="0">--- Seleccionar ---</option>
                        <option v-for="item in listaPuestos" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                    </select>
                    <span class="text-danger">{{ errors.first('puesto_id') }}</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 " for="text-input">Ubicacion de planta</label>
                <div class="col-md-4">
                    <select class="form-control" id="ubicacion_id"  name="ubicacion_id" data-toggle="tooltip" title="Selecciona la ubicación del empleado"  v-model="empleado.ubicacion_id" v-validate="'excluded:0'" data-vv-as="Ubicacion">
                        <option value="0">--- Seleccionar ---</option>
                        <option v-for="item in listaUbicaciones" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                    </select>
                    <span class="text-danger">{{ errors.first('ubicacion_id') }}</span>
                </div>
                <label class="col-md-1 " for="text-input">Empresa</label>
                <div class="col-md-2">
                  <select class="form-control" v-model="empleado.id_checador" v-validate="'required'" data-vv-name="empresa">
                    <option value="1">Conserflow Semanal</option>
                    <option value="2">Conserflow Quincenal</option>
                    <option value="3">CSCT Semanal</option>
                    <option value="4">CSCT Quincenal</option>
                  </select>
                  <!-- <input type="number"  class="form-control" title="Id checador" placeholder="Id checador"> -->
                    <span class="text-danger">{{ errors.first('empresa') }}</span>
                </div>
            </div>
        </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="guardarEmpleado()"><i class="fas fa-save"></i>&nbsp;{{ (tipoAccion == 1) ? 'Guardar' : 'Actualizar' }}</button>
        </div>
    </main>
</template>

<script>
var config = require('../../Herramientas/config-vuetables-client').call(this);
import Utilerias from '../../Herramientas/utilerias.js';

    export default {
        data (){
            return {
                url: '/empleado',
                empleado: {
                    id:0,
                    nombre : '',
                    ap_paterno : '',
                    ap_materno : '',
                    sexo: '',
                    lug_nac : '',
                    fech_nac : '',
                    fech_alta_imss : '',
                    fech_ing : '',
                    tipo_sangre : '',
                    talla_overol : 0,
                    talla_botas : 0,
                    amortizacion: 0,
                    numero_credito: 0,
                    edo_civil_id : 0,
                    puesto_id : 0,
                    curp: '',
                    rfc: '',
                    nss_imss: '',
                    ubicacion_id: 0,
                    id_checador : '',
                },
                tipoAccion : 1,
                error : 0,
                listaUbicaciones: [],
                listaEstados: [],
                listaPuestos: [],
                errorMostrarMsj : [],
                isLoading: false,
            }
        },
        methods : {
            getLista() {
                let me=this;
                axios.get('/estadosciviles').then(response => {
                    me.listaEstados = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
                axios.get('/tipoubicacion').then(response => {
                    me.listaUbicaciones = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
                axios.get('/puesto').then(response => {
                    me.listaPuestos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            guardarEmpleado(){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: me.tipoAccion == 1 ? 'POST' : 'PUT',
                            url: me.tipoAccion == 1 ? me.url : me.url + '/' + this.empleado.id,
                            data: {
                                'nombre' : this.empleado.nombre,
                                'ap_paterno' : this.empleado.ap_paterno,
                                'ap_materno' : this.empleado.ap_materno,
                                'lug_nac' : this.empleado.lug_nac,
                                'fech_nac' : this.empleado.fech_nac,
                                'fech_alta_imss' : this.empleado.fech_alta_imss,
                                'fech_ing' : this.empleado.fech_ing,
                                'curp' :this.empleado.curp,
                                'rfc' : this.empleado.rfc,
                                'nss_imss' : this.empleado.nss_imss,
                                'sexo' : this.empleado.sexo,
                                'edo_civil_id' : this.empleado.edo_civil_id,
                                'tipo_sangre' : this.empleado.tipo_sangre,
                                'talla_overol' : this.empleado.talla_overol,
                                'talla_botas' : this.empleado.talla_botas,
                                'amortizacion' : this.empleado.amortizacion,
                                'numero_credito' : this.empleado.numero_credito,
                                'puesto_id' : this.empleado.puesto_id,
                                'ubicacion_id' : this.empleado.ubicacion_id,
                                'id_checador' : this.empleado.id_checador,
                            }
                        }).then(function (response) {
                            toastr.success('Empleado ' + ((me.tipoAccion == 1) ? 'Registrado' : 'Actualizado') + ' Correctamente.');
                            me.empleado = response.data;
                            me.$emit('clicked', me.empleado, (me.tipoAccion == 1) );

                            me.tipoAccion = 2;
                            me.isLoading = false;
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                    else{
                        toastr.error('Completa todos los campos');
                    }
                });
            },
            cargarEmpleado(dataEmpleado = null) {
                if (dataEmpleado == null) {
                    Utilerias.resetObject(this.empleado);
                    this.tipoAccion = 1;
                } else {
                    Utilerias.resetObject(this.empleado);
                    this.tipoAccion = 2;
                    this.isLoading = true;
                    let me=this;
                    axios.get('/empleado/' + dataEmpleado.id).then(response => {
                        me.isLoading = false;
                        me.empleado = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            }
        },
        mounted() {
            this.getLista();

        }
    }
</script>
