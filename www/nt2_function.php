<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
// FONCTION GETVALEUR SICO ############################################################################
// FONCTION GETVALEUR SICO ############################################################################
// FONCTION GETVALEUR SICO ############################################################################
// FONCTION GETVALEUR SICO ############################################################################

function tabvaleurouzero($tableau,$valeur)
{
if(array_key_exists($valeur,$tableau))
	return $tableau[$valeur];
else
	return 0;

}



function affichpseudo($idcompte,$pseudo)
{
// return "<a href=\"index.php?do=affichfichejoueur&idjoueur=$idcompte\">$pseudo</a>"; //possibilit� de colorer s'il fait partie du meilleur groupe du jeu
	return $pseudo;
}

function affichgroupe($idgroupe,$nomgroupe)
{
// return "<a href=\"index.php?do=affichfichegroupe&idequipe=$idgroupe\">$nomgroupe</a>"; //possibilit� de mettre le nombre de m�daille � droite du nom du groupe
	return $nomgroupe;
}

function msgtab($message,$titre)
{
$html="<br>".opentab("align=\"center\" width=\"90%\"").openligne("","titre").opencol().$titre.closecol().closeligne().openligne().opencol().$message.closecol().closeligne().closetab()."<br>";


return $html;
}

function compareclass($nom1,$nom2)
{
if(strtoupper($nom1)==strtoupper($nom2))
	return true;
else
	return false;
}
function sign($val)
{
	if($val<>0) return($val/abs($val));
	else    return 0;
}


function getvaleur($sico,$nouv=0) //$nouv = 1 si action � ajouter
{   

$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$resultat=ExecRequete("SELECT valeur FROM cacval WHERE codesico = '$sico'",$connexion);
$lastval = 0;
while($r=mysql_fetch_array($resultat))
{
	$lastval=$r["valeur"];
	return $lastval;
}

}







function traiteeuronextcsv($lines)
{
	if(!tempsjeu())
	{
		return "";
	}

	$maintenant = date ("U");
	$patterns[0] = "/'/";
	$patterns[1] = "/\"/";
	$patterns[2] = "/\\\"/";
	$replacements[0] = "\'";
	$replacements[1] = "";
	$replacements[2] = "";
	$update=0;
	$insert=0;
	list($chour, $cmin, $csec, $cday, $cmon, $cyr) = explode(" ",date("H i s d m y"));
	//print "il y a ".count ($lines)." lignes<br>";

        //on charge maintenant le tableau sql en tableau array
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$resultat=ExecRequete("SELECT * FROM cacval WHERE down='1' ORDER BY codesico ASC",$connexion);
	$stat="";
	$destination=array();
	$stat=array();
	while($r=mysql_fetch_array($resultat))
	{
		$destination[$r["yahooname"]] = array("valeur" => $r["valeur"], "unixtime" => $r["lasttime"]);
		$stat[$r["yahooname"]] = array("codesico" => $r["codesico"] ,"lasttime" => $r["lasttime"], "lasttimedown" => $r["lasttimedown"]);
	}


	for ( $i = 0; $i < count ($lines) ; $i++ )
	{
			if (ereg ("([^;]*);([^;]*);([^;]*);([^;]*);([^;]*);([^;]*);([^;]*);([^;]*);([^;]*);([^;]*);([0-9]{2})/([0-9]{2})/([0-9]{2}) ([0-9]{1,2}):([0-9]{1,2});([^;]*);([^;]*);([*]*)", preg_replace($patterns,$replacements,$lines[$i]), $regs))
			{
				$heure=$regs[14];
				$minute=$regs[15];
				$annee="20".$regs[13];
				$mois=$regs[12];
				$jour=$regs[11];
				$valeur=$regs[8];
				$code=$regs[2];
				$volume=$regs[9];
				$progressionjour=$regs[10];
				$nom=$regs[1];
				$unixtime = mktime($heure,$minute, 0, $mois, $jour, $annee);
                                $valaction=floatval($valeur);
                                if(array_key_exists ( $code, $destination) && $valaction>=2.00)
				{

					//Mise � jour
					if( $destination[$code]["unixtime"]!=$unixtime )
					{

						if(floatval($destination[$code]["valeur"])>0 && $valaction>0 && abs($valaction-floatval($destination[$code]["valeur"]))/floatval($destination[$code]["valeur"])>=.75)
						{
							$corps="L'action $nom (".$stat[$code]["codesico"]." a chang� de + de 75% (de ".strval($destination[$code]["valeur"])." � ".strval($valaction)." ), aller sur la page d'admin pour r�activer si il n'y a pas de multiplication ou division d'action. ";
		                                        envoimail(EMAILADMIN,"NetTrader, valeur se modifie de 75% !",$corps);
		                    			$resultat=ExecRequete("UPDATE cacval SET down='0' WHERE yahooname='$code'",$connexion);
						}
						$resultat=ExecRequete("UPDATE cacval SET valeur='$valeur', lasttime='$unixtime', lasttimedown='$maintenant' WHERE  yahooname='$code'",$connexion);
                                                $update++;
					}
				}else{
					if($valaction>=2.00)
					{
						//Ajout d'une nouvelle action
						$corps="L'action $nom a �t� ajout�e\n Valeur:$valeur";
	                                        envoimail(EMAILADMIN,"Nouvelle action disponible !",$corps);
						$resultat=ExecRequete("INSERT INTO `cacval` ( `codesico` , `yahooname` , `nom` , `valeur` , `lasttime` , `lasttimedown` , `authachat` , `down` , `idsecteur` ) VALUES ( '', '$code', '$nom', '$valeur', '$unixtime', '0', '1', '1', '22')",$connexion);
						$insert++;
					}
				}

			}
	}
	return  count ($lines)." t�l�charg�s  , $update mis � jour et $insert ajout�es";
}



 function traiteyahoocsv($lines)
{
	if(!tempsjeu())
	{
		return "";
	}

	$maintenant = date ("U");
	$patterns[0] = "/'/";
	$patterns[1] = "/\"/";
	$patterns[2] = "/\\\"/";
	$replacements[0] = "\'";
	$replacements[1] = "";
	$replacements[2] = "";
	$update=0;
	$insert=0;
	list($chour, $cmin, $csec, $cday, $cmon, $cyr) = explode(" ",date("H i s d m y"));
	//print "il y a ".count ($lines)." lignes<br>";

        //on charge maintenant le tableau sql en tableau array
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$resultat=ExecRequete("SELECT * FROM cacval WHERE down='1' ORDER BY codesico ASC",$connexion);
	$stat="";
	$destination=array();
	$stat=array();
	while($r=mysql_fetch_array($resultat))
	{
		$destination[$r["yahooname"]] = array("valeur" => $r["valeur"], "unixtime" => $r["lasttime"]);
		$stat[$r["yahooname"]] = array("codesico" => $r["codesico"] ,"lasttime" => $r["lasttime"], "lasttimedown" => $r["lasttimedown"]);
	}


	for ( $i = 0; $i < count ($lines) ; $i++ )
	{               //	    AC    . PA       ;ACCOR  ; 66,83; 17          h 37         ; 17         /10          /2007      ;-0,49  ;67,59  ;68,00  ;66,51  ;1115072
			//          1       2         3        4      5             6            7           8            9          10      11      12      13      14
			if (ereg ("([^.]*).([A-Z]{2});([^;]*);([^;]*);([0-9]{1,2})h([0-9]{1,2});([0-9]{1,2})/([0-9]{1,2})/([0-9]{4});([^;]*);([^;]*);([^;]*);([^;]*);([0-9]*)", preg_replace($patterns,$replacements,$lines[$i]), $regs))
			{

				$code=$regs[1].".".$regs[2];
				$nom=$regs[3];
				$valeur=ereg_replace (",",".",$regs[4]);
				$heure=$regs[5];
				$minute=$regs[6];
				$jour=$regs[7];
				$mois=$regs[8];
				$annee=$regs[9];
				$progressionjour=$regs[10];
				$volume=$regs[14];
				$unixtime = mktime($heure,$minute, 0, $mois, $jour, $annee);
                                $valaction=floatval($valeur);
                                if(array_key_exists ( $code, $destination) && $valaction>=2.00)
				{

					//Mise � jour
					if( $destination[$code]["unixtime"]!=$unixtime )
					{

						if(floatval($destination[$code]["valeur"])>0 && $valaction>0 && abs($valaction-floatval($destination[$code]["valeur"]))/floatval($destination[$code]["valeur"])>=.25)
						{
							$corps="L'action $nom (".$stat[$code]["codesico"]." a chang� de + de 25% (de ".strval($destination[$code]["valeur"])." � ".strval($valaction)." ), aller sur la page d'admin pour r�activer si il n'y a pas de multiplication ou division d'action. ";
		                                        envoimail(EMAILADMIN,"NetTrader, valeur se modifie de 25% !",$corps);
		                    			$resultat=ExecRequete("UPDATE cacval SET down='0' WHERE yahooname='$code'",$connexion);
						}
						$resultat=ExecRequete("UPDATE cacval SET valeur='$valeur', lasttime='$unixtime', lasttimedown='$maintenant' WHERE  yahooname='$code'",$connexion);
                                                $update++;
					}
				}else{
					if($valaction>=2.00)
					{
						//Ajout d'une nouvelle action
						$corps="L'action $nom a �t� ajout�e\n Valeur:$valeur";
	                                        envoimail(EMAILADMIN,"Nouvelle action disponible !",$corps);
						$index=rand(1,32768);
						$resultat=ExecRequete("INSERT INTO `cacval` ( `codesico` , `yahooname` , `nom` , `valeur` , `lasttime` , `lasttimedown` , `authachat` , `down` , `idsecteur` ) VALUES ( '$index', '$code', '$nom', '$valeur', '$unixtime', '0', '1', '1', '22')",$connexion);
						$insert++;
					}
				}

			}
	}
	return  count ($lines)." t�l�charg�s  , $update mis � jour et $insert ajout�es";
}






