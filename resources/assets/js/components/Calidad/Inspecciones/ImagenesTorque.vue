<template>
<!-- Imagenes -->
<div class="card">
    <div class='card-header'>
        <i class='fa fa-align-justify'></i>{{folio}}
        <button type='button' class='btn btn-dark float-sm-right' @click='Regresar()'>
            <i class='fas fa-arrow-left'>&nbsp;</i> Regresar
        </button>
    </div>
    <div class="card-body">
        <!-- ubicación -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class='card-header text-white bg-dark'>
                        <i class='fa fa-align-justify'></i>Ubicación Unión Brida
                    </div>
                    <div class="card-body">
                        <div class="form-group" v-show="!listImagenes_changed[0]">
                            <label for="img1">Seleccionar imagén</label>
                            <input type="file" id="img0" class="form-control-file" accept="image/x-png,image/jpeg">
                            <button class="btn btn-dark btn-sm" @click="GuardarImagenes2(0)">
                                <i class="fas fa-save"></i> &nbsp; Guardar
                            </button>
                        </div>

                        <div class="container-fluid" v-show="listImagenes_changed[0]">
                            <vue-element-loading :active="isFotosLoading" />
                            <div class="">
                                <img :src="listModelImagenes[0].ruta" class="img-fluid" alt="" @click="VerImagen(listModelImagenes[0].ruta)">
                            </div>
                            <button class="btn btn-secondary" @click="CambiarImagen(0)">
                                <i class="fas fa-exchange-alt"></i> &nbsp;
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class='card-header text-white bg-dark'>
                <i class='fa fa-align-justify'></i>Evidencia Fotográfica
            </div>
            <div class="card-body">
                <!-- 1-3 -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="container-fluid border my-1">
                            <div class="form-group" v-show="!listImagenes_changed[1]">
                                <label for="img1">Seleccionar imagén 1</label>
                                <input type="file" id="img1" class="form-control-file" accept="image/x-png,image/jpeg">
                                <button class="btn btn-dark btn-sm" @click="GuardarImagenes2(1)">
                                    <i class="fas fa-save"></i> &nbsp; Guardar
                                </button>
                            </div>

                            <div class="container-fluid" v-show="listImagenes_changed[1]">
                                <vue-element-loading :active="isFotosLoading" />
                                <div class="container-img">
                                    <img :src="listModelImagenes[1].ruta" class="img-fluid" alt="" @click="VerImagen(listModelImagenes[1].ruta)">
                                    <button class="btn btn-secondary" @click="CambiarImagen(1)">
                                        <i class="fas fa-exchange-alt"></i> &nbsp;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="container-fluid border my-1">
                            <div class="form-group" v-show="!listImagenes_changed[2]">
                                <label for="img2">Seleccionar imagén 2</label>
                                <input type="file" id="img2" class="form-control-file" accept="image/x-png,image/jpeg">
                                <button class="btn btn-dark btn-sm" @click="GuardarImagenes2(2)">
                                    <i class="fas fa-save"></i> &nbsp; Guardar
                                </button>
                            </div>
                            <div class="container-fluid" v-show="listImagenes_changed[2]">
                                <vue-element-loading :active="isFotosLoading" />
                                <div class="container-img">
                                    <img :src="listModelImagenes[2].ruta" class="img-fluid" alt="" @click="VerImagen(listModelImagenes[2].ruta)">
                                    <button class="btn btn-secondary" @click="CambiarImagen(2)">
                                        <i class="fas fa-exchange-alt"></i> &nbsp;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="container-fluid border my-1">
                            <div class="form-group" v-show="!listImagenes_changed[3]">
                                <label for="img1">Seleccionar imagén 3</label>
                                <input type="file" id="img3" class="form-control-file" accept="image/x-png,image/jpeg">
                                <button class="btn btn-dark btn-sm" @click="GuardarImagenes2(3)">
                                    <i class="fas fa-save"></i> &nbsp; Guardar
                                </button>
                            </div>
                            <div class="container-fluid" v-show="listImagenes_changed[3]">
                                <vue-element-loading :active="isFotosLoading" />
                                <div class="container-img">
                                    <img :src="listModelImagenes[3].ruta" class="img-fluid" alt="" @click="VerImagen(listModelImagenes[3].ruta)">
                                    <button class="btn btn-secondary" @click="CambiarImagen(3)">
                                        <i class="fas fa-exchange-alt"></i> &nbsp;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <!-- 4-6 -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="container-fluid border my-1">
                            <div class="form-group" v-show="!listImagenes_changed[4]">
                                <label for="img4">Seleccionar imagén 4</label>
                                <input type="file" id="img4" class="form-control-file" accept="image/x-png,image/jpeg">
                                <button class="btn btn-dark btn-sm" @click="GuardarImagenes2(4)">
                                    <i class="fas fa-save"></i> &nbsp; Guardar
                                </button>
                            </div>
                            <div class="container-fluid" v-show="listImagenes_changed[4]">
                                <vue-element-loading :active="isFotosLoading" />
                                <div class="container-img">
                                    <img :src="listModelImagenes[4].ruta" class="img-fluid" alt="" @click="VerImagen(listModelImagenes[4].ruta)">
                                    <button class="btn btn-secondary" @click="CambiarImagen(4)">
                                        <i class="fas fa-exchange-alt"></i> &nbsp;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="container-fluid border my-1">
                            <div class="form-group" v-show="!listImagenes_changed[5]">
                                <label for="img5">Seleccionar imagén 5</label>
                                <input type="file" id="img5" class="form-control-file" accept="image/x-png,image/jpeg">
                                <button class="btn btn-dark btn-sm" @click="GuardarImagenes2(5)">
                                    <i class="fas fa-save"></i> &nbsp; Guardar
                                </button>
                            </div>
                            <div class="container-fluid" v-show="listImagenes_changed[5]">
                                <vue-element-loading :active="isFotosLoading" />
                                <div class="container-img">
                                    <img :src="listModelImagenes[5].ruta" class="img-fluid" alt="" @click="VerImagen(listModelImagenes[5].ruta)">
                                    <button class="btn btn-secondary" @click="CambiarImagen(5)">
                                        <i class="fas fa-exchange-alt"></i> &nbsp;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="container-fluid border my-1">
                            <div class="form-group" v-show="!listImagenes_changed[6]">
                                <label for="img6">Seleccionar imagén 6</label>
                                <input type="file" id="img6" class="form-control-file" accept="image/x-png,image/jpeg">
                                <button class="btn btn-dark btn-sm" @click="GuardarImagenes2(6)">
                                    <i class="fas fa-save"></i> &nbsp; Guardar
                                </button>
                            </div>
                            <div class="container-fluid" v-show="listImagenes_changed[6]">
                                <vue-element-loading :active="isFotosLoading" />
                                <div class="container-img">
                                    <img :src="listModelImagenes[6].ruta" class="img-fluid" alt="" @click="VerImagen(listModelImagenes[6].ruta)">
                                    <button class="btn btn-secondary" @click="CambiarImagen(6)">
                                        <i class="fas fa-exchange-alt"></i> &nbsp;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <!-- 7-9 -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="container-fluid border my-1">
                            <div class="form-group" v-show="!listImagenes_changed[7]">
                                <label for="img7">Seleccionar imagén 7</label>
                                <input type="file" id="img7" class="form-control-file" accept="image/x-png,image/jpeg">
                                <button class="btn btn-dark btn-sm" @click="GuardarImagenes2(7)">
                                    <i class="fas fa-save"></i> &nbsp; Guardar
                                </button>
                            </div>
                            <div class="container-fluid" v-show="listImagenes_changed[7]">
                                <vue-element-loading :active="isFotosLoading" />
                                <div class="container-img">
                                    <img :src="listModelImagenes[7].ruta" class="img-fluid" alt="" @click="VerImagen(listModelImagenes[7].ruta)">
                                    <button class="btn btn-secondary" @click="CambiarImagen(7)">
                                        <i class="fas fa-exchange-alt"></i> &nbsp;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="container-fluid border my-1">
                            <div class="form-group" v-show="!listImagenes_changed[8]">
                                <label for="img8">Seleccionar imagén 8</label>
                                <input type="file" id="img8" class="form-control-file" accept="image/x-png,image/jpeg">
                                <button class="btn btn-dark btn-sm" @click="GuardarImagenes2(8)">
                                    <i class="fas fa-save"></i> &nbsp; Guardar
                                </button>
                            </div>
                            <div class="container-fluid" v-show="listImagenes_changed[8]">
                                <vue-element-loading :active="isFotosLoading" />
                                <div class="container-img">
                                    <img :src="listModelImagenes[8].ruta" class="img-fluid" alt="" @click="VerImagen(listModelImagenes[8].ruta)">
                                    <button class="btn btn-secondary" @click="CambiarImagen(8)">
                                        <i class="fas fa-exchange-alt"></i> &nbsp;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="container-fluid border my-1">
                            <div class="form-group" v-show="!listImagenes_changed[9]">
                                <label for="img9">Seleccionar imagén 9</label>
                                <input type="file" id="img9" class="form-control-file" accept="image/x-png,image/jpeg">
                                <button class="btn btn-dark btn-sm" @click="GuardarImagenes2(9)">
                                    <i class="fas fa-save"></i> &nbsp; Guardar
                                </button>
                            </div>
                            <div class="container-fluid" v-show="listImagenes_changed[9]">
                                <vue-element-loading :active="isFotosLoading" />
                                <div class="container-img">
                                    <img :src="listModelImagenes[9].ruta" class="img-fluid" alt="" @click="VerImagen(listModelImagenes[9].ruta)">
                                    <button class="btn btn-secondary" @click="CambiarImagen(9)">
                                        <i class="fas fa-exchange-alt"></i> &nbsp;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</template>

