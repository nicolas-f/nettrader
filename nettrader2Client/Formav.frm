VERSION 5.00
Object = "{5E9E78A0-531B-11CF-91F6-C2863C385E30}#1.0#0"; "MSFLXGRD.OCX"
Begin VB.Form Formav 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Acheter et vendre des actions"
   ClientHeight    =   7425
   ClientLeft      =   45
   ClientTop       =   360
   ClientWidth     =   6585
   Icon            =   "Formav.frx":0000
   LinkTopic       =   "Form1"
   LockControls    =   -1  'True
   MaxButton       =   0   'False
   MDIChild        =   -1  'True
   MinButton       =   0   'False
   ScaleHeight     =   7425
   ScaleWidth      =   6585
   Begin VB.Timer Timer1 
      Interval        =   1000
      Left            =   2655
      Top             =   3915
   End
   Begin VB.CommandButton cmdguide 
      Height          =   450
      Left            =   3960
      Style           =   1  'Graphical
      TabIndex        =   45
      Top             =   4080
      Width           =   2500
   End
   Begin VB.CommandButton CmdReload 
      Height          =   450
      Left            =   3960
      Style           =   1  'Graphical
      TabIndex        =   30
      Top             =   3600
      Width           =   1215
   End
   Begin VB.PictureBox onglet2 
      Height          =   495
      Left            =   6120
      ScaleHeight     =   435
      ScaleWidth      =   675
      TabIndex        =   15
      Top             =   3960
      Visible         =   0   'False
      Width           =   735
   End
   Begin VB.PictureBox onglet1 
      Height          =   375
      Left            =   6240
      ScaleHeight     =   315
      ScaleWidth      =   915
      TabIndex        =   14
      Top             =   3960
      Visible         =   0   'False
      Width           =   975
   End
   Begin VB.CommandButton Command3 
      Height          =   450
      Left            =   5280
      Style           =   1  'Graphical
      TabIndex        =   13
      Top             =   3600
      Width           =   1215
   End
   Begin VB.PictureBox Curseur 
      Appearance      =   0  'Flat
      AutoSize        =   -1  'True
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   315
      Left            =   120
      ScaleHeight     =   315
      ScaleWidth      =   45
      TabIndex        =   11
      Top             =   3120
      Visible         =   0   'False
      Width           =   45
   End
   Begin VB.Frame Frame2 
      Caption         =   "Vendre"
      Height          =   2775
      Left            =   3360
      TabIndex        =   4
      Top             =   4545
      Visible         =   0   'False
      Width           =   3135
      Begin VB.PictureBox Picture3 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         BorderStyle     =   0  'None
         ForeColor       =   &H80000008&
         Height          =   525
         Left            =   2325
         ScaleHeight     =   525
         ScaleWidth      =   765
         TabIndex        =   50
         Tag             =   "frame"
         Top             =   1125
         Width           =   765
         Begin VB.OptionButton lblvente 
            Caption         =   "actions"
            Height          =   195
            Index           =   1
            Left            =   0
            TabIndex        =   52
            Top             =   225
            Value           =   -1  'True
            Visible         =   0   'False
            Width           =   855
         End
         Begin VB.OptionButton lblvente 
            Caption         =   "%"
            Height          =   195
            Index           =   0
            Left            =   0
            TabIndex        =   51
            Top             =   0
            Visible         =   0   'False
            Width           =   735
         End
      End
      Begin VB.TextBox SeuilVenteFin 
         Height          =   285
         Left            =   960
         TabIndex        =   35
         Text            =   "-1"
         Top             =   1920
         Visible         =   0   'False
         Width           =   615
      End
      Begin VB.ComboBox CmbTypeVente 
         Height          =   315
         ItemData        =   "Formav.frx":0E42
         Left            =   120
         List            =   "Formav.frx":0E4F
         TabIndex        =   29
         Text            =   "Choisir type"
         Top             =   1200
         Width           =   1215
      End
      Begin VB.TextBox ExpVente 
         Height          =   285
         Left            =   1080
         TabIndex        =   27
         Text            =   "Text1"
         Top             =   2280
         Visible         =   0   'False
         Width           =   1935
      End
      Begin VB.TextBox SeuilVente 
         Height          =   285
         Left            =   960
         TabIndex        =   24
         Text            =   "0"
         Top             =   1560
         Visible         =   0   'False
         Width           =   615
      End
      Begin VB.TextBox nbvente 
         Height          =   285
         Left            =   1440
         TabIndex        =   16
         Text            =   "0"
         Top             =   1200
         Visible         =   0   'False
         Width           =   855
      End
      Begin VB.PictureBox Slider 
         Appearance      =   0  'Flat
         AutoRedraw      =   -1  'True
         BackColor       =   &H80000005&
         BorderStyle     =   0  'None
         ForeColor       =   &H80000008&
         Height          =   495
         Index           =   1
         Left            =   120
         ScaleHeight     =   495
         ScaleWidth      =   2895
         TabIndex        =   12
         Top             =   600
         Visible         =   0   'False
         Width           =   2895
      End
      Begin VB.CommandButton CmdVente 
         Enabled         =   0   'False
         Height          =   420
         Left            =   1800
         Style           =   1  'Graphical
         TabIndex        =   9
         Top             =   1680
         Visible         =   0   'False
         Width           =   1215
      End
      Begin VB.ComboBox lstActionsVentes 
         Height          =   315
         Left            =   120
         TabIndex        =   6
         Text            =   "Séléctionnez une action"
         Top             =   240
         Width           =   2895
      End
      Begin VB.Label lblseuilvente 
         BackStyle       =   0  'Transparent
         Caption         =   "€"
         Height          =   255
         Index           =   3
         Left            =   1680
         TabIndex        =   36
         Top             =   1920
         Visible         =   0   'False
         Width           =   135
      End
      Begin VB.Label lblseuilvente 
         Alignment       =   1  'Right Justify
         BackStyle       =   0  'Transparent
         Caption         =   "Jusqu'a"
         Height          =   255
         Index           =   2
         Left            =   120
         TabIndex        =   34
         Top             =   1920
         Visible         =   0   'False
         Width           =   735
      End
      Begin VB.Label lbldatevente 
         BackStyle       =   0  'Transparent
         Caption         =   "Expiration le"
         Height          =   255
         Left            =   120
         TabIndex        =   26
         Top             =   2295
         Visible         =   0   'False
         Width           =   855
      End
      Begin VB.Label lblseuilvente 
         BackStyle       =   0  'Transparent
         Caption         =   "€"
         Height          =   255
         Index           =   1
         Left            =   1680
         TabIndex        =   25
         Top             =   1560
         Visible         =   0   'False
         Width           =   135
      End
      Begin VB.Label lblseuilvente 
         Alignment       =   1  'Right Justify
         BackStyle       =   0  'Transparent
         Caption         =   "A partir de"
         Height          =   255
         Index           =   0
         Left            =   120
         TabIndex        =   23
         Top             =   1560
         Visible         =   0   'False
         Width           =   735
      End
   End
   Begin VB.Frame Frame1 
      Caption         =   "Acheter"
      Height          =   2775
      Left            =   120
      TabIndex        =   3
      Top             =   4545
      Visible         =   0   'False
      Width           =   3135
      Begin VB.PictureBox Picture6 
         Appearance      =   0  'Flat
         BackColor       =   &H80000005&
         BorderStyle     =   0  'None
         ForeColor       =   &H80000008&
         Height          =   510
         Left            =   2175
         ScaleHeight     =   510
         ScaleWidth      =   915
         TabIndex        =   46
         Tag             =   "frame"
         Top             =   1125
         Width           =   915
         Begin VB.OptionButton lblachat 
            Caption         =   "%"
            Height          =   225
            Index           =   0
            Left            =   0
            TabIndex        =   49
            Top             =   0
            Visible         =   0   'False
            Width           =   735
         End
         Begin VB.OptionButton lblachat 
            Caption         =   "actions"
            Height          =   195
            Index           =   1
            Left            =   0
            TabIndex        =   48
            Top             =   240
            Value           =   -1  'True
            Visible         =   0   'False
            Width           =   855
         End
         Begin VB.OptionButton optquant 
            Caption         =   "Pourcentage"
            Height          =   255
            Index           =   1
            Left            =   3240
            TabIndex        =   47
            Top             =   120
            Width           =   1575
         End
      End
      Begin VB.TextBox SeuilAchatDeb 
         Height          =   285
         Left            =   840
         TabIndex        =   32
         Text            =   "0"
         Top             =   1560
         Visible         =   0   'False
         Width           =   615
      End
      Begin VB.ComboBox CmbTypeAchat 
         Height          =   315
         ItemData        =   "Formav.frx":0E77
         Left            =   120
         List            =   "Formav.frx":0E84
         TabIndex        =   28
         Text            =   "Choisir type"
         Top             =   1200
         Visible         =   0   'False
         Width           =   1215
      End
      Begin VB.TextBox ExpAchat 
         Height          =   285
         Left            =   1080
         TabIndex        =   22
         Top             =   2280
         Visible         =   0   'False
         Width           =   1935
      End
      Begin VB.TextBox SeuilAchat 
         Height          =   285
         Left            =   840
         TabIndex        =   19
         Text            =   "-1"
         Top             =   1920
         Visible         =   0   'False
         Width           =   615
      End
      Begin VB.TextBox nbachat 
         Height          =   285
         Left            =   1440
         TabIndex        =   10
         Text            =   "0"
         Top             =   1200
         Visible         =   0   'False
         Width           =   705
      End
      Begin VB.CommandButton CmdAchat 
         Height          =   420
         Left            =   1800
         Style           =   1  'Graphical
         TabIndex        =   8
         Top             =   1680
         Visible         =   0   'False
         Width           =   1215
      End
      Begin VB.PictureBox Slider 
         Appearance      =   0  'Flat
         AutoRedraw      =   -1  'True
         BackColor       =   &H80000005&
         BorderStyle     =   0  'None
         ForeColor       =   &H80000008&
         Height          =   495
         Index           =   0
         Left            =   120
         ScaleHeight     =   495
         ScaleWidth      =   2895
         TabIndex        =   7
         Top             =   600
         Visible         =   0   'False
         Width           =   2895
      End
      Begin VB.ComboBox lstActionsAchat 
         Height          =   315
         Left            =   120
         TabIndex        =   5
         Text            =   "Séléctionnez une action"
         Top             =   240
         Width           =   2895
      End
      Begin VB.Label lblseuilachat 
         BackStyle       =   0  'Transparent
         Caption         =   "€"
         Height          =   255
         Index           =   3
         Left            =   1560
         TabIndex        =   33
         Top             =   1560
         Visible         =   0   'False
         Width           =   135
      End
      Begin VB.Label lblseuilachat 
         Alignment       =   1  'Right Justify
         BackStyle       =   0  'Transparent
         Caption         =   "De"
         Height          =   255
         Index           =   2
         Left            =   120
         TabIndex        =   31
         Top             =   1560
         Visible         =   0   'False
         Width           =   615
      End
      Begin VB.Label lbldateachat 
         BackStyle       =   0  'Transparent
         Caption         =   "Expiration le"
         Height          =   255
         Left            =   120
         TabIndex        =   21
         Top             =   2295
         Visible         =   0   'False
         Width           =   975
      End
      Begin VB.Label lblseuilachat 
         BackStyle       =   0  'Transparent
         Caption         =   "€"
         Height          =   255
         Index           =   1
         Left            =   1560
         TabIndex        =   20
         Top             =   1920
         Visible         =   0   'False
         Width           =   135
      End
      Begin VB.Label lblseuilachat 
         Alignment       =   1  'Right Justify
         BackStyle       =   0  'Transparent
         Caption         =   "Jusqu'a"
         Height          =   255
         Index           =   0
         Left            =   120
         TabIndex        =   18
         Top             =   1920
         Visible         =   0   'False
         Width           =   615
      End
   End
   Begin VB.PictureBox Picture2 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   2895
      Left            =   120
      ScaleHeight     =   2895
      ScaleWidth      =   6375
      TabIndex        =   1
      Top             =   600
      Width           =   6375
      Begin MSFlexGridLib.MSFlexGrid FGOrdres 
         Height          =   2895
         Left            =   0
         TabIndex        =   17
         Top             =   0
         Visible         =   0   'False
         Width           =   6375
         _ExtentX        =   11245
         _ExtentY        =   5106
         _Version        =   393216
         Cols            =   7
         FixedCols       =   0
         BackColorBkg    =   16777215
         SelectionMode   =   1
         AllowUserResizing=   1
         BorderStyle     =   0
         Appearance      =   0
      End
      Begin MSFlexGridLib.MSFlexGrid FGPortef 
         Height          =   2895
         Left            =   0
         TabIndex        =   2
         Top             =   0
         Width           =   6375
         _ExtentX        =   11245
         _ExtentY        =   5106
         _Version        =   393216
         Cols            =   5
         FixedCols       =   0
         BackColorFixed  =   -2147483635
         BackColorBkg    =   16777215
         SelectionMode   =   1
         AllowUserResizing=   1
         BorderStyle     =   0
         Appearance      =   0
      End
   End
   Begin VB.PictureBox Picture1 
      Appearance      =   0  'Flat
      BackColor       =   &H80000005&
      BorderStyle     =   0  'None
      ForeColor       =   &H80000008&
      Height          =   495
      Left            =   120
      ScaleHeight     =   495
      ScaleWidth      =   6375
      TabIndex        =   0
      Top             =   120
      Width           =   6375
   End
   Begin VB.Label lblcapital 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "10000"
      Height          =   195
      Left            =   1200
      TabIndex        =   44
      Top             =   4200
      Width           =   450
   End
   Begin VB.Label Label2 
      BackStyle       =   0  'Transparent
      Caption         =   "Capital"
      Height          =   255
      Left            =   120
      TabIndex        =   43
      Top             =   4200
      Width           =   855
   End
   Begin VB.Label lblcash 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "Cashback"
      Height          =   195
      Left            =   120
      TabIndex        =   42
      Top             =   3960
      Width           =   720
   End
   Begin VB.Label Cashback 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "10000 €"
      Height          =   195
      Left            =   1200
      TabIndex        =   41
      Top             =   3960
      Width           =   585
   End
   Begin VB.Label Label1 
      BackStyle       =   0  'Transparent
      Caption         =   "Portefeuille"
      Height          =   255
      Left            =   120
      TabIndex        =   40
      Top             =   3480
      Width           =   975
   End
   Begin VB.Label lblportef 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "fffff"
      Height          =   195
      Left            =   1200
      TabIndex        =   39
      Top             =   3480
      Width           =   225
   End
   Begin VB.Label lbllibbenef 
      BackStyle       =   0  'Transparent
      Caption         =   "Plus-value"
      Height          =   255
      Left            =   120
      TabIndex        =   38
      Top             =   3720
      Width           =   855
   End
   Begin VB.Label lblBenef 
      AutoSize        =   -1  'True
      BackStyle       =   0  'Transparent
      Caption         =   "500"
      Height          =   195
      Left            =   1200
      TabIndex        =   37
      Top             =   3720
      Width           =   225
   End
