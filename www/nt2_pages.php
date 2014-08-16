<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
function inclskr()
{
include_once ("skin/default/include_interface.php");
include_once ("const.php");
include_once ("constbdd.php");
include_once ("db_connect.php");
include_once ("db_reqtableaux.php");
include_once ("db_reqfunction.php");
include_once ("nt2_function.php");
include_once ("nt2_pages.php");
return "";
}


function achatvente($sicavselecta,$sicavselectv)
{
	global $internaute;
	//afficher la liste des valeurs
	$liste = portefeuille_joueur();
	$return="";
	$return.=opentab(" align=center width=\"100%\"","invi").openligne("","invi").opencol(" valign=top width=\"45%\"");
	if(count($liste)>0 || $internaute->vad )
	{	$return .= formvente($sicavselectv,$liste).closecol().opencol(" width=\"10%\" ").closecol().opencol(" valign=top width=\"45%\"");
	}
	$return .= formachat($sicavselecta).closecol().closeligne().closetab();
	$return .= formlistaction($liste);
	$return.="<br><br>";
	$return .= form_list_ordre();
	$return.="<br><br>";
	$return.="<br><center><br><a href=\"index.php?do=lstactions\">".openfont("titre1").lang(126).closefont()."</a></center><br>";

	return $return;
}

function formlistaction($liste) // $ liste = portefeuille_joueur()
{
global $internaute,$skinrep;
//$internaute->cashback=GetCashBack($internaute->idcompte);
//$liste = portefeuille_joueur();
$echo = "";
$affichagedate = "";
$totalport=0;
$totalbenef=0;
if($liste<>"")
{
	$echo = opentab(" align=center width=\"90%\"  ");
	$echo .= openligne("","titre2");
	$echo .= opencol("colspan=\"4\"");
	$echo .= lang(24)." :";
	$echo .= closecol();
	$echo .= opencol();
	$echo .= htm_iconhelp("formlistaction");
	$echo .= closecol();
	$echo .= closeligne();
	$echo .= openligne("","titre");
	$echo .= opencol();
	$echo .= "<b>".lienordre("nomactionportef",lang(15))."</b>";
	$echo .= closecol();
	$echo .= opencol();
	$echo .= "<b>".lienordre("nombreportef",lang(17))."</b>";
	$echo .= closecol();
	$echo .= opencol();
	$echo .= "<b>".lienordre("ansvaleur",lang(113))."</b>";
	$echo .= closecol();
	$echo .= opencol();
	$echo .= "<b>".lienordre("valeuractportef",lang(112))."</b>";
	$echo .= closecol();
	$echo .= opencol();
	$echo .= "<b>".lienordre("benefportef",lang(114))."(%)</b>";
	$echo .= closecol();
	$echo .= closeligne();
	foreach ($liste as $key => $value)
	{
		$echo .= openligne();
		$echo .= opencol();
		$echo .= htm_iconinfo($value["helpurl"],$value["nomsicav"])."&nbsp;&nbsp;".$value["nomsicav"];
		$echo .= closecol();
		$echo .= opencol();
		$echo .= $value["nombsicav"];
		$echo .= closecol();
		$echo .= opencol();
		$echo .= round($value["ansvaltotsicav"],2)." � ( ".$value["ansvalsicav"]." �)";
		$echo .= closecol();
		$echo .= opencol();
		$echo .= round($value["valtotsicav"],2)." � ( ".$value["valsicav"]." �)";
		$echo .= closecol();
		$echo .= opencol();
		$clr="";
		if(round($value["benefsicav"],2)>0)
		{
		        $clr="gain";
		}
		if(round($value["benefsicav"],2)<0)
		{
			$clr="perte";
		}
		$echo .= "<font class=\"$clr\">";
		$echo .= round($value["benefsicav"],2)." � ( ".round($value["pourcentsicav"],2)." %)";
		$echo .= "</font>";
		$echo .= closecol();
		$echo .= closeligne();
		$totalport += round($value["valtotsicav"],2);
		$totalbenef += round($value["benefsicav"],2);
		$affichagedate = date("j/m/y H:i",$value["laststamp"]);
	}
	//$echo .= closetab();
	
	
	
	

}else{
	$echo .= "<center>".lang(8)."<center>";
	$echo .= "<br><br>".opentab(" align=center width=\"90%\"");
}
$echo .= openligne().opencol("colspan=\"3\" align=\"right\" ","titre")."<b>".lang(24)."</b> : ".closecol().opencol();
$echo .= round($totalport,2)." �";
$echo .= closecol().opencol();
if($totalport<>0)
{
        $clr="";
	if($totalbenef>0)
	{
	        $clr="gain";
	}
	if($totalbenef<0)
	{
		$clr="perte";
	}
	$echo .= "<font class=\"$clr\">";
	if(($totalbenef<0 && $totalbenef/$totalport>0) || ($totalbenef>0 && $totalbenef/$totalport<0)) //corrige profits sur la vad
			$pourcbenef=round(($totalbenef/($totalport-$totalbenef))*100,2)*-1;
	else
            $pourcbenef=round(($totalbenef/($totalport-$totalbenef))*100,2);
	$echo .= round($totalbenef,2)." � ( ".$pourcbenef." %)";
	$echo.="</font>";
}else{
	$echo .= "0 � ( 0 %)";
}
$echo .= closecol().closeligne();

$echo .= openligne().opencol("colspan=\"3\" align=\"right\" ","titre")."<b>CashBack</b> :".closecol().opencol();
$echo .= ($internaute->cashback)." �";
$echo .= closecol().opencol();
$echo .= "-";
$echo .= closecol().closeligne();

$echo .= openligne().opencol("colspan=\"3\" align=\"right\" ","titre")."<b>".lang(115)."</b> :".closecol().opencol();
$echo .= ($internaute->cashback+round($totalport,2))." �";
$echo .= closecol().opencol();
$echo .= round(floatval($value["prog"]),2)." %"; //round((($totalport+$internaute->cashback-CAPDEB)/(CAPDEB))*100,2)." %";
$echo .= closecol().closeligne().closetab();

if($affichagedate<>"")
{	//$echo .= "<br><p align=\"right\">".lang(133).":<br>$affichagedate</p>";
}
return $echo;
}




function dovente($idcompte,$sicav,$nombre,$dernvaleur)
{

// on efface tout les caractere pouvant interferer avec le sql
$sicav=sec($sicav);
$nombre=sec($nombre);
//on verifie si cette vente est possible
//Le joue � t'il cette valeur et ce nombre d'action ?
$possede=joueur_possede($sicav,$idcompte);
$nivjoueur=niv_joueur($idcompte);
if(!$possede->nombsicav>0 && !$nivjoueur->vad)
{
	return lang(3);
}



$echo="";

//on affecte correctement le nombre en fonction du $typevente

//fin reafectation

if(!$nivjoueur->vad)
{
	if(intval($possede->nombsicav) < intval($nombre) or $nombre<=0) //si pas assez d'action pour vendre tout ca
	{
		return lang(1).intval($possede->nombsicav).lang(2);
	}
}else{
	//$nbactions=getnbactionmax(getmontantvadpossible($internaute->idcompte),$valeuraction)+$jpossede->nombsicav;
	$quantpos=$possede->nombsicav;
	if($quantpos<0)
                $quantpos=0;
    	$nbactionsmax=getnbactionmax(getmontantvadpossible($idcompte),$dernvaleur)+$quantpos; //si on ne possede pas cette action alors on calcul le nombre maximal d'action a vendre en d�couvert
	if(intval($nbactionsmax) < intval($nombre) or $nombre<=0) //si le joueur demande + que le levier
	{
		return lang(1).intval($nbactionsmax).lang(2);
	}
}

// tout les test on �t� effectu� maintenant l'on vend les actions et l'on cr�dite le compte
//tout d'abord on enleve les action
$NvQuant= intval($possede->nombsicav)-$nombre;
if($possede->nombsicav)
{
	ModifAction($idcompte,$sicav,$NvQuant,$dernvaleur);
}else{
 //si l'action est vendu en decouvert alors que le joueur n'en n'avait pas
    AjoutPort($idcompte,$sicav,$NvQuant,$dernvaleur);
}
//ensuite on credite le joueur
$acrediter=$nombre*$dernvaleur;
$taxe=gettaxe($dernvaleur,$nombre);//on enleve les taxes a ceci
$acrediter = $acrediter-$taxe; //
ModifLiquide($idcompte,$acrediter); //$acrediter contient le nombre d'euro  � ajouter
// a fortiori c'est bon l�
//on calcul les profits fait par le joueur
if($possede->nombsicav>$nombre) #calcul de la quantit�e qui reprensente une op�ration de cloture
{
        $quantvendu=$nombre;
}else{
	if($possede->nombsicav>0)
	{
		$quantvendu=$possede->nombsicav;
	}else{
                $quantvendu=0;
	}
}
$tottaxes=$taxe+gettaxe($possede->ansvaleur,$nombre);
$profit=($dernvaleur-$possede->ansvaleur)*$quantvendu-$tottaxes;
AddHistorique($idcompte,"Vente",$sicav,$nombre,$dernvaleur, -$taxe,$profit); //ajout a l'historique

return "OK";
}







function inscrjeu($pseudo,$nom,$prenom,$adresse,$cp,$ville,$tel,$mail,$etab,$niveau,$mailsemaine,$mailjour)
{
if(date("U")>=FINCONC)
{
	return lang(69);
}

$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
if(INCONC)
{
	$resultat=ExecRequete("SELECT pseudonyme, adresse FROM compte WHERE pseudonyme like '$pseudo' OR adresse like '$adresse' ",$connexion);
	while($r=mysql_fetch_array($resultat))
	{
		if(strtolower($r["adresse"])==strtolower($adresse))
		{
			return "Un seul compte par foyer autoris�, cette adresse est d�j� utilis�e par un autre joueur.";
		}else{
			return "Le pseudonyme saisie existe d�ja veuillez en entrer un diff�rend, cliquez sur le bouton pr�c�dent de votre navigateur.";
		}
	}
}else{
	$resultat=ExecRequete("SELECT pseudonyme,email FROM compte WHERE pseudonyme like '$pseudo' or email='$mail' ",$connexion);
	while($r=mysql_fetch_object($resultat))
	{	
		if($r->pseudonyme==$pseudo)
		{
			return lang(85);
		}else{
			return lang(92);
		}
	}
}
if(!($niveau>0))
{
	return "Niveau incorrect";
}
if(trim($pseudo)=="" || !($mailsemaine==0 ||$mailsemaine==1) || !($mailjour==0 ||$mailjour==1))
{
	return lang(170);
}

$passe=substr(md5(getmicrotime()), 0, 5);
$cryptpasse=md5($passe);
$maintenant=date("U");
if(INCONC)
{
	$result = ExecRequete("INSERT INTO `compte` ( `pseudonyme` , `nom` , `prenom` ,`passe`, `dateinscr`, `adresse` , `cp` , `ville` , `tel` , `email` , `etablissement`, `idniveau`, `cashback`)
	VALUES (
	'$pseudo', '$nom', '$prenom','$cryptpasse', '$maintenant', '$adresse', '$cp', '$ville', '$tel', '$mail', '$etab', '$niveau', '".CAPDEB."')
	",$connexion) or die("error");
}else{
	$result = ExecRequete("INSERT INTO `compte` ( `pseudonyme` ,`passe`, `dateinscr`, `email` , `cashback`, `idniveau`, `maildaily`, `mailweekly`)
	VALUES (
	'$pseudo','$cryptpasse', '$maintenant', '$mail', '".CAPDEB."', '$niveau', '$mailsemaine', '$mailjour')
	",$connexion) or die("error");
}


if(INCONC)
{	
	$corps="<Message g�n�r� automatiquement>\n<CONSERVEZ CE MESSAGE>\n\n  Bienvenue � Transac'Challenge, \n\n Votre inscription a �t� prise en compte et vous pouvez d�s maintenant jouer , voici les informations pour vous identifier: \n Login: $mail \n Mot de Passe: $passe  \n\n Vous pouvez � tout moment modifier toutes vos informations via le site de NetTrader Transac'Challenge:\n ".ADDRNTTRANSAC;
	$titre="Bienvenue � NetTrader - Transac'Challenge";			
}else{
	$corps="

<Message g�n�r� automatiquement>\n
<CONSERVEZ CE MESSAGE>\n
\n
  Bienvenue dans NetTrader 2, \n
\n
 Votre inscription a �t� prise en compte et vous pouvez d�s maintenant jouer , voici les informations pour vous identifier: \n
 Login: $mail \n
 Mot de Passe: $passe  \n
\n
 Vous pouvez � tout moment modifier toutes vos informations via le site de NetTrader 2:\n
 ".ADDRNT."

Sachez que les comptes sont supprim�s au bout de deux mois d'inactivit�s, pour conserver votre compte, il suffit de vous \n
identifier sur le site ou dans le logiciel au moins une fois tout les 59 jours.Vous ne serez pas avertis si votre compte est sur le point d'�tre supprim�.\n
La suppression d'un compte entra�ne �galement l'effacement de celui-ci du classement des mois �coul�s.

Bon Jeu ;)

L'auteur, FORTIN Nicolas
";
	$titre="Bienvenue dans NetTrader II";		
}
envoimail($mail, $titre,$corps);
$echo = "<br><br>Vous �tes inscrit, vous recevrez dans quelques minutes l'ensemble des informations concernant votre compte par email.";

return $echo;
}

function jscript_av($nombre)
{
$codesource= "<script language=\"Javascript\">
		  function SetValeur(pourcent) {
		  var nbr;
		  	if(pourcent>0 && pourcent<=100)
			{
		  nbr=Math.round(pourcent/100*$nombre);
		  document.form.nbr.value=nbr;
		  document.form.nb2.value=pourcent;
		  document.form.select[1].checked=true;
		  	}
		  }
		  function ChgQuant()
		  {
            document.form.select[0].checked=true;
		  }
		  function sela_click1()
		  {

			document.form.valmax.style.visibility=\"hidden\";
			document.form.valmin.style.visibility=\"hidden\";
		  }
			function sela_click2()
		  {  

			document.form.valmax.style.visibility=\"visible\";
			document.form.valmin.style.visibility=\"visible\";
		  }
		  function selv_click1()
		  {

			document.form.valmin.style.visibility=\"hidden\";
			document.form.valmax.style.visibility=\"hidden\";
		  }
			function selv_click2()
		  {  

			document.form.valmin.style.visibility=\"visible\";
			document.form.valmax.style.visibility=\"visible\";
		  }			  
</script>";
return $codesource;
}

function doachat($idcompte,$sicav,$nombre,$dernvaleur) //appel� lors de l'execution d'un ordre
{
// on efface tout les caractere pouvant interferer avec le sql
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$joueur= ChercheInternaute ($idcompte, $connexion);


$sicav=sec($sicav);
$nombre=sec(intval(sec($nombre)));
/* $ansvaleur=sec($ansvaleur); */



//cette action existe elle pour le joueur ?
$bddsico=dansliste($sicav);

if($bddsico=="")
{
	return lang(10);
}
$echo="";


$max=getnbactionmax($joueur->cashback,$dernvaleur);

if( $max < intval($nombre) or $nombre<=0) //si pas assez de cashback
{
	return lang(11).$max.lang(2);
}

// tout les test on �t� effectu� maintenant l'on ach�te les actions et l'on retire du cashback
//tout d'abord on enleve le cashback
$taxe=gettaxe($dernvaleur,$nombre);
$cout=$nombre*$dernvaleur + $taxe;

if( $cout > $joueur->cashback) //si pas assez de cashback
{
	return lang(11).$max.lang(2);
}

ModifLiquide($idcompte,-$cout); //$acrediter contient le nombre d'euro  � retirer
//ensuite on ajoute les actions au joueur
$possede=joueur_possede($sicav,$idcompte);

if($possede=="")
{ //le joueur en a pas
	AjoutPort($idcompte,$sicav,$nombre,$dernvaleur);
}else{ //le joueur en possede deja
	$NvQuant= $possede->nombsicav+$nombre;
	ModifAction($idcompte,$sicav,$NvQuant,$dernvaleur);
}
//ajouter a l'historique
//on calcul les profits fait par le joueur
if($possede->nombsicav>=0) #calcul de la quantit�e qui reprensente une op�ration de cloture
{
        $quantachat=0;
}else{
	if(abs($possede->nombsicav)>=$nombre)
	{
		$quantachat=$nombre;
	}else{
                $quantachat=abs($possede->nombsicav);
	}
}
$tottaxes=$taxe+gettaxe($possede->ansvaleur,$nombre);
$profit=-($dernvaleur-$possede->ansvaleur)*$quantachat;
AddHistorique($idcompte,"Achat",$sicav,$nombre,$dernvaleur, $taxe,$profit);
// a fortiori c'est bon l�
return "OK";
}


/* if($ecart<15*60) //si la plus veille a �t� t�l�charg� il y a moins de 10 minutes avec des valeurs datant d'il y a moins 20 minutes
	{
		$return=$maintenant + 60 + (60*15 - $ecart); //on t�l�charge dans 15 minutes apr�s la date du t�l�chargement
	}else{
		if($ecartdown<5*60)
		{ //le site t�l�chargera dans  $maintenant + (60*10 - $ecartdown)
			$return=$maintenant + 60 + (60*5 - $ecartdown);
		}else{ //superieur a 5min
			$return=$maintenant + 120; //on t�l�charge avant que ce soit le site
		}
	} */
	
function get_nextrefresh() 
{
//$timestamp=get_dernier_timestamp();//$lasttime contient la date la plus ancienne des valeurs
//$lasttime=$timestamp->lasttime;
//$lasttimedown=$timestamp->lasttimedown;
$maintenant = date ("U");
list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));

$date2 =  $maintenant + 60*9; // 9 minutes
$date3 = mktime("9", "01", "00", $mon, $day+2, $yr); //apr�s demain
$date4 = mktime("9", "01", "00", $mon, $day+1, $yr); //demain
$date5 = mktime("9", "01", "00", $mon, $day, $yr); //aujourd'hui
//$ecart=$maintenant-$lasttime;
//$ecartdown=$maintenant-$lasttimedown;
//echo $ecartdown."|";



if(date("w",$maintenant) >0 and date("w",$maintenant) <6)
{ //si la bourse est ouvert
	if(date("H",$maintenant) >=9 and date("H",$maintenant) <18)
	{
		$return= $maintenant + 100;
	}else{
		if(date("H",$maintenant) >=18)
		{
			//BOURSE FERME aujourd'hui � 18h00
			$return=$date4;
		}else{
			//bourse ferm� il est moins de 9h
			$return=$date5;
		}
	}
}else{
	//si week end
	//samedi ou dimanche
	if(date("w",$maintenant)==6)
	{ 
		//SAMEDI
		$return=$date3;
	}else{
		//DIMANCHE
		$return=$date4;
	}
}
$retour = $return-$maintenant;
if($retour<30){$retour=30;}
return $retour;
}