function traitehtmlsicav($lines,$sico = 0)
{
if(!tempsjeu())
{
	return "";
}
if($sico>0)
{
	$sico=getyahooname($sico);
}
$maintenant = date ("U");
$patterns[0] = "/'/";
$patterns[1] = "/\"/";
$patterns[2] = "/\\\"/";
$replacements[0] = "\'";
$replacements[1] = "";
$replacements[2] = "";
list($chour, $cmin, $csec, $cday, $cmon, $cyr) = explode(" ",date("H i s d m y"));
//print "il y a ".count ($lines)." lignes<br>";
for ( $i = 0; $i < count ($lines) ; $i++ )
{
	//on parcourt les lignes, on stocke les info(code,valeur,an,mois,jour,heure,minute) dans une table, puis on classe par code sico
	// 12040.PA;ACCOR;34,60;4/19/2004;10h49;-0,49;35,12;35,12;34,53;103592	
	//   if (ereg (\"([^;]*).([A-Z]{2});([^;]*);([0-9]*[,]{1}[0-9]*);([0-9]{1,2})/([0-9]{1,2})/([0-9]{4});([0-9]{1,2})h([0-9]{1,2})", $lines[$i], $regs))
	//ereg ("([^.]*).([A-Z]{2}),([^,]*),([^,]*), ([0-9]{1,2}):([0-9]{1,2})", preg_replace($patterns,$replacements,$lines[$i]), $regs))
		//print $lines[$i]."<br>";
		if (ereg ("([^.]*).([A-Z]{2}),([^,]*),([^,]*),([^0-9]*)([0-9]{1,2}):([0-9]{1,2})([A-Z]{2})", preg_replace($patterns,$replacements,$lines[$i]), $regs))
		{

			$sourcecode=$regs[1].".".$regs[2];
			//print_r($regs);
			//print $regs[3]."<br>";
			$valeur=ereg_replace (",",".",$regs[4]);
			$heure=$regs[6]+6;
			$minute=$regs[7];
			if($regs[8]=="PM")
				$heure+=12;
			//echo "[$heure:$minute] ";
			//echo "<br>$sourcecode : $jour<br>";
			$unixtime = mktime($heure,$minute, 0, $cmon, $cday, $cyr);
			$source[$sourcecode] = array("valeur" => $valeur, "unixtime" => $unixtime);
		}
}
//on trie le tableau source
ksort($source,0);
//print_r($source);
//on charge maintenant le tableau sql en tableau array
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$resultat=ExecRequete("SELECT * FROM cacval WHERE down='1' ORDER BY codesico ASC",$connexion);
$stat="";
while($r=mysql_fetch_array($resultat))
{
	$destination[$r["yahooname"]] = array("valeur" => $r["valeur"], "unixtime" => $r["lasttime"]);
	$stat[$r["yahooname"]] = array("codesico" => $r["codesico"] ,"lasttime" => $r["lasttime"], "lasttimedown" => $r["lasttimedown"]);
}
if(count($source)!=count($destination))
{
	echoadmin("Erreur nombre de valeurs(".count($source)."=>".count($destination).")");
	echotabadmin(array_diff_assoc($destination,$source));
}
	$chaineupdate="";
	$mintimeupdate=9999999999;
	$updates=0;
	$grpupdate=0;
	foreach( $source as $cle => $tabval )
	{
		//$couleurs[$cle] = strtoupper($couleur);
		if(array_key_exists ( $cle, $destination))// && $tabval["unixtime"]>$destination[$cle]["unixtime"] && $tabval["unixtime"]<date("U"))
		{
			if($tabval["valeur"]<>$destination[$cle]["valeur"])// || $tabval["unixtime"]>$destination[$cle]["unixtime"])
			{
				if($tabval["valeur"]>0 && abs($tabval["valeur"]-$destination[$cle]["valeur"])/$tabval["valeur"]>=.25 && $destination[$cle]["valeur"]!=0)
				{
					$corps="L'action yahooname=$cle a chang� de 25% (de ".$destination[$cle]["valeur"]." � ".$tabval["valeur"]." ), aller sur la page d'admin pour r�activer si il n'y a pas de multiplication ou division d'action.";
                                        envoimail(EMAILADMIN,"NetTrader, valeur se modifie de 25% !",$corps);
                    			$resultat=ExecRequete("UPDATE cacval SET down='0' WHERE  yahooname='$cle'",$connexion);
				}
				$resultat=ExecRequete("UPDATE cacval SET valeur='".$tabval["valeur"]."', lasttime='".$tabval["unixtime"]."', lasttimedown='$maintenant' WHERE  yahooname='$cle'",$connexion);
				if(date("U")<DATEFINSTATS) ExecRequete("INSERT INTO `statmaj` ( `idstat` , `codesico` , `lasttime_ans` , `lasttimedown_ans` , `lasttime_nouv` , `lasttimedown_nouv` ) VALUES ('', '".$stat[$cle]["codesico"]."', '".$stat[$cle]["lasttime"]."', '".$stat[$cle]["lasttimedown"]."', '".$tabval["unixtime"]."', UNIX_TIMESTAMP( ));",$connexion);
				$updates++;
			}else{
				/*
				$grpupdate++;
				if($chaineupdate<>"")
				{
					$chaineupdate.=",";
				}
				$chaineupdate.="'$cle'";
				if($tabval["unixtime"]<$mintimeupdate)
				{
					$mintimeupdate=$tabval["unixtime"];
				}
				*/
			}
		}
	}

	if($chaineupdate!="")
	{
		$resultat=ExecRequete("UPDATE cacval SET lasttime='$mintimeupdate', lasttimedown='$maintenant' WHERE yahooname IN ($chaineupdate)",$connexion);
	//}else{
	//	$resultat=ExecRequete("UPDATE cacval SET lasttimedown='$maintenant'",$connexion);
	}

	echoadmin(" $updates updates $grpupdate updates de groupe");


// $exploded_ligne[1] contient la valeur et $UnixStampTime la date et $sico le codesico
// inscrire dans la bdd
// on place dans un tableau tout ce que contient la base
$valsicav=0;
if($sico!=0)
{
	if(array_key_exists ( $sico, $source))
	{
		$valsicav=$source[$sico]["valeur"];
	}else{
		echoadmin("Erreur t�l�chargement action $sico .");
		exit();
	}
}
return $valsicav;
}


