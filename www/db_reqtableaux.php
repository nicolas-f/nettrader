<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
function get_messagelist($debligne,$nbligne,$idcompte)
{
global $internaute;
$letimestamp=get_refresh();
$datesql=$letimestamp->datesql;
$datedown=$letimestamp->datedown;
	$query = " SELECT compte.pseudonyme,messages.*
FROM messages LEFT JOIN compte ON messages.idenvoyeur = compte.idcompte
WHERE messages.idcompte='$idcompte'
ORDER BY messages.idcompte,datemess DESC LIMIT $debligne,$nbligne";
	//echo $query;
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$i++] = $run_result;
   }
return $return;
}

function get_messagelistenvoye($idcompte)
{
global $internaute;
	$query = " SELECT compte.pseudonyme,messages.*
FROM messages LEFT JOIN compte ON messages.idcompte = compte.idcompte
WHERE messages.idenvoyeur='$idcompte' and etat='non lu'
ORDER BY messages.idcompte,datemess DESC";
	//echo $query;
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$i++] = $run_result;
   }
return $return;
}

function get_playerconnected()
{
	$query = "SELECT compte.pseudonyme AS Pseudo
FROM compte,Session
WHERE compte.idcompte = Session.idcompte AND tempsconnect>UNIX_TIMESTAMP() - 305 and compte.authlevel='1'
GROUP BY pseudonyme
ORDER BY tempsconnect DESC";
	//echo $query;
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);	
   $i=0;
   $return="";
   while ( $run_result = mysql_fetch_array($run_query) )
   {   $return[$i++] = $run_result;
   }
return $return;

}

function get_oldplayer()
{
	$query = "SELECT compte.idcompte as id,compte.pseudonyme AS pseudo,dateactivite as seclast,IF(dateactivite>0,FROM_UNIXTIME(dateactivite),'') as lastconnect,FROM_UNIXTIME(dateinscr) as 'dateinscrfrm'
FROM compte
WHERE cashback<>10000 AND dateactivite<UNIX_TIMESTAMP()-3600*24*30*2 AND ( dateactivite > 0 or (dateactivite=0 AND dateinscr<UNIX_TIMESTAMP()-3600*24*30))
ORDER BY dateactivite ASC";
	//echo $query;
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;

}

function get_players()
{
	$query = "SELECT *
FROM compte
WHERE dateactivite<UNIX_TIMESTAMP()-3600*24*30*2
ORDER BY authlevel DESC,pseudonyme ASC";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;

}

function get_sicavcat($lstactions)
{
global $internaute;
$aj="";
$ajfrom="cacval, secteurent, portef
	WHERE  cacval.codesico = portef.codesico AND";
if($lstactions<>"") $aj = "AND cacval.codesico IN ($lstactions)";

$trispecial=0;
if(array_key_exists ("champ",$_GET)) if($_GET["champ"]=="part" OR $_GET["champ"]=="partjoueur") $trispecial=1;
if(array_key_exists ("champ",$_GET)) if($_GET["champ"]=="partjoueur") $ajfrom="secteurent, cacval LEFT JOIN portef ON (cacval.codesico=portef.codesico AND portef.idcompte = '$internaute->idcompte') WHERE ";
if(!$trispecial)
{
	$query = "SELECT libellesecteur, codesico, nom, valeur, yahooname
	FROM cacval, secteurent
	WHERE cacval.idsecteur = secteurent.idsecteur
	AND authachat = '1'
	AND down = '1' $aj
	ORDER BY ".tabordre("lstactions");
}else{

	$query = "SELECT libellesecteur, cacval.codesico, nom, valeur, yahooname, SUM( portef.quant * cacval.valeur ) AS part
	FROM $ajfrom cacval.idsecteur = secteurent.idsecteur
	AND authachat = '1'
	AND down = '1' $aj AND portef.quant>0
	GROUP BY cacval.codesico
	ORDER BY ".tabordre("lstactions");

}

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;
}

function get_acttotpossede($lstactions="") // par action le volume en � poss�d� par tout les joueurs
{
$aj="";
if($lstactions<>"") $aj = "AND portef.codesico IN ($lstactions)";
$query="SELECT cacval.codesico as 'codesico',SUM(portef.quant * cacval.valeur) AS Valeur
 FROM cacval,portef
 WHERE cacval.codesico = portef.codesico AND portef.quant>0 AND cacval.authachat='1' $aj
 GROUP BY cacval.nom";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);
$tot=0;
$tab=array();
while($ligne=LigneSuivante($run_query))
{
	$tab[$ligne->codesico]=$ligne->Valeur;
	$tot+=$ligne->Valeur;
}
$tot2=0;
foreach($tab as $k => $v)
{
	if($tot)
    	$tab[$k]=round(($v/$tot)*100,2);
	else
        $tab[$k]=0;
	$tot2+=round(($v/$tot)*100,2);
}
return $tab;
}

function getnbstats()
{
$query = "SELECT COUNT(*) AS nb
FROM reqlistpublic
WHERE 1";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
$lst=LigneSuivante($run_query);
return $lst->nb;
}

function exepublicreq($idreq)
{
$idreq=sec($idreq);
$query = "SELECT *
FROM reqlistpublic
WHERE idreq='$idreq'";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
if($run_query<>"")
{
	$resultat = LigneSuivante($run_query);
		if($resultat->req<>"")
		{
			$sql = "DROP TABLE IF EXISTS `stats`";
			$run_query = ExecRequete ($sql, $connexion);
			$query = stripslashes($resultat->req);
			$sql = "CREATE TABLE `stats` $query";
			$run_query = ExecRequete ($sql, $connexion);
			$titre=addslashes($resultat->libelreq);
			$sql = "UPDATE conf set valeur='$titre' where libel='lastmajstattitle'";
			$run_query = ExecRequete ($sql, $connexion);
		}else{
			return "";
		}
}else{
	return "Erreur dans l'id (contacter nicolas)";
}

return $retour;
}


