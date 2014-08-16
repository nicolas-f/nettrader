<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
//profil d'un groupe( classement integr�)
session_start();
include_once ("const.php");
include_once ("constbdd.php");
include_once ("db_connect.php");
include_once ("db_reqtableaux.php");
include_once ("db_reqfunction.php");
include_once ("nt2_function.php");
include_once ("nt2_pages.php");

global $do,$skinrep,$notupdated;
$format="";
$sicavselecta="";
$sicavselectv="";
$internaute="";
$message="";

$souvenir=0;
$numligne=0;
$do=&$_GET['do'];
$email=&$_POST['email'];
$motDePasse=&$_POST['motDePasse'];
$format=&$_GET['format'];
$sicavselecta=&$_REQUEST['sicavselachat'];
$sicavselectv=&$_REQUEST['sicavselvendr'];
$souvenir=&$_POST['souvenir'];
$numligne=&$_GET['numligne'];
if($numligne<0)
        $numligne=0;
$cherche=&$_REQUEST['cherche'];

$dateclasstmp1=&$_POST['moisclasse'];
$dateclasstmp2=&$_GET['moisclasse'];
if(IS_NULL($dateclasstmp1))
{
	$dateclass=$dateclasstmp2;
}else{
        $dateclass=$dateclasstmp1;
}
if(IS_NULL($dateclass) or $dateclass=='')
{
	$dateclass=date("m-Y");
}


if($do<>"inscrjeu")
{
	$message = ControleAcces ($email,$motDePasse,$emailInternaute, sec(session_id()),$souvenir);
}
if(!INCONC)
{
	include_once ("lang/lang_fr.php");
}else{
        include_once ("lang/lang_pgsm.php");
}
if($internaute->authlevel>=1 AND $do=="deconnect")
{
	$message=$message.deconnection();
}
if($internaute=="")
{
	$skinrep="skin/default";
}else{
	$skinrep="skin/".$internaute->repskin;
}
include_once ($skinrep."/include_interface.php");

$skinrep.="/images";
//affichage ent�te menu+design
if($do=="")
{
	$do="accueil";
}
echo html_header();

echo "
	<span class=\"genmed\">
      ".lang(77)." ".date("j M Y H:i a")."<br>
        ".lang(78)." ".date("j M Y H:i a",get_tempsbourse())."</span>
      <br><center>".getgooglePub()."<center>";

if($message<>"")
{
	echo msgtab($message,"Information"); //message de bienvue ou erreur
}

$public=1;
switch ($do) //Tous
{
	
	case "accueil":
		echo pgaccueil(sec(intval($numligne)));
		break;
	case "forminscription":
		echo forminscription();
		break;
	case "inscrjeu":
		echo msgtab(inscrjeu(sec(trim($_POST['pseudo'])),"","","","","","",sec($_POST['mail']),"",sec(intval($_POST['lvl'])),sec($_POST['mailsemaine']),sec($_POST['mailjour'])),"Inscription");
		break;
	case "classement":
		//updatelistsicav(getclassementsicavlist()); //refresh des valeurs de tous les joueurs
		echo formclasse(sec(intval($numligne)),sec($dateclass),sec($cherche));
		break;
	case "formhelp":
		echo txt_help(sec(intval(tabvaleurouzero($_GET,'idaide'))));
		break;
	case "formfaq":
		echo txt_faq(sec(intval(tabvaleurouzero($_GET,'idaide'))));
		break;
	case "reglement":
		echo txt_regl();
		break;
	case "frmlogin":
		echo html_login(1);
		break;
	case "contactauteur":
		echo msgtab(formcontact(),lang(104));
		break;
	case "formrecuppass":
	        echo msgtab(formrecuppass(),lang(105));
	        break;
	case "dosendpass":
	        echo msgtab(formsendpass(sec($_POST['pseudo'])),lang(105));
	        break;
	case "rtrmdp":
	        echo msgtab(dosendpass(sec($_GET['c']),sec($_GET['m'])),lang(105));
	        break;
	case "classementequipe":
		echo classementequipes(sec(intval($numligne)),sec($dateclass),sec($cherche));
		break;
	case "viewgroupeprofil":
		echo tabgroupeprofil(sec($_GET['idgroupe']));
		break;
	case "showlstforums":
		echo lstforums();
		break;
	case "showlstsujets":
		echo lstsujets(sec(intval($_GET['idforum'])),sec(intval($numligne)));
		break;
	case "showlstposts":
		echo lstposts(sec(intval($_GET['idsujet'])),sec(intval($numligne)),sec(tabvaleurouzero($_GET,'last')));
		break;
	case "unabledailystats":
		echo disabledaily(sec(intval($_GET['idcompte'])),sec($_GET['checkstr']));
		break;
	case "unableweeklystats":
		echo disableweekly(sec(intval($_GET['idcompte'])),sec($_GET['checkstr']));
		break;
	case "junksicav":
		echo junkoldsicav(sec(intval($_GET['idcompte'])),sec($_GET['checkstr']),sec(intval($_GET['codesico'])));
		break;
	default :
		$public=0;
	}
