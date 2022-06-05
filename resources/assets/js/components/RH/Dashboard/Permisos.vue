<template>
    <div class="card border-secondary">
        <div class="card-header bg-dark text-white">
            <i class="fa fa-align-justify"> </i> Permisos
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
                        <th scope="col">Permiso</th>
                        <th scope="col">Estado goce de sueldo </th>
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
                        <td class="text-right">{{ item.tipo_salida == 1 ? 'Día completo' : 'Temporal' }}</td>
                     <td>
                            <button class="btn btn-info"   :hidden = "item.goce == 1 || item.goce == 0"  @click="GoceSueldo(1, item.id)"><i class="fas fa-check"></i> Si.</button>
                            <button class="btn btn-warning"  :hidden=  "item.goce == 1 || item.goce == 0"  @click="GoceSueldo(0, item.id)"><i class="fas fa-close"></i> No.</button>

                            <template v-if="item.goce!=2">
                              <button class = "btn btn-outline-dark">{{item.goce== 1 ? 'Con goce' : 'Sin goce'}}</button>
                            </template>



                    </td>

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
                    <td>Permiso:</td>
                    <td>{{ solicitud.tipo_salida == 1 ? 'Día completo' : 'Temporal' }}</td>
                </tr>
                <tr>
                    <td>Motivo:</td>
                    <td>{{ solicitud.motivo }}</td>
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
                        <template v-if="solicitud.condicion == 1">
                                <span class="badge badge-success">Autorizado</span>
                        </template>
                        <template v-if="solicitud.condicion == 0">
                                        <span class="badge badge-info">En Espera</span>
                        </template>
                        <template v-if="solicitud.condicion == 2">
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
                    <td>Hora salida:</td>
                    <td>{{ solicitud.hora_salida }}</td>
                </tr>
                <tr>
                    <td>Hora regreso:</td>
                    <td>{{ solicitud.hora_regreso }}</td>
                </tr>
                <tr>
                    <td>Goce de sueldo:</td>
                    <td>{{ solicitud.goce == 1 ? 'Si' : 'No' }}</td>
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
            solicitud: [],
          }
    },

    methods: {

        getData() {
            this.isLoading = true;
            let me=this;
            axios.get('/porautorizar/permisos').then(response => {
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
                url: (autorizado == 1) ? '/autorizado/permiso' : '/noautorizado/permiso',
                data: {
                    'id': id
                }
            }).then(function (response) {
                me.isLoading = false;
                if (autorizado == 1)
                    toastr.success('Solicitud de permiso autorizada.');
                else
                    toastr.info('Solicitud de permiso no autorizada');
                me.getData();
            }).catch(function (error) {
                console.log(error);
            });
        },

        GoceSueldo(goce, id) {

            this.isLoading = true;
            let me=this;

            axios({
                method: 'POST',
                url: (goce == 1) ? '/congoce/permiso' : '/singoce/permiso',
                data: {
                    'id': id
                }
            }).then(function (response) {
                me.isLoading = false;
                if (goce == 1)
                toastr.success('Con goce.');
                else
                toastr.info('Sín goce');
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
        cargarPermisos() {
            this.getData();
        }
    },
    mounted() {
        // this.getData();
    }
}
</script>
