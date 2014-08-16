<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
function get_ordrelistprog()
{
global $internaute;
$letimestamp=get_refresh();
$datesql=$letimestamp->datesql;
$datedown=$letimestamp->datedown;
	$query = " SELECT cacval.nom as Nom,FROM_UNIXTIME(datecreation,'%d/%c/%Y %H:%I:%S' ) as 'Date de cr�ation',sens,nbr as 'quantit�e',pourc as 'pourcentage',coursmin as 'Cours mini',coursmax as 'Cours max',valeur 
FROM ordre
INNER JOIN cacval ON ordre.codesico = cacval.codesico
WHERE idcompte=$internaute->idcompte ORDER BY datecreation DESC";
	//echo $query;
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
 return $run_query;
}

function sorttableauprog($resultat)
{
$qte=mysql_num_fields($resultat);/*nombre de champs s�lectionn�s*/
$echo="";
	for ($i=0;$i<$qte;$i++)
	{
		 if($i<>0 && $i<$qte)
		 {
		 $echo.=";";
		 }
		 $echo.=mysql_field_name($resultat,$i);/*les noms des champs*/
	}
	while ($row =   mysql_fetch_array($resultat,MYSQL_ASSOC))
	{/*array des donn�es*/
		$echo.=chr(13);
		$i=0;
		foreach ($row as $elem)
		{/*pour chaque �l�ment...*/
			if($i<>0 && $i<$qte)
		 	{
		 	$echo.=";";
		 	}
			 $i++;
			 $echo.=ereg_replace(chr(13), "",ereg_replace(chr(10), "",stripslashes($elem)));
		}
		
	}
return $echo;
}


function form_list_ordreprog()
{
global $skinrep;
$liste=get_ordrelistprog();
if($liste<>"")
{
$retour=sorttableauprog($liste);
}else{
$retour="0";
}
return $retour;
}


?>