End
Attribute VB_Name = "Formav"
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

Dim Slidervar(0 To 1) As Slider
Private EtatAchat As String
Private EtatVente As String
Private XmlPortef As String
Private XmlLstActions As String
Private TabLstActions As Variant
Private TabPortef As Variant
Private TabOrdre As Variant
Dim finjour As String
Const TxtPasAction As String = "Veuillez choisir une action"


Private Sub ChgVisibleSeuilAchat(Etat As Boolean)
'affichage
lblseuilachat(0).Visible = Etat
lblseuilachat(1).Visible = Etat

lbldateachat.Visible = Etat
ExpAchat.Visible = Etat
SeuilAchat.Visible = Etat


'initialisation si vers vrai




End Sub
Private Sub ChgVisiblePlageAchat(Etat As Boolean)
'affichage
lblseuilachat(0).Visible = Etat
lblseuilachat(1).Visible = Etat
lblseuilachat(2).Visible = Etat
lblseuilachat(3).Visible = Etat

lbldateachat.Visible = Etat
ExpAchat.Visible = Etat
SeuilAchat.Visible = Etat
SeuilAchatDeb.Visible = Etat
SeuilAchatDeb.Visible = Etat
'initialisation si vers vrai




End Sub
Private Sub ChgVisibleAToutPrixAchat(Etat As Boolean)
'affichage
ExpAchat.Visible = Etat
lbldateachat.Visible = Etat