function ansgetvaleur($sico,$nouv=0) //$nouv = 1 si action � ajouter
{   
echoadmin("/($sico)");
$sicodown = leading_zero($sico, 6, 0); // Output: 021 
//echo $sico."<br>";
// $sico en entr�e -> en sortie la derni�re valeur connue avec 14 min d'�card par rapport � l'heure de la derniere valeur (prit sur la page)
// lecture de la derni�re date en 
$maintenant = date ("U");
$letimestamp=get_refresh();
$datesql=$letimestamp->datesql;
$datedown=$letimestamp->datedown;
//echo $datesql." ".$datedown;

$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$resultat=ExecRequete("SELECT valeur FROM cacval WHERE codesico = '$sico' AND (lasttime > '$datesql' OR lasttimedown > '$datedown')  ",$connexion);
$lastval = 0;
while($r=mysql_fetch_array($resultat))
{
	$lastval=$r["valeur"];
	return $lastval;
}
/* // aucune valeur a �t� trouv� pour un t�l�chargement r�cent, on va recuperer la derniere date de t�l�chargement, si la valeur lasttime est d'aujourd'hui alors on passe cette �tape, si c'est d'hier alors si lasttimedown date de moins de 15 minutes alors on retourne la derniere valeur sinon on passe
$resultat=ExecRequete("SELECT nom,lasttimedown,lasttime,valeur FROM cacval WHERE codesico = '$sico'",$connexion);
$lastval = 0;
$lenom="";
while($r=mysql_fetch_array($resultat))
{
	$derndown=$r["lasttimedown"];
	$derndate=$r["lasttime"];
	$lavaleur=$r["valeur"];
	
	$lenom=&$r["nom"];
	if(date("w",$maintenant)<>date("w",$derndate) AND ($derndown+(15*60))>$maintenant AND $lenom<>"")
	{
		return $lavaleur;
	}
} */
//si on est l� c'est qu'il faut t�l�charger la derni�re valeur puis la mettre dans la bdd
$fd = fopen (ADDRDEB."$sicodown", "r") OR DIE(lang(25));
echoadmin("telechargement");
if(!$fd)
	exit("ERROR");

while (!feof ($fd)) 
{
  $buffer = fgets($fd, 4096);
  $lines[] = $buffer;
}
fclose ($fd);

for ( $i = 0; $i < count ($lines) ; $i++ )
{
	if(ereg('name=' , $lines[$i]) == 1)
	{
		if (ereg ("name=(.*)", $lines[$i], $regs))
		{
			$NomSico=sec($regs[1]);
		}
	}
	if(ereg('title' , $lines[$i]) == 1)
	{
		if (ereg ("title=([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $lines[$i], $regs))
		{
			$yr=$regs[3];
			$mon=$regs[2];
			$day=$regs[1];
		}else{
			echo "Format de date invalide : $lines[$i]";
			exit; 
		}
	}
	if(ereg('EndData' , $lines[$i]) == 1)
	{
		//$lines[$i-1] contient la ligne
		$exploded_ligne = explode("	", $lines[$i-1] );
		$valsicav=$exploded_ligne[1];
		//list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y")); 
		if (ereg ("([0-9]{2})([0-9]{2})([0-9]{2})", $exploded_ligne[0], $regs))
		{
			$hours=$regs[1];
			$min=$regs[2];
			$sec=$regs[3];
		}else{
			echo "Format d'heure invalide : ".$exploded_ligne[0];
			exit; 
		}
		$UnixStampTime = mktime($hours,$min, $sec, $mon, $day, $yr);
	}
}
// $exploded_ligne[1] contient la valeur et $UnixStampTime la date et $sico le codesico
// inscrire dans la bdd
$maintenant = date("U");
if($nouv==0)
{
	if($valsicav<>"" AND $UnixStampTime<>"" AND $sico<>"")
	{
		$resultat=ExecRequete("UPDATE cacval SET valeur=$valsicav, lasttime=$UnixStampTime, lasttimedown=$maintenant WHERE codesico=$sico",$connexion);
	}
}else{
	//la ligne n'existe pas ou code non nomm� alors, il faut cr�er la ligne
	delete_sicav($sico);
	$resultat=ExecRequete("INSERT INTO `cacval` (`codesico`, `nom`, `valeur`, `lasttime`, `lasttimedown`) VALUES ('$sico', '$NomSico', '$valsicav', '$UnixStampTime', '$maintenant')",$connexion);
} 

	return $valsicav;
}
// FIN SICO ##########################################################################################
// FIN SICO ##########################################################################################
// FIN SICO ##########################################################################################
// FIN SICO ##########################################################################################
// FIN SICO ##########################################################################################
// FIN SICO ##########################################################################################
function leading_zero( $aNumber, $intPart, $floatPart=NULL, $dec_point=NULL, $thousands_sep=NULL) 
{        //Note: The $thousands_sep has no real function because it will be "disturbed" by plain leading zeros -> the main goal of the function
  $formattedNumber = $aNumber;
  if (!is_null($floatPart)) {    //without 3rd parameters the "float part" of the float shouldn't be touched
    $formattedNumber = number_format($formattedNumber, $floatPart, $dec_point, $thousands_sep);
    }
  //if ($intPart > floor(log10($formattedNumber)))
    $formattedNumber = str_repeat("0",($intPart + -1 - floor(log10($formattedNumber)))).$formattedNumber;
  return $formattedNumber;
}

function updateplayersicav() // met � jours les actions du joueur
{
global $tempsdebexec,$do,$internaute,$notupdated;
if($notupdated==1)
{
	$liste = joueur_liste_sicav($internaute->idcompte);
}else{
	$liste = "";
}

if($liste==""){return 1;}
foreach ($liste as $key => $value)
	{
	$tempsexec = round(getmicrotime()-$tempsdebexec,2);
	if($tempsexec<5)
		{
		getvaleur($value["codesicav"]);
		echoadmin ("(".$tempsexec.")");		
		}else{
		$echo = "<head><meta http-equiv=\"Refresh\" content=\"2;url=index.php?do=$do\"></head>Veuillez patienter ...";
		return $echo;
		}
	}
return 1;
}

function echotabadmin($tab)
{
global $internaute;
if($internaute->idcompte == 1)
	{
	echo "<pre>";
	print_r($tab);
	echo "</pre>";
	}
return 1;
}

function sorttableau($resultat,$titre,$largeur="90")
{
	$html="";
	if($resultat)
	{
	$qte=mysql_num_fields($resultat);/*nombre de champs s�lectionn�s*/
	$html= opentab(" align=center width=\"$largeur%\"  ");
	$html.= openligne("","titre2").opencol("colspan=\"$qte\"").$titre.closecol().closeligne();	
	$html.= openligne("","titre");/*couleur grise*/
	for ($i=0;$i<$qte;$i++)
	{
		 $html.= opencol();
		 $html.= mysql_field_name($resultat,$i);/*les noms des champs*/
		 $html.= closecol();
	}
	$html.= closeligne();
	
	while ($row =   mysql_fetch_array($resultat,MYSQL_ASSOC))
	{/*array des donn�es*/
		$html.= openligne();
		foreach ($row as $elem)
		{/*pour chaque �l�ment...*/
			 $html.= opencol().stripslashes($elem).closecol();
		}
		$html.= closeligne();
	}
	$html.= closetab();
	}
return $html;
} // fin de la fonction...

function barrepage($nblignes,$ligneparpage,$lignecourante,$add="")
{
	//on se limite � 40 chiffres affich�es
	//1er..20 avant..20apr�s..dernier

	global $do;

	$nbpage = ceil($nblignes/$ligneparpage);
	$html="<center>";
	$pageouverte=ceil($lignecourante/$ligneparpage);
	$limitebasse=$pageouverte-8;
	$limitehaute=$pageouverte+8+retiftrue(abs($limitebasse),$limitebasse<0,0);
	$pause=false;
	for ($i=1;$i<=$nbpage;$i++)
	{
		if($nbpage<=20||$i==1||$i==$nbpage||($i>$limitebasse && $i<=$pageouverte)||($i>=$pageouverte && $i<=$limitehaute))
		{

			if($i<>1 && !$pause)
			{
				$html.=" - ";
			}
			$pause=false;
			if($lignecourante>($i*$ligneparpage)-$ligneparpage-1 && $lignecourante<=($i*$ligneparpage)-1)
			{
				$html.=$i;
			}else{
	            		$html.=html_lien($i,getnewurl("numligne",$i*$ligneparpage-$ligneparpage));
			}
		}else{
			if(!$pause)
				$html.=" ... ";
			$pause=true;
		}
	}
	$html.="</center>";
	return $html;
}

function tempsjeu()
{
global $internaute;
$maintenant = date("U");
if(($maintenant>DEBCONC && $maintenant<FINCONC) || $internaute->idcompte==1)
{
	return true;
}else{
	return false;
}

}

function updaterecompensegroupes()
{
//recompense or,argent,bronze
//1 �re �tape , on recupere les 3 premiers groupes ( perf ) de ce mois-ci
$res=getperfgroupes();
$i=0;
while($ligne=LigneSuivante($res))
{
	$i++;
	if($i==1)
	{
          increcompensegroupe($ligne->idgroupe,1,0,0);
	}
	elseif($i==2)
	{
          increcompensegroupe($ligne->idgroupe,0,1,0);
	}
	elseif($i==3)
	{
          increcompensegroupe($ligne->idgroupe,0,0,1);
	  break;
	}
}
}

function checkscore()
{
if(!(scoreestactuel()))
{
	insertscore();
        effacvieuxscores();
        majlistmoisclass();
}
if(!teamscoreestactuel())
{
	insertgroupescore();
	updaterecompensegroupes();
	//TODO : on r�compense les 3 premiers groupes OR,ARGENT,BRONZE

}
return 1;
}

function updatenomsicav() // met � jour le nom des sicav
{
global $tempsdebexec,$do,$internaute;

$liste = listvaleur();
if($liste==""){return 1;}
foreach ($liste as $key => $value)
	{
	$tempsexec = round(getmicrotime()-$tempsdebexec,2);
	if($tempsexec<5)
		{
		getvaleur($value["codesicav"]);
		echoadmin ("(".$tempsexec.")");		
		}else{
		$echo = "<head><meta http-equiv=\"Refresh\" content=\"2;url=index.php?do=$do\"></head>Veuillez patienter ...";
		return $echo;
		}
	}
return 1;
}

function getnbactionmax($cashback,$valeursicav)
{
//ans 0.997018913449
//solve(nbaction*val(1 + 0.003(1+.196))=capital,nbaction)
if($valeursicav==0)
	return 0;
$NbActionMax = floor((0.99642482771815 * $cashback) / $valeursicav);
if($cashback<4.95+$valeursicav)
{
	$NbActionMax = 0;
}
return $NbActionMax;
}

function gettaxe($valeursicav,$nombre)
{
$taxe = Round(($nombre * $valeursicav) * 0.0030 * (1 + 0.196), 2);
if($taxe<4.95)
{
	$taxe = 4.95; // 4,5 � de taxe minimum pour toutes transactions (achat vente, vad)
}
return $taxe;
}

function getmontantvadpossible($idcompte)
{
//il est possible d'avoir des actions en vad � hauteur de 100% du capital (hors vad)
//$limite = $cashback*5+$portefnonvad*2.5
//on retourne la vad encore possible donc on extrait � ce qui est d�j� en vad
$capitalhorsvad=getplayercapitalhorsvad($idcompte);
$capitalvad=getplayercapitalvad($idcompte);
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$joueur=ChercheInternaute( $idcompte,$connexion);
$limitevad=($joueur->cashback-$capitalvad)*1+$capitalhorsvad*1;
$limitevadpossible=$limitevad-$capitalvad;

if($limitevadpossible<0) $limitevadpossible=0;

return $limitevadpossible;
}


function get_refresh()
{
$maintenant = date ("U");
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
//$date1 =  $maintenant - (20*60); //Il y a 20 minutes
//$date2 =  $maintenant - (10*60); //Il y a 10 minutes
$date1 =  $maintenant - (20*60); //Il y a 15 minutes
$date2 =  $maintenant - (2*60); //Il y a 2 minutes
$date3 = mktime("18", "00", "00", $mon, $day-1, $yr); // HIER
$date4 = mktime("18", "00", "00", $mon, $day-2, $yr); //Avant hier
$date5 = 9999999999;//JAMAIS
$date6 = mktime("18", "00", "00", $mon, $day-3, $yr);//Il y a 3 jours
$date7 = mktime("18", "00", "00", $mon, $day, $yr); //AUJOURD'HUI
if(date("w",$maintenant) >0 and date("w",$maintenant) <6)
{
	if(date("H",$maintenant) >9 and date("H",$maintenant) <18)
	{
		$datesql = $date1;
		$datedown = $date2;
	}else{
	if(date("H",$maintenant) >9)
		{
			//BOURSE FERME aujourd'hui � 17h30
			$datesql=$date5;
			$datedown = $date7 ;
		}else{
			//bourse ferm� il est moins de 9h
			$datesql=$date5;
			if(date("w",$maintenant)==1)
			{	//LUNDI
				$datedown = $date6; //les cours de vendredi dernier
			}else{
				//mardi a vendredi
				$datedown = $date3; //les cours d'hier
			}			
		}
	}
}else{
	//sinon on prend la date de la journ�e d'hier � 18h00
	//samedi ou dimanche
	if(date("w",$maintenant)==6)
	{
		//SAMEDI
		$datesql = $date5;
		$datedown = $date3;
	}else{
		//DIMANCHE
		$datesql = $date5;
		$datedown = $date4;
	}
}
if(tempsjeu())
{
	$retour->datesql=$datesql;
	$retour->datedown=$datedown;
}else{
	$retour->datesql=0;
	$retour->datedown=0;
}
return $retour;
}



function updatelistsicav($liste)
{
global $tempsdebexec,$do;
if($liste<>"")
{
/* 	foreach ($liste as $key => $value)
	{
	 $tempsexec = round(getmicrotime()-$tempsdebexec,2);
	if($tempsexec<5)
		{
		getvaleur($value["codesicav"]);
		//echoadmin ("(".$tempsexec.")");		
		}else{
		$echo = "<head><meta http-equiv=\"Refresh\" content=\"2;url=index.php?do=$do\"></head>Veuillez patienter ...";
		return $echo;
		} 
		
	} */
	cmd_downvaleur();
}
return 1;
}

function cmd_to_update_liste() //retourne les codes des actions qu'il faut mettre � jour
{
$liste = joueur_liste_sicav("",1*60); //affiche les valeurs qui serons t�l�charg� dans 5 minutes
$return="";
if($liste<>"")
{
	$i=0;
	foreach ($liste as $key => $value)
	{
		if($i<>0)
		{
			$return.=";";
		}
		$return.=leading_zero($value["codesicav"], 6, 0);
		$i++;
	}
}
return "OK||".get_nextrefresh(date("U"))."|".ADDRDEB."|".ADDRFIN;
//return "OK|".$return."|".get_nextrefresh(date("U"))."|".ADDRDEB."|".ADDRFIN;
}

function cmd_downhisto()
{
//http://fr.finance.yahoo.com/d/quotes.csv?s=nom1+nom2+nom3&f=snl1d1t1c1ohgv&e=.csv
$lstvaleurtodown=get_sicavdown();
$chaineurl="";
$compteur=0;
for($i=0;$i<=count($lstvaleurtodown)-1;$i++)
{
	$compteur++;
	if($chaineurl!="")
                $chaineurl.="+";
        $chaineurl.=$lstvaleurtodown[$i]["yahooname"];
	if($compteur==1 || $i==count($lstvaleurtodown)-1)
	{       /*
                try {
		  $timeout = 1;
		  $old = ini_set('default_socket_timeout', $timeout);
		  $fd = fopen ("http://ichart.yahoo.com/table.csv?s=".$lstvaleurtodown[$i]["yahooname"]."&a=00&b=2&c=2007&d=00&e=2&f=2007&g=d&ignore=.csv", "r");
		  ini_set('default_socket_timeout', $old);
		}
                catch (Exception $e) {
			$fd=0;
		}
		*/
                $fd=0;
		print "\"http://ichart.yahoo.com/table.csv?s=".$lstvaleurtodown[$i]["yahooname"]."&a=00&b=2&c=2007&d=00&e=2&f=2007&g=d&ignore=.csv\",";
		if($fd)
		{
			$l=0;
			while (!feof ($fd))
			{
			  $buffer = fgets($fd, 4096);
			  if($l==1)
			  	$lines[] = $buffer;
			  $l++;
			}
			fclose ($fd);
		}
                $chaineurl="";
                $compteur=0;
	}
}
for($i=0;$i<=count($lstvaleurtodown)-1;$i++)
{
	print "\"".$lstvaleurtodown[$i]["yahooname"]."\",";
}
//$valsicav = traitehtmlsicav($lines,0);
print_r($lines);
return "";


}

function cmd_euronextdownvaleur()
{
$fd = fopen (ADDREURONEXT, "r") OR DIE(lang(25));
if(!$fd)
	exit("ERROR");

while (!feof ($fd))
{
  $buffer = fgets($fd, 4096);
  $lines[] = $buffer;
}
fclose ($fd);
return traiteeuronextcsv($lines);
}

function cmd_downvaleur()
{
//http://fr.finance.yahoo.com/d/quotes.csv?s=nom1+nom2+nom3&f=snl1d1t1c1ohgv&e=.csv
$lstvaleurtodown=get_sicavdown();
$chaineurl="";
$compteur=0;
for($i=0;$i<=count($lstvaleurtodown)-1;$i++)
{
	$compteur++;
	if($chaineurl!="")
                $chaineurl.="+";
        $chaineurl.=$lstvaleurtodown[$i]["yahooname"];
	if($compteur==MAXDOWN || $i==count($lstvaleurtodown)-1)
	{
		$fd = fopen (NOUVADDR.$chaineurl.NOUVADDRFIN, "r") OR DIE(lang(25));
		if(!$fd)
			exit("ERROR");

		while (!feof ($fd))
		{
		  $buffer = fgets($fd, 4096);
		  $lines[] = $buffer;
		}
		fclose ($fd);
                $chaineurl="";
                $compteur=0;
	}
}
return " ".traiteyahoocsv($lines,0);
}

function cmd_nodownvaleur($donnes)
{
sauveipadress($_SERVER['REMOTE_ADDR']);
$lines=split(chr(13).chr(10),$donnes);
for($i=0;$i<=count($lines)-1;$i++)
{
 	if (eregi(",", $lines[$i]))
	{
	   $sublines[]= $lines[$i];
	}
}
traitehtmlsicav($sublines,0);
return "";
}

function cmd_setvaleur($codesico,$valeur,$ladate,$lheure)
{
$sico_list = explode('|', $codesico );
$valeur_list = explode('|', $valeur );
$date_list = explode('|', $ladate );
$heure_list = explode('|', $lheure );
if( count ($sico_list)<> count($valeur_list) OR count ($valeur_list)<> count($date_list) OR count ($date_list)<> count($heure_list) )
{
return "OK Nombre d'element incompatible|120";
}
$return="";
for ( $i = 0; $i < count ($sico_list) ; $i++ )
{
	
	$ladate=$date_list[$i];
	$lheure=$heure_list[$i];
	$codesico=$sico_list[$i];
	$valeur=$valeur_list[$i];
	
	$return.=chr(13)."Sico:".chr(9).$codesico.chr(9)." Valeur:".chr(9).$valeur.chr(9)." Date,Heure:".chr(9).$ladate.",".$lheure;
	$lasttimedown=date("U");
	if (ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $ladate, $regs))
	{
		$yr=$regs[3];
		$mon=$regs[2];
		$day=$regs[1];
	}else{
		return "OK Format de date invalide : $ladate|120";
	}
	if (ereg ("([0-9]{2})([0-9]{2})([0-9]{2})", $lheure, $regs))
	{
		$hours=$regs[1];
		$min=$regs[2];
		$sec=$regs[3];
	}else{
		return "OK Format d'heure invalide : $lheure|120";
	}
	$lasttime= mktime($hours,$min, $sec, $mon, $day, $yr);
	if($lasttime>$lasttimedown)
	{
		return "OK date de t�l�chargement sup�rieur � maintenant|120";
	}
	cmd_update_sicav($codesico,$valeur,$lasttime,$lasttimedown);
}


return "OK|".get_nextrefresh();  
}

function tomoisfr($mois)
{

if($mois=="01") { $mois = "Janvier" ; }
if($mois=="02") { $mois = "Fevrier" ; }
if($mois=="03") { $mois = "Mars" ; }
if($mois=="04") { $mois = "Avril" ; }
if($mois=="05") { $mois = "Mai" ; }
if($mois=="06") { $mois = "Juin" ; }
if($mois=="07") { $mois = "Juillet" ; }
if($mois=="08") { $mois = "Aout" ; }
if($mois=="09") { $mois = "Septembre" ; }
if($mois=="10") { $mois = "Octobre" ; }
if($mois=="11") { $mois = "Novembre" ; }
if($mois=="12") { $mois = "Decembre" ; }

return $mois;
}

function numlimit($courant,$max,$diff)
{
if($courant+$diff>$max)
{
	$retour=$max;
}else{
	if($courant+$diff<0)
	{
	        $retour=0;
	}else{
		$retour=$courant+$diff;
	}
}
return $retour;
}

function finjour()
{
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
$p=0;
if(date("w") == 0 || (date("H") >= 18 && date("w")>=1 && date("w")<=4))  //si dimanche ou 18h du lundi au jeudi
{
 $p=1;
}
if(date("w") == 6) // si samedi
{
 $p=2;
}
if(date("w") == 5 && date("H") >= 18) //si vendredi apres 18h00
{
  $p=3;
}
$date1 = mktime("18", "00", "00", $mon, $day+$p, $yr); // cloture
$retour=date("d/m/Y H:i",$date1);
return $retour;
}

function openform($do,$othervar="")
{
return "<form method=\"post\" action=\"index.php?do=$do $othervar\">";
}

function classtohtmlcolor($classement,$tot)
{
if($tot)
	$base=dechex(intval(256*(1-($classement/$tot))));
else
	$base="00";
return "#".str_repeat($base,3);
}

function couleurfonctionclasse($tab) //entr�e -> tableau avec id et %
{
$tabs=array();
$nb=count($tab);
//on classe le tableau
asort($tab);
$c=1;
foreach($tab as $k => $v)
{
	$tabs[$k]=classtohtmlcolor($c++,$nb);
}

return $tabs;
}

function htmlourien($htmlcolor)
{
if(!$htmlcolor)
	return "#FFFFFF";
else
	return $htmlcolor;
}

function lnkachat($codesico)
{
return "index.php?do=formachatvente&info=".ADSENSEKEYWORD."&sicavselachat=$codesico";
}

function lnkvente($codesico,$val=1,$texte)
{
if($val)
	return "<a href=\"index.php?do=formachatvente&info=".ADSENSEKEYWORD."&sicavselvendr=$codesico\">$texte</a>";
else
	return "$texte";
}
//html_lien("leTexte",getnewurl("format","portef"))
function html_lien($texte,$donnees)
{
return "<a href=\"index.php?$donnees\">$texte</a>";
}

function getnewurl($find,$value,$ansurl="")
{
if($ansurl=="")
	$url1 = $_SERVER['QUERY_STRING'];
else
	$url1= $ansurl;
$str = parse_str($url1, $output);

// Modification critere
if($value!="")
	$output[$find] = $value;
else
	unset($output[$find]);

$res="";
foreach($output as $k => $v)
{
	if($v!="" && $k!="last")
	{
		if($res!="")
			$res.="&";
		$res.="$k=$v";
	}
}
//return http_build_query($output);
return $res;
}

function getsigne($valeur)
{
	if($valeur>=0)
	{
		return 1;
	}else{
		return -1;
	}
}

function tabordre($table)
{
$champ="";
$ordre="";
if(array_key_exists ("champ",$_GET)) $champ=$_GET['champ'];
if(array_key_exists ("champ",$_GET)) $ordre=$_GET['ordre'];
$champ=sec($champ);
$ordre=sec($ordre);

switch($table)
{
case "portef":
	switch($champ)
	{
	case "nomactionportef":
		$champordre="nomsicav";
		break;
	case "nombreportef":
		$champordre="nombsicav";
		break;
	case "ansvaleur":
		$champordre="ansvaltotsicav";
		break;
	case "valeuractportef":
		$champordre="valtotsicav";
		break;
	case "benefportef":
		$champordre="benefsicav";
		break;
	default:
		$champordre="cacval.nom";
		break;
	}
	break;
case "lstactions":
	switch($champ)
	{
	case "partjoueur":
	case "part":
		$champordre="part";
		break;
	case "valeuraction":
		$champordre="valeur";
		break;
	case "nomaction":
		$champordre="nom";
		break;
	default:
		$champordre="libellesecteur ASC , nom";
		break;
	}
	break;
case "historique":
	switch($champ)
	{
	case "datehisto":
		$champordre="LADATE";
		break;
	case "nomhisto":
		$champordre="LENOM";
		break;
	case "senshisto":
		$champordre="LESENS";
		break;
	case "nombrehisto":
		$champordre="LENOMBRE";
		break;
	case "valhthisto":
		$champordre="LETOTHT";
		break;
	case "taxehisto":
		$champordre="LATAXE";
		break;
	case "totalttchisto":
		$champordre="LETTC";
		break;
	case "profithisto":
		$champordre="PROFITOP";
		break;
	default:
		$champordre="LADATE";
		$ordre="d";
		break;
	}
	break;
case "classement":
	switch($champ)
	{
	case "pseudoclasse":
		$champordre="pseudonyme";
		break;
	case "capitalclasse":
		$champordre="capital";
		break;
	case "pourcbenefclasse":
		$champordre="prog";
		break;
	default:
		$champordre="prog";
		$ordre="d";
		break;
	}
	break;
case "classementequipe":
	switch($champ)
	{
	case "nomequipeclasse":
		$champordre="titregroupe";
		break;
	case "pourcbenefclasse":
		$champordre="prog";
		break;
	case "nbjoueursclasse":
		$champordre="nbjoueurs";
		break;
	default:
		$champordre="prog";
		$ordre="d";
		break;
	}
	break;

//Pseudonyme	Date inscr.	Capital inscr.	Portefeuille	Plus value
/*pseudonyme, dateinscription,capitalinscr,prog,capital*/
case "profilequipe":
	switch($champ)
	{
	case "Pseudonyme":
		$champordre="pseudonyme";
		break;
	case "Dateinscr":
		$champordre="datejoint";
		break;
	case "Capitalinscr":
		$champordre="capitalinscr";
		break;
	case "Portefeuille":
		$champordre="capital";
		break;
	case "Plusvalue":
		$champordre="prog";
		break;
	default:
		$champordre="pseudonyme";
		$ordre="c";
		break;
	}
	break;
}
if($ordre=="d")
	$champordre.=" DESC";
else
	$champordre.=" ASC";



return $champordre;
}

function lienordre($champ,$titre)
{
$champans="";
$ordreans="";
if(array_key_exists ("champ",$_GET)) $champans=$_GET['champ'];
if(array_key_exists ("champ",$_GET)) $ordreans=$_GET['ordre'];
$champans=sec($champans);
$ordreans=sec($ordreans);

$nouvchamp=$champ;
$nouvordre="c";

if($champans==$champ)
{
    if($ordreans=="d") $nouvordre="c";
	if($ordreans=="c") $nouvordre="d";
}

$url=getnewurl("ordre",$nouvordre);
$url=getnewurl("champ",$nouvchamp,$url);

return "<a href=\"index.php?$url\">$titre</a>";
}

function bbtohtml($text) //transforme une chaine en bbcode vers une chaine comportant de l'html
{
global $skinrep;
  $bbcode = array(
                "[list]", "[*]", "[/list]",
                "[img]", "[/img]",
                "[b]", "[/b]",
                "[u]", "[/u]",
                "[i]", "[/i]",
                '[color="', "[/color]",
                "[size=", "[/size]",
                '[url="', "[/url]",
                "[mail=\"", "[/mail]",
                "[code]", "[/code]",
                "[quote]", "[/quote]",
                '"]',
                ']',":D",":)",":(",":o ",":shock:",":? ","8)",":lol:",":x",":oops:",":cry:",":evil:",":roll:",":wink:",":!:",":?:",":idea:",":arrow:",":neutral:",":mrgreen:");
  $htmlcode = array(
                "<ul>", "<li>", "</ul>",
                "<img src=\"", "\">",
                "<b>", "</b>",
                "<u>", "</u>",
                "<i>", "</i>",
                "<span style=\"color:", "</span>",
                "<span style=\"font-size:", "</span>",
                '<a href="', "</a>",
                "<a href=\"mailto:", "</a>",
                "<code>", "</code>",
                opentab(" width=100% ").openligne("","citation")."<td>", "</td></tr></table>",
                '">','">',"<img src=\"$skinrep/smiles/icon_biggrin.gif\" title=\"Very Happy\" border=\"0\">","<img src=\"$skinrep/smiles/icon_smile.gif\" title=\"Smile\" border=\"0\">","<img src=\"$skinrep/smiles/icon_sad.gif\" title=\"Sad\" border=\"0\">","<img src=\"$skinrep/smiles/icon_surprised.gif\" title=\"Surprised\" border=\"0\">","<img src=\"$skinrep/smiles/icon_eek.gif\" title=\"Shocked\" border=\"0\">","<img src=\"$skinrep/smiles/icon_confused.gif\" title=\"Confused\" border=\"0\">","<img src=\"$skinrep/smiles/icon_cool.gif\" title=\"Cool\" border=\"0\">","<img src=\"$skinrep/smiles/icon_lol.gif\" title=\"Laughing\" border=\"0\">","<img src=\"$skinrep/smiles/icon_mad.gif\" title=\"Mad\" border=\"0\">","<img src=\"$skinrep/smiles/icon_redface.gif\" title=\"Embarassed\" border=\"0\">","<img src=\"$skinrep/smiles/icon_cry.gif\" title=\"Crying or Very sad\" border=\"0\">","<img src=\"$skinrep/smiles/icon_evil.gif\" title=\"Evil or Very Mad\" border=\"0\">","<img src=\"$skinrep/smiles/icon_rolleyes.gif\" title=\"Rolling Eyes\" border=\"0\">","<img src=\"$skinrep/smiles/icon_wink.gif\" title=\"Wink\" border=\"0\">","<img src=\"$skinrep/smiles/icon_exclaim.gif\" title=\"Exclamation\" border=\"0\">","<img src=\"$skinrep/smiles/icon_question.gif\" title=\"Question\" border=\"0\">","<img src=\"$skinrep/smiles/icon_idea.gif\" title=\"Idea\" border=\"0\">","<img src=\"$skinrep/smiles/icon_arrow.gif\" title=\"Arrow\" border=\"0\">","<img src=\"$skinrep/smiles/icon_neutral.gif\" title=\"Neutral\" border=\"0\">","<img src=\"$skinrep/smiles/icon_mrgreen.gif\" title=\"Mr. Green\" border=\"0\">");
  $newtext = str_replace($bbcode, $htmlcode, $text);
  $newtext = nl2br($newtext);// Ins�re un retour � la ligne HTML � chaque nouvelle ligne
  return $newtext;
}

function estadmingroupe($idcompte,$idgroupe=0)
{
	$ligne=getgroupbyadmin($idcompte);
	if(is_object($ligne) && ($ligne->idgroupe==$idgroupe || $idgroupe==0 ))
	{
		return 1;
	}else{
		return 0;
	}
}

function estmembregroupe($idcompte)
{
	$ligne=getgroupbymembre($idcompte);
	if(is_object($ligne))
	{
		return 1;
	}else{
		return 0;
	}
}

function getidgroupe($idcompte)
{
	$ligne=getgroupbyadmin($idcompte);
	if(is_object($ligne))
	{
		return $ligne->idgroupe;
	}else{
		return -1;
	}
}

function envoimail($email,$titre,$corps)
{
	$from_email  = EMAILADMIN;
	$entetedate  = date("D, j M Y H:i:s -0600"); // avec offset horaire
	$entetemail  = "From: $from_email \n"; // Adresse exp�diteur
	$entetemail .= "Cc: \n";
	$entetemail .= "Bcc: \n"; // Copies cach�es
	$entetemail .= "Reply-To: $from_email \n"; // Adresse de retour
	$entetemail .= "X-Mailer: PHP/" . phpversion() . "\n" ;
	$entetemail .= "Date: $entetedate";
	$titre="NetTrader : ".$titre;
	$corps.="\n\n\n\nPour ne plus recevoir d'email provenant du site Nettrader veuillez vous d�sinscrire de nettrader par le site via le lien \"R.A.Z. joueur\" (imm�diat : ".ADDRNT."/index.php?do=formrazjoueur ) ou contacter l'administrateur en r�pondant � ce mail(temps d'attente +/- 24h ).";
	if($_SERVER['REMOTE_ADDR']!="127.0.0.1")
		mail($email, $titre,stripslashes($corps),stripslashes($entetemail));
	else
		echo msgtab(bbtohtml(stripslashes($corps)),stripslashes($titre));

 return 0;
}

function retiftrue($data,$condition,$else="")
{
if($condition)
	return $data;
else
	return $else;

}

function majstats()
{
$nbstats=getnbstats();
srand(intval(date("dm")));
$numstat=rand(1,$nbstats);
exepublicreq($numstat);
}
function print_reward($medor,$medargent,$medbronze)
{
global $skinrep;
return str_repeat("<IMG SRC=\"$skinrep/premier.png\" border=0>",$medor).str_repeat("<IMG SRC=\"$skinrep/deus.png\" border=0>",$medargent).str_repeat("<IMG SRC=\"$skinrep/tres.png\" border=0>",$medbronze);
}

function forum_peut_editer($lignemessage,$infoforum)
{
 global $internaute;
	return $lignemessage->idcompte==$internaute->idcompte||$internaute->authlevel>1;
}

function geturlaide($yahooname)
{
	//return "http://fr.finsearch.yahoo.com/fr/?s=fr_sort&nm=$yahooname&tp=s&r=";
        return "http://fr.finance.yahoo.com/echarts?s=$yahooname#symbol=$yahooname;range=1m";
}
?>