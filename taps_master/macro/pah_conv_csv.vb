Sub restructureCell()
    'Delete header cells
    'Range("A1:F12,A32:F45").Delete
    
    Rows("1:12").EntireRow.Delete
    
    
    'Delete footer cells
    firstRow = Range("A:Z").Find(What:="Notes").Row
    lastRow = Range("A:Z").Find(What:="******** end of report ********").Row
    'MsgBox firstRow & " " & lastRow
    Rows(firstRow & ":" & lastRow).EntireRow.Delete
    
    'Delete empty columns
    Delete_Columns
    
    'Insert a new column for Sample ID
    Range("A1").EntireColumn.Insert
     
    'MsgBox "C1: " & Len(Range("C1").Value)
    
    Dim fstPosition As String
    Dim fstPosition2 As String
    Dim lRow As Long
    Dim lColumn As Long
    Dim lstPosition As String
      
    'check co-located columns
    If (Len(Range("C1").Value) > 12) Then
        'MsgBox "It has co-located columns " & Len(Range("C1").Value)
        fstPosition = Range("A:Z").Find(What:="Compounds").Row + 1
        fstPosition2 = Range("A:Z").Find(What:="Compounds").Row + 1
        fstPosition = "B" & fstPosition
    
        'Get last row and last column
        lRow = Range("B1").End(xlDown).Row
        lColumn = Range("B1").End(xlToRight).Column
            
        'Transform column position to ASCII
        Select Case lColumn
            Case 1
                lstPosition = "A" & lRow
            Case 2
                lstPosition = "B" & lRow
            Case 3
                lstPosition = "C" & lRow
            Case 4
                lstPosition = "D" & lRow
            Case 5
                lstPosition = "E" & lRow
            Case 6
                lstPosition = "F" & lRow
            Case 7
                lstPosition = "G" & lRow
            Case 8
                lstPosition = "H" & lRow
            Case 9
                lstPosition = "I" & lRow
            Case 10
                lstPosition = "J" & lRow
            Case 11
                lstPosition = "K" & lRow
            Case Else
                MsgBox "Unknown Number"
        End Select
        
        'MsgBox fstPosition & " " & lstPosition
         
        
        'Copy set of sample data with different Sample ID
        Range(fstPosition & ":" & lstPosition).Copy Range("B" & lRow + 1)
        Range(fstPosition & ":" & lstPosition).Copy Range("B" & ((lRow * 2) - 1))
        
        
        'Copy sample ID into column A
        Range("C1").Copy Range("A" & fstPosition2 & ":A" & lRow)
        Range("D1").Copy Range("A" & lRow + 1 & ":A" & ((lRow * 2) - 2))
        Range("E1").Copy Range("A" & ((lRow * 2) - 1) & ":A" & ((lRow * 3) - 3))
        
        
        Range("B1").Copy Range("A2")
        Rows("1").EntireRow.Delete
        'Range("A1:F1").Delete
        'Copy sample data (Column D, E) into correct postition
        Range("D" & lRow & ":D" & ((lRow * 2) - 1)).Copy Range("C" & lRow & ":C" & ((lRow * 2) - 1))
        Range("E" & lRow & ":E" & ((lRow * 2) - 3)).Copy Range("C" & ((lRow * 2) - 2) & ":C" & ((lRow * 3) - 9))
                
            
        'Delete Last Column
        Delete_Last
        
        'Range("E1:F35").Delete
        'lstColumn = Range("A1").End(xlToRight).Column
        'Columns(lstColumn).EntireColumn.Delete
        
        'lstColumn = Range("A1").End(xlToRight).Column
        'Columns(lstColumn).EntireColumn.Delete
    Else
        fstPosition = Range("A:Z").Find(What:="Compounds").Row + 1
        fstPosition2 = Range("A:Z").Find(What:="Compounds").Row + 1
        fstPosition = "B" & fstPosition
            
        'Get last row and last column
        lRow = Range("B1").End(xlDown).Row
        lColumn = Range("B1").End(xlToRight).Column
            
        'Transform column position to ASCII
        Select Case lColumn
            Case 1
                lstPosition = "A" & lRow
            Case 2
                lstPosition = "B" & lRow
            Case 3
                lstPosition = "C" & lRow
            Case 4
                lstPosition = "D" & lRow
            Case 5
                lstPosition = "E" & lRow
            Case 6
                lstPosition = "F" & lRow
            Case 7
                lstPosition = "G" & lRow
            Case 8
                lstPosition = "H" & lRow
            Case 9
                lstPosition = "I" & lRow
            Case 10
                lstPosition = "J" & lRow
            Case 11
                lstPosition = "K" & lRow
            Case Else
                MsgBox "Unknown Number"
        End Select
        
        'MsgBox fstPosition & " " & lstPosition
         
        
        'Copy set of sample data with different Sample ID
        Range(fstPosition & ":" & lstPosition).Copy Range("B" & lRow + 1)
        
        'Copy sample ID into column A
        Range("C1").Copy Range("A" & fstPosition2 & ":A" & lRow)
        Range("D1").Copy Range("A" & lRow + 1 & ":A" & ((lRow * 2) - 2))
        Range("B1").Copy Range("A2")
        
        Rows("1").EntireRow.Delete
        'Range("A1:F1").Delete
        Range("D" & lRow & ":D" & ((lRow * 2) - 1)).Copy Range("C" & lRow & ":C" & ((lRow * 2) - 1))
            
        'Delete Last Column
        'Range("E1:F35").Delete
        lstColumn = Range("A1").End(xlToRight).Column
        Columns(lstColumn).EntireColumn.Delete
        
        lstColumn = Range("A1").End(xlToRight).Column
        Columns(lstColumn).EntireColumn.Delete
    End If
