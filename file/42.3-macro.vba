    Sub FormSubmit()
        ' submits data in the form to the database worksheet
        ' shortcut: ctrl + shift + s
        
        ' select the form sheet, which is redundant in all likelihood, but adds stability
        Sheets("Form").Select
        
        ' unprotect and unhide workbook and database sheet
        ActiveWorkbook.Unprotect Password:="password"
        Sheets("Form Database").Visible = True
        Sheets("Form Database").Unprotect Password:="password"

        ' copying form data to database sheet
        Range("E6").Select                      ' move to data input
        Selection.Copy                          ' copy data
        Sheets("Form Database").Select          ' move to database sheet
        Range("A1").Select                      ' move to storage cell
        Selection.PasteSpecial Paste:=xlPasteValues, Operation:=xlNone, SkipBlanks _
            :=False, Transpose:=False           ' paste data as actual values (e.g., pastes date
                                                ' as text, rather than as a formula)

        ' repeat above block for each input field
        Sheets("Form").Select
        Range("E8").Select
        Application.CutCopyMode = False
        Selection.Copy
        Sheets("Form Database").Select
        Range("B1").Select
        Selection.PasteSpecial Paste:=xlPasteValues, Operation:=xlNone, SkipBlanks _
            :=False, Transpose:=False

        Sheets("Form").Select
        Range("E10").Select
        Application.CutCopyMode = False
        Selection.Copy
        Sheets("Form Database").Select
        Range("C1").Select
        Selection.PasteSpecial Paste:=xlPasteValues, Operation:=xlNone, SkipBlanks _
            :=False, Transpose:=False

        Sheets("Form").Select
        Range("E12").Select
        Application.CutCopyMode = False
        Selection.Copy
        Sheets("Form Database").Select
        Range("D1").Select
        Selection.PasteSpecial Paste:=xlPasteValues, Operation:=xlNone, SkipBlanks _
            :=False, Transpose:=False

        Sheets("Form").Select
        Range("E14").Select
        Application.CutCopyMode = False
        Selection.Copy
        Sheets("Form Database").Select
        Range("E1").Select
        Selection.PasteSpecial Paste:=xlPasteValues, Operation:=xlNone, SkipBlanks _
            :=False, Transpose:=False

        Sheets("Form").Select
        Range("E16").Select
        Application.CutCopyMode = False
        Selection.Copy
        Sheets("Form Database").Select
        Range("F1").Select
        Selection.PasteSpecial Paste:=xlPasteValues, Operation:=xlNone, SkipBlanks _
            :=False, Transpose:=False

        Sheets("Form").Select
        Range("E18").Select
        Application.CutCopyMode = False
        Selection.Copy
        Sheets("Form Database").Select
        Range("G1").Select
        Selection.PasteSpecial Paste:=xlPasteValues, Operation:=xlNone, SkipBlanks _
            :=False, Transpose:=False

        Sheets("Form").Select
        Range("E20").Select
        Application.CutCopyMode = False
        Selection.Copy
        Sheets("Form Database").Select
        Range("H1").Select
        Selection.PasteSpecial Paste:=xlPasteValues, Operation:=xlNone, SkipBlanks _
            :=False, Transpose:=False

        Sheets("Form").Select
        Range("E22").Select
        Application.CutCopyMode = False
        Selection.Copy
        Sheets("Form Database").Select
        Range("I1").Select
        Selection.PasteSpecial Paste:=xlPasteValues, Operation:=xlNone, SkipBlanks _
            :=False, Transpose:=False

        ' insert row above new results, pushing all results down (prep for next submission)
        ActiveCell.EntireRow.Insert

        ' move information on the right back into place
        Range("K2:K14").Select
        Selection.Cut Destination:=Range("K1:K13")

        ' shift selection to A1 in database sheet, for orgnisational and performance purposes
        Range("A1").Select

        ' re-protect and re-hide database sheet
        Sheets("Form Database").Protect Password:="password"
        Sheets("Form Database").Visible = False
        ActiveWorkbook.Protect Password:="password", Structure:=True, Windows:=True

        ' clear most form cells (not date or money spent)
        Sheets("Form").Select
        Range("E8,E10,E12,E14,E16,E18,E22").Select
        Range("E22").Activate
        Selection.ClearContents

        ' set money spent to 0 (appears as a hyphen)
        Range("E20").Select
        Selection.FormulaR1C1 = "0"

        ' return focus to first input cell
        Range("E8").Select
        
        ' save our edited workbook
        ActiveWorkbook.Save

        ' be nice and sociable; show a sucess dialogue window on screen
        MsgBox "Your information was submitted sucessfully. Thank-you for participating!"
    End Sub
