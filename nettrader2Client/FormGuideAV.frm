VERSION 5.00
Begin VB.Form FormGuideAV 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Guide Achat / Vente"
   ClientHeight    =   5025
   ClientLeft      =   45
   ClientTop       =   435
   ClientWidth     =   5535
   Icon            =   "FormGuideAV.frx":0000
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   MaxButton       =   0   'False
   MDIChild        =   -1  'True
   MinButton       =   0   'False
   ScaleHeight     =   5025
   ScaleWidth      =   5535
   Begin VB.Frame Frame1 
      Caption         =   "Astuce"
      Height          =   1455
      Left            =   120
      TabIndex        =   19
      Top             =   3480
      Width           =   5295
      Begin VB.PictureBox Picture3 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         BorderStyle     =   0  'None
         ForeColor       =   &H80000008&
         Height          =   1095
         Left            =   120
         ScaleHeight     =   1095
         ScaleWidth      =   5055
         TabIndex        =   20
         Tag             =   "frame"
         Top             =   240
         Width           =   5055
         Begin VB.Label lblTip 
            BackStyle       =   0  'Transparent
            Height          =   1095
            Left            =   0
            TabIndex        =   21
            Top             =   0
            Width           =   5055
         End
      End
   End
   Begin VB.CommandButton CmdFermer 
      Height          =   375
      Left            =   2160
      Style           =   1  'Graphical
      TabIndex        =   8
      Top             =   3000
      Width           =   1215
   End
   Begin VB.CommandButton CmdPrec 
      Enabled         =   0   'False
      Height          =   375
      Left            =   240
      Style           =   1  'Graphical
      TabIndex        =   1
      Top             =   3000
      Width           =   1215
   End
   Begin VB.CommandButton CmdVendre 
      Height          =   375
      Left            =   4080
      Style           =   1  'Graphical
      TabIndex        =   10
      Top             =   3000
      Visible         =   0   'False
      Width           =   1215
   End
   Begin VB.CommandButton CmdAcheter 
      Height          =   375
      Left            =   4080
      Style           =   1  'Graphical
      TabIndex        =   9
      Top             =   3000
      Visible         =   0   'False
      Width           =   1215
   End
   Begin VB.CommandButton CmdSuiv 
      Height          =   375
      Left            =   4080
      Style           =   1  'Graphical
      TabIndex        =   2
      Top             =   3000
      Width           =   1215
   End
   Begin VB.Frame frquant 
      Caption         =   "Quantité"
      Height          =   2895
      Left            =   120
      TabIndex        =   35
      Top             =   0
      Width           =   5295
      Begin VB.TextBox datefinordre 
         Height          =   285
         Left            =   585
         TabIndex        =   47
         Top             =   2535
         Width           =   4335
      End
      Begin VB.PictureBox Curseur 
         Appearance      =   0  'Flat
         AutoSize        =   -1  'True
         BackColor       =   &H80000005&
         BorderStyle     =   0  'None
         ForeColor       =   &H80000008&
         Height          =   315
         Left            =   840
         ScaleHeight     =   315
         ScaleWidth      =   45
         TabIndex        =   45
         Top             =   1680
         Visible         =   0   'False
         Width           =   45
      End
      Begin VB.TextBox txtquantpourc 
         Height          =   285
         Left            =   2640
         TabIndex        =   42
         Text            =   "0"
         Top             =   1800
         Width           =   1335
      End
      Begin VB.TextBox txtquantnbr 
         Height          =   285
         Left            =   90
         TabIndex        =   40
         Text            =   "0"
         Top             =   1800
         Width           =   1215
      End
      Begin VB.PictureBox Picture6 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         BorderStyle     =   0  'None
         ForeColor       =   &H80000008&
         Height          =   495
         Left            =   120
         ScaleHeight     =   495
         ScaleWidth      =   5055
         TabIndex        =   37
         Tag             =   "frame"
         Top             =   390
         Width           =   5055
         Begin VB.OptionButton optquant 
            Caption         =   "Pourcentage"
            Height          =   255
            Index           =   1
            Left            =   3240
            TabIndex        =   39
            Top             =   120
            Width           =   1575
         End
         Begin VB.OptionButton optquant 
            Caption         =   "Nombre"
            Height          =   255
            Index           =   0
            Left            =   120
            TabIndex        =   38
            Top             =   120
            Value           =   -1  'True
            Width           =   1935
         End
      End
      Begin VB.PictureBox Slider 
         Appearance      =   0  'Flat
         AutoRedraw      =   -1  'True
         BackColor       =   &H80000005&
         BorderStyle     =   0  'None
         ForeColor       =   &H80000008&
         Height          =   495
         Index           =   0
         Left            =   1200
         ScaleHeight     =   495
         ScaleWidth      =   2895
         TabIndex        =   36
         Top             =   1035
         Width           =   2895
      End
      Begin VB.Label Label2 
         BackStyle       =   0  'Transparent
         Caption         =   "Date d'expiration de l'ordre :"
         Height          =   255
         Left            =   120
         TabIndex        =   46
         Top             =   2280
         Width           =   2265
      End
      Begin VB.Label Label8 
         BackStyle       =   0  'Transparent
         Caption         =   "% du cashback"
         Height          =   255
         Left            =   4035
         TabIndex        =   43
         Top             =   1830
         Width           =   1095
      End
      Begin VB.Label lblnbraction 
         BackStyle       =   0  'Transparent
         Caption         =   "actions"
         Height          =   255
         Left            =   1335
         TabIndex        =   41
         Top             =   1815
         Width           =   1260
      End
   End
   Begin VB.Frame FrPlage 
      Caption         =   "Plage"
      Height          =   2895
      Left            =   120
      TabIndex        =   27
      Top             =   0
      Width           =   5295
      Begin VB.PictureBox Picture5 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         BorderStyle     =   0  'None
         ForeColor       =   &H80000008&
         Height          =   2535
         Left            =   120
         ScaleHeight     =   2535
         ScaleWidth      =   5055
         TabIndex        =   28
         Tag             =   "frame"
         Top             =   240
         Width           =   5055
         Begin VB.TextBox txtplagefin 
            Height          =   285
            Left            =   120
            TabIndex        =   33
            Top             =   1200
            Width           =   1095
         End
         Begin VB.TextBox txtplagedeb 
            Height          =   285
            Left            =   120
            TabIndex        =   29
            Text            =   "0"
            Top             =   480
            Width           =   1095
         End
         Begin VB.Label Label6 
            BackStyle       =   0  'Transparent
            Caption         =   "€"
            Height          =   255
            Left            =   1305
            TabIndex        =   34
            Top             =   1260
            Width           =   495
         End
         Begin VB.Label Label5 
            BackStyle       =   0  'Transparent
            Caption         =   "Jusqu'à"
            Height          =   255
            Left            =   120
            TabIndex        =   32
            Top             =   840
            Width           =   1695
         End
         Begin VB.Label lvlachatventeplage 
            BackStyle       =   0  'Transparent
            Caption         =   "Achat/Vente de"
            Height          =   255
            Left            =   120
            TabIndex        =   31
            Top             =   120
            Width           =   4815
         End
         Begin VB.Label Label4 
            BackStyle       =   0  'Transparent
            Caption         =   "€"
            Height          =   255
            Left            =   1305
            TabIndex        =   30
            Top             =   540
            Width           =   495
         End
      End
   End
   Begin VB.Frame FrSeuil 
      Caption         =   "Seuil"
      Height          =   2895
      Left            =   120
      TabIndex        =   22
      Top             =   0
      Width           =   5295
      Begin VB.PictureBox Picture4 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         BorderStyle     =   0  'None
         ForeColor       =   &H80000008&
         Height          =   2535
         Left            =   120
         ScaleHeight     =   2535
         ScaleWidth      =   5055
         TabIndex        =   23
         Tag             =   "frame"
         Top             =   240
         Width           =   5055
         Begin VB.TextBox txtseuil 
            Height          =   285
            Left            =   120
            TabIndex        =   25
            Text            =   "0"
            Top             =   480
            Width           =   1095
         End
         Begin VB.Label Label3 
            BackStyle       =   0  'Transparent
            Caption         =   "€"
            Height          =   255
            Left            =   1305
            TabIndex        =   26
            Top             =   540
            Width           =   495
         End
         Begin VB.Label lblAchatVente 
            BackStyle       =   0  'Transparent
            Caption         =   "Achat/Vente à partir de/Jusqu'à"
            Height          =   255
            Left            =   120
            TabIndex        =   24
            Top             =   120
            Width           =   4815
         End
      End
   End
   Begin VB.Frame FrType 
      Caption         =   "Choix du type de l'ordre"
      Height          =   2895
      Left            =   120
      TabIndex        =   0
      Top             =   0
      Width           =   5295
      Begin VB.Frame Frame2 
         Caption         =   "Sens  "
         Height          =   2535
         Left            =   120
         TabIndex        =   3
         Top             =   240
         Width           =   2535
         Begin VB.PictureBox Picture1 
            Appearance      =   0  'Flat
            BackColor       =   &H80000005&
            BorderStyle     =   0  'None
            ForeColor       =   &H80000008&
            Height          =   1935
            Left            =   120
            ScaleHeight     =   1935
            ScaleWidth      =   2295
            TabIndex        =   11
            Tag             =   "frame"
            Top             =   240
            Width           =   2295
            Begin VB.OptionButton OptSens 
               Caption         =   "Vendre à découvert"
               Height          =   255
               Index           =   2
               Left            =   0
               TabIndex        =   14
               ToolTipText     =   "Vendre des actions à découvert"
               Top             =   840
               Width           =   1815
            End
            Begin VB.OptionButton OptSens 
               Caption         =   "Vendre"
               Height          =   255
               Index           =   1
               Left            =   0
               TabIndex        =   13
               ToolTipText     =   "Vendre des actions"
               Top             =   480
               Width           =   1695
            End
            Begin VB.OptionButton OptSens 
               Caption         =   "Acheter"
               Height          =   255
               Index           =   0
               Left            =   0
               TabIndex        =   12
               ToolTipText     =   "Acheter des actions"
               Top             =   120
               Value           =   -1  'True
               Width           =   1695
            End
         End
      End
      Begin VB.Frame Frame3 
         Caption         =   "Condition d'execution              "
         Height          =   2535
         Left            =   2760
         TabIndex        =   4
         Top             =   240
         Width           =   2415
         Begin VB.PictureBox Picture2 
            Appearance      =   0  'Flat
            BackColor       =   &H80000005&
            BorderStyle     =   0  'None
            ForeColor       =   &H80000008&
            Height          =   2175
            Left            =   120
            ScaleHeight     =   2175
            ScaleWidth      =   2055
            TabIndex        =   15
            Tag             =   "frame"
            Top             =   240
            Width           =   2055
            Begin VB.OptionButton OptCondValeur 
               Caption         =   "A tout prix"
               Height          =   255
               Index           =   0
               Left            =   0
               TabIndex        =   18
               ToolTipText     =   "Acheter/Vendre à n'importe quel prix"
               Top             =   120
               Value           =   -1  'True
               Width           =   1215
            End
            Begin VB.OptionButton OptCondValeur 
               Caption         =   "A seuil de valeur"
               Height          =   255
               Index           =   1
               Left            =   0
               TabIndex        =   17
               ToolTipText     =   "Acheter jusqu'a une certaine valeur - Vendre à partir d'une certaine valeur"
               Top             =   480
               Width           =   1695
            End
            Begin VB.OptionButton OptCondValeur 
               Caption         =   "A plage de valeur"
               Height          =   255
               Index           =   2
               Left            =   0
               TabIndex        =   16
               ToolTipText     =   "A/V à partir et jusqu'a une certaine valeur"
               Top             =   840
               Width           =   1575
            End
         End
      End
   End
   Begin VB.Frame FrSelAct 
      Caption         =   "Sélection de l'action"
      Height          =   2895
      Left            =   120
      TabIndex        =   5
      Top             =   0
      Visible         =   0   'False
      Width           =   5295
      Begin VB.CommandButton cmdgetprofil 
         Height          =   405
         Left            =   2160
         Style           =   1  'Graphical
         TabIndex        =   44
         Top             =   1605
         Width           =   1215
      End
      Begin VB.ComboBox CmbAction 
         Height          =   315
         Left            =   540
         TabIndex        =   6
         Text            =   "Sélectionnez une valeur"
         Top             =   600
         Width           =   4455
      End
      Begin VB.Label Label1 
         BackStyle       =   0  'Transparent
         Caption         =   "Valeur :"
         Height          =   255
         Left            =   105
         TabIndex        =   7
         Top             =   240
         Width           =   1095
      End
   End
