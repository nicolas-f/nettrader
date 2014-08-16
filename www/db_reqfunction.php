<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
function updatecptpost()
{
//Met � jour le compteur de postage afin d'eviter les doublons d'envoie de formulaire
global $internaute;
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="UPDATE compte SET lastpostaction=UNIX_TIMESTAMP() where idcompte='$internaute->idcompte'";
$run_query =  ExecRequete ($query, $connexion);
}

function getcptpost()
{
//retourne le compteur de postage afin d'eviter les doublons d'envoie de formulaire
global $internaute;
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="SELECT lastpostaction FROM compte where idcompte='$internaute->idcompte'";
$run_query =  ExecRequete ($query, $connexion);
$ligne=LigneSuivante($run_query);
return $ligne->lastpostaction;
}

function portefeuille_joueur()
{  
global $internaute;
$idcompte=$internaute->idcompte;
	$query = "SELECT lasttime AS laststamp,cacval.codesico AS codesicav,cacval.nom AS nomsicav,cacval.yahooname as helpurl ,portef.quant AS nombsicav,(portef.quant*cacval.valeur) AS valtotsicav,cacval.valeur AS valsicav,((portef.quant*cacval.valeur)-(portef.quant*portef.ansvaleur)) AS benefsicav,(((cacval.valeur-portef.ansvaleur)/portef.ansvaleur)*100*SIGN(portef.quant)) AS pourcentsicav,portef.ansvaleur as ansvalsicav,(portef.ansvaleur*portef.quant) AS ansvaltotsicav,stats.prog
          FROM cacval,portef LEFT JOIN statsclassement stats ON ('$internaute->idcompte'=stats.idcompte)
          WHERE cacval.codesico = portef.codesico
		  	AND portef.idcompte = '$idcompte'
          ORDER BY ".tabordre("portef");
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$i++] = $run_result;
   }
   return $return;
}
function joueur_liste_sicav($idcompte="",$timemodif=0) // retourne toutes les valeurs qu'il faut mettre � jour [pour le joueur local]
{  
$condition="";
$table="";
if($idcompte<>"")
{
$idcompte = "	AND portef.idcompte = '$idcompte'";
$condition = "cacval.codesico = portef.codesico AND";
$table=",portef";
}
$letimestamp=get_refresh();
$datesql=$letimestamp->datesql+$timemodif;
$datedown=$letimestamp->datedown+$timemodif;
	$query = "SELECT cacval.codesico AS codesicav,cacval.nom AS nomsicav,cacval.valeur AS valeursicav
          FROM cacval $table
          WHERE $condition (lasttime <= '$datesql' AND lasttimedown <= '$datedown') $idcompte AND down='1'
          ORDER BY cacval.nom ";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$i++] = $run_result;
   }
   return $return;
}

function joueur_possede($sico,$idcompte)
{  
$sico=sec($sico);
	$query = "SELECT cacval.nom AS nomsicav,portef.quant AS nombsicav,ansvaleur,valeur
          FROM cacval,portef
          WHERE cacval.codesico = portef.codesico
		  	AND portef.idcompte = '$idcompte' AND cacval.codesico = '$sico'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   return LigneSuivante($run_query);
}
function GetCashBack($idjoueur)
{
$idjoueur=sec($idjoueur);
$query = "SELECT cashback
          FROM compte
          WHERE idcompte = '$idjoueur'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
$objet=	mysql_fetch_array ($run_query);
return $objet[0];
}



function ModifLiquide($idcompte,$somme) //modifie le cashback du joueur
{
global $internaute;
//UPDATE nom_table SET nom_champ=valeur WHERE nom_champ<10
$somme=round($somme+getcashback($idcompte),2);
	$query = "UPDATE compte SET `cashback`=$somme
          WHERE compte.idcompte = '$idcompte'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
$internaute->cashback = $somme;
return 0;
}

function AddHistorique($idcompte,$operation,$sicav,$nombre,$valunique, $taxe, $profit) //operation achat ou vente,
{
$maintenant=date("U");
$query = "INSERT INTO `historique` ( `temps` , `codesico` , `idcompte` , `sens` , `nbr` , `valeurunique` , `taxe`, `profit` ) VALUES ( '$maintenant', '$sicav', '$idcompte', '$operation', '$nombre', '$valunique', '$taxe', '$profit' );";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);

return 0;
}


function ModifAction($idcompte,$sicav,$quant,$valeur) // /!\ $Quant contient le nombre total d'action � affecter
{
//SELECT cacval.nom AS nomsicav,portef.quant AS nombsicav,ansvaleur
//64.654545454545|50|100|60|8879.2727272727|110|

$possede=joueur_possede($sicav,$idcompte);
if($quant==0)
{
	$query = "DELETE FROM `portef` WHERE `idcompte` = '$idcompte' AND `codesico` = '$sicav'";
}else{

	$nombdiff=$quant-$possede->nombsicav; //$nombdiff > 0 si achat
	if(($possede->nombsicav<0 && $quant<$possede->nombsicav)||($possede->nombsicav>0 && $quant>$possede->nombsicav)) //vente sur vad ou achat sur standart
		$val=((( $possede->nombsicav*$possede->ansvaleur)+($nombdiff*$valeur))/($nombdiff+$possede->nombsicav));
	else
		if(($possede->nombsicav<0 && $quant>0)||($possede->nombsicav>0 && $quant<0))
        		$val=$valeur;
		else
        		$val=$possede->ansvaleur;
	$query = "UPDATE portef SET `quant`='$quant',`ansvaleur`='$val' WHERE portef.idcompte = '$idcompte' AND portef.codesico = '$sicav'";
}	  

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
return 0;
}

function ChercheSkin($idskin) //retourne le nom,repertoire,description de la skin
{
$query = "SELECT cacval.nom AS nomsicav,portef.quant AS nombsicav,ansvaleur 
          FROM cacval,portef
          WHERE cacval.codesico = portef.codesico
		  	AND portef.idcompte = '$idcompte' AND cacval.codesico = '$sico'";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);	

return LigneSuivante($run_query);
}

function get_FromFormatedTime($ladate)
{
if (ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ladate, $regs))
{
	$day=$regs[1];
	$mon=$regs[2];
	$yr=$regs[3];
	$hours=$regs[4];
	$min=$regs[5];
	$sec=$regs[6];
	return mktime($hours,$min, $sec, $mon, $day, $yr);
}else{
	return 0;
}
}

function creer_ordre($sens,$sicav,$nombre,$valmin,$valmax,$timemin,$select,$ansvaleur,$seuil,$ppourc) //$ select, ordre nbr en % ou nombre pour execution (1==nombre 2==pourcent )
{
global $internaute;

if(!($sens=="achat" || $sens=="vente"))
	return "";

//teste les conditions, si VRAI alors on execute tout de suite, sinon on cr�� l'ordre
$dernvaleur=getvaleur($sicav);
$bddaction=donnaction($sicav);
//$timemin = "10/02/2003 19:50"
//mettre sous forme de timestamp

		if (ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2})", $timemin, $regs))
		{
			$day=$regs[1];
			$mon=$regs[2];
			$yr=$regs[3];
			$hours=$regs[4];
			$min=$regs[5];
			$timemin = mktime($hours,$min, 0, $mon, $day, $yr);
			if($timemin<date("U"))
			{
				$timemin=date("U");
			}
		}else{
			$timemin=date("U");
		}


if(tempsjeu())
{
	// On cr�e l'ordre et on modifie la quantit�e en % du capital
		$nmbr=$nombre;
		if($select==1)
		{
			$pourc=0;
		}else{
		    //on effectue la modif si c'est un pourcentage
		    if($sens=="achat")
		    {
				if($ppourc>0 && $ppourc<=100)
					$pourc=$ppourc/100;
				else
					$pourc=0;
				$nombre=0;
				$nbr=floor(getnbactionmax($internaute->cashback,$dernvaleur)*$pourc);

		     }
		     if($sens=="vente")
		     {
                		$nivjoueur=niv_joueur($internaute->idcompte);
				$possede=joueur_possede($sicav,$internaute->idcompte);
				if($nivjoueur->vad)
                    			$possede->nombsicav=getnbactionmax(getmontantvadpossible($internaute->idcompte),$dernvaleur)+$possede->nombsicav; //si on ne possede pas cette action alors on calcul le nombre maximal d'action a vendre en d�couvert
				$nombre=0;
				$nbr=floor($possede->nombsicav*($ppourc/100));
                		$pourc=$ppourc/100;
		      }


// ################################################################################################

		}
		if($seuil==0)
		{ //a tout prix
			$valmin=0;
			$valmax=-1;
		}
		//echo "#### $timemin  ####";
		if((!(SECURE)||$bddaction->lasttime>date("U")-SECURETIMEDELAY) && ($dernvaleur>$valmin && ($dernvaleur<=$valmax || $valmax==-1) && date("U")<=$timemin))
		{
            if( $dernvaleur <> $ansvaleur AND $ansvaleur<>"" ) //si la valeur de l'action a chang�
			{
				$echo = lang(4)."($dernvaleur �<> $ansvaleur�)";
			}else{
				if($sens=="achat")
				{
				    $echo = doachat($internaute->idcompte,$sicav,$nmbr,$dernvaleur);
					if($echo=="OK")
					{
					        $echo=lang(94);
					}
				}else{
				        $echo = dovente($internaute->idcompte,$sicav,$nmbr,$dernvaleur);
					if($echo=="OK")
					{
					        $echo=lang(93);
					}
				}
			}
		}else{
			if((($nombre>0 && !$pourc)||(!$nombre && $pourc>0))||($internaute->vad && ($nombre!=0 && !$pourc)||(!$nombre && $pourc!=0)))
			{
				addordre($sicav,$internaute->idcompte,date("U"),$sens,$nombre,$pourc,$timemin,$valmin,$valmax);
				$echo = lang(40);
			}else{
				$echo = lang(135);
			}
		}
}else{
	$echo = lang(69);
}
return $echo; //afficher le r�capitulatif de l'ordre
}

