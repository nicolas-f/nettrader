VERSION 5.00
Begin VB.Form frmlogin 
   BackColor       =   &H00FFFFFF&
   BorderStyle     =   4  'Fixed ToolWindow
   Caption         =   "Authentification NetTrader 2"
   ClientHeight    =   3585
   ClientLeft      =   45
   ClientTop       =   315
   ClientWidth     =   6000
   ForeColor       =   &H00000000&
   Icon            =   "Form2.frx":0000
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   3585
   ScaleWidth      =   6000
   ShowInTaskbar   =   0   'False
   StartUpPosition =   2  'CenterScreen
   Begin VB.CheckBox Check2 
      Caption         =   "Enregistrer le Pseudonyme"
      Height          =   255
      Left            =   3240
      TabIndex        =   13
      Top             =   1920
      Value           =   1  'Checked
      Width           =   2175
   End
   Begin VB.Timer Timer1 
      Enabled         =   0   'False
      Interval        =   3000
      Left            =   5520
      Top             =   2040
   End
   Begin VB.ComboBox Combo1 
      Height          =   315
      Left            =   4320
      TabIndex        =   2
      Text            =   "Combo1"
      Top             =   1080
      Width           =   1575
   End
   Begin VB.PictureBox Picture1 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   975
      Left            =   0
      ScaleHeight     =   975
      ScaleWidth      =   3315
      TabIndex        =   11
      Top             =   0
      Width           =   3315
   End
   Begin VB.CommandButton CmdInscr 
      Height          =   420
      Left            =   3720
      Style           =   1  'Graphical
      TabIndex        =   4
      Top             =   360
      Width           =   2010
   End
   Begin VB.CommandButton CmdFermer 
      Height          =   420
      Left            =   4560
      Style           =   1  'Graphical
      TabIndex        =   1
      Top             =   2640
      Width           =   1215
   End
   Begin VB.CheckBox Check1 
      BackColor       =   &H80000009&
      Caption         =   "Enregistrer le mot de passe"
      Height          =   255
      Left            =   3240
      TabIndex        =   5
      Top             =   2280
      Width           =   2655
   End
   Begin VB.TextBox Text4 
      Height          =   285
      Left            =   240
      TabIndex        =   10
      Text            =   "Si vous n'avez pas créé de compte cliquez sur le bouton inscription."
      Top             =   3240
      Width           =   5655
   End
   Begin VB.TextBox Text3 
      Height          =   285
      IMEMode         =   3  'DISABLE
      Left            =   4320
      PasswordChar    =   "*"
      TabIndex        =   3
      Top             =   1560
      Width           =   1575
   End
   Begin VB.TextBox Text2 
      Height          =   1875
      Left            =   120
      MultiLine       =   -1  'True
      ScrollBars      =   2  'Vertical
      TabIndex        =   9
      Top             =   1230
      Width           =   2775
   End
   Begin VB.CommandButton CmdOk 
      Appearance      =   0  'Flat
      BackColor       =   &H00E0E0E0&
      Height          =   420
      Left            =   3120
      Style           =   1  'Graphical
      TabIndex        =   0
      Top             =   2640
      Width           =   1215
   End
   Begin VB.CommandButton CmdConfig 
      Height          =   420
      Left            =   3720
      Style           =   1  'Graphical
      TabIndex        =   6
      Top             =   360
      Visible         =   0   'False
      Width           =   2010
   End
   Begin VB.Label Label3 
      BackStyle       =   0  'Transparent
      Caption         =   "Créé par Nicolas FORTIN"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   120
      TabIndex        =   12
      Top             =   960
      Width           =   3015
   End
   Begin VB.Label Label2 
      Alignment       =   1  'Right Justify
      BackStyle       =   0  'Transparent
      Caption         =   "Mot de passe :"
      Height          =   255
      Left            =   3120
      TabIndex        =   8
      Top             =   1560
      Width           =   1095
   End
   Begin VB.Label Label1 
      Alignment       =   1  'Right Justify
      BackStyle       =   0  'Transparent
      Caption         =   "Pseudonyme :"
      Height          =   255
      Left            =   3120
      TabIndex        =   7
      Top             =   1080
      Width           =   1095
   End
