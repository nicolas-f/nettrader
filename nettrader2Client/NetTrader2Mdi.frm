VERSION 5.00
Begin VB.MDIForm NetTrader2Mdi 
   BackColor       =   &H00808080&
   Caption         =   "NetTrader 2"
   ClientHeight    =   6585
   ClientLeft      =   60
   ClientTop       =   570
   ClientWidth     =   9570
   Icon            =   "NetTrader2Mdi.frx":0000
   LinkTopic       =   "MDIForm1"
   LockControls    =   -1  'True
   WindowState     =   2  'Maximized
   Begin VB.Timer Timer2 
      Enabled         =   0   'False
      Interval        =   7000
      Left            =   3120
      Top             =   3360
   End
   Begin VB.PictureBox Picture2 
      Align           =   2  'Align Bottom
      Appearance      =   0  'Flat
      AutoRedraw      =   -1  'True
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   255
      Left            =   0
      ScaleHeight     =   255
      ScaleWidth      =   9570
      TabIndex        =   6
      Top             =   6330
      Width           =   9570
   End
   Begin VB.PictureBox Picture1 
      Align           =   3  'Align Left
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   6330
      Left            =   0
      ScaleHeight     =   6330
      ScaleWidth      =   1185
      TabIndex        =   0
      Top             =   0
      Width           =   1185
      Begin VB.CommandButton Command5 
         Appearance      =   0  'Flat
         BackColor       =   &H80000010&
         Height          =   840
         Left            =   120
         MaskColor       =   &H00C00000&
         Style           =   1  'Graphical
         TabIndex        =   5
         ToolTipText     =   "Telecharger les valeurs"
         Top             =   3960
         Width           =   870
      End
      Begin VB.CommandButton Command4 
         Appearance      =   0  'Flat
         BackColor       =   &H80000010&
         Height          =   840
         Left            =   120
         MaskColor       =   &H00C00000&
         Style           =   1  'Graphical
         TabIndex        =   4
         ToolTipText     =   "Graphique"
         Top             =   3000
         Width           =   870
      End
      Begin VB.CommandButton Command3 
         Appearance      =   0  'Flat
         BackColor       =   &H80000010&
         Height          =   840
         Left            =   120
         MaskColor       =   &H00C00000&
         Style           =   1  'Graphical
         TabIndex        =   3
         ToolTipText     =   "Historique"
         Top             =   2040
         Width           =   870
      End
      Begin VB.CommandButton Command2 
         Appearance      =   0  'Flat
         BackColor       =   &H80000010&
         Height          =   840
         Left            =   120
         MaskColor       =   &H00C00000&
         Style           =   1  'Graphical
         TabIndex        =   2
         ToolTipText     =   "Acheter Vendre"
         Top             =   1080
         Width           =   870
      End
      Begin VB.CommandButton Command1 
         Appearance      =   0  'Flat
         BackColor       =   &H80000010&
         Height          =   840
         Left            =   120
         MaskColor       =   &H00C00000&
         Style           =   1  'Graphical
         TabIndex        =   1
         ToolTipText     =   "Changer de joueur"
         Top             =   120
         Width           =   870
      End
   End
   Begin VB.Timer Timer1 
      Enabled         =   0   'False
      Left            =   1560
      Top             =   840
   End
   Begin VB.Menu MnuFichier 
      Caption         =   "Fichier"
      Begin VB.Menu MnuLogin 
         Caption         =   "Log-In"
      End
      Begin VB.Menu MnuSuppCache 
         Caption         =   "Effacer le cache"
      End
      Begin VB.Menu MnuBarre 
         Caption         =   "-"
      End
      Begin VB.Menu MnuQuitter 
         Caption         =   "&Quitter"
      End
   End
   Begin VB.Menu MnuJoueur 
      Caption         =   "Joueur"
      Begin VB.Menu MnuAV 
         Caption         =   "Achat Vente"
      End
      Begin VB.Menu MnuHisto 
         Caption         =   "Historique"
      End
   End
   Begin VB.Menu MnuBourse 
      Caption         =   "Bourse"
      Begin VB.Menu MnuDown 
         Caption         =   "Telecharger les valeurs"
      End
      Begin VB.Menu MnuHistoAction 
         Caption         =   "Historique Action"
      End
      Begin VB.Menu Mnugraph 
         Caption         =   "Graphique"
      End
   End
   Begin VB.Menu MnuSite 
      Caption         =   "Site NetTrader"
      Begin VB.Menu MnuSiteAcc 
         Caption         =   "Page d'accueil"
      End
      Begin VB.Menu MnuClassement 
         Caption         =   "Votre Classement"
      End
      Begin VB.Menu MnuMessages 
         Caption         =   "Vos Messages"
      End
   End
End
Attribute VB_Name = "NetTrader2Mdi"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
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


Private Sub ChangeUser()
Dim res

