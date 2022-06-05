<table>
    <tr>
        <td colspan="10" style="text-align : center;">CONSERFLOW S.A. DE C.V.</td>
    </tr>
    <tr>
        <td rowspan="3" colspan="8" style="text-align : center;">
            <b>CONTROL DE CALIBRACIÓN DE EQUIPOS</b>
        </td>
        <td style="text-align : center;">CÓDIGO</td>
        <td style="text-align : center;">PAL-02/F-01</td>
    </tr>
    <tr>
        <td style="text-align : center;">REVISIÓN</td>
        <td style="text-align : center;">00</td>
    </tr>
    <tr>
        <td style="text-align : center;">EMISIÓN</td>
        <td style="text-align : center;">01.ABR.20</td>
    </tr>
    <tr>
        <td></td>
    </tr>

    <tr>
        <th rowspan="2" style="background-color : #5E7EFF; text-align : center;"><b>Equipo a calibrar</b></th>
        <th rowspan="2" style="background-color : #5E7EFF; text-align : center;" width="30"><b>Marca</b></th>
        <th rowspan="2" style="background-color : #5E7EFF; text-align : center;" width="80"><b>Modelo</b></th>
        <th rowspan="2" style="background-color : #5E7EFF; text-align : center;"><b>Rango de Medición</b></th>
        <th rowspan="2" style="background-color : #5E7EFF; text-align : center;"><b>No. De serie</b></th>
        <th colspan="3" style="background-color : #a4a4a4; text-align : center;"><b>Calibración</b></th>
        <th rowspan="2" style="background-color : #5E7EFF; text-align : center;"><b>Resguardo</b></th>
        <th rowspan="2" style="background-color : #5E7EFF; text-align : center;"><b>Observaciones</b></th>
    </tr>
    <tr>
        <th style="background-color : #5E7EFF; text-align : center;"><b>Freecuencia</b></th>
        <th style="background-color : #5E7EFF; text-align : center;"><b>Fecha del Servicio</b></th>
        <th style="background-color : #5E7EFF; text-align : center;"><b>Próxima Fecha</b></th>
        <th></th>
        <th></th>
    </tr>

    <tbody>

        @foreach($aux as $equipo)
        <tr>
            <td>{{$equipo["descripcion"]}}</td>
            <td>{{$equipo["marca"]}}</td>
            <td>{{$equipo["modelo"]}}</td>
            <td>{{$equipo["rango_medicion"]}}</td>
            <td>{{$equipo["numero_serie"]}}</td>
            <td>{{$equipo["frecuencia"]}}</td>
            <td>{{$equipo["fecha_servicio"]}}</td>
            <td>{{$equipo["proxima_fecha"]}}</td>
            <td>{{$equipo["resguardo"]}}</td>
            <td>{{$equipo["observaciones"]}}</td>
        </tr>
        @endforeach
    </tbody>
</table>