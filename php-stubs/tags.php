<?php

	require("../inc/conex.inc");


//sleep(3);

/* funcion auxiliar para emular filtro sobre los tags*/
function filter($str, $tags)
{
    $results = array();
    
    foreach($tags as $t)
    {
        if (stristr($t['value'], $str) !== false) $results[] = $t;
    }  
    
    return $results;
}

/*
 * Este script retorna utilizando JSON, los tags que ya fueron cargados.
 * 
 * si por ejemplo el resultado de la consulta en base de datos se almacena en el array $tags,
 * 
 * se debería imprimir algo por el estilo: 
 * 
 * echo json_encode($tags); 
 * 
 */

$tags = array();

$ssqlp = "select * from tags";
$result = mysql_query($ssqlp);	
while($row = mysql_fetch_array($result)){
	array_push($tags, 
	    array(
	        'id' => $row["id"],
	        'label' => $row["tag"],
	        'value' => $row["tag"]
	    )
	);
};




//print($_GET['term']);
if (isset($_GET['term']) && $_GET['term']) $tags = filter($_GET['term'], $tags);

echo json_encode($tags);

//echo '[ { "id": "ejem1", "label": "Ejemplo 1", "value": "Ejemplo 1" }, { "id": "ejem2", "label": "Ejemplo 2", "value": "Ejemplo 2" }]';

?>