End
Attribute VB_Name = "FormGuideAV"
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

Private Type Slider
    MouseDown As Boolean
    value As Double
    Max As Integer
    Pourc As Boolean
    CurseurY As Integer
    CurseurXMin As Integer
    CurseurXMax As Integer
    CurseurDiff As Integer
End Type



Private Type Infoaction
    codesico As Long
    nbportef As Integer
    value As Double
    achatmax As Integer
    ventemax As Integer
    finjour As String
End Type

Dim Slidervar(0) As Slider
Private CurrentInfoAction As Infoaction
Private TabLstActions As Variant
Private TabPortef As Variant
Dim Etape As String

Dim Lordre As Tordre

Private Sub ChargeListeAchat()
Dim data As String
Dim TabLst As Variant
Dim i As Integer
CmbAction.Clear

data = CommNetTrader("getportef", "do=lstactionsachat")
If GetVarXML(data, "erreur") = "faux" Then
    XmlLstActions = data
    Champs = Array("codesicav", "nomsicav", "valeur") 'champs des données à recuperer
    TabLstActions = GetTabXML(data, "actions", "action", Champs) 'on transforme le xml en tableau
    For i = 0 To UBound(TabLstActions, 1)
        CmbAction.AddItem TabLstActions(i, 1)
    Next i
    finjour = GetVarXML(data, "finjour")
