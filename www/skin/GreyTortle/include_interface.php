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
$source="<input type=\"radio\" value=\"$valeur\" $checked name=\"$nom\" $add>$texte";
return $source;
}

//function getgooglePub()
//{
//	return "<script type=\"text/javascript\"><!--
//google_ad_client = \"pub-7151069878409822\";
///* 728x90, turtle */
//google_ad_slot = \"0973697802\";
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

function Html_textezone($nom,$lignes,$colonnes,$valeur,$add="")
{
$source="<textarea name=\"$nom\" rows=\"$lignes\" $add cols=\"$colonnes\" wrap=\"virtual\" class=\"post\" >$valeur</textarea>";
return $source;
}

function Html_texte($nom,$valeur,$taille,$longueurmax,$add="")
{
$source="<input name=\"$nom\" type=\"text\" class=\"textbox\" value=\"$valeur\" size=\"$taille\" maxlength=\"$longueurmax\" $add>";
return $source;
}

function Html_pass($nom,$valeur,$taille,$longueurmax,$add="")
{
$source="<input name=\"$nom\" type=\"password\"  class=\"textbox\" value=\"$valeur\" size=\"$taille\" maxlength=\"$longueurmax\" $add>";
return $source;
}

function Html_bouton($nom,$valeur,$add="")
{
$source=" <input type=\"submit\" name=\"$nom\" class=\"bouton\" value=\"$valeur\" $add>";
return $source;
}
function Html_head_liste($nom,$add="") // $liste = array('25' => '25 %', '50' => '50 %', '75' => '75%')
{
return "<select name=\"$nom\" class=\"select\" $add>";
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
	$add.=" border=\"0\"";
}else{
	if($type=="fond")
	{
		$add.="border=0  bgcolor=\"E7E9F0\"";
	}else{
		$add.="border=\"0\" cellspacing=\"0\" cellpadding=\"5\" bgcolor=\"E7E9F0\"";
	}
}
$return = "<table  $add  >";
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
		$add.=" class=\"tabtitre\" ";
		break;
	case "titre2":
		$add.=" class=\"titre\" ";
		break;
	case "citation":
		$add.=" class=\"citation\" ";
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
		$add =" bgcolor=#D9DFEF";
		break;
	default :
		$add =$type;
}
if($cat=="titre")
{
	$add.=" class=\"tabtitre\" ";
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
		$echo .=" size=4 ";
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
$echo="<html>\n
<head>\n
<title>".TITRE_JEU."</title>\n
<link href=\"$skinrep/style.css\" rel=\"stylesheet\" type=\"text/css\">
".html_anime()."</head>
<body link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
\n
<div align=center>\n
<table width=770 cellspacing=0 border=0  bgcolor=EFEFEF>\n
<tr>\n
<td bgcolor=2B2D33><div align=center>";
/* $echo.="<SCRIPTLANGUAGE=\"JavaScript\">StartAnim();</SCRIPT>"; */
$echo.="<IMG SRC=\"$skinrep/top_gris1.jpg\" BORDER=0>";
$echo.="</div>\n
</td>\n
</tr>\n
<tr>\n
<td bgcolor=2B2D33> <table border=0 width=100% cellspacing=0 cellpadding=0>\n
<tr height=28>
<td align=left valign=\"midle\" background=$skinrep/menu_vide.jpg>".html_menu()."</td><td align=right background=$skinrep/menu_vide.jpg>".html_login();
					  
	$echo .="<tr><td><br>";

	return $echo;
}

function html_footer()
{
global $nbreqexecuted,$tempsdebexec,$internaute,$skinrep;
$tempsexec = round(getmicrotime()-$tempsdebexec,2);
$info="";
if($internaute->authlevel>1 OR $internaute->idcompte==16)
{
	$info="<font class=\"footer\"><i><center>$nbreqexecuted requ�tes ex�cut�es et $tempsexec secondes d'execution.</center></i></font><br>";
}
	$info.="<font class=\"footer\"><center><i>Cr�� par: <a href=\"index.php?do=contactauteur\" class=\"Liencreateur\" target=\"_blank\">FORTIN Nicolas</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Design: POTHIER Guillaume</i></center></font>";
$echo = "";
$echo.= "<br></div></td>
</tr>
<tr>
<td bgcolor=2B2D33>
<div align=center><hr>$info</div>

<br>
<div align=center><a href=\"index.php?do=formhelp\" class=\"Liencreateur\" >".lang(160)."</a> <a href=\"index.php?do=formfaq\" class=\"Liencreateur\" >".lang(166)."</a></div><hr>

</td>
</tr>
</table>
</div>
</body>
</html>";
	return $echo;
}