<style>
img:hover {
    cursor: pointer;
}

.container-img btn {
    position: absolute;
    bottom: 0;
    right: 0;
    margin: 3px;
}
</style>

<script>
import TraspasosVue from '../../Almacen/Transferencias/Traspasos.vue';
export default
{
    props: ["folio", "reporte_id"],
    data()
    {
        return {
            url: "calidad/torque",
            // ubicacion,imagen 1-6
            listImagenes_changed: [],
            listModelImagenes: [
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {}, ],
            isFotosLoading: false
        }
    },
    methods:
    {
        CargarImagenes()
        {
            // Obtener imágenes
            axios.get(this.url + "/obtenerimagenes/" + this.reporte_id).then(res =>
            {
                if (res.data.status)
                {
                    this.listImagenes_changed = [
                        false, false, false,
                        false, false, false,
                        false, false, false, false
                    ];
                    if (res.data.imagenes.valido)
                    {
                        let temp_models = [
                        {
                            tipo: 1,
                            posision: 0
                        },
                        {
                            tipo: 2,
                            posision: 1
                        },
                        {
                            tipo: 2,
                            posision: 2
                        },
                        {
                            tipo: 2,
                            posision: 3
                        },
                        {
                            tipo: 2,
                            posision: 4
                        },
                        {
                            tipo: 2,
                            posision: 5
                        },
                        {
                            tipo: 2,
                            posision: 6
                        },
                        {
                            tipo: 2,
                            posision: 7
                        },
                        {
                            tipo: 2,
                            posision: 8
                        },
                        {
                            tipo: 2,
                            posision: 9
                        }];
                        res.data.imagenes.imagenes.forEach((img, i) =>
                        {
                            let model = {
                                id: img.id,
                                tipo: img.tipo,
                                posision: img.posision,
                                ruta: img.ruta
                            };
                            temp_models[img.posision] = model;
                            this.listImagenes_changed[img.posision] = true;
                        });
                        this.listModelImagenes = temp_models;
                    }
                }
            })
        },

        /**
         * Guarda la imagen ingresada
         */
        GuardarImagenes2(n_imagen)
        {
            if (!this.Validar(n_imagen))
            {
                // No seleccionada
                toastr.warning("Seleccionar la imágen " + n_imagen);
                return;
            }
            // Obtener imagen
            let imagen = "#img" + n_imagen;
            let data_img = $(imagen).prop('files')[0];

            // Obtener model de imagen
            let model = this.listModelImagenes[n_imagen];

            let data = new FormData();
            if (model.id != null)
                data.append("id", model.id);
            data.append("torque_id", this.reporte_id);
            data.append("tipo", model.tipo);
            data.append("posision", model.posision);
            data.append("path_imagen", data_img);

            axios.post(this.url + "/guardarimagenes", data).then(res =>
            {
                if (res.data.status)
                {
                    this.isFotosLoading = false;
                    toastr.success("Guardado correctamente");
                    this.CargarImagenes();
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })

        },

        /**
         * Guarda las imagenes del reporte
         */
        GuardarImagenes()
        {
            // Continuar si la imagen es valida
            if (!this.Validar(0)) return;
            if (!this.Validar(1)) return;
            if (!this.Validar(2)) return;
            if (!this.Validar(3)) return;
            if (!this.Validar(4)) return;
            if (!this.Validar(5)) return;
            if (!this.Validar(6)) return;
            if (!this.Validar(7)) return;
            if (!this.Validar(8)) return;
            if (!this.Validar(9)) return;
            // Guardar imagenes
            this.isFotosLoading = true;
            let data = "";
            axios.post(this.url + "/guardarimagenes", data).then(res =>
            {
                if (res.data.status)
                {
                    this.isFotosLoading = false;
                    toastr.success("Guardado correctamente");
                    this.CargarImagenes();
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },

        /**
         * Validar que se haya seleccionado la imagen ingresada
         */
        Validar(n_imagen)
        {
            if (this.listImagenes_changed[n_imagen])
                return true; // No se cambió
            // Seleccionar imagen
            let imagen = "#" + "img" + n_imagen;
            let imgprop = $(imagen).prop('files')
            if (imgprop.length == 0) return false; //No seleccioanda
            if (imgprop[0] == null) return false; //No seleccioanda
            return true; // Ok

        },
        /**
         * Activa el cambio de la imagen seleccioanda
         */
        CambiarImagen(imagen)
        {
            let aux = [...this.listImagenes_changed];
            aux[imagen] = false;
            this.listImagenes_changed = aux;
        },
        Regresar()
        {
            this.$emit("regresar");
        },
        // Abre la imagen en una nueva ventana
        VerImagen(nombre)
        {
            window.open(nombre, '_blank');
        }

    },
    mounted()
    {
        this.CargarImagenes();
    }
}
</script>