End Sub
Private Sub ChgVisibleSeuilVente(Etat As Boolean)
'affichage
lblseuilvente(0).Visible = Etat
lblseuilvente(1).Visible = Etat

lbldatevente.Visible = Etat
ExpVente.Visible = Etat
SeuilVente.Visible = Etat


'initialisation si vers vrai




End Sub
Private Sub ChgVisiblePlageVente(Etat As Boolean)
'affichage
lblseuilvente(0).Visible = Etat
lblseuilvente(1).Visible = Etat
lblseuilvente(2).Visible = Etat
lblseuilvente(3).Visible = Etat

lbldatevente.Visible = Etat
ExpVente.Visible = Etat
SeuilVente.Visible = Etat
SeuilVenteFin.Visible = Etat
SeuilVenteFin.Visible = Etat
'initialisation si vers vrai




End Sub
Private Sub ChgVisibleAToutPrixVente(Etat As Boolean)
'affichage

ExpVente.Visible = Etat
lbldatevente.Visible = Etat


End Sub
Private Sub ChargeListeAchat()
Dim data As String
Dim TabLst As Variant
Dim i As Integer
lstActionsAchat.Clear

data = CommNetTrader("getportef", "do=lstactionsachat")
If GetVarXML(data, "erreur") = "faux" Then
    XmlLstActions = data
    Champs = Array("codesicav", "nomsicav", "valeur") 'champs des données à recuperer
    TabLstActions = GetTabXML(data, "actions", "action", Champs) 'on transforme le xml en tableau
    For i = 0 To UBound(TabLstActions, 1)
        lstActionsAchat.AddItem TabLstActions(i, 1)
        If joueur(IdJoueur).Vad = True Then lstActionsVentes.AddItem TabLstActions(i, 1)
    Next i
    finjour = GetVarXML(data, "finjour")