End Sub
Sub ChangeView()

Dim ws As Worksheet

For Each ws In ActiveWorkbook.Worksheets
    ws.Select
    ActiveWindow.View = xlNormalView
    Range("A1").Select
Next
End Sub

Sub Delete_Last()

    Dim Last As Long
    
    On Error Resume Next
    
    ' Last column
    Last = Cells.Find("*", , , , xlByColumns, xlPrevious).Column
    ' Delete last 3 columns
    If Last >= 3 Then Columns(Last - 2).Resize(, 3).Delete

    ' Last row
    Last = 0
    Last = Cells.Find("*", , , , xlByRows, xlPrevious).Row
    ' Delete last row
    If Last > 0 Then Rows(Last).Delete
    
    On Error GoTo 0
    
End Sub

Sub activeMacro()
    Sheets("Report pg2").Select
    restructureCell
End Sub

Sub Delete_Columns()
    Dim c As Integer
    c = ActiveSheet.Cells.SpecialCells(xlLastCell).Column
    
    Do Until c = 0
        If WorksheetFunction.CountA(Columns(c)) = 0 Then
            Columns(c).Delete
        End If
        c = c - 1
    Loop
End Sub

Sub sbLastRowOfAColumn()
'Find the last Row with data in a Column
'In this example we are finding the last row of column A
    Dim lastRow As Long
    With ActiveSheet
        lastRow = .Cells(.Rows.Count, "A").End(xlUp).Row
    End With
    MsgBox lastRow
    
    'sbLastRowOfAColumn
    
    'Dim LastCell As Range
    'Set LastCell = ActiveSheet.Cells.Find("*", SearchOrder:=xlByRows, SearchDirection:=xlPrevious)
    'MsgBox LastCell
End Sub

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
    
    ChangeView
End Sub

Sub Save_Excel_to_csv()
Dim ws1 As Worksheet
Dim path_1 As String
Application.ScreenUpdating = False
path_1 = ActiveWorkbook.Path & "" & Left(ActiveWorkbook.Name, InStr(ActiveWorkbook.Name, ".") - 1)
For Each ws1 In Worksheets
    If (ws1.Name = "Report pg2") Then
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
