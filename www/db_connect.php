<?
/**
* NetTrader 2
*
* @package NetTrader
* @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
* @author Nicolas Fortin <nfortin@nettrader.fr>
*/
/*
Fonction Liste:
ligne 239: joueur_liste_sicav([idCompte]) retourne toutes les sicav qui doivent �tre mis � jour [pour un joueur]


*/
function sec($input="")
{
if (get_magic_quotes_gpc())
{
	$output = htmlentities($input);
}else{
	if(is_array($input))
	{
		foreach ($input as $key => $champ)
		{
			$output[$key]=sec($champ);//ouah de la r�cursivit�e !
		}
	}else{
		$output=htmlentities(addslashes($input));
	}
}
return $output;
}
function echoadmin($message)
{
global $internaute;
if($internaute->idcompte == 1 || $_SERVER['REMOTE_ADDR']=="127.0.0.1")
	{
	echo $message;
	}
return 1;
}

function getmicrotime()
{
   list($usec, $sec) = explode(" ",microtime());
   return ((float)$usec + (float)$sec);
}
  
function cookievalide($idSession) //retourne 0 si non valide, l'idcompte si valide
{
	//echo "cookievars:(".$_COOKIE["Transac"].")";
	$chainecookie = &$_COOKIE["nettrader2session"];
	if(isset($_COOKIE["nettrader2session"]))
	{
		$exploded_ligne = explode("-", $chainecookie );
		$idcompte=$exploded_ligne[0];
		$chainemd5 = $exploded_ligne[1];
		$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
		$tempinternaute = ChercheInternaute ($idcompte,$connexion);
		list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
		$date3 = mktime(0, 0, 0, 0, 0, $yr);
		if(md5($tempinternaute->idcompte.$date3.$tempinternaute->passe.$tempinternaute->cookiesess)==$chainemd5)
		{
                        //echo "[COOKIE VALIDE]";
			$maintenant = date ("U");
			$tempsLimite = $maintenant + (3600 * 24); 
	
			$insSession = "INSERT INTO Session (idSession, idcompte"
						 . ",tempsLimite,tempsconnect) VALUES ('$idSession', "
						 . "'$idcompte',"
						 . "'$tempsLimite','$maintenant')";       
                        $resultat = ExecRequete ($insSession, $connexion);
                        forum_majtoutvuforum($idcompte);
                        $requete  = "UPDATE compte SET dateactivite = '$maintenant' WHERE idcompte='$idcompte'";
                        $resultat = ExecRequete ($requete, $connexion);
			session_register ("$idcompte");
			return $idcompte;
		}else{
			//echo "[COOKIE INVALIDE]";
			return 0;
		}
	}else{
		return 0;
	}
}

if (!isset ($FichierConnexion))
{
 $FichierConnexion = 1;

 // Fonction Connexion: connexion � MySQL

	function Connexion ($pNom, $pMotPasse, $pBase, $pServeur)
	{
	static $connectbdd;
	// Connexion au serveur 
	if(!$connectbdd)
	{
	  $connexion = mysql_connect ($pServeur, $pNom, $pMotPasse);
	  if (!$connexion) 
	  {
	    echo "D�sol�, connexion au serveur impossible\n";
	    exit;
	  }
	   if (!mysql_select_db ($pBase, $connexion)) 
	  {
	    echo "D�sol�, acc�s � la base impossible\n";
	    echoadmin( "<B>Message de MySQL :</B> " . mysql_error($connexion));
	    exit;
	  }
	  $connectbdd=$connexion;
	}else{
	  $connexion=$connectbdd;
	}
  // On renvoie la variable de connexion
  return $connexion;
 } // Fin de la fonction
} // Fin du test sur $FichierConnexion