$prive=0;
if($internaute->authlevel>=1) //acc�s restreint aux enregistr�s
{       $prive=1;
	switch ($do)
	{
	    case "deconnect":
			echo deconnection();
			break;
		case "login":
			if(getnvmessages()>0)
			{
					echo msgtab(lang(186),lang(86));
					echo form_messagerie(sec(intval($numligne)));
					break;
			}
	 	case "formachatvente":
			echo achatvente(sec($sicavselecta),sec($sicavselectv));
			break;
		case "formachatseul":
			echo formachat(sec($sicavselecta));
			break;
		case "formventeseul":
			echo formvente(sec($sicavselectv));
			break;
		case "venteaction":
		case "achataction":
			if(sec($_POST['codesicav'])<>"")
			{
				echo msgtab(creer_ordre(sec($_POST['sens']),sec($_POST['codesicav']),sec(intval($_POST['nbr'])),sec(str_replace(",",".",$_POST['valmin'])),sec(str_replace(",",".",$_POST['valmax'])),sec($_POST['tempsmin']),sec($_POST['select']),sec($_POST['ansval']),sec($_POST['seuil']),sec($_POST['nb2'])),lang(86));
			}
			echo "<br><br>";
			echo achatvente(sec($sicavselecta),sec($sicavselectv));
			break;	
	 	case "historique":
			echo formhisto(sec(intval($numligne)));
			break;
		case "listemessage":
			echo form_messagerie(sec(intval($numligne)),sec(intval(tabvaleurouzero($_GET,'ouvre'))));
			break;
		case "profil":
			echo formprofil();
			break;
		case "supprtoutordre":
			echo msgtab(supprtoutordre(),lang(86));
			echo "<br><br>";
			echo achatvente(sec($sicavselecta),sec($sicavselectv));
			break;
		case "supprordre":
			echo msgtab(supprordre(sec($_GET['idordre'])),lang(86));
			echo "<br><br>";
			echo achatvente(sec($sicavselecta),sec($sicavselectv));
			break;
		case "editprof":
			echo msgtab(editprofil(sec($_POST['mail']),sec($_POST['lvl']),sec($_POST['nbhisto']),sec($_POST['nbmsg']),sec($_POST['nbclasse']),sec($_POST['skin']),sec(intval($_POST['mailjour'])),sec(intval($_POST['mailsemaine']))),lang(86));
			break;
		case "derdate":
			echo msgtab(scoreestactuel(),"Derniere date ?");
			break;
		case "setscore":
			checkscore();
			break;
		case "formrazjoueur":
		        echo frmrazjoueur();
		        break;
		case "doraz":
			if(tempsjeu()) echo doraz(sec($_POST['mdp']),sec($_POST['validok']),sec($_POST['optiondel'])); else echo msgtab(lang(69),lang(69));
			break;
		case "lstactions":
            		echo lstAction(sec($format));
			break;
		case "suppcomment":
			delcommentaire(sec($_GET['idcomment']));
			echo txt_help(sec(intval(tabvaleurouzero($_GET,'idaide'))));
			break;
		case "postemessage":
			ajoutcommentaire(sec($_POST['message']),sec($_POST['idaide']));
			echo txt_help(sec(intval($_GET['idaide'])));
			break;
		case "suppcommentfaq":
			delcommentairefaq(sec($_GET['idcomment']));
			echo txt_faq(sec(intval($_GET['idaide'])));
			break;
		case "postemessagefaq":
			ajoutcommentairefaq(sec($_POST['message']),sec($_POST['idaide']));
			echo txt_faq(sec(intval($_GET['idaide'])));
			break;
		case "profilaction":
			echo profilaction(sec($_GET['yn']));
			break;
		case "invitejoueur": //inviter un joueur � rejoindre un groupe
                        if(estadmingroupe($internaute->idcompte)) echo doinvitejoueur(sec(intval($_POST['idjoueur'])));
		case "nouvmessage":
			echo form_nouvmessage(sec(intval($_REQUEST['idjoueur'])),sec($_REQUEST['titre']),$_REQUEST['corps']);
			break;
		case "postmessage":
			echo sendmessage(sec(intval($_POST['destinataire'])),sec($_POST['titre']),sec($_POST['corps']));
			echo form_messagerie(sec(intval($numligne)));
			break;
		case "delmessage":
			echo dodelmessage(sec(intval($_GET['idmessage'])));
			echo form_messagerie(sec(intval($numligne))); 
			break;
		case "ajgroupe":
			if(ACTIVATION_GROUPE)
				echo frmmodifajgroupe();
			break;
		case "modifgroupe":
			if(estadmingroupe($internaute->idcompte)) echo frmmodifajgroupe(getidgroupe($internaute->idcompte));
			break;
		case "domodifgroupe":
			if(estadmingroupe($internaute->idcompte,$_POST['idgroupe']) && $_POST['idgroupe']>0 ) echo domodifgroupe(sec($_POST['idgroupe']),sec($_POST['idchef']),sec($_POST['titreeq']),sec($_POST['titreeqcourt']),sec($_POST['urlsite']),sec($_POST['corps']));
			break;
		case "doajgroupe":
			echo doajgroupe($internaute->idcompte,sec($_POST['titreeq']),sec($_POST['titreeqcourt']),sec($_POST['urlsite']),sec($_POST['corps']));
			break;
		case "acceptinvite":
			echo dojoingroupe(sec(intval($_GET['idgroupe'])));
			echo tabgroupeprofil(sec($_GET['idgroupe']));
			break;
		case "supprtoutinvite":
			echo doundoallinvitegroupe();
			break;
		case "quittegroupe":
			echo frmquittegroupe();
			break;
		case "doquittegroupe":
			if(date("d")<=EQUIPE_FINJOURVIRER) echo doquittegroupe(sec($_POST['validok'])); else echo msgtab(lang(204),lang(146));
			break;
		case "exclurejoueur":
			if(date("d")<=EQUIPE_FINJOURVIRER && estadmingroupe($internaute->idcompte)) echo doexcluregroupe(sec($_POST['idcompteexclu'])); else echo msgtab(lang(204),lang(146));
			break;
		case "forumpostmessage":
			echo forum_postmessage(sec(intval(tabvaleurouzero($_GET,'idforum'))),sec(intval(tabvaleurouzero($_GET,'idsujet'))),sec(intval(tabvaleurouzero($_GET,'idmessage'))),"",sec(intval(tabvaleurouzero($_GET,'edit'))));
			break;
		case "doforumpostmessage": //$sujet,$corps,$idforum,$idsujet
			echo doforum_postmessage(sec(tabvaleurouzero($_POST,'subject')),sec($_POST['message']),sec($_POST['idforum']),sec($_POST['idsujet']),sec($_POST['edit']),sec($_POST['idmessage']));
			break;
		case "chgmdp":
			echo formchgmdp();
			break;
		case "dochgmdp":
			echo msgtab(chgmdp(sec($_POST['nmdp']),sec($_POST['cnmdp'])),lang(272));
			break;
		default:
			$prive=0;
	}
}