function execute_ordre()
{
//on charge la liste des ordres qui doivent �tre �x�cut�, pour les actions qui 'ont pas besoin d'�tre mis � jour
$liste=get_ordre();
if($liste<>"" and tempsjeu())
{
	foreach ($liste as $key => $value)
	{       $possede=joueur_possede($value["codesico"],$value["idcompte"]);
	        $nivjoueur=niv_joueur($value["idcompte"]);
		if($possede->nombsicav>0 || $value["sens"]=="achat" || $nivjoueur->vad)
		{
			$retourne = efface_ordre($value["codesico"],$value["idcompte"],$value["datecreation"]);
			if($retourne==0) //si on a rien �ffac� ca veut dire qu'une autre page est appel�e en m�me temps
			{
				return "";
			}
			//on effectue la modif si c'est un pourcentage
			if($value["pourc"]>0)
			{
				if($value["sens"]=="achat")
			    	{
					$nombre=floor(getnbactionmax(GetCashBack($value["idcompte"]),$value["valeur"])*$value["pourc"]);
				}
				if($value["sens"]=="vente")
				{
					if($nivjoueur->vad)
	                    			$possede->nombsicav=getnbactionmax(getmontantvadpossible($value["idcompte"]),$value["valeur"])+$possede->nombsicav; //si on ne possede pas cette action alors on calcul le nombre maximal d'action a vendre en d�couvert
					$nombre=floor($possede->nombsicav*$value["pourc"]);
				}
			}else{
				$nombre=$value["nbr"];
			}
			//on va appeler l'execution
			if($value["sens"]=="achat")
			{
				$echo = doachat($value["idcompte"],$value["codesico"],$nombre,$value["valeur"]);
				$multip=1;
				$sens = lang(46);
			}
			if($value["sens"]=="vente")
			{
				$echo = dovente($value["idcompte"],$value["codesico"],$nombre,$value["valeur"]);
				$multip=-1;
				$sens = lang(47);
			}
			if($echo=="OK")
			{ //codesico  idcompte  datecreation  sens  nbr  pourc  tempslim  coursmin  coursmax
				$taxe = gettaxe($value["valeur"],$nombre);
				$corps= "<br><br>".tab_mess_ordre($value["datecreation"],$value["tempslim"],$value["nom"],$value["sens"],$value["nbr"],$value["coursmin"],$value["valeur"],$value["coursmax"],$value["pourc"])."<br><br>"
				.$sens.": ".$value["nom"].
				"<br>".lang(14).": ".date("j/m/y H:i:s").
				"<br>".lang(43)." ".$value["valeur"]." �
				<br>".lang(51)." :".$nombre.
				"<br>".lang(44)." ".$value["valeur"]*$nombre." �
				<br>".lang(45)." ".$taxe."<br>".lang(20).": ".(($nombre*$value["valeur"])+($multip*$taxe))." �";
				//add_msg(1,$value["idcompte"],$sens." ".$value["nom"],$corps);
			}else{
				//on remet l'ordre � l'execution
				add_msg(1,$value["idcompte"],$sens.": ".lang(60).$value["nom"],lang(60).": ".$echo."<br><br>".tab_mess_ordre($value["datecreation"],$value["tempslim"],$value["nom"],$value["sens"],$value["nbr"],$value["coursmin"],$value["valeur"],$value["coursmax"],$value["pourc"])."<br><br>");
			}
		}
	}
}



return "";
}

function tab_mess_ordre($datecreation,$tempslim,$nom,$sens,$nombre,$valmin,$valeur,$valmax,$pourc)
{

if($nombre==0){$nombre=(round($pourc*100,2))." %";}
if($valmax==-1){$valmax="";}
if($valmin==0){$valmin="";}
$html=openfont().lang(41).closefont()." :<br>";
$html.=lang(50)." : ".date("j/m/y H:i:s",$datecreation)."<br>";
$html.=lang(65)." : ".date("j/m/y H:i:s",$tempslim)."<br>";
$html.=lang(15)." : ".$nom."<br>";
$html.=lang(16)." : ".$sens."<br>";
$html.=lang(51)." : ".$nombre."<br>";
$html.=lang(52)." : ".$valmin."<br>";
$html.=lang(53)." : ".$valeur."<br>";
$html.=lang(54)." : ".$valmax."<br><br>";
//$html = opentab(" align=center width=\"90%\"  ").openligne("","titre2").opencol("colspan=\"8\"").lang(41).closecol().closeligne().openligne("","titre").opencol()."<b>".lang(50)."</b>".closecol().opencol()."<b>".lang(65)."</b>".closecol().opencol()."<b>".lang(15)."</b>".closecol().opencol()."<b>".lang(16).closecol().opencol()."<b>".lang(51)."</b>".closecol().opencol()."<b>".lang(52)."</b>".closecol().opencol()."<b>".lang(53)."</b>".closecol().opencol()."<b>".lang(54)."</b>".closecol().closeligne();
//$html.= openligne().opencol().date("j/m/y H:i:s",$datecreation).closecol().opencol().date("j/m/y H:i:s",$tempslim).closecol().opencol().$nom.closecol().opencol().$sens.closecol().opencol().$nombre.closecol().opencol().$valmin.closecol().opencol().$valeur.closecol().opencol().$valmax.closecol().closeligne();
//$html.= closetab();

return $html;
}

function form_list_ordre()
{
return list_ordre_sens("achat")."<br>".list_ordre_sens("vente");
}


function list_ordre_sens($sens)
{
$retour="";
$tab=get_ordrelist("AND sens='$sens'");
$nbtotordres=count($tab);
if($nbtotordres>0 && is_array($tab)==true)
{
	
	$retour=jscript_ordre();
	$retour.=opentab(" align=center width=\"90%\"  ").openligne("","titre2").opencol("colspan=\"8\"").lang(55)." ($sens) :".closecol().opencol().htm_iconhelp("listordre").closecol().closeligne();

	$taba=get_ordrelist("AND tempslim>UNIX_TIMESTAMP() AND (NOT ".SECURE." OR lasttime>=datecreation) AND etat='1' AND sens='$sens'");
	$nbattordres=0;
	if(is_array($taba))
	{   $retour.=form_list_ordrefe($taba,lang(129)); //ordres en attente( attente d'�tre dans la bonne zone de valeur)
		$nbattordres=count($taba);
	}
	
	$tabb="";
	$tabc="";
    //echo "[[$nbtotordres-$nbattordres>0).]]";
	$nblimitordres=0;
	if($nbtotordres-$nbattordres>0)
	{
        $tabb=get_ordrelist("AND (tempslim<UNIX_TIMESTAMP() OR (".SECURE." AND lasttime<datecreation)) AND etat='1' AND sens='$sens'");
		//ordres non executable (limite de date de fin d�pass�,limite de date de debut non atteinte)
		if(is_array($tabb))
		{
			$retour.=form_list_ordrefe($tabb,lang(130),1);
        	$nblimitordres=count($tabb);
		}
	}
	if($nbtotordres-$nbattordres-$nblimitordres>0)
	{
		if($nbattordres+$nblimitordres > 0) $tabc=get_ordrelist("AND etat='0' AND sens='$sens'");
			else $tabc=$tab;
		$retour.=form_list_ordrefe($tabc,lang(131));//ordres �xecut�s
	}
	
	//

	$retour.=closetab();
}

return $retour;
}


function form_list_ordrefe($liste,$titre,$indiDecalage=0)
{
// liste des ordres en attente( attente d'�tre dans la bonne zone de valeur), ordres non executable (limite de date de fin d�pass�,limite de date de debut non atteinte), ordres �xecut�s
global $skinrep;
if($liste<>"")
{

$retour=openligne("","titre").opencol("colspan=\"9\"").$titre.closecol().closeligne();
$icontoutsuppr="<a href=\"index.php?do=supprtoutordre\" onclick=\"return confirmLink(this, '".lang(212)."')\"><img title=\"".lang(212)."\" src=\"$skinrep/suppr.gif\" border=\"0\"></a>";
$retour.=openligne("","titre").opencol().$icontoutsuppr.closecol().opencol()."<b>".lang(50)."</b>".closecol().opencol()."<b>".lang(65)."</b>".closecol().opencol()."<b>".lang(15)."</b>".closecol().opencol()."<b>".lang(16).closecol().opencol()."<b>".lang(51)."</b>".closecol().opencol()."<b>".lang(52)."</b>".closecol().opencol()."<b>".lang(53)."</b>".closecol().opencol()."<b>".lang(54)."</b>".closecol().closeligne();
foreach ($liste as $key => $value)
	{
	$retour.=openligne();
	$nombre=$value["nbr"];
	if($nombre==0){$nombre=(round($value["pourc"]*100,2))." %";}
	$valmax=$value["coursmax"];
	$valmin=$value["coursmin"];
	if($valmax==-1){$valmax="";}
	if($valmin==0){$valmin="";}
	$aj="";
	$calc=$value["datecreation"]-$value["lasttime"];
	//if($indiDecalage  && $value["datecreation"]-$value["lasttime"]>0){$aj=" (".date("H:i:s",$value["datecreation"]-$value["lasttime"]).")";}
	if($indiDecalage  && $calc>0 && $calc<3600){$aj=" (".date("i \m\i\\n",$calc).")";}
        $clr="";
	if($value["tempslim"]<date("U"))
                $clr="perte";
	$retour.=opencol()."<a href=\"index.php?do=supprordre&idordre=".$value["datecreation"]."\" onclick=\"return confirmLink(this, '".date("j/m/y H:i:s",$value["datecreation"])."')\"><img title=\"".lang(66)."\" src=\"$skinrep/suppr.gif\" border=\"0\"></a>".closecol().opencol().date("j/m/y H:i:s",$value["datecreation"]).$aj.closecol().opencol()."<font class=\"$clr\">".date("j/m/y H:i:s",$value["tempslim"])."</font>".closecol().opencol().$value["nom"].closecol().opencol().$value["sens"].closecol().opencol().$nombre.closecol().opencol().$valmin.closecol().opencol().$value["valeur"].closecol().opencol().$valmax.closecol();
	//$retour=$retour.$head."<a href=".$value["link_menu"].">".lang($value["text_id"])."</a>".$footer;
	$retour.=closeligne();
	}
}else{
//$retour=lang(49);
$retour="";
}
return $retour;
}




function htm_iconhelp($form)
{
global $skinrep;
$html="<a href=\"index.php?do=formhelp#$form\"><img align=\"right\" border=\"0\" src=\"$skinrep/interr.gif\"></a>";

return $html;
}

function htm_iconinfo($siconame,$nom)
{
global $skinrep;
//$html="<a href=\"http://fr.finance.yahoo.com/q?s=$siconame&d=c\" target=\"_blank\"><img title=\"".lang(134).$nom."\" src=\"$skinrep/info.gif\" border=\"0\"></a>";
$html="<a href=\"index.php?do=profilaction&yn=$siconame\"><img title=\"".lang(134).$nom."\" src=\"$skinrep/info.gif\" border=\"0\"></a>";
return $html;
}

function profilaction($yahooname)
{
$limit=date("U")-24*3600*9;//historique depuis
//On r�cup�re les information de l'action, tout d'abord ca ligne
$laction=donnactionyn($yahooname);
//Puis sur la derni�re semaine le nombre d'achat et vente la concernant
$lstat=stataction($laction->codesico,$limit);
//FROM_UNIXTIME( temps, '%d/%m/%Y' ) AS jour, sens,AVG(valeurunique) as valeurechang,SUM( nbr ) as nb
//Puis les ordres actuellement en attente pour cette action regroup� par valeur
$lordrea=ordreactionachat($laction->codesico,$laction->lasttime,$laction->valeur);
$lordrev=ordreactionvente($laction->codesico,$laction->lasttime,$laction->valeur);
$corps=lang(15)." : $laction->nom<br>";
$corps.=lang(180)." : $laction->libellesecteur<br>";
$corps.=lang(53)." : $laction->valeur �<br>";
$corps.=lang(14)." : ".date("j M Y H:i a",$laction->lasttime)."<br>";
$corps.="<a href=\"".geturlaide($yahooname)."\" target=\"_blank\" title=\"".lang(134).$laction->nom."\">".lang(185)."</a>";

$lignevide=openligne().opencol()."-".closecol().opencol()."-".closecol().opencol()."-".closecol().opencol()."-".closecol().opencol()."-".closecol().opencol()."-".closecol().closeligne();
$lignevide2=openligne().opencol()."-".closecol().opencol()."-".closecol().opencol()."-".closecol().opencol()."-".closecol().closeligne();
$tabhisto=opentab("width=\"100%\"").openligne("","titre2").opencol(" colspan=\"6\" ").lang(12)." ".lang(6).closecol().closeligne().openligne("","titre").opencol().lang(14).closecol().opencol().lang(16).closecol().opencol().lang(17).closecol().opencol().lang(181).closecol().opencol().lang(210).closecol().opencol().lang(273).closecol().closeligne();
$i=0;
while($ligne=LigneSuivante($lstat))
{
	//$tabhisto.="Le ".$ligne->jour.", ".$ligne->sens." de $ligne->nb actions � $ligne->valeurechang euros<br>";
	$tabhisto.=openligne().opencol().$ligne->jour.closecol().opencol().$ligne->sens.closecol().opencol().$ligne->nb.closecol().opencol().round($ligne->valeurechang,2)." �".closecol().opencol().round($ligne->profit,2)." �".closecol().opencol().round($ligne->perte,2)." �".closecol().closeligne();
	$i++;
}
if($i<9)
{
	$tabhisto.=str_repeat($lignevide,9-$i);
}
$tabhisto.=closetab();


$tabordres=opentab("width=\"100%\"").openligne("","titre2").opencol(" colspan=\"4\" ").lang(184).closecol().closeligne().openligne("","titre").opencol().lang(182).closecol().opencol().lang(112).closecol().opencol().lang(183).closecol().opencol().lang(51).closecol().closeligne();
$i=0;
$ltabordres="";
while($ligne=LigneSuivante($lordrev))
{
	if( intval($ligne->quant)==0)
	{ 
	 $ligne->quant="-";
	}
	if( intval($ligne->prc)==0)
	{ 
	 $ligne->prc="-";
	}else{
	 $ligne->prc=round($ligne->prc,2)." %";
	}
	$ltabordres.=openligne().opencol().lang(47).closecol().opencol().$ligne->valeur." �".closecol().opencol().$ligne->prc.closecol().opencol().$ligne->quant.closecol().closeligne();
	$i++;
}
if($i<4)
{
	$tabordres.=str_repeat($lignevide2,4-$i).$ltabordres;
}else{
	$tabordres.=$ltabordres;
}

$tabordres.=openligne().opencol().lang(53).closecol().opencol().$laction->valeur." �".closecol().opencol()."-".closecol().opencol()."-".closecol().closeligne();
$ltabordres="";
$i=0;
while($ligne=LigneSuivante($lordrea))
{
	if( intval($ligne->quant)==0)
	{ 
	 $ligne->quant="-";
	}
	if( intval($ligne->prc)==0)
	{ 
	 $ligne->prc="-";
	}else{
	 $ligne->prc=round($ligne->prc,2)." %";
	}
	$ltabordres.=openligne().opencol().lang(46).closecol().opencol().$ligne->valeur." �".closecol().opencol().$ligne->prc.closecol().opencol().$ligne->quant.closecol().closeligne();
	$i++;
}
if($i<4)
{
	$tabordres.=$ltabordres.str_repeat($lignevide2,4-$i);
}else{
	$tabordres.=$ltabordres;
}

$tabordres.=closetab();
$corps.=opentab("width=\"100%\"","invi").openligne().opencol().$tabhisto.closecol().opencol().$tabordres.closecol().closeligne().closetab();

$html=msgtab($corps,$laction->nom." - ".$laction->libellesecteur);

return $html;
}


function txt_help($idhelpshowcomment=0) //idhelpshowcomment id de l'aide o� il faut afficher les commentaire
{
//d'abord l'index de l'aide
//ensuite les blocs d'aides
//ensuite l'index de faq
//ensuite les blocs de faq
global $internaute;
$html="";
$req=get_listeaide();
$reqc=get_listecomment($idhelpshowcomment);
$liste="";
$laide="";
$txtaide="";
$anschap=0;
while($ligne=LigneSuivante($req))
{
	if($anschap<>$ligne->idchapaide)
	{
		if($liste<>"") $liste.="</ul>";
		$anschap=$ligne->idchapaide;
        $liste.=openfont("titre1")."$ligne->titrechap".closefont()."<ul>";
	}
	$liste.="<li><a href=\"#$ligne->lnkaide\"> $ligne->titreaide</a></li><br>";
	$laidetitre="<a name=\"$ligne->lnkaide\">".$ligne->titreaide."</a>";
	$laide.=$ligne->txtaide."<br>".html_lien($ligne->nbcomment." ".lang(159)." >>",getnewurl("idaide",$ligne->idligne)."#$ligne->lnkaide")."<br><br><br>";
	if($ligne->idligne==$idhelpshowcomment)
	{
        while($lignecomment=LigneSuivante($reqc))
		{
			$messsuppr="";
			if($lignecomment->auteurid==$internaute->idcompte || $internaute->authlevel>1) $messsuppr=html_lien("[ ".lang(163)." ]","do=suppcomment&idcomment=$lignecomment->idcomment#$ligne->lnkaide");
            $laide.="<br><hr>".lang(161)." ".$lignecomment->pseudonyme." ".lang(162)." ".date("j M Y H:i a",$lignecomment->datecomment)." ".$messsuppr."<br><br>".bbtohtml($lignecomment->textecomment);
		}
		//On affiche le formulaire de saisie de commentaire
		if($internaute->authlevel>=1)
		{
			$laide.="<br><hr><br><br><form method=\"POST\" action=\"index.php?".getnewurl("do","postemessage")."#$ligne->lnkaide\"><input type=\"hidden\" name=\"idaide\" value=\"$idhelpshowcomment\"><center><textarea name=\"message\" rows=\"8\" cols=\"25\" wrap=\"virtual\" style=\"width:450px\" tabindex=\"3\" class=\"post\"></textarea></center><br><br><center>".Html_bouton('valider',lang(164))."</center></form>";
		}
	}
	$txtaide.=msgtab($laide,$laidetitre);
	$laide="";
}
$liste.="</ul>";


$html.=msgtab($liste,lang(158)).$txtaide;


//$html=opentab(" align=\"center\" width=\"90%\" ").openligne("","titre").opencol()."Aide de NetTrader".closecol().closeligne().openligne().opencol().$html.closecol().closeligne().closetab();

$html=openfont("titre1").lang(160).closefont()."<br><br>".$html;

return $html;
}


