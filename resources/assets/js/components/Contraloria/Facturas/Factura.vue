<template >
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Registro de XML

        </div>
        <div class="card-body">
          
          <v-client-table :data="tableData" :options="options" :columns="columns">
          </v-client-table>
        </div>
      </div>

    </div>
  </main>
</template>
<script>

export default {
  data(){
    return{
      tableData : [],
      columns : ['id'],
      options : {
        headings: {
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  methods: {
    agregar(){
      this.modal = 0;
    }

  },
  mounted(){

  }
}

</script>
