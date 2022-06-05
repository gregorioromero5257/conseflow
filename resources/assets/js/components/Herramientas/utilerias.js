export default {
    scrollMeTo(tv, refName) {
        var element = tv.$refs[refName];
        var top = element.offsetTop;
        window.scrollTo(0, top);
    },
    resetObject(obj) {
        for(let key in obj) {
            if (typeof obj[key] === 'string')
                obj[key] = '';
            else if (typeof obj[key] === 'number')
                obj[key] = 0;
            else if(typeof obj[key] === undefined)
                obj[key] = null;
            else if(typeof obj[key] === 'object')
                obj[key] = {};
        }
    },
    colorPalette(tipo) {
        switch(tipo) {
            case 0:
                // Hichcharts
                return ["#434348","#7cb5ec",  "#90ed7d", "#f7a35c", "#8085e9", "#f15c80", "#e4d354", "#2b908f", "#f45b5b", "#91e8e1"];

            case 1:
                // Colors Belize (light flavor) SAP
                return ['#597fe2','#e29159','#59e27c','#e2597c', '#e2dc59','#9159e2','#525DF4','#BF399E','#6C8893','#EE6868','#2F6497'];

            case 2:
                // Highcharts 3.x
                return ['#2f7ed8', '#0d233a', '#8bbc21', '#910000', '#1aadce','#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a'];

            case 3:
                // Palette 2
                return ['#003f5c','#2f4b7c','#665191','#a05195','#d45087','#f95d6a','#ff7c43','#ffa600'];

            case 4:
                // Sequencial Palette
                return ['#004c6d','#086081','#147596','#208ba9','#2ea1bc','#3eb8ce','#50cfdf','#63e7f0','#79ffff'];
        }
    },


    getRandomColor() {
        // var letters = '0123456789ABCDEF';
        // var color = '#';

        // for (var i = 0; i < 6; i++) {
        //   color += letters[Math.floor(Math.random() * 16)];
        // }


        // return color;
        var o = Math.round, r = Math.random, s = 255;
        return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + r().toFixed(1) + ')';

      },



      setRandomColor() {
        var array_colors = [];
        for (let index = 1; index < 60; index++) {
            array_colors.push(this.getRandomColor());

        }
        return array_colors;
      },


      random_rgba() {

    },




    getCRUD(ruta){
        let formData = new FormData();
        formData.append('ruta',ruta);
        formData.append('identificador',1);
        var permisos =
        {
            Create:false,
            Read:false,
            Update:false,
            Delete:false,
            Download:false,
            Upload:false,
        };
        axios.post('/CRUD',formData).then(response => {
            for (var i = 0; i < response.data.length; i++) {
                if (response.data[i].control_id == 1) {
                    permisos.Create =true
                }
                if (response.data[i].control_id == 2) {
                    permisos.Read =true
                }
                if (response.data[i].control_id == 3) {
                    permisos.Update =true
                }
                if (response.data[i].control_id == 4) {
                    permisos.Delete =true
                }
                if (response.data[i].control_id == 5) {
                    permisos.Download =true
                }
                if (response.data[i].control_id == 6) {
                    permisos.Upload =true
                }

            }
        })
        .catch(function (error) {
            console.log(error);
        });
        return permisos;
    },
    descargarArc(ruta_uno, ruta_dos, archivo){
      let me=this;
      axios({
          url: ruta_uno + archivo ,
          method: 'GET',
          responseType: 'blob', // importante
      }).then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
          document.body.appendChild(link);
          link.click();
          //--Llama el metodo para borrar el archivo local una ves descargado--//
          axios.delete( ruta_dos + archivo)
          .then(response => {
          })
          .catch(function (error) {
                  console.log(error);
          });
          //--fin del metodo borrar--//
      });
    }
}
