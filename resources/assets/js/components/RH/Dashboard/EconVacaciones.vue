<template>
            <div class="card border-secondary">
                <div class="card-header bg-dark text-white">
                    <i class="fa fa-align-justify"> </i> Empleados con vacaciones :
                </div>
                <!-- <div class="card-body"> -->
                    <vue-element-loading :active="isLoading"/>
                    <table class="VueTables__table table table-hover table-sm" v-show="!detalle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Empleado</th>
                                <th scope="col">Dias ganados</th>
                                <th scope="col">Dias tomados</th>
                                <th scope="col">Dias restantes</th>
                                <th scope="col">Antiguedad / Fecha de ingreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in solicitudes" :value="item.empleado.id" :key="item.empleado.id">
                                <td>{{item.empleado.nombre}} {{item.empleado.ap_paterno}} {{item.empleado.ap_materno}}</td>
                                <td>{{item.diasganados}}</td>
                                <td>{{item.diastomados}}</td>
                                <td>{{item.diasrestantes}}</td>
                                <td>{{item.antiguedad}} / {{item.fecha_inicial}}</td>
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
            axios.get('/vacacionesemp').then(response => {
                 for (var i = 0; i < response.data.length; i++) {
                  if (response.data[i].diasganados > 0 && (response.data[i].diastomados < response.data[i].diasganados) ) {
                    me.solicitudes.push(response.data[i]);
                  }
                 }
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cargarContratos() {
            this.getData();
        }
    },
    mounted() {
    }
}
</script>
