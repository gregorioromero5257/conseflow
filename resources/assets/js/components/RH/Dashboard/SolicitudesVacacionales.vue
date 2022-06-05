<template>
            <div class="card border-secondary">
                <div class="card-header bg-dark text-white">
                    <i class="fa fa-align-justify"> </i> Solicitudes de vacaciones
                    <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right" v-show="detalle">
                        <i class="icon-arrow-left"></i>&nbsp;Atras
                    </button>
                </div>
                <!-- <div class="card-body"> -->
                    <vue-element-loading :active="isLoading"/>
                    <table class="VueTables__table table table-hover table-sm" v-show="!detalle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Autorizar</th>
                                <th scope="col">Solicita</th>
                                <th scope="col">Fecha solicitud</th>
                                <th scope="col">Total dias</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in solicitudes" :value="item.id" :key="item.id">
                                <td>
                                    <button class="btn btn-sm btn-success" @click="autorizar(1, item.id)"><i class="fas fa-check"></i> Si</button>
                                    <button class="btn btn-sm btn-danger" @click="autorizar(0, item.id)"><i class="fas fa-close"></i> No</button>
                                </td>
                                <td><a @click.prevent="mostrarSolicitud(item)" href="#">{{ item.e_solicita }}</a></td>
                                <td>{{ item.fecha_solicitud }}</td>
                                <td class="text-right">{{ item.total_dias }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="VueTables__table table table-hover table-sm" v-show="detalle">
                        <tbody>
                        <tr>
                            <td>Fecha de solicitud:</td>
                            <td>{{ solicitud.fecha_solicitud }}</td>
                        </tr>
                        <tr>
                            <td>Formato de vacación:</td>
                            <td>{{ solicitud.formato_vacacion }}</td>
                        </tr>
                        <tr>
                            <td>Solicita:</td>
                            <td>{{ solicitud.e_solicita }}</td>
                        </tr>
                        <tr>
                            <td>Autoriza:</td>
                            <td>{{ solicitud.e_autoriza }}</td>
                        </tr>
                        <tr>
                            <td>Estado:</td>
                            <td>
                                <template v-if="solicitud.estado == 1">
                                        <span class="badge badge-success">Autorizado</span>
                                </template>
                                <template v-if="solicitud.estado == 0">
                                                <span class="badge badge-info">En Espera</span>
                                </template>
                                <template v-if="solicitud.estado == 2">
                                                <span class="badge badge-danger">No Autorizado</span>
                                </template>
                            </td>
                        </tr>
                        <tr>
                            <td>Fecha inicio:</td>
                            <td>{{ solicitud.fecha_inicio }}</td>
                        </tr>
                        <tr>
                            <td>Fecha fin:</td>
                            <td>{{ solicitud.fecha_fin }}</td>
                        </tr>
                        <tr>
                            <td>Total de dias:</td>
                            <td>{{ solicitud.total_dias }}</td>
                        </tr>
                        </tbody>
                    </table>
                <!-- </div> -->
            </div>
</template>
<script>
export default {
    data() {
        return {
            isLoading: false,
            solicitudes: [],
            detalle: false,
            solicitud: []
        }
    },
    methods: {
        getData() {
            this.isLoading = true;
            let me=this;
            axios.get('/porautorizar/vacaciones').then(response => {
                me.solicitudes = response.data.solicitudes;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        autorizar(autorizado, id) {
            this.isLoading = true;
            let me=this;
            axios({
                method: 'POST',
                url: (autorizado == 1) ? '/autorizado/vacacion' : '/noautorizado/vacacion',
                data: {
                    'id': id
                }
            }).then(function (response) {
                me.isLoading = false;
                if (autorizado == 1)
                    toastr.success('Solicitud de vacciones autorizada.');
                else
                    toastr.info('Solicitud de vacaciones no autorizada');
                me.getData();
            }).catch(function (error) {
                console.log(error);
            });
        },
        maestro() {
            this.detalle = false;
        },
        mostrarSolicitud(data) {
            this.detalle = true;
            this.solicitud = data;
        },
        cargarSolicitudes() {
            this.getData();
        }
    },
    mounted() {
        // this.getData();
    }
}
</script>