Else
    ShowStatutBar ("Impossible de récuperer la liste des actions.")
End If

End Sub
Private Sub fillactioninfo(codesico As Long)
Dim data As String
data = CommNetTrader("getinfoaction", "do=getinfoaction&codesico=" & codesico)
CurrentInfoAction.achatmax = Int(GetVarXML(data, "nbactionmaxachat"))
CurrentInfoAction.nbportef = Int(GetVarXML(data, "quantportef"))
CurrentInfoAction.ventemax = Int(GetVarXML(data, "nbactionmaxvente"))
CurrentInfoAction.value = CDbl("0" & GetVarXML(data, "valeur"))
CurrentInfoAction.codesico = codesico
CurrentInfoAction.finjour = GetVarXML(data, "finjour")
End Sub





Private Sub CmbAction_LostFocus()
Call controleSortie(CmbAction)
End Sub

Private Sub CmdAcheter_Click()
Dim data As String
Dim LimitMax As String
Dim typeach As Integer
Dim quantperc As Integer
Dim quant As Integer
Lordre.Qte = txtquantnbr.Text
Lordre.Prc = txtquantpourc.Text
Lordre.DateLim = datefinordre.Text


If (Lordre.Qte > 0 And optquant(0).value) Or (Lordre.Prc > 0 And optquant(1).value) Then
    quant = Lordre.Qte
    quantperc = 0
    If optquant(0).value = True Then
        typeach = 1
    Else
        quantperc = Lordre.Prc
        typeach = 0
    End If
    data = CommNetTrader("envoiordre", "do=sendachatvente&sens=achat&codesicav=" & Lordre.codesico & "&nbr=" & quant & "&valmin=" & Replace(Lordre.CoursMin, ",", ".") & "&valmax=" & Replace(Lordre.CoursMax, ",", ".") & "&tempsmin=" & Lordre.DateLim & "&select=" & typeach & "&ansval=&seuil=1&nb2=" & quantperc & "&pourc=" & quantperc)
    If GetVarXML(data, "erreur") = "faux" Then
        ShowStatutBar GetVarXML(data, "messageordre")
        GlobalState.DoUpdateHisto = True
        GlobalState.DoUpdatePortef = True
        Me.Hide
        Unload Me
    Else
        ShowStatutBar "L'envoi de l'ordre n'a pas pu se faire."
    End If
