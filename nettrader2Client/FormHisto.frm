VERSION 5.00
Object = "{5E9E78A0-531B-11CF-91F6-C2863C385E30}#1.0#0"; "MSFLXGRD.OCX"
Begin VB.Form FormHisto 
   Caption         =   "Historique achat/vente"
   ClientHeight    =   3090
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   4680
   Icon            =   "FormHisto.frx":0000
   LinkTopic       =   "Form1"
   MDIChild        =   -1  'True
   ScaleHeight     =   3090
   ScaleWidth      =   4680
   Begin VB.Timer Timer1 
      Interval        =   1000
      Left            =   1305
      Top             =   2295
   End
   Begin MSFlexGridLib.MSFlexGrid MSFlexGrid1 
      Height          =   3135
      Left            =   0
      TabIndex        =   0
      Top             =   0
      Width           =   4695
      _ExtentX        =   8281
      _ExtentY        =   5530
      _Version        =   393216
      Cols            =   7
      FixedCols       =   0
      AllowUserResizing=   1
   End
End
Attribute VB_Name = "FormHisto"
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

Const MinWidth As Single = 9405
Const MinHeight As Single = 1000

Private Sub Form_Load()
LoadResizing Me.hWnd, MinWidth, MinHeight

Call LoadSkin
Call ShowHisto

Me.Width = 9405
End Sub

Private Sub Form_Resize()
MSFlexGrid1.Width = Me.ScaleWidth
MSFlexGrid1.Height = Me.ScaleHeight
End Sub

Private Sub Form_Unload(Cancel As Integer)
RestoreResizing (Me.hWnd)
End Sub

Private Sub LoadSkin()
Dim fondcouleur As Long

fondcouleur = IniColor("Controles", "FondCouleurGrilleFormHisto")

MSFlexGrid1.BackColor = fondcouleur
MSFlexGrid1.BackColorBkg = fondcouleur
MSFlexGrid1.BackColorFixed = fondcouleur

fondcouleur = IniColor("Controles", "CouleurTexteGrilleHisto")

MSFlexGrid1.ForeColor = fondcouleur
End Sub

Private Sub ShowHisto()
Dim TabHisto As Variant
Dim data As String


MSFlexGrid1.Clear
MSFlexGrid1.FixedAlignment(0) = vbCenter
MSFlexGrid1.Rows = 1
MSFlexGrid1.Row = 0

For i = 0 To MSFlexGrid1.Cols - 1
    MSFlexGrid1.FixedAlignment(i) = vbCenter
    MSFlexGrid1.Col = i
    Select Case i
        Case 0
            MSFlexGrid1.CellFontBold = True
            MSFlexGrid1.Text = "Date"
            MSFlexGrid1.ColWidth(i) = 1425
        Case 1
            MSFlexGrid1.CellFontBold = True
            MSFlexGrid1.Text = "Nom"
            MSFlexGrid1.ColWidth(i) = 2280
        Case 2
            MSFlexGrid1.CellFontBold = True
            MSFlexGrid1.Text = "Sens"
            MSFlexGrid1.ColWidth(i) = 585
        Case 3
            MSFlexGrid1.CellFontBold = True
            MSFlexGrid1.Text = "Nombre"
            MSFlexGrid1.ColWidth(i) = 900
        Case 4
            MSFlexGrid1.CellFontBold = True
            MSFlexGrid1.Text = "VU ( Total HT)"
            MSFlexGrid1.ColWidth(i) = 1710
        Case 5
            MSFlexGrid1.CellFontBold = True
            MSFlexGrid1.Text = "Taxe"
            MSFlexGrid1.ColWidth(i) = 855
        Case 6
            MSFlexGrid1.CellFontBold = True
            MSFlexGrid1.Text = "Total TTC"
            MSFlexGrid1.ColWidth(i) = 1170
    End Select
Next

'on recherche si il existe le fichier historique de sauvegardé
Dim canal As Integer
Dim NomHistorique As String
Dim DataFromFile As String
Dim NomDossierJoueur As String
Dim dernDate As Long
dernDate = 0
DataFromFile = ""
NomDossierJoueur = App.Path & "\" & DossierDonnees & "\" & joueur(IdJoueur).DossierJoueur
NomHistorique = NomDossierJoueur & "\historique.xml"

If FileExists(NomHistorique) Then
    canal = FreeFile
    Open NomHistorique For Input As #canal
    Do Until EOF(canal)
        Line Input #canal, data
        DataFromFile = DataFromFile & data
    Loop
    Close canal
    If DataFromFile <> "" Then
        dernDate = CLng(GetVarXML(CStr(DataFromFile), CStr("UNIX")))
    Else
        dernDate = 0
    End If
End If






'MSFlexGrid1.AddItem "19/08/04 13:54:10" & Chr(9) & "AVENIR TELECOM" & Chr(9) & "Vente" & Chr(9) & "1376" & Chr(9) & "1.04 €( 1431.04 €)" & Chr(9) & "15.00 €" & Chr(9) & "1416.04 €"
data = CommNetTrader("gethisto", "do=lsthisto&depuis=" & dernDate)
Clipboard.SetText data
If GetVarXML(data, "erreur") = "faux" Then
    Champs = Array("dateexe", "LENOM", "LESENS", "LENOMBRE", "LEHT", "LATAXE", "LETTC") 'champs des données à recuperer
    TabHisto = GetTabXML(data, "historique", "ordre", Champs, DataFromFile) 'on transforme le xml en tableau
    If IsArray(TabHisto) Then 'on ajoute les lignes au tableau historique
        For i = 0 To UBound(TabHisto, 1)
            'ligne de la liste des actions
            MSFlexGrid1.AddItem TabHisto(i, 0) & Chr(9) & TabHisto(i, 1) & Chr(9) & TabHisto(i, 2) & Chr(9) & TabHisto(i, 3) & Chr(9) & TabHisto(i, 4) & Chr(9) & TabHisto(i, 5) & " €" & Chr(9) & TabHisto(i, 6) & " €"
        Next i
    End If
End If

'on enregistre
If Not DossierExiste(NomDossierJoueur, vbDirectory) Then
   MkDir NomDossierJoueur
End If

canal = FreeFile
Open NomHistorique For Output As #canal
Print #canal, GetVarXML(data, "historique") & DataFromFile
Close canal


End Sub

Private Sub Timer1_Timer()
If GlobalState.DoUpdateHisto = True Then
    GlobalState.DoUpdateHisto = False
    Call ShowHisto
End If

End Sub