if (!isset ($FichierExecRequete))
{
 $FichierExecRequete = 1;

 // Ex�cution d'une requ�te avec MySQL

 function ExecRequete ($requete, $connexion)
 {
  global $nbreqexecuted,$tempssql;
  $nbreqexecuted++;
  //echoadmin("  [$requete]  ");
  //$nbreqexecuted.="|".$requete."|";
  $tempdeb=getmicrotime();
  $resultat = mysql_query ($requete, $connexion);
	$tempssql=$tempssql+round((getmicrotime()-$tempdeb),2);
  if ($resultat)
   return $resultat;
  else 
  {  
  	global $internaute,$do;
    echoadmin("<B>Erreur dans l'ex�cution de la requ�te '$requete'.</B><BR>");
    echoadmin("<B>Message de MySQL :</B> ".mysql_error($connexion));

	$corps="Joueur: $internaute->pseudonyme \n
	 <B>Message de MySQL :</B> ".mysql_error($connexion)."
	 /n Erreur dans l'ex�cution de la requ�te '$requete' \n
	 do=$do";
        envoimail(EMAILADMIN,"NetTrader, Erreur MySql",$corps);
	 //echo "<B>Erreur dans l'ex�cution de la requ�te '$requete'.</B><BR>";
         //echo "<B>Message de MySQL :</B> ".mysql_error($connexion);
	//to do: envoyer un mail � moi si cette erreur ce produit
	echo "Une erreur c'est produite, l'auteur r�glera ce probl�me dans les plus bref d�lais.";
    exit;
  }  
 } // Fin de la fonction ExecRequete

 // Recherche de la ligne suivante

 function LigneSuivante ($resultat)
 {
   return  mysql_fetch_object ($resultat);
 } // Fin de la fonction LigneSuivante

} // Fin du test 

 function ChercheInternaute ($idcompte=0, $connexion,$mail="")
  {
  $mail=sec($mail);
    if($mail=="")
	{
			$requete = 
       		"SELECT * FROM compte,skin,niveau WHERE idcompte = '$idcompte' AND compte.skin = skin.idskin AND compte.idniveau = niveau.idniveau" ;
	}else{
			$requete = 
       		"SELECT * FROM compte,skin,niveau WHERE email = '$mail' AND compte.skin = skin.idskin AND compte.idniveau = niveau.idniveau" ;
	}
    $resultat = ExecRequete ($requete, $connexion);
    return LigneSuivante ($resultat);
  }

function nbessai($idcompte)
{
$depuis=date("U")-5*60;
$query = "SELECT COUNT(idcompte) as nbessai
          FROM `tabforcing` where idcompte='$idcompte' and dateforcing>'$depuis'";
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
	$run_query =  ExecRequete ($query, $connexion);
$resultat=LigneSuivante($run_query);
return $resultat->nbessai;
}

