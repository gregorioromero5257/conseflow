<template>
    <main>
        <vue-element-loading :active="isLoading"/>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Almacén</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in movimientos" :value="item.id" :key="item.id">
                    <td>{{ item.tipo_movimiento }}</td>
                    <td>{{ item.folio }}</td>
                    <td>{{ item.fecha }} <br> {{ item.hora }}</td>
                    <td>{{ item.almacen }}<br>{{ item.stock}}<br>{{ item.lote }}</td>
                    <td class="text-right">{{ item.cantidad }}</td>
                </tr>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <li class="page-item" :class="page == 1 ? 'disabled' : ''"><a class="page-link" @click.prevent="anterior()" href="#">Anterior</a></li>
                <li class="page-item" :class="page == total ? 'disabled' : ''"><a class="page-link" @click.prevent="siguiente()" href="#">Siguiente</a></li>
            </ul>
        </nav>
    </main>
</template>
<script>
export default {
    data() {
        return {
            isLoading: false,
            movimientos: [],
            page: 1,
            total: 0,
            articulo_id: 0,
        }
    },
    methods: {
        cargarMovimientos(data) {
            this.page = 1;
            this.articulo_id = data.id;
            this.cargarPagina();
        },
        cargarPagina() {
            let me = this;
            this.isLoading = true;
            axios.get('/movimientos/kardex/', {
                params: {
                    articulo_id: this.articulo_id,
                    page: this.page
                }
            }).then(response => {
                me.movimientos = response.data.movimientos.data;
                me.total = response.data.movimientos.last_page;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        
        anterior() {
            this.page = this.page == 1 ? 1 : this.page-1;
            this.cargarPagina();
        },
        siguiente() {
            this.page = this.page == this.total ? this.page : this.page+1;
            this.cargarPagina();
        }
    },
    mounted() {
    }
}
</script>