function txt_faq($idhelpshowcomment=0) //idhelpshowcomment id de l'aide o� il faut afficher les commentaire
{
//d'abord l'index de l'aide
//ensuite les blocs d'aides
//ensuite l'index de faq
//ensuite les blocs de faq
global $internaute;
$html="";
$req=get_listefaq();
$reqc=get_listecommentfaq($idhelpshowcomment);
$liste="";
$laide="";
$txtaide="";
$anschap=0;
$liste.="<ul>";
while($ligne=LigneSuivante($req))
{
	$liste.="<li><a href=\"#$ligne->lnkaide\"> $ligne->titreaide</a></li><br>";
	$laidetitre="<a name=\"$ligne->lnkaide\">".$ligne->titreaide."</a>";
	$laide.=$ligne->txtaide."<br><br>".html_lien($ligne->nbcomment." ".lang(165)." >>",getnewurl("idaide",$ligne->idligne)."#$ligne->lnkaide")."<br><br><br>";
	if($ligne->idligne==$idhelpshowcomment)
	{
        while($lignecomment=LigneSuivante($reqc))
		{
			$messsuppr="";
			if($lignecomment->auteurid==$internaute->idcompte || $internaute->authlevel>1) $messsuppr=html_lien("[ ".lang(163)." ]","do=suppcommentfaq&idcomment=$lignecomment->idcomment#$ligne->lnkaide");
            $laide.="<br><hr>".lang(161)." ".$lignecomment->pseudonyme." ".lang(162)." ".date("j M Y H:i a",$lignecomment->datecomment)." ".$messsuppr."<br><br>".bbtohtml($lignecomment->textecomment);
		}
		//On affiche le formulaire de saisie de commentaire
		if($internaute->authlevel>=1)
		{
			$laide.="<br><hr><br><br><form method=\"POST\" action=\"index.php?".getnewurl("do","postemessagefaq")."#$ligne->lnkaide\"><input type=\"hidden\" name=\"idaide\" value=\"$idhelpshowcomment\"><center><textarea name=\"message\" rows=\"8\" cols=\"25\" wrap=\"virtual\" style=\"width:450px\" tabindex=\"3\" class=\"post\"></textarea></center><br><br><center>".Html_bouton($nom,lang(164))."</center></form>";
		}
	}
	$txtaide.=msgtab($laide,$laidetitre);
	$laide="";

}
$liste.="</ul>";

$html.=msgtab($liste,lang(158)).$txtaide;


//$html=opentab(" align=\"center\" width=\"90%\" ").openligne("","titre").opencol()."Aide de NetTrader".closecol().closeligne().openligne().opencol().$html.closecol().closeligne().closetab();

$html=openfont("titre1").lang(166).closefont()."<br><br>".$html;

return $html;
}

function supprtoutordre()
{
global $internaute;
if(tempsjeu())
{
	$message=lang(67);
	effacordresinactifs();
}else{
	$message=lang(69);
}
return $message;
}

function supprordre($dateordre)
{
global $internaute;
if(tempsjeu())
{
	$ordre=get_info_ordre($dateordre);
	if(!$ordre->datecreation) return lang(67);
	if(!SECURE || ($ordre->datecreation>date("U")-5*60 ||  $ordre->datecreation<date("U")-20*60) || $ordre->etat==0 )
	{
		$message=lang(67);
		del_ordre($dateordre);
	}else{
		$message=lang(128).date("i \m\i\\n s \s\e\c",$ordre->datecreation+(20*60)-date("U"));
	}
}else{
	$message=lang(69);
}
return $message;
}


function jscript_ordre()
{
$html="\n<script language=\"Javascript\">
function confirmLink(theLink, theSqlQuery)
{
    // Confirmation is not required in the configuration file
    // or browser is Opera (crappy js implementation)
    var is_confirmed = confirm('Voulez-vous supprimer l\'ordre du : ' + theSqlQuery);
    if (is_confirmed) {
        document.location.href=theLink;
    }

    return is_confirmed;
} 
</script>";
return $html;
}