Else
    ShowStatutBar ("Impossible de récuperer la liste des actions.")
End If

lstActionsAchat.Text = TxtPasAction
End Sub
Private Sub ShowPortefOrdres()
Dim data As String
Dim Champs As Variant
Dim Benef As Double
Dim Largeur As Long
Dim PercBenef As Double
Dim TotalBenef As Double
Dim TotalPortef As Double
Dim liquid As Double
Dim liquidInit As Double
FGPortef.Clear
FGPortef.FixedAlignment(0) = vbCenter
FGPortef.Rows = 1
FGPortef.Row = 0

If joueur(IdJoueur).Vad = False Then lstActionsVentes.Clear

For i = 0 To FGPortef.Cols - 1
    FGPortef.FixedAlignment(i) = vbCenter
    FGPortef.Col = i
    Select Case i
        Case 0
            FGPortef.CellFontBold = True
            FGPortef.Text = "Nom"
            FGPortef.ColWidth(i) = 1455
        Case 1
            FGPortef.CellFontBold = True
            FGPortef.Text = "Nombre"
            FGPortef.ColWidth(i) = 780
        Case 2
            FGPortef.CellFontBold = True
            FGPortef.Text = "Valeur Achat"
            FGPortef.ColWidth(i) = 1500
        Case 3
            FGPortef.CellFontBold = True
            FGPortef.Text = "Valeur"
            FGPortef.ColWidth(i) = 1200
        Case 4
            FGPortef.CellFontBold = True
            FGPortef.Text = "Bénéfices ( % )"
            FGPortef.ColWidth(i) = 1410
    End Select
    'on charge la taille personalisé pour cette colonne
    Largeur = CLng(GetConfJoueur("TableauPortef", "Largeur_" & FGPortef.Text, 0))
    If Largeur > 0 Then FGPortef.ColWidth(i) = Largeur
Next




FGOrdres.Clear
FGOrdres.FixedAlignment(0) = vbCenter
FGOrdres.Rows = 1
FGOrdres.Row = 0

For i = 0 To FGOrdres.Cols - 1
    FGOrdres.FixedAlignment(i) = vbCenter
    FGOrdres.Col = i
    Select Case i
        Case 0
            FGOrdres.CellFontBold = True
            FGOrdres.Text = "Expiration"
            FGOrdres.ColWidth(i) = 1415
        Case 1
            FGOrdres.CellFontBold = True
            FGOrdres.Text = "Action"
            FGOrdres.ColWidth(i) = 1890
        Case 2
            FGOrdres.CellFontBold = True
            FGOrdres.Text = "Sens"
            FGOrdres.ColWidth(i) = 555
        Case 3
            FGOrdres.CellFontBold = True
            FGOrdres.Text = "Qté"
            FGOrdres.ColWidth(i) = 615
        Case 4
            FGOrdres.CellFontBold = True
            FGOrdres.Text = "Mini"
            FGOrdres.ColWidth(i) = 570
        Case 5
            FGOrdres.CellFontBold = True
            FGOrdres.Text = "Actuel"
            FGOrdres.ColWidth(i) = 615
        Case 6
            FGOrdres.CellFontBold = True
            FGOrdres.Text = "Maxi"
            FGOrdres.ColWidth(i) = 630
    End Select
    'on charge la taille personalisé pour cette colonne
    Largeur = CLng(GetConfJoueur("TableauOrdres", "Largeur_" & FGOrdres.Text, 0))
    If Largeur > 0 Then FGOrdres.ColWidth(i) = Largeur