function listvaleur() //retourne toutes les actions pouvant �tre achet�e
{  
global $internaute;
	$query = "SELECT codesico AS codesicav,nom AS nomsicav,valeur,yahooname
          FROM cacval WHERE authachat='1' ORDER BY cacval.nom ";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$i++] = $run_result;
   }
   return $return;
}

function dansliste($sico) //to do, limitation en fonction du niveau du joueur, ou du jeu (etudiant ou nettrader2)
{  
//global $internaute;
//$idcompte=$internaute->idcompte;  
$sico=addslashes($sico);
	$query = "SELECT * 
          FROM cacval
          WHERE cacval.codesico = '$sico' and cacval.authachat='1'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   return LigneSuivante($run_query);
}

function AjoutPort($idcompte,$sicav,$quant,$valeur) //on ajoute car le joueur n'avait pas cette action
{
$possede=joueur_possede($sicav,$idcompte);
if($quant<>0)
{
	$query = "INSERT INTO `portef` ( `idcompte` , `codesico` , `quant` , `ansvaleur` ) VALUES ( '$idcompte', '$sicav', '$quant', '$valeur' )";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
}	  


return 0;
}

function delete_sicav($sicav)
{
// to do: vend toutes les valeurs correspondant � la sicav puis supprime la sicav de cacval
$query = "DELETE FROM `cacval` WHERE codesico=$sicav";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);	

}

function listmenu($type="menu") //retourne tout les champs du menu
{  
	global $internaute;
	//authlevel 2=Admin seulement,1=Logg� uniquement,0=TOUS,-1 Non logg�
	$visiteur=0;
	if(!($internaute->authlevel>=1)) //si non logg�
	{
		$authlevel = -1;
		$visiteur = 1;
	}else{
		$authlevel = $internaute->authlevel;
	}
	$query = "SELECT idmenu,type_menu,text_id,CONCAT(link_menu,'do=',do) AS link_menu,alldo,do
	FROM menu
	WHERE (type_menu='menu' or type_menu='$type') AND ((`authlevel`<='$authlevel' AND `visiteurseulement`='$visiteur') OR `authlevel`='0')
	ORDER BY idmenu";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	$i=0;
	$return="";
	while ( $run_result = mysql_fetch_array($run_query) )
	{   $return[$i++] = $run_result;
	}
	return $return;
}

function listhisto($deblign,$nblign,$depuis=0) //retourne tous les champs de l'historique 7 champs
{  
global $internaute;
$reqsup="";
if($depuis>0) $reqsup=" and historique.temps>'$depuis'";
	$query = "SELECT temps AS LADATE,cacval.nom AS LENOM,sens AS LESENS,nbr AS LENOMBRE,CONCAT( valeurunique, ' � ( ', valeurunique * nbr, ' � )' )  AS LEHT,round(ABS(taxe),2) AS LATAXE, round(valeurunique*nbr + taxe,2) AS LETTC, temps as UNIX, (valeurunique * nbr) AS LETOTHT, CONCAT( profit, ' �') as PROFITOP
          FROM cacval,historique
		  WHERE idcompte='$internaute->idcompte' and historique.codesico = cacval.codesico $reqsup
		  ORDER BY ".tabordre("historique")." LIMIT $deblign,$nblign";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$i++] = $run_result;
   }
   return $return;
}

/* function listclassement($mois,$ligncour,$maxligne)
{

global $internaute;
$addchamp="";
if(INCONC)
{
	$addchamp = ",etablissement";
}
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$date1 = mktime("01", "01", "01", $mon, "1", $yr);
$ladate=date("Y-m-d",$date1);

list($yr,$mon,$jour) = explode("-",$mois);
$date2 = mktime("01", "01", "01", $mon+1, "1", $yr);
$ladateap=date("Y-m-d",$date2);

list($yr,$mon,$jour) = explode("-",$mois);
$date3 = mktime("01", "01", "01", $mon, "1", $yr);
$ladatesel=date("Y-m-d",$date3);

if($ladate==$mois)
{
$query = "
SELECT pseudonyme, CONCAT( round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + cashback, 2 ) , \" �\" ) AS capital, round( (
(
COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + compte.cashback - COALESCE( scores.capitalscores, ".CAPDEB." ) ) / COALESCE( scores.capitalscores, ".CAPDEB." ) ) * 100, 2
) AS prog $addchamp
FROM compte
LEFT JOIN portef
USING ( idcompte )
LEFT  JOIN scores
ON (compte.idcompte=scores.idcompte AND scores.datescore = '$mois')
LEFT JOIN cacval ON cacval.codesico = portef.codesico
WHERE authlevel = '1'
GROUP BY compte.idcompte
ORDER BY prog DESC
LIMIT $ligncour,$maxligne
";

}else{
echo "[2]";
$cap=CAPDEB;
$query = "
SELECT pseudonyme, CONCAT(b.capitalscores,' �')   AS capital,
round(((b.capitalscores - COALESCE( a.capitalscores, $cap ))/COALESCE( a.capitalscores, $cap ))*100,2) AS prog
 $addchamp
FROM compte, scores AS b
LEFT JOIN scores AS a ON (b.idcompte = a.idcompte AND b.datescore <> a.datescore AND a.datescore = '$ladatesel')
WHERE b.datescore = '$ladateap'
AND b.idcompte = compte.idcompte
ORDER BY prog DESC LIMIT $ligncour,$maxligne
";

}
	//echo "[$query]";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   return $run_query;
} */
/*
SELECT pseudonyme, CONCAT(b.capitalscores,' �')   AS capital, CONCAT(round((b.capitalscores - COALESCE( a.capitalscores, 10000 ))/10000,2),' %') AS prog ,b.capitalscores - COALESCE( a.capitalscores, 10000 ) as ordre
FROM compte, scores AS b
LEFT JOIN scores AS a ON b.idcompte = a.idcompte AND b.datescore <> a.datescore
WHERE b.datescore = '2004-06-01'
AND (a.datescore = '2004-05-01' OR ISNULL(a.datescore))
AND b.idcompte = compte.idcompte
ORDER BY ORDRE DESC
 */

function listclassementequipe($mois,$ligncour,$maxligne,$cherche="")
{

global $internaute;
$addchamp="";
$addreq="";
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$date1 = mktime("01", "01", "01", $mon, "1", $yr);
$ladate=date("Y-m-d",$date1);

list($yr,$mon,$jour) = explode("-",$mois);
$date2 = mktime("01", "01", "01", $mon+1, "1", $yr);
$ladateap=date("Y-m-d",$date2);

list($yr,$mon,$jour) = explode("-",$mois);
$date3 = mktime("01", "01", "01", $mon, "1", $yr);
$ladatesel=date("Y-m-d",$date3);
$cap=CAPDEB;

	if($ladate==$mois)
	{
		$query = "
                SELECT iselect.idgroupe as idgroupe, titregroupe,initialgroupe,medor,medargent,medbronze, round( (
		(
		SUM( capital ) - SUM( debcapital ) ) / SUM( debcapital )
		) *100, 2
		) AS prog, COUNT( * ) AS nbjoueurs
		FROM (

		SELECT idgroupe, COALESCE( scores.capitalscores, $cap ) AS debcapital, round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + cashback, 2 ) AS capital
		FROM membregroupe, compte
		LEFT JOIN portef
		USING ( idcompte )
		LEFT JOIN scores ON ( compte.idcompte = scores.idcompte
		AND scores.datescore = '$ladate' )
		LEFT JOIN cacval ON cacval.codesico = portef.codesico
		WHERE membregroupe.idcompte = compte.idcompte
		GROUP BY compte.idcompte
		) AS iselect, groupe
		WHERE iselect.idgroupe = groupe.idgroupe
		GROUP BY titregroupe, idgroupe HAVING nbjoueurs>1
		ORDER BY ".tabordre("classementequipe")."
		";

	}else{
		$query = "
                SELECT groupe.idgroupe as idgroupe, titregroupe,initialgroupe,medor,medargent,medbronze, round( (
		( capitalfin  - capitaldeb ) / capitaldeb
		) *100, 2  ) AS prog, nbmembres AS nbjoueurs
		FROM scoresgroupes,groupe WHERE datescore='$ladateap' and scoresgroupes.idgroupe = groupe.idgroupe
		ORDER BY ".tabordre("classementequipe")."
		";

	}

$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$l=0;
$i=0;
   $return="";
   $all="";
   $specified="";
   $pseudotrouv=-1;
   while ( $run_result = mysql_fetch_array($run_query) )
   {
     $all[$l] = $run_result;
     $h=0;
     if($l>=$ligncour && $l<$ligncour+$maxligne)
     {
   	$return[$i++] = $run_result;
   	$h=1;
     }
     if($h==0 && compareclass($run_result['titregroupe'],$cherche))
     {
	$pseudotrouv=$l;
     }
     $l++;
   }
   $debut=-1;
   if($pseudotrouv<>-1)
   {
   	$x=0;
	$debut=numlimit($pseudotrouv,$l-1,-2);
	$fin=numlimit($pseudotrouv,$l-1,2);
	for($y=$debut;$y<=$fin;$y++)
	{
	        $specified[$x] =$all[$y] ;
	        $x++;
	}
   }
   $retourne->liste=$return;
   $retourne->spec=$specified;
   $retourne->classement=$pseudotrouv;
   $retourne->deb=$debut;
   $retourne->nb=$l;
   return $retourne;
}

function gettabjoueursenequipes()
{

	$query = "SELECT membregroupe.idcompte as idcompte, initialgroupe , groupe.idgroupe as idgroupe
          FROM membregroupe,groupe
		  WHERE groupe.idgroupe=membregroupe.idgroupe";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   $return=array();
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$run_result["idcompte"]] = array($run_result["initialgroupe"],$run_result["idgroupe"]);
   }
   return $return;


}

