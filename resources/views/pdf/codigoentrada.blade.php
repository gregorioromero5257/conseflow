<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Etiquetas</title>
  <style>
   @page {
                margin: 40px 15px 15px 15px;
            }
   table{
    width: 100%;
/*   border :1px solid;*/
   }
   td{

    width: 50%;
/* border: 2px solid;
    height:18%; */
   }
   .sticker tr{
      /* word-wrap: break-word;
      position: absolute;
      width: 100%; */
      }
  .eimg {
   /* border : 2px solid;*/
    position: absolute;
    left: 50px;
    top: 18px;
    width:  60%;
}
.eimg2 {
    position: absolute;
    left: 450px;
    top: 18px;
    width: 60%;
}
.eimg3 {
    position: absolute;
    left: 50px;
    top: 215px;
    width: 60%;
}
.eimg4 {
    position: absolute;
    left: 450px;
    top: 215px;
    width: 60%;
}
.eimg5 {
    position: absolute;
    left: 50px;
    top: 405px;
    width: 60%;
}
.eimg6 {
    position: absolute;
    left: 450px;
    top: 405px;
    width: 60%;
}
.eimg7 {
    position: absolute;
    left: 50px;
    top: 605px;
    width: 60%;
}
.eimg8 {
    position: absolute;
    left: 450px;
    top: 605px;
    width: 60%;
}
.eimg9 {
    position: absolute;
    left: 50px;
    top: 805px;
    width: 60%;
}
.eimg10 {
    position: absolute;
    left: 450px;
    top: 805px;
    width: 60%;
}
  </style>
  <body>
  <div>
    @if($tamanio_d == 2)
    <table class="sticker">
    <tr>
    <td>
    <img src="data:image/png;base64,'.{{$array_codigos[0]}}.'" class="eimg" />
    <p style="position: absolute;
      top: 90px; font-size: 8px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: justify;">  {{$array_descripciones[0]}}
    </p>
    </td>
    <td>
    <p class="eimg2" width="200px" heigth="400px">&nbsp;</p>
    <p style="position: absolute;
      top: 90px; font-size: 8px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: justify;">  &nbsp;
    </p>
    </td>
    </tr>
    </table>
    @else
  <table class="sticker">
  <tr>
  <td>
  <img src="data:image/png;base64,'.{{$array_codigos[0]}}.'" class="eimg" />
  <p style="position: absolute;
    top: 90px; font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;">  {{$array_descripciones[0]}}
  </p>
  </td>
  <td>
  <img src="data:image/png;base64,'.{{$array_codigos[1]}}.'" class="eimg2" width="200px" heigth="400px"/>
  <p style="position: absolute;
    top: 90px; font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;">  {{$array_descripciones[1]}}
  </p>
  </td>
  </tr>
  <tr>
  <td>
  <img src="data:image/png;base64,'.{{$array_codigos[2]}}.'" class="eimg3"/>
  <p style="position: absolute;
    top: 270px; font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;">  {{$array_descripciones[2]}}
  </p>
  </td>
  <td>
  <img src="data:image/png;base64,'.{{$array_codigos[3]}}.'" class="eimg4"/>
  <p style="position: absolute;
    top: 270px; font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;">  {{$array_descripciones[3]}}
  </p>
  </td>
  </tr>
  <tr>
  <td>
  <img src="data:image/png;base64,'.{{$array_codigos[4]}}.'" class="eimg5"/>
  <p style="position: absolute;
    top: 460px; font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;">  {{$array_descripciones[4]}}
  </p>
  </td>
  <td>
  <img src="data:image/png;base64,'.{{$array_codigos[5]}}.'" class="eimg6"/>
  <p style="position: absolute;
    top: 460px; font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;">  {{$array_descripciones[5]}}
  </p>
  </td>
  </tr>
  <tr>
  <td>
  <img src="data:image/png;base64,'.{{$array_codigos[6]}}.'" class="eimg7"/>
  <p style="position: absolute;
    top: 650px; font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;">  {{$array_descripciones[6]}}
  </p>
  </td>
  <td>
  <img src="data:image/png;base64,'.{{$array_codigos[7]}}.'" class="eimg8"/>
  <p style="position: absolute;
    top: 650px; font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;">  {{$array_descripciones[7]}}
  </p>
  </td>
  </tr>
  <tr>
  <td>
  <img src="data:image/png;base64,'.{{$array_codigos[8]}}.'" class="eimg9"/>
  <p style="position: absolute;
    top: 850px; font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;">  {{$array_descripciones[8]}}
  </p>
  </td>
  <td>
  <img src="data:image/png;base64,'.{{$array_codigos[9]}}.'" class="eimg10"/>
  <p style="position: absolute;
    top: 850px; font-size: 8px;
    font-family: Arial, Helvetica, sans-serif;
    text-align: justify;">  {{$array_descripciones[9]}}
  </p>
  </td>
  </tr>
  </table>
  @endif
  </div>



  </body>
</html>