$admin=0;
if($internaute->authlevel>1) //ADMIN OU MODOS
{
	$admin=1;
	include_once ("nt2_adminfunction.php");
	switch ($do)
	{
		case "addsico":
			echo getvaleur(sec($_GET['sico']));
			break;	
		case "updatenom":
			echo updatenomsicav();
			break;	
		case "getunixtime":
			echo date("U");
			break;
		case "formadmin":
			echo formadmin();
			break;
		case "exeadmin":
			echo afficheadminres(sec($_GET['idreq']));
			break;
		case "lstusertodel":
			echo lstplayeradmin();
			break;
		case "dodellstplayer":
			echo dodelplayers($_POST['sel']);
			break;
		case "doexordres":
			echo execute_ordre();
			break;
		case "lstadmincacval":
			echo frmadmincacval();
			break;
		case "domodiflstactions":
			echo modiflstactions($_POST['sel'],sec($_POST['optmodif']),sec($_POST['facteur']),sec($_POST['debmodif']),sec($_POST['finmodif)']));
			break;
		case "lstgroupesverif":
			echo admingroupes();
			break;
		case "dogroupeaccepterefuse":
			echo dogroupeaccepterefuse(sec($_POST['idverif']),sec($_POST['choixadmin']),sec($_POST['commentaireadmin']));
                        echo admingroupes();
			break;
		case "majall":
                        majstats();
			majclassement();
                        majlistmoisclass();
			break;
		case "syncforum":
                        forumsyncquantity(sec($_GET['idforum']));
			forumsyncidlastmessage(sec($_GET['idforum']));
			break;
		case "forumtogroupes":
                        forum_giveforumtogroups();
			print msgtab("OK","OK");
		case "incarner":
                        incarnerjoueur(sec($_GET['idcompte']));
		default :
			$admin=0;
	}
}
if($public==0 && $prive==0 && $admin==0 && $do<>"deconnect")// si rien d'affich�
{
	//echo msgtab("Erreur 404, cette page n'existe pas, merci de prevenir l'auteur.","Lien incorrect !");
	echo pgaccueil(sec(intval($numligne)));
}


//affichage bas de page
list($thour, $tmin) = explode(" ",date("H i"));
if(($thour+round($tmin/60,2))<9.25  || ($thour+round($tmin/60,2))>17.917 || date('w')==0 || date('w')==6)
{
	echo "<p align=\"center\">".openfont("titre1").lang(132).closefont()."</p>";

}

	echo html_footer();

?>