function listclassement($mois,$ligncour,$maxligne,$cherche="")
{

global $internaute;
$addchamp="";
$addreq="";
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$date1 = mktime("01", "01", "01", $mon, "1", $yr);
$ladate=date("Y-m-d",$date1);

list($yr,$mon,$jour) = explode("-",$mois);
$date2 = mktime("01", "01", "01", $mon+1, "1", $yr);
$ladateap=date("Y-m-d",$date2);

list($yr,$mon,$jour) = explode("-",$mois);
$date3 = mktime("01", "01", "01", $mon, "1", $yr);
$ladatesel=date("Y-m-d",$date3);
if(INCONC)
{
        $query = "
		SELECT pseudonyme, round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + cashback, 2 ) AS capital, round( (
		(
		COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + compte.cashback - ".CAPDEB." ) / ".CAPDEB." ) * 100, 2
		) AS prog ,etablissement,compte.idcompte as idcompte
		FROM compte
		LEFT JOIN portef
		USING ( idcompte )
  		LEFT JOIN cacval ON cacval.codesico = portef.codesico
		WHERE authlevel = '1' and cashback<>'".CAPDEB."'
		GROUP BY compte.idcompte
		ORDER BY ".tabordre("classement")."
		";


}else{
	if($ladate==$mois)
	{
		if(istableexist("statsclassement"))
		{
		$query = "
		SELECT * FROM statsclassement
		ORDER BY ".tabordre("classement");
		}else{
                $query = "SELECT pseudonyme, round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + cashback, 2 ) AS capital, round( (
		(
		COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + compte.cashback - COALESCE( scores.capitalscores, ".CAPDEB." ) ) / COALESCE( scores.capitalscores, ".CAPDEB." ) ) * 100, 2
		) AS prog,compte.idcompte as idcompte
		FROM compte
		LEFT JOIN portef
		USING ( idcompte )
		LEFT  JOIN scores
		ON (compte.idcompte=scores.idcompte AND scores.datescore = '$ladate')
		LEFT JOIN cacval ON cacval.codesico = portef.codesico
		WHERE authlevel = '1' and dateactivite>=UNIX_TIMESTAMP()-2592000 and cashback<>'".CAPDEB."'
		GROUP BY compte.idcompte
		ORDER BY ".tabordre("classement");

		}
	}else{
		$cap=CAPDEB;
		$query = "
		SELECT pseudonyme, b.capitalscores AS capital,
		round(((b.capitalscores - COALESCE( a.capitalscores, $cap ))/COALESCE( a.capitalscores, $cap ))*100,2) AS prog,compte.idcompte as idcompte
		 $addchamp
		FROM compte, scores AS b
		LEFT JOIN scores AS a ON (b.idcompte = a.idcompte AND b.datescore <> a.datescore AND a.datescore = '$ladatesel')
		WHERE b.datescore = '$ladateap'
		AND b.idcompte = compte.idcompte
		AND authlevel = '1'
		ORDER BY ".tabordre("classement")."
		";

	}
}
	//echo "[$query]";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
	$l=0;
	$i=0;
   $return="";
   $all="";
   $specified="";
   $pseudotrouv=-1;
   while ( $run_result = mysql_fetch_array($run_query) )
   {
     $all[$l] = $run_result;
     $h=0;
     if($l>=$ligncour && $l<$ligncour+$maxligne)
     {
   	$return[$i++] = $run_result;
   	$h=1;
     }
     if($h==0 && compareclass($run_result['pseudonyme'],$cherche))
     {
	$pseudotrouv=$l;
     }
     $l++;
   }
   $debut=-1;
   if($pseudotrouv<>-1)
   {
   	$x=0;
	$debut=numlimit($pseudotrouv,$l-1,-2);
	$fin=numlimit($pseudotrouv,$l-1,2);
	for($y=$debut;$y<=$fin;$y++)
	{
	        $specified[$x] =$all[$y] ;
	        $x++;
	}
   }
   $retourne->liste=$return;
   $retourne->spec=$specified;
   $retourne->classement=$pseudotrouv;
   $retourne->deb=$debut;
   $retourne->nb=$l;
   return $retourne;
}

function listclassementcount($moisstamp)
{
global $internaute;
	 $query = "SELECT COUNT(*) as nbrplayer
FROM compte
WHERE authlevel='1' and dateinscr<'$moisstamp'"; 

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
	$lignresult = LigneSuivante($run_query);
   return $lignresult->nbrplayer;
}

function getclassementsicavlist() // retourne toutes les valeurs qu'il faut mettre � jour pour afficher le classement
{  

$letimestamp=get_refresh();
$datesql=$letimestamp->datesql;
$datedown=$letimestamp->datedown;
//echoadmin("[".date("j/m/y H:i:s",$datesql)."] et [".date("j/m/y H:i:s",$datedown)."]");
	$query = "SELECT cacval.codesico AS codesicav,cacval.nom AS nomsicav,cacval.valeur AS valeursicav
          FROM cacval,portef
          WHERE cacval.codesico = portef.codesico
		  AND !(lasttime > '$datesql' OR lasttimedown > '$datedown')
		  GROUP BY codesicav,nomsicav,valeursicav";
	//echoadmin(" [".$query."] <br>");
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$i++] = $run_result;
   }
   return $return;
}

function cmd_update_sicav($codesico,$valeur,$lasttime,$lasttimedown )
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$codesico=intval($codesico);
//echo chr(13)."Sico:".chr(9).$codesico.chr(9)." Valeur:".chr(9).$valeur.chr(9)." TimeStamp:".chr(9).$lasttime.",".$lasttimedown;

$resultat=ExecRequete("UPDATE cacval SET valeur=$valeur, lasttime=$lasttime, lasttimedown=$lasttimedown WHERE codesico = $codesico AND lasttime<=$lasttime AND lasttimedown<$lasttimedown",$connexion);
return 0;
}

function get_dernier_timestamp()
{
	$query = "SELECT min(lasttime) AS laststamp,min(lasttimedown) AS lastdownstamp
          FROM cacval";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {
		$timestamp->lasttime=$run_result["laststamp"];
   		$timestamp->lasttimedown=$run_result["lastdownstamp"];
   }
return $timestamp;
}


function addordre($codesico,$idcompte,$datecreation,$sens,$nbr,$pourc,$tempslim,$coursmin,$coursmax) //operation achat ou vente, 
{
if(intval($pourc==0))
 $pourc="NULL";
else
 $pourc="'".$pourc."'";
$query = "INSERT INTO `ordre` ( `codesico` , `idcompte` , `datecreation` , `sens` , `nbr` , `pourc` , `tempslim` , `coursmin` , `coursmax` ) VALUES ('$codesico','$idcompte','$datecreation','$sens','$nbr',$pourc,'$tempslim','$coursmin','$coursmax');";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);

return 0;
}

function niv_joueur($idcompte) //to do, limitation en fonction du niveau du joueur, ou du jeu (etudiant ou nettrader2)
{  
	$query = "SELECT niveau.* 
          FROM compte,niveau
          WHERE compte.idcompte = $idcompte AND compte.idniveau = niveau.idniveau";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   return LigneSuivante($run_query);
}

function get_ordre()
{
if(SECURE)
{
	$add=" AND datecreation-".SECURETIMEDELAY." <= cacval.lasttime";
}else{
	$add="";
}

$letimestamp=get_refresh();
$datesql=$letimestamp->datesql;
$datedown=$letimestamp->datedown;
$now=date("U");
	$query = " SELECT * 
FROM ordre,cacval
WHERE ordre.codesico = cacval.codesico AND ( lasttime > '$datesql' OR lasttimedown > '$datedown')
 AND ( ( ordre.coursmin <= cacval.valeur AND ordre.coursmax >= cacval.valeur ) OR ( ordre.coursmin <= cacval.valeur AND ordre.coursmax = '-1' ) )
 $add AND $now <= tempslim and (cacval.authachat='1' or ordre.sens='vente') and etat='1' ORDER BY datecreation ASC";
		  
		 


	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$i++] = $run_result;
   }
return $return;
}

function add_msg($idfrom,$idrecept,$title,$corps)
{
$etat="non lu";
if($idrecept==0) $etat="lu";
$query = "INSERT INTO `messages` ( `idcompte` , `datemess` , `idenvoyeur` , `titre` , `corps` , `etat`) 
VALUES ('$idrecept', '".date("U")."', '$idfrom', '$title', '$corps', '$etat');";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
return "";
}

function upd_msgetat($idmessage)
{
global $internaute;
$query = "UPDATE `messages` SET etat='lu' WHERE idmsg='$idmessage' and idcompte='$internaute->idcompte'";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
return "";
}

function dodelmessage($idmessage)
{
global $internaute;
$query = "DELETE FROM `messages` WHERE idmsg='$idmessage' and (idcompte='$internaute->idcompte' or (idenvoyeur='$internaute->idcompte' and etat='non lu' ))";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
return msgtab(lang(177),lang(86));
}

function efface_ordre($codesico,$idcompte,$datecreation)
{
//$query = "DELETE FROM `ordre` WHERE `codesico` = '$codesico' AND `idcompte` = '$idcompte' AND CONCAT(`datecreation`) = '$datecreation' LIMIT 1";
$query = "UPDATE `ordre` SET etat='0' WHERE `codesico` = '$codesico' AND `idcompte` = '$idcompte' AND CONCAT(`datecreation`) = '$datecreation' LIMIT 1";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
return mysql_affected_rows();
}

function get_ordrelist($condition="")
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
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_assoc($run_query) )
   {   $return[$i++] = $run_result;
   }
return $return;
}

function get_idmenu()
{
global $do;
$reqdo=sec($do);
$query = "SELECT text_id 
          FROM menu
          WHERE do='$reqdo'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
	$temp=LigneSuivante($run_query);
   return $temp->text_id;
}

function del_ordre($datecreation)
{
global $internaute;
$query = "DELETE FROM `ordre` WHERE `idcompte` = '$internaute->idcompte' AND CONCAT(`datecreation`) = '$datecreation' LIMIT 1";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
return "";
}

function get_info_ordre($datecreation)
{
global $internaute;
$query = "SELECT * FROM `ordre` WHERE `idcompte` = '$internaute->idcompte' AND CONCAT(`datecreation`) = '$datecreation' LIMIT 1";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
return LigneSuivante($run_query);
}

function donnaction($codesico) //to do, limitation en fonction du niveau du joueur, ou du jeu (etudiant ou nettrader2)
{  
	$query = "SELECT * 
          FROM cacval
          WHERE cacval.codesico='$codesico'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   return LigneSuivante($run_query);
}

