<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
include_once ("const.php");
include_once ("constbdd.php");
include_once ("db_connect.php");
include_once ("db_reqfunction.php");
include_once ("progfunc.php");
include_once ("progreq.php");
include_once ("nt2_function.php");
include_once ("lang/lang_fr.php");
include_once ("nt2_pages.php");

global $internaute,$explorer;
$explorer=0;
$explorer=&intval($_GET['explorer']);
$internaute="";
$do="";
$do=&$_GET['do'];
$sess="";
$sess=&$_GET['sess'];

if($do<>"login")
{
	if($sess<>"")
	{
		$mess= ControleProgAcces(sec($sess));
	}
}else{
	$mess= proglogin($_GET['pseudo'],$_GET['pass']);
}
echo "<xml><flux>".$mess;
switch($do) //sans etre logg�
{
	case "infomsg": //message d'info
                echo proginfomess(sec($_GET['progver']),sec($_GET['progtyp']));
		break;
}

if($internaute->authlevel>=1)
{
	switch($do) //en �tant logg�
	{
		case "deco": //deco
			echo xmess(progdeco());
			break;
		case "portef": //affiche le portefeuille du joueur
			echo progportef();
			break;
		case "lstordre": // affiche la liste des ordres
			echo progordre();
			break;
		case "lstordreportef": //affiche les ordres et le portefeuille
			echo progportef();
			echo progordre();
			break;
		case "lstactionsachat":
			echo progactionslist();
			break;
		case "sendachatvente":
			echo "<messageordre>".creer_ordre(sec($_GET['sens']),sec($_GET['codesicav']),sec(intval($_GET['nbr'])),sec($_GET['valmin']),sec($_GET['valmax']),sec($_GET['tempsmin']),sec($_GET['select']),sec($_GET['ansval']),sec($_GET['seuil']),sec($_GET['nb2']))."</messageordre>";
			break;
		case "getachatmax":
			echo progachatmax(sec($_GET['codesico']));
			break;
		case "getventemax":
			echo progventemax(sec($_GET['codesico']));
			break;
		case "lsthisto":
			echo proglsthisto(sec($_GET['depuis']));
			break;
		case "supprordre":
			echo "<messagesuppr>".supprordre(get_FromFormatedTime(sec($_GET['idordre'])))."</messagesuppr>";
			break;
		case "getlienprofilaction":
			echo "<urlaide>".geturlaide(getyahooname(sec($_GET['codesico'])))."</urlaide>";
			break;
		case "getinfoaction":     //retourne un maximum d'information sur une action
			echo progallinfo(sec($_GET['codesico']));
			break;
		//todo getscoreplayerhisto retourne l'historique de la valeur du portefeuille
	}
	echo "<erreur>faux</erreur>";
}
echo "</flux>"."</xml>";

?>