Else
    ShowStatutBar "Vous devez acheter plus que 0 actions"
End If
End Sub

Private Sub CmdFermer_Click()

Me.Hide
Unload Me

End Sub

Private Sub cmdgetprofil_Click()
Dim codesico  As String, data  As String, urlhelp As String
Indice = ChercherTab(TabLstActions, 1, CmbAction.Text) 'va retourner l'identifiant du tableau
If Indice <> -1 Then
    codesico = TabLstActions(Indice, 0)
    data = CommNetTrader("getlienprofilaction", "do=getlienprofilaction&codesico=" & codesico)
    urlhelp = GetVarXML(data, "urlaide")
    Call OpenBrowser(urlhelp)
Else
    ShowStatutBar "Veuillez sélectionner une action !"
End If
End Sub

Private Sub CmdPrec_Click()
Select Case Etape
    Case "SelectType"
        Call etapechoixaction
    Case "SelectPlage"
        Call etapechoixtype
    Case "SelectSeuil"
        Call etapechoixtype
    Case "SelectQuant"
        CmdSuiv.Enabled = True
        CmdSuiv.Visible = True
        CmdAcheter.Visible = False
        CmdVendre.Visible = False
        If Lordre.Cond = "atp" Then
            Call etapechoixtype
        ElseIf Lordre.Cond = "aseuil" Then
            Call etapechoixseuil
        Else
            Call etapechoixplage
        End If
        
