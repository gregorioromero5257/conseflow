<template>
    <div class="card border-dark">
        <div class="card-header bg-dark text-white">
            <i class="fa fa-align-justify"> </i> Articulos con stock bajo
        </div>
        <!-- <div class="card-body"> -->
            <vue-element-loading :active="isLoading"/>
            <table class="VueTables__table table table-hover table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Mínimo</th>
                        <th scope="col">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in registros" :value="item.id" :key="item.id">
                        <td>{{ item.codigo }}</td>
                        <td>{{ item.nombre }}</td>
                        <td>{{ item.descripcion }}</td>
                        <td class="text-right">{{ item.minimo }}</td>
                        <td class="text-right">{{ item.existencia }}</td>
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
            registros: [],
        }
    },
    methods: {
        getData() {
            this.isLoading = true;
            let me=this;
            axios.get('/articulo/minimos').then(response => {
                me.registros = response.data.registros;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cargarMinimos() {
            this.getData();
        }
    },
    mounted() {
        // this.getData();
    }
}
</script>