Next




data = CommNetTrader("getportef", "do=lstordreportef")
If GetVarXML(data, "erreur") = "faux" Then
    XmlPortef = data
    Champs = Array("code", "nom", "nombre", "valachat", "valactuel") 'champs des données à recuperer
    TabPortef = GetTabXML(data, "portef", "action", Champs) 'on transforme le xml en tableau
    If IsArray(TabPortef) Then 'on ajoute les lignes au tableau du portefeuille
        For i = 0 To UBound(TabPortef, 1)
            'ligne de la liste des actions
            Benef = Round((Cdblo(TabPortef(i, 4)) * Cdblo(TabPortef(i, 2))) - (Cdblo(TabPortef(i, 3)) * Cdblo(TabPortef(i, 2))), 2)
            TotalBenef = TotalBenef + Benef
            TotalPortef = TotalPortef + Cdblo(TabPortef(i, 4)) * Cdblo(TabPortef(i, 2))
            PercBenef = Round(((Cdblo(TabPortef(i, 4)) - Cdblo(TabPortef(i, 3))) / Cdblo(TabPortef(i, 3))) * 100, 2)
            FGPortef.AddItem TabPortef(i, 1) & Chr(9) & TabPortef(i, 2) & Chr(9) & CStr(Round(Cdblo(TabPortef(i, 3)), 2)) & "€( " & CStr(Cdblo(TabPortef(i, 3)) * Cdblo(TabPortef(i, 2))) & "€)" & Chr(9) & CStr(Cdblo(TabPortef(i, 4))) & "€( " & CStr(Cdblo(TabPortef(i, 4)) * Cdblo(TabPortef(i, 2))) & "€)" & Chr(9) & CStr(Benef) & "€(" & CStr(PercBenef) & "%)"
            FGPortef.Col = 4
            FGPortef.Row = i + 1
            FGPortef.CellFontBold = True
            If PercBenef > 0 Then FGPortef.CellForeColor = IniColor("Controles", "CouleurTexteBenef")
            If PercBenef < 0 Then FGPortef.CellForeColor = IniColor("Controles", "CouleurTextePertes")
            
            If joueur(IdJoueur).Vad = False Then lstActionsVentes.AddItem TabPortef(i, 1)
        Next i
    End If
    
    Champs = Array("codesico", "nom", "etat", "nbr", "datecreation", "pourc", "datelimit", "coursmin", "coursmax", "sens", "valeur")

    TabOrdre = GetTabXML(data, "ordres", "ordre", Champs) 'on transforme le xml en tableau
    If IsArray(TabOrdre) Then 'on ajoute les lignes au tableau des ordres
        For i = 0 To UBound(TabOrdre, 1)
            If TabOrdre(i, 3) = 0 Then TabOrdre(i, 3) = CDbl(TabOrdre(i, 5)) * 100 & " %" 'si on a des % et non un nombre
            FGOrdres.AddItem TabOrdre(i, 6) & Chr(9) & TabOrdre(i, 1) & Chr(9) & TabOrdre(i, 9) & Chr(9) & TabOrdre(i, 3) & Chr(9) & TabOrdre(i, 7) & Chr(9) & TabOrdre(i, 10) & Chr(9) & TabOrdre(i, 8)
        Next i
    End If
    liquid = GetVarXML(data, "cashback")
    Cashback.Caption = liquid & " €"
    lblportef.Caption = Round(TotalPortef, 2) & " €"
    If TotalPortef <> 0 Then
        lblBenef.Caption = Round(TotalBenef, 2) & "€ (" & Round((TotalBenef / TotalPortef) * 100, 2) & " %)"
    Else
        lblBenef.Caption = "0 € ( 0 %)"
    End If
    liquidInit = GetVarXML(data, "cashbackInitial")
    lblcapital.Caption = Round(TotalPortef + liquid, 2) & " € (" & Round((((TotalPortef + liquid) - liquidInit) / liquidInit) * 100, 2) & " %)"
    
    If TotalBenef > 0 Then lblBenef.ForeColor = IniColor("Controles", "CouleurTexteBenef")
    If TotalBenef < 0 Then lblBenef.ForeColor = IniColor("Controles", "CouleurTextePertes")
Else
    ShowStatutBar ("Impossible de récuperer le portefeuille.")
End If

End Sub


