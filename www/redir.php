<?
//profil d'un groupe( classement integr�)
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
$internaute->idcompte=1;
$internaute->authlevel=2;

include_once ("const.php");
include_once ("constbdd.php");
include_once ("db_connect.php");

$url=sec($_GET['url']);
function get_ip(){
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];}
elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
$ip = $_SERVER['HTTP_CLIENT_IP'];}
else{ $ip = $_SERVER['REMOTE_ADDR'];}
return $ip;}$ip = get_ip();
$query = "INSERT INTO `statout` ( `tmps` , `ip`, `url` )
VALUES (
UNIX_TIMESTAMP() , '$ip', '$url'
)";
$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
$run_query =  ExecRequete ($query, $connexion);

header("Status: 302 Found");
header("Location: $url");
exit();
?>