function donnactionyn($yn) //information de l'action
{
	$query = "SELECT *
          FROM cacval,secteurent
          WHERE cacval.idsecteur=secteurent.idsecteur and cacval.yahooname='$yn'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   return LigneSuivante($run_query);
}

function stataction($codesico,$limit) //Stat, nombre de vente et d'achat sur la semaine pass�
{
	$query = "SELECT FROM_UNIXTIME( temps, '%d/%m/%Y' ) AS jour, sens,AVG(valeurunique) as valeurechang,SUM( nbr ) as nb , SUM(IF(profit>0,profit,0)) as profit,ABS(SUM(IF(profit<0,profit,0))) as perte
          FROM historique
          WHERE historique.codesico='$codesico' and temps>'$limit'
			GROUP BY jour,sens ORDER BY temps DESC LIMIT 9";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   return $run_query;
}
/*
SELECT IF (
sens = "achat", coursmax, coursmin
) AS valeur, sens, SIGN( nbr ) AS
TYPE , SUM( nbr ) AS quant, AVG( pourc ) *100 AS prc
FROM ordre
WHERE ordre.codesico = '25874' AND etat = '1'
GROUP BY valeur, sens,
TYPE
*/
/*
function ordreaction($codesico,$tmps) //Ordres concernant un action
{
	$query = "SELECT IF(sens=\"achat\",coursmax,coursmin) as valeur, sens,SIGN( nbr ) AS
type , SUM( nbr ) as quant, AVG( pourc) * 100 as prc
          FROM ordre
          WHERE ordre.codesico='$codesico' and etat='1' and datecreation<='$tmps'
			GROUP BY valeur,sens,type HAVING valeur<>0 AND valeur<>-1 ORDER BY sens,valeur ";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   return $run_query;
}
*/
function ordreactionachat($codesico,$tmps,$valaction) //Ordres concernant un action
{
	$query = "SELECT coursmax as valeur, SUM( nbr ) as quant, AVG( pourc) * 100 as prc
          FROM ordre
          WHERE ordre.codesico='$codesico' and etat='1' and (datecreation<='$tmps' or !".SECURE.") and sens='achat' and coursmax<'$valaction' and coursmax>0
			GROUP BY valeur ORDER BY valeur DESC LIMIT 4";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   return $run_query;
}

function ordreactionvente($codesico,$tmps,$valaction) //Ordres concernant un action
{
	$query = "SELECT coursmin as valeur, SUM( nbr ) as quant, AVG( pourc) * 100 as prc
          FROM ordre
          WHERE ordre.codesico='$codesico' and etat='1' and (datecreation<='$tmps' or !".SECURE.") and sens='vente' and coursmin>'$valaction'
			GROUP BY valeur ORDER BY valeur DESC LIMIT 4";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   return $run_query;
}











function exeadminreq($idreq)
{
$idreq=sec($idreq);
$query = "SELECT *
FROM reqlist
WHERE idreq='$idreq'"; 

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
if($run_query<>"")
{
	$resultat = LigneSuivante($run_query);
	$nouvutil=$resultat->nbutil+1;
	$query = "UPDATE reqlist SET nbutil='$nouvutil' WHERE idreq='$resultat->idreq'"; 
	$run_query =  ExecRequete ($query, $connexion);	
		if($resultat->req<>"")
		{
			$query = stripslashes($resultat->req);
			$run_query =  ExecRequete ($query, $connexion);	
		}
}else{
	return "Erreur dans l'id (contacter nicolas)";
}
$retour->req=$run_query;
$retour->titre=$resultat->libelreq;
return $retour;
}

function listadminreq()
{
global $internaute;
$query = "SELECT CONCAT(\"<a href=\'index.php?do=exeadmin&idreq=\",idreq,\"\'>\",libelreq,\"</a>\") AS 'Afficher :', nbutil AS 'Nombre d\'affichage'
FROM reqlist ORDER BY nbutil DESC"; 

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   
   return $run_query;
}

function listhistocount($idcompte) //retourne tous les champs de l'historique 7 champs
{  
	$query = "SELECT COUNT(*) as nbrhisto
          FROM historique
		  WHERE idcompte=$idcompte";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
	$ligne=LigneSuivante($run_query);

   return $ligne->nbrhisto;
}

function getplayercapital($idcompte)
{
$query = "SELECT pseudonyme, round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0  )  + cashback, 2  )  AS capital
FROM compte
LEFT  JOIN portef
USING ( idcompte ) 
LEFT  JOIN cacval
USING ( codesico ) 
WHERE compte.idcompte =  '$idcompte'
GROUP  BY compte.idcompte"; 

$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);	
$resultat = LigneSuivante($run_query);


return $resultat->capital;
}

function getplayercapitalhorsvad($idcompte)
{
$query = "SELECT round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0  ), 2  )  AS capital
FROM portef
LEFT  JOIN cacval
USING ( codesico )
WHERE portef.idcompte =  '$idcompte' and portef.quant>'0'
GROUP  BY portef.idcompte";

$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$resultat = LigneSuivante($run_query);


return doubleval($resultat->capital);
}


function getplayercapitalvad($idcompte)
{
$query = "SELECT round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0  ), 2  )  AS capital
FROM portef
LEFT  JOIN cacval
USING ( codesico )
WHERE portef.idcompte =  '$idcompte' and portef.quant<'0'
GROUP  BY portef.idcompte";

$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$resultat = LigneSuivante($run_query);

return -doubleval($resultat->capital);
}

function listmessagescount($idcompte) //retourne tous les champs de l'historique 7 champs
{  
	$query = "SELECT COUNT(*) as nbrmsg
          FROM messages
		  WHERE idcompte='$idcompte'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
	$ligne=LigneSuivante($run_query);

   return $ligne->nbrmsg;
}

function get_tempsbourse()
{
	$query = "SELECT max(lasttime) AS laststamp
          FROM cacval";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {
		$timestamp=$run_result["laststamp"];
   }
return $timestamp;
}

function listskin() //retourne la liste des skin
{  
	$query = "SELECT *
          FROM skin ORDER BY nomskin ASC";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$run_result['idskin']] = $run_result['nomskin'];
   }
   return $return;
}

function skin_existe($idskin)
{
$query = "SELECT *
          FROM skin where idskin='$idskin'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
if(mysql_num_rows($run_query)==0)
{
   return 0;
}else{
	return 1;
}
}

function scoreestactuel()
{
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$date1 = mktime("01", "01", "01", $mon, $day, $yr);
$ladate=date("Y-m-d",$date1);
$query = "SELECT MAX( datescore ) as dernier
FROM `scores`
WHERE 1";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);	
$nombre=LigneSuivante($run_query);
if($nombre->dernier==$ladate)
{
	return 1;
}else{
	return 0;
}
}

function teamscoreestactuel()
{
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$date1 = mktime("01", "01", "01", $mon, "01", $yr);
$ladate=date("Y-m-d",$date1);
$query = "SELECT MAX( datescore ) as dernier
FROM `scoresgroupes`
WHERE 1";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$nombre=LigneSuivante($run_query);
if($nombre->dernier==$ladate)
{
	return 1;
}else{
	return 0;
}
}
function insertscore()
{
//insert dans le tableau score le score pour le mois �coul�
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$date1 = mktime("01", "01", "01", $mon, $day, $yr);
//$date2 = mktime("01", "01", "01", $mon-1, "1", $yr);
$ladate=date("Y-m-d",$date1);
//$ladatemoisprec=date("Y-m-d",$date2);
$addreq="";
if(0)
{
         $addreq="WHERE dateactivite>=UNIX_TIMESTAMP()-2592000";
}
$query = "INSERT INTO `scores` SELECT compte.idcompte,  '$ladate', round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0  )  + cashback, 2  )  AS capital
FROM compte
LEFT  JOIN portef
USING (idcompte)
LEFT JOIN cacval USING(codesico) WHERE dateactivite>0 and cashback<>'".CAPDEB."'
 GROUP  BY compte.idcompte";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
return 1;
}

function getperfgroupes()
{
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$date1 = mktime("01", "01", "01", $mon, $day, $yr);
$ladate=date("Y-m-d",$date1);
$query = "SELECT scoresgroupes.idgroupe,round( (
		(
		capitalfin - capitaldeb ) / capitaldeb ) * 100, 2
		) AS prog from scoresgroupes,membregroupe where datescore='$ladate' and scoresgroupes.idgroupe=membregroupe.idgroupe GROUP BY scoresgroupes.idgroupe ORDER BY prog DESC";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	return $run_query;



}



function insertgroupescore()
{
//insert dans le tableau score le score pour le mois �coul�
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$date1 = mktime("01", "01", "01", $mon, $day, $yr);
$date2 = mktime("01", "01", "01", $mon-1, "1", $yr);
$ladate=date("Y-m-d",$date1);
$ladatemoisprec=date("Y-m-d",$date2); //capitaldeb capitalfin nbmembres
$query = "INSERT INTO `scoresgroupes` SELECT idgroupe,'$ladate', SUM( debcapital ) capitaldeb, SUM( capital ) capitalfin, COUNT( * ) nbmembres
FROM (
SELECT idgroupe, COALESCE( scores.capitalscores, ".CAPDEB." ) AS debcapital, round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + cashback, 2 ) AS capital
FROM membregroupe, compte
LEFT JOIN portef
USING ( idcompte )
LEFT JOIN scores ON ( compte.idcompte = scores.idcompte
AND scores.datescore = '$ladatemoisprec' )
LEFT JOIN cacval ON cacval.codesico = portef.codesico
WHERE membregroupe.idcompte = compte.idcompte
GROUP BY compte.idcompte
) AS iselect
GROUP BY idgroupe
HAVING nbmembres>1";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return 1;
}

