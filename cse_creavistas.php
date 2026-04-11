<!-- Captura de compras -->
<HTML>
<head>
<title>SisBol</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />

<?php
//Aqui cargo las funciones 
include("funciones.php");
$link=conectarbd();

?>
</head>
<body>
<form name='form1' method='post' action='cse_ccompra11.php'>
    <h4>Generando Vistas</h4>
    <?php
    $error=0;
    $cont=1;

    // Vista vw_barrio
    $sql = "CREATE OR REPLACE VIEW vw_barrio AS
    SELECT id_barrio, CONCAT(nombre_bar,' ',comuna_bar) AS descripcion 
    FROM barrio";
    $res = mysqli_query($link, $sql);
    if (!$res) {
    echo "<div class='col-sm-12'>vw_barrio NO CREADA: " . mysqli_error($link) . "</div>";
    $error++;
    }
    incrementar($cont);
    $cont++;

    // Vista vw_boleta
    $sql = "CREATE OR REPLACE VIEW vw_boleta AS
    SELECT boleta.codi_bol, boleta.id_camp, detalle_bol.nume_dbo, cliente.tpid_cli, 
        cliente.nrod_cli, cliente.exped_cli, 
        CONCAT(cliente.nomb_cli,' ',cliente.apel_cli) AS nombre,
        cliente.tele_cli, CONCAT(cliente.dire_cli,' ',barrio.nombre_bar) AS direccion,
        cliente.emai_cli, tipo.desc_tip AS tipo_ident, campania.nombre_camp,
        campania.mecanica_camp, campania.fechafin_camp, boleta.anul_bol
    FROM boleta
    INNER JOIN detalle_bol ON detalle_bol.codi_bol = boleta.codi_bol
    INNER JOIN cliente     ON cliente.codi_cli      = boleta.codi_cli
    INNER JOIN campania    ON campania.id_camp       = boleta.id_camp
    INNER JOIN tipo        ON tipo.codi_tip          = cliente.tpid_cli
    INNER JOIN barrio      ON barrio.id_barrio       = cliente.id_barrio";
    $res = mysqli_query($link, $sql);
    if (!$res) {
    echo "<div class='col-sm-12'>vw_boleta NO CREADA: " . mysqli_error($link) . "</div>";
    $error++;
    }
    incrementar($cont);
    $cont++;

    // Vista vw_compra
    $sql = "CREATE OR REPLACE VIEW vw_compra AS
    SELECT cliente.codi_cli, cliente.nrod_cli, 
        CONCAT(cliente.nomb_cli,' ',cliente.apel_cli) AS nombre,
        cliente.dire_cli, cliente.tele_cli, cliente.sexo_cli, cliente.fnac_cli,
        compra.tdoc_com, tp.valo_tip AS tipo_doc_comp, compra.ndoc_com,
        compra.fech_com, compra.valo_com, compra.loca_com, loc.desc_tip AS local,
        barrio.nombre_bar, boleta.id_camp, boleta.anul_bol
    FROM compra
    INNER JOIN boleta  ON boleta.codi_bol   = compra.codi_bol
    INNER JOIN cliente ON cliente.codi_cli   = boleta.codi_cli
    INNER JOIN barrio  ON barrio.id_barrio   = cliente.id_barrio
    INNER JOIN tipo AS tp  ON tp.codi_tip    = compra.tdoc_com
    INNER JOIN tipo AS loc ON loc.codi_tip   = compra.loca_com";
    $res = mysqli_query($link, $sql);
    if (!$res) {
    echo "<div class='col-sm-12'>vw_compra NO CREADA: " . mysqli_error($link) . "</div>";
    $error++;
    }
    incrementar($cont);
    $cont++;

    // Vista vw_categoria
    $sql = "CREATE OR REPLACE VIEW vw_categoria AS
    SELECT tipo.codi_tip, tipo.desc_tip 
    FROM tipo 
    WHERE tipo.codi_gru = '05'";
    $res = mysqli_query($link, $sql);
    if (!$res) {
    echo "<div class='col-sm-12'>vw_categoria NO CREADA: " . mysqli_error($link) . "</div>";
    $error++;
    }
    incrementar($cont);
    $cont++;
    

    if($error<>0){
        ?>
        <b>Atención:</b>
        <br>Comunique los anteriores errores al personal de soporte técnico de MEDINET
        <?php
    }

    //actbarrios();
    ?>                            
    <br>Proceso finalizado
</form>

</body>
</HTML>


    


<?php
function incrementar($c_){
    $totvistas=14;
    $por_vistas=($c_*100)/$totvistas;
    if($por_vistas>98){
        $por_vistas=100;
    }    
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            incrementabarra(<?php echo $por_vistas;?>);
        });
    </script>
    <?php
}

function actbarrios(){
    $link=conectarbd();
    $consbarrio = "SELECT id_barrio, nombre_bar FROM barrio";
    $consbarrio = mysqli_query($link, $consbarrio);

    while ($row = mysqli_fetch_array($consbarrio)) {
        $nombre_bar = strtoupper($row['nombre_bar']);
        echo "<br>" . $nombre_bar;

        $concli = "SELECT * FROM cliente WHERE dire_cli LIKE '%$nombre_bar%'";
        $concli = mysqli_query($link, $concli);
        //echo "<br>Coincidencias: " . mysqli_num_rows($concli);

        $id_barrio = mysqli_real_escape_string($link, $row['id_barrio']);
        $sql = "UPDATE cliente SET 
                dire_cli  = REPLACE(dire_cli, '$nombre_bar', ''),
                id_barrio = '$id_barrio'
                WHERE dire_cli LIKE '%$nombre_bar%'";
        //echo "<br>sql: " . $sql;
        mysqli_query($link, $sql);
        //echo "<br>Reemplazos: " . mysqli_affected_rows($link);
    }
}

?>
<script type="text/javascript">
    function incrementabarra(valor){
        valor=valor+"%";        
        $(".progress-bar").animate({
        width: valor
        },1);        
    }

</script>