<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
function Html_radio($nom,$valeur,$texte,$checked,$add="")
{
$source="<input type=\"radio\" value=\"$valeur\" $checked name=\"$nom\" id=\"$nom$valeur\" $add><label for=\"$nom$valeur\">$texte</label>";
return $source;
}


//function getgooglePub()
//{
//	return "<script type=\"text/javascript\"><!--
//google_ad_client = \"pub-7151069878409822\";
///* 728x90 texte */
//google_ad_slot = \"6657121588\";
//google_ad_width = 728;
//google_ad_height = 90;
////-->
//</script>
//<script type=\"text/javascript\"
//src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">
//</script>";
//}


//function getgooglePub()
//{
//	$ad_cycle=array("<!-- Affiliate Code Do NOT Modify--><a href=\"http://system.referfx.com/processing/clickthrgh.asp?btag=a_1569b_1534\"  target=\"_blank\"><img src=\"http://system.referfx.com/processing/impressions.asp?btag=a_1569b_1534\" alt=\"GFC Markets\" border=0 width=\"728\"  height=\"90\" ></a><!-- End affiliate Code-->",
//"<!-- Affiliate Code Do NOT Modify--><a href=\"http://system.referfx.com/processing/clickthrgh.asp?btag=a_1569b_1560\"  target=\"_blank\"><img src=\"http://system.referfx.com/processing/impressions.asp?btag=a_1569b_1560\" alt=\"GFC Markets\" border=0 width=\"728\"  height=\"90\" ></a><!-- End affiliate Code-->",
//"<!-- Affiliate Code Do NOT Modify--><a href=\"http://system.referfx.com/processing/clickthrgh.asp?btag=a_1569b_1823\"  target=\"_blank\"><img src=\"http://system.referfx.com/processing/impressions.asp?btag=a_1569b_1823\" alt=\"GFC Markets\" border=0 width=\"728\"  height=\"90\" ></a><!-- End affiliate Code-->");
//	return $ad_cycle[array_rand($ad_cycle)];
//}


function getgooglePub()
{
	return "<script type=\"text/javascript\">
<!--
var bseuri = 'http://script.banstex.com/script/affichagejs.aspx?zid=39496&rnd=' + new String (Math.random()).substring (2, 11);
document.write('<scr'+'ipt language=\"javascript\" src=\"'+bseuri+'\"></scr'+'ipt>');
-->
</script>";
}


function Html_texte($nom,$valeur,$taille,$longueurmax,$add="")
{
$source="<input name=\"$nom\" type=\"text\" value=\"$valeur\" size=\"$taille\" maxlength=\"$longueurmax\" $add class=\"post\">";
return $source;
}

function Html_textezone($nom,$lignes,$colonnes,$valeur,$add="")
{
$source="<textarea name=\"$nom\" rows=\"$lignes\" $add cols=\"$colonnes\" wrap=\"virtual\" class=\"post\" >$valeur</textarea>";
return $source;
}

function Html_pass($nom,$valeur,$taille,$longueurmax,$add="")
{
$source="<input name=\"$nom\" type=\"password\"  class=\"post\" value=\"$valeur\" size=\"$taille\" maxlength=\"$longueurmax\" $add>";
return $source;
}

function Html_bouton($nom,$valeur,$add="")
{
$source=" <input type=\"submit\" name=\"$nom\" class=\"mainoption\" value=\"$valeur\" $add>";
return $source;
}
function Html_head_liste($nom,$add="") // $liste = array('25' => '25 %', '50' => '50 %', '75' => '75%')
{
return "<select name=\"$nom\" class=\"post\" $add>";
}

function Html_liste($nom,$liste,$add="",$defaut="") // $liste = array('25' => '25 %', '50' => '50 %', '75' => '75%')
{
$source=Html_head_liste($nom,$add);
reset($liste);
while (list($key, $val) = each($liste)) {
   $def="";
   if($key==$defaut)
   {
   		$def="selected";
   }
   $source.= "<option $def value=\"$key\">$val</option>";
   
}
$source.="</select>";
return $source;
}


