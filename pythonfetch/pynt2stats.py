# -*- coding: utf-8 -*-
#
# NetTrader 2
#
# @package NetTrader
# @license http://www.gnu.org/licenses/agpl.html AGPL Version 3
# @author Nicolas Fortin <nfortin@nettrader.fr>
import os
import MySQLdb
import time
from pyconst import *
import md5
SIZETAB=8
CAPDEB="10000"
URLINDEX="http://www.nettrader.fr/"

def getRowDict(rowData,desc):
    dicodata={}
    if(rowData!=None):
        for i in range(0,len(rowData)):
            dicodata[desc[i][0]]=rowData[i]
    return dicodata
def getValue(tab,col):
    if col in tab:
        return str(tab[col])
    else:
        return ""
def tgetValue(tab,col):
    if col in tab:
        if len(str(tab[col]))<SIZETAB:
            return str(tab[col])+(" "*(SIZETAB-len(str(tab[col]))))
        else:
            return str(tab[col])[:SIZETAB*2-1]
    else:
        return ""

def setValOk(val):
    if val=="":
        return 0
    else:
        return val

class Stats_Daily:
    def __init__(self,db):
        self.db=db
        cursor = self.db.cursor()
        #Creation du classement journalier tout les joueurs
        cursor.execute("DROP TABLE IF EXISTS `dailystatclassement`")
        cursor.execute("CREATE TABLE `dailystatclassement` \
        SELECT pseudonyme, round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + cashback, 2 ) AS capital, round( (\
        (\
        COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + compte.cashback - COALESCE( scores.capitalscores, "+CAPDEB+" ) ) / COALESCE( scores.capitalscores, "+CAPDEB+" ) ) * 100, 2\
        ) AS prog,compte.idcompte as idcompte\
        FROM compte\
        LEFT JOIN portef\
        USING ( idcompte )\
        LEFT  JOIN scores\
        ON (compte.idcompte=scores.idcompte AND scores.datescore = FROM_UNIXTIME( UNIX_TIMESTAMP( ), '%Y-%m-%d' ))\
        LEFT JOIN cacval ON cacval.codesico = portef.codesico\
        WHERE authlevel = '1' and dateactivite>=UNIX_TIMESTAMP()-2592000 and cashback<>'"+CAPDEB+"'\
        GROUP BY compte.idcompte\
        ")
        db.commit()
        cursor.execute("SELECT pseudonyme FROM dailystatclassement WHERE 1 ORDER BY PROG DESC LIMIT 1;")
        progj=getRowDict(cursor.fetchone(),cursor.description)
        self.todaybesttrader=getValue(progj,"pseudonyme")
    """Generation de stats journaliere"""
    def createstatsforuser(self,idcompte):
        db=self.db
        cursor = db.cursor()
        cursor.execute("SELECT * FROM compte where idcompte='"+str(idcompte)+"'") 
        numrows = int(cursor.rowcount)
        desc=cursor.description
        if numrows>0:
            row = cursor.fetchone()
            #joueur
            joueur=getRowDict(row,desc)
            #Calcul de la date d'aujourd'hui
            tmps=time.localtime()
            today="%i/%i/%i :" % (tmps[2],tmps[1],tmps[0])
            #Calcul de la progression du joueur en euros aujourd'hui
            cursor.execute("SELECT (capital-capitalscores) as scorejournee FROM `scores` s,`statsclassement` c   \
            WHERE s.idcompte=c.idcompte and s.datescore=FROM_UNIXTIME( UNIX_TIMESTAMP( ), '%Y-%m-%d' ) \
            and s.idcompte='"+str(idcompte)+"'")
            progj=getRowDict(cursor.fetchone(),cursor.description)
            todayprog=getValue(progj,"scorejournee")
            todayprog=setValOk(todayprog)
            #todayhisto
            cursor.execute("SELECT FROM_UNIXTIME( temps ) ladate, nom, sens , nbr, taxe, ((valeurunique*nbr)+taxe) totalttc, profit   FROM \
            `historique`,`cacval` WHERE `idcompte`='"+str(idcompte)+"' and \
            `temps`>UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) , '%Y-%m-%d 09:00:00' ) ) and cacval.codesico=historique.codesico ORDER BY temps ASC")
            desc=cursor.description
            row = cursor.fetchone()
            todayhisto="Date"+chr(9)+chr(9)+chr(9)+"Nom"+chr(9)+chr(9)+"Sens"+chr(9)+"Nombre"+chr(9)+"Taxe"+chr(9)+chr(9)+"Total TTC"+chr(9)+"Profit\n"
            todayincome=0
            while row!=None:
                ligne=getRowDict(row,desc)
                todayhisto+=getValue(ligne,"ladate")+chr(9)+tgetValue(ligne,"nom")+chr(9)+getValue(ligne,"sens")+chr(9)+getValue(ligne,"nbr")+chr(9)\
                +getValue(ligne,"taxe")+" e"+chr(9)+getValue(ligne,"totalttc")+" e"+chr(9)+getValue(ligne,"profit")+" e\n\r"
                todayincome+=ligne["profit"]
                row = cursor.fetchone()
            #monthrank
            cursor.execute("SET @n=0")
            cursor.execute("SELECT class FROM (SELECT @n:=@n+1 as class ,idcompte FROM statsclassement WHERE 1 ORDER BY `prog` DESC)\
            classreq WHERE idcompte='"+str(idcompte)+"';")
            progj=getRowDict(cursor.fetchone(),cursor.description)
            monthrank=getValue(progj,"class")
            monthrank=setValOk(monthrank)
            #todayrank
            cursor.execute("SET @n=0")
            cursor.execute("SELECT class FROM (SELECT @n:=@n+1 as class ,idcompte FROM dailystatclassement WHERE 1 ORDER BY `prog` DESC)\
            classreq WHERE idcompte='"+str(idcompte)+"';")
            progj=getRowDict(cursor.fetchone(),cursor.description)
            todayrank=getValue(progj,"class")
            todayrank=setValOk(todayrank)
            #todaybestplayer
            todaybestplayer=self.todaybesttrader
            #newmsg
            cursor.execute("SELECT COUNT(*) as nbmess FROM `messages` m   \
            WHERE m.idcompte='"+str(idcompte)+"' and datemess>UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) , '%Y-%m-%d 00:00:01' ) )")
            progj=getRowDict(cursor.fetchone(),cursor.description)
            newmsg=getValue(progj,"nbmess")
            #footer
            footer=u"Vous pouvez vous connecter au jeu a l'adresse suivante :\n%s\n\n" % (URLINDEX+"?do=frmlogin")
            #Verification si le joueur a des actions perimes
            cursor.execute("SELECT * FROM warn_old_sicav WHERE idcompte='"+str(idcompte)+"'")
            row = cursor.fetchone()
            desc=cursor.description
            if row!=None:
                footer+=u"\n Certaines des actions que vous avez en portefeuille ne sont plus mis a jour, pour vous en separer au prix d'achat et sans frais cliquez sur les liens suivant :\n"
                while row!=None:
                    ligne=getRowDict(row,desc)
                    footer+=getValue(ligne,"link")+"\n"
                    row = cursor.fetchone()
                    


            
            footer+=u"Cliquez ici pour desactiver les mails journalier :\n%s" % (URLINDEX+"?do=unabledailystats&idcompte="+str(idcompte)+"&checkstr="+md5.new(str(idcompte)+str(getValue(joueur,"dateinscr"))).hexdigest())
            body =\
