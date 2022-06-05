<template>
<div>
    <div class="contanier">
        <div class="row">
            <!-- Grafica -->
            <div class="col-md-6">
                <chart-total id="char-dona" :chart-data="chartData" :options="chartOptions"></chart-total>

                <div class="container">
                    <div class="row">
                        <div class="col">
                            <br>
                            <br>
                            <p class="h5 font-weight-bold">AVANCE</p>
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th class="tbl-header-vert">Nuevas</th>
                                    <td>{{juntas.nuevas}}</td>
                                    <th>{{CalcularAvance(juntas.nuevas)}}</th>
                                </tr>
                                <tr>
                                    <th class="tbl-header-vert">Habilitadas</th>
                                    <td>{{juntas.habilitadas}}</td>
                                    <th>{{CalcularAvance(juntas.habilitadas)}}</th>
                                </tr>
                                <tr>
                                    <th class="tbl-header-vert">Soldadas</th>
                                    <td>{{juntas.soldadas}}</td>
                                    <th>{{CalcularAvance(juntas.soldadas)}}</th>
                                </tr>
                                <tr>
                                    <th class="tbl-header-vert">Inspeccionadas</th>
                                    <td>{{juntas.inspeccionadas}}</td>
                                    <th>{{CalcularAvance(juntas.inspeccionadas)}}</th>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>{{juntas.total}}</td>

                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Juntas -->
            <div class="col-md-6">
                <div class="container">
                    <div class="row">
                        <p class="h5 font-weight-bold">JUNTAS {{estado_juntas}}</p>
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th class="tbl-header-vert">#</th>
                                <th class="tbl-header-vert">Spool</th>
                                <th class="tbl-header-vert">No</th>
                                <th class="tbl-header-vert">Diametro</th>
                                <th class="tbl-header-vert">Fecha</th>

                            </tr>
                            <vue-element-loading :active="isJuntasLoading" />
                            <tr v-for="(junta,i) in list_juntas">
                                <td class="tbl-center">{{i+1}}</td>
                                <td class="tbl-center">{{junta.spool_no}}</td>
                                <td class="tbl-center">{{junta.nombre}}</td>
                                <td class="tbl-center">{{junta.diametro}}</td>
                                <td v-if="junta.estado==0" class="tbl-center"> — </td> <!-- Nuevo -->
                                <td v-if="junta.estado==1" class="tbl-center"> {{junta.fecha_habilitado}} </td> <!-- Habilitada -->
                                <td v-if="junta.estado==2" class="tbl-center"> {{junta.fecha_soldado}} </td> <!-- Soldada -->
                                <td v-if="junta.estado==3" class="tbl-center"> {{junta.fecha_inspeccion}} </td> <!-- Inspeccionada -->
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<style>
.tbl-header-vert {
    background-color: rgba(0, 0, 0, .1);
    color: black;
}

.tbl-center {
    text-align: center;
}
</style>

<script>
import InspeccionVue from '../Reporte/Inspeccion.vue';
import chartTotal from './chartDoughnut.vue';

export default
{
    props: ["proyecto_id"],
    data()
    {
        return {
            url: "/calidad/resumen",
            juntas:
            {},
            estado_juntas: "",
            list_juntas: [],
            chartData:
            {
                labels: [],
                datasets: [],
            },
            chartOptions:
            {
                responsive: true,
                maintainAspectRatio: false,
                onClick: this.VerJuntas,
                plugins:
                {
                    title:
                    {
                        display: true,
                        text: 'Custom Chart Title',
                        padding:
                        {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            },
            isJuntasLoading: false,

        }
    },
    components:
    {
        chartTotal,
    },
    methods:
    {

        //Obtiene el total de las juntas de cada uno de los estados
        ObtenrJuntas()
        {
            axios.get(this.url + "/avancesoldadura/" + this.proyecto_id).then(res =>
            {
                if (res.data.status)
                {
                    let totales = res.data.totales.total;
                    let nuevas = res.data.totales.nuevas;
                    let habilitadas = res.data.totales.habilitadas;
                    let soldadas = res.data.totales.soldadas;
                    let inspeccionadas = res.data.totales.inspeccionadas;
                    this.juntas = {
                        "nuevas": nuevas,
                        "habilitadas": habilitadas,
                        "soldadas": soldadas,
                        "inspeccionadas": inspeccionadas,
                        "total": totales
                    };
                    this.chartData = {
                        labels: ['Nuevas', 'Habilitadas', 'Soldadas', 'Inspeccionadas'],
                        datasets: [
                        {
                            backgroundColor: [
                                '#4472c5',
                                '#ed7c30',
                                '#ffb83b',
                                '#93d150'
                            ],
                            data: [nuevas,habilitadas, soldadas, inspeccionadas, ]
                        }]
                    };
                }
            }).catch(x =>
            {
                toastr.error("Error al obtener los datos");
                console.error(x);
            })
        },

        // Calcula el porcentaje de avance de avance de cada estado
        CalcularAvance(n)
        {
            let p = ((n / this.juntas.total) * 100).toFixed(2);
            return p + "%";
        },
        // Muestra las juntas del estado seleccionado
        VerJuntas(point, event)
        {
            const item = event[0]
            // Obtener index
            if (item != null)
            {
                let i = item._index;
                let data = this.proyecto_id + "&" + i; // estado y proyecto_id
                this.isJuntasLoading = true;
                this.estado_juntas = i == 0 ? "NUEVAS" : i == 1 ? "HABILITADAS" : i == 2 ? "SOLDADAS" : "INSPECCIONADAS";
                axios.get(this.url + "/juntasporestado/" + data).then(res =>
                {
                    this.isJuntasLoading = false;
                    if (res.data.status)
                    {
                        this.list_juntas = res.data.juntas;
                        console.error(this.list_juntas);
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                })
            }

        },
    },
    mounted()
    {
        this.ObtenrJuntas();
    }
}
</script>