function html_login()
{   
global $internaute,$do,$skinrep;

	if($internaute>0 AND $do<>"deconnect")
	{
		$echo = "<b><a href=index.php?do=deconnect><img border=\"0\" title=\"$internaute->pseudonyme\" src=\"$skinrep/disconnect.jpg\"></a></b></td></tr></table></td></tr>";
	}else{
		$echo ="<FORM  METHOD='POST' ACTION='index.php?do=login' NAME='Form'><b>Membres :</b>&nbsp;&nbsp;Email : <INPUT class=textbox maxLength=30 size=10 name=email>&nbsp;&nbsp;Password : <INPUT class=textbox type=password maxLength=30 NAME='motDePasse' size=10>&nbsp;&nbsp;<INPUT class=bouton type=submit value=Ok name=ident></td></tr></table></td></tr></form>";
	}
return $echo;
}

function return_link_menu($head,$footer,$before) //to do: creer colonne dans bdd avec la valeur de do, puis mettra la valeur global ici et comparer pour pouvoir changer les images si page en cours
{
global $do,$skinrep;
$liste = listmenu();
if($liste==""){return 1;}
$retour="";
//$idpic=get_idmenu();
//if($do=="")
//{
//	$idpic=5;
//}
foreach ($liste as $key => $value)
	{
		
		if($before)
		{
			//$retour.=$head."<a href=".$value["link_menu"]."><b>".lang($value["text_id"])."</b></a>".$footer;
			//if($value["text_id"]==intval($idpic))
			if((!(strpos($value["alldo"],"|".$do."|"  ) === false) AND $do<>"") or $do==$value["do"])
			{
				//$retour.=$head."<img src=\"$skinrep/men".$value["idmenu"].".jpg\" border=\"\" >".$footer;
				$retour.=$head."<a href=".$value["link_menu"]."><img src=\"$skinrep/men".$value["text_id"].".jpg\" title=\"".lang($value["text_id"])."\" border=\"\" ></a>".$footer;
			}else{
				$retour.=$head."<a href=".$value["link_menu"]."><img src=\"$skinrep/men".$value["text_id"]."_no.jpg\" title=\"".lang($value["text_id"])."\" border=\"\" ></a>".$footer;
			}
			
		}else{
			// $retour.="<a href=".$value["link_menu"].">".$head.lang($value["text_id"]).$footer."</a>";
		}
	}
return $retour;
}

function html_menu()
{   
global $internaute,$do,$skinrep;
		
$echo = return_link_menu("","",1);
    return $echo;
}

function html_anime()
{
global $skinrep;
$html="<script langage=javascript>\n
\n
//fonctions pour l'animation\n
i = new Array;\n
version = navigator.appVersion.substring(0,1);\n
if (version >= 3)\n
{\n
i0 = new Image;\n
i0.src = '$skinrep/top_gris1.jpg';\n
i[0] = i0.src;\n
i1 = new Image;\n
i1.src = '$skinrep/top_gris.jpg';\n
i[1] = i1.src;\n
}\n
\n
\n
a = 0;\n
function StartAnim()\n
{\n
if (version >= 3)\n
{\n
document.write('<IMG SRC=\"$skinrep/top_gris1.jpg\" BORDER=0 NAME=defil>');\n
defilimg()\n
}\n
else\n
{\n
document.write('<IMG SRC=\"$skinrep/top_gris.jpg\" BORDER=0 >')\n
}\n
}\n
\n
function defilimg()\n
{\n
if (a == 2)\n
{\n
a = 0;\n
}\n
if (version >= 3) {\n
document.defil.src = i[a];\n
tempo = setTimeout(\"defilimg()\",720);\n
a++;\n
} \n
}\n
\n
</script>";

return ""; //$html;
}

?>