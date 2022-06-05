<template>
<div>
    <vue-element-loading :active="isRimesLoading" />
    <div class="accordion" id="accordion">
        <div v-if="list_rimes.length==0" class="container">
            <h5 class="mt-1">Sin RIEPC</h5>
        </div>

        <div v-else>
            <div v-for="rime in list_rimes" class="card mb-2">
                <div class="card-header bg-dark">
                    <h2 class="mb-0">
                        <button style="text-decoration:none;" class="text-white btn btn-link btn-block text-left" type="button" data-toggle="collapse" :data-target="'#collapse_rime_'+rime.id" aria-expanded="true">
                            {{rime.folio}}
                            <a class="btn btn-dark float-sm-right" @click="DescargarRime(rime.id)">
                                <i class="fas fa-eye"></i>
                            </a>
                        </button>
                    </h2>
                </div>

                <div :id="'collapse_rime_'+rime.id" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th>#</th>
                                <th>Cantidad</th>
                                <th>Descripción</th>
                                <th>Certificado</th>
                            </tr>
                            <tr v-for="(partida,i) in rime.partidas">
                                <td width="3%">{{i+1}}</td>
                                <td width="7%">{{partida.cantidad}}</td>
                                <td width="60%" class="">
                                    {{partida.articulo}}
                                </td>
                                <td width="10%">
                                    {{partida.tag}}
                                </td>
                                <td>
                                    <p v-if="partida.no_certificado==='N/A'">N/D</p>
                                    <button @click="DescargarCertificado(partida.id)" v-else type="button" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-file-pdf"></i>
                                        {{partida.no_certificado}}
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
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
            isRimesLoading: false,
            list_rimes: [],
        }

    },
    methods:
    {
        ObtenerRimes()
        {
            this.isRimesLoading = true;
            axios.get("/calidad/resumen/riepc/" + this.proyecto_id).then(res =>
            {
                this.isRimesLoading = false;
                if (res.data.status)
                {
                    this.list_rimes = res.data.rimespc;
                }
                else
                {
                    tostr.eror(res.data.mensaje);
                }
            });
        },
        // Descarga el certificado de la rime ingresada
        DescargarCertificado(rime_id)
        {
            window.open("/calidad/inspeccionescliente/certificado/" + rime_id, "_blank");
        },
        // Descarga el formato de la rime seleccionada
        DescargarRime(rime_id)
        {
            window.open("/calidad/inspeccionescliente/descargar/" + rime_id, "_blank");
        }
    },
    mounted()
    {
        this.ObtenerRimes();
    }
}
</script>
