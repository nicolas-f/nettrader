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
include_once ("db_reqtableaux.php");
include_once ("db_reqfunction.php");
include_once ("nt2_function.php");
include_once ("nt2_pages.php");

include_once ("lang/lang_fr.php");

global $skinrep;
$skinrep="skin/default";
include_once ($skinrep."/include_interface.php");

//$truc=cmd_downvaleur();
if($_GET['do']=="testscript")
{
	echo cmd_downhisto();
}
elseif($_GET['do']=="executeorder" && date("U")<FINCONC)
{
 	global $internaute;
        $internaute->idcompte=1;
	echo "\n".date("j M Y H:i a");
	if(tempsjeu())
		execute_ordre(); //on execute les ordres en attente si elles sont executables
        majstats();
	majclassement();
	checkoutdated();
}
elseif($_GET['do']=="checkscore" && date("U")<FINCONC)
{
 	global $internaute;
        $internaute->idcompte=1;
	echo "\n".date("j M Y H:i a");
	if(tempsjeu())
            checkscore();
}
elseif($_GET['do']=="webupdate" && date("U")<FINCONC)
{
 	global $internaute;
        $internaute->idcompte=1;
	echo "\n".date("j M Y H:i a");
	if(tempsjeu())
		checkscore();
	//echo cmd_downvaleur(); //refresh des valeurs de tous les joueurs
	echo cmd_downvaleur();
	if(tempsjeu())
		execute_ordre(); //on execute les ordres en attente si elles sont executables
        majstats();
	majclassement();
	checkoutdated();
}
elseif($_GET['do']=="localwebupdate" )
{
		echo cmd_downvaleur(); //refresh des valeurs de tous les joueurs
}

?>