function listmoisclass()
{
	$query = "SELECT * FROM listmoisclass";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	$format="m-Y";
	$format2=" Y";// mettre le format francais mois ann�e
	$format3="m";
	$listmois=array();
	$valuedate=date($format);
	$titredate=tomoisfr(date($format3)).date($format2);
   	$listmois[$valuedate] = $titredate;
	while ( $run_result = mysql_fetch_array($run_query) )
    {

   		list($yr,$mon,$day) = explode("-",$run_result['datescore']);
		$date1 = mktime("01", "01", "01", $mon-1, "1", $yr);
		$valuedate=date($format,$date1);
		$titredate=tomoisfr(date($format3,$date1)).date($format2,$date1);
   		$listmois[$valuedate] = $titredate;
     
    }
   	
	return $listmois;
}

function listmoisclassequipe()
{
	$query = "SELECT datescore
FROM `scoresgroupes`
WHERE 1
GROUP BY datescore
ORDER BY datescore DESC";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	$format="m-Y";
	$format2=" Y";// mettre le format francais mois ann�e
	$format3="m";
	$listmois=array();
	$valuedate=date($format);
	$titredate=tomoisfr(date($format3)).date($format2);
   	$listmois[$valuedate] = $titredate;
	while ( $run_result = mysql_fetch_array($run_query) )
    {
   		list($yr,$mon,$day) = explode("-",$run_result['datescore']);
		$date1 = mktime("01", "01", "01", $mon-1, "1", $yr);
		$valuedate=date($format,$date1);
		$titredate=tomoisfr(date($format3,$date1)).date($format2,$date1);
   		$listmois[$valuedate] = $titredate;

    }

	return $listmois;
}

function getyahooname($sico)
{
$query = "SELECT yahooname
          FROM cacval WHERE codesico='$sico'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	$obj=LigneSuivante($run_query);
	return $obj->yahooname;
}

function get_yahoosicavliste()
{
global $internaute;
	$query = "SELECT yahooname
          FROM cacval WHERE down='1'
          ORDER BY codesico";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   $i=0;
   $return="";
   while ( $r = mysql_fetch_object($run_query) )
   {
        if($i==1)
        {
                $return.="+";
        }
   	$return.= $r->yahooname;
   	$i=1;
   }
   return NOUVADDR.$return.NOUVADDRFIN;
}

function getinternauteinfo($pseudo)
{
$query = "SELECT *
          FROM `compte` where pseudonyme='$pseudo'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
$resultat=LigneSuivante($run_query);
return $resultat;
}

function setmdp($idcompte,$mdp)
{
$passe=md5($mdp);
$query = "UPDATE compte SET passe='$passe' WHERE idcompte='$idcompte'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return 1;
}


function increcompensegroupe($idgroupe,$or,$argent,$bronze)
{
$query = "UPDATE `groupe` SET `medor`=`medor`+$or,`medargent`=`medargent`+$argent,`medbronze`=`medbronze`+$bronze WHERE idgroupe='$idgroupe'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return 1;
}

function getheritier($idgroupe,$idcomptedead)
{
$query = "SELECT *
          FROM `membregroupe` where idgroupe='$idgroupe' and idcompte!='$idcomptedead' ORDER BY datejoint ASC LIMIT 1";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
$resultat=LigneSuivante($run_query);
return $resultat->idcompte;
}


function fctgetoffteammaster($idcompte)
{
//si membres dans le groupe, le suivant devient maitre
//si aucun membre dans le groupe, suppression du groupe
$groupe=getgroupbyadmin($idcompte);
if($groupe->idgroupe>0)
{
	$idcompteheritier=getheritier($groupe->idgroupe,$idcompte);
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	if($idcompteheritier>0)
	{
	        $query = "UPDATE `groupe` SET `idcompte` = '$idcompteheritier' WHERE `idgroupe` = '$groupe->idgroupe' LIMIT 1";
		$run_query =  ExecRequete ($query, $connexion);
                fctgetoffteam($idcompte);
	}else{
		$query = "DELETE FROM `invitegroupe` WHERE `idgroupe` = '$groupe->idgroupe'";
		$run_query =  ExecRequete ($query, $connexion);
		$query = "DELETE FROM `membregroupe` WHERE `idgroupe` = '$groupe->idgroupe'";
		$run_query =  ExecRequete ($query, $connexion);
		$query = "DELETE FROM `scoresgroupes` WHERE `idgroupe` = '$groupe->idgroupe'";
		$run_query =  ExecRequete ($query, $connexion);
		$query = "DELETE FROM `verifgroupe` WHERE `idgroupe` = '$groupe->idgroupe'";
		$run_query =  ExecRequete ($query, $connexion);
		$query = "DELETE FROM `groupe` WHERE `idgroupe` = '$groupe->idgroupe'";
		$run_query =  ExecRequete ($query, $connexion);
	}
}
}

function fctgetoffteam($idcompte)
{
$groupe=getgroupbymembre($idcompte);
if($groupe->idgroupe>0)
{
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$query = "DELETE FROM `membregroupe` WHERE `idgroupe` = '$groupe->idgroupe' and `idcompte`='$idcompte' LIMIT 1";
	$run_query =  ExecRequete ($query, $connexion);
}
}

function fctdoraz($liste,$optdel=0)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
//supprimer enregistrement dans
//portefeuille,historique,messages,scores,ordres,compte
$query = "DELETE FROM portef WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
$query = "DELETE FROM historique WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
$query = "DELETE FROM messages WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
$query = "DELETE FROM scores WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
$query = "DELETE FROM ordre WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
$query = "DELETE FROM invitegroupe WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
$query = "DELETE FROM membregroupe WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);


//maj du compte

$query = "UPDATE compte SET cashback='".CAPDEB."' WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);


//si suppr demand� on supprime le compte

//si suppr demand� on supprime le compte

if($optdel && $liste!=1 && $liste!=IDCOMPTEDEMO)
{
	$query = "DELETE FROM tabaidecomment WHERE idcompte IN ($liste)";
	$run_query =  ExecRequete ($query, $connexion);
	$query = "DELETE FROM tabfaqcomment WHERE idcompte IN ($liste)";
	$run_query =  ExecRequete ($query, $connexion);
    	$query = "DELETE FROM compte WHERE idcompte IN ($liste)";
	$run_query =  ExecRequete ($query, $connexion);
	//Pour forum, remplacer idcompte du joueur par idcompte du compte ANONYME
        $query = "UPDATE `f_sujet` SET `idcompteauteur` = '".ID_COMPTE_ANONYME."' WHERE `idcompteauteur` IN ($liste)";
	$run_query =  ExecRequete ($query, $connexion);
        $query = "UPDATE `f_message` SET `idcompte` = '".ID_COMPTE_ANONYME."' WHERE `idcompte` IN ($liste)";
	$run_query =  ExecRequete ($query, $connexion);
}

return 0;
}
/*
function sqldelusers($liste)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
//supprimer enregistrement dans
//portefeuille,historique,messages,scores,ordres,compte
$query = "DELETE FROM portef WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
$query = "DELETE FROM historique WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
$query = "DELETE FROM messages WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
$query = "DELETE FROM scores WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
$query = "DELETE FROM ordre WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
//$query = "INSERT INTO comptehisto SELECT * FROM compte WHERE idcompte IN ($liste)";
//$run_query =  ExecRequete ($query, $connexion);
//maj du compte

$query = "UPDATE compte SET cashback='".CAPDEB."' WHERE idcompte IN ($liste)";
$run_query =  ExecRequete ($query, $connexion);
//$query = "DELETE FROM compte WHERE idcompte IN ($liste)";
//$run_query =  ExecRequete ($query, $connexion);

return 0;
}
*/
function getCodesSicoSecteurPortef($idjoueur)
{
$query = "SELECT cacvalfinish.codesico
      FROM cacval,portef,cacval as cacvalfinish WHERE cacval.codesico=portef.codesico AND portef.idcompte='$idjoueur' and cacval.idsecteur=cacvalfinish.idsecteur ORDER BY cacvalfinish.idsecteur";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$lst="";
while($res=LigneSuivante($run_query))
{
	if($lst<>"") $lst.=",";
	$lst.=$res->codesico;
}
return $lst;
}

function getCodesSicoPortef($idjoueur)
{
$query = "SELECT portef.codesico
      FROM portef WHERE portef.idcompte='$idjoueur'";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$lst="";
while($res=LigneSuivante($run_query))
{
	if($lst<>"") $lst.=",";
	$lst.=$res->codesico;
}
return $lst;
}

function getCodesSicoCote($idjoueur)
{
$query = "SELECT cacval.codesico,ROUND(SUM(nbr*valeurunique),2) as Valeur  FROM cacval,historique WHERE cacval.codesico = historique.codesico AND temps>UNIX_TIMESTAMP()-(3600*24*7) AND historique.nbr>0 GROUP BY cacval.nom ORDER BY Valeur DESC LIMIT 5";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$lst="";
while($res=LigneSuivante($run_query))
{
	if($lst<>"") $lst.=",";
	$lst.=$res->codesico;
}
return $lst;
}

function ajoutcommentaire($message,$idaide)
{
global $internaute;
if($message=="") return "";
$query = "INSERT INTO `tabaidecomment` ( `idcomment` , `idaide` , `idcompte` , `datecomment` , `textecomment` )
VALUES (
'', '$idaide', '$internaute->idcompte', '".date("U")."', '$message'
)";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
return "";
}

function effacvieuxordres()
{
global $internaute;
$query = "DELETE FROM `ordre`
WHERE (
`etat` = '0' OR `tempslim` < UNIX_TIMESTAMP( )
) AND `datecreation` < UNIX_TIMESTAMP( ) -3600*25 AND idcompte='$internaute->idcompte'";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	
return $query;
}

function effacordresinactifs()
{
global $internaute;
$query = "DELETE FROM `ordre`
WHERE (
`etat` = '0' OR `tempslim` < UNIX_TIMESTAMP( )
) AND idcompte='$internaute->idcompte'";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

return $query;
}



function delcommentaire($idcomment)
{
global $internaute;

	$query = "SELECT *
FROM tabaidecomment
WHERE idcomment= '$idcomment' ";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	$lauteur=LigneSuivante($run_query);
if($lauteur->idcompte==$internaute->idcompte || $internaute->authlevel>1)
{

	$query = "DELETE FROM `tabaidecomment` WHERE `idcomment`='$idcomment'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
}
return "";
}

