<?php
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
  //
  // Fichier contenant les definitions de constantes
  //
  //define ("NOUVADDR","http://fr.finance.yahoo.com/d/quotes.csv?s=");
  //define ("NOUVADDRFIN","&f=snl1d1t1c1ohgv&e=.csv");
//http://uk.finance.yahoo.com/d/quotes.csv?s=@^FCHI&f=sl1d1t1c1ohgv&e=.csv
  define ("NOUVADDR","http://fr.old.finance.yahoo.com/d/quotes.csv?s=");
  define ("NOUVADDRFIN","&f=snl1d1t1c1ohgv&e=.csv");
  define ("ADDRDEB","http://www.bourse-de-paris.fr/servlet/graph.intraDay3?sicovam=");
  define ("ADDRFIN","");

  define ("ADDRNT","http://www.nettrader.fr");
  define ("ADDRNTTRANSAC","http://nettrader2.apinc.org/transac/");
  define ("EMAILADMIN","nfortin@nettrader.fr");
  define ("INCONC","0");
  define ("SECURE","0"); // d�calage de 15 minutes avant execution, contre la triche 0: triche possible, 1: triche impossible
  define ("DEBCONC","0");
  define ("ACTIVATION_GROUPE","1");
  define ("EQUIPE_FINJOURVIRER","8");
  define ("MAX_MESSAGE_ENVOYE_NON_LU","10");
  define ("IDCOMPTEDEMO",781);
  define ("ADSENSEKEYWORD","trading");
  define ("MAXDOWN",190);
  define ("MAX_MESSAGE_TEMPS","24"); //pour la limite de message envoy�, nombre d'heure o� un message est comptabilis� comme nouveau
  define ("LIGNEPARTENAIRES","<a href=\"http://www.finaperf.com/\" target=\"_blank\">Annuaire finance et bourse</a> | 
  <a href=\"http://www.xiti.com/xiti.asp?s=309232\" title=\"WebAnalytics\">
<script type=\"text/javascript\">
<!--
Xt_param = 's=309232&p=".$_GET['do']."';
try {Xt_r = top.document.referrer;}
catch(e) {Xt_r = document.referrer; }
Xt_h = new Date();
Xt_i = '<img width=\"39\" height=\"25\" border=\"0\" alt=\"\" ';
Xt_i += 'src=\"http://logv16.xiti.com/hit.xiti?'+Xt_param;
Xt_i += '&hl='+Xt_h.getHours()+'x'+Xt_h.getMinutes()+'x'+Xt_h.getSeconds();
if(parseFloat(navigator.appVersion)>=4)
{Xt_s=screen;Xt_i+='&r='+Xt_s.width+'x'+Xt_s.height+'x'+Xt_s.pixelDepth+'x'+Xt_s.colorDepth;}
document.write(Xt_i+'&ref='+Xt_r.replace(/[<>\"]/g, '').replace(/&/g, '$')+'\" title=\"Internet Audience\">');
//-->
</script>
<noscript>
<img width=\"39\" height=\"25\" src=\"http://logv16.xiti.com/hit.xiti?s=309232&p=".$_GET['do']."\" alt=\"WebAnalytics\" />
</noscript></a>");
  define ("TITRE_JEU","Jeu de gestion de portefeuille - NetTrader");
  define ("FINCONC","9999999999");
  define ("DATEFINSTATS","00000000000");
  define ("NB_SUJETS_PAR_PAGE","10");
  define ("NB_MESS_PAR_PAGE","10");
  define ("INTERVAL_POST_FORUM","5");
  define ("ID_COMPTE_ANONYME","1");
  define ("SECURETIMEDELAY",60*25);
  define ("NB_JOUR_GARDER_STAT_JOUEUR",30);
  define ("SEC_JOUEUR_CONSIDERER_FORUM_TOUTLU",3600*12); //desactiver les notification message non vu

  if(INCONC)
  {
  	define ("CAPDEB","100000");
  	define ("SECURE","1");
  }else{
  	define ("CAPDEB","10000");
  }
//setlocale (LC_TIME,fr_FR);
setlocale (LC_TIME, "fr");
 define ("ADDREURONEXT","http://www.euronext.com/search/download/trapridownloadpopup.jcsv?pricesearchresults=actif&filter=1&lan=FR&mep=8629&belongsToList=eligibility_SRD&resultsTitle=Paris%20-%20SRD%20eligible&cha=1800&format=txt&formatDecimal=.&formatDate=dd/MM/yy");
 define ("CONSIDERER_OUTDATED_SICAV",4); //considerer email perim� en jour
?>
