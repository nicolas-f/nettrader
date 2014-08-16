Attribute VB_Name = "Module1"
'  NetTrader 2 Logiciel de simulation boursière
'  Copyright (C) 2008 Nicolas FORTIN — Tous droits réservés.
'
'  Ce programme est un logiciel libre ; vous pouvez le redistribuer ou le
'  modifier suivant les termes de la “GNU General Public License” telle que
'  publiée par la Free Software Foundation : soit la version 3 de cette
'  licence, soit (à votre gré) toute version ultérieure.
'
'  Ce programme est distribué dans l’espoir qu’il vous sera utile, mais SANS
'  AUCUNE GARANTIE : sans même la garantie implicite de COMMERCIALISABILITÉ
'  ni d’ADÉQUATION À UN OBJECTIF PARTICULIER. Consultez la Licence Générale
'  Publique GNU pour plus de détails.
'
'  Vous devriez avoir reçu une copie de la Licence Générale Publique GNU avec
'  ce programme ; si ce n’est pas le cas, consultez :
'  <http://www.gnu.org/licenses/>.

Option Explicit
'FONCTIONS
Private Declare Function ShellExecute Lib "shell32.dll" Alias "ShellExecuteA" (ByVal hWnd As Long, ByVal lpOperation As String, ByVal lpFile As String, ByVal lpParameters As String, ByVal lpDirectory As String, ByVal nShowCmd As Long) As Long
Private Declare Function GetPrivateProfileString Lib "kernel32" Alias "GetPrivateProfileStringA" (ByVal lpApplicationName As String, ByVal lpKeyName As Any, ByVal lpDefault As String, ByVal lpReturnedString As String, ByVal nSize As Long, ByVal lpFileName As String) As Long
Private Declare Function WritePrivateProfileString Lib "kernel32" Alias "WritePrivateProfileStringA" (ByVal lpApplicationName As String, ByVal lpKeyName As Any, ByVal lpString As Any, ByVal lpFileName As String) As Long
Private Declare Function InternetOpen Lib "wininet.dll" Alias "InternetOpenA" (ByVal sAgent As String, ByVal lAccessType As Long, ByVal sProxyName As String, ByVal sProxyBypass As String, ByVal lFlags As Long) As Long
Private Declare Function InternetOpenUrl Lib "wininet.dll" Alias "InternetOpenUrlA" (ByVal hOpen As Long, ByVal sUrl As String, ByVal sHeaders As String, ByVal lLength As Long, ByVal lFlags As Long, ByVal lContext As Long) As Long
Private Declare Function InternetReadFile Lib "wininet.dll" (ByVal hFile As Long, ByVal sBuffer As String, ByVal lNumBytesToRead As Long, lNumberOfBytesRead As Long) As Integer
Private Declare Function InternetCloseHandle Lib "wininet.dll" (ByVal hInet As Long) As Integer
Private Declare Function GetWindowLong Lib "user32" Alias "GetWindowLongA" (ByVal hWnd As Long, ByVal nIndex As Long) As Long
Private Declare Function SetWindowLong Lib "user32" Alias "SetWindowLongA" (ByVal hWnd As Long, ByVal nIndex As Long, ByVal dwNewLong As Long) As Long
Private Declare Function CallWindowProc Lib "user32" Alias "CallWindowProcA" (ByVal lpPrevWndFunc As Long, ByVal hWnd As Long, ByVal Msg As Long, ByVal wParam As Long, ByVal lParam As Long) As Long
Private Declare Sub MDFile Lib "aamd532.dll" (ByVal f As String, ByVal r As String)
Private Declare Sub MDStringFix Lib "aamd532.dll" (ByVal f As String, ByVal t As Long, ByVal r As String)
Public Declare Function InitCommonControls Lib "comctl32.dll" () As Long

Private Declare Sub CopyMemory Lib "kernel32" Alias "RtlMoveMemory" (Destination As Any, Source As Any, ByVal Length As Long)