End
Attribute VB_Name = "frmlogin"
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


Private Sub Check1_Click()
If Check1.value = vbChecked Then Check2.value = vbChecked
End Sub

Private Sub Check2_Click()
If Check2.value = vbUnchecked Then Check1.value = vbUnchecked
End Sub

Private Sub Combo1_Click()
Dim id As Integer
'on recherche
id = 1
Do While id <= UBound(joueur) And joueur(id).Pseudo <> Combo1.Text
    id = id + 1
Loop
If joueur(id).Pseudo <> Combo1.Text Then
    'pas trouvé

Else
    'trouvé
    If joueur(id).PassLen > 0 Then
        Text3.Text = String(joueur(id).PassLen, "*")
        Text3.Tag = joueur(id).Pass
        Check1.value = vbChecked
    Else
        Text3.Text = ""
        Text3.Tag = ""
    End If
End If


End Sub

Private Sub Combo1_KeyPress(KeyAscii As Integer)
Text3.Tag = ""
Text3.Text = ""
End Sub

Private Sub CmdOk_Click()
CmdOk.Enabled = False
Dim res As Boolean
Dim id As Integer
Dim last As Integer
If Text3.Tag <> "" Then
    res = LogIn(Combo1.Text, Text3.Tag)
Else
    res = LogIn(Combo1.Text, MD5String(Text3.Text))
End If

If res = True Then
    'le joueur est loggé on enregistre
    canal = FreeFile
    Open FichierJoueurNom For Output As #canal
    For id = 1 To UBound(joueur)
        last = False
        If id = IdJoueur Then last = True
        If ((id = IdJoueur And Check1.value = vbChecked) Or (joueur(id).PassLen <> 0 And id <> IdJoueur)) Then
            Write #canal, joueur(id).Pseudo, joueur(id).Pass, joueur(id).PassLen, last
        Else
            If id <> IdJoueur Or Check2.value = vbChecked Then Write #canal, joueur(id).Pseudo, "", 0, last
        End If
        joueur(id).DossierJoueur = Mid(MD5String(joueur(IdJoueur).Pseudo), 1, 8)
    Next id
    Close canal
    'enregistré
    Me.Hide
    Unload Me
Else
    'l'authentification a échoué
    CmdOk.Enabled = True
End If


End Sub

Private Sub CmdFermer_Click()
    End
End Sub

Private Sub CmdInscr_Click()
Call OpenBrowser(GameUrl & "/index.php?do=forminscription")
End Sub

Private Sub Command5_Click()

End Sub

Private Sub Form_Initialize()
 InitCommonControls
End Sub

Private Sub Form_Load()
    Call loadformskin
    Call loadplayerlist
    
    Text2.Tag = ""
    
    ShowStatutBar "Téléchargement du message dans 3 secondes."
    Timer1.Enabled = True
End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
If UnloadMode = vbFormControlMenu Then
    End
End If
End Sub
Private Sub loadplayerlist()
Combo1.Clear
Dim id As Integer
Dim lastidjoueur As Integer
Dim last As Integer
lastidjoueur = 0
If FileExists(FichierJoueurNom) Then
    If joueur(1).Pseudo = "" Then
        id = 1
        canal = FreeFile
        Open FichierJoueurNom For Input As #canal
        Do Until EOF(canal)
            If id <> 1 Then ReDim Preserve joueur(1 To id)
            Input #1, joueur(id).Pseudo, joueur(id).Pass, joueur(id).PassLen, last
            If last <> 0 Then lastidjoueur = id - 1
            Combo1.AddItem joueur(id).Pseudo
            id = id + 1
        Loop
        Close canal
    Else
        For id = 1 To UBound(joueur)
            Combo1.AddItem joueur(id).Pseudo
        Next id
    End If
    If Combo1.ListCount >= 1 Then
        Combo1.Text = Combo1.List(lastidjoueur)
        Call Combo1_Click
    End If
 Else
    Combo1.Text = ""
    Combo1.Clear
    'joueur
 End If