function ajoutcommentairefaq($message,$idaide)
{
global $internaute;
if($message=="") return "";
$query = "INSERT INTO `tabfaqcomment` ( `idcomment` , `idaide` , `idcompte` , `datecomment` , `textecomment` )
VALUES (
'', '$idaide', '$internaute->idcompte', '".date("U")."', '$message'
)";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
return "";
}


function delcommentairefaq($idcomment)
{
global $internaute;

	$query = "SELECT *
FROM tabfaqcomment
WHERE idcomment= '$idcomment' ";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	$lauteur=LigneSuivante($run_query);
if($lauteur->idcompte==$internaute->$idcompte || $internaute->authlevel>1)
{

	$query = "DELETE FROM `tabfaqcomment` WHERE `idcomment`='$idcomment'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
}
return "";
}


function modifetatactions($lst,$nouvetat)
{

	$query = "UPDATE cacval SET authachat='$nouvetat',down='$nouvetat' WHERE codesico IN ($lst)";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

return "";
}



function delactions($lst)
{
//il faut obtenir la liste des actions d�tenu, � d�couvert ou non
//ensuite cr�er un ordre de vente ou d'achat ( si vad ou pas )
//modifier la date lasttime et lasttimedown � maintenant
//et executer les ordres d'achats et de vente
//modifier l'�tant de authachat et down pour 0

	$query = "INSERT INTO ordre ( `codesico` , `idcompte` , `datecreation` , `sens` , `nbr` , `pourc` , `tempslim` , `coursmin` , `coursmax` , `etat` )
( SELECT codesico,idcompte,0,'vente',quant,0,UNIX_TIMESTAMP()+5000,0,-1,'1' FROM portef WHERE codesico IN ($lst) AND quant>0)";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

	$query = "INSERT INTO ordre ( `codesico` , `idcompte` , `datecreation` , `sens` , `nbr` , `pourc` , `tempslim` , `coursmin` , `coursmax` , `etat` )
 (SELECT codesico,idcompte,0,'achat',-quant,0,UNIX_TIMESTAMP()+5000,0,-1,'1' FROM portef WHERE codesico IN ($lst) AND quant<0)";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

	$query = "UPDATE cacval SET lasttime=UNIX_TIMESTAMP(),lasttimedown=UNIX_TIMESTAMP() WHERE codesico IN ($lst)";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

	execute_ordre();


	modifetatactions($lst,0);

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

return "";
}


function factoriseactions($lst,$type,$fac,$datedeb,$datefin)
{

  /*`idhisto` int(11) NOT NULL auto_increment,
  `temps` decimal(10,0) NOT NULL default '0',
  `codesico` mediumint(8) unsigned NOT NULL default '0',
  `idcompte` smallint(5) unsigned NOT NULL default '0',
  `sens` enum('Achat','Vente') NOT NULL default 'Achat',
  `nbr` int(11) NOT NULL default '0',
  `valeurunique` double unsigned NOT NULL default '0',
  `taxe` double NOT NULL default '0', */

	if($type=="multiplier") $query = "INSERT INTO historique ( SELECT '',UNIX_TIMESTAMP(),codesico,idcompte,IF(quant>0,'achat','vente'),-quant+(quant*$fac),0,0.001,0 FROM portef WHERE codesico IN ($lst) )";
	if($type=="diviser") $query = "INSERT INTO historique ( SELECT '',UNIX_TIMESTAMP(),codesico,idcompte,IF(quant<0,'achat','vente'),quant-(quant/$fac),0,-0.001,0 FROM portef WHERE codesico IN ($lst) )";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

	if($type=="multiplier") $query = "UPDATE portef SET quant=quant*$fac,ansvaleur=ansvaleur/$fac WHERE codesico IN ($lst)";
	if($type=="diviser") $query = "UPDATE portef SET quant=quant/$fac,ansvaleur=ansvaleur*$fac WHERE codesico IN ($lst)";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

return "";
}

function getnvmessages($idcompte=0)
{  
	global $internaute;
	if($idcompte==0) $idcompte=$internaute->idcompte;
	$query = "SELECT COUNT(*) as nbrmsg
          FROM messages
		  WHERE idcompte='$idcompte' and etat='non lu'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
	$ligne=LigneSuivante($run_query);
	
   return $ligne->nbrmsg;
}

function getnvmessagesenvoye($idcompte=0)
{  
	global $internaute;
	if($idcompte==0) $idcompte=$internaute->idcompte;
	$query = "SELECT COUNT(*) as nbrmsg
          FROM messages
		  WHERE idenvoyeur='$idcompte' and etat='non lu' and ".date("U")."-datemess<=".MAX_MESSAGE_TEMPS*3600;
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
	$ligne=LigneSuivante($run_query);
	
   return $ligne->nbrmsg;
}

function getgroupbyadmin($idcompte)
{  
	$query = "SELECT *
          FROM groupe
		  WHERE idcompte='$idcompte'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
	
   return LigneSuivante($run_query);
}

function getgroupbymembre($idcompte)
{
	$query = "SELECT *
          FROM groupe,membregroupe
		  WHERE groupe.idgroupe=membregroupe.idgroupe and membregroupe.idcompte='$idcompte'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
	
   return LigneSuivante($run_query);
}

function membreestinvite($idcompte,$idgroupe)
{
	$query = "SELECT COUNT(*) AS nb
          FROM invitegroupe
		  WHERE idgroupe='$idgroupe' and idcompte='$idcompte'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   $ligne=LigneSuivante($run_query);
   return $ligne->nb;
}


function getmembrebygroup($idgroupe)
{
	$query = "SELECT *
          FROM compte,membregroupe
		  WHERE compte.idcompte=membregroupe.idcompte and membregroupe.idgroupe='$idgroupe' order by compte.pseudonyme";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

   return $run_query;
}

function getjoueursnotingroupe()
{
	$query = "SELECT idcompte,pseudonyme
          FROM compte
		  WHERE idcompte NOT IN (SELECT idcompte FROM membregroupe) order by pseudonyme ASC";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

   return $run_query;
}


function doinvitejoueur($idjoueur)
{
	global $internaute;
	$groupe=getgroupbymembre($internaute->idcompte);
	$idgroupe=$groupe->idgroupe;
	if( !membreestinvite($idjoueur,$idgroupe) && !estmembregroupe($idjoueur))
	{
		$query = "INSERT INTO `invitegroupe` ( `idgroupe` , `idcompte` ) VALUES ( '$idgroupe', '$idjoueur' )";
		$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
		$run_query =  ExecRequete ($query, $connexion);
	}
}


function delinvitejoueur($idjoueur)
{
	$query = "DELETE FROM `invitegroupe` WHERE `idcompte`='$idjoueur'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
}


function doajgroupe($idcompte,$titregroupe,$diminutif,$url,$description)
{
//on verifie que le joueur n'est pas d�j� membre ou bien maitre d'equipe

if( !estadmingroupe($idcompte) and !estmembregroupe($idcompte))
{
        $ligne=getverifgroupe(0,$idcompte);
        $groupe=LigneSuivante($ligne);

        if( $groupe->idcompte!=$idcompte )
        {
        	$query = "INSERT INTO `verifgroupe` ( `idverifgroupe` , `idgroupe` , `idcompte` , `titregroupe` , `initialgroupe` , `urlsite` , `descriptiongroupe` ) 	VALUES ('', '0', '$idcompte', '$titregroupe', '$diminutif', '$url' , '$description')";
		$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
		$run_query =  ExecRequete ($query, $connexion);
        	return msgtab(lang(193),lang(187));
	}else{
        	return msgtab(lang(208),lang(187));
        }
}else{
        return  msgtab(lang(207),lang(187));
}
}

function dojoingroupe($idgroupe)
{
	//un joueur d�sire rejoindre un groupe
	//il faut tester qu'il soit invit�
	global $internaute;
	if(membreestinvite($internaute->idcompte,$idgroupe)&&!estmembregroupe($internaute->idcompte))
	{
		delinvitejoueur($internaute->idcompte);
		$capital=getscorejoueur($internaute->idcompte);
                $query = " INSERT INTO `membregroupe` ( `idcompte` , `idgroupe` , `datejoint` , `capitalinscr` ) VALUES ( '$internaute->idcompte', '$idgroupe', UNIX_TIMESTAMP( ) , '$capital') ";
		$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
		$run_query =  ExecRequete ($query, $connexion);
                $query = " DELETE FROM `verifgroupe` WHERE `idcompte`='$internaute->idcompte'";
		$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
		$run_query =  ExecRequete ($query, $connexion);
	}else{
		echo msgtab(lang(220),lang(171));
	}
}




function domodifgroupe($idgroupe,$idcompte,$titregroupe,$diminutif,$url,$description)
{

//on verifie si le groupe est deja dans verifgroupe
$ligne=getverifgroupe($idgroupe);
$groupe=LigneSuivante($ligne);

if( $groupe->idgroupe==$idgroupe )
{
//dej� dans la table
$query = "DELETE FROM `verifgroupe` WHERE idgroupe='$idgroupe'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
}
        $query = "INSERT INTO `verifgroupe` ( `idverifgroupe` , `idgroupe` , `idcompte` , `titregroupe` , `initialgroupe` , `urlsite` , `descriptiongroupe` )
VALUES (
'', '$idgroupe', '$idcompte', '$titregroupe', '$diminutif', '$url' , '$description'
)";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
        return lang(194);

}

function getverifgroupe($idgroupe=0,$idcompte=0)
{
$where="";
  if($idgroupe!=0)
        $where=" WHERE idgroupe='$idgroupe'";
  if($idcompte!=0)
        $where=" WHERE idcompte='$idcompte'";

  $query = "SELECT * FROM `verifgroupe` $where ORDER BY idgroupe";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;
}

function sauveipadress($ip)
{
$query = "UPDATE conf set valeur='$ip' WHERE libel='envoyeurip'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);

}

function getiphome()
{
 $query = "SELECT * FROM conf WHERE libel='envoyeurip'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	$ligne=LigneSuivante($run_query);
	return "<iphome>$ligne->valeur</iphome>";
}