'CONSTANTES
Public Const INTERNET_OPEN_TYPE_PRECONFIG = 0
Public Const INTERNET_OPEN_TYPE_DIRECT = 1
Public Const INTERNET_OPEN_TYPE_PROXY = 3
Public Const DEBUG_MODE = True
Public Const scUserAgent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows 98; NT2)"
Public Const INTERNET_FLAG_RELOAD = &H80000000
Public Const GameUrl = "http://www.nettrader.fr"
'Public Const GameUrl = "http://localhost/nettrader2"
Public Const DossierCours = "cours"
Public Const PageUrl = "/prog.php?"
Public Const FichierJoueurNom = "PlayerList.cfg"
Public Const DossierDonnees = "data"
Private Const GWL_WNDPROC = (-4)
Private Const WM_GETMINMAXINFO = &H24


'TYPES
Private Type POINTAPI
    x As Long
    y As Long
End Type
Private Type MINMAXINFO
    ptReserved As POINTAPI
    ptMaxSize As POINTAPI
    ptMaxPosition As POINTAPI
    ptMinTrackSize As POINTAPI
    ptMaxTrackSize As POINTAPI
End Type
Public Type Joueurtype
    Pseudo As String
    Pass As String
    Session As String
    PassLen As Integer
    DossierJoueur As String
    Vad As Boolean
    ConfigJoueur As String
End Type

Public Type portefdat
    Code As Long
    nom As String
    Nombre As Long
    ValAchat As Double
    ValActuel As Double
End Type

Public Type Tordre
    Sens As String 'achat vente venteadecouvert
    Cond As String 'atp as ap //atoutprix aseuil aplage
    codesico As Long
    Qte As Long
    Prc As Double ' 0 a 100
    DateLim As String
    CoursMin As Double
    CoursMax As Double
End Type

Public Type TglobalState
    DoUpdateHisto As Boolean
    DoUpdatePortef As Boolean
End Type
'VARIABLES
Public GlobalState As TglobalState
Public SkinRep As String
Private MinX As Single
Private MinY As Single
Private AdressWinProc As Long
Public feuille() As Form
Public joueur() As Joueurtype 'chaque profil sauvegardé
Public IdJoueur As Integer 'id dans joueur() actuellement utilisé

Public Sub EcrireErreur(erreur As String)
Dim canal As Integer
canal = FreeFile
Open "err.log" For Append As #canal
Print #canal, Now & ": " & erreur
Close canal
End Sub

Public Function LogIn(Pseudo As String, Mdp As String) As Boolean
Dim data As String, message, Idcompte, Session, Code, erreur As String
IdJoueur = 1
Dim i As Integer
'on test si les données sont valides
data = CommNetTrader("login", "do=login&pseudo=" & Pseudo & "&pass=" & Mdp)

erreur = GetVarXML(data, "erreur")
If erreur = "vrai" Then
    message = GetVarXML(data, "message")
    Code = GetVarXML(data, "codeerreur")
    If Code = "1" Then
        ShowStatutBar ("Mot de passe invalide")
        LogIn = False
        Exit Function
    ElseIf Code = "2" Then
        ShowStatutBar ("Le pseudonyme entré n'appartient à aucun utilisateur")
        LogIn = False
        Exit Function
    ElseIf Code = "6" Then
        ShowStatutBar ("Nombre d'essai dépassé, veuillez réessayer dans quelques minutes.")
        LogIn = False
        Exit Function
    Else
        ShowStatutBar ("Erreur inconnue : " & message)
        LogIn = False
        Exit Function
    End If
Else
    If erreur = "faux" Then
        'Si pas d'erreur mysql
        'on charge la variable
        i = 1
        IdJoueur = 1

        Do While i <= UBound(joueur)
            If joueur(i).Pseudo = Pseudo Then
                IdJoueur = i
                Exit Do
            End If
            i = i + 1
        Loop
        If joueur(IdJoueur).Pseudo <> Pseudo And UBound(joueur) > 1 Then   'le joueur n'est pas dans la liste
            IdJoueur = UBound(joueur) + 1
            ReDim Preserve joueur(1 To UBound(joueur) + 1)
        End If


        joueur(IdJoueur).Pass = Mdp
        joueur(IdJoueur).Pseudo = Pseudo
        joueur(IdJoueur).Session = GetVarXML(data, "session")
        joueur(IdJoueur).PassLen = Len(frmlogin.Text3.Text)
        joueur(IdJoueur).Vad = False
        If GetVarXML(data, "vad") = "1" Then joueur(IdJoueur).Vad = True
        ShowStatutBar (Pseudo & ", authentification réussi.")
        LogIn = True
    Else
        'bon là il y a un gros problème ( erreur 404 ou autre )
        ShowStatutBar ("Serveur d'authentification injoignable, verifiez votre connexion internet.")
        LogIn = False
        Exit Function
    End If