End Sub

Private Sub loadformskin()
Dim PoliceCouleur, CouleurBarre As Long
Dim NomPolice As String
Call ChargeSkinFille(Me)
CouleurFond = IniColor("MDIChild", "Fond")
Me.BackColor = CouleurFond
Check1.BackColor = CouleurFond

CmdOk.Picture = LoadPicture(SkinRep & "image\btlogin.bmp")
CmdInscr.Picture = LoadPicture(SkinRep & "image\btinscr.bmp")
CmdFermer.Picture = LoadPicture(SkinRep & "image\btexit.bmp")
CmdConfig.Picture = LoadPicture(SkinRep & "image\btconfig.bmp")
Picture1.Picture = LoadPicture(SkinRep & "image\logonet.bmp")

'CouleurBarre = IniColor("MDIChild", "CouleurSeparationBarre")
'Line2.BorderColor = CouleurBarre
'Line3.BorderColor = CouleurBarre

FondBouton = IniColor("MDIChild", "FondBouton")

CmdOk.BackColor = FondBouton
CmdFermer.BackColor = FondBouton
CmdInscr.BackColor = FondBouton
CmdConfig.BackColor = FondBouton

PoliceCouleur = IniColor("MDIChild", "PoliceCouleur")

Label1.ForeColor = PoliceCouleur
Label2.ForeColor = PoliceCouleur
Label3.ForeColor = PoliceCouleur
Check1.ForeColor = PoliceCouleur

NomPolice = GetIni("MDIChild", "NomPolice")

Label1.FontName = NomPolice
Label2.FontName = NomPolice
Label3.FontName = NomPolice
Check1.FontName = NomPolice

NomPolice = GetIni("MDIChild", "TextBoxPolicenom")

Combo1.FontName = NomPolice
Text2.FontName = NomPolice
Text3.FontName = NomPolice
Text4.FontName = NomPolice

PoliceCouleur = IniColor("MDIChild", "TextBoxCouleurPolice")

Combo1.ForeColor = PoliceCouleur
Text2.ForeColor = PoliceCouleur
Text3.ForeColor = PoliceCouleur
Text4.ForeColor = PoliceCouleur

fondcouleur = IniColor("MDIChild", "TextBoxFond")

Combo1.BackColor = fondcouleur
Text2.BackColor = fondcouleur
Text3.BackColor = fondcouleur
Text4.BackColor = fondcouleur


End Sub

Private Sub Text2_Click()
If Text2.Tag <> "" Then
    Call OpenBrowser(Text2.Tag)
End If
End Sub

Private Sub Text3_KeyPress(KeyAscii As Integer)
If Text3.Tag <> "" Then
    Text3.Tag = ""
    Text3.Text = ""
End If
If KeyAscii = 13 Then
    Call CmdOk_Click
    KeyAscii = 0
End If
End Sub

Private Sub Text4_GotFocus()
CmdOk.SetFocus
End Sub

Private Sub Timer1_Timer()
Timer1.Enabled = False
Dim chaine As String
Dim PopUp As String
chaine = CommNetTrader("mess", "do=infomsg&progver=" & App.Major & "." & App.Minor & "." & App.Revision & "&progtyp=nt2client")
Text2.Text = GetVarXML(chaine, "message")
Text2.Tag = GetVarXML(chaine, "clique")
PopUp = GetVarXML(chaine, "ouverture")
If InStr(PopUp, "http://") > 0 Then
    Call OpenBrowser(PopUp)
End If


End Sub