function exeanspublicreq()
{
$query = "SELECT `valeur`
FROM `conf`
WHERE `libel` = 'lastmajstattitle'";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
if($run_query<>"")
{
	$resultat = LigneSuivante($run_query);
	$retour->titre=$resultat->valeur;
	$query = "SELECT * FROM `stats` WHERE 1";
	$run_query = ExecRequete ($query, $connexion);
}else{
	return "Erreur dans l'id (contacter nicolas)";
}
$retour->req=$run_query;
return $retour;
}

function get_listeaide()
{
	$query = "SELECT *,COUNT(idcomment) as nbcomment,tabaide.idaide as idligne
FROM chapaide,tabaide LEFT JOIN tabaidecomment ON ( tabaide.idaide = tabaidecomment.idaide )
WHERE tabaide.idchapaide = chapaide.idchapaide
GROUP BY tabaide.idaide
ORDER BY chapaide.idchapaide ASC,tabaide.idaide ASC
";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;

}

function get_listecomment($idaide)
{
	$query = "SELECT *,compte.idcompte as auteurid
FROM tabaidecomment,compte
WHERE tabaidecomment.idaide = '$idaide' and tabaidecomment.idcompte = compte.idcompte
ORDER BY datecomment DESC
";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;
}


function get_listefaq()
{
	$query = "SELECT *,COUNT(idcomment) as nbcomment,tabfaq.idaide as idligne,tabfaq.idaide as lnkaide
FROM tabfaq LEFT JOIN tabfaqcomment ON ( tabfaq.idaide = tabfaqcomment.idaide )
GROUP BY tabfaq.idaide
ORDER BY tabfaq.idaide ASC
";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;

}

function get_listecommentfaq($idaide)
{
	$query = "SELECT *,compte.idcompte as auteurid
FROM tabfaqcomment,compte
WHERE tabfaqcomment.idaide = '$idaide' and tabfaqcomment.idcompte = compte.idcompte
ORDER BY datecomment DESC
";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;
}

function get_lstactions()
{
	$query = "SELECT *
FROM cacval
ORDER BY nom ASC
";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;
}


function get_listeforums()
{
global $internaute;
$add="forum.idsection!=0";
$add2="";
if(estmembregroupe($internaute->idcompte))
{
	$legroupe=getgroupbymembre($internaute->idcompte);
	$add.=" or forum.idforum=$legroupe->idforum";
}
if($internaute->authlevel>1)
{       $add="forum.idsection!=0 or (rf.idcompte is Null and mess.datepost>$internaute->toutvuforum) ";
}
	$query = "SELECT *,IF(rf.idcompte,0,1) as notif_new ,forum.idforum as frmid
FROM `f_forum` forum
LEFT JOIN `f_section` section ON (forum.idsection=section.idsection)
LEFT JOIN `f_message` mess ON (mess.idmessage=forum.idlastmessage)
LEFT JOIN `compte` cpt ON (mess.idcompte=cpt.idcompte)
LEFT JOIN `f_sujet` sujet ON (mess.idsujet=sujet.idsujet)
LEFT JOIN `f_readforum` rf ON (rf.idcompte='$internaute->idcompte' and rf.idforum=forum.idforum)
 WHERE $add  $add2 order by forum.idsection,forum.idforum
";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;
}

function get_listesujets($idforum,$de,$juska)
{
global $internaute;
	$query = "SELECT *,cauteur.pseudonyme as pseudoauteur,clast.pseudonyme as lastpseudo, IF(rs.idcompte,0,1) as notif_new,fs.idsujet as numsujet
FROM  `f_forum` ff, `f_message` fm, `compte` clast,`compte` cauteur,`f_sujet` fs LEFT JOIN `f_readsujet` rs ON (rs.idcompte='$internaute->idcompte' and rs.idsujet=fs.idsujet)
WHERE fs.idlastmessage = fm.idmessage and fs.idcompteauteur=cauteur.idcompte and fm.idcompte=clast.idcompte
AND fs.idforum = ff.idforum and fs.idforum='$idforum'
ORDER BY fs.idlastmessage DESC LIMIT $de,$juska
";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;
}

function get_infoforum($idforum)
{
global $internaute;
	$query = "SELECT * FROM `f_forum` WHERE idforum='$idforum'";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return LigneSuivante($run_query);
}


function get_listemessages($idsujet,$de,$juska)
{
global $internaute;
	$query = "SELECT *,cpt.pseudonyme as auteur
FROM `f_message` fm, `f_corps` fc, `compte` cpt
 LEFT JOIN `membregroupe` grpmbr USING(idcompte)
 LEFT JOIN groupe grp USING(idgroupe)
 LEFT JOIN statsclassement stats ON (cpt.idcompte=stats.idcompte)
WHERE fm.idmessage = fc.idmessage
AND fm.idsujet = '$idsujet'
AND fm.idcompte=cpt.idcompte
LIMIT $de,$juska
";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return $run_query;
}

function get_infosujet($idsujet)
{
global $internaute;
	$query = "SELECT * FROM `f_forum` ff,`f_sujet` fs WHERE ff.idforum=fs.idforum and fs.idsujet='$idsujet'";

	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return LigneSuivante($run_query);
}

function get_infomessage($idmessage)
{
global $internaute;
	$query = "SELECT * FROM `f_message` fm,`f_corps` fc WHERE fm.idmessage=fc.idmessage and fm.idmessage='$idmessage'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
return LigneSuivante($run_query);
}
?>