function dogroupeaccepterefuse($idverif,$choixadmin,$commentaireadmin)
{

$query = "SELECT * FROM `verifgroupe` WHERE  idverifgroupe='$idverif'";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$groupe=LigneSuivante($run_query);
$player=chercheinternaute($groupe->idcompte,$connexion);
$doing="annul�";

$idcompte=addslashes($groupe->idcompte);
$titregroupe=addslashes($groupe->titregroupe);
$initialgroupe=addslashes($groupe->initialgroupe);
$urlsite=addslashes($groupe->urlsite);
$descriptiongroupe=addslashes($groupe->descriptiongroupe);
$idgroupe=addslashes($groupe->idgroupe);



if($groupe->idgroupe==0)
{
//ajout
	if($choixadmin=="1")// j'ai accept�
	{
                $idforum=forum_newgroupeforum($initialgroupe);
      		$query = "INSERT INTO `groupe` ( `idgroupe` , `idcompte` , `titregroupe` , `initialgroupe` , `urlsite` , `etat` , `descriptiongroupe` , `medor` , `medargent` , `medbronze` , `datecreation`, `idforum` )
		  VALUES (
		  '', '$idcompte', '$titregroupe', '$initialgroupe', '$urlsite', 'inactif', '$descriptiongroupe', '0', '0', '0', UNIX_TIMESTAMP( ), '$idforum')";
		$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
		$run_query =  ExecRequete ($query, $connexion);
      		$query = "INSERT INTO `membregroupe` ( `idcompte` , `idgroupe` , `datejoint`,`capitalinscr` ) VALUES ('$idcompte', '".mysql_insert_id()."', UNIX_TIMESTAMP( ),'".getscorejoueur($idcompte)."')";
		$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
		$run_query =  ExecRequete ($query, $connexion);
 		envoimail($player->email, lang(187),lang(195).lang(196)."\n\n".$commentaireadmin);
		$doing="ajout�";
	}else{
	//refus�, envoie email
                envoimail($player->email, lang(187),lang(195).lang(199).$commentaireadmin);
	}
}else{
//MAJ
	if($choixadmin=="1")// j'ai accept�
	{
		//on verifie si le chef de groupe a chang�, si oui, on regarde si ce chef de groupe n'est pas d�j� dans un groupe
		$autregroupe=getgroupbymembre($groupe->idcompte);
		if($autregroupe->idgroupe==$groupe->idgroupe || $autregroupe==0)
		{
	      		$query = "UPDATE `groupe` SET `idcompte`='$idcompte',`titregroupe`='$titregroupe' , `initialgroupe`='$initialgroupe' , `urlsite`='$urlsite' , `descriptiongroupe`='$descriptiongroupe'
				WHERE idgroupe='$idgroupe'";
			$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
			$run_query =  ExecRequete ($query, $connexion);
	 		envoimail($player->email, lang(188),lang(195).lang(197)."\n\n".$commentaireadmin);
			//si le chef de groupe a chang� alors on
			$doing="modifi�";
		}
	}else{
	//refus�, envoie email
                envoimail($player->email, lang(188),lang(195).lang(200).$commentaireadmin);
	}
}
//supression de verifgroupe
$query = "DELETE FROM `verifgroupe`
		WHERE idverifgroupe='$groupe->idverifgroupe'";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
return msgtab("Groupe a �t� ".$doing.".","Administration des groupes");
}

function effacvieuxscores()
{
global $internaute;
	$query = "DELETE FROM `scores`  WHERE DAY( `datescore` )<>1 and `datescore` < DATE_SUB(CURDATE() , INTERVAL  ".NB_JOUR_GARDER_STAT_JOUEUR." DAY)";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return 0;
}

function getscorejoueur($idcompte)
{
	$query="
	SELECT round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + cashback, 2 ) AS capital
	FROM compte
	LEFT JOIN portef
	USING ( idcompte )
	LEFT JOIN cacval ON cacval.codesico = portef.codesico
	WHERE compte.idcompte='$idcompte'
	GROUP BY compte.idcompte";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
   	$ligne=LigneSuivante($run_query);
   	return $ligne->capital;
}
function get_sicavdown()
{
	$query = "SELECT yahooname
          FROM cacval WHERE down='1'
          ORDER BY codesico";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	$i=0;
	$return="";
	while ( $run_result = mysql_fetch_array($run_query) )
	{   $return[$i++] = $run_result;
	}
	return $return;
}

function doundoallinvitegroupe()
{
global $internaute;
$groupe=getgroupbyadmin($internaute->idcompte);
if($groupe->idgroupe>0)
{
	$query = "DELETE FROM `invitegroupe`  WHERE idgroupe='$groupe->idgroupe'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
}
return msgtab(lang(227),"Administration des groupes");
}


function getinfogroupe($idgroupe)
{
	$query = "SELECT * FROM `groupe`,`compte` WHERE groupe.idgroupe='$idgroupe' and groupe.idcompte=compte.idcompte";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
        $ligne=LigneSuivante($run_query);
	return $ligne;
}

function getcompositionequipe($idgroupe)
{
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$date1 = mktime("01", "01", "01", $mon, "1", $yr);
$ladate=date("Y-m-d",$date1);
$cap=CAPDEB;
$query="SELECT pseudonyme, FROM_UNIXTIME( datejoint, '%d/%m/%Y' ) as dateinscription,membregroupe.capitalinscr as capitalinscr ,round( (
		(
		COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + compte.cashback - COALESCE( scores.capitalscores, $cap) ) / COALESCE( scores.capitalscores, $cap ) ) * 100, 2
		) AS prog, round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + cashback, 2 ) AS capital
		FROM membregroupe, compte
		LEFT JOIN portef
		USING ( idcompte )
		LEFT JOIN scores ON ( compte.idcompte = scores.idcompte
		AND scores.datescore = '$ladate' )
		LEFT JOIN cacval ON cacval.codesico = portef.codesico
		WHERE membregroupe.idcompte = compte.idcompte and membregroupe.idgroupe='$idgroupe'
		GROUP BY compte.idcompte ORDER BY ".tabordre("profilequipe");
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;

}

function checkoutdated()
{
$query="TRUNCATE TABLE warn_old_sicav";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$query="INSERT INTO warn_old_sicav SELECT compte.idcompte,cacval.codesico,CONCAT(CONCAT('".ADDRNT."',CONCAT('?do=junksicav&idcompte=',CONCAT(compte.idcompte,'&checkstr='))),
CONCAT(md5(CONCAT(compte.idcompte,dateinscr)),'&codesico=',cacval.codesico)) as link FROM compte,cacval,portef WHERE compte.idcompte=portef.idcompte and
 portef.codesico=cacval.codesico and compte.dateactivite>UNIX_TIMESTAMP()-(24*3600)  and lasttime<UNIX_TIMESTAMP()-".strval(CONSIDERER_OUTDATED_SICAV*3600*24);
$run_query =  ExecRequete ($query, $connexion);
}


function majclassement()
{

$mois=date("Y-m-d");

  list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$date1 = mktime("01", "01", "01", $mon, "1", $yr);
$ladate=date("Y-m-d",$date1);

list($yr,$mon,$jour) = explode("-",$mois);
$date2 = mktime("01", "01", "01", $mon+1, "1", $yr);
$ladateap=date("Y-m-d",$date2);

list($yr,$mon,$jour) = explode("-",$mois);
$date3 = mktime("01", "01", "01", $mon, "1", $yr);
$ladatesel=date("Y-m-d",$date3);
$query = "TRUNCATE TABLE `statsclassement`";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
$query = "INSERT INTO statsclassement
SELECT pseudonyme, round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + cashback, 2 ) AS capital, round( (
(
COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + compte.cashback - COALESCE( scores.capitalscores, ".CAPDEB." ) ) / COALESCE( scores.capitalscores, ".CAPDEB." ) ) * 100, 2
) AS prog,compte.idcompte as idcompte
FROM compte
LEFT JOIN portef
USING ( idcompte )
LEFT  JOIN scores
ON (compte.idcompte=scores.idcompte AND scores.datescore = '$ladate')
LEFT JOIN cacval ON cacval.codesico = portef.codesico
WHERE authlevel = '1' and cashback<>'".CAPDEB."'
GROUP BY compte.idcompte";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
}

function majlistmoisclass()
{
/*
$query = "DROP TABLE IF EXISTS `listmoisclass`";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
*/

$query = "TRUNCATE TABLE `listmoisclass`";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$query = "INSERT INTO listmoisclass SELECT datescore
FROM `scores`
WHERE RIGHT( `datescore` , 2 ) = '01'
GROUP BY datescore
ORDER BY datescore DESC";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);


}

function istableexist($nomtable)
{
$query = "SHOW TABLES LIKE '$nomtable'";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
if(mysql_num_rows($run_query)==1)
	return true;
else
	return false;
}

function forumsyncquantity($idforum)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="UPDATE `f_forum` frm SET `nbmessages`=0,`nbsujets`=0 WHERE frm.idforum='$idforum'";
$run_query =  ExecRequete ($query, $connexion);
$query="UPDATE `f_sujet` fs SET `s_nbmessages`=(SELECT COUNT(*) FROM f_message fm WHERE fm.idsujet=fs.idsujet)-1 WHERE fs.idforum='$idforum'";
$run_query =  ExecRequete ($query, $connexion);
$query="UPDATE `f_forum` frm SET `nbmessages`=(SELECT SUM(`s_nbmessages`+1) FROM f_sujet fs WHERE fs.idforum=frm.idforum),`nbsujets`=(SELECT COUNT(*) FROM f_sujet fs WHERE fs.idforum=frm.idforum) WHERE frm.idforum='$idforum'";
$run_query =  ExecRequete ($query, $connexion);
}

function forumsyncidlastmessage($idforum)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="UPDATE f_forum ff SET idlastmessage=0 WHERE ff.idforum='$idforum'";
$run_query =  ExecRequete ($query, $connexion);
$query="UPDATE f_sujet fsu SET idlastmessage=(SELECT MAX(fm.idmessage) FROM f_message fm WHERE fm.idsujet=fsu.idsujet) WHERE fsu.idforum='$idforum'";
$run_query =  ExecRequete ($query, $connexion);
$query="UPDATE f_forum ff SET idlastmessage=(SELECT MAX(fs.idlastmessage) FROM f_sujet fs WHERE fs.idforum=ff.idforum) WHERE ff.idforum='$idforum'";
$run_query =  ExecRequete ($query, $connexion);
}

