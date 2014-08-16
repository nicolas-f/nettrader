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
from pynt2stats import *
import re
SENDMAIL = "/usr/sbin/sendmail" # sendmail location
C_SENDMAILFORREAL=1
C_CHECKTIME=10
TIME_BETWEENMAIL=1
FROMMAILWEEK="noreply@nettrader.fr"

def convtomysl(inn):
    return re.escape(inn)

def sec(var):
    return var.replace(":","")

def f_sendmail(emailfrom,pseudofrom,emailto,pseudoto,titre,corps):
    if C_SENDMAILFORREAL:
        
        p = os.popen("%s -t" % SENDMAIL, "w")
	p.write("From: "+pseudofrom+" <"+emailfrom+">\n")
        p.write("Reply-To: "+pseudofrom+" <"+emailfrom+">\n")
        p.write("To: "+emailto+"\n")
        p.write("Subject: "+titre+"\n")
	p.write("Content-Type: text/plain; charset=UTF-8; format=flowed\n")
        p.write("Content-Transfer-Encoding: 8bit\n")	
        p.write("\n")
        p.write(corps)
        p.close()
	return True
    else:
        print("From: "+pseudofrom+" <"+emailfrom+">\n") 
        print("Reply-To: "+pseudofrom+" <"+emailfrom+">\n")
        print("To: "+pseudoto+" <"+emailto+">\n")
        print("Subject: "+titre+"\n")
        print("Content-Type: multipart/alternative;\n")
        print("Date: "+time.strftime("%a, %d %b %Y %H:%M:%S -0600 (CEST)", time.gmtime())+"\n")
        print("\n")
        print(corps)
        return 0;


col={"idmail" : 0 , "from_mail" : 1, "from_pseudo" : 2, "to_mail" : 3 , "to_pseudo" : 4, "titre" : 5, "corps" : 6, "etat" :7}

