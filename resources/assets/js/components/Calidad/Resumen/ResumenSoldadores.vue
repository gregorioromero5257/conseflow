<template>
<div>
    <vue-element-loading :active="isSoldadoresLoading" />
    <div>
        <div v-if="list_soldadores.length==0" class="container">
            <h5 class="mt-1">Sin soldadores</h5>
        </div>

        <div v-else>
            <div class="row">
                <div class="col-md-6" v-for="soldador in list_soldadores">
                    <div class="card">
                        <div class="card-header bg-dark py-0">
                            <p class="mt-2 text-white">{{soldador.sold_nombre}}</p>
                        </div>
                        <div class="card-body">
                            <h5 class="mb-2">Soldador</h5>
                            <!-- Nombre -->
                            <div class="form-group row mx-0 my-0">
                                <label class="col-md-3 col-form-label font-weight-bold">Nombre</label>
                                <div class="col-sm-9">
                                    <input style="width:100%" type="text" readonly class="form-control-plaintext" :value="soldador.sold_nombre">
                                </div>
                            </div>
                            <!-- Clave -->
                            <div class="form-group row mx-0 my-0">
                                <label class="col-md-3 col-form-label font-weight-bold">Clave</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly class="form-control-plaintext" :value="soldador.clave">
                                </div>
                            </div>
                            <!-- Procedimiento -->
                            <div class="form-group row mx-0 my-0">
                                <label class="col-md-3 col-form-label font-weight-bold">
                                    Proceso
                                </label>
                                <div class="col-sm-3">
                                    <input type="text" readonly class="form-control-plaintext" :value="soldador.proced_clave">
                                </div>
                            </div>
                            <br>
                            <h5 class="mb-2">Máquina de Soldar</h5>
                            <!-- No Control -->
                            <div class="form-group row mx-0 my-0">
                                <label class="col-md-3 col-form-label font-weight-bold">No. Control</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly class="form-control-plaintext" :value="soldador.no_control">
                                </div>
                            </div>
                            <!-- Procedimiento -->
                            <div class="form-group row mx-0 my-0">
                                <label class="col-md-3 col-form-label font-weight-bold">
                                    Marca
                                </label>
                                <div class="col-sm-3">
                                    <input type="text" readonly class="form-control-plaintext" :value="soldador.marca">
                                </div>
                            </div>
                            <!-- FEcha -->
                            <div class="form-group row mx-0 my-0">
                                <label class="col-md-3 col-form-label font-weight-bold">
                                    Calibración
                                </label>
                                <div class="col-sm-3">
                                    <input type="text" readonly class="form-control-plaintext" :value="soldador.maq_fecha_cert">
                                </div>
                            </div>

                            <!-- Certificado -->
                            <div class="form-group row mx-0 my-0">
                                <label class="col-md-3 col-form-label font-weight-bold">
                                    Certificado
                                </label>
                                <div class="col-sm-6">
                                    <template v-if="soldador.maq_cert!=null">
                                        <input style="width:100%" type="text" readonly class="form-control-plaintext" :value="soldador.maq_cert.substr(18,100)">
                                    </template>
                                    <template v-else>
                                        <label>N/D</label>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- row -->
        </div>
    </div>
</div>
</template>

<script>
export default
{
    props: ["proyecto_id"],
    data()
    {
        return {
            isSoldadoresLoading: false,
            list_soldadores: [],
        }

    },
    methods:
    {
        ObtenerSoldadores()
        {
            this.isSoldadoresLoading = true;
            axios.get("/calidad/resumen/soldadores/" + this.proyecto_id).then(res =>
            {
                this.isSoldadoresLoading = false;
                if (res.data.status)
                {
                    this.list_soldadores = res.data.soldadores;
                }
                else
                {
                    tostr.eror(res.data.mensaje);
                }
            })
        }
    },
    mounted()
    {
        this.ObtenerSoldadores();
    }
}
</script>