function ChercheSession ($idSession, $connexion) 
  {
    $idSession=sec($idSession);	
	$requete =  "SELECT * FROM Session,compte,skin,niveau WHERE idSession = '$idSession' AND Session.idcompte = compte.idcompte AND compte.skin = skin.idskin AND compte.idniveau=niveau.idniveau ORDER BY tempsLimite DESC" ;
    $resultat = ExecRequete ($requete, $connexion);
    return LigneSuivante ($resultat);
  }

  // V�rification qu'une session est valide

  function SessionValide ($connexion, $session)
  {
    // V�rifions que le temps limite n'est pas d�pass�

    $maintenant = date ("U");
    if ($session->tempsLimite < $maintenant)
    {
      // Destruction de la session
      session_destroy();
   setcookie("nettrader2session", "", time()+3600*24*30, "/");
	  $session->idSession=sec($session->idSession);
      $requete  = "DELETE FROM Session "
                . "WHERE idSession='$session->idSession' or tempsLimite<'$maintenant'";
			
      $resultat = ExecRequete ($requete, $connexion);
      return FALSE;
    }else{ // C'est bon !
		if ($session->tempsconnect < $maintenant-5*60)
    		{
			$requete  = "UPDATE Session SET tempsconnect = '$maintenant' WHERE idcompte='$session->idcompte'";
      			$resultat = ExecRequete ($requete, $connexion);
                        forum_majtoutvuforum($session->idcompte);
			$requete  = "UPDATE compte SET dateactivite = '$maintenant' WHERE idcompte='$session->idcompte'";
			$resultat = ExecRequete ($requete, $connexion);
		}
       return TRUE;
  	}
  }

  // Tentative de cr�ation d'une session

  function CreerSession ($connexion, $email, $motDePasse, $idSession, $souvenir)
  {
  	global $internaute;
	$internaute = ChercheInternaute (0, $connexion,$email);
	
    // L'internaute existe-t-il ?
    if (is_object($internaute))
    {
      //V�rification du nombre d'essai restant
	  if(nbessai($internaute->idcompte)>=25)
	  {
	  	include_once ("lang/lang_fr.php");
		$internaute="";
	  	return lang(88);	  
	  }	  
	  // V�rification du mot de passe
      if ($internaute->passe == md5($motDePasse))
      {
        // On ins�re dans la table Session, pour 24 heures 
        $maintenant = date ("U");
        $tempsLimite = $maintenant + (3600 * 24); 

        $insSession = "INSERT INTO Session (idSession, idcompte"
                     . ",tempsLimite,tempsconnect) VALUES ('$idSession', "
                     . "'$internaute->idcompte',"
                     . "'$tempsLimite','$maintenant')";       
        $resultat = ExecRequete ($insSession, $connexion);
        forum_majtoutvuforum($internaute->idcompte);
        $requete  = "UPDATE compte SET dateactivite = '$maintenant' WHERE idcompte='$internaute->idcompte'";
                        $resultat = ExecRequete ($requete, $connexion);
		list($hour, $min, $sec, $day, $mon, $yr) = explode(" ",date("H i s d m y"));
		$date3 = mktime(0, 0, 0, 0, 0, $yr); // HIER
		if($souvenir==1)
		{
			setcookie("nettrader2session", "$internaute->idcompte-".md5($internaute->idcompte.$date3.$internaute->passe.$internaute->cookiesess), time()+3600*24*30, "/");
			//echo "[".$_SERVER['HTTP_HOST']."]";
		}
		// On enregistre la variable emailInternaute 
        session_register ("$internaute->idcompte");
        return "TRUE";
      }
	  $maintenant = date ("U");
	  $insSession = "INSERT INTO `tabforcing` ( `idcompte` , `dateforcing` )
		VALUES ('$internaute->idcompte', '$maintenant');";       
      $resultat = ExecRequete ($insSession, $connexion); 
	  $internaute="";
	  include_once ("lang/lang_fr.php"); 
      return "<B>".lang(26)."<P></B>\n";
    }      
    else
   {
	 $internaute="";
	 include_once ("lang/lang_fr.php");
     return "<B>".lang(27)."</B><P>\n";
   }
  }

  // Fonction de contr�le d'acc�s

 
  
  function ControleAcces (&$email,&$motDePasse,&$emailInternaute, $idSession, $souvenir)
  {
  	global $internaute;
    $emailInternaute=sec($emailInternaute);
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
    $sessionCourante = ChercheSession ($idSession, $connexion);
	if (!(is_object($sessionCourante)))
    {
		cookievalide($idSession);
		$sessionCourante = ChercheSession ($idSession, $connexion);
	}
    // Cas 1: V�rification de la session courante
    if (is_object($sessionCourante))
    {
		  // La session existe. Est-elle valide ?
		if (SessionValide ($connexion, $sessionCourante))
		{
			// Gardons l'email dans la variable associ�e � la session
			$internaute = $sessionCourante;
			return;
		}else{
			return "<B>Votre session n'est pas (ou plus) valide.<P></B>\n";
		}
    }
 
    // Cas 2.a: pas de session mais email et mot de passe
 
    if (isset($email))
    {
		$email=sec($email);
      // Une paire email/mot de passe existe. Est-elle correcte ?
	$message=CreerSession ($connexion, $email, $motDePasse, $idSession, $souvenir);
      if ($message=="TRUE")
      {
        // On conserve l'email dans la variable de la session
        $emailInternaute = $email;
        return "Bienvenue ".$internaute->pseudonyme."<br><br>";
      }
      else 
        return $message;
    }

    // Cas 2.b : il faut afficher le formulaire, en proposant
    // l'email comme valeur par d�faut.   
 }


function deconnection()
{
	global $internaute;
	effacvieuxordres();
	$connexion = Connexion (NOM, PASSE, BASE, SERVEUR);
    $requete  = "DELETE FROM Session WHERE idcompte='$internaute->idcompte' OR tempsLimite<UNIX_TIMESTAMP()";
	setcookie("nettrader2session", "", time()-3600, "/");
	$resultat = ExecRequete ($requete, $connexion);
	$tag=md5(getmicrotime());
    $requete = "UPDATE compte SET cookiesess='$tag' WHERE idcompte='$internaute->idcompte'";
	$resultat = ExecRequete ($requete, $connexion);
	session_destroy();
	$internaute="";
    return lang(37);
}

function ChercheComptePseudo ($pseudo, $connexion)
{
    $requete =  "SELECT * FROM compte WHERE pseudonyme = '$pseudo'" ;
    $resultat = "";
    $resultat = ExecRequete ($requete, $connexion);
    return LigneSuivante ($resultat);
}

?>