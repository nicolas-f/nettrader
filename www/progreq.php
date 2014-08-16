<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
function progreqportef()
{
global $internaute;
$idcompte=$internaute->idcompte;
	$query = "SELECT lasttime AS laststamp,cacval.codesico AS codesicav,cacval.nom AS nomsicav,portef.quant AS nombsicav,cacval.valeur AS valsicav,portef.ansvaleur as ansvalsicav
          FROM cacval,portef
          WHERE cacval.codesico = portef.codesico
		AND portef.idcompte = '$idcompte'
          ORDER BY cacval.nom ";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   return $run_query;
}

function progreqinfomess()
{
	$query = "SELECT *
          FROM progmess
          WHERE 1
          ORDER BY idprogmess DESC LIMIT 1";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   return LigneSuivante($run_query);
}

/*function progget_ordrelist($condition="")
{
global $internaute;
$letimestamp=get_refresh();
$datesql=$letimestamp->datesql;
$datedown=$letimestamp->datedown;
	$query = " SELECT *
FROM ordre,cacval
WHERE ordre.codesico = cacval.codesico and idcompte=$internaute->idcompte $condition ORDER BY datecreation DESC";
	//echo $query;
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   return $run_query;
}*/

?>