Private Sub LoadSkin()
'charge les images des boutons et curseurs
Curseur.Picture = LoadPicture(SkinRep & "image\BoutonSlider.bmp")
CmdAchat.Picture = LoadPicture(SkinRep & "image\btacheter.bmp")
CmdVente.Picture = LoadPicture(SkinRep & "image\btvendre.bmp")
Command3.Picture = LoadPicture(SkinRep & "image\btfermer.bmp")
cmdguide.Picture = LoadPicture(SkinRep & "image\btav.bmp")
CmdReload.Picture = LoadPicture(SkinRep & "image\btactualiser.bmp")
'charge les deux image (caché) de l'onglet
onglet1.Picture = LoadPicture(SkinRep & "image\onglet1.bmp")
onglet2.Picture = LoadPicture(SkinRep & "image\onglet2.bmp")
'charge l'image du premier onglet à l'onglet visible
Picture1.Picture = onglet1.Picture
Picture1.Tag = 1 'indique que l'onglet 1 est activé
'image du slider
Slider(0).Picture = LoadPicture(SkinRep & "image\FondSLider.bmp")
Slider(1).Picture = Slider(0).Picture

Picture2.BackColor = IniColor("Controles", "FondCouleurGrilleFormAV") 'colorie l'image derriere la grille

Call ChargeSkinFille(Me) 'charge les couleurs generiques (communes a toutes les feuilles)
End Sub
Private Sub InitSlider(idslider As Integer)
Slidervar(idslider).value = 0

Slidervar(idslider).CurseurY = CInt((Slider(idslider).Height / 2) - (Curseur.Height / 2))
Slidervar(idslider).CurseurXMin = CInt(187 - (Curseur.Width / 2))
Slidervar(idslider).CurseurXMax = CInt(Slider(idslider).Width - 187 - (Curseur.Width / 2))
Slidervar(idslider).CurseurDiff = CInt(Curseur.Width / 2)

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




Private Sub CmbTypeAchat_Click()
'on n'affiche plus
Select Case EtatAchat
    Case "A seuil":
        ChgVisibleSeuilAchat False
        EtatAchat = "off"
    Case "A tout prix"
        ChgVisibleAToutPrixAchat False
    Case "A intervalle"
        ChgVisiblePlageAchat False
End Select

'on affiche
Select Case CmbTypeAchat.Text
    Case "A seuil":
        ChgVisibleSeuilAchat True
    Case "A tout prix"
        ChgVisibleAToutPrixAchat True
    Case "A intervalle"
        ChgVisiblePlageAchat True
End Select
EtatAchat = CmbTypeAchat.Text

CmdAchat.Enabled = True
End Sub

Private Sub CmbTypeAchat_KeyPress(KeyAscii As Integer)
KeyAscii = 0
End Sub

Private Sub desactiverFormAchat()
        Call InitAchatForm
        EtatAchat = "off"
        CmbTypeAchat = "Choisir type"
        lstActionsAchat.Text = TxtPasAction
lstActionsAchat.ListIndex = -1
ExpAchat.Visible = False
lstActionsAchat.Tag = -1
Slider(0).Visible = False
nbachat.Visible = False
lblachat(0).Visible = False
lblachat(1).Visible = False
CmbTypeAchat.Visible = False
ChgVisiblePlageAchat False
CmdAchat.Visible = False
End Sub
Private Sub desactiverFormVente()
    Call InitVenteForm
    EtatVente = "off"
    CmbTypeVente = "Choisir type"
    lstActionsVentes.Text = TxtPasAction
    lstActionsVentes.ListIndex = -1
    ExpVente.Visible = False
    lstActionsVentes.Tag = -1
ChgVisiblePlageVente False

nbvente.Visible = False
CmbTypeVente.Visible = False
lblvente(0).Visible = False
lblvente(1).Visible = False
CmbTypeVente.Visible = False
ChgVisiblePlageVente False
CmdVente.Visible = False
Slider(1).Visible = False
End Sub

Private Sub CmbTypeVente_Click()
If CmbTypeAchat.Text <> "Choisir type" Then desactiverFormAchat
'on n'affiche plus
Select Case EtatVente
    Case "A seuil":
        ChgVisibleSeuilVente False
        EtatAchat = "off"
    Case "A tout prix"
        ChgVisibleAToutPrixVente False
    Case "A intervalle"
        ChgVisiblePlageVente False
End Select

'on affiche
Select Case CmbTypeVente.Text
    Case "A seuil":
        ChgVisibleSeuilVente True
    Case "A tout prix"
        ChgVisibleAToutPrixVente True
    Case "A intervalle"
        ChgVisiblePlageVente True
End Select
EtatAchat = CmbTypeAchat.Text

CmdVente.Enabled = True

End Sub

Private Sub CmdAchat_Click()
Dim data As String
Dim LimitMax As String
If lstActionsAchat.Text <> txtpasction And nbachat > 0 Then
    quant = nbachat.Text
    quantperc = 0
    If lblachat(1).value = True Then
        typeach = 1
    Else
        quantperc = nbachat.Text
        typeach = 0
    End If
    If SeuilAchat.Visible = True Then LimitMax = Replace(SeuilAchat.Text, ",", ".") Else LimitMax = -1
    data = CommNetTrader("envoiordre", "do=sendachatvente&sens=achat&codesicav=" & TabLstActions(lstActionsAchat.Tag, 0) & "&nbr=" & quant & "&valmin=" & Replace(SeuilAchatDeb.Text, ",", ".") & "&valmax=" & Replace(LimitMax, ",", ".") & "&tempsmin=" & ExpAchat.Text & "&select=" & typeach & "&ansval=&seuil=1&nb2=" & quantperc & "&pourc=" & quantperc)
    If GetVarXML(data, "erreur") = "faux" Then
        desactiverFormAchat
        ShowPortefOrdres 'on actualise la liste des actions
        ShowStatutBar GetVarXML(data, "messageordre")
        GlobalState.DoUpdateHisto = True
    Else
        ShowStatutBar "L'envoi de l'ordre n'a pas pu se faire."
    End If
