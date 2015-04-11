Option Compare Database


' Click event handler for PaymentMade on tbl_bookings

' This function generates a random ID for a new booking when the user clicks
' in the "Payment Made" field.

Private Sub PaymentMade_Click()

    ' only create an id if it's needed
    If IsNull([BookingID].Value) Then

        ' variable declarations
        Dim Chars As String
        Dim Alpha As String
            Alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
        Dim Ref As Integer
        
        Dim Digits As Integer
        Dim DigitsStr As String

        ' add a random letter on, four times
        For n = 1 To 4
            Randomize Timer
            Ref = Int(26 * Rnd) + 1
            Chars = Chars & Mid(Alpha, Ref, 1)
        Next

        ' generate a random number
        Randomize Timer
        Digits = Int(10000 * Rnd) ' 0-9999

        ' convert number to string
        DigitsStr = Trim(Str(Digits))

        ' zero-pad id string
        For j = 1 To 4 - Len(DigitsStr)
            DigitsStr = "0" & DigitsStr
        Next j

        ' concatenate the four letters and four digits
        [BookingID] = Chars & DigitsStr

    End If

End Sub


' AfterUpdate event handler for NameLast on tbl_customers

' This function will fire after the content of the NameLast field has been
' changed. The function takes the surname of the customer and a random number,
' and generates a (likely) unique customer ID.

Private Sub NameLast_AfterUpdate()

    ' variable definitions
    Dim CustNameClip As String
    Dim CustId As Integer
    Dim CustIdStr As String

    ' cut the first four characters, and convert to upper-case
    CustNameClip = UCase([NameLast].Value)
    CustNameClip = Replace(CustNameClip, " ", "")
    CustNameClip = Replace(CustNameClip, "'", "")
    CustNameClip = Left(CustNameClip, 3)
    
    ' pad name string
    For i = 1 To 3 - Len(CustNameClip)
        CustNameClip = CustNameClip & "X"
    Next i

    ' generate a random number
    Randomize Timer
    CustId = Int(10000 * Rnd) ' 0-9999
    
    ' convert number to string
    CustIdStr = Trim(Str(CustId))

    ' zero-pad id string
    For j = 1 To 4 - Len(CustIdStr)
        CustIdStr = "0" & CustIdStr
    Next j

    ' concatenate the two halves of the id, then set id field to output value
    [ID] = Trim(CustNameClip) & Trim(CustIdStr)

End Sub