End If
End Function

Public Sub OpenBrowser(Url As String)
ShellExecute NetTrader2Mdi.hWnd, "open", Url, ByVal 0&, 0&, 1
End Sub


Sub Main()
SkinRep = " "
SkinRep = GetIni("NetTrader", "SkinRep")
SkinRep = "skin\" & SkinRep & "\"
ReDim joueur(1)
Load NetTrader2Mdi
NetTrader2Mdi.Show
Load frmlogin
frmlogin.Show vbModal
End Sub
Public Function FileExists(sFileName As String) As Boolean
    On Error Resume Next
    FileExists = ((GetAttr(sFileName) And vbDirectory) = 0)
End Function
Public Function DossierExiste(Path As String, ThingType As VbFileAttribute) As Boolean
    On Error Resume Next
    DossierExiste = ((GetAttr(Path) And ThingType) = ThingType)
    Err.Clear
End Function
Public Function IniColor(Cat As String, objet As String) As Long
Dim ret As String, NC As Long
Dim a, couleur, i, rouge, vert, bleu As Integer

ret = String(255, 0)
NC = GetPrivateProfileString(Cat, objet, "Default", ret, 255, App.Path & "\" & SkinRep & "Config.ini")
If NC <> 0 Then ret = Left$(ret, NC)
a = Split(ret, ",")
i = 0
If ret = "Default" Then
    MsgBox "Le fichier config.ini ne semble pas correct.Pour " & objet & " dans " & Cat, vbCritical & vbOKOnly, "Erreur du fichier de configuration"
    rouge = 0
    vert = 0
    bleu = 0
    GoTo couleur
End If
For Each couleur In a
    Select Case i
    Case 0
        rouge = couleur
    Case 1
        vert = couleur
    Case 2
        bleu = couleur
    End Select
    i = i + 1
Next couleur
couleur:
IniColor = RGB(rouge, vert, bleu)
End Function
Public Function GetIni(Cat As String, objet As String) As String
Dim ret As String, NC As Long

ret = String(255, 0)
NC = GetPrivateProfileString(Cat, objet, "Default", ret, 255, App.Path & "\" & SkinRep & "Config.ini")
If NC <> 0 Then ret = Left$(ret, NC)
If ret = "Default" Then
    MsgBox "Le fichier config.ini ne semble pas correct.Pour " & objet & " dans " & Cat, vbCritical & vbOKOnly, "Erreur du fichier de configuration"
    ret = ""
    GoTo fin
End If
fin:
GetIni = ret
End Function
Public Function GetExtension(nom As String) As String
    Dim emp As Long
    emp = InStrRev(nom, ".")
    GetExtension = Mid(nom, emp + 1, Len(nom) - emp)
End Function
Public Function GetNomFichier(nom As String) As String
    Dim emp As Long
    emp = InStrRev(nom, ".")
    GetNomFichier = Mid(nom, 1, emp - 1)
End Function
Public Function Download(Url As String) As String

    Dim hOpen                As Long
    Dim hOpenUrl            As Long
    Dim bDoLoop          As Boolean
    Dim bRet                As Boolean
    Dim sReadBuffer      As String * 2048
    Dim lNumberOfBytesRead  As Long
    Dim sBuffer          As String

    hOpen = InternetOpen(scUserAgent, INTERNET_OPEN_TYPE_PRECONFIG, vbNullString, vbNullString, 0)
    hOpenUrl = InternetOpenUrl(hOpen, Url, vbNullString, 0, INTERNET_FLAG_RELOAD, 0)

    bDoLoop = True
    While bDoLoop
        DoEvents
        sReadBuffer = vbNullString
        bRet = InternetReadFile(hOpenUrl, sReadBuffer, Len(sReadBuffer), lNumberOfBytesRead)
        sBuffer = sBuffer & Left$(sReadBuffer, lNumberOfBytesRead)
        If Not CBool(lNumberOfBytesRead) Then bDoLoop = False
    Wend

    Download = CStr(sBuffer)
    
    If hOpenUrl <> 0 Then InternetCloseHandle (hOpenUrl)
    If hOpen <> 0 Then InternetCloseHandle (hOpen)

End Function
Public Function MaWinProc(ByVal hWnd As Long, ByVal uMsg As Long, ByVal wParam As Long, ByVal lParam As Long) As Long
    Dim MinMax As MINMAXINFO
    
    'Intercepte le Message Windows de redimensionnement de fenêtre
    If uMsg = WM_GETMINMAXINFO Then
        CopyMemory MinMax, ByVal lParam, Len(MinMax)
        MinMax.ptMinTrackSize.x = MinX \ Screen.TwipsPerPixelX
        MinMax.ptMinTrackSize.y = MinY \ Screen.TwipsPerPixelY

        CopyMemory ByVal lParam, MinMax, Len(MinMax)
        'Code de retour pour signaler à Windows que le traitement s'est correctement effectué
        MaWinProc = 1
        Exit Function
    End If
    
    'Laisse les autres Messages à traiter à Windows
    MaWinProc = CallWindowProc(AdressWinProc, hWnd, uMsg, wParam, lParam)
End Function


Public Function LoadResizing(ByRef hWnd As Long, ByRef MinWidth As Single, ByRef MinHeight As Single)
    MinX = MinWidth
    MinY = MinHeight
    AdressWinProc = SetWindowLong(hWnd, GWL_WNDPROC, AddressOf MaWinProc)
End Function
Public Function GetConfJoueur(NomSection As String, NomValeur As String, Default As Variant) As String
'on recherche si il existe le fichier historique de sauvegardé
'on recherche si il existe le fichier de configuration pour le joueur specifié
Dim data As String
Dim canal As Integer
Dim NomConfig As String
Dim DataFromFile As String
Dim NomDossierJoueur As String
Dim res As Variant
DataFromFile = ""
NomDossierJoueur = App.Path & "\" & DossierDonnees & "\" & joueur(IdJoueur).DossierJoueur
NomConfig = NomDossierJoueur & "\config.xml"
If joueur(IdJoueur).ConfigJoueur = "" Then
    If FileExists(NomConfig) Then
        canal = FreeFile
        Open NomConfig For Input As #canal
        Do Until EOF(canal)
            Input #canal, data
            joueur(IdJoueur).ConfigJoueur = joueur(IdJoueur).ConfigJoueur & data
        Loop
        Close canal
    Else
        GetConfJoueur = Default
        Exit Function
    End If
End If
res = GetVarXML(GetVarXML(joueur(IdJoueur).ConfigJoueur, NomSection), NomValeur)
If res <> "" Then
    GetConfJoueur = res
Else
    GetConfJoueur = Default
End If

End Function
Public Sub SauverConfigJoueur()
Dim DonneesValeur, NomDossierJoueur As String, data As String
Dim canal As Integer
Dim NomConfig As String

NomDossierJoueur = App.Path & "\" & DossierDonnees & "\" & joueur(IdJoueur).DossierJoueur
NomConfig = NomDossierJoueur & "\config.xml"


'on enregistre
If Not DossierExiste(NomDossierJoueur, vbDirectory) Then
   MkDir NomDossierJoueur
End If

canal = FreeFile
Open NomConfig For Output As #canal
Print #canal, joueur(IdJoueur).ConfigJoueur
Close canal
End Sub
Public Function MajConfigJoueur(NomSection As String, NomValeur As String, valeur As String)
Dim DonneesSection As String
Dim DonneesValeur, NomDossierJoueur, data As String
Dim canal As Integer
Dim NomConfig As String
'met a jour si existe, ajoute si n'existe pas
NomDossierJoueur = App.Path & "\" & DossierDonnees & "\" & joueur(IdJoueur).DossierJoueur
NomConfig = NomDossierJoueur & "\config.xml"
If joueur(IdJoueur).ConfigJoueur = "" Then
    If FileExists(NomConfig) Then
        canal = FreeFile
        Open NomConfig For Input As #canal
        Do Until EOF(canal)
            Input #canal, data
            joueur(IdJoueur).ConfigJoueur = joueur(IdJoueur).ConfigJoueur & data
        Loop
        Close canal
    End If
End If
'on modifie ConfigJoueur
'on cherche la section
DonneesSection = GetVarXML(joueur(IdJoueur).ConfigJoueur, NomSection)
DonneesValeur = GetVarXML(DonneesSection, NomValeur)
If DonneesSection <> "" Then
    If DonneesValeur <> "" Then
        'il faut modifier la valeur
        joueur(IdJoueur).ConfigJoueur = ReplaceVarXML(joueur(IdJoueur).ConfigJoueur, NomSection, ReplaceVarXML(DonneesSection, NomValeur, valeur))
    Else
        'il faut creer la valeur
        joueur(IdJoueur).ConfigJoueur = ReplaceVarXML(joueur(IdJoueur).ConfigJoueur, NomSection, DonneesSection & cxml(valeur, NomValeur))
    End If
Else
'il faut creer la section et la valeur
joueur(IdJoueur).ConfigJoueur = joueur(IdJoueur).ConfigJoueur & cxml(cxml(valeur, NomValeur), NomSection)
End If

End Function
Public Function RestoreResizing(ByRef hWnd As Long)
    Call SetWindowLong(hWnd, GWL_WNDPROC, AdressWinProc)
End Function
Public Function MD5String(p As String) As String
' compute MD5 digest on a given string, returning the result
    Dim r As String * 32, t As Long
    r = Space(32)
    t = Len(p)
    MDStringFix p, t, r
    MD5String = r
End Function

Public Function CommNetTrader(Commande As String, Url As String) As String
'télécharge les données du site nettrader
Dim retour As String
Dim choix As VbMsgBoxResult


Url = GameUrl & PageUrl & Url

retry:

Select Case Commande
    Case "login"
        ShowStatutBar ("Connexion au serveur d'authentification en cours...")
    Case "relog"
        ShowStatutBar ("Session expiré, tentative de nouvelle authentification...")
    Case "delog"
        ShowStatutBar ("Déconnexion en cours...")
    Case "mess"
        ShowStatutBar ("Connexion au serveur pour réception du message.")
    Case "getportef"
        ShowStatutBar ("Connexion au serveur pour téléchargement du portefeuille.")
    Case "envoiordre"
        ShowStatutBar ("Connexion au serveur pour envoie de l'ordre.")
    Case "supprordre"
        ShowStatutBar ("Connexion au serveur pour suppression de l'ordre.")
    Case Else
        ShowStatutBar ("Connexion au serveur pour télechargement des informations")
End Select

If Commande <> "login" And Commande <> "relog" And Commande <> "mess" Then
    Url = Url & "&sess=" & joueur(IdJoueur).Session
End If

If DEBUG_MODE Then EcrireErreur ("Telechargement : " & Url)

retour = Download(Url)

If retour = "" Then
    If retour = "" Then choix = MsgBox("Connexion au serveur impossible, voulez vous réessayer ?", vbCritical + vbAbortRetryIgnore, "Erreur de connexion")
    If GetVarXML(retour, "erreur") = "" Then choix = MsgBox("Nous avons pu nous connecter, cependant le serveur de NetTrader a des difficultées, voulez-vous réessayer ?", vbCritical + vbAbortRetryIgnore, "Erreur du serveur")
    If choix = vbRetry Then GoTo retry
    If choix = vbCancel Then
        Call DoRelog
        Exit Function
    End If
    If choix = vbAbort Then
        End
    End If
End If

If GetVarXML(retour, "erreur") = "vrai" And Commande <> "delog" Then 'Session expiré
   If CInt(GetVarXML(retour, "codeerreur")) = 3 Then
        Call LogIn(joueur(IdJoueur).Pseudo, joueur(IdJoueur).Pass)
        GoTo retry
    End If
End If

ShowStatutBar ("Réception des données réussi")

CommNetTrader = retour

End Function
Public Sub ShowStatutBar(message As String)
NetTrader2Mdi.Timer2.Enabled = True
NetTrader2Mdi.Picture2.Cls
NetTrader2Mdi.Picture2.CurrentY = (NetTrader2Mdi.Picture2.Height / 2) - (NetTrader2Mdi.Picture2.TextHeight("PPPPP") / 2)
NetTrader2Mdi.Picture2.CurrentX = (NetTrader2Mdi.Picture2.Width / 2) - (NetTrader2Mdi.Picture2.TextWidth(message) / 2)
NetTrader2Mdi.Picture2.Print message
DoEvents
End Sub

Public Function DoRelog()
'L'utilisateur doit s'identifier à nouveau
Call CloseAll
Load frmlogin
If Not frmlogin.Visible Then
    frmlogin.Show vbModal
End If
End Function

Public Function CloseAll()
Dim i
If Not IsNull(feuille) Then
    For i = 0 To UBound(feuille)
        If Not feuille(i) Is Nothing Then
            Unload feuille(i)
        End If
    Next i
End If
Unload Formav
Unload FormHisto
Unload frmdown

End Function
Public Function GetVarXML(data As String, key As String) As String
Dim debpos, finpos As Long
Dim retour, sopen, sclose As String
Dim valeur As String
sopen = "<" & key & ">"
sclose = "</" & key & ">"

debpos = InStr(data, sopen) + Len(sopen)
finpos = InStr(data, sclose)

If debpos < finpos Then
    valeur = Mid(data, debpos, finpos - debpos)
    If IsNumeric(Replace(valeur, ".", ",")) Then valeur = Replace(valeur, ".", ",")
    retour = valeur
Else
    retour = ""
End If

GetVarXML = retour
End Function
Public Function ReplaceVarXML(data As String, key As String, nouvellevaleur As String) As String
Dim Debut As String
Dim fin As String
Dim debpos, finpos As Long
Dim retour, sopen, sclose As String
Dim valeur As String
sopen = "<" & key & ">"
sclose = "</" & key & ">"

debpos = InStr(data, sopen) + Len(sopen)
finpos = InStr(data, sclose)

If debpos < finpos Then
    'Mid(Data, debpos, finpos - debpos)
    retour = Left(data, debpos - 1) & nouvellevaleur & Right(data, Len(data) - finpos + 1)
Else
    retour = data
End If

ReplaceVarXML = retour
End Function
Public Function GetTabXML(data As String, keytab As String, keylign As String, KeyCol As Variant, Optional TxtAdd As String = "") As Variant
Dim debpos, finpos As Long
Dim retour, sopen, sclose, TabTot As String, res As String, ColKey As Variant, ColData As String
Dim RetTab As Variant
Dim nblign As Variant
Dim i As Integer 'ligne
Dim j As Integer 'colonne
TabTot = GetVarXML(data, keytab) & TxtAdd 'Tableau à traiter

If TabTot <> "" Then 'si le tableau existe
    sclose = "</" & keylign & ">"
    finpos = InStr(TabTot, sclose) + Len(sclose)
    res = GetVarXML(TabTot, keylign)
    nblign = UBound(Split(TabTot, sclose)) - 1
    TabTot = Mid(TabTot, finpos)
    i = 0
    ReDim RetTab(0 To nblign, 0 To UBound(KeyCol))
    Do While res <> "" 'tant que l'on a pas traité chaque ligne
        'l'ont doit maintenant traiter chaque colonne
        j = 0
        For Each ColKey In KeyCol 'pour chaque colonne
            ColData = GetVarXML(res, CStr(ColKey))
            RetTab(i, j) = ColData
            j = j + 1
        Next ColKey
        'ligne suivante
        finpos = InStr(TabTot, sclose) + Len(sclose)
        res = GetVarXML(TabTot, keylign)
        If res <> "" Then
            TabTot = Mid(TabTot, finpos)
            i = i + 1
        End If
    Loop
Else
    RetTab = ""
End If

GetTabXML = RetTab
End Function

Public Sub controleSortie(ctrl As ComboBox)
Dim i As Integer
Dim proche As Integer
If ctrl.ListCount > 0 Then
    i = 0
    proche = 0
    Do While ctrl.List(i) <> ctrl.Text And i < ctrl.ListCount - 1
        i = i + 1
        If UCase(Mid(ctrl.List(i), 1, Len(ctrl.Text))) = UCase(ctrl.Text) Then proche = i
    Loop
    If ctrl.List(i) <> ctrl.Text Then 'pas trouve
        ctrl.ListIndex = proche
    Else
        ctrl.ListIndex = i
    End If
End If
End Sub
Public Function Cdblo(chaine) As Double
Cdblo = CDbl(Replace(chaine, ".", ","))
End Function
Public Function cxml(valeur As String, champ As String) As String
cxml = "<" & champ & ">" & valeur & "</" & champ & ">"
End Function
Public Function ChercherTab(tabval As Variant, ColToFind, ValToFind)
'on va chercher dans un tableau et renvoyer la valeur d'une autre colonne
Dim i As Integer
i = 0
    Do While i < UBound(tabval) And tabval(i, ColToFind) <> ValToFind
       i = i + 1
    Loop
If tabval(i, ColToFind) = ValToFind Then
    ChercherTab = i
Else
    ChercherTab = -1
End If
End Function



Public Sub ChargeSkinFille(frm As Form) ' applique les couleurs aux controles des feuilles filles
Dim i As Integer
Static charge As Boolean
Static fondcouleur, FondBouton, FondBox, CouleurTexte, CouleurTexteBox, FondCouleurGrille, CouleurTexteGrille As Long
Static NomPolice, NomPolicelbl As String

If Not charge Then
    'chargement des variable contenant les couleurs
    fondcouleur = IniColor("MDIChild", "Fond")
    FondBouton = IniColor("MDIChild", "FondBouton")
    FondBox = IniColor("MDIChild", "TextBoxFond")
    CouleurTexteBox = IniColor("MDIChild", "TextBoxCouleurPolice")
    CouleurTexte = IniColor("MDIChild", "PoliceCouleur")
    FondCouleurGrille = IniColor("Controles", "FondCouleurGrilleFormAV")
    CouleurTexteGrille = IniColor("Controles", "CouleurTexteGrilleAV")
    'chargement des variables contenant le texte ( police ... )
    NomPolice = GetIni("MDIChild", "TextBoxPolicenom")
    NomPolicelbl = GetIni("MDIChild", "NomPolice")
    charge = True
End If



frm.BackColor = fondcouleur ' couleur de fond des feuilles filles

For i = 0 To frm.Controls.Count - 1 'pour chaque contrôle
    If TypeOf frm.Controls(i) Is Frame Then 'si une frame
        frm.Controls(i).BackColor = fondcouleur
        frm.Controls(i).FontName = NomPolicelbl
        frm.Controls(i).ForeColor = CouleurTexte
    End If
    If TypeOf frm.Controls(i) Is PictureBox And frm.Controls(i).Tag = "frame" Then 'si une pb
        frm.Controls(i).BackColor = fondcouleur
    End If
    If TypeOf frm.Controls(i) Is Label Then 'si une etiquette
        frm.Controls(i).FontName = NomPolicelbl
        frm.Controls(i).ForeColor = CouleurTexte
    End If
    If TypeOf frm.Controls(i) Is CommandButton Then
        frm.Controls(i).BackColor = FondBouton
    End If
    If TypeOf frm.Controls(i) Is TextBox Then
        frm.Controls(i).ForeColor = CouleurTexteBox
        frm.Controls(i).BackColor = FondBox
    End If
    If TypeOf frm.Controls(i) Is ComboBox Then
        frm.Controls(i).ForeColor = CouleurTexteBox
        frm.Controls(i).BackColor = FondBox
    End If
    If TypeOf frm.Controls(i) Is MSFlexGrid Then
        frm.Controls(i).BackColor = FondCouleurGrille
        frm.Controls(i).BackColorBkg = FondCouleurGrille
        frm.Controls(i).BackColorFixed = FondCouleurGrille
        frm.Controls(i).ForeColor = CouleurTexteGrille
    End If
    If TypeOf frm.Controls(i) Is CheckBox Then
        frm.Controls(i).FontName = NomPolicelbl
        frm.Controls(i).ForeColor = CouleurTexte
        frm.Controls(i).BackColor = fondcouleur
    End If
    If TypeOf frm.Controls(i) Is OptionButton Then
        frm.Controls(i).FontName = NomPolicelbl
        frm.Controls(i).ForeColor = CouleurTexte
        frm.Controls(i).BackColor = fondcouleur
    End If
    If TypeOf frm.Controls(i) Is ListBox Then
        frm.Controls(i).BackColor = FondBox
        frm.Controls(i).FontName = NomPolicelbl
        frm.Controls(i).ForeColor = CouleurTexte
    End If
Next i

End Sub