End Select
End Sub
Private Sub etapechoixaction()
Call subfermertout
CmdPrec.Enabled = False
Etape = "SelectAction"
FrSelAct.Visible = True
End Sub
Private Sub etapechoixtype()
Dim i As Integer

Call subfermertout
CmdPrec.Enabled = True
Etape = "SelectType"
If joueur(IdJoueur).Vad Then
    OptSens(2).Visible = True
    OptSens(2).value = True
Else
    OptSens(2).Visible = False
End If
If CurrentInfoAction.nbportef > 0 And CurrentInfoAction.ventemax > 0 Then
    OptSens(1).Visible = True
    OptSens(1).value = True
Else
    OptSens(1).Visible = False
End If
If CurrentInfoAction.achatmax > 0 Then
    OptSens(0).Visible = True
    OptSens(0).value = True
Else
    OptSens(0).Visible = False
    ShowStatutBar "Vous n'avez pas assez de liquiditées pour acheter."
End If

FrType.Visible = True
End Sub
Private Sub etapechoixseuil()
Call subfermertout
Etape = "SelectSeuil"
If Lordre.Sens = "achat" Then
    lblAchatVente.Caption = "Achat jusqu'à"
    txtseuil.Text = CurrentInfoAction.value
Else
    lblAchatVente.Caption = "Vente à partir de"
    txtseuil.Text = CurrentInfoAction.value
End If
FrSeuil.Visible = True
End Sub
Private Sub etapechoixplage()
Static dejapasse As Boolean

Call subfermertout
Etape = "SelectPlage"
If Lordre.Sens = "achat" Then
    lvlachatventeplage.Caption = "Achat de"
    If Not dejapasse Then
        If CurrentInfoAction.nbportef >= 0 Then
            txtplagedeb.Text = "0"
            txtplagefin.Text = CurrentInfoAction.value
        Else
            txtplagedeb.Text = CurrentInfoAction.value
            txtplagefin.Text = "-1"
        End If
    End If
Else
    lvlachatventeplage.Caption = "Vente de"
    If Not dejapasse Then
        txtplagedeb.Text = CurrentInfoAction.value
        txtplagefin.Text = "-1"
    End If
End If

FrPlage.Visible = True
dejapasse = True
End Sub
Private Sub etapechoixquant()
Call subfermertout
Etape = "SelectQuant"
txtquantpourc.Enabled = False
txtquantnbr.Enabled = True
Call InitSlider(0)
datefinordre.Text = CurrentInfoAction.finjour
If Lordre.Sens = "achat" Then
    If CurrentInfoAction.nbportef >= 0 Then
        Slidervar(0).Max = CurrentInfoAction.achatmax
    Else
        If Abs(CurrentInfoAction.nbportef) <= CurrentInfoAction.achatmax Then
            Slidervar(0).Max = Abs(CurrentInfoAction.nbportef)
        Else
            Slidervar(0).Max = CurrentInfoAction.achatmax
        End If
    End If
    Call SliderMove(0, Slider(0).Width)
Else
    Slidervar(0).Max = CurrentInfoAction.ventemax
End If
Call SliderMove(0, 0)
  
CmdSuiv.Enabled = False
CmdSuiv.Visible = False
If Lordre.Sens = "achat" Then
    CmdAcheter.Visible = True
