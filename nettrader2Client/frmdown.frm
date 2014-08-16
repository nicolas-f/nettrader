VERSION 5.00
Begin VB.Form frmdown 
   BackColor       =   &H80000005&
   BorderStyle     =   3  'Fixed Dialog
   Caption         =   "Téléchargement des valeurs"
   ClientHeight    =   3600
   ClientLeft      =   45
   ClientTop       =   435
   ClientWidth     =   4695
   Icon            =   "frmdown.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MDIChild        =   -1  'True
   MinButton       =   0   'False
   ScaleHeight     =   3600
   ScaleWidth      =   4695
   ShowInTaskbar   =   0   'False
   Begin VB.CommandButton cmdselectall 
      Height          =   420
      Left            =   1320
      Style           =   1  'Graphical
      TabIndex        =   6
      Top             =   1860
      Width           =   2010
   End
   Begin VB.PictureBox Progress1 
      BackColor       =   &H00FFFFFF&
      Height          =   375
      Left            =   120
      ScaleHeight     =   315
      ScaleWidth      =   4395
      TabIndex        =   5
      Top             =   2595
      Width           =   4455
   End
   Begin VB.CommandButton cmdfermer 
      Height          =   420
      Left            =   2895
      Style           =   1  'Graphical
      TabIndex        =   3
      Top             =   3075
      Width           =   1215
   End
   Begin VB.CommandButton cmdtelecharger 
      Height          =   420
      Left            =   360
      Style           =   1  'Graphical
      TabIndex        =   2
      Top             =   3075
      Width           =   2010
   End
   Begin VB.Frame Frame1 
      BackColor       =   &H80000005&
      Caption         =   "Séléction des actions à télécharger"
      ForeColor       =   &H00000000&
      Height          =   1815
      Left            =   120
      TabIndex        =   0
      Top             =   0
      Width           =   4455
      Begin VB.ListBox List1 
         Appearance      =   0  'Flat
         Height          =   1380
         ItemData        =   "frmdown.frx":0442
         Left            =   120
         List            =   "frmdown.frx":044C
         Style           =   1  'Checkbox
         TabIndex        =   1
         Top             =   240
         Width           =   4215
      End
   End
   Begin VB.Label Label1 
      BackStyle       =   0  'Transparent
      Caption         =   "0 actions sur 12 téléchargées"
      Height          =   255
      Left            =   240
      TabIndex        =   4
      Top             =   2325
      Width           =   4095
   End
End
Attribute VB_Name = "frmdown"
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

Private Pourc As Double

Private Type ProgressBar
    ForeColor As Long
    ForeColorSombre As Long
End Type


Dim ProgressBar1 As ProgressBar
Private Sub LoadLstActions()
'Télechargement de la liste des actions disponible dans NetTrader


End Sub
Private Sub ShowProgressBar()

' Dessine le pourcentage fait
Dim X2 As Integer

If Pourc > 0 And Pourc <= 100 Then
    Progress1.Cls
    X2 = Progress1.Width * (Pourc / 100)
    Progress1.Line (0, 0)-(X2, Progress1.Height), ProgressBar1.ForeColorSombre, BF  'dessin de valeur
    If X2 > 25 And Progress1.Height > 50 Then
        Progress1.Line (0, 0)-(X2 - 25, Progress1.Height - 50), ProgressBar1.ForeColor, BF 'dessin de valeur
    End If
    Progress1.CurrentY = (Progress1.Height / 2) - (Progress1.TextHeight("99%") / 2)
    Progress1.CurrentX = (Progress1.Width / 2) - (Progress1.TextWidth(Pourc & " %") / 2)
    Progress1.Print Pourc & " %"


End If


End Sub

Private Sub cmdselectall_Click()
    For idel = 0 To List1.ListCount - 1
        List1.Selected(idel) = True
    Next idel
End Sub

Private Sub cmdtelecharger_Click()

' pour tester le graph
Pourc = Pourc + 10

Call ShowProgressBar
'on ajoute 10% a chaque clique
End Sub



Private Sub CmdFermer_Click()
Me.Hide
Unload Me
End Sub




Private Sub Form_Load()
Pourc = 0
Call LoadSkin
End Sub

Private Sub LoadSkin()
Dim FondBouton, fondcouleur As Long

cmdtelecharger.Picture = LoadPicture(SkinRep & "image\btdown.bmp")
CmdFermer.Picture = LoadPicture(SkinRep & "image\btfermer.bmp")
cmdselectall.Picture = LoadPicture(SkinRep & "image\btselecttout.bmp")

Progress1.BackColor = IniColor("Controles", "FondProgressBar")
Progress1.FontName = GetIni("Controles", "PoliceTexteProgressBar")
Progress1.ForeColor = IniColor("Controles", "CouleurTexteProgressBar")
ProgressBar1.ForeColor = IniColor("Controles", "CouleurFaitProgressBar")
ProgressBar1.ForeColorSombre = IniColor("Controles", "CouleurFaitProgressBarSombre")


Call ChargeSkinFille(Me) 'charge les couleurs generiques (communes a toutes les feuilles)

End Sub
