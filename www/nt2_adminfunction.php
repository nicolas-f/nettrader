<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
function formadmin() //affiche la liste des requetes dispo
{
$tab = listadminreq();
$retour = sorttableau($tab,"Synth�se des joueurs :");
$retour.="<br><br>";
$retour.=msgtab(adminfuncliste(),"Administration du jeu :");
return $retour;
}


function frmadmincacval()
{
//On est face a une liste de valeurs que l'on peut s�l�ctionner
// tout en bas ce trouve le bouton valider, avec des coches
//la premi�re option permet de supprimer une action
//la deuxi�me option permet de multiplier les actions achet�s de tel date � tel date
//la troisi�me option permet de diviser les actions achet�s de tel date � tel date


$lst=get_lstactions();
$tab="<form NAME=\"listactions\" METHOD=\"POST\" action=\"index.php?do=domodiflstactions\">".opentab("align=\"center\" width=\"90%\" ");
$tab.=openligne();
$tab.=opencol()."#".closecol().opencol()."Modifier".closecol().opencol()."Nom".closecol().opencol()."Valeur".closecol().opencol()."Date de t�l�chargement".closecol();
$tab.=closeligne();
$num=0;
while($ligne=LigneSuivante($lst))
{
	$num++;
	$tab.=openligne();
	$tab.=opencol().$num.closecol().opencol()."<input type=\"checkbox\" name=\"sel[$num]\" value=\"$ligne->codesico\" id=\"checkbox_row_3\"/>".closecol().opencol()."$ligne->nom ( $ligne->codesico ) ( $ligne->authachat $ligne->down )".closecol().opencol().$ligne->valeur.closecol().opencol().date("j/m/y H:i:s",$ligne->lasttime).closecol();
	$tab.=closeligne();
}
$tab.=closetab()."<br><center><a href=\"\" onclick=\"setCheckboxesRange('listactions', true);return false;\"> Sel tous</A> <a href=\"\" onclick=\"setCheckboxesRange('listactions', false);return false;\"> Sel aucun</A>
<br><br>
<input type=\"radio\" name=\"optmodif\" value=\"supprimmer\" id=\"checkbox_row_3\"/> Supprimmer<br><br>
<input type=\"radio\" name=\"optmodif\" value=\"activer\" id=\"checkbox_row_3\"/> Activer<br><br>

De <input name=\"debmodif\" value=\"01/01/2001 18:00\" size=\"22\" maxlength=\"16\" class=\"post\" type=\"text\"> � <input name=\"finmodif\" value=\"".finjour()."\" size=\"22\" maxlength=\"16\" class=\"post\" type=\"text\"><br>
<input type=\"radio\" name=\"optmodif\" value=\"multiplier\" id=\"checkbox_row_3\"/> Multiplier&nbsp;&nbsp;&nbsp;&nbsp;
<input type=\"radio\" name=\"optmodif\" value=\"diviser\" id=\"checkbox_row_3\"/> Diviser<br>
Par <input name=\"facteur\" value=\"0\" size=\"22\" maxlength=\"16\" class=\"post\" type=\"text\">



<br><br>".Html_bouton("submit","Valider")."</center></form>";




$code="
<script language=\"JavaScript\">
function setCheckboxesRange(the_form, do_check)
{
    for (var i = 1; i <= $num; i++) {
        if (typeof(document.forms[the_form].elements['sel['+i+']']) != 'undefined') {
            document.forms[the_form].elements['sel['+i+']'].checked = do_check;
        }
    }
    return true;
}
</script>
".$tab;



return $code;
}

function adminfuncliste()
{

$retour="<a href=\"index.php?do=lstusertodel\">Supprimer les joueurs ne jouant plus</a><br><br>";

$retour.="<a href=\"index.php?do=lstadmincacval\">Administration des actions boursi�re</a><br><br>";

$retour.="<a href=\"index.php?do=lstgroupesverif\">Accepter/Refuser la cr�ation de groupes</a><br><br>";
return $retour;
}


function afficheadminres($idreq)
{
$tab = exeadminreq($idreq);
$retour = sorttableau($tab->req,$tab->titre);
return $retour;
}

function lstplayeradmin()
{
$lst=get_oldplayer();
$tab="<form NAME=\"listplayer\" METHOD=\"POST\" action=\"index.php?do=dodellstplayer\">".opentab("align=\"center\" width=\"90%\" ");
$tab.=openligne();
$tab.=opencol()."#".closecol().opencol()."Supprimer".closecol().opencol()."Pseudo".closecol().opencol()."Ultime connection".closecol().opencol()."Date d'inscription".closecol();
$tab.=closeligne();
$num=0;
while($ligne=LigneSuivante($lst))
{
	$num++;
	$tab.=openligne();
	$tab.=opencol().$num.closecol().opencol()."<input type=\"checkbox\" name=\"sel[$num]\" value=\"$ligne->id\" id=\"checkbox_row_3\"/>".closecol().opencol()."$ligne->pseudo ( $ligne->id )".closecol().opencol().$ligne->lastconnect.closecol().opencol().$ligne->dateinscrfrm.closecol();
	$tab.=closeligne();
}
$tab.=closetab()."<br><center><a href=\"\" onclick=\"setCheckboxesRange('listplayer', true);return false;\"> Sel tous</A> <a href=\"\" onclick=\"setCheckboxesRange('listplayer', false);return false;\"> Sel aucun</A><br><br>".Html_bouton("submit","Supprimer")."</center></form>";




$code="
<script language=\"JavaScript\">
function setCheckboxesRange(the_form, do_check)
{
    for (var i = 1; i <= $num; i++) {
        if (typeof(document.forms[the_form].elements['sel['+i+']']) != 'undefined') {
            document.forms[the_form].elements['sel['+i+']'].checked = do_check;
        }
    }
    return true;
}
</script>
".$tab;

return $code;
}