Else
    CmdVendre.Visible = True
End If
frquant.Visible = True
End Sub
Private Sub subfermertout()
FrSelAct.Visible = False
FrType.Visible = False
FrSeuil.Visible = False
FrPlage.Visible = False
frquant.Visible = False
End Sub
Private Sub InitSlider(idslider As Integer)
Slidervar(idslider).value = 0

Slidervar(idslider).CurseurY = CInt((Slider(idslider).Height / 2) - (Curseur.Height / 2))
Slidervar(idslider).CurseurXMin = CInt(187 - (Curseur.Width / 2))
Slidervar(idslider).CurseurXMax = CInt(Slider(idslider).Width - 187 - (Curseur.Width / 2))
Slidervar(idslider).CurseurDiff = CInt(Curseur.Width / 2)
Slidervar(idslider).Max = 100
End Sub
Private Sub CmdSuiv_Click()
Select Case Etape
    Case "SelectType"
        'Verification champs
        'enregistrement de l'ordre
        If OptSens(0).value = True Then Lordre.Sens = "achat"
        If OptSens(1).value = True Then Lordre.Sens = "vente"
        If OptSens(2).value = True Then Lordre.Sens = "venteadecouvert"
        If OptCondValeur(0).value = True Then Lordre.Cond = "atp"
        If OptCondValeur(1).value = True Then Lordre.Cond = "aseuil"
        If OptCondValeur(2).value = True Then Lordre.Cond = "aplage"
        'dechargement de l'ex feuille
        If Lordre.Cond = "atp" Then
            Lordre.CoursMax = -1
            Lordre.CoursMin = 0
            Call etapechoixquant
        ElseIf Lordre.Cond = "aseuil" Then
            Call etapechoixseuil
        Else
            Call etapechoixplage
        End If
    Case "SelectAction"
        Indice = ChercherTab(TabLstActions, 1, CmbAction.Text) 'va retourner l'identifiant du tableau
        If Indice <> -1 Then
            Lordre.codesico = TabLstActions(Indice, 0)
            Call fillactioninfo(Lordre.codesico)
            Call etapechoixtype
        Else
            ShowStatutBar "Impossible de télécharger les informations de cette action"
        End If
    Case "SelectPlage"
        Lordre.CoursMin = txtplagedeb.Text
        Lordre.CoursMax = txtplagefin.Text
        Call etapechoixquant
    Case "SelectSeuil"
        Lordre.CoursMax = -1
        Lordre.CoursMin = 0
        If Lordre.Sens = "achat" Then Lordre.CoursMax = txtseuil.Text
        If Lordre.Sens = "vente" Or Lordre.Sens = "venteadecouvert" Then Lordre.CoursMin = txtseuil.Text
        Call etapechoixquant
End Select
End Sub
Private Function CheckForm() As Boolean 'vrai = correct
'on verifie les champs
Select Case Etape
Case "SelectType"
    CheckForm = True
Case "SelecAction"


End Select

End Function

Private Sub Command1_Click()

End Sub

Private Sub CmdVendre_Click()
Dim data As String
Dim LimitMax As String
Dim typeach As Integer
Dim quantperc As Integer
Dim quant As Integer
Lordre.Qte = txtquantnbr.Text
Lordre.Prc = txtquantpourc.Text
Lordre.DateLim = datefinordre.Text



If (Lordre.Qte > 0 And optquant(0).value) Or (Lordre.Prc > 0 And optquant(1).value) Then
    quant = Lordre.Qte
    quantperc = 0
    If optquant(0).value = True Then
        typeach = 1
    Else
        quantperc = Lordre.Prc
        typeach = 0
    End If
    data = CommNetTrader("envoiordre", "do=sendachatvente&sens=vente&codesicav=" & Lordre.codesico & "&nbr=" & quant & "&valmin=" & Replace(Lordre.CoursMin, ",", ".") & "&valmax=" & Replace(Lordre.CoursMax, ",", ".") & "&tempsmin=" & Lordre.DateLim & "&select=" & typeach & "&ansval=&seuil=1&nb2=" & quantperc & "&pourc=" & quantperc)
    If GetVarXML(data, "erreur") = "faux" Then
        ShowStatutBar GetVarXML(data, "messageordre")
        GlobalState.DoUpdateHisto = True
        GlobalState.DoUpdatePortef = True
        Me.Hide
        Unload Me
    Else
        ShowStatutBar "L'envoi de l'ordre n'a pas pu se faire."
    End If