Else
    ShowStatutBar "Vous devez acheter plus que 0 actions"
End If





End Sub

Private Sub cmdguide_Click()
'ShowStatutBar "Disponible dans la prochaine mise à jour"
Load FormGuideAV
FormGuideAV.Show
FormGuideAV.StartAV
End Sub

Private Sub CmdReload_Click()
Call ShowPortefOrdres
End Sub

Private Sub CmdVente_Click()

Dim codesicav As String
Dim data As String
Dim LimitMax As String
If lstActionsVentes.Text <> txtpasction And nbvente > 0 Then
    quant = nbvente.Text
    quantperc = 0
    If lblvente(1).value = True Then
        typeven = 1
    Else
        quantperc = nbvente.Text
        typeven = 0
    End If
    If SeuilVente.Visible = True Then LimitMax = Replace(SeuilVente.Text, ",", ".") Else LimitMax = -1
    If joueur(IdJoueur).Vad = False Then
        codesicav = TabPortef(lstActionsVentes.Tag, 0)
    Else
        codesicav = TabLstActions(lstActionsVentes.Tag, 0)
    End If
    data = CommNetTrader("envoiordre", "do=sendachatvente&sens=vente&codesicav=" & codesicav & "&nbr=" & quant & "&valmax=" & Replace(SeuilVenteFin.Text, ",", ".") & "&valmin=" & LimitMax & "&tempsmin=" & ExpVente.Text & "&select=" & typeven & "&ansval=&seuil=1&nb2=" & quantperc & "&pourc=" & quantperc)
    If GetVarXML(data, "erreur") = "faux" Then
        desactiverFormVente
        ShowPortefOrdres 'on actualise la liste des actions
        ShowStatutBar GetVarXML(data, "messageordre")
        GlobalState.DoUpdateHisto = True
    Else
        ShowStatutBar "L'envoi de l'ordre n'a pas pu se faire."
    End If
Else
    ShowStatutBar "Vous devez vendre plus que 0 action"
End If

End Sub



Private Sub Command3_Click()
Call saveparams
Call SauverConfigJoueur
Formav.Hide

Unload Formav

End Sub

Private Sub FGOrdres_DblClick()
Dim res As VbMsgBoxResult
Dim leTexte As String
If FGOrdres.RowSel > 0 Then
    res = MsgBox("Êtes-vous certain de vouloir supprimer cet ordre ?", vbYesNo + vbInformation, "Validation de suppression")
    If res = vbYes Then
        leTexte = supprimerordre(TabOrdre(FGOrdres.RowSel - 1, 0), TabOrdre(FGOrdres.RowSel - 1, 4))
        Call ShowPortefOrdres
        ShowStatutBar GetVarXML(leTexte, "messagesuppr")
    End If
End If
End Sub
Private Function supprimerordre(codesico As Variant, datecreation As Variant) As String
Dim data As String
data = CommNetTrader("supprordre", "do=supprordre&idordre=" & datecreation)
supprimerordre = data
End Function





Private Sub Form_Load()
Call LoadSkin
Call InitSlider(0) 'on initialise les barres de valeurs
Call InitSlider(1)

Call ShowPortefOrdres
Call ChargeListeAchat

desactiverFormAchat 'met à 0 le formulaire d'achat
desactiverFormVente

End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
Call saveparams
End Sub
Private Sub saveparams()
'on sauvegarde les parametres
    FGPortef.Row = 0
    FGOrdres.Row = 0
For i = 0 To FGPortef.Cols - 1
    FGPortef.Col = i
    Call MajConfigJoueur("TableauPortef", "Largeur_" & FGPortef.Text, FGPortef.ColWidth(i))
Next i
For i = 0 To FGOrdres.Cols - 1
    FGOrdres.Col = i
    Call MajConfigJoueur("TableauOrdres", "Largeur_" & FGOrdres.Text, FGOrdres.ColWidth(i))
Next i
End Sub
Private Sub lblachat_Click(index As Integer)
If index = 0 Then
    Slidervar(0).Pourc = True
Else
    Slidervar(0).Pourc = False
End If
Call SliderMove(0, 0)
nbachat.Text = 0
End Sub

Private Sub lblvente_Click(index As Integer)
If index = 0 Then
    Slidervar(1).Pourc = True
Else
    Slidervar(1).Pourc = False
End If
Call SliderMove(1, 0)
nbvente.Text = 0
End Sub

Private Sub lstActionsAchat_Click()
If lstActionsVentes.Text <> TxtPasAction Then desactiverFormVente
ChargeActionAchat
Call SliderMove(0, 0)
nbachat.Text = 0
End Sub

Private Sub ChargeActionAchat()
Dim Indice, codesico, NbMax
Dim data As String
Indice = ChercherTab(TabLstActions, 1, lstActionsAchat.Text) 'va retorner l'identifiant du tableau

