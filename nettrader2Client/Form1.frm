VERSION 5.00
Begin VB.Form graphi 
   AutoRedraw      =   -1  'True
   Caption         =   "GRAPHIQUE"
   ClientHeight    =   2940
   ClientLeft      =   1620
   ClientTop       =   1410
   ClientWidth     =   5760
   FontTransparent =   0   'False
   Icon            =   "Form1.frx":0000
   LinkTopic       =   "Form1"
   MDIChild        =   -1  'True
   ScaleHeight     =   2940
   ScaleWidth      =   5760
   Begin VB.PictureBox Picture1 
      Appearance      =   0  'Flat
      AutoRedraw      =   -1  'True
      BackColor       =   &H00FFFFFF&
      BorderStyle     =   0  'None
      FillColor       =   &H00FFFFFF&
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   8.25
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      ForeColor       =   &H00FFFFFF&
      Height          =   2535
      Left            =   50
      ScaleHeight     =   2535
      ScaleWidth      =   5655
      TabIndex        =   4
      Top             =   360
      Width           =   5655
   End
   Begin VB.CheckBox Check3 
      Caption         =   "Valeur de cloture"
      Height          =   255
      Left            =   4200
      TabIndex        =   3
      Top             =   0
      Width           =   1575
   End
   Begin VB.CheckBox Check2 
      Caption         =   "Cours"
      Height          =   255
      Left            =   3480
      TabIndex        =   2
      Top             =   0
      Value           =   1  'Checked
      Width           =   735
   End
   Begin VB.CheckBox Check1 
      Caption         =   "Volumes"
      Height          =   255
      Left            =   2520
      TabIndex        =   1
      Top             =   0
      Width           =   975
   End
   Begin VB.ComboBox Combo1 
      BackColor       =   &H80000006&
      ForeColor       =   &H80000005&
      Height          =   315
      Left            =   0
      TabIndex        =   0
      Top             =   0
      Width           =   2535
   End
End
Attribute VB_Name = "graphi"
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

Dim TableauValeur() As Single
Dim TableauVolumes() As Single
Dim TableauDates() As String
Dim rangeX() As Variant 'pour x= correspond au champ courant
Dim DateDebutSelect As Variant
Dim XDebutSelect As Variant
Dim CouleurFond As Single
Dim CouleurTexte As Single
Dim CouleurCourbeVolume As Single
Dim CouleurCourbeValeur As Single
Dim CouleurCurseur As Single
Dim CouleurTraitCoupure As Single

Const MinWidth As Single = 5880
Const MinHeight As Single = 3450

Private Type graph
    Haut As Double  'la plus haute des valeurs
    Bas As Double 'la plus basse des valeurs
    Nombre As Double 'nombre d'enregistrement
    pourcentageHauteur As Double
    pourcentagelargeur As Double
End Type
Private FocusGraph As Boolean
Private AnsX As Long
Private AnsI As Long
Private Type graphio
    dx As Long
    dy As Long
    fx As Long
    fy As Long
End Type

Private ValeurGraph() As graphio
Private VolumeGraph() As graphio
Private Graphique As graph







Private Sub dessin()
Picture1.Cls
' 1ere etape voir quel est le plus haut des chiffres et le plus bas
Dim DecalPixelH, DecalPixelV, DecalMargeH, DecalMargeV, PourcMargeV, PourcMargeH As Single
PourcMargeV = 230 / Picture1.Height
PourcMargeH = 5.5
DecalMargeH = 5.5
DecalMargeY = 0
DecalPixelH = 0
DecalPixelV = -230
Picture1.ForeColor = CouleurTexte 'couleur du texte


Graphique.Haut = TableauValeur(0)
Graphique.Bas = TableauValeur(0)
For i = 0 To UBound(TableauValeur)
If TableauValeur(i) > Graphique.Haut Then
Graphique.Haut = TableauValeur(i)
End If
If TableauValeur(i) < Graphique.Bas Then
Graphique.Bas = TableauValeur(i)
End If
Next
If (Graphique.Haut - Graphique.Bas) = 0 Then
Graphique.Haut = Graphique.Haut + 2
Graphique.Bas = Graphique.Bas - 2
End If

