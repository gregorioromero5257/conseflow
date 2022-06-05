<template>
    <main>
        <vue-element-loading :active="isLoading"/>
            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
            <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="correo_electronico">Correo electrónico</label>
                    <div class="col-md-9">
                        <input type="email"  size="60" v-validate="'required|max:60'" name="correo_electronico" v-model="contacto.correo_electronico" class="form-control" placeholder="Correo electrónico" autocomplete="off" id="correo_electronico" data-vv-as="Correo electronico">
                        <span class="text-danger">{{ errors.first('correo_electronico') }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="tel_celular">Teléfono celular</label>
                    <div class="col-md-9">
                        <input type="text" v-validate="'required|numeric|max:13'" name="tel_celular" v-model="contacto.tel_celular" class="form-control" placeholder="Tel. celular" autocomplete="off" id="tel_celular" data-vv-as="Tel. celular">
                        <span class="text-danger">{{ errors.first('tel_celular') }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="tel_casa">Teléfono casa</label>
                    <div class="col-md-9">
                        <input type="text" v-validate="'required|max:13'" name="tel_casa" v-model="contacto.tel_casa" class="form-control" placeholder="Tel. casa" autocomplete="off" id="tel_casa" data-vv-as="Tel. casa">
                        <span class="text-danger">{{ errors.first('tel_casa') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="contacto_emergencia">Contacto emergencia</label>
                    <div class="col-md-9">
                        <input type="text" v-validate="'required|max:50'" name="contacto_emergencia" v-model="contacto.contacto_emergencia" class="form-control" placeholder="Contacto emergencia" autocomplete="off" id="contacto_emergencia" data-vv-as="Contacto emergencia">
                        <span class="text-danger">{{ errors.first('contacto_emergencia') }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="tel_emergencia">Telefono emergencia</label>
                    <div class="col-md-9">
                        <input type="text" v-validate="'required|numeric'" name="tel_emergencia" v-model="contacto.tel_emergencia" class="form-control" placeholder="Tel. emergencia" autocomplete="off" id="tel_emergencia" data-vv-as="Tel. emergencia">
                        <span class="text-danger">{{ errors.first('tel_emergencia') }}</span>
                    </div>
                </div>
            </div>
            </div>
            <!-- </form> -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="guardarContacto(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
        </div>
    </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                empleado: null,
                contacto: {
                    id: 0,
                    tel_celular: '',
                    tel_casa: '',
                    correo_electronico: '',
                    contacto_emergencia: '',
                    tel_emergencia: '',
                    empleado_id: 0,
                },
                isLoading: false,
            }
        },
        computed:{
        },
        methods : {
            guardarContacto(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: 'PUT',
                            url: '/contactoemp/actualizar/',
                            data: {
                                'id': this.contacto.id,
                                'empleado_id': this.contacto.empleado_id,
                                'tel_celular': this.contacto.tel_celular,
                                'tel_casa': this.contacto.tel_casa,
                                'contacto_emergencia': this.contacto.contacto_emergencia,
                                'correo_electronico': this.contacto.correo_electronico,
                                'tel_emergencia': this.contacto.tel_emergencia,
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                toastr.success('Contacto Actualizado Correctamente.');
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
            cargarContacto(dataEmpleado = []) {
                Utilerias.resetObject(this.contacto);
                this.isLoading = true;
                let me=this;
                axios.get('/contacto/' + dataEmpleado.id).then(response => {
                    me.isLoading = false;
                    this.contacto.id=response.data['id'];
                    this.contacto.empleado_id = response.data['empleado_id'];
                    this.contacto.tel_celular = response.data['tel_celular'];
                    this.contacto.tel_casa = response.data['tel_casa'];
                    this.contacto.correo_electronico = response.data['correo_electronico'];
                    this.contacto.contacto_emergencia = response.data['contacto_emergencia'];
                    this.contacto.tel_emergencia = response.data['tel_emergencia'];
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