function dodelplayers($lst)
{
if(count($lst)>0)
{
        $liste="";
	foreach ($lst as $key => $champ)
	{
		if($lst[$key]<>"" and $lst[$key]>1)
		{
			// $lst[$i] c'est un idcompte � supprimer;
			if($liste<>"")
			{
				$liste.=",";
			}
			$liste.="'".$lst[$key]."'";
		}
	}
	fctdoraz($liste);
}
$mess=msgtab($liste." supprim�s.","Supression de joueurs");
return $mess;
}

function modiflstactions($lst,$optmodif,$facteur,$debmodif,$finmodif)
{

$liste="";
foreach ($lst as $key => $champ)
{
	if($lst[$key]<>"" and $lst[$key]>1)
	{
		// $lst[$i] c'est un idcompte � supprimer;
		if($liste<>"")
		{
			$liste.=",";
		}
		$liste.="'".$lst[$key]."'";
	}
}

switch($optmodif)
{
	case "activer":
        modifetatactions($liste,1);
		break;
	case "supprimmer":
        delactions($liste);
		break;
	case "multiplier":
        factoriseactions($liste,"multiplier",$facteur,$debmodif,$finmodif);
		break;
	case "diviser":
        factoriseactions($liste,"diviser",$facteur,$debmodif,$finmodif);
		break;
}


$mess=msgtab("c bon","Modifications actions");
return $mess;
}

function tabgroupe($idverif,$idgroupe,$idcompte,$titre,$titrecourt,$urlsite,$presentation)
{
if($idgroupe==0)
{
	$typemodif="Demande d'ajout d'un groupe.";
}else{
        $typemodif="<a href=\"index.php?do=profilgroupe&idgroupe=$idgroupe\" target=\"_blank\">Modification d'un groupe. ( Voir ancien groupe )</a>";
}



$form="<br>".opentab("align=\"center\"").openligne("","titre").opencol("colspan=\"2\"").$typemodif.closecol().closeligne();
$form.="<form method=\"POST\" name=\"dogroupeaccepterefuse\" action=\"index.php?do=dogroupeaccepterefuse\">";
$form.=openligne().opencol("align=\"right\"").lang(189)." :".closecol().opencol().$titre.closecol().closeligne();
$form.=openligne().opencol("align=\"right\"").lang(190)." :".closecol().opencol().$titrecourt.closecol().closeligne();
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$maitre=chercheinternaute($idcompte,$connexion);
$form.=openligne().opencol("align=\"right\"").lang(192)." :".closecol().opencol().$maitre->pseudonyme.closecol().closeligne();
$form.=openligne().opencol("align=\"right\"").lang(198)." :".closecol().opencol()."<INPUT type=\"hidden\" name=\"idgroupe\" value=\"$idgroupe\"><INPUT type=\"hidden\" name=\"idverif\" value=\"$idverif\"><a href=\"$urlsite\" target=\"_blank\">$urlsite</a>".closecol().closeligne();

$form.=openligne().opencol("colspan=\"2\" align=\"center\" ").
lang(191)." :<br><br><div class=\"tab1\">".$presentation."</div><br><br>".Html_textezone("commentaireadmin",7,50,"Bonne Chance ! \n\n-Nicolas").
"<br><br>".Html_radio("choixadmin","1","Accepter","checked")."&nbsp;&nbsp;&nbsp;".Html_radio("choixadmin","0","Refuser","")."<br><br><br>".Html_bouton("submit","Accepter/Refuser")."</form>"
."<br><br>".closecol().closeligne().closetab();
$form.="<br>";


return $form;
}




function admingroupes()
{
//on va chercher la liste des groupes
$groupes=getverifgroupe($idgroupe=0,$idcompte=0);
$html="";
while($groupe=LigneSuivante($groupes))
{
// idverifgroupe  	 idgroupe  	 idcompte  	 titregroupe  	 initialgroupe  	 descriptiongroupe
$html.=tabgroupe($groupe->idverifgroupe,$groupe->idgroupe,$groupe->idcompte,$groupe->titregroupe,$groupe->initialgroupe,$groupe->urlsite,$groupe->descriptiongroupe);

}

return $html;
}

?>