Hauteur = Picture1.Height - ((Picture1.Height * (PourcMargeV / 100)) / 2) - DecalMargeY / 100 * Picture1.Height / 2 - DecalPixelV
echely = (Picture1.Height * (1 - (PourcMargeV / 100))) / (Graphique.Haut - Graphique.Bas)
echelx = (Picture1.Width * (1 - (PourcMargeH / 100))) / (UBound(TableauValeur))
x = (Picture1.Width * (PourcMargeH / 100)) / 2 + DecalMargeH / 100 * Picture1.Width / 2 + DecalPixelH
decal = x
y = -((TableauValeur(0) - Graphique.Bas) * echely) + Hauteur
ReDim ValeurGraph(0 To UBound(TableauValeur))
For i = 0 To UBound(TableauValeur)
With ValeurGraph(i)
.dx = x
.dy = y
.fx = i * echelx + decal
.fy = -((TableauValeur(i) - Graphique.Bas) * echely) + Hauteur
End With
If Check2.value = 1 Then
Picture1.Line (ValeurGraph(i).dx, ValeurGraph(i).dy)-(ValeurGraph(i).fx, ValeurGraph(i).fy), CouleurCourbeValeur
End If
rangeX(i) = ValeurGraph(i).fx
x = ValeurGraph(i).fx
y = ValeurGraph(i).fy
Next

' FIN DESSIN DE LA COURBE VALEUR




Graphique.Haut = TableauVolumes(0)
Graphique.Bas = TableauVolumes(0)
For i = 0 To UBound(TableauVolumes)
    If CDbl(TableauVolumes(i)) > CDbl(Trim(Graphique.Haut)) Then
        Graphique.Haut = TableauVolumes(i)
    End If
    If TableauVolumes(i) < Graphique.Bas Then
        Graphique.Bas = TableauVolumes(i)
    End If
Next
Hauteur = Picture1.Height - ((Picture1.Height * (PourcMargeV / 100)) / 2) - DecalMargeY / 100 * Picture1.Height / 2 - DecalPixelV
If (Graphique.Haut - Graphique.Bas) <> 0 Then
    echely = (Picture1.Height * (1 - (PourcMargeV / 100))) / (Graphique.Haut - Graphique.Bas)
End If
echelx = (Picture1.Width * (1 - (PourcMargeH / 100))) / (UBound(TableauVolumes))
x = (Picture1.Width * (PourcMargeH / 100)) / 2 + DecalMargeH / 100 * Picture1.Width / 2 + DecalPixelH
decal = x
y = -((TableauVolumes(0) - Graphique.Bas) * echely) + Hauteur
ReDim VolumeGraph(0 To UBound(TableauVolumes))
For i = 0 To UBound(TableauVolumes)
    With VolumeGraph(i)
        .dx = x
        .dy = y
        .fx = i * echelx + decal
        .fy = -((TableauVolumes(i) - Graphique.Bas) * echely) + Hauteur
    End With
    If Check1.value = 1 Then
        Picture1.Line (VolumeGraph(i).dx, VolumeGraph(i).dy)-(VolumeGraph(i).fx, VolumeGraph(i).fy), CouleurCourbeVolume
    End If
    'MsgBox "C:" & CStr(TableauVolumes(i)) & " H:" & CStr(Graphique.Haut)
    rangeX(i) = VolumeGraph(i).fx
    x = VolumeGraph(i).fx
    y = VolumeGraph(i).fy
Next
'si coché
' FIN DESSIN DE LA COURBE VALEUR






End Sub




Private Sub Check1_Click()
If Graphique.Haut = 0 Then Exit Sub
Call dessin
End Sub

Private Sub Check2_Click()
If Graphique.Haut = 0 Then Exit Sub
Call dessin
End Sub


Private Sub Check3_Click()
If Graphique.Haut = 0 Then Exit Sub
Call dessin
End Sub

Private Sub Combo1_Click()
i = 0

'"http://www.euronext.com/tools/datacentre/dataCentreDownloadExcell.jcsv?cha=3044&lan=FR&idInstrument=17049&isinCode=FR0010220475&indexCompo=&opening=&high=&low=&closing=on&volume=on&dateFrom=15/09/2005&dateTo=13/09/2007&typeDownload=2"
End Sub


Private Sub Form_GotFocus()
FocusGraph = True
'Il faut d'abord charger la liste des valeurs disponibles

End Sub
Private Sub loadformskin()
Dim PoliceCouleur, CouleurBarre As Long
Dim NomPolice As String

Call ChargeSkinFille(Me)

CouleurFond = IniColor("GraphiqueCouleur", "Fond")
CouleurTexte = IniColor("GraphiqueCouleur", "Texte")
CouleurCourbeVolume = IniColor("GraphiqueCouleur", "CourbeVolume")
CouleurTexte = IniColor("GraphiqueCouleur", "Texte")
CouleurCurseur = IniColor("GraphiqueCouleur", "Curseur")
CouleurTraitCoupure = IniColor("GraphiqueCouleur", "TraitCoupure")
CouleurCourbeValeur = IniColor("GraphiqueCouleur", "CourbeValeur")