db = MySQLdb.connect(host=C_HOST, user=C_USER, passwd=C_PWD, db=C_DBNAME)
while 1:
    cursor = db.cursor()
    db.query("UPDATE mail_tosend SET etat='traitement' WHERE etat='attente' and dateenvoi<UNIX_TIMESTAMP()")
    db.commit()
    cursor.execute("SELECT `idmail` , `from_mail` , `from_pseudo` , `to_mail` , `to_pseudo` , `titre` , `corps` , `etat`  FROM mail_tosend where etat='traitement'") 
    numrows = int(cursor.rowcount)
    mailsendwithouterror=0
    started=0
    for x in range(0,numrows):
        started=1
        row = cursor.fetchone()
        #On traite l'envoie de l'email
	if not f_sendmail(sec(row[col["from_mail"]]),sec(row[col["from_pseudo"]]),sec(row[col["to_mail"]]),sec(row[col["to_pseudo"]]),row[col["titre"]],row[col["corps"]]):
            db.query("UPDATE mail_tosend SET etat='erreur' WHERE idmail='"+str(row[col["idmail"]])+"'")
        else:       
            db.query("UPDATE mail_tosend SET etat='traite' WHERE idmail='"+str(row[col["idmail"]])+"'")
            mailsendwithouterror+=1
        db.commit()
        time.sleep(TIME_BETWEENMAIL)
    cursor.close()
    if started:
        db.query("DELETE FROM mail_tosend WHERE etat='traite'")
        db.commit()
        messInfo=str(mailsendwithouterror)+" emails envoye avec succes, "+str(numrows-mailsendwithouterror)+" mails en erreur."
	f_sendmail("statsweek@nettrader.fr","admin","statsweek@nettrader.fr","admin","mails envoy�s",messInfo)
    #check stats semaine
    if time.localtime()[3]==18 and time.strftime("%w")=='5':
        cursor = db.cursor()
        #on verifie si on a envoyé un mail depuis + de 6 jours
        cursor.execute("SELECT * FROM conf where libel='lastenvoiemailhebdo'")
        row = cursor.fetchone()
        desc=cursor.description
        ligne=getRowDict(row,desc)
        lastsend=getValue(ligne,"valeur")
        ilastsend=int(lastsend)
        if lastsend!="" and ilastsend<time.time()-(6*24*3600):
            print "Debut creation des enregistrements"
            db.query("UPDATE conf SET valeur=UNIX_TIMESTAMP() WHERE libel='lastenvoiemailhebdo'")
            db.commit()
            statsengine=Stats_Weekly(db)
            cursor.execute("SELECT idcompte,pseudonyme,email FROM compte WHERE mailweekly='1' ORDER BY idcompte")
            desc=cursor.description
            row = cursor.fetchone()
            nbmailprepare=0
            nbmailprepareerr=0
            debcreation=time.time()
            tmps=time.localtime()
            tmps=(tmps[0], tmps[1], tmps[2]+3, 1, 0, 0, tmps[6], tmps[7], tmps[8])
            dateunix_sendmail=time.mktime(tmps)
            while row!=None:
                ligne=getRowDict(row,desc)
                try:
                    bodymail=statsengine.createstatsforuser(getValue(ligne,"idcompte"))
                    sql="INSERT INTO `mail_tosend` ( `idmail` , `dateenvoi` , `from_mail` , `from_pseudo` , `to_mail` , `to_pseudo` , `titre` , `corps` , `etat` ) \
                    VALUES ( '', '%s' , '%s', 'Administrateur', '%s', '%s\
                             ', '%s', '%s', 'attente')"\
                             % (str(dateunix_sendmail),FROMMAILWEEK,getValue(ligne,"email"),convtomysl(getValue(ligne,"pseudonyme")),convtomysl(getValue(ligne,"pseudonyme"))+", vos statistiques de la semaine",convtomysl(bodymail.encode("latin1")))
                    db.query(sql)
                    nbmailprepare+=1
                except:
                    nbmailprepareerr+=1
                row = cursor.fetchone()
            db.commit()
            print str(nbmailprepare)+" mail prepare en % secondes et %s en erreur" % (time.time()-debcreation,str(nbmailprepareerr))
            del statsengine
        cursor.close()
    #check stats jour
    if time.localtime()[3]==18 and int(time.strftime("%w"))>0 and int(time.strftime("%w"))<6:
        cursor = db.cursor()
        #on verifie si on a envoyé un mail depuis + de 12 heures
        cursor.execute("SELECT * FROM conf where libel='lastenvoiemailquotidien'")
        row = cursor.fetchone()
        desc=cursor.description
        ligne=getRowDict(row,desc)
        lastsend=getValue(ligne,"valeur")
        ilastsend=int(lastsend)
        if lastsend!="" and ilastsend<time.time()-(12*3600):
            print "Debut creation des enregistrements"
            db.query("UPDATE conf SET valeur=UNIX_TIMESTAMP() WHERE libel='lastenvoiemailquotidien'")
            db.commit()
            statsengine=Stats_Daily(db)
            cursor.execute("SELECT idcompte,pseudonyme,email FROM compte WHERE maildaily='1' ORDER BY idcompte")
            desc=cursor.description
            row = cursor.fetchone()
            nbmailprepare=0
            nbmailprepareerr=0
            debcreation=time.time()
            while row!=None:
                ligne=getRowDict(row,desc)
                try:
                    bodymail=statsengine.createstatsforuser(getValue(ligne,"idcompte"))
                    sql="INSERT INTO `mail_tosend` ( `idmail` , `dateenvoi` , `from_mail` , `from_pseudo` , `to_mail` , `to_pseudo` , `titre` , `corps` , `etat` ) \
                    VALUES ( '', UNIX_TIMESTAMP( ) , '%s', 'Administrateur', '%s', '%s\
                             ', '%s', '%s', 'attente')"\
                             % (FROMMAILWEEK,getValue(ligne,"email"),convtomysl(getValue(ligne,"pseudonyme")),convtomysl(getValue(ligne,"pseudonyme"))+", vos statistiques de la journee",convtomysl(bodymail.encode("latin1")))
                    db.query(sql)
                    nbmailprepare+=1
                except:
                    nbmailprepareerr+=1
                row = cursor.fetchone()
            db.commit()
            print str(nbmailprepare)+" mail prepare en % secondes et %s en erreur" % (time.time()-debcreation,str(nbmailprepareerr))
            del statsengine
        cursor.close()
    time.sleep(C_CHECKTIME)        
db.close()