res = MsgBox("Êtes-vous sûr de vouloir vous déconnecter ?", vbYesNo + vbInformation, "Déconnexion")

If res = vbYes Then
    Call SauverConfigJoueur
    Call CommNetTrader("delog", "do=deco")
    Load frmlogin
    frmlogin.Show vbModal
End If
End Sub
Private Sub Command1_Click()
Call ChangeUser
End Sub

Private Sub Command2_Click()
Load Formav
Formav.Show
End Sub

Private Sub Command3_Click()
Load FormHisto
FormHisto.Show
End Sub

Private Sub Command4_Click()
Call LoadNewGraph
End Sub
Private Sub LoadNewGraph()
ReDim Preserve feuille(0 To UBound(feuille) + 1)
Set feuille(UBound(feuille)) = New graphi
feuille(UBound(feuille)).Show
End Sub
Private Sub Command5_Click()
Load frmdown
frmdown.Show
End Sub

Private Sub MDIForm_Initialize()
InitCommonControls
End Sub

Private Sub MDIForm_Load()
ReDim feuille(0 To 0) 'prepare le tableau de feuilles de graph
Call ChargeSkinMdi
End Sub
Private Sub ChargeSkinMdi()
Dim gauche, Largeur, Hauteur As Long

Command1.Picture = LoadPicture(SkinRep & "image\icon2.bmp")
Command2.Picture = LoadPicture(SkinRep & "image\icon1.bmp")
Command3.Picture = LoadPicture(SkinRep & "image\icon3.bmp")
Command4.Picture = LoadPicture(SkinRep & "image\icon4.bmp")
Command5.Picture = LoadPicture(SkinRep & "image\icon5.bmp")

Largeur = GetIni("MDIConfig", "BarreBoutonLargeur")
Hauteur = GetIni("MDIConfig", "BarreBoutonHauteur")

Command1.Width = Largeur
Command1.Height = Hauteur
Command2.Width = Largeur
Command2.Height = Hauteur
Command3.Width = Largeur
Command3.Height = Hauteur
Command4.Width = Largeur
Command4.Height = Hauteur
Command5.Width = Largeur
Command5.Height = Hauteur

fond = IniColor("MDICouleur", "FondBouton")

Command1.BackColor = fond
Command2.BackColor = fond
Command3.BackColor = fond
Command4.BackColor = fond
Command5.BackColor = fond

gauche = Split(GetIni("MDIConfig", "BarreBoutonLeft"), ",")

Command1.Left = gauche(0)
Command2.Left = gauche(1)
Command3.Left = gauche(2)
Command4.Left = gauche(3)
Command5.Left = gauche(4)

Haut = Split(GetIni("MDIConfig", "BarreBoutonTop"), ",")

Command1.Top = Haut(0)
Command2.Top = Haut(1)
Command3.Top = Haut(2)
Command4.Top = Haut(3)
Command5.Top = Haut(4)

Picture1.Align = CInt(GetIni("MDIConfig", "BarreAlignement"))
couleur = IniColor("MDICouleur", "Barre")
Picture1.BackColor = couleur
Picture2.BackColor = couleur

If Picture1.Align = 0 Then
    Picture1.Left = GetIni("MDIConfig", "Barreleft")
    Picture1.Top = GetIni("MDIConfig", "Barretop")
End If

Largeur = GetIni("MDIConfig", "BarreLargeur")
If Largeur <> 0 Then
    Picture1.Width = Largeur
End If

Hauteur = GetIni("MDIConfig", "BarreHauteur")
If Hauteur <> 0 Then
    Picture1.Height = Hauteur
End If

NetTrader2Mdi.BackColor = IniColor("MDICouleur", "Fond")

Picture2.ForeColor = IniColor("MDICouleur", "CouleurTexteInfoBarre")

Picture2.FontSize = GetIni("MDIConfig", "TailleTexteInfoBarre")

Picture2.FontName = GetIni("MDIConfig", "PoliceTexteInfoBarre")
End Sub


Private Sub MnuAV_Click()
Call Command2_Click
End Sub

Private Sub MnuDown_Click()
Call Command5_Click
End Sub

Private Sub Mnugraph_Click()
Call Command4_Click
End Sub

Private Sub MnuHisto_Click()
Call Command3_Click
End Sub

Private Sub MnuQuitter_Click()
'delog()
Call SauverConfigJoueur
End


End Sub

Private Sub MnuSuppCache_Click()
Dim NomDossierJoueur As String
NomDossierJoueur = App.Path & "\" & DossierDonnees & "\" & joueur(IdJoueur).DossierJoueur
If DossierExiste(NomDossierJoueur, vbDirectory) Then
    RmDir ("data\fe01ce2a\") '(NomDossierJoueur)
End If
Call ShowStatutBar("Les données en cache vous concernant ont été supprimées")



End Sub

Private Sub Timer2_Timer()
ShowStatutBar ""
Timer2.Enabled = False
End Sub