function getinfojoueur($idjoueur)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="SELECT * FROM compte where idcompte='$idjoueur'";
$run_query =  ExecRequete ($query, $connexion);
return LigneSuivante($run_query);
}


function getinfosicav($idsicav)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="SELECT * FROM cacval where codesico='$idsicav'";
$run_query =  ExecRequete ($query, $connexion);
return LigneSuivante($run_query);
}

function forum_getidmessagesujet($idsujet) //donner l'id 1er message du sujet
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="SELECT idmessage FROM `f_message` where idsujet='$idsujet' ORDER BY idmessage ASC LIMIT 0,1";
$run_query =  ExecRequete ($query, $connexion);
$ligne=LigneSuivante($run_query);
return $ligne->idmessage;
}


function forum_getlastmessagesujet($idsujet)//donner le dernier message du sujet
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="SELECT * FROM `f_message` where idsujet='$idsujet' ORDER BY idmessage DESC LIMIT 0,1";
$run_query =  ExecRequete ($query, $connexion);
return LigneSuivante($run_query);
}

function forum_peutposter($idcompte,$idforum)
{
$infoforum=get_infoforum($idforum);
$infojoueur=getinfojoueur($idcompte);
if($infojoueur->authlevel>1)
	return true;
if($infojoueur->idcompte==IDCOMPTEDEMO)
	return false;
if($infoforum->authwrite=="groupe")
{
        $infogroupe=getgroupbymembre($idcompte);
	return $infogroupe->idforum==$idforum;
}elseif($infoforum->authwrite=="identifie"){
	return $infojoueur->authlevel>=1;
}else{
	return false;
}
}
function forum_peutlire($idcompte,$idforum)
{
$infoforum=get_infoforum($idforum);
$infojoueur=getinfojoueur($idcompte);
if($infojoueur->authlevel>1)
	return true;
if($infoforum->authread=="groupe")
{
        $infogroupe=getgroupbymembre($idcompte);
	return $infogroupe->idforum==$idforum;
}elseif($infoforum->authread=="identifie"){
	return true;
}elseif($infoforum->authread=="ouvert"){
	return true;
}else{
	return false;
}
}


function setsujetlu($idsujet)
{
global $internaute;
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="INSERT IGNORE INTO `f_readsujet` ( `idsujet` , `idcompte` ) VALUES ( '$idsujet', '$internaute->idcompte')";
$run_query =  ExecRequete ($query, $connexion);
$infosujet=get_infosujet($idsujet);
$query="INSERT IGNORE INTO `f_readforum` ( `idforum` , `idcompte` ) VALUES ('$infosujet->idforum', '$internaute->idcompte')";
$run_query =  ExecRequete ($query, $connexion);
}


function forum_inc_nblectures($idsujet)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="UPDATE `f_sujet` SET `nblectures` = `nblectures`+1 WHERE `idsujet` = '$idsujet' ";
$run_query =  ExecRequete ($query, $connexion);
}


function forum_inc_joueur_nbposts($idjoueur)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="UPDATE `compte` SET `nbpostforum` = `nbpostforum`+1 WHERE `idcompte` = '$idjoueur' ";
$run_query =  ExecRequete ($query, $connexion);
}


function forum_set_joueur_toutlu($idjoueur,$date)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="UPDATE `compte` SET `toutvuforum` = '$date' WHERE `idcompte` = '$idjoueur' ";
$run_query =  ExecRequete ($query, $connexion);
}


function forum_majtoutvuforum($idjoueur)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="SELECT dateactivite FROM compte where idcompte='$idjoueur'";
$run_query =  ExecRequete ($query, $connexion);
$ligne=LigneSuivante($run_query);
$lastactivite=$ligne->dateactivite;
if($ligne->dateactivite<date("U")-SEC_JOUEUR_CONSIDERER_FORUM_TOUTLU) //si ca fait x temps qu'il s'est pas connect�
{
        forum_set_joueur_toutlu($idjoueur,$lastactivite);
	$query="DELETE FROM `f_readsujet` where idcompte='$idjoueur'";
	$run_query =  ExecRequete ($query, $connexion);
}
}

function forum_ajoutforum($idsection , $nomforum , $descriptionforum , $authread , $authwrite)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="INSERT INTO `f_forum` ( `idsection` , `nomforum` , `descriptionforum` , `nbsujets` , `nbmessages` , `idlastmessage` , `authread` , `authwrite` )
VALUES ( '$idsection', '$nomforum', '$descriptionforum', '0', '0', '0', '$authread', '$authwrite')";
$run_query =  ExecRequete ($query, $connexion);
return mysql_insert_id($connexion);
}

function forum_newgroupeforum($nomggroupe)
{
 return forum_ajoutforum(0 , "Forum $nomggroupe" , "Forum du groupe $nomggroupe" , 'groupe' , 'groupe');
}

function setsujetpaslu($idsujet)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="DELETE FROM `f_readsujet` WHERE `idsujet`='$idsujet'";
$run_query =  ExecRequete ($query, $connexion);
$infosujet=get_infosujet($idsujet);
$query="DELETE FROM `f_readforum` WHERE `idforum`='$infosujet->idforum'";
$run_query =  ExecRequete ($query, $connexion);
}



function doforum_postmessage($sujet,$corps,$idforum,$idsujet=0,$edit=0,$idmessage=0)
{
global $internaute;
//Verification des droits
$infoforum=get_infoforum($idforum);

if(!forum_peutposter($internaute->idcompte,$idforum))
	return lang(259);
if(getcptpost()>date("U")-INTERVAL_POST_FORUM)
	return msgtab(lang(263),lang(262));
$nouvsujet=false;
if($idsujet==0)
{
	$nouvsujet=true;
	if(strlen(trim($sujet))==0)
		return msgtab(lang(267),lang(256)).forum_postmessage($idforum,$idsujet,0,$corps);
}else{
	$infosujet=get_infosujet($idsujet);
	if($infosujet->idforum!=$idforum)
  		return lang(259);
}


$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
if($edit)
{
	$mess=get_infomessage($idmessage);
	if(!forum_peut_editer($mess,$infoforum))
		return "";
	$idmesssujet=forum_getidmessagesujet($idsujet);
	if($idmesssujet==$idmessage)
	{
		$query="UPDATE `f_sujet` SET `txtsujet` = '$sujet' WHERE `idsujet` = '$idsujet' ";
		$run_query =  ExecRequete ($query, $connexion);
	}
	$query="UPDATE `f_corps` SET `contenu` = '$corps' WHERE `idmessage` = '$idmessage'";
	$run_query =  ExecRequete ($query, $connexion);
        $corptab=lang(269)."<br><br>".html_lien(lang(265),"do=showlstsujets&idforum=$idforum")."<br><br>".html_lien(lang(266),"do=showlstposts&idsujet=$idsujet&last=1#last");
}else{
	if($nouvsujet)
	{
		$query="INSERT INTO `f_sujet`
	( `idforum` , `idcompteauteur` , `s_nbmessages` , `txtsujet` , `idlastmessage` , `nblectures` )
	VALUES ( '$idforum', '$internaute->idcompte', '0', '$sujet', '0', '0')";
		$run_query =  ExecRequete ($query, $connexion);
		$idsujet=mysql_insert_id($connexion);
	}

	setsujetpaslu($idsujet);
	$query="INSERT INTO `f_message` (`idsujet`, `datepost`, `idcompte`) VALUES ('$idsujet', UNIX_TIMESTAMP(), '$internaute->idcompte')";
	$run_query =  ExecRequete ($query, $connexion);
	$nummess=mysql_insert_id($connexion);

	$query="INSERT INTO `f_corps` ( `idmessage` , `contenu` ) VALUES ('$nummess', '$corps')";
	$run_query =  ExecRequete ($query, $connexion);

	$query="UPDATE f_forum ff SET idlastmessage='$nummess'".retiftrue(",`nbsujets`=`nbsujets`+1",$nouvsujet).",`nbmessages`=`nbmessages`+1 WHERE ff.idforum='$idforum'";
	$run_query =  ExecRequete ($query, $connexion);
	$query="UPDATE f_sujet fsu SET idlastmessage='$nummess'".retiftrue(",`s_nbmessages`=`s_nbmessages`+1",!$nouvsujet)." WHERE fsu.idsujet='$idsujet'";
	$run_query =  ExecRequete ($query, $connexion);
	updatecptpost();
        forum_inc_joueur_nbposts($internaute->idcompte);
	$corptab=lang(264)."<br><br>".html_lien(lang(265),"do=showlstsujets&idforum=$idforum")."<br><br>".html_lien(lang(266),"do=showlstposts&idsujet=$idsujet&last=1#last");
}
 return msgtab($corptab,lang(171));
}


function forum_giveforumtogroups()
{
//fonction admin pour la maj
	$query = "SELECT * FROM groupe WHERE idforum='0'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
	while($ligne=LigneSuivante($run_query))
	{
                $idforum=forum_newgroupeforum(addslashes($ligne->initialgroupe));
		$query = "UPDATE groupe SET `idforum`='$idforum' WHERE idgroupe='$ligne->idgroupe'";
		ExecRequete ($query, $connexion);
	}
}

function incarnerjoueur($idcomptejoueur)
{
	global $internaute;
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$query="UPDATE `Session` SET `idcompte` = '$idcomptejoueur' WHERE `idcompte` = '$internaute->idcompte'";
	$run_query =  ExecRequete ($query, $connexion);
}


function deactivateweekstats($idjoueur)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="UPDATE compte SET `mailweekly` = '0' where idcompte='$idjoueur'";
$run_query =  ExecRequete ($query, $connexion);
return;
}

function deactivatedaystats($idjoueur)
{
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$query="UPDATE compte SET `maildaily` = '0' where idcompte='$idjoueur'";
$run_query =  ExecRequete ($query, $connexion);
return;
}

?>