Picture1.BackColor = CouleurFond

End Sub
Private Sub Form_Load()
Dim fich As String
LoadResizing Me.hWnd, MinWidth, MinHeight

Me.Width = 5880
Me.Height = 3450
Call loadformskin


If Not DossierExiste(DossierCours, vbDirectory) Then
   MkDir DossierCours
End If


fich = Dir(DossierCours + "\")

Do While fich <> ""
    If GetExtension(fich) = "csv" Then
        Combo1.AddItem (GetNomFichier(fich))
    End If
    fich = Dir
Loop


Exit Sub

Dim ac As String
Dim bc As String
Dim canal As Long
Dim res



    

 'Combo1.AddItem nomactiontemp

 'Combo1.AddItem GETnom(rs.Fields(0)) & " -Jour"

'organisation de la listbox
Call ArrangeList

Dim TagGenere As String
TagGenere = ""
For i = 0 To 9
Randomize
chiffre = Int(Rnd * 10)
lettre = Chr(Int(Rnd * 26) + 97)
tire = Int(Rnd * 2)
If tire = 0 Then
TagGenere = TagGenere & lettre
Else
TagGenere = TagGenere & chiffre
End If
Next i

Me.Tag = TagGenere
End Sub
Private Sub ArrangeList()
If Combo1.ListCount = 0 Then Exit Sub
Dim Tbl$()
Dim Ntbl$()
Dim CH As Boolean
ReDim Tbl(Combo1.ListCount - 1)
ReDim Ntbl(Combo1.ListCount - 1)
mtb = Combo1.ListCount - 1
For n = 0 To mtb
Tbl(n) = Combo1.List(n)
Ntbl(n) = ""
Next n
Combo1.Clear
For n = LBound(Tbl) To UBound(Tbl)
For a = LBound(Tbl) To UBound(Tbl)
If a >= n Then
If StrComp(Tbl(n), Tbl(a), vbTextCompare) = -1 Then
tx = Tbl(n)
Tbl(n) = Tbl(a)
Tbl(a) = tx
End If
End If
Next a
Next n
For n = UBound(Tbl) To LBound(Tbl) Step -1
Combo1.AddItem Tbl(n)
Next n
End Sub


Private Sub Form_LostFocus()
FocusGraph = False
End Sub

Private Sub Form_Resize()
On Error GoTo erreur
Me.SetFocus
Combo1.Width = Me.Width - Check1.Width - Check2.Width - Check3.Width - 120
Picture1.Width = Me.Width - 210
Picture1.Height = Me.Height - Combo1.Height - 600
Check1.Left = Combo1.Width
Check2.Left = Check1.Left + Check1.Width
Check3.Left = Check2.Left + Check2.Width
Call dessin
erreur:
End Sub








Private Sub Form_Unload(Cancel As Integer)
RestoreResizing (Me.hWnd)
End Sub

Private Sub Picture1_MouseDown(Button As Integer, Shift As Integer, x As Single, y As Single)
If Graphique.Haut = 0 Then Exit Sub

If Button = 1 And x >= rangeX(0) Then
If DateDebutSelect <> 0 Then
For i = 1 To UBound(rangeX)
If rangeX(i) >= x Then
x = rangeX(i)
Exit For
End If
Next
Picture1.Line (x, Picture1.Height)-(x, 195), CouleurTraitCoupure
End If
End If

End Sub

Private Sub Picture1_MouseMove(Button As Integer, Shift As Integer, x As Single, y As Single)
Exit Sub
If Graphique.Haut = 0 Or fMainForm.ActiveForm.Tag <> Me.Tag Then Exit Sub
Dim ValeurVa, ValeurVo
Dim TailleTexte, Texte, HauteurTexte
Static AnsValeurVa, AnsValeurVo
If x < rangeX(0) Or x > rangeX(UBound(rangeX)) Or AnsX = x Then Exit Sub

'recherche de i
For i = 1 To UBound(rangeX)
If rangeX(i) >= x Then
If Check3.value = 1 Then
'valeur de cloture selectionne
x = rangeX(i)
End If
Exit For
End If

Next
If Check3.value = 0 Then
fx = ValeurGraph(i).fx
dy = TableauValeur(i - 1)
dx = ValeurGraph(i - 1).dx
fy = TableauValeur(i)
a = ((fy - dy) / (fx - dx))
b = dy - a * dx
valeur = a * x + b
Else
valeur = TableauValeur(i)
End If


'dessin des deux barres calcul
fx = ValeurGraph(i).fx
dy = ValeurGraph(i).dy
dx = ValeurGraph(i).dx
fy = ValeurGraph(i).fy
a = ((fy - dy) / (fx - dx))
b = dy - a * dx
ValeurVa = a * x + b
fx = VolumeGraph(i).fx
dy = VolumeGraph(i).dy
dx = VolumeGraph(i).dx
fy = VolumeGraph(i).fy
a = ((fy - dy) / (fx - dx))
b = dy - a * dx
ValeurVo = a * x + b



'effacer les carrés
'Picture1.Line (0, 0)-(Picture1.Width * (50 / 1000), Picture1.Height), RGB(255,255, 255), BF
If ValeurVa > AnsValeurVa Then
Picture1.Line (Picture1.Width * (1 / 1000), 0)-(Picture1.Width * (25 / 1000), ValeurVa), CouleurFond, BF
End If
If ValeurVo > AnsValeurVo Then
Picture1.Line (Picture1.Width * (26 / 1000), 0)-(Picture1.Width * (5 / 100), ValeurVo), CouleurFond, BF
End If
'desiner les carres
Picture1.Line (Picture1.Width * (1 / 1000), Picture1.Height)-(Picture1.Width * (25 / 1000), ValeurVa), CouleurCourbeValeur, BF 'dessin de valeur
Picture1.Line (Picture1.Width * (26 / 1000), Picture1.Height)-(Picture1.Width * (5 / 100), ValeurVo), CouleurCourbeVolume, BF  'dessin de volume
AnsValeurVo = ValeurVo
AnsValeurVa = ValeurVa
'Picture1.Line (X, 10)-(X, 0)
'Picture1.Line (X, Picture1.Height - Picture1.Height * (1 / 10))-(X, Picture1.Height)
'Picture1.Line (AnsX, 10)-(AnsX, 0), RGB(255,255, 255)
'Picture1.Line (AnsX, Picture1.Height - Picture1.Height * (1 / 10))-(AnsX, Picture1.Height), RGB(255,255, 255)
Picture1.Line (AnsX, Picture1.Height)-(AnsX, 198), CouleurFond
If DateDebutSelect <> 0 Then
If Button = 1 Then
If AnsI > 1 And AnsI < UBound(rangeX) Then Picture1.Line (rangeX(AnsI), Picture1.Height)-(rangeX(AnsI), 198), CouleurFond
End If
End If
Picture1.Line (x, Picture1.Height)-(x, 198), CouleurCurseur

If Check2.value = 1 Then
If AnsI > 1 And AnsI < UBound(rangeX) Then
Picture1.Line (ValeurGraph(AnsI - 1).dx, ValeurGraph(AnsI - 1).dy)-(ValeurGraph(AnsI - 1).fx, ValeurGraph(AnsI - 1).fy), CouleurCourbeValeur
Picture1.Line (ValeurGraph(AnsI + 1).dx, ValeurGraph(AnsI + 1).dy)-(ValeurGraph(AnsI + 1).fx, ValeurGraph(AnsI + 1).fy), CouleurCourbeValeur
End If
Picture1.Line (ValeurGraph(AnsI).dx, ValeurGraph(AnsI).dy)-(ValeurGraph(AnsI).fx, ValeurGraph(AnsI).fy), CouleurCourbeValeur
End If

If Check1.value = 1 Then
If AnsI > 1 And AnsI < UBound(rangeX) Then
Picture1.Line (VolumeGraph(AnsI - 1).dx, VolumeGraph(AnsI - 1).dy)-(VolumeGraph(AnsI - 1).fx, VolumeGraph(AnsI - 1).fy), CouleurCourbeVolume
Picture1.Line (VolumeGraph(AnsI + 1).dx, VolumeGraph(AnsI + 1).dy)-(VolumeGraph(AnsI + 1).fx, VolumeGraph(AnsI + 1).fy), CouleurCourbeVolume
End If
Picture1.Line (VolumeGraph(AnsI).dx, VolumeGraph(AnsI).dy)-(VolumeGraph(AnsI).fx, VolumeGraph(AnsI).fy), CouleurCourbeVolume
End If
If DateDebutSelect <> 0 Then
Picture1.Line (XDebutSelect, Picture1.Height)-(XDebutSelect, 198), CouleurTraitCoupure
If Button = 1 Then
Picture1.Line (rangeX(i), Picture1.Height)-(rangeX(i), 198), CouleurTraitCoupure
End If
End If

'Label1.Visible = False
'If X - (Label1.Width / 2) > 0 And X + (Label1.Width / 2) < Picture1.Width Then
'Label1.Left = X - (Label1.Width / 2)
'Else
'If X - (Label1.Width / 2) < 0 Then
'Label1.Left = 0
'Else
'Label1.Left = Picture1.Width - Label1.Width
'End If
'End If
If TableauVolumes(i) <> 1 Then
Texte = TableauDates(i) & " Valeur : " & Round(valeur, 2) & " €" & " Volume : " & TableauVolumes(i)
Else
Texte = TableauDates(i) & " Valeur : " & Round(valeur, 2) & " €"
End If
TailleTexte = Picture1.TextWidth(Texte)
HauteurTexte = Picture1.TextHeight("X")

Picture1.Line (AnsX - (TailleTexte), 0)-(AnsX + (TailleTexte), HauteurTexte), CouleurFond, BF

If x - (TailleTexte / 2) >= 0 And x + (TailleTexte / 2) < Picture1.Width Then
Picture1.CurrentX = x - (TailleTexte / 2)
Else
If x - (TailleTexte / 2) < 0 Then
Picture1.CurrentX = 0
Else
Picture1.CurrentX = Picture1.Width - TailleTexte
End If
End If



Picture1.CurrentY = 0
Picture1.Print Texte

AnsI = i
AnsX = x
'Label1.Caption = TableauDates(i) & " Valeur : " & Round(Valeur, 2) & " €" & " Volume : " & TableauVolumes(i)
'Label1.Visible = True
End Sub



Private Sub Picture1_MouseUp(Button As Integer, Shift As Integer, x As Single, y As Single)
If Graphique.Haut = 0 Then Exit Sub

'click droit redessiner toute la courbe//clique gauche definition du debut ou de la fin de l'intervalle d'affichage
If Button = 2 Then
    DateDebutSelect = 0
    Call Combo1_Click
    Exit Sub
End If
If Button = 1 And x >= rangeX(0) Then

For i = 1 To UBound(rangeX)
If rangeX(i) >= x Then
Exit For
End If
Next
    
    If DateDebutSelect = 0 Then
    DateDebutSelect = i
    XDebutSelect = rangeX(i)
    Else
    'modifier les valeurs dans les tableaux et appeler dessin et effacer les deux variables
    If UBound(TableauValeur) = UBound(TableauVolumes) And UBound(TableauVolumes) = UBound(TableauDates) And i <> DateDebutSelect Then
    Dim TableauTempValeur() As Single
    Dim TableauTempVolumes() As Single
    Dim TableauTempDates() As String
    Dim deb As Variant
    Dim fin As Variant
    Dim IndexTemp As Variant
    If DateDebutSelect < i Then
    deb = DateDebutSelect
    fin = i
    Else
    fin = DateDebutSelect
    deb = i
    End If
    ReDim TableauTempValeur(0 To fin - deb)
    ReDim TableauTempVolumes(0 To fin - deb)
    ReDim TableauTempDates(0 To fin - deb)
    IndexTemp = 0
    For h = deb To fin
    TableauTempValeur(IndexTemp) = TableauValeur(h)
    TableauTempVolumes(IndexTemp) = TableauVolumes(h)
    TableauTempDates(IndexTemp) = TableauDates(h)
    IndexTemp = IndexTemp + 1
    Next h
    ReDim TableauValeur(0 To UBound(TableauTempValeur))
    ReDim TableauVolumes(0 To UBound(TableauTempValeur))
    ReDim TableauDates(0 To UBound(TableauTempValeur))
    TableauValeur = TableauTempValeur
    TableauVolumes = TableauTempVolumes
    TableauDates = TableauTempDates
    DateDebutSelect = 0
    AnsI = 0
    AnsX = 0
    ReDim rangeX(0 To UBound(TableauValeur, 1))
    
    If TypeTemps = "year" Then
    Me.Caption = Replace(Combo1.Text, " -Jour", "") & " : Graphique du " & TableauDates(0) & " au " & TableauDates(UBound(TableauDates))
    Else
    Me.Caption = Replace(Combo1.Text, " -Jour", "") & " : Graphique de " & TableauDates(0) & " à " & TableauDates(UBound(TableauValeur))
    End If
    
    Call dessin
    Else
    DateDebutSelect = 0
    Call Combo1_Click
    End If
    End If 'fin definition de la premiere date

End If


End Sub

