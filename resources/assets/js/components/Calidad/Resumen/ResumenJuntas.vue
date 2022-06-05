<template>
<div>
    <vue-element-loading :active="isJuntasLoading" />
    <div>
        <div v-if="list_juntas.length==0" class="container">
            <h5 class="mt-1">Sin Juntas</h5>
        </div>

        <div v-else>
            <div class="row">
                <div class="col-12" v-for="sistema in list_juntas">
                    <div class="card">
                        <div class="card-header bg-dark py-0">
                            <p class="mt-2 text-white">{{sistema.sistema.nombre}}</p>
                        </div>
                        <div class="card-body">
                            <h5 class="">Juntas</h5>
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th width="3%">#</th>
                                    <th width="15%">Spool</th>
                                    <th width="10%">No.</th>
                                    <th width="8%">Diámetro</th>
                                    <th width="7%">Espesor</th>
                                    <th width="20%"> Material 1</th>
                                    <th width="20%">Material 2</th>
                                    <th width="10%">Soldador</th>
                                    <th width="10%">Estado</th>
                                </tr>
                                <tr v-for="(junta,i) in sistema.juntas">
                                    <td>{{i+1}}</td>
                                    <td>{{junta.spool_no}}</td>
                                    <td>{{junta.nombre}}</td>
                                    <td>{{junta.diametro}}</td>
                                    <td>{{junta.espesor}}</td>
                                    <td>
                                        {{junta.nombre_material1}} - {{junta.colada_uno}}
                                    </td>
                                    <td>
                                        {{junta.nombre_material2}} - {{junta.colada_dos}}
                                    </td>
                                    <td>
                                        {{junta.clave_soldador}}
                                    </td>
                                    <td>
                                        <template>
                                            <button v-if="junta.estado==0" class="btn btn-secondary">Nuevo</button>
                                            <button v-if="junta.estado==1" class="btn btn-primary">Habilitada</button>
                                            <button v-if="junta.estado==2" class="btn btn-warning">Soldada</button>
                                            <button v-if="junta.estado==3" class="btn btn-success">Inspeccionada</button>
                                        </template>
                                    </td>
                                </tr>
                            </table>
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
            isJuntasLoading: false,
            list_juntas: [],
        }

    },
    methods:
    {
        ObtenerSoldadores()
        {
            this.isJuntasLoading = true;
            axios.get("/calidad/resumen/spools/" + this.proyecto_id).then(res =>
            {
                this.isJuntasLoading = false;
                if (res.data.status)
                {
                    this.list_juntas = res.data.juntas;
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