function jscript_inscr()
{
//$_POST['pseudo'],$_POST['passe'],$_POST['nom'],$_POST['prenom'],$_POST['adresse'],$_POST['cp']
//,$_POST['ville'],$_POST['tel'],$_POST['mail'],$_POST['etab']);
$html="<script language=\"Javascript\">
function test() 
{
	if (document.forminscr.pseudo.value == \"\" || document.forminscr.mail.value == \"\" ";
	
	if(INCONC)
	{
		$html .= " || document.forminscr.nom.value == \"\" || document.forminscr.prenom.value == \"\" || document.forminscr.adresse.value == \"\" || document.forminscr.cp.value == \"\" || document.forminscr.ville.value == \"\" || document.forminscr.tel.value == \"\" || document.forminscr.etab.value == \"\"";
	}
	 $html.= ")
	

	
	{
		window.alert(\"Veuillez remplir tout les champs.\");
		return false;
	}
	if (verif_email(document.forminscr.mail.value)== false)
	{
		window.alert(\"L'email doit obligatoirement �tre valide !\");
		return false;
	}
	document.forminscr.Submit.disabled=true;
	return true;
}
function verif_email(varp)\n
{\n
if (varp.indexOf(\"@\")==-1)\n
{\n
alert(\"Une adresse E-mail doit contenir un '@'\");\n
return false;\n
}\n
if (varp.indexOf(\".\")==-1)\n
{\n
alert(\"Une adresse E-mail doit contenir au moins un '.'\");\n
return false;\n
}\n
\n
if ((varp.indexOf(\" \")!=-1)||(varp.indexOf(\";\")!=-1)||\n
(varp.indexOf(\",\")!=-1)||\n
(varp.indexOf(\"&\")!=-1)||(varp.indexOf(\"�\")!=-1)||\n
(varp.indexOf(\"�\")!=-1)||\n
(varp.indexOf(\"�\")!=-1)||(varp.indexOf(\";\")!=-1)||\n
(varp.indexOf(\"�\")!=-1)||\n
(varp.indexOf(\"|\")!=-1)||(varp.indexOf(\"�\")!=-1)||\n
(varp.indexOf(\"�\")!=-1)||\n
(varp.indexOf(\"�\")!=-1)||(varp.indexOf(\"�\")!=-1)||\n
(varp.indexOf(\"%\")!=-1)||\n
(varp.indexOf(\"?\")!=-1)||(varp.indexOf(\"!\")!=-1)||\n
(varp.indexOf(\"�\")!=-1)||\n
(varp.indexOf(\":\")!=-1)||(varp.indexOf(\"/\")!=-1)||\n
(varp.indexOf(\"�\")!=-1)||\n
(varp.indexOf(\"{\")!=-1)||(varp.indexOf(\"}\")!=-1)||\n
(varp.indexOf(\"(\")!=-1)||\n
(varp.indexOf(\"[\")!=-1)||(varp.indexOf(\"]\")!=-1)||\n
(varp.indexOf(\")\")!=-1)||\n
(varp.indexOf(\"`\")!=-1)||(varp.indexOf(\"=\")!=-1)||\n
(varp.indexOf(\"+\")!=-1)||\n
(varp.indexOf(\"<\")!=-1)||(varp.indexOf(\">\")!=-1)||\n
(varp.indexOf(\"~\")!=-1))
{\n
alert(\"Une adresse E-mail ne doit pas contenir de caract�res sp�ciaux\")\n
return false\n
}\n
var indexa = varp.indexOf(\"@\");\n
var lindexa = varp.lastIndexOf(\"@\");\n
if (indexa != lindexa){\n
alert(\"Une adresse E-mail ne peut pas contenir plusieurs '@'\");\n
return false;\n
}\n
var lindexp = varp.lastIndexOf(\".\"); \n
if(lindexp < indexa){\n
alert(\"Il doit y avoir un '.' APRES le @\");\n
return false\n
}\n
var longadr = varp.length;\n
lastindex = longadr-1;\n
if(lindexp == lastindex){\n
alert(\"Il doit y avoir une extension apres le '.' (.fr .com)\");\n
return false;\n
}\n
}
</script>";
return $html;
}

function jscript_groupe()
{
//$_POST['pseudo'],$_POST['passe'],$_POST['nom'],$_POST['prenom'],$_POST['adresse'],$_POST['cp']
//,$_POST['ville'],$_POST['tel'],$_POST['mail'],$_POST['etab']);
$html="<script language=\"Javascript\">
function test()
{
	if (document.frmajmodifgroupe.titreeq.value == \"\" || document.frmajmodifgroupe.titreeqcourt.value == \"\" )
	{
		window.alert(\"Veuillez remplir tout les champs obligatoire.\");
		return false;
	}
}
</script>";
return $html;
}

function back_link()
{
global $internaute;
$echo="<br><center>";
$echo.="<a href=\"http://nettrader.apinc.org/phpBB2/\" class=\"Lienbas\" target=\"_blank\" >[ Forum NetTrader2 ]</a>";
$echo.=" - <a href=\"index.php?do=reglement\" class=\"Lienbas\" >[ Reglement ]</a>";
if($internaute->authlevel>=1)
{
$echo.=" - <a href=\"index.php?do=profil\" class=\"Lienbas\" >[ Profil ]</a>";
}
if($internaute->authlevel>=2)
{
	$echo.=" - <a href=\"index.php?do=formadmin\" class=\"Lienbas\" >[ Administration ]</a>";
}
$echo.="</center>";

return $echo;
}
function chgmdp($nouvpass,$nouvpassconfirm)
{
	global $internaute;
	if(IDCOMPTEDEMO==$internaute->idcompte)
		return "";
	if($nouvpass<>"")
	{
		if($nouvpass<>$nouvpassconfirm)
		{
			return "Le nouveau mot de passe ne correspond pas au mot de passe de confirmation, vous devez entrez le m�me mot de passe dans ces deux champs.";
		}else{
			$passe=md5($nouvpass);
		}
	}else{
		$passe=$internaute->passe;
	}


$chainesql= "
UPDATE `compte` SET
`passe` = '".md5($nouvpass)."'
 WHERE `idcompte` = '$internaute->idcompte'";

$corps="
Bonjour,\n\n

Votre demande de modification de mot de passe est effectu�, voici votre nouveau mot de passe :\n\n

login:$internaute->email\n
passe:$nouvpass\n\n

Veuillez imprimer, sauvegarder ou noter ces informations afin de ne pas les perdre.\n\n

-Nicolas
";
envoimail($internaute->email,"Changement de mot de passe",$corps);
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$result = ExecRequete($chainesql,$connexion);

return lang(87);
}

function editprofil($mail,$niveau,$nbhisto,$nbmsg,$nbclasse,$idskin,$mailjour,$mailsemaine)
{
global $internaute;
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);

if($internaute->pseudonyme=="demo" and $internaute->email!=$mail) return "Vous ne pouvez pas changer l'email";


if(!($nbhisto>0 && $nbmsg >0 && $nbclasse >0))
{
	return "Le nombre de ligne entr� est incorrect !";
}

if(skin_existe($idskin)==0)
{
	return "Cette skin n'existe pas !";
}

if( !($mailsemaine==0 ||$mailsemaine==1) || !($mailjour==0 ||$mailjour==1))
	return "";
$chainesql= "
UPDATE `compte` SET
`email` = '$mail',
`idniveau` = '$niveau',
`histonbl` = '$nbhisto',
`msgnbl` = '$nbmsg',
`classenbl` = '$nbclasse',
`skin` = '$idskin',
`maildaily` = '$mailjour',
`mailweekly` = '$mailsemaine'
 WHERE `idcompte` = '$internaute->idcompte'";


$result = ExecRequete($chainesql,$connexion);

return lang(87);
}

function jscript_profil()
{
//$_POST['pseudo'],$_POST['passe'],$_POST['nom'],$_POST['prenom'],$_POST['adresse'],$_POST['cp']
//,$_POST['ville'],$_POST['tel'],$_POST['mail'],$_POST['etab']);
$html="<script language=\"Javascript\">
function test() 
{
	if (document.forminscr.mail.value == \"\" || document.forminscr.lvl.value == \"\" )
	{
		window.alert(\"Veuillez remplir tout les champs.\");
		return false;
	}


	document.forminscr.Submit.disabled=true;
	return true;
}
</script>";
return $html;
}

function jscript_profil2()
{
//$_POST['pseudo'],$_POST['passe'],$_POST['nom'],$_POST['prenom'],$_POST['adresse'],$_POST['cp']
//,$_POST['ville'],$_POST['tel'],$_POST['mail'],$_POST['etab']);
$html="<script language=\"Javascript\">
function test()
{
	if (document.forminscr.nmdp.value != document.forminscr.cnmdp.value )
	{
		window.alert(\"Le nouveau mot de passe de correspond pas � la confirmation(champs Confirmation), veuillez saisir le m�me mot de passe dans la zone de texte de confirmation.\");
		return false;
	}
	document.forminscr.Submit.disabled=true;
	return true;
}
</script>";
return $html;
}


function classementequipes($ligncour,$moisan,$cherche="") //feuille du classement des �quipes
{
global $internaute,$skinrep;
$equipeinternaute=getgroupbymembre($internaute->idcompte);

if($cherche=="" && $equipeinternaute->idgroupe>0 )
{
	$cherche=$equipeinternaute->titregroupe;
	$chaffiche="";
}else{
	$chaffiche=$cherche;
}

list($mon, $yr) = explode("-",$moisan);
$date1 = mktime("01", "01", "01", $mon, "1", $yr);
$ladate=date("Y-m-d",$date1);

if($ligncour<0)
{
	$ligncour=0;
}
if($internaute->authlevel>=1)
{
	$maxligne=$internaute->classenbl;
}else{
	$maxligne=30;
}

$res = listclassementequipe($ladate,$ligncour,$maxligne,$cherche);
$theliste=$res->liste;
$secondeliste=$res->spec;
$pos=$res->classement;
$deb=$res->deb;
$numligne=$res->nb;

$form="<center>";
$listemois=listmoisclassequipe();
$form.=retiftrue("<a href=\"index.php?do=classement\">".lang(13)."</a> - <a href=\"index.php?do=classementequipe\">".lang(229)."</a><br><br>",ACTIVATION_GROUPE);
$form.="<FORM  METHOD='GET' ACTION='index.php' NAME='Form'>";
$form.=lang(228)." : ".Html_texte("cherche",$chaffiche,30,50)."<INPUT type=\"hidden\" name=\"do\" value=\"classementequipe\">";
$form.="&nbsp;&nbsp;&nbsp;";
$form.=Html_liste("moisclasse",$listemois,"",$moisan);
$form.=Html_bouton("valider","Afficher");
$form.="</form>";
$form.="</center>";
if($theliste==""){return $form.msgtab("Pas d'�quipes !","Info :");}
$barre=barrepage($numligne,$maxligne,$ligncour,"&moisclasse=$moisan&cherche=$chaffiche");
$retour=$barre;
$span=3;

$retour.="<br>".opentab(" align=\"center\"  width=\"90%\"  ").openligne("","titre2").opencol("colspan=\"$span\"").lang(229).closecol().opencol().htm_iconhelp("formclasseequipe").closecol().closeligne().openligne("","titre").
opencol()."<b>".lang(61)."</b>".closecol().opencol()."<b>".lienordre("nomequipeclasse",lang(189))."</b>".closecol().opencol()."<b>".lienordre("pourcbenefclasse",lang(22))."</b>".closecol().opencol()."<b>".lienordre("nbjoueursclasse",lang(230))."</b>".closecol();
$retour.=closeligne();

$i=$ligncour;
$trouve=0;
for($li=0;$li<count($theliste);$li++)
   {
   $value=$theliste[$li];
   $i++;
	if(!compareclass(sec($value["titregroupe"]),$cherche))
	{
		$retour.=openligne();
	 }else{
		$retour.=openligne("","titre2");
		$trouve=1;
	}
	$retour.=opencol().$i.closecol().opencol().stripslashes ($value["titregroupe"])." - "."<a href=\"?do=viewgroupeprofil&idgroupe=".$value["idgroupe"]."\">".$value["initialgroupe"]."</a>&nbsp;".str_repeat("<IMG SRC=\"$skinrep/premier.png\" border=0>",$value["medor"]).str_repeat("<IMG SRC=\"$skinrep/deus.png\" border=0>",$value["medargent"]).str_repeat("<IMG SRC=\"$skinrep/tres.png\" border=0>",$value["medbronze"]).closecol().opencol().$value["prog"]." %".closecol().opencol().$value["nbjoueurs"].closecol();
	$retour.=closeligne();
   }
$retour.=closetab()."<br>";

$retour.=$barre;
/*
if(($numligne>$pos or $maxligne+$numligne<$pos) and $secondeliste<>"") //le joueur n'est pas affich� sur la page, il faut trouver son classement puis
{
	$retour= $form."<br><br>".sous_formclasse($deb,$pos,$secondeliste,$cherche).$retour;
}else{
*/
	$retour=$form.$retour;
//}


return $retour;
}










function formclasse($ligncour,$moisan,$cherche="") //feuille du classement des joueurs
{
global $internaute;
//echo "[".$moisan."]";

if($cherche=="" && $internaute->authlevel>=1 )
{
	$cherche=$internaute->pseudonyme;
	$chaffiche="";
}else{
	$chaffiche=$cherche;
}

list($mon, $yr) = explode("-",$moisan);
$date1 = mktime("01", "01", "01", $mon, "1", $yr);
$ladate=date("Y-m-d",$date1);

if($ligncour<0)
{
	$ligncour=0;
}
if($internaute->authlevel>=1)
{
	$maxligne=$internaute->classenbl;
}else{
	$maxligne=30;
}
//echoadmin("##### $ligncour,$numligne #####");
//echoadmin("##### $ligncour|$maxligne|$ladate #####");
$res = listclassement($ladate,$ligncour,$maxligne,$cherche);
$nomsgroupes=gettabjoueursenequipes();
$theliste=$res->liste;
$secondeliste=$res->spec;
$pos=$res->classement;
$deb=$res->deb;
$numligne=$res->nb;

$form="<center>";
$listemois=listmoisclass();
$form.=retiftrue("<a href=\"index.php?do=classement\">".lang(13)."</a> - <a href=\"index.php?do=classementequipe\">".lang(229)."</a><br><br>",ACTIVATION_GROUPE);

$form.="<FORM  METHOD='GET' ACTION='index.php' NAME='Form'>";
$form.=lang(142)." : ".Html_texte("cherche",$chaffiche,30,50)."<INPUT type=\"hidden\" name=\"do\" value=\"classement\">";
$form.="&nbsp;&nbsp;&nbsp;";
$form.=Html_liste("moisclasse",$listemois,"",$moisan);
$form.=Html_bouton("valider","Afficher");
$form.="</form>";
$form.="</center>";
if($theliste==""){return $form.msgtab("Pas de joueurs !","Info :");}
$barre=barrepage($numligne,$maxligne,$ligncour,"&moisclasse=$moisan&cherche=$chaffiche");
$retour=$barre;
if(INCONC)
{
$span=4;
}else{
$span=3;
}
$retour.="<br>".opentab(" align=\"center\"  width=\"90%\"  ").openligne("","titre2").opencol("colspan=\"$span\"").lang(13).closecol().opencol().htm_iconhelp("formclasse").closecol().closeligne().openligne("","titre").opencol()."<b>".lang(61)."</b>".closecol().opencol()."<b>".lienordre("pseudoclasse",lang(21))."</b>".closecol().opencol()."<b>".lienordre("capitalclasse",lang(24))."</b>".closecol().opencol()."<b>".lienordre("pourcbenefclasse",lang(22))."</b>".closecol();
if(INCONC)
{
$retour.=opencol()."<b>".lang(23)."</b>".closecol();
}
$retour.=closeligne();

$i=$ligncour;
$trouve=0;
for($li=0;$li<count($theliste);$li++)
   {
   $value=$theliste[$li];
   $i++;
	if(!compareclass(sec($value["pseudonyme"]),$cherche))
	{ 
		$retour.=openligne();
	 }else{
		$retour.=openligne("","titre2");	
		$trouve=1;
	}
        $nomgroupe="";
        if(array_key_exists($value["idcompte"],$nomsgroupes))
             $nomgroupe="&nbsp;<a href=\"?do=viewgroupeprofil&idgroupe=".$nomsgroupes[$value["idcompte"]][1]."\"><font class=\"gain\"><b>[".$nomsgroupes[$value["idcompte"]][0]."]</b></font></a>";
	$retour.=opencol().$i.closecol().opencol().retiftrue(" <a href=\"?do=incarner&idcompte=".$value["idcompte"]."\"><img src=\"skin/default/images/interr.gif\" border=\"0\"></a> ",$internaute->authlevel>1).stripslashes($value["pseudonyme"]).$nomgroupe.closecol().opencol().$value["capital"]." �".closecol().opencol().$value["prog"]." %".closecol();
	$retour.=closeligne();
   }
$retour.=closetab()."<br>";

$retour.=$barre;
if(($numligne>$pos or $maxligne+$numligne<$pos) and $secondeliste<>"") //le joueur n'est pas affich� sur la page, il faut trouver son classement puis
{
	$retour= $form."<br><br>".sous_formclasse($deb,$pos,$secondeliste,$cherche,$nomsgroupes).$retour;
}else{
	$retour=$form.$retour;
}


return $retour;
}

function sous_formclasse($ligncour,$pos,$theliste,$cherche,$nomsgroupes)
{
if($theliste==""){return "";}
$retour="";
if(INCONC)
{
$span=4;
}else{
$span=3;
}

$retour.="<br>".opentab(" align=\"center\"  width=\"90%\"  ").openligne("","titre2").opencol("colspan=\"$span\"").lang(13).closecol().opencol().htm_iconhelp("formclasse").closecol().closeligne().openligne("","titre").opencol()."<b>".lang(61)."</b>".closecol().opencol()."<b>".lang(21)."</b>".closecol().opencol()."<b>".lang(24)."</b>".closecol().opencol()."<b>".lang(22)."</b>".closecol();
if(INCONC)
{
$retour.=opencol()."<b>".lang(23)."</b>".closecol();
}
$retour.=closeligne();
$i=$ligncour;
for($li=0;$li<count($theliste);$li++)
   {
   $value=$theliste[$li];
   $i++;
	if(!compareclass($value["pseudonyme"],$cherche))
	{ 
		$retour.=openligne();
	 }else{
		$retour.=openligne("","titre2");	
	}
	$retour.=opencol().$i.closecol().opencol().stripslashes ($value["pseudonyme"]).retiftrue("&nbsp;<a href=\"?do=viewgroupeprofil&idgroupe=".$nomsgroupes[$value["idcompte"]][1]."\"><font class=\"gain\"><b>[".$nomsgroupes[$value["idcompte"]][0]."]</b></font></a>",$nomsgroupes[$value["idcompte"]][0]).closecol().opencol().$value["capital"].closecol().opencol().$value["prog"]." %".closecol();
	if(INCONC)
	{
		$retour.=opencol().stripslashes ($value["etablissement"]).closecol();
	}
	
	$retour.=closeligne();
   }
$retour.=closetab()."<br>";
return $retour;
}

function formhisto($ligncour)
{
global $internaute;
$numligne= listhistocount($internaute->idcompte);
if($ligncour<0 || $ligncour>$numligne)
{
	$ligncour=0;
}
$maxligne = $internaute->histonbl;

$liste = listhisto($ligncour,$maxligne);
if($liste==""){return "Pas d'historique";}
$retour="";
// to do: mettre cette ligne de type ENTETE
$retour.=barrepage($numligne,$maxligne,$ligncour);
$retour.="<br>".opentab(" align=center width=\"90%\"");
$retour.=openligne("","titre2").opencol("colspan=\"7\"").lang(12).closecol().opencol().htm_iconhelp("formhisto").closecol().closeligne();
$retour.=openligne("","titre").opencol()."<b>".lienordre("datehisto",lang(14))."</b>".closecol().opencol()."<b>".lienordre("nomhisto",lang(15))."</b>".closecol().opencol()."<b>".lienordre("senshisto",lang(16))."</b>".closecol().opencol()."<b>".lienordre("nombrehisto",lang(17))."</b>".closecol().opencol()."<b>".lienordre("valhthisto",lang(18))."</b>".closecol().opencol()."<b>".lienordre("taxehisto",lang(19))."</b>".closecol().opencol()."<b>".lienordre("totalttchisto",lang(20))."</b>".closecol().opencol()."<b>".lienordre("profithisto",lang(210))."</b>".closecol().closeligne();
foreach ($liste as $key => $value)
	{
	$clr="";
	if(round($value["PROFITOP"],2)>0.)
	{
	        $clr="gain";
	}
	if(round($value["PROFITOP"],2)<0.)
	{
		$clr="perte";
	}
	$retour.=openligne();
	$retour.=opencol().date("j/m/y H:i:s",$value["LADATE"]).closecol().opencol().$value["LENOM"].closecol().opencol().$value["LESENS"].closecol().opencol().$value["LENOMBRE"].closecol().opencol().$value["LEHT"].closecol().opencol().$value["LATAXE"].closecol().opencol().$value["LETTC"].closecol().opencol()."<font class=\"$clr\">".$value["PROFITOP"]."</font>".closecol();
	//$retour=$retour.$head."<a href=".$value["link_menu"].">".lang($value["text_id"])."</a>".$footer;
	$retour.=closeligne();
	}
$retour.=closetab()."<br>".barrepage($numligne,$maxligne,$ligncour);
return $retour;
}


function form_messagerie($ligncour,$ouvre=0)
{
global $internaute;
$numligne= listmessagescount($internaute->idcompte);
if($ligncour<0 || $ligncour>$numligne)
{
	$ligncour=0;
}
$maxligne = $internaute->msgnbl;
//echoadmin("##### $ligncour,$numligne #####");
$liste=get_messagelist($ligncour,$maxligne,$internaute->idcompte);
$html="<a href=\"index.php?do=nouvmessage&idjoueur=-1\">".lang(167)."</a><br>";
if($liste<>"")
{
	$html.=barrepage($numligne,$maxligne,$ligncour)."<br>";
	foreach ($liste as $key => $value)
	{
		$corps = str_replace(array("&quot;"),array("\""), stripslashes($value["corps"]));
		//$corps = gzinflate($value["corps"]);
		$html.=opentab("width=\"90%\" align=\"center\" ").openligne("","titre").opencol().lang(56).$value["pseudonyme"].closecol().opencol().lang(57).date("j/m/y H:i:s",$value["datemess"]).closecol().opencol().lang(58).$value["titre"].closecol().closeligne();
		if($ouvre==$value["idmsg"] && $value["etat"]=="non lu")
			upd_msgetat($value["idmsg"]);
		if($value["etat"]=="lu" or $ouvre==$value["idmsg"])
		{
			$html.=openligne().opencol("colspan=\"3\"").bbtohtml($corps).closecol().closeligne();
			$html.=openligne().opencol("colspan=\"3\"")."<center><a href=\"index.php?do=nouvmessage&idjoueur=".$value["idenvoyeur"]."&titre=Re: ".$value["titre"]."\">".lang(175)."</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href=\"index.php?do=delmessage&idmessage=".$value["idmsg"]."\" >".lang(176)."</a></center>".closecol().closeligne();
		}
		if($value["etat"]=="non lu" && $ouvre<>$value["idmsg"])
			$html.=openligne().opencol("colspan=\"3\"")."<center><a href=\"index.php?do=listemessage&ouvre=".$value["idmsg"]."&numligne=$ligncour\" >".lang(174)."</a></center>".closecol().closeligne();
		$html.=closetab()."<br>";
	}
}else{
	$html.=msgtab(lang(91),lang(86));
}
$liste=get_messagelistenvoye($internaute->idcompte);
if(is_array($liste) && $internaute->idcompte!=1)
{
	$html.="<br>".lang(178)." :<br><br>";
	foreach ($liste as $key => $value)
	{
		$corps = str_replace(array("&quot;"),array("\""), stripslashes($value["corps"]));
		$html.=opentab("width=\"90%\" align=\"center\" ").openligne("","titre").opencol().lang(219).$value["pseudonyme"].closecol().opencol().lang(57).date("j/m/y H:i:s",$value["datemess"]).closecol().opencol().lang(58).$value["titre"].closecol().closeligne();
		$html.=openligne().opencol("colspan=\"3\"").bbtohtml($corps).closecol().closeligne();
		$html.=openligne().opencol("colspan=\"3\"")."<center><a href=\"index.php?do=delmessage&idmessage=".$value["idmsg"]."\" >".lang(179)."</a></center>".closecol().closeligne();
		$html.=closetab()."<br>";
	}
}
$html.="<br>".barrepage($numligne,$maxligne,$ligncour);
return $html;
}

function form_nouvmessage($idjoueur,$sujet,$corps)
{
global $internaute;
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$html="";
$titre=lang(167);
$form="<form method=\"POST\" action=\"index.php?do=postmessage\">";
if($idjoueur>0)
{ //si on connait le destinataire
	$destinataire=ChercheInternaute ($idjoueur, $connexion);
	$form.=lang(168)." : ".$destinataire->pseudonyme;
	$form.="<input type=\"hidden\" name=\"destinataire\" value=\"$idjoueur\">";
}else{
	$joueurs=get_players();
	$listejoueurs="";
	if($internaute->authlevel>1) $listejoueurs[0]="Tous";
	while($ligne=LigneSuivante($joueurs))
		$listejoueurs[$ligne->idcompte]=$ligne->pseudonyme;
	$form.=lang(168)." : ".Html_liste("destinataire",$listejoueurs);
}
$form.="<br><br>".lang(58).Html_texte("titre",$sujet,100,250);
$form.="<br><br>".lang(171)."<br>".Html_textezone("corps",30,104,stripslashes($corps));
$form.="<br><br>".Html_bouton("envoyer",lang(169))."</form>";
return msgtab($form,$titre);
}

function sendmessage($destinataire,$titre,$corps)
{
global $internaute;
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$dest=ChercheInternaute ($destinataire, $connexion);
if(trim($titre)=="" || trim($corps)=="" || (!($dest->idcompte>0) && $internaute->authlevel<2))
	return msgtab(lang(172),lang(86)).form_nouvmessage($destinataire,$titre,$corps);

if($internaute->authlevel<2 && getnvmessagesenvoye($internaute->idcompte)>MAX_MESSAGE_ENVOYE_NON_LU)
	return msgtab(lang(205),lang(86)).form_nouvmessage($destinataire,$titre,$corps);

if($internaute->authlevel>1) $corps=html_entity_decode($corps);	
add_msg($internaute->idcompte,$destinataire,$titre,$corps);
$html=msgtab(lang(173),lang(86));


return $html;
}


function txt_accueil()
{
if(!INCONC)
{
	$html=" Bienvenue dans NetTrader 2 <br><br>NetTrader 2 est un jeu accessible � tous qui vous permet de go�ter aux joies et aux frayeurs de la Bourse mais sans le moindre risque. Achetez, Revendez, Gagnez ou Perdez, peu importe puisque vous ne jouez pas d'argent r�el . Vous d�marrez avec un capital de 10.000 � et vous devez r�aliser le meilleur b�n�fice. Vous figurez dans un classement sur le site de NetTrader pour �tre confront� � tous les autres performances.
	<br><br>
	Pour plus de r�alisme, NetTrader utilise les v�ritables cours de la Bourse avec seulement 15 minutes de diff�r�s par rapport au r��l.
	<br><br>
	Un jeu instructif qui r�v�lera peut-�tre vos talents cach�s.<br><br>

	NetTrader c'est :<br><br>

	-Un jeu � la fois palpitant et instructif<br>
	-10.000 � virtuel � faire fructifier<br>
	-Plus de 160 titres boursiers r��l � acheter et vendre<br>
	-Une mise � jour des valeurs et du classement toute les 100 secondes !<br>
	-3 niveaux de difficult�, d�butant, initi� et expert<br>
	-Un tr�s grand r�alisme, les transactions sont soumises aux taxes et les cours ont seulement 15 minutes de diff�r�s<br>
	-L'execution des ordres d'achat et de vente peuvent se faire sur un seuil ou une plage de valeurs<br>
	-Vente � d�couvert et effet de levier sont au rendez-vous<br>
	-Plus de 1600 Traders ( joueurs ) en qu�te de la premi�re place au classement !<br>
	";
}else{
	$html="Texte du concours";
}
return $html;
}

function txt_regl()
{
$html="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Les cours ne doivent �tre utilis� que pour le jeu virtuel.NetTrader 2 n'est pas un logiciel de gestion de portfeuille, tout est fictif et il n'y a rien � gagner.<br><br>Les comptes o� les joueurs n'ont pas eu d'activit� pendant deux mois ou plus seront supprim�s afin de garder un classement propre, les d�teneurs du compte ne recevrons jamais de mail pour les informer de la suppression de leur compte.";

return msgtab($html,"REGLEMENT :");
}

function forminscription()
{
if(date("U")>=FINCONC)
{
	return lang(69);
}
global $skinrep;
$echo = jscript_inscr()."<br><br><br>
      <form name=\"forminscr\" method=\"post\" action=\"index.php?do=inscrjeu\"  onSubmit=\"return test();\">
          ".opentab("align=\"center\" ").openligne("","titre").opencol(" colspan=\"4\"")."<b>".lang(48)."</b>".closecol().closeligne()."
          ".openligne()." 
            <td align=\"right\"> &nbsp;Pseudonyme :&nbsp;".closecol()."
            ".opencol()." 
              ".Html_texte("pseudo","",30,255)."
              ".closecol()."
            <td align=\"right\"> &nbsp;Mot de passe :&nbsp;".closecol()."
            ".opencol()." 
              <div align=\"left\"> 
                ".lang(84)."
                 </div>
            ".closecol()."
          ".closeligne()."
          
          ".openligne()." 
            <td align=\"right\"> E-m@il :&nbsp;".closecol()."
            ".opencol()."  
              ".Html_texte("mail","",30,255)."
              ".closecol()."
            <td align=\"right\"> &nbsp;".lang(79)." :&nbsp;".closecol()."
            ".opencol()." 
              <div align=\"left\"> 
                ".Html_liste("lvl",array('1' => 'D�butant', '2' => 'Initi�', '3' => 'Expert'))."
                 </div>
            ".closecol()."
          ".closeligne();
if(INCONC)
{  
	$echo.= openligne()." 
	<td align=\"right\"> &nbsp;Nom :&nbsp; ".closecol()."
	".opencol()." 
	".Html_texte("nom","",30,255)."
	".closecol()."
	<td align=\"right\"> Prenom :&nbsp;".closecol()."
	".opencol()." 
	".Html_texte("prenom","",30,255)."
	".closecol()."
	".closeligne()."
	".openligne()." 
	<td align=\"right\" > Adresse :&nbsp;
	".closecol()."
	<td> 
	".Html_texte("adresse","",30,255)."               ".closecol()." 
	<td align=\"right\"> Ville :&nbsp;".closecol()."
	".opencol()." 
	".Html_texte("ville","",30,255)."
	".closecol()."
	".closeligne()."
	".openligne()." 
	<td align=\"right\" > Code postal :&nbsp;
	".closecol()."
	<td> 
	".Html_texte("cp","",30,255)."               ".closecol()."
	<td align=\"right\"> Etablissement :&nbsp;".closecol()."
	".opencol()." 
	".Html_liste("etab",array('' => '- Faites votre choix -','CENTRALE PARIS' => 'CENTRALE PARIS','EDHEC Lille' => 'EDHEC Lille  ','EDHEC Nice' => 'EDHEC Nice','EM-LYON' => 'EM-LYON','ESC BORDEAUX' => 'ESC BORDEAUX','ESC LILLE' => 'ESC LILLE','ESC ROUEN' => 'ESC ROUEN','ESC TOULOUSE' => 'ESC TOULOUSE','ESCP-EAP' => 'ESCP-EAP','ESLSCA' => 'ESLSCA','ESSCA' => 'ESSCA','ESSEC' => 'ESSEC','HEC' => 'HEC','ISC' => 'ISC','PARIS DAUPHINE' => 'PARIS DAUPHINE','SCIENCE PO' => 'SCIENCE PO','POLYTECHNIQUE' => 'POLYTECHNIQUE','SORBONNE' => 'SORBONNE','ESG' => 'ESG','ESGF' => 'ESGF','ESGCI' => 'ESGCI','ESGI' => 'ESGI', 'ANCIENS TGE' => 'ANCIENS TGE','MASTERS PGSM' => 'MASTERS PGSM','ANCIENS PGSM' => 'ANCIENS PGSM','PROFESSEURS PGSM' => 'PROFESSEURS PGSM','ADMINISTRATION PGSM' => 'ADMINISTRATION PGSM'))."
	".closecol()."
	".closeligne()."".openligne()." 
	<td align=\"right\" > Telephone :&nbsp;".closecol()."
	".opencol("colspan=\"3\"")."  
	".Html_texte("tel","",30,255)."
	".closecol().closeligne();
}
			  
			  
$echo .= openligne().opencol(" colspan=\"4\"").lang(274)." &nbsp; ".
Html_radio("mailjour",0,lang(277),"CHECKED").
Html_radio("mailjour",1,lang(276),"")
."<br>".lang(275)." &nbsp;".
Html_radio("mailsemaine",0,lang(277),"").
Html_radio("mailsemaine",1,lang(276),"CHECKED")
."<br>".lang(136)."<br>".lang(137)."<br>".lang(138)."<br>".lang(139)."<br>".lang(140)."<br>".lang(141)
	.closecol().closeligne()."</table><br><br><center>".Html_bouton("Submit",lang(7))."</center>
      </form>";

return $echo;
}

function formprofil()
{
global $internaute;
//pseudonyme` , `passe` , `nom` , `prenom` , `adresse` , `cp` , `ville` , `tel` , `email` , `etablissement`
$echo = jscript_profil()."<br><br><br>
      <form name=\"forminscr\" method=\"POST\" action=\"index.php?do=editprof\"   onSubmit=\"return test();\">
          ".opentab("align=\"center\" ","fond").openligne("","titre2").opencol(" colspan=\"4\"")."<b>".lang(63)."</b>".closecol().closeligne()."
          ".openligne()." 
            <td align=\"right\"> &nbsp;Pseudonyme :&nbsp;".closecol()."
            ".opencol()." 
              $internaute->pseudonyme
              ".closecol()."
            <td align=\"right\"> &nbsp;".lang(79)." :&nbsp;".closecol()."
            ".opencol()." 
              <div align=\"left\"> 
                ".Html_liste("lvl",array('1' => 'D�butant', '2' => 'Initi�', '3' => 'Expert'),"",$internaute->idniveau)."
                 </div>
            ".closecol()."
          ".closeligne()."
          ".openligne()." 
            <td align=\"right\"> E-m@il :&nbsp;".closecol()."
            ".opencol()."  
              ".Html_texte("mail",$internaute->email,30,255)."
              ".closecol()."
            <td align=\"right\">".lang(73)." :&nbsp;".closecol()."
            ".opencol()."  
              ".Html_liste("nbhisto",array('10' => '10 lignes', '20' => '20 lignes', '30' => '30 lignes', '50' => '50 lignes' ),"",$internaute->histonbl)."
              ".closecol()."
          ".closeligne().openligne()." 
            <td align=\"right\">".lang(74)." :&nbsp;".closecol()."
            ".opencol()."  
              ".Html_liste("nbmsg",array('5' => '5 lignes', '10' => '10 lignes', '15' => '15 lignes'),"",$internaute->msgnbl)."
              ".closecol()."
            <td align=\"right\">".lang(75)." :&nbsp;".closecol()."
            ".opencol()."  
              ".Html_liste("nbclasse",array('10' => '10 lignes', '20' => '20 lignes', '30' => '30 lignes', '50' => '50 lignes' ),"",$internaute->classenbl)."
              ".closecol()."
          ".closeligne().openligne()."
            <td align=\"right\">".lang(72)." :&nbsp;".closecol()."
            ".opencol(" colspan=\"3\"").Html_liste("skin",listskin(),"",$internaute->idskin)."
              ".closecol()
.closeligne().openligne()."
            <td align=\"right\">".lang(274)." :&nbsp;".closecol()."
            ".opencol(" colspan=\"3\"").Html_radio("mailjour",0,lang(277),retiftrue("CHECKED",!$internaute->maildaily==1)).
Html_radio("mailjour",1,lang(276),retiftrue("CHECKED",$internaute->maildaily==1))."
              ".closecol()
.closeligne().openligne()."
            <td align=\"right\">".lang(275)." :&nbsp;".closecol()."
            ".opencol(" colspan=\"3\"").Html_radio("mailsemaine",0,lang(277),retiftrue("CHECKED",!($internaute->mailweekly==1))).
Html_radio("mailsemaine",1,lang(276),retiftrue("CHECKED",$internaute->mailweekly==1))."
              ".closecol()
.closeligne().openligne("","").opencol(" colspan=\"4\"")."<center>".Html_bouton("Submit",lang(64))."</center>".closecol().closeligne()."
          
        </table><br><br><center>

      </center></form>";

return $echo;
}

function connectstat()
{
$listeplayer=get_playerconnected();
$nbplayer=count($listeplayer);
$liste="";
if($listeplayer<>"")
{
	for($i=0;$i<$nbplayer;$i++)
	{
		if($i>=1)
		{
			$liste.= ", ";
		}
		$liste.=$listeplayer[$i]['Pseudo'];
	}
}else{
	$liste.="Invit�";
}

$echo=lang(81)." ".$nbplayer." ".lang(82)."<br>";
$echo.=lang(83).$liste;
return $echo;
}

function formcontact()
{
$html = "Contacter l'auteur: contact_2012(chez)nettrader(point)fr
";
return $html;
}

function formachat($sicavselect)
{
global $do,$internaute,$skinrep;
$echo ="<center>".lang(31)." :<br><br><form method=\"post\" action=\"index.php?do=";
if($internaute->avautrepage==0)
{
	$echo.="formachatvente&info=".ADSENSEKEYWORD."";
}else{
	$echo.="formachatseul&info=".ADSENSEKEYWORD."";
}
$echo.="\">";
$echo.=Html_head_liste("sicavselachat");
$liste = listvaleur();
if($liste==""){return "";}
foreach ($liste as $key => $value)
{
	//<OPTION VALUE="capital">Capital
	if($value["codesicav"]==$sicavselect)
	{
		$addon = "SELECTED";
		$nomsicav=$value["nomsicav"];
		$keycourant = $key ;
		$codesicav = $value["codesicav"];
		$urlname = $value["yahooname"];
        $jpossede=joueur_possede($value["codesicav"],$internaute->idcompte);
 	}else{
		$addon = "";
	}
	$echo.="<OPTION $addon VALUE=\"".$value["codesicav"]."\">".$value["nomsicav"];
}
$echo.= "</SELECT>".Html_bouton("Submit",lang(31))."</form></center>";
if($sicavselect<>"")
{
	//la suite � afficher seulement si l'utilisater � valider la derni�re fois l'action
	$valeuraction = getvaleur(sec($codesicav));
	if($valeuraction<>0)
	{
		$nbactions=getnbactionmax($internaute->cashback,$valeuraction);
	}else{
		$nbactions=0;
	}
 	$enteteformulaire =jscript_av($nbactions)."<form name=\"form\" method=\"post\" action=\"index.php?do=achataction\"  onSubmit=\"Submit.disabled=true;\">";
	$enteteformulaire .= "<input type=\"hidden\" name=\"sens\" value=\"achat\"><input type=\"hidden\" name=\"ansval\" value=\"$valeuraction\"><input type=\"hidden\" name=\"codesicav\" value=\"$sicavselect\">";
	$echo.=$enteteformulaire.opentab("align=\"center\"").openligne("","titre2").opencol();
	//$echo.= opentab("width=\"100%\"","invi").openligne("","invi").opencol(" align=\"center\"");
	//$echo.=openfont("titre1")."&nbsp;&nbsp;&nbsp;&nbsp;"."$nomsicav ".closefont().": $valeuraction � / ".lang(28);

	$echo.= opentab("width=\"100%\"","invi").openligne("","invi").opencol("width=\"20\" ").htm_iconinfo($urlname,$nomsicav).closecol().opencol(" align=\"center\"");
	$echo.=openfont("titre1").$nomsicav.closefont().": $valeuraction � / ".lang(28);

	$echo.=closecol().opencol("width=\"20\" ").htm_iconhelp("formachat");
	$echo.=closecol().closeligne().closetab();
	$echo.= closecol().closeligne();
	$echo.= openligne().opencol();

	$echo.=opentab("width=\"100%\"","invi");
	$echo.=openligne().opencol();
	$echo.=lang(29);
	$echo.=closecol().opencol();
	$liste=array();
    if($jpossede->nombsicav<0 && $nbactions>0)
	{
		$pourcpossede=round(abs($jpossede->nombsicav)/$nbactions*100,4);
		if($pourcpossede*.25<=100) $liste[strval(round($pourcpossede*.25,4))]="25 % ".lang(147); //je transforme en chaine car php ne supporte pas les decimale en cl�
		if($pourcpossede*.50<=100) $liste[strval(round($pourcpossede*.50,4))]="50 % ".lang(147);
		if($pourcpossede*.75<=100) $liste[strval(round($pourcpossede*.75,4))]="75 % ".lang(147);
		if($pourcpossede<=100) $liste[strval($pourcpossede)]="100 % ".lang(147);
	}
	$liste += array('25' => '25 %', '50' => '50 %', '75' => '75%','100' => '100%');
	$echo.=Html_liste("nb1",$liste,"onChange=\"SetValeur(this.value)\"");
	$echo.=lang(30)."<br><br>";
	$echo.=Html_texte("nb2","25","3","3","onKeyUp=\"SetValeur(this.value)\"")." % ".lang(30)."<br><br>";
	$echo.=closecol().closeligne();
	$echo.=closetab();

	$echo.=closecol().closeligne();
	$echo.=openligne().opencol("align=\"center\"");
	if($internaute->seuil==1 && $internaute->plage==0)
	{
		$echo.=Html_radio("seuil","0",lang(68),"checked","onclick=\"sela_click1();\"").Html_radio("seuil","1",lang(150),"","onclick=\"sela_click2();\"")."<br><br>";
	}
	if($internaute->seuil==1 && $internaute->plage==1)
	{
		$echo.=Html_radio("seuil","0",lang(68),"checked","onclick=\"sela_click1();\"").Html_radio("seuil","1",lang(149),"","onclick=\"sela_click2();\"")."<br><br>";
	}
	$echo.=lang(32).Html_texte("nbr",intval($nbactions*.25),"8","15","onKeyUp=\"ChgQuant()\"");
	if($internaute->seuil==1 && $internaute->plage==0)
	{
		//$bddaction=donnaction($codesicav);
		//$echo.=lang(32).Html_texte("valmin","0","8","15")."� ".
		$echo.="<input type=\"hidden\" name=\"valmin\" value=\"0\">";
		$echo.=" ".lang(9)." ".lang(38).Html_texte("valmax",$valeuraction,"8","15"," style=\"visibility:hidden\" ")." �".lang(59)."<br>
		".Html_radio("select","1",lang(17),"").Html_radio("select","0",lang(183),"checked")."
		<br><br>".lang(39).Html_texte("tempsmin",finjour(),"22","16")."
		<br><br>";
	}else{
		if($internaute->plage==1)
		{
			$echo.=" ".lang(9)."<br><br>".lang(151).Html_texte("valmin",0,"8","15"," style=\"visibility:hidden\" ")." ".lang(38)." ".Html_texte("valmax",$valeuraction,"8","15"," style=\"visibility:hidden\" ")." �<br>
			".Html_radio("select","1",lang(17),"").Html_radio("select","0",lang(183),"checked")."
			<br><br>".lang(39).Html_texte("tempsmin",finjour(),"22","16")."
			<br><br>";
		}else{
			$echo.=" /$nbactions ".lang(9); //pas de seuil limite
			$echo.=closecol().closeligne();
			$echo.=openligne().opencol("align=\"center\"");
			$echo.="<input type=\"hidden\" name=\"valmin\" value=\"0\">
			<input type=\"hidden\" name=\"valmax\" value=\"$valeuraction\">
			<input type=\"hidden\" name=\"select\" value=\"0\">
			<input type=\"hidden\" name=\"tempsmin\" value=\"".finjour()."\">";
		}
	}
	$echo.=Html_bouton("Submit",lang(31));
	$echo.=closecol().closeligne();
	$echo.=closetab()."</form>";
}
return $echo;
}


function formvente($sicavselect,$liste=0) // $liste = portefeuille_joueur()
{
global $do,$internaute,$skinrep;
if(($liste==0 or $sicavselect!=tabvaleurouzero($_POST,'sicavselvendr')) && !$internaute->vad)
{
	$liste = portefeuille_joueur();
}

if($internaute->vad) //si on peut faire une vente � d�couvert on affiche toutes les valeurs disponibles ( pas seulement ceux du portefeuille )
{
    $liste = listvaleur();
}

$echo ="<center>".lang(34)." :<br><br><form method=\"post\" action=\"index.php?do=";
if($internaute->avautrepage==0)
{
	$echo.="formachatvente&info=".ADSENSEKEYWORD."";
}else{
	$echo.="formventeseul&info=".ADSENSEKEYWORD."";
}
$echo.="\">";
$echo.=Html_head_liste("sicavselvendr");
//$liste = portefeuille_joueur();
if($liste==""){return "";}
foreach ($liste as $key => $value)
{
	//<OPTION VALUE="capital">Capital
	if($value["codesicav"]==$sicavselect)
	{
		$addon = "SELECTED";
		$jpossede->nombsicav=0; //initialisation, pour creation outil "Possede"
		if($value["nombsicav"])
		{
			$nbactions=$value["nombsicav"];
		}else{
			$jpossede=joueur_possede($value["codesicav"],$internaute->idcompte);
			if($jpossede->nombsicav<0) $jpossede->nombsicav = 0;
            $nbactions=getnbactionmax(getmontantvadpossible($internaute->idcompte),$value["valeur"])+$jpossede->nombsicav; //si on ne possede pas cette action alors on calcul le nombre maximal d'action a vendre en d�couvert

		}
		if($value["valsicav"])
			 $valeuraction=$value["valsicav"];
		else
            $valeuraction=$value["valeur"];
		$nomsicav=$value["nomsicav"];
		$keycourant = $key ;
		$codesicav = $value["codesicav"];
		$urlname=$value["yahooname"];
	}else{
		$addon = "";
	}
	$echo.="<OPTION $addon VALUE=\"".$value["codesicav"]."\">".$value["nomsicav"];
}
$echo.= "</SELECT> ".Html_bouton("Submit",lang(34))."</form></center>";
if($sicavselect<>"")
{


	$enteteformulaire =jscript_av($nbactions)."<form name=\"form\" method=\"post\" action=\"index.php?do=venteaction\"  onSubmit=\"Submit.disabled=true;\">";
	$enteteformulaire .= "<input type=\"hidden\" name=\"sens\" value=\"vente\"><input type=\"hidden\" name=\"ansval\" value=\"$valeuraction\"><input type=\"hidden\" name=\"codesicav\" value=\"$sicavselect\">";
	$echo.=$enteteformulaire.opentab("align=\"center\"").openligne("","titre2").opencol();
	$echo.= opentab("width=\"100%\"","invi").openligne("","invi").opencol("width=\"20\" ").htm_iconinfo($urlname,$nomsicav).closecol().opencol(" align=\"center\"");
	$echo.=openfont("titre1").$nomsicav.closefont().": $valeuraction � / ".lang(28);
	$echo.=closecol().opencol("width=\"20\" ").htm_iconhelp("formvente");
	$echo.=closecol().closeligne().closetab();
	$echo.= closecol().closeligne();
	$echo.= openligne().opencol();

	$echo.=opentab("width=\"100%\"","invi");
	$echo.=openligne().opencol();
	$echo.=lang(29);
	$echo.=closecol().opencol();
	if($internaute->vad==0)
	{
		$liste = array('25' => '25 %', '50' => '50 %', '75' => '75%','100' => '100%');
	}else{
		$liste = array('25' => '25 % '.lang(278), '50' => '50 % '.lang(278), '75' => '75% '.lang(278),'100' => '100% '.lang(278));
	}
// '.lang(278)
	if($jpossede->nombsicav>0 && $nbactions>0)
	{
		$pourcpossede=round($jpossede->nombsicav/$nbactions*100,4);
		$liste[strval(round($pourcpossede*.25,4))]="25 % ".lang(24); //je transforme en chaine car php ne supporte pas les decimale en cl�
		$liste[strval(round($pourcpossede*.50,4))]="50 % ".lang(24);
		$liste[strval(round($pourcpossede*.75,4))]="75 % ".lang(24);
		$liste[strval($pourcpossede)]="100 % ".lang(24);
	}
	$echo.=Html_liste("nb1",$liste,"onChange=\"SetValeur(this.value)\"");
	$echo.=lang(33)."<br><br>";
	$echo.=Html_texte("nb2","25","3","3","onKeyUp=\"SetValeur(this.value)\"")." % ".lang(33)."<br><br>";
	$echo.=closecol().closeligne();
	$echo.=closetab();
	// ############################################################################


	$echo.=closecol().closeligne();
	$echo.=openligne().opencol("align=\"center\"");
	if($internaute->seuil==1)
	{
		$echo.=Html_radio("seuil","0",lang(68),"checked","onclick=\"selv_click1();\"").Html_radio("seuil","1","A seuil","","onclick=\"selv_click2();\"")."<br><br>";
	}
	$echo.=lang(35).Html_texte("nbr",intval($nbactions*.25),"8","15","onKeyUp=\"ChgQuant()\"");

	if($internaute->seuil==1 && $internaute->plage==0)
	{
		//Html_texte("valmax","-1","8","15")."�
		$echo.="<input type=\"hidden\" name=\"valmax\" value=\"-1\">";
		$echo.=" ".lang(9)." ".lang(38).Html_texte("valmin",$valeuraction,"8","15"," style=\"visibility:hidden\" ")." � ".lang(42)."<br>
		".Html_radio("select","1",lang(17),"").Html_radio("select","0",lang(183),"checked")."
		<br><br>".lang(39).Html_texte("tempsmin",finjour(),"22","16")."
		<br><br>";
	}else{
        if($internaute->plage==1)
		{
			//$echo.=" ".lang(9)."<br><br>".lang(38).Html_texte("valmin",$valeuraction,"8","15"," style=\"visibility:hidden\" ")." � <br>";
			$echo.=" ".lang(9)."<br><br>".lang(151).Html_texte("valmin",$valeuraction,"8","15"," style=\"visibility:hidden\" ")." ".lang(38)." ".Html_texte("valmax",-1,"8","15"," style=\"visibility:hidden\" ")." �<br>";
			$echo.=Html_radio("select","1",lang(17),"").Html_radio("select","0",lang(183),"checked")."
			<br><br>".lang(39).Html_texte("tempsmin",finjour(),"22","16")."
			<br><br>";
		}else{
			$echo.=" /$nbactions ".lang(9); //pas de seuil limite
			$echo.=closecol().closeligne();
			$echo.=openligne().opencol("align=\"center\"");
			$echo.="<input type=\"hidden\" name=\"valmin\" value=\"$valeuraction\">
			<input type=\"hidden\" name=\"valmax\" value=\"-1\">
			<input type=\"hidden\" name=\"select\" value=\"0\">
			<input type=\"hidden\" name=\"tempsmin\" value=\"".finjour()."\">";
		}
	}

	$echo.=Html_bouton("Submit",lang(34));
	$echo.=closecol().closeligne();
	$echo.=closetab()."</form>";

}


return $echo;


}


function form_news($ligncour)
{
$numligne= listmessagescount(0);
if($ligncour<0 || $ligncour>$numligne)
{
	$ligncour=0;
}
$maxligne = 2;
//echoadmin("##### $ligncour,$numligne #####");
$liste=get_messagelist($ligncour,$maxligne,0);
$html="<br>";
if($liste<>"")
{
$html.=opentab("width=\"90%\" align=\"center\" ").openligne("","titre").opencol().lang(90).closecol().closeligne().openligne("","invi").opencol();
	$html.=barrepage($numligne,$maxligne,$ligncour)."<br>".closecol().closeligne().openligne("","invi").opencol();
	foreach ($liste as $key => $value)
	{
		$corps = stripslashes($value["corps"]);
		//$corps = gzinflate($value["corps"]);
		$html.=opentab("width=\"100%\" align=\"center\" ").openligne("","titre").opencol().lang(56).$value["pseudonyme"].closecol().opencol().lang(57).date("j/m/y H:i:s",$value["datemess"]).closecol().opencol().lang(58).$value["titre"].closecol().closeligne();
		$html.=openligne().opencol("colspan=\"3\"").bbtohtml($corps).closecol().closeligne().closetab()."<br>";
	}
$html.="<br>".closecol().closeligne().openligne("","invi").opencol().barrepage($numligne,$maxligne,$ligncour).closecol().closeligne().closetab();
}else{
	$html.=msgtab("Pas de Nouvelles.","Information");
}
return $html;
}

function pgaccueil($numligne)
{
global $internaute;
$corps="";
if(!($internaute->authlevel>=1))
	$corps.=msgtab(txt_accueil(),lang(89));
else
	$corps.=msgtab("<!-- Debut shoutbox - http://www.i-tchat.com --><iframe src=\"http://www.i-tchat.com/shoutbox/shoutbox.php?idShoutbox=44311\" width=\"100%\" height=\"270\" frameborder=\"0\" allowtransparency=\"true\" >Votre navigateur n'est pas compatible avec le <a href=\"http://www.i-tchat.com\" onClick=\"window.open(this.href+'?44311');\">tchat</a>, cliquez ici pour voir le <a href=\"http://www.i-tchat.com\" onClick=\"window.open(this.href+'?44311');\">tchat gratuit</a>.</iframe><br />Ouvrir le <a href=\"http://www.i-tchat.com\" onClick=\"window.open(this.href+'?44311');return false;\">tchat</a> dans une popup.<!-- Fin shoutbox -->","Chat avec les joueurs");
$corps.=form_news($numligne);

$html=menu($corps);

return $html;
}

function newpart($titre,$contenu1="",$contenu2="",$contenu3="",$contenu4="",$contenu5="",$contenu6="",$contenu7="",$contenu8="")
{
$html=openligne("","titre").opencol();
$html.="$titre :";
$html.=closecol().closeligne().openligne().opencol();
if($contenu1!="")
	$html.=$contenu1.imgdot();
if($contenu2!="")
	$html.=$contenu2.imgdot();
if($contenu3!="")
	$html.=$contenu3.imgdot();
if($contenu4!="")
	$html.=$contenu4.imgdot();
if($contenu5!="")
	$html.=$contenu5.imgdot();
if($contenu6!="")
	$html.=$contenu6.imgdot();
if($contenu7!="")
	$html.=$contenu7.imgdot();
if($contenu8!="")
	$html.=$contenu8.imgdot();
$html.=closecol().closeligne();
if(strlen($contenu1.$contenu2.$contenu3.$contenu4.$contenu5.$contenu6)>0)
	return $html;
else
	return "";
}


function menu($corps)
{
 global $internaute;
$html = opentab("align=\"center\" width=\"100%\"","invi").openligne("","invi").opencol("width=\"70%\" valign=\"top\"");
$html.= $corps;
$html.=closecol().opencol("valign=\"top\" width=\"30%\"")."<br>";
$html.=opentab("width=\"100%\"");
$html.=newpart(lang(95),
                retiftrue("<a href=\"index.php?do=formachatvente&info=".ADSENSEKEYWORD."\">".lang(6)."</a>",$internaute->authlevel>=1),
                retiftrue("<a href=\"index.php?do=historique\">".lang(12)."</a>",$internaute->authlevel>=1),
                retiftrue("<a href=\"index.php?do=listemessage\">".lang(62)."</a>",$internaute->authlevel>=1),
		retiftrue("<a href=\"index.php?do=forminscription\">".lang(7)."</a>",!$internaute->authlevel>=1),
		retiftrue("<a href=\"index.php?do=formrecuppass\">".lang(101)."</a>",!$internaute->authlevel>=1));
$html.=newpart(lang(221),
		retiftrue("<a href=\"index.php?do=lstactions\">".lang(127)."</a>",$internaute->authlevel>=1),
		"<a href=\"index.php?do=classement\">".lang(13)."</a>",
		retiftrue("<a href=\"index.php?do=classementequipe\">".lang(229)."</a>",ACTIVATION_GROUPE));
$html.=newpart(lang(97),
		retiftrue("<a href=\"index.php?do=profil\">".lang(222)."</a>",$internaute->authlevel>=1),
		retiftrue("<a href=\"index.php?do=chgmdp\">".lang(272)."</a>",$internaute->authlevel>=1),
                retiftrue("<a href=\"index.php?do=ajgroupe\">".lang(187)."</a>",!estmembregroupe($internaute->idcompte) && ACTIVATION_GROUPE && $internaute->authlevel>=1),
                retiftrue("<a href=\"index.php?do=modifgroupe\">".lang(188)."</a>",estadmingroupe($internaute->idcompte) && ACTIVATION_GROUPE && $internaute->authlevel>=1),
		retiftrue("<a href=\"index.php?do=quittegroupe\">".lang(202)."</a>",estmembregroupe($internaute->idcompte) && ACTIVATION_GROUPE && $internaute->authlevel>=1),
                retiftrue("<a href=\"index.php?do=formadmin\">".lang(99)."</a>",$internaute->authlevel>1),
                retiftrue("<a href=\"index.php?do=formrazjoueur\">".lang(111)."</a>",$internaute->authlevel>=1));
$html.=newpart(lang(223),
                "<a href=\"index.php?do=reglement\">".lang(98)."</a>",
                "<a href=\"index.php?do=formhelp\">".lang(160)."</a>",
                "<a href=\"index.php?do=formfaq\">".lang(166)."</a>");
$html.=newpart(lang(224),
                "<a href=\"index.php?do=contactauteur\">".lang(104)."</a>",
                "<a href=\"index.php?do=showlstforums\">".lang(240)."</a>");

$html.=newpart(lang(211),
                "<a href=\"http://www.lobourse.com\" target=\"_blank\">D�buter en bourse avec le site Lobourse.com</a>");




$html.=closecol().closeligne();
$html.=closetab();
$html.="<br><br>".txtfuncjour();
$html.=closecol().closeligne().closetab();
return $html;
}


function txtfuncjour()
{
$html="";
if(istableexist("stats"))
{
	$stats=exeanspublicreq();
	$html=sorttableau($stats->req,$stats->titre,"100");
}
return $html;
}

function imgdot()
{
return "<br><img src=\"skin/dot.gif\" width=\"1\" height=\"12\"><br>";
}

function formrecuppass()
{
$html=lang(103)."<br><br><center>".openform("dosendpass").lang(102)."&nbsp;&nbsp;&nbsp;".Html_texte("pseudo","",30,60)."<br><br>".Html_bouton("valide","Valider")."</form></center>";

return $html;
}

function formsendpass($pseudo)
{
if(strlen(strchr($pseudo,"@"))==0)
{
        $player = getinternauteinfo($pseudo);
}else{
        $connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
        $player = chercheinternaute(0,$connexion,$pseudo);
}
if($player->passe=="")
{
	return lang(107);
}else{
	$adresse=ADDRNT."/index.php?do=rtrmdp&c=$player->pseudonyme&m=".md5($player->idcompte.$player->email.$player->passe);
	$corps="Message g�n�r� automatiquement\n\n  Vous avez demand� � recevoir un nouveau mot de passe, afin de le recevoir rendez vous sur cette page :\n\n <a href=\"$adresse\">$adresse</a> \n\n Si le lien ne fonctionne pas allez sur :\n\n $adresse \n\n Si vous n'avez pas fait cette demande alors ne cliquez pas sur le lien pour que rien de change, en cas d'abus veuillez r�pondre � ce mail en informant sur l'abus.";
	$titre="Demande de nouveau mot de passe";
        envoimail($player->email, $titre,$corps);
	return lang(109);
}
}

function dosendpass($pseudo,$md5mdp)
{
$player = getinternauteinfo($pseudo);

if($md5mdp == md5($player->idcompte.$player->email.$player->passe))
{
	$nouvmdp=substr(md5(getmicrotime()), 0, 5);
	setmdp($player->idcompte,$nouvmdp);
	$corps="Message g�n�r� automatiquement \nCONSERVEZ CE MESSAGE !!!\n  :\n\n Voici vos informations :\n \n Email:$player->email \n Mot de passe:$nouvmdp";
	$titre="Votre mot nouveau mot de passe";
        envoimail($player->email,$titre,$corps);
	return lang(108);
}else{
	return lang(110);
}
}

function frmrazjoueur() //formulaire de mise a zero
{
//le joueur doit taper son mot de passe et recopier le mot OK puis valider


$html=msgtab(sec(lang(116))."<br>".lang(117)."<br><br><form METHOD=\"POST\" ACTION=\"index.php?do=doraz\">".lang(118)." :
".Html_pass("mdp","",30,50)." &nbsp;&nbsp;&nbsp;".lang(143)." : ".html_texte("validok","",10,2).
"&nbsp;&nbsp;&nbsp;".Html_bouton("valide","R.A.Z.")."<br>".
Html_radio("optiondel","1",lang(144),"")."<br>".
Html_radio("optiondel","0",lang(111),"CHECKED")
."</form> ",lang(119));
return $html;
}

function doraz($mdp,$vok,$optdel)
{
global $internaute;
if(md5($mdp)<>$internaute->passe)
{
	return(msgtab(lang(26),lang(120)));
}else{
	if($vok<>"OK")
	{
		return(msgtab(lang(121),lang(120)));
	}else{
		if(estadmingroupe($internaute->idcompte))
			fctgetoffteammaster($internaute->idcompte);
		fctdoraz($internaute->idcompte,$optdel);
		if($optdel) 
			$idmess=145;
		else
            $idmess=123;
		return(msgtab(lang($idmess),lang(146)));
	}
}
}

function lstAction($typeAffiche="") //affiche la liste des actions, permettant aux joueurs de voir
{ //les actions class�es par activit�, avec un lien � droite pour acheter ou vendre cette action
global $internaute;
$lstCodeSico="";
if($typeAffiche<>"") //type d'affichage, du portefeuille, des m�me secteurs
{
	if($typeAffiche=="secteur") $lstCodeSico=getCodesSicoSecteurPortef($internaute->idcompte);
	if($typeAffiche=="portef") $lstCodeSico=getCodesSicoPortef($internaute->idcompte);
	if($typeAffiche=="cote") $lstCodeSico=getCodesSicoCote($internaute->idcompte);
}else{
    $lstCodeSico=getCodesSicoSecteurPortef($internaute->idcompte);
}

$sicavlist=get_sicavcat($lstCodeSico);
$portef=portefeuille_joueur();
//creation du tableau $tab[idsico]=%duportef
$tabportef=array();
if(is_array($portef))
{
	$tot=0;
	for($i=0;$i<count($portef);$i++)
	{
		$tot+=$portef[$i]["valtotsicav"]*sign(sign($portef[$i]["valtotsicav"])+1);
	}
	for($i=0;$i<count($portef);$i++)
	{
		if($tot>0 && $portef[$i]["valtotsicav"]>0)
			$tabportef[$portef[$i]["codesicav"]]=round(($portef[$i]["valtotsicav"]*sign(sign($portef[$i]["valtotsicav"])+1))/$tot*100,2);
		//else
	        //$tabportef[$portef[$i]["codesicav"]]=0;
	}
}
//creation du tableau $tab[idsico]=%portefdesjoueurs
//$tabportefall=get_acttotpossede($lstCodeSico);
$tabportefall=get_acttotpossede();
$tabcouleurall=couleurfonctionclasse($tabportefall);
$tabcouleur=couleurfonctionclasse($tabportef);


$html="<center>".html_lien(lang(154),getnewurl("format","tous"))." ".html_lien(lang(155),getnewurl("format","secteur"))." ".html_lien(lang(156),getnewurl("format","portef"))." ".html_lien(lang(157),getnewurl("format","cote"))."</center><br>";
$html.=opentab(" align=center width=\"90%\" ");
$titre=openligne("","titre").opencol().lienordre("nomaction",lang(15)).closecol().opencol().lienordre("valeuraction",lang(112)).closecol().opencol("colspan=\"2\"").lienordre("part",lang(124)).closecol().opencol("colspan=\"2\"").lienordre("partjoueur",lang(125)).closecol().opencol().lang(6).closecol().closeligne();
$html.=openligne("","titre2").opencol().lang(126)." &nbsp;&nbsp;&nbsp;&nbsp;".html_lien(lang(153),getnewurl("champ","")).closecol().opencol().htm_iconhelp("formlstactions").closecol().closeligne();
$html.=opentab(" align=center width=\"90%\" ");
$ans="";
if(array_key_exists ("champ",$_GET))	$html.=$titre;

while($ligne=LigneSuivante($sicavlist))
{
	if(($ans<>$ligne->libellesecteur or $ans=="") && !array_key_exists ("champ",$_GET))
	{
		$html.=openligne("","titre").opencol("colspan=\"7\"").$ligne->libellesecteur.closecol().closeligne();
		$html.=$titre;
		$ans=$ligne->libellesecteur;
	}
	$html.=openligne("titre").opencol().htm_iconinfo($ligne->yahooname,$ligne->nom)."&nbsp;&nbsp;".$ligne->nom.closecol().opencol().$ligne->valeur." �".closecol().opencol("width=\"30\" bgcolor=\"".htmlourien(tabvaleurouzero($tabcouleurall,$ligne->codesico))."\"")."&nbsp;".closecol().opencol().round(tabvaleurouzero($tabportefall,$ligne->codesico),2)." %".closecol().opencol("width=\"30\" bgcolor=\"".htmlourien(tabvaleurouzero($tabcouleur,$ligne->codesico))."\"")."&nbsp;&nbsp;&nbsp;".closecol().opencol().round(tabvaleurouzero($tabportef,$ligne->codesico),2)." %".closecol().opencol()."<a href=\"".lnkachat($ligne->codesico)."\">".lang(31)."</a>"."&nbsp;&nbsp;&nbsp;";
	if($internaute->vad)
		$csico=$ligne->codesico;
	else
        $csico=intval($tabportef[$ligne->codesico]);
	$html.=lnkvente($ligne->codesico,$csico,lang(34));
	$html.=closecol().closeligne();
}
$html.=closetab();
//$html.=closetab();
return $html;
}
function frminvitejoueur($idgroupe)
{

$form="<br>".opentab("align=\"center\"").openligne("","titre").opencol("colspan=\"2\"").lang(213).closecol().closeligne();
$form.="<form method=\"POST\" name=\"frminvite\" action=\"index.php?do=invitejoueur\">";
$form.=openligne().opencol("align=\"right\"").lang(214)." :".closecol().opencol();

$joueurs=getjoueursnotingroupe();
$listejoueurs="";
while($ligne=LigneSuivante($joueurs))
	$listejoueurs[$ligne->idcompte]=$ligne->pseudonyme;
$form.=Html_liste("idjoueur",$listejoueurs,"","");
$form.="<INPUT type=\"hidden\" name=\"titre\" value=\"".lang(215)."\">";
$form.="<INPUT type=\"hidden\" name=\"corps\" value='".lang(216)."\n[url=\"".ADDRNT."/index.php?do=acceptinvite&idgroupe=$idgroupe\"]".lang(218)."[/url]'>";
$form.=closecol().closeligne();


$form.=openligne().opencol("colspan=\"2\" align=\"center\" ").
Html_bouton("envoyer",lang(217))."</form>";
$form.="<br>".closecol().closeligne().closetab();


return $form;
}
function frmgroupeaction($idgroupe)
{

$form="<br>".opentab("align=\"center\"").openligne("","titre").opencol("colspan=\"2\"").lang(225).closecol().closeligne();
$form.=openligne().opencol("colspan=\"2\"")."<a href=\"?do=supprtoutinvite\">".lang(226)."</a>".closecol().closeligne();
$form.=closetab();


return $form;
}
function frmexclurejoueur($idgroupe)
{

$form="<br>".opentab("align=\"center\"").openligne("","titre").opencol("colspan=\"2\"").lang(202).closecol().closeligne();
$form.="<form method=\"POST\" name=\"frmexclusion\" action=\"index.php?do=exclurejoueur\">";
$form.=openligne().opencol("align=\"right\"").lang(214)." :".closecol().opencol();

$joueurs=getmembrebygroup($idgroupe);
$listejoueurs="";
$listejoueurs[]="";
while($ligne=LigneSuivante($joueurs))
	$listejoueurs[$ligne->idcompte]=$ligne->pseudonyme;
$form.=Html_liste("idcompteexclu",$listejoueurs,"",0);
$form.=closecol().closeligne();
$form.=openligne().opencol("colspan=\"2\" align=\"center\" ").
Html_bouton("envoyer",lang(203))."</form>";
$form.="<br>".closecol().closeligne().closetab();


return $form;
}

function frmmodifajgroupe($idgroupe=0)
{
global $internaute;
$form="";
if($idgroupe>0)
{
	$form.=frminvitejoueur($idgroupe);
	$form.=frmgroupeaction($idgroupe);
}
$groupe="";
$groupe->urlsite="http://";
if($idgroupe==0)
{
	$laction="doajgroupe";
	$titre=lang(187);
}else{
	$laction="domodifgroupe";
	$titre=lang(188);
	$groupe=getgroupbyadmin($internaute->idcompte);
}

/*
$html="";
$form="<form method=\"POST\" action=\"index.php?do=$laction\">";
$form.="<br><br>".lang(58).Html_texte("titre",$sujet,50,250);
$form.="<br><br>".lang(171)."<br>".Html_textezone("corps",15,50,$corps);
$form.="<br><br>".Html_bouton("envoyer",lang(169))."</form>";
*/


$form.=jscript_groupe()."<br>".opentab("align=\"center\"").openligne("","titre").opencol("colspan=\"2\"").$titre.closecol().closeligne();
$form.=openligne("","").opencol("colspan=\"2\"").lang(201).closecol().closeligne();
$form.="<form method=\"POST\" name=\"frmajmodifgroupe\"   onSubmit=\"return test();\" action=\"index.php?do=$laction\">";
$form.=openligne().opencol("align=\"right\"").lang(189)." :".closecol().opencol().Html_texte("titreeq",$groupe->titregroupe,50,250).closecol().closeligne();
$form.=openligne().opencol("align=\"right\"").lang(190)." :".closecol().opencol().Html_texte("titreeqcourt",$groupe->initialgroupe,25,5).closecol().closeligne();
$form.=openligne().opencol("align=\"right\"").lang(192)." :".closecol().opencol();
if($idgroupe==0)
{
	$form.=$internaute->pseudonyme;
}else{
	$joueurs=getmembrebygroup($idgroupe);
	$listejoueurs="";
	while($ligne=LigneSuivante($joueurs))
		$listejoueurs[$ligne->idcompte]=$ligne->pseudonyme;
	$form.=Html_liste("idchef",$listejoueurs,"",$internaute->idcompte);
}
$form.=closecol().closeligne();
$form.=openligne().opencol("align=\"right\"")."* ".lang(198)." :".closecol().opencol()."<INPUT type=\"hidden\" name=\"idgroupe\" value=\"$idgroupe\">".Html_texte("urlsite",$groupe->urlsite,50,250).closecol().closeligne();

$form.=openligne().opencol("colspan=\"2\" align=\"center\" ").
lang(191)." :<br><br>".Html_textezone("corps",15,50,$groupe->descriptiongroupe).
"<br><br>".Html_bouton("envoyer",lang(169))."</form>"
."<br><br>".lang(206).closecol().closeligne().closetab();
$form.="<br>";

if($idgroupe>0)
{       if(date("d")<=EQUIPE_FINJOURVIRER)
		$form.=frmexclurejoueur($idgroupe);
	else
        	$form.=msgtab(lang(233),lang(146));
}

return $form;
}



function frmquittegroupe() //formulaire de mise a zero
{
//le joueur doit recopier le mot OK puis valider
if(date("d")>EQUIPE_FINJOURVIRER)
$html=msgtab(lang(204),lang(146));
else
$html=msgtab(lang(231)."<br>".lang(117)."<br><br><form METHOD=\"POST\" ACTION=\"index.php?do=doquittegroupe\">&nbsp;&nbsp;&nbsp;".lang(143)." : ".html_texte("validok","",10,2).
"&nbsp;&nbsp;&nbsp;".Html_bouton("valide",lang(202))."<br>"."</form> ",lang(202));
return $html;
}

function doquittegroupe($vok)
{
global $internaute;

if($vok<>"OK")
{
	return(msgtab(lang(121),lang(120)));
}else{
	if(estadmingroupe($internaute->idcompte))
		fctgetoffteammaster($internaute->idcompte);
	else
                fctgetoffteam($internaute->idcompte);
	return(msgtab(lang(232),lang(202)));
}
}

function doexcluregroupe($idcompteexclu)
{
global $internaute;
$groupadmin=getgroupbymembre($internaute->idcompte);
$groupmembre=getgroupbymembre($idcompteexclu);
if($groupmembre->idgroupe==$groupadmin->idgroupe)
{
	if(estadmingroupe($internaute->idcompte))
	{
		if($internaute->idcompte==$idcompteexclu)
			fctgetoffteammaster($internaute->idcompte);
		else
                	fctgetoffteam($idcompteexclu);
	}
	return(msgtab(lang(234),lang(202)));
}
return "";
}

function tabgroupeprofil($idgroupe)
{
global $internaute;
//Afficher nomgroupe,initiales,progression, progression de chaque membre
$form="";
$infogroupe=getinfogroupe($idgroupe);
if($infogroupe->idgroupe>0)
{



	$form.="<br>".opentab("align=\"center\"").openligne("","titre").opencol("colspan=\"2\"")."$infogroupe->titregroupe [$infogroupe->initialgroupe]".closecol().closeligne();
	$form.=openligne("","").opencol("colspan=\"2\"").lang(191)." :".closecol().closeligne();
	$form.=openligne("","").opencol("colspan=\"2\"").$infogroupe->descriptiongroupe.closecol().closeligne();
	$form.=openligne().opencol("align=\"left\"").lang(192)." :".closecol().opencol().html_lien($infogroupe->pseudonyme,"do=nouvmessage&idjoueur=$infogroupe->idcompte").closecol().closeligne();
	$form.=openligne().opencol("align=\"left\"").lang(236)." :".closecol().opencol().print_reward($infogroupe->medor,$infogroupe->medargent,$infogroupe->medbronze).closecol().closeligne();
	$form.=openligne().opencol("align=\"left\"").lang(198)." :".closecol().opencol()."<a href=\"$infogroupe->urlsite\" target=\"_blank\">$infogroupe->urlsite</a>".closecol().closeligne();
	$form.=retiftrue(openligne().opencol("align=\"left\"").lang(271)." :".closecol().opencol().html_lien(lang(240),"do=showlstsujets&idforum=$infogroupe->idforum").closecol().closeligne(),forum_peutlire($internaute->idcompte,$infogroupe->idforum));
	$form.=openligne("","").opencol("colspan=\"2\"").lang(235)." :".closecol().closeligne();
	$res=getcompositionequipe($idgroupe);
        $form.=openligne("","").opencol("colspan=\"2\"");
        //Pseudonyme	Dateinscr	Capitalinscr	Portefeuille	Plusvalue
        $form.=opentab("width=\"100%\"").openligne("","titre").opencol().lienordre("Pseudonyme",lang(21)).closecol().opencol().lienordre("Dateinscr",lang(238)).closecol().opencol().lienordre("Capitalinscr",lang(237)).closecol().opencol().lienordre("Portefeuille",lang(24)).closecol().opencol().lienordre("Plusvalue",lang(22)).closecol().closeligne();
	while($ligne=LigneSuivante($res))
	{
           $form.=openligne().opencol().$ligne->pseudonyme.closecol().opencol().$ligne->dateinscription.closecol().opencol().$ligne->capitalinscr." �".closecol().opencol().$ligne->capital." �".closecol().opencol().$ligne->prog." %".closecol().closeligne();
	}
        $form.=closetab().closecol().closeligne();
	$form.=openligne().opencol("colspan=\"2\" align=\"center\" ")."".closecol().closeligne().closetab();
	$form.="<br>";

}
return $form;

}

function lstforums()
{
global $skinrep,$internaute;
//colonnes forum
// lu,nom forum, nombre de message,date dernier message, txtsujet pseudo(idsujet,idmessage) du dernier message
// ,Description du forum,,
$reqforums=get_listeforums();
$html="";
$html.="<br>".opentab("align=\"center\"  width=\"90%\" ");
$anssection="";
$html.=openligne("","titre2").opencol("colspan=\"2\"")."<a href=\"\">".lang(158)."</a>".closecol().opencol().lang(243).closecol().opencol().lang(244).closecol().
"<th>".lang(245)."</th>".closeligne();
while($lignefo=LigneSuivante($reqforums))
{
 //nouvelle section
if($anssection!=$lignefo->libellesection)
{
$anssection=$lignefo->libellesection;
$html.=openligne("","titre").opencol("colspan=\"5\"").$lignefo->libellesection.closecol().closeligne();
}
if(!$internaute->authlevel>0)
 $internaute->toutvuforum=mktime(1,0, 0, date("m"), date("d"), date("y"));
 //nouveau forum
if($lignefo->notif_new && $lignefo->nbsujets>0 && !($lignefo->datepost<$internaute->toutvuforum))
	$lnk="<img src=\"$skinrep/nouvmess.png\" border=\"0\" TITLE=\"".lang(247)."\">";
else
	$lnk="<img src=\"$skinrep/pasnouvmess.png\" border=\"0\" TITLE=\"".lang(246)."\">";

$html.=openligne("","").opencol("width=\"25\"").$lnk.closecol().
opencol("width=\"80%\"")."<a href=\"?do=showlstsujets&idforum=".$lignefo->frmid."\">$lignefo->nomforum</a>"."<br>".$lignefo->descriptionforum.retiftrue("<div align=right>".html_lien("Synchroniser","do=syncforum&idforum=$lignefo->frmid")."</div>",$internaute->authlevel>1).closecol().opencol("align=\"center\"")."$lignefo->nbsujets".closecol().opencol("align=\"center\"")."$lignefo->nbmessages".closecol().
opencol("width=\"20%\"")."<nobr>".retiftrue(date("j M Y H:i a",$lignefo->datepost)."</nobr><br>$lignefo->pseudonyme ".html_lien("<img src=\"$skinrep/goto.gif\" border=\"0\" TITLE=\"".lang(249)."\">","do=showlstposts&idsujet=$lignefo->idsujet&last=1#last"),$lignefo->idsujet,lang(248)).closecol().closeligne();


}


$html.=closetab();
$html.="<center><div style=\"width: 80px; height: 45px;\">
<a href=\"http://www.webrankinfo.com/livres/\">
<img src=\"http://www.webrankinfo.com/images/wri/webrankinfo-80-15.png\"
 style=\"width:80px; height:15px; border:0;\" title=\"WebRankInfo\"
 alt=\"Livres Informatique\"></a><marquee style=\"border: 0; font-family: Arial;
 font-size: 9px; font-weight: normal; background-color: white; width: 80px;
 height: 15px;\" scrollamount=\"1\" scrolldelay=\"20\" onmouseover=\"this.stop()\"
 onmouseout=\"this.start()\">Ce site est list� dans la cat�gorie
<a href=\"http://www.dicodunet.com/annuaire/cat-157-jeux-sur-internet.htm\">
Jeux sur Internet</a> :
<a href=\"http://www.dicodunet.com/annuaire/cat-4323-jeux-de-simulation-en-ligne.htm\">
Jeux de simulation en ligne</a></marquee><a href=\"http://www.dicodunet.com/annuaire/\">
<img src=\"http://www.webrankinfo.com/images/dicodunet-80-15.png\" title=\"DicoDuNet\"
 style=\"width:80px; height:15px; border:0;\" alt=\"Annuaire gratuit\"></a></div></center>
";
return $html;
}

function lstsujets($idforum,$numligne)
{
global $skinrep,$internaute;

if(!$internaute->authlevel>0)
 $internaute->toutvuforum=mktime(1,0, 0, date("m"), date("d"), date("y"));
$from=$numligne;
$reqforums=get_listesujets($idforum,$from,NB_SUJETS_PAR_PAGE);
$infoforum=get_infoforum($idforum);

if(!forum_peutlire($internaute->idcompte,$idforum))
	return msgtab(lang(260),lang(261));
$html="";
$barre=barrepage($infoforum->nbsujets,NB_SUJETS_PAR_PAGE,$numligne,"&idforum=$idforum");

$html.=$barre."<br>".opentab("align=\"center\"  width=\"90%\" ");
$anssection="";
$html.=openligne("","titre2")."<th colspan=\"2\" align=\"left\">"."<a href=\"?do=showlstforums\">".lang(158)."</a> -&#62; <a href=\"\">".$infoforum->nomforum."</a></th><th>".lang(251)."</th><th>".lang(252)."</th>".
"<th>".lang(253)."</th><th>".lang(245)."</th>".closeligne();
if(forum_peutposter($internaute->idcompte,$idforum))
	$html.=openligne("","titre").opencol("colspan=\"6\"")."<STRONG>".html_lien(lang(255),"do=forumpostmessage&idforum=$idforum")."</STRONG>".closecol().closeligne();
if($infoforum->nbmessages>0)
{
	while($lignefo=LigneSuivante($reqforums))
	{
		if($lignefo->notif_new && !($lignefo->datepost<$internaute->toutvuforum))
			$lnk="<img src=\"$skinrep/nouvmess.png\" border=\"0\" TITLE=\"".lang(247)."\">";
		else
			$lnk="<img src=\"$skinrep/pasnouvmess.png\" border=\"0\" TITLE=\"".lang(246)."\">";

		$html.=openligne("","").opencol("width=\"25\"").$lnk.closecol().
		opencol("width=\"80%\"").html_lien($lignefo->txtsujet,"do=showlstposts&idsujet=".$lignefo->numsujet).closecol().opencol("align=\"center\"")."$lignefo->pseudoauteur".closecol().opencol("align=\"center\"")."$lignefo->s_nbmessages".closecol().opencol("align=\"center\"")."$lignefo->nblectures".closecol().
		opencol("width=\"20%\"")."<span class=\"gensmall\"><nobr>".date("j M Y H:i a",$lignefo->datepost)."</nobr><br>$lignefo->lastpseudo </span> ".html_lien("<img src=\"$skinrep/goto.gif\" border=\"0\" TITLE=\"".lang(249)."\">","do=showlstposts&idsujet=$lignefo->numsujet&last=1#last").closecol().closeligne();
	}
}else{
	$html.=openligne("","").opencol("colspan=\"6\"")."<center>".lang(254)."</center>".closecol().closeligne();
}

$html.=closetab()."<br>$barre";
return $html;
}

function lstposts($idsujet,$numligne,$seelast=false)
{
global $skinrep,$internaute;
//colonnes forum
// lu,nom forum, nombre de message,date dernier message, txtsujet pseudo(idsujet,idmessage) du dernier message
// ,Description du forum,,
$infosujet=get_infosujet($idsujet);
if($seelast && $infosujet->s_nbmessages+1>NB_MESS_PAR_PAGE && $numligne==0)
{
	$numligne=ceil((($infosujet->s_nbmessages+1)/NB_MESS_PAR_PAGE))*NB_MESS_PAR_PAGE-NB_MESS_PAR_PAGE;
}

$reqforums=get_listemessages($idsujet,$numligne,NB_MESS_PAR_PAGE);
if(!forum_peutlire($internaute->idcompte,$infosujet->idforum))
	return msgtab(lang(260),lang(261));
$html="";
if($seelast||!$numligne)
{
        $lastmess=forum_getlastmessagesujet($idsujet);
	if(!($lastmess->datepost<$internaute->toutvuforum))
		setsujetlu($idsujet);
	if($internaute->authlevel<2)
        	forum_inc_nblectures($idsujet);
}
$barre=barrepage($infosujet->s_nbmessages+1,NB_MESS_PAR_PAGE,$numligne,"last=0");

$html.=$barre."<br>".opentab("align=\"center\"  width=\"90%\" ");
$anssection="";
$html.=openligne("","titre2")."<th colspan=\"2\" align=\"left\">"."<a href=\"?do=showlstforums\">".lang(158)."</a> -&#62; <a href=\"?do=showlstsujets&idforum=$infosujet->idforum\">$infosujet->nomforum</a> -&#62; <a href=\"\">".$infosujet->txtsujet."</a></th>".closeligne();
$html.=openligne("","titre")."<th>".lang(251)."</th>"."<th>".lang(256)."</th>".closeligne();
//idforum idsection nomforum descriptionforum nbsujets nbmessages idlastmessage authread authwrite idmessage idsujet datepost
//idcompte idsujet idforum idcompteauteur s_nbmessages txtsujet idlastmessage nblectures idmessage contenu
$peutposter=forum_peutposter($internaute->idcompte,$infosujet->idforum);
while($lignefo=LigneSuivante($reqforums))
{
	$html.=openligne("","").opencol("width=\"20%\" valign=\"top\"").retiftrue("<a name=\"last\"></a>",$lignefo->idmessage==$infosujet->idlastmessage)."<STRONG>".$lignefo->auteur."</STRONG><br>".retiftrue(print_reward($lignefo->medor,$lignefo->medargent,$lignefo->medbronze)."<br><a href=\"?do=viewgroupeprofil&idgroupe=$lignefo->idgroupe\"><font class=\"gain\">[$lignefo->initialgroupe]</font></a>",$lignefo->idgroupe)."<br><br>".lang(244).": $lignefo->nbpostforum"."<br>".lang(22).": ".round(floatval($lignefo->prog),2)." %".closecol().
	opencol("valign=\"top\"")."<span class=\"gensmall\">".retiftrue("<div style=\"display: inline;float: right;\">".retiftrue(html_lien(lang(268),"do=forumpostmessage&idmessage=$lignefo->idmessage&idsujet=$lignefo->idsujet&edit=1")." ",forum_peut_editer($lignefo,$infosujet)).html_lien(lang(257),"do=forumpostmessage&idmessage=$lignefo->idmessage&idsujet=$lignefo->idsujet")."</div>",$peutposter).date("j M Y H:i a",$lignefo->datepost)."</span><hr>".bbtohtml(str_replace(array("&quot;"),array("\""), stripslashes($lignefo->contenu))).closecol().closeligne();
}


$html.=closetab()."<br>$barre";
return $html;
}





function javaforum()
{
return "<script language=\"JavaScript\" type=\"text/javascript\">
<!--
// bbCode control by
// subBlue design
// www.subBlue.com

// Startup variables
var imageTag = false;
var theSelection = false;

// Check for Browser & Platform for PC & IE specific bits
// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf(\"msie\") != -1) && (clientPC.indexOf(\"opera\") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
var is_moz = 0;

var is_win = ((clientPC.indexOf(\"win\")!=-1) || (clientPC.indexOf(\"16bit\") != -1));
var is_mac = (clientPC.indexOf(\"mac\")!=-1);

// Helpline messages
b_help = \"Texte gras: [b]texte[/b] (alt+b)\";
i_help = \"Texte italique: [i]texte[/i] (alt+i)\";
u_help = \"Texte soulign�: [u]texte[/u] (alt+u)\";
q_help = \"Citation: [quote]texte cit�[/quote] (alt+q)\";
c_help = \"Afficher du code: [code]code[/code] (alt+c)\";
l_help = \"Liste: [list]texte[/list] (alt+l)\";
o_help = \"Liste ordonn�e: [list=]texte[/list] (alt+o)\";
p_help = \"Ins�rer une image: [img]http://image_url/[/img] (alt+p)\";
w_help = \"Ins�rer un lien: [url]http://url/[/url] ou [url=http://url/]Nom[/url] (alt+w)\";
a_help = \"Fermer toutes les balises BBCode ouvertes\";
s_help = \"Couleur du texte: [color=red]texte[/color] Astuce: #FF0000 fonctionne aussi\";
f_help = \"Taille du texte: [size=x-small]texte en petit[/size]\";

// Define the bbCode tags
bbcode = new Array();
bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url=\"','\"]Texte du lien[/url]');
imageTag = false;

// Shows the help messages in the helpline window
function helpline(help) {
	document.post.helpbox.value = eval(help + \"_help\");
}


// Replacement for arrayname.length property
function getarraysize(thearray) {
	for (i = 0; i < thearray.length; i++) {
		if ((thearray[i] == \"undefined\") || (thearray[i] == \"\") || (thearray[i] == null))
			return i;
		}
	return thearray.length;
}

// Replacement for arrayname.push(value) not implemented in IE until version 5.5
// Appends element to the array
function arraypush(thearray,value) {
	thearray[ getarraysize(thearray) ] = value;
}

// Replacement for arrayname.pop() not implemented in IE until version 5.5
// Removes and returns the last element of an array
function arraypop(thearray) {
	thearraysize = getarraysize(thearray);
	retval = thearray[thearraysize - 1];
	delete thearray[thearraysize - 1];
	return retval;
}


function checkForm() {

	formErrors = false;

	if (document.post.message.value.length < 2) {
		formErrors = \"Vous devez entrer un message avant de poster.\";
	}

	if (formErrors) {
		alert(formErrors);
		return false;
	} else {
		bbstyle(-1);
		//formObj.preview.disabled = true;
		//formObj.submit.disabled = true;
		return true;
	}
}

function emoticon(text) {
	var txtarea = document.post.message;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos) {
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
		txtarea.focus();
	} else {
		txtarea.value  += text;
		txtarea.focus();
	}
}

function bbfontstyle(bbopen, bbclose) {
	var txtarea = document.post.message;

	if ((clientVer >= 4) && is_ie && is_win) {
		theSelection = document.selection.createRange().text;
		if (!theSelection) {
			txtarea.value += bbopen + bbclose;
			txtarea.focus();
			return;
		}
		document.selection.createRange().text = bbopen + theSelection + bbclose;
		txtarea.focus();
		return;
	}
	else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
	{
		mozWrap(txtarea, bbopen, bbclose);
		return;
	}
	else
	{
		txtarea.value += bbopen + bbclose;
		txtarea.focus();
	}
	storeCaret(txtarea);
}


function bbstyle(bbnumber) {
	var txtarea = document.post.message;

	txtarea.focus();
	donotinsert = false;
	theSelection = false;
	bblast = 0;

	if (bbnumber == -1) { // Close all open tags & default button names
		while (bbcode[0]) {
			butnumber = arraypop(bbcode) - 1;
			txtarea.value += bbtags[butnumber + 1];
			buttext = eval('document.post.addbbcode' + butnumber + '.value');
			eval('document.post.addbbcode' + butnumber + '.value =\"' + buttext.substr(0,(buttext.length - 1)) + '\"');
		}
		imageTag = false; // All tags are closed including image tags :D
		txtarea.focus();
		return;
	}

	if ((clientVer >= 4) && is_ie && is_win)
	{
		theSelection = document.selection.createRange().text; // Get text selection
		if (theSelection) {
			// Add tags around selection
			document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];
			txtarea.focus();
			theSelection = '';
			return;
		}
	}
	else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
	{
		mozWrap(txtarea, bbtags[bbnumber], bbtags[bbnumber+1]);
		return;
	}

	// Find last occurance of an open tag the same as the one just clicked
	for (i = 0; i < bbcode.length; i++) {
		if (bbcode[i] == bbnumber+1) {
			bblast = i;
			donotinsert = true;
		}
	}

	if (donotinsert) {		// Close all open tags up to the one just clicked & default button names
		while (bbcode[bblast]) {
				butnumber = arraypop(bbcode) - 1;
				txtarea.value += bbtags[butnumber + 1];
				buttext = eval('document.post.addbbcode' + butnumber + '.value');
				eval('document.post.addbbcode' + butnumber + '.value =\"' + buttext.substr(0,(buttext.length - 1)) + '\"');
				imageTag = false;
			}
			txtarea.focus();
			return;
	} else { // Open tags

		if (imageTag && (bbnumber != 14)) {		// Close image tag before adding another
			txtarea.value += bbtags[15];
			lastValue = arraypop(bbcode) - 1;	// Remove the close image tag from the list
			document.post.addbbcode14.value = \"Img\";	// Return button back to normal state
			imageTag = false;
		}

		// Open tag
		txtarea.value += bbtags[bbnumber];
		if ((bbnumber == 14) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag
		arraypush(bbcode,bbnumber+1);
		eval('document.post.addbbcode'+bbnumber+'.value += \"*\"');
		txtarea.focus();
		return;
	}
	storeCaret(txtarea);
}

// From http://www.massless.org/mozedit/
function mozWrap(txtarea, open, close)
{
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	if (selEnd == 1 || selEnd == 2)
		selEnd = selLength;

	var s1 = (txtarea.value).substring(0,selStart);
	var s2 = (txtarea.value).substring(selStart, selEnd)
	var s3 = (txtarea.value).substring(selEnd, selLength);
	txtarea.value = s1 + open + s2 + close + s3;
	return;
}

// Insert at Claret position. Code from
// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
function storeCaret(textEl) {
	if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
}

//-->
</script>";
}




function forum_postmessage($idforum=0,$idsujet=0,$idmessage=0,$corps="",$edit=0)
{
	global $skinrep;
if($idmessage>0)
{
	$mess=get_infomessage($idmessage);
	$idsujet=$mess->idsujet;
}

if($idsujet>0)
{
	$suj=get_infosujet($idsujet);
	$idforum=$suj->idforum;
}

if($idforum>0)
{
	$forum=get_infoforum($idforum);
}else{
	return msgtab("Le numero de forum n'est pas sp�cifi�","Erreur");
}

if($edit && !forum_peut_editer($mess,$forum))
{
	return "";
}
$idmesssujet=forum_getidmessagesujet($idsujet);


$hiddeninfos="<input type=\"hidden\" name=\"idmessage\" value=\"$idmessage\"><input type=\"hidden\" name=\"edit\" value=\"$edit\"><input type=\"hidden\" name=\"idforum\" value=\"$idforum\"><input type=\"hidden\" name=\"idsujet\" value=\"$idsujet\">";

	$form=javaforum()."";
	$form.="<form action=\"?do=doforumpostmessage\" method=\"post\" name=\"post\" onsubmit=\"return checkForm(this)\">".opentab("width=\"50%\" align=\"center\"");
	$form.="
".openligne("","titre")."
		<th class=\"thHead\" colspan=\"2\" height=\"25\"><b>".lang(258)."</b></th>
 ".closeligne()."
 ".retiftrue(openligne()."
	  <td class=\"row1\" width=\"22%\"><span class=\"gen\"><b>Sujet</b></span></td>

	  <td class=\"row2\" width=\"78%\"> <span class=\"gen\">
		<input type=\"text\" name=\"subject\" size=\"45\" maxlength=\"60\" style=\"width:450px\" tabindex=\"2\" class=\"post\" value=\"".retiftrue($suj->txtsujet,$edit && $idmesssujet==$idmessage)."\" />
		</span> </td>
 ".closeligne(),!$idsujet||($edit && $idmesssujet==$idmessage))."
 ".openligne()."
	  <td class=\"row1\" valign=\"top\">

     ".opentab("cellpadding=\"5\" cellspacing=\"0\" ","invi")."
				".openligne("align=\"center\"","invi")."
				  <td colspan=\"4\" class=\"gensmall\"><b>Smilies</b></td>

    ".closeligne()."
				".openligne("align=\"center\" valign=\"middle\"","invi")."
				  <td><a href=\"javascript:emoticon(':D')\"><img src=\"$skinrep/smiles/icon_biggrin.gif\" border=\"0\" alt=\"Very Happy\" title=\"Very Happy\" /></a></td>
				  <td><a href=\"javascript:emoticon(':)')\"><img src=\"$skinrep/smiles/icon_smile.gif\" border=\"0\" alt=\"Smile\" title=\"Smile\" /></a></td>
				  <td><a href=\"javascript:emoticon(':(')\"><img src=\"$skinrep/smiles/icon_sad.gif\" border=\"0\" alt=\"Sad\" title=\"Sad\" /></a></td>
				  <td><a href=\"javascript:emoticon(':o')\"><img src=\"$skinrep/smiles/icon_surprised.gif\" border=\"0\" alt=\"Surprised\" title=\"Surprised\" /></a></td>
    ".closeligne()."
				".openligne("align=\"center\" valign=\"middle\"","invi")."
				  <td><a href=\"javascript:emoticon(':shock:')\"><img src=\"$skinrep/smiles/icon_eek.gif\" border=\"0\" alt=\"Shocked\" title=\"Shocked\" /></a></td>

				  <td><a href=\"javascript:emoticon(':?')\"><img src=\"$skinrep/smiles/icon_confused.gif\" border=\"0\" alt=\"Confused\" title=\"Confused\" /></a></td>
				  <td><a href=\"javascript:emoticon('8)')\"><img src=\"$skinrep/smiles/icon_cool.gif\" border=\"0\" alt=\"Cool\" title=\"Cool\" /></a></td>
				  <td><a href=\"javascript:emoticon(':lol:')\"><img src=\"$skinrep/smiles/icon_lol.gif\" border=\"0\" alt=\"Laughing\" title=\"Laughing\" /></a></td>
    ".closeligne()."
				".openligne("align=\"center\" valign=\"middle\"","invi")."
				  <td><a href=\"javascript:emoticon(':x')\"><img src=\"$skinrep/smiles/icon_mad.gif\" border=\"0\" alt=\"Mad\" title=\"Mad\" /></a></td>
				  <td><a href=\"javascript:emoticon(':oops:')\"><img src=\"$skinrep/smiles/icon_redface.gif\" border=\"0\" alt=\"Embarassed\" title=\"Embarassed\" /></a></td>
				  <td><a href=\"javascript:emoticon(':cry:')\"><img src=\"$skinrep/smiles/icon_cry.gif\" border=\"0\" alt=\"Crying or Very sad\" title=\"Crying or Very sad\" /></a></td>
				  <td><a href=\"javascript:emoticon(':evil:')\"><img src=\"$skinrep/smiles/icon_evil.gif\" border=\"0\" alt=\"Evil or Very Mad\" title=\"Evil or Very Mad\" /></a></td>

    ".closeligne()."
				".openligne("align=\"center\" valign=\"middle\"","invi")."
				  <td><a href=\"javascript:emoticon(':roll:')\"><img src=\"$skinrep/smiles/icon_rolleyes.gif\" border=\"0\" alt=\"Rolling Eyes\" title=\"Rolling Eyes\" /></a></td>
				  <td><a href=\"javascript:emoticon(':wink:')\"><img src=\"$skinrep/smiles/icon_wink.gif\" border=\"0\" alt=\"Wink\" title=\"Wink\" /></a></td>
				  <td><a href=\"javascript:emoticon(':!:')\"><img src=\"$skinrep/smiles/icon_exclaim.gif\" border=\"0\" alt=\"Exclamation\" title=\"Exclamation\" /></a></td>
				  <td><a href=\"javascript:emoticon(':?:')\"><img src=\"$skinrep/smiles/icon_question.gif\" border=\"0\" alt=\"Question\" title=\"Question\" /></a></td>
    ".closeligne()."
				".openligne("align=\"center\" valign=\"middle\"","invi")."
				  <td><a href=\"javascript:emoticon(':idea:')\"><img src=\"$skinrep/smiles/icon_idea.gif\" border=\"0\" alt=\"Idea\" title=\"Idea\" /></a></td>

				  <td><a href=\"javascript:emoticon(':arrow:')\"><img src=\"$skinrep/smiles/icon_arrow.gif\" border=\"0\" alt=\"Arrow\" title=\"Arrow\" /></a></td>
				  <td><a href=\"javascript:emoticon(':neutral:')\"><img src=\"$skinrep/smiles/icon_neutral.gif\" border=\"0\" alt=\"Neutral\" title=\"Neutral\" /></a></td>
				  <td><a href=\"javascript:emoticon(':mrgreen:')\"><img src=\"$skinrep/smiles/icon_mrgreen.gif\" border=\"0\" alt=\"Mr. Green\" title=\"Mr. Green\" /></a></td>
    ".closeligne()."  </table>

	  </td>
	  <td class=\"row2\" valign=\"top\"><span class=\"gen\"> <span class=\"genmed\"> </span>
		<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
				".openligne("align=\"center\" valign=\"middle\"")."
			<td><span class=\"genmed\">

			  <input type=\"button\" class=\"button\" accesskey=\"b\" name=\"addbbcode0\" value=\" B \" style=\"font-weight:bold; width: 30px\" onClick=\"bbstyle(0)\" onMouseOver=\"helpline('b')\" />
			  </span></td>
			<td><span class=\"genmed\">
			  <input type=\"button\" class=\"button\" accesskey=\"i\" name=\"addbbcode2\" value=\" i \" style=\"font-style:italic; width: 30px\" onClick=\"bbstyle(2)\" onMouseOver=\"helpline('i')\" />
			  </span></td>
			<td><span class=\"genmed\">
			  <input type=\"button\" class=\"button\" accesskey=\"u\" name=\"addbbcode4\" value=\" u \" style=\"text-decoration: underline; width: 30px\" onClick=\"bbstyle(4)\" onMouseOver=\"helpline('u')\" />
			  </span></td>
			<td><span class=\"genmed\">

			  <input type=\"button\" class=\"button\" accesskey=\"q\" name=\"addbbcode6\" value=\"Quote\" style=\"width: 50px\" onClick=\"bbstyle(6)\" onMouseOver=\"helpline('q')\" />
			  </span></td>
			<td><span class=\"genmed\">
			  <input type=\"button\" class=\"button\" accesskey=\"c\" name=\"addbbcode8\" value=\"Code\" style=\"width: 40px\" onClick=\"bbstyle(8)\" onMouseOver=\"helpline('c')\" />
			  </span></td>
			<td><span class=\"genmed\">
			  <input type=\"button\" class=\"button\" accesskey=\"l\" name=\"addbbcode10\" value=\"List\" style=\"width: 40px\" onClick=\"bbstyle(10)\" onMouseOver=\"helpline('l')\" />
			  </span></td>
			<td><span class=\"genmed\">

			  <input type=\"button\" class=\"button\" accesskey=\"o\" name=\"addbbcode12\" value=\"List=\" style=\"width: 40px\" onClick=\"bbstyle(12)\" onMouseOver=\"helpline('o')\" />
			  </span></td>
			<td><span class=\"genmed\">
			  <input type=\"button\" class=\"button\" accesskey=\"p\" name=\"addbbcode14\" value=\"Img\" style=\"width: 40px\"  onClick=\"bbstyle(14)\" onMouseOver=\"helpline('p')\" />
			  </span></td>
			<td><span class=\"genmed\">
			  <input type=\"button\" class=\"button\" accesskey=\"w\" name=\"addbbcode16\" value=\"URL\" style=\"text-decoration: underline; width: 40px\" onClick=\"bbstyle(16)\" onMouseOver=\"helpline('w')\" />
			  </span></td>
    ".closeligne()."

    ".openligne()."
			<td colspan=\"9\">
			  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
    ".openligne()."
				  <td><span class=\"genmed\"> &nbsp;Couleur:
					<select name=\"addbbcode18\" onChange=\"bbfontstyle('[color=' + this.form.addbbcode18.options[this.form.addbbcode18.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;\" onMouseOver=\"helpline('s')\">
					  <option style=\"color:black; background-color: #FAFAFA\" value=\"#444444\" class=\"genmed\">D�faut</option>
					  <option style=\"color:darkred; background-color: #FAFAFA\" value=\"darkred\" class=\"genmed\">Rouge fonc�</option>

					  <option style=\"color:red; background-color: #FAFAFA\" value=\"red\" class=\"genmed\">Rouge</option>
					  <option style=\"color:orange; background-color: #FAFAFA\" value=\"orange\" class=\"genmed\">Orange</option>
					  <option style=\"color:brown; background-color: #FAFAFA\" value=\"brown\" class=\"genmed\">Marron</option>
					  <option style=\"color:yellow; background-color: #FAFAFA\" value=\"yellow\" class=\"genmed\">Jaune</option>
					  <option style=\"color:green; background-color: #FAFAFA\" value=\"green\" class=\"genmed\">Vert</option>
					  <option style=\"color:olive; background-color: #FAFAFA\" value=\"olive\" class=\"genmed\">Olive</option>

					  <option style=\"color:cyan; background-color: #FAFAFA\" value=\"cyan\" class=\"genmed\">Cyan</option>
					  <option style=\"color:blue; background-color: #FAFAFA\" value=\"blue\" class=\"genmed\">Bleu</option>
					  <option style=\"color:darkblue; background-color: #FAFAFA\" value=\"darkblue\" class=\"genmed\">Bleu fonc�</option>
					  <option style=\"color:indigo; background-color: #FAFAFA\" value=\"indigo\" class=\"genmed\">Indigo</option>
					  <option style=\"color:violet; background-color: #FAFAFA\" value=\"violet\" class=\"genmed\">Violet</option>
					  <option style=\"color:white; background-color: #FAFAFA\" value=\"white\" class=\"genmed\">Blanc</option>

					  <option style=\"color:black; background-color: #FAFAFA\" value=\"black\" class=\"genmed\">Noir</option>
					</select> &nbsp;Taille:<select name=\"addbbcode20\" onChange=\"bbfontstyle('[size=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/size]')\" onMouseOver=\"helpline('f')\">
					  <option value=\"7\" class=\"genmed\">Tr�s petit</option>
					  <option value=\"9\" class=\"genmed\">Petit</option>
					  <option value=\"12\" selected class=\"genmed\">Normal</option>
					  <option value=\"18\" class=\"genmed\">Grand</option>

					  <option  value=\"24\" class=\"genmed\">Tr�s grand</option>
					</select>
					</span></td>
				  <td nowrap=\"nowrap\" align=\"right\"><span class=\"gensmall\"><a href=\"javascript:bbstyle(-1)\" class=\"genmed\" onMouseOver=\"helpline('a')\">Fermer les Balises</a></span></td>
    ".closeligne()."
			  </table>
			</td>
    ".closeligne()."

    ".openligne()."
			<td colspan=\"9\">
<input type=\"text\" name=\"helpbox\" size=\"45\" style=\"width:450px\" tabindex=\"2\" class=\"post\" value=\"Astuce: Une mise en forme peut �tre appliqu�e au texte s�lectionn�.\" />

			  </td>
    ".closeligne()."
    ".openligne()."
			<td colspan=\"9\"><span class=\"gen\">
			  <textarea name=\"message\" rows=\"15\" cols=\"35\" wrap=\"virtual\" style=\"width:450px\" tabindex=\"3\" class=\"post\" onselect=\"storeCaret(this);\" onclick=\"storeCaret(this);\" onkeyup=\"storeCaret(this);\">".retiftrue($mess->contenu,$idmessage && $edit).retiftrue(chr(13).chr(13).chr(13).chr(13)."[quote]$mess->contenu[/quote]",$idmessage && !$edit)."$corps</textarea>

			  </span></td>
    ".closeligne()."
		</table>
		</span></td>
	".closeligne()."".openligne().opencol(" colspan=\"2\" align=\"center\"").$hiddeninfos.Html_bouton("post",lang(169)).closecol().closeligne().openligne()." <td class=\"gensmall\" colspan=\"2\" height=\"25\">  <a href=\"http://www.phpbb.com/\" target=\"_phpbb\" class=\"copyright\"> Formulaire phpBB</a>&copy; 2001, 2005 phpBB Group</td>".closeligne()."";

















	$form.=closetab();
	return $form;
}

function formchgmdp()
{
global $internaute;
//pseudonyme` , `passe` , `nom` , `prenom` , `adresse` , `cp` , `ville` , `tel` , `email` , `etablissement`
$echo = jscript_profil2()."<br><br><br>
      <form name=\"forminscr\" method=\"POST\" action=\"index.php?do=dochgmdp\"   onSubmit=\"return test();\">
          ".opentab("align=\"center\" ","fond").openligne("","titre2").opencol(" colspan=\"4\"")."<b>".lang(272)." :</b>".closecol().closeligne()."
          ".openligne()."
            <td align=\"right\">Nouveau mot de passe :&nbsp;".closecol()."
            ".opencol()."
              ".Html_pass("nmdp","",30,255)."
              ".closecol()."
            <td align=\"right\"> Confirmation :&nbsp;".closecol()."
            ".opencol()."
              ".Html_pass("cnmdp","",30,255)."
              ".closecol()."
          ".closeligne()."

        </table><br><br><center>
              ".Html_bouton("Submit",lang(64))."
      </center></form>";

return $echo;
}



function disabledaily($idcompte,$chainemd5)
{
	$lejoueur=getinfojoueur($idcompte);
	if(md5($idcompte.$lejoueur->dateinscr)==$chainemd5)
	{
	        deactivatedaystats($idcompte);
		return "Vous ne recevrez plus votre email quotidien de statistique.";
	}else{
		return "Informations incorrect";
	}
}



function disableweekly($idcompte,$chainemd5)
{
	$lejoueur=getinfojoueur($idcompte);
	if(md5($idcompte.$lejoueur->dateinscr)==$chainemd5)
	{
	        deactivateweekstats($idcompte);
		return "Vous ne recevrez plus votre email hebdomadaire de statistique.";
	}else{
		return "Informations incorrect";
	}
}

function junkoldsicav($idcompte, $chainemd5, $codesico)
{
$lejoueur=getinfojoueur($idcompte);
	if(md5($idcompte.$lejoueur->dateinscr)==$chainemd5)
	{
		$infoaction=getinfosicav($codesico);
		if($infoaction->codesico>0)
		{
			if(CONSIDERER_OUTDATED_SICAV*3600*24<date("U")-$infoaction->lasttime)
			{       $portef=joueur_possede($codesico,$idcompte); //nombsicav valeur
				if($portef->nombsicav>0)
				{
					//Action achet�,
	                                ModifLiquide($idcompte,$portef->ansvaleur*$portef->nombsicav);
	                        	AddHistorique($idcompte,"vente",$sicav,$portef->nombsicav,$portef->ansvaleur, 0, 0);
				}
				if($portef->nombsicav<0)
				{
	                                ModifLiquide($idcompte,$portef->ansvaleur*$portef->nombsicav); //retire des liquidit�s
	                        	AddHistorique($idcompte,"achat",$sicav,-$portef->nombsicav,$portef->ansvaleur, 0, 0);
				}
	                        ModifAction($idcompte,$codesico,0,0);
			}else{
				return "La derni�re mise � jour de l'action est trop recente pour la supprimer de cette facon." ;
			}
		}else{
			return "Vous n'avez pas cette action dans votre portefeuille !";
		}
	}
}

?>