Else
    ShowStatutBar "Vous devez acheter plus que 0 actions"
End If
End Sub

Private Sub Form_Load()
Call LoadSkin
Call ChargeListeAchat
Call StartAV

End Sub
Public Sub StartAV()
Call subfermertout
Call etapechoixaction

End Sub


Private Sub LoadSkin()
'charge les images des boutons
CmdFermer.Picture = LoadPicture(SkinRep & "image\btfermer.bmp")
CmdAcheter.Picture = LoadPicture(SkinRep & "image\btacheter.bmp")
CmdVendre.Picture = LoadPicture(SkinRep & "image\btvendre.bmp")
CmdPrec.Picture = LoadPicture(SkinRep & "image\btprecedent.bmp")
CmdSuiv.Picture = LoadPicture(SkinRep & "image\btsuivant.bmp")
cmdgetprofil.Picture = LoadPicture(SkinRep & "image\btdetails.bmp")




Slider(0).Picture = LoadPicture(SkinRep & "image\FondSLider.bmp")
Curseur.Picture = LoadPicture(SkinRep & "image\BoutonSlider.bmp")

Call ChargeSkinFille(Me) 'charge les couleurs generiques (communes a toutes les feuilles)
End Sub

Private Sub SliderMove(idslider As Integer, XCoord As Integer)


Slider(idslider).Cls
If XCoord - Slidervar(idslider).CurseurDiff >= Slidervar(idslider).CurseurXMin Then
    If XCoord - Slidervar(idslider).CurseurDiff <= Slidervar(idslider).CurseurXMax Then
        If Slidervar(idslider).Pourc = False Then
            Slidervar(idslider).value = Round(((XCoord - Slidervar(idslider).CurseurDiff) / Slidervar(idslider).CurseurXMax) * Slidervar(idslider).Max)
            x = Round((Slidervar(idslider).value / Slidervar(idslider).Max) * Slidervar(idslider).CurseurXMax)
        Else
            x = XCoord - Slidervar(idslider).CurseurDiff
            Slidervar(idslider).value = Round(((XCoord - Slidervar(idslider).CurseurDiff) / Slidervar(idslider).CurseurXMax) * 100)
        End If
    Else
        If Slidervar(idslider).Pourc = False Then
            Slidervar(idslider).value = Slidervar(idslider).Max
        Else
            Slidervar(idslider).value = 100
        End If
        x = Slidervar(idslider).CurseurXMax
    End If
Else
    Slidervar(idslider).value = 0
    x = Slidervar(idslider).CurseurXMin
End If

Slider(idslider).PaintPicture Curseur, x, Slidervar(idslider).CurseurY


End Sub

Private Sub optquant_Click(index As Integer)
If index = 0 Then
    txtquantpourc.Enabled = False
    txtquantnbr.Enabled = True
    Slidervar(0).Pourc = False
Else
    txtquantnbr.Enabled = False
    txtquantpourc.Enabled = True
    Slidervar(0).Pourc = True
End If
End Sub

Private Sub Slider_LostFocus(index As Integer)
Slidervar(index).MouseDown = False
End Sub

Private Sub Slider_MouseDown(index As Integer, Button As Integer, Shift As Integer, x As Single, y As Single)
Slidervar(index).MouseDown = True
Call SliderMove(index, CInt(x))
Call setvaleur(index, Slidervar(index).value)
End Sub
Private Sub setvaleur(index As Integer, value As Double)
    If index = 0 Then
        If optquant(0).value = True Then
            txtquantnbr.Text = value
        Else
            txtquantpourc.Text = value
        End If
    End If
End Sub
Private Sub Slider_MouseMove(index As Integer, Button As Integer, Shift As Integer, x As Single, y As Single)
If Slidervar(index).MouseDown = True Then
    Call SliderMove(index, CInt(x))
    Call setvaleur(index, Slidervar(index).value)

End If

End Sub

Private Sub Slider_MouseUp(index As Integer, Button As Integer, Shift As Integer, x As Single, y As Single)
Slidervar(index).MouseDown = False
End Sub