If Indice <> -1 And Indice <> lstActionsAchat.Tag Then
    InitAchatForm
    codesico = TabLstActions(Indice, 0)
    data = CommNetTrader("getportef", "do=getachatmax&codesico=" & codesico)
    NbMax = GetVarXML(data, "nbactionmax")
    If NbMax <> "" Then
        If NbMax > 0 Then
            Slidervar(0).Max = NbMax
            SeuilAchat.Text = GetVarXML(data, "valeur")
            lstActionsAchat.Tag = Indice
            CmdAchat.Visible = True
            Slider(0).Visible = True
            nbachat.Visible = True
            lblachat(0).Visible = True
            lblachat(1).Visible = True
            CmbTypeAchat.Visible = True
            
        Else
            ShowStatutBar "Vous n'avez pas assez de liquiditées"
        End If
    Else
        ShowStatutBar "Impossible de télécharger les informations de cette action"
    End If
End If
End Sub
Public Sub InitAchatForm()
Slidervar(0).Max = 1


Call SliderMove(0, 0) 'on met à O la barre de valeur


SeuilAchatDeb.Text = 0
SeuilAchat.Text = -1
ExpAchat = finjour

CmdAchat.Enabled = False

End Sub
Public Sub InitVenteForm()
Slidervar(1).Max = 1

Call SliderMove(1, 0) 'on met à O la barre de valeur

SeuilVenteFin.Text = -1
SeuilVente.Text = 0
ExpVente = finjour

CmdVente.Enabled = False

End Sub

Private Sub lstActionsVentes_Click()
If lstActionsAchat.Text <> TxtPasAction Then desactiverFormAchat

ChargeActionVente

Call SliderMove(1, 0)
nbvente.Text = 0
End Sub

Private Sub lstActionsVentes_KeyPress(KeyAscii As Integer)
If KeyAscii = 13 Then
    controleSortie lstActionsVentes
    KeyAscii = 0
    Call lstActionsVentes_Click
End If
End Sub


Private Sub ChargeActionVente()
Dim Indice, codesico, NbMax
Dim data As String
If joueur(IdJoueur).Vad = False Then
    Indice = ChercherTab(TabPortef, 1, lstActionsVentes.Text) 'va retorner l'identifiant du tableau
Else
    Indice = ChercherTab(TabLstActions, 1, lstActionsVentes.Text) 'va retorner l'identifiant du tableau
End If
If Indice <> -1 And Indice <> CInt(lstActionsVentes.Tag) Then
    InitVenteForm
    If joueur(IdJoueur).Vad = False Then
        NbMax = TabPortef(Indice, 2)
    Else
        codesico = TabLstActions(Indice, 0)
        data = CommNetTrader("getportef", "do=getventemax&codesico=" & codesico)
        NbMax = GetVarXML(data, "nbactionmax")
    End If
    If NbMax <> "" Then
        If NbMax > 0 Then
            Slidervar(1).Max = NbMax
            If joueur(IdJoueur).Vad = False Then
                SeuilVente.Text = TabPortef(Indice, 3)
            Else
                SeuilVente.Text = GetVarXML(data, "valeur")
            End If
            lstActionsVentes.Tag = Indice
            CmdVente.Enabled = True
            Slider(1).Visible = True
            nbvente.Visible = True
            lblvente(0).Visible = True
            lblvente(1).Visible = True
            CmbTypeVente.Visible = True
            CmdVente.Visible = True
        Else
            Slidervar(1).Max = 0
            SeuilVente.Text = 0
            lstActionsVentes.Tag = 0
            CmdVente.Enabled = False
            Slider(1).Visible = False
            nbvente.Visible = False
            lblvente(0).Visible = False
            lblvente(1).Visible = False
            CmbTypeVente.Visible = False
            CmdVente.Visible = False
            ShowStatutBar ("Vous ne pouvez pas vendre de cette action")
        End If
    End If
End If
End Sub
Private Sub Picture1_MouseUp(Button As Integer, Shift As Integer, x As Single, y As Single)
If x < Picture1.Width / 2 Then
    If Picture1.Tag = 2 Then
        Picture1.Picture = onglet1.Picture
        Picture1.Tag = 1
        FGPortef.Visible = True
        FGOrdres.Visible = False
    End If
Else
    If Picture1.Tag = 1 Then
        Picture1.Picture = onglet2.Picture
        Picture1.Tag = 2
        FGOrdres.Visible = True
        FGPortef.Visible = False
        ShowStatutBar "Pour supprimer un ordre double-cliquez sur la ligne"
    End If
End If
End Sub

Private Sub Slider_LostFocus(index As Integer)
Slidervar(index).MouseDown = False
End Sub

Private Sub Slider_MouseDown(index As Integer, Button As Integer, Shift As Integer, x As Single, y As Single)
Slidervar(index).MouseDown = True
Call SliderMove(index, CInt(x))
If index = 0 Then nbachat.Text = Slidervar(index).value
If index = 1 Then nbvente.Text = Slidervar(index).value
End Sub

Private Sub Slider_MouseMove(index As Integer, Button As Integer, Shift As Integer, x As Single, y As Single)
If Slidervar(index).MouseDown = True Then
    Call SliderMove(index, CInt(x))
    If index = 0 Then nbachat.Text = Slidervar(index).value
    If index = 1 Then nbvente.Text = Slidervar(index).value
End If

End Sub

Private Sub Slider_MouseUp(index As Integer, Button As Integer, Shift As Integer, x As Single, y As Single)
Slidervar(index).MouseDown = False
End Sub


Private Sub Timer1_Timer()
If GlobalState.DoUpdatePortef = True Then
    GlobalState.DoUpdatePortef = False
    ShowPortefOrdres 'on actualise la liste des actions
End If
End Sub