function opentab($attrib="",$type="")
{
$add=$attrib;
if($type=="invi")
{
	$add.="";
}else{
	if($type=="fond")
	{
		$add.=" class=\"tab1\" ";
	}else{
		$add.=" class=\"tab1\" ";
	}
}
//
$return = "<table border=\"0\" cellpadding=\"4\" cellspacing=\"1\" $add  >";
return $return;
}
function closetab()
{
$return = "</table>";
return $return;
}
function openligne($type="",$cat="")
{
$add=$type;
switch ($cat)
{
	
	case "titre":
		$add.=" class=\"row1\" ";
		break;
	case "titre2":
		$add.=" class=\"row2\" ";
		break;
	case "citation":
		$add.=" class=\"citation\" ";
		break;
	case "invi":
		break;
	default:
		$add.=" class=\"row3\"";
		break;
}
$return = "<tr $add>";
return $return;
}
function closeligne($type="")
{
$return = "</tr>";
return $return;
}
function opencol($type="",$cat="")
{
switch($type)
{
	case "back":
		$add ="  class=\"row1\" ";
		break;
	case "standart":
		$add ="  class=\"row3\" ";
		break;
	default :
		$add =$type;
		$add .="";
}
if($cat=="titre")
{
	$add.=" class=\"row1\" ";
}
$return = "<td $add>";
return $return;
}
function closecol($type="")
{
$return = "</td>";
return $return;
}
function openfont($fonttype="")
{
$echo ="<font";
switch($fonttype)
{
	case "titre1":
		$echo .=" class=\"titre2\" ";
		break;
	default :
		$echo .=" size=3 "; //taille normalle
}
$echo .= ">";
return $echo;
}
function closefont($fonttype="")
{
$echo ="</font";
$echo .= ">";
return $echo;
}

function html_header()
{
global $tempsdebexec,$skinrep;
$tempsdebexec=getmicrotime();
$echo="<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">
<meta http-equiv=\"Content-Style-Type\" content=\"text/css\">
<link href=\"$skinrep/style.css\" rel=\"stylesheet\" type=\"text/css\">
<title>".TITRE_JEU."</title>
<link rel=\"chapter NetTrader\" href=\"index.php?do=accueil\" title=\"Accueil\" />
<link rel=\"chapter NetTrader\" href=\"index.php?do=formachatvente&info=".ADSENSEKEYWORD."\" title=\"Portefeuille\" />
<link rel=\"chapter NetTrader\" href=\"index.php?do=lstactions\" title=\"Achat Vente\" />
<link rel=\"chapter NetTrader\" href=\"index.php?do=historique\" title=\"Historique\" />
<link rel=\"chapter NetTrader\" href=\"index.php?do=classement\" title=\"Classement\" />
<link rel=\"chapter NetTrader\" href=\"index.php?do=historique\" title=\"Historique\" />
<link rel=\"chapter NetTrader\" href=\"index.php?do=listemessage\" title=\"Messagerie\" />
<link rel=\"chapter NetTrader\" href=\"index.php?do=profil\" title=\"Profil Joueur\" />
<link rel=\"chapter NetTrader\" href=\"http://nettrader.apinc.org/phpBB2/\" title=\"Forum NetTrader\" />

</head>
<body bgcolor=\"#000000\" text=\"#FFFFFF\" link=\"#FFFFFF\" vlink=\"#8EC0DA\"> 
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\"> 
  <tr> 
    <td class=\"back1\">
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <tr> 
          <td><a href=\"index.php\"><img src=\" $skinrep/logonet2.gif\" border=\"0\" title=\"Accueil NetTrader 2\" vspace=\"1\" /></a></td>
          <td align=\"center\" width=\"100%\" valign=\"middle\"><span class=\"titre1\">NetTrader</span><br> 
            <span class=\"gen\">Jeu de simulation boursi�re </span><br>            <br>".html_menu()."</td> 
        </tr> 
    </table>".html_login()."";
	return $echo;
}