u"Bonjour %s\n\
Voici les statistiques du %s :\n\n\
Ce jour votre capital a progresse de %s euros.\n\
\n\
Voici votre historique d'aujourd'hui :\n\
%s\n\
\n\
Au total vos operations de la journee vous ont rapportees %s euros !\n\
\n\
Vous etes classe %s eme(er) du classement mensuel\n\
\n\
Vous etes classe %s eme(er) du classement d'aujourd'hui\n\
\n\
Le meilleur trader d'aujourd'hui est %s\n\
\n\
\n\
Vous avez %s nouveau(x) messages.\n\
\n\
%s\n\
\n\
" \
% (joueur["pseudonyme"].decode("latin1"),today,todayprog,todayhisto,todayincome,monthrank,todayrank,todaybestplayer.decode("latin1"),newmsg,footer) 
            return body

            
 
 
 
 
 
 


 
class Stats_Weekly:
    def __init__(self,db):
        self.db=db
        cursor = self.db.cursor()
        #Creation du classement journalier tout les joueurs
        cursor.execute("DROP TABLE IF EXISTS `weeklystatclassement`")
        cursor.execute("CREATE TABLE `weeklystatclassement` \
        SELECT pseudonyme, round( COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + cashback, 2 ) AS capital, round( (\
        (\
        COALESCE( SUM( cacval.valeur * portef.quant ) , 0 ) + compte.cashback - COALESCE( scores.capitalscores, "+CAPDEB+" ) ) / COALESCE( scores.capitalscores, "+CAPDEB+" ) ) * 100, 2\
        ) AS prog,compte.idcompte as idcompte\
        FROM compte\
        LEFT JOIN portef\
        USING ( idcompte )\
        LEFT  JOIN scores\
        ON (compte.idcompte=scores.idcompte AND scores.datescore = FROM_UNIXTIME( UNIX_TIMESTAMP( )-(4*24*3600), '%Y-%m-%d' ))\
        LEFT JOIN cacval ON cacval.codesico = portef.codesico\
        WHERE authlevel = '1' and dateactivite>=UNIX_TIMESTAMP()-2592000 and cashback<>'"+CAPDEB+"'\
        GROUP BY compte.idcompte\
        ")
        db.commit()
        cursor.execute("SELECT pseudonyme FROM weeklystatclassement WHERE 1 ORDER BY PROG DESC LIMIT 1;")
        progj=getRowDict(cursor.fetchone(),cursor.description)
        self.thisweekbesttrader=getValue(progj,"pseudonyme")
    """Generation de stats hebdo"""
    def createstatsforuser(self,idcompte):
        db=self.db
        cursor = db.cursor()
        cursor.execute("SELECT * FROM compte where idcompte='"+str(idcompte)+"'") 
        numrows = int(cursor.rowcount)
        desc=cursor.description
        if numrows>0:
            row = cursor.fetchone()
            #joueur
            joueur=getRowDict(row,desc)
            #Calcul de la date d'aujourd'hui
            tmps=time.localtime()
            today="%i/%i/%i" % (tmps[2],tmps[1],tmps[0])
            todayother="%i-%i-%i" % (tmps[2],tmps[1],tmps[0])
            #calcul de la date il y a une semaine
            tmps=time.localtime()
            tmps=(tmps[0], tmps[1], tmps[2]-4, tmps[3], tmps[4], tmps[5], tmps[6], tmps[7], tmps[8])
            tmps=time.localtime(time.mktime(tmps))
            sinceoneweek="%i/%i/%i " % (tmps[2],tmps[1],tmps[0])
            #Calcul de la progression du joueur en euros cette semaine
            cursor.execute("SELECT (capital-capitalscores) as scorejournee FROM `scores` s,`statsclassement` c   \
            WHERE s.idcompte=c.idcompte and s.datescore=FROM_UNIXTIME( UNIX_TIMESTAMP( )-(3*24*3600), '%Y-%m-%d' ) \
            and s.idcompte='"+str(idcompte)+"'")
            progj=getRowDict(cursor.fetchone(),cursor.description)
            todayprog=getValue(progj,"scorejournee")
            todayprog=setValOk(todayprog)
            #todayperf
            cursor.execute("SELECT SUM(profit) as totprofit   FROM \
            `historique`,`cacval` WHERE `idcompte`='"+str(idcompte)+"' and \
            `temps`>(UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) , '%Y-%m-%d 09:00:00' ) )-(4*24*3600)) and cacval.codesico=historique.codesico ORDER BY temps ASC")
            desc=cursor.description
            row = cursor.fetchone()
            ligne=getRowDict(row,desc)
            weekincome=getValue(row,"totprofit")
            weekincome=setValOk(weekincome)

            #monthrank
            cursor.execute("SET @n=0")
            cursor.execute("SELECT class FROM (SELECT @n:=@n+1 as class ,idcompte FROM statsclassement WHERE 1 ORDER BY `prog` DESC)\
            classreq WHERE idcompte='"+str(idcompte)+"';")
            progj=getRowDict(cursor.fetchone(),cursor.description)
            monthrank=getValue(progj,"class")
            monthrank=setValOk(monthrank)
            #weekrank
            cursor.execute("SET @n=0")
            cursor.execute("SELECT class FROM (SELECT @n:=@n+1 as class ,idcompte FROM weeklystatclassement WHERE 1 ORDER BY `prog` DESC)\
            classreq WHERE idcompte='"+str(idcompte)+"';")
            progj=getRowDict(cursor.fetchone(),cursor.description)
            weekrank=getValue(progj,"class")
            weekrank=setValOk(weekrank)
            #weekbestplayer
            weekbestplayer=self.thisweekbesttrader
            #histoscore
            cursor.execute("SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(datescore)-(24*3600), '%d-%m-%Y' ) as datescore,capitalscores FROM \
            `scores` WHERE `idcompte`='"+str(idcompte)+"' and \
            `datescore`>=FROM_UNIXTIME( UNIX_TIMESTAMP( )-(3*24*3600), '%Y-%m-%d' ) ORDER BY datescore ASC")
            desc=cursor.description
            row = cursor.fetchone()
            histoscore="Date"+chr(9)+chr(9)+"Capital\n"
            while row!=None:
                ligne=getRowDict(row,desc)
                thisday=getValue(ligne,"datescore")
                histoscore+=getValue(ligne,"datescore")+chr(9)+getValue(ligne,"capitalscores")+" \n"
                row = cursor.fetchone()
            cursor.execute("SELECT capital FROM statsclassement WHERE idcompte='"+str(idcompte)+"';")
            progj=getRowDict(cursor.fetchone(),cursor.description)
            histoscore+=todayother+chr(9)+getValue(progj,"capital")+" \n"
            #footer
            footer=u"Vous pouvez vous connecter au jeu a l'adresse suivante :\n%s\n\n" % (URLINDEX+"?do=frmlogin")
            footer+=u"Si vous avez oublie votre mot de passe rendez-vous a l'adresse suivante (email a saisir:%s) :\n%s\n\n" % (getValue(joueur,"email").decode("latin1"),URLINDEX+"?do=formrecuppass")
            #Verification si le joueur a des actions perimes
            cursor.execute("SELECT * FROM warn_old_sicav WHERE idcompte='"+str(idcompte)+"'")
            row = cursor.fetchone()
            desc=cursor.description
            if row!=None:
                footer+=u"\n Certaines des actions que vous avez en portefeuille ne sont plus mis a jour, pour vous en separer au prix d'achat et sans frais cliquez sur les liens suivant :\n"
                while row!=None:
                    ligne=getRowDict(row,desc)
                    footer+=getValue(ligne,"link")+"\n"
                    row = cursor.fetchone()
                    


            
            footer+=u"Si vous desirez ne plus recevoir vos statistiques,cliquez ici pour vous desinscrire des mails hebdomadaire :\n%s" % (URLINDEX+"?do=unableweeklystats&idcompte="+str(idcompte)+"&checkstr="+md5.new(str(idcompte)+str(getValue(joueur,"dateinscr"))).hexdigest())
            body =\
u"Bonjour %s\n\
Voici les statistiques du %s au %s :\n\n\
Cette semaine votre capital a progresse de %s euros.\n\
\n\
Au total vos operations de la semaine vous ont rapportees %s euros !\n\
\n\
Vous etes classe %s eme(er) du classement mensuel\n\
\n\
Vous etes classe %s eme(er) du classement de cette semaine\n\
\n\
Le meilleur trader cette semaine est %s\n\
\n\
Voici votre progression cette semaine :\n\
%s\
\n\
Si les informations dans ce mail ne s'affichent pas correctement c'est que vous vous n'etes pas connecte depuis plus d'un mois.\n\n\
%s\n\
\n\
" \
% (joueur["pseudonyme"].decode("latin1"),sinceoneweek,today,todayprog,weekincome,monthrank,weekrank,weekbestplayer.decode("latin1"),histoscore,footer) 
            return body
"""
db = MySQLdb.connect(host=C_HOST, user=C_USER, passwd=C_PWD, db=C_DBNAME)

statsengine=Stats_Daily(db)
print statsengine.createstatsforuser(1991)

"""

