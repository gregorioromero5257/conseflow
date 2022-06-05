<template >

</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      isLoading : false,
      datos : null,
    }
  },
  methods : {
    getData(data){
      let me = this;
      me.isLoading = true;
      me.datos = data;
      me.isLoading = false;
      axios.get('/precioventa').then(response => {
        console.log(response.data);
      }).catch(error => {
        console.error(error);
      });

    }
  },
  mounted(){
    this.getData();
  }

}
</script>