function html_footer()
{
global $nbreqexecuted,$tempsdebexec,$internaute,$skinrep,$tempssql;
$tempsexec = round(getmicrotime()-$tempsdebexec,2);
$info="";
if($internaute->authlevel>1 OR $internaute->idcompte==1)
{
	if($tempsexec>$tempssql)
	{
		$tempsphp=round($tempsexec-$tempssql,2);
	}else{
		$tempsphp=round($tempssql-$tempsexec,2);
	}
	$info="<br><font class=\"genmed\">$nbreqexecuted requ�tes ex�cut�es et $tempsphp secondes d'execution PHP, $tempssql  secondes d'execution Mysql, total $tempsexec secondes.</font>";
}
$echo= "<br>
      <br> 
      ".opentab("width=\"90%\" align=\"center\"").openligne("","titre").opencol()."Qui est en ligne ?".closecol().closeligne().openligne()." 
         
          <td><font class=\"genmed\">".connectstat()."</font>$info</td> 
        </tr> 
      </table> 

      <br><center>
         <span class=\"gensmall\">Les cours de la bourse ont 15 minutes de diff�r�s <br><br> &copy; Cr�� par <a href=\"index.php?do=contactauteur\" target=\"_blank\" class=\"copyright\">FORTIN Nicolas</a></span>
      </center><br><center>".LIGNEPARTENAIRES."</center>
  </tr> 
</table>";

if(!$internaute->authlevel>=1)
{
	$echo.="<center><script type=\"text/javascript\"><!--
	google_ad_client = \"pub-7151069878409822\";
	google_ad_width = 468;
	google_ad_height = 15;
	google_ad_format = \"468x15_0ads_al_s\";
	google_ad_channel = \"\";
	google_color_border = \"000000\";
	google_color_bg = \"000000\";
	google_color_link = \"FFFFFF\";
	google_color_text = \"000000\";
	google_color_url = \"008000\";
	//--></script>
	<script type=\"text/javascript\"
	  src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">
	</script></center>";
}
$echo.="�
</body>
</html>
";
	return $echo;
}

function html_login($frommenu=0)
{   
global $internaute,$do,$skinrep;
$echo="";
	if(($internaute=="" OR $do=="deconnect") && ($do!="frmlogin" or $frommenu) && $do!="forminscription")
	{
		$echo ="      <br> <FORM  METHOD='POST' ACTION='index.php?do=login' NAME='Form'>
      ".opentab("width=\"90%\" align=\"center\"")."
        ".openligne("","titre").opencol().lang(71)."</td>".openligne()."
          ".opencol(" align=\"center\"")."<span class=\"gensmall\"><b></b>&nbsp;&nbsp;Email : ".Html_texte('email',retiftrue("demo",$do!="frmlogin"),"30","50")."&nbsp;&nbsp;&nbsp;Mot de passe : ".Html_pass('motDePasse',retiftrue("demo",$do!="frmlogin"),"10","30")."&nbsp;&nbsp;&nbsp;".lang(80)."<input name=\"souvenir\" type=\"checkbox\" value=\"1\">&nbsp;&nbsp;&nbsp;".Html_bouton("ident",lang(71))."</span></td>
        </tr>
      </table></form>";
	}
return $echo;
}

function return_link_menu($head,$footer,$before) //to do: creer colonne dans bdd avec la valeur de do, puis mettra la valeur global ici et comparer pour pouvoir changer les images si page en cours
{
global $do,$skinrep,$internaute;
$liste = listmenu();
if($liste==""){return 1;}
$retour="";
foreach ($liste as $key => $value)
	{
		$retour.=$head."<a href=".$value["link_menu"]." class=\"mainmenu\"><nobr><img src=\"$skinrep/men".$value["text_id"].".gif\" title=\"".lang($value["text_id"])."\" border=\"\" > ".lang($value["text_id"])."</nobr></a>".$footer;
	}
if($internaute>0 AND $do<>"deconnect")
{
	$retour .= $head."<nobr><a href=\"index.php?do=deconnect\" class=\"mainmenu\"><img border=\"0\" title=\"$internaute->pseudonyme\" src=\"$skinrep/delog.gif\"> ".lang(70)." [ $internaute->pseudonyme ]</a></nobr>".$footer;
}else{
	$retour .= $head."<nobr><a href=\"index.php?do=frmlogin\" class=\"mainmenu\"><img border=\"0\" src=\"$skinrep/delog.gif\"> ".lang(71)."</a></nobr>".$footer;
}
return $retour;
}

function html_menu()
{   
global $internaute,$do,$skinrep;
		
$echo = return_link_menu("","&nbsp;&nbsp;&nbsp;",1);
    return $echo;
}


?>