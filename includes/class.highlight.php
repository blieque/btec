<?php

/* highlight:
 *
 * Takes plain text and highlights its syntax, returning HTML. The language is
 * defined by the function that is called.
 * 
 */

class Highlight {

	public function vba($input)	{
	
		$output	= ['<link rel="stylesheet" href="/btec/css/highlight-common.css">'];

		# things to match

		$keywords	= ["AddHandler", "AddressOf", "Alias", "And", "AndAlso", "As", "Boolean", "ByRef", "Byte", "ByVal", "Call", "Case", "Catch", "CBool", "CByte", "CChar", "CDate", "CDec", "CDbl", "Char", "CInt", "Class", "CLng", "CObj", "Const", "Continue", "CSByte", "CShort", "CSng", "CStr", "CType", "CUInt", "CULng", "CUShort", "Date", "Decimal", "Declare", "Default", "Delegate", "Dim", "DirectCast", "Do", "Double", "Each", "Else", "ElseIf", "End", "EndIf", "Enum", "Erase", "Error", "Event", "Exit", "Finally", "For", "Friend", "Function", "Get", "GetType", "GetXMLNamespace", "Global", "GoSub", "GoTo", "Handles", "If", "If()", "Implements", "Imports", "In", "Inherits", "Integer", "Interface", "Is", "IsNot", "Let", "Lib", "Like", "Long", "Loop", "Me", "Mod", "Module", "MustInherit", "MustOverride", "MyBase", "MyClass", "Namespace", "Narrowing", "New", "Next", "Not", "Nothing", "NotInheritable", "NotOverridable", "Object", "Of", "On", "Operator", "Option", "Optional", "Or", "OrElse", "Overloads", "Overridable", "Overrides", "ParamArray", "Partial", "Private", "Property", "Protected", "Public", "RaiseEvent", "ReadOnly", "ReDim", "REM", "RemoveHandler", "Resume", "Return", "SByte", "Select", "Set", "Shadows", "Shared", "Short", "Single", "Static", "Step", "Stop", "String", "Structure", "Sub", "SyncLock", "Then", "Throw", "To", "Try", "TryCast", "TypeOf", "Variant", "Wend", "UInteger", "ULong", "UShort", "Using", "When", "While", "Widening", "With", "WithEvents", "WriteOnly", "Xor", '#Const', '#Else', '#ElseIf', '#End', '#If'];
		$operator_s	= ["&", "&=", "*", "*=", "+", "+=", "=", "-", "-=", "<<", "<<=", ">>", ">>=", "/", "/=", "\\", "\\=", "^", "^="];
		$operator_w	= ["AddressOf", "And", "AndAlso", "Function", "GetType", "GetXmlNamespace", "If", "Is", "IsFalse", "IsNot", "IsTrue", "Like", "Mod", "Not", "Or", "OrElse", "TypeOf", "Xor"];
		$booleans	= ["True", "False"];
		$data_types	= ["Boolean", "Byte", "Char", "Date", "Decimal", "Double", "Integer", "Long", "Object", "SByte", "Short", "Single", "String", "UInteger", "ULong", "User-Defined", "UShort"];
		$constants	= ["CONFIG", "DEBUG", "TARGET", "TRACE", "VBC_VER", "_MYTYPE", "Print", "Display", "AppWinStyle", "AudioPlayMode", "BuiltInRole", "CallType", "CompareMethod", "DateFormat", "DateInterval", "DeleteDirectoryOption", "DueDate", "FieldType", "FileAttribute", "FirstDayOfWeek", "FirstWeekOfYear", "MsgBoxResult", "MsgBoxStyle", "OpenAccess", "OpenMode", "OpenShare", "RecycleOption", "SearchOption", "TriState", "UICancelOption", "UIOption", "VariantType", "VbStrConv"];
		$functions	= ["asc", "chr", "curdir", "instr", "instrrev", "lcase", "left", "len", "ltrim", "mid", "replace", "right", "rtrim", "space", "str", "strcomp", "strconv", "trim", "ucase", "val", "abs", "atn", "cos", "exp", "fix", "int", "log", "rnd", "round", "sgn", "sin", "tan", "val", "and", "case", "if", "else", "or", "isdate", "iserror", "isnull", "isnumeric", "date", "dateadd", "datediff", "datepart", "dateserial", "datevalue", "day", "format", "hour", "minute", "month", "monthname", "now", "timeserial", "timevalue", "weekday", "weekdayname", "year", "cbool", "cbyte", "ccur", "cdate", "cdbl", "cdec", "cint", "clng", "csng", "cstr", "cvar", "ddb", "fv", "ipmt", "irr", "mirr", "nper", "npv", "pmt", "ppmt", "pv", "rate", "sln", "syd", "chdir", "chdrive", "curdir", "dir", "filedatetime", "filelen", "getattr", "mkdir", "setattr", "choose", "switch"];

		# let the highlighting begin
		
		// $input	= preg_replace("/('.*?)ESCAPED-NEWLINE/", '<span class="comment">$1</span>ESCAPED-NEWLINE', $input);

		array_push($output, $input);
		
		return $output;

	}

}
