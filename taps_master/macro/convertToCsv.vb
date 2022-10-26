Sub openWorkSheet()
    Application.EnableCancelKey = xlDisabled
    Application.ScreenUpdating = False
    
    actWBPath = Application.ActiveWorkbook.Path
    actWBName = ThisWorkbook.Name
    actSheetName = ActiveSheet.Name
    
    ChDrive actWBPath
    ChDir actWBPath
    
    archi = Dir("*.xls")
    
    Do While archi <> ""
    
    If archi <> actWBName Then
        Workbooks.Open archi, UpdateLinks:=0, ReadOnly:=True
        For Each Sheet In ActiveWorkbook.Sheets
            Sheet.Copy after:=ThisWorkbook.ActiveSheet
        Next Sheet
    
        Workbooks(archi).Close False
    End If
    
    archi = Dir()
    Loop
    
    Sheets(actSheetName).Select
    
    Application.ScreenUpdating = True
    
    DeleteHiddenSheets
End Sub

Sub Save_Excel_to_csv()
Dim ws1 As Worksheet
Dim path_1 As String
Application.ScreenUpdating = False
path_1 = ActiveWorkbook.Path & "" & Left(ActiveWorkbook.Name, InStr(ActiveWorkbook.Name, ".") - 1)
For Each ws1 In Worksheets
    If (ws1.Name <> "Macro") Then
        DelEmptyRows ws1
        DelEmptyCols ws1
        ws1.Copy
        ActiveWorkbook.SaveAs Filename:=path_1 & "" & ws1.Name & ".csv", FileFormat:=xlCSV, CreateBackup:=False
        ActiveWorkbook.Close False
    End If
Next
 Application.ScreenUpdating = True
End Sub

' deletes blank rows on the specified  worksheet.
' If the argument is omitted, ActiveSheet is processed
Public Sub DelEmptyRows(Optional ws As Worksheet = Nothing)
    Dim toDel As Range, rng As Range, r As Range
    
    If ws Is Nothing Then Set ws = ActiveSheet
    
    Set rng = ws.Range(ws.Cells(1, 1), ws.UsedRange)
    For Each r In rng.Rows
        If WorksheetFunction.CountA(r) = 0 Then
            If toDel Is Nothing Then
                Set toDel = r
            Else
                Set toDel = Union(toDel, r)
            End If
        End If
    Next
    If Not toDel Is Nothing Then toDel.EntireRow.Delete
End Sub

' deletes blank columns on the specified  worksheet.
' If the argument is omitted, ActiveSheet is processed
Public Sub DelEmptyCols(Optional ws As Worksheet = Nothing)
    Dim toDel As Range, rng As Range, c As Range
    
    If ws Is Nothing Then Set ws = ActiveSheet
    
    Set rng = ws.Range(ws.Cells(1, 1), ws.UsedRange)
    For Each c In rng.Columns
        If WorksheetFunction.CountA(c) = 0 Then
            If toDel Is Nothing Then
                Set toDel = c
            Else
                Set toDel = Union(toDel, c)
            End If
        End If
    Next
    If Not toDel Is Nothing Then toDel.EntireColumn.Delete
End Sub

Sub DeleteHiddenSheets()
'PURPOSE: Remove any hidden sheets from the active workbook
'SOURCE: www.TheSpreadsheetGuru.com/the-code-vault
'NOTE: This macro skips over Very Hidden sheets

Dim Removals As Long

'Turn Off Alerts
  Application.DisplayAlerts = False

'Loop Through Each Sheet in Workbook
  For Each sht In ActiveWorkbook.Sheets
    'Test if Sheeet is Hidden
      If sht.Visible = xlSheetHidden Then
        sht.Delete
        Removals = Removals + 1
      End If
  Next sht

'Turn Alerts Back On
  Application.DisplayAlerts = True

'Report How Many Were Removed
  If Removals = 1 Then
    MsgBox "[1] sheet was removed from this workbook."
  Else
    MsgBox "[" & Removals & "] sheets were removed from this workbook."
  End If

End Sub

