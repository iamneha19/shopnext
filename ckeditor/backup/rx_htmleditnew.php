<!-- rx_htmledit.php -->
<SCRIPT language=javascript>

                                var ae_alloweditsource = new Array();
                                var ae_fieldname = new Array();
                                var ae_imagedata = new Array();
                                var ae_imageurl = new Array();
                                var ae_defaultfont = new Array();
                                var ae_baseurl = new Array();
                                var ae_breakonenter = new Array();
                                var ae_xhtml = new Array();
                                var ae_tabview = new Array();
                                var ae_inc = 'inc/';
                                var quickformatNameArray = new Array();
                                var ae_stylesheet = new Array();
                                var ae_quickfontcolors = new Array();
                                var ae_backgroundcolor = new Array();
                                var fileType = 'cfm';


                                var errlevel = 2;
                                var ae_version="5.0.52";

                                ae_breakonenter[1] = true;
                                ae_baseurl[1] = "";
                                ae_defaultfont[1] = "10pt verdana";
                                ae_tabview[1] = true;
                                ae_alloweditsource[1] = true;
                                ae_imagedata[1] = '9BA5F688D2F9B6058AF46EF4C523173C';
                                ae_imageurl[1] = 'inc';
                                ae_stylesheet[1] = 'http://cms.mid-day.com/inc/example_style.css';
                                ae_xhtml[1] = true;
                                ae_backgroundcolor[1] = '';
                                quickformatNameArray[1] = new Array();
                                        quickformatNameArray[1][0] = "Normal";
                                        quickformatNameArray[1][1] = "Formatted";
                                        quickformatNameArray[1][2] = "Address";
                                        quickformatNameArray[1][3] = "Heading 1";
                                        quickformatNameArray[1][4] = "Heading 2";
                                        quickformatNameArray[1][5] = "Heading 3";
                                        quickformatNameArray[1][6] = "Heading 4";
                                        quickformatNameArray[1][7] = "Heading 5";
                                        quickformatNameArray[1][8] = "Heading 6";
                                        quickformatNameArray[1][9] = "Numbered List";
                                        quickformatNameArray[1][10] = "Bulleted List";
                                        quickformatNameArray[1][11] = "Directory List";
                                        quickformatNameArray[1][12] = "Menu List";
                                        quickformatNameArray[1][13] = "Definition Term";
                                        quickformatNameArray[1][14] = "Definition";
                                        quickformatNameArray[1][15] = "Paragraph";
                        </SCRIPT>

<SCRIPT language=JavaScript src="inc/spell.js"></SCRIPT>

<SCRIPT language=JavaScript src="inc/base.js"
type=text/javascript></SCRIPT>
<script src="inc/speller/spellChecker.js"></script>
<script>
function openSpellChecker() {
        var speller = new spellChecker();
        var spellerInputs = new Array();
	//alert(aeObjects[1].contentDocument.body.innerHTML);
        spellerInputs[0] = aeObjects[1].contentDocument.body.innerText;
        speller.textInputs = spellerInputs;
        speller.openChecker();
}
function setSpellValue(value) {
	aeObjects[1].contentDocument.body.innerText = value;
}
</script>
<script>
if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){ //test for MSIE x.x;
	document.write("<SCRIPT language=javascript src='inc/msie.js' type=text/javascript>");
}else {
	document.write("<SCRIPT language=javascript src='inc/gecko.js' type=text/javascript>");
}
</script> 
<SCRIPT language=JavaScript>
                        //declare global javascript variables
                        if (errlevel == 2){
                                //error catching
                                document.write("<BR>The file editjs.cfm did not load properly. Make sure the the inc attribute is specified correctly<br>");
                                document.write("Current Value: inc=" + ae_inc + "<br>");
                                document.write("<comment>"); // Keeps the rest of the page from displaying
                        }
                </SCRIPT>

<SCRIPT language=javascript src="inc/user.js"></SCRIPT>

<STYLE type=text/css>.ae_disabled {
        BORDER-RIGHT: buttonface 1px solid; PADDING-RIGHT: 0px; BORDER-TOP: buttonface 1px solid; PADDING-LEFT: 0px; VISIBILITY: visible; PADDING-BOTTOM: 0px; MARGIN: 0px; VERTICAL-ALIGN: middle; BORDER-LEFT: buttonface 1px solid; WIDTH: 23px; PADDING-TOP: 0px; BORDER-BOTTOM: buttonface 1px solid; HEIGHT: 22px; BACKGROUND-COLOR: buttonface; -moz-opacity: .25; opacity: .25
}
.ae_flat {
        BORDER-RIGHT: buttonface 1px solid; PADDING-RIGHT: 0px; BORDER-TOP: buttonface 1px solid; PADDING-LEFT: 0px; VISIBILITY: visible; PADDING-BOTTOM: 0px; MARGIN: 0px; VERTICAL-ALIGN: middle; BORDER-LEFT: buttonface 1px solid; WIDTH: 23px; PADDING-TOP: 0px; BORDER-BOTTOM: buttonface 1px solid; HEIGHT: 22px
}
.ae_outset {
        BORDER-RIGHT: buttonshadow 1px outset; PADDING-RIGHT: 0px; BORDER-TOP: buttonshadow 1px outset; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; MARGIN: 0px; VERTICAL-ALIGN: middle; BORDER-LEFT: buttonshadow 1px outset; WIDTH: 23px; PADDING-TOP: 0px; BORDER-BOTTOM: buttonshadow 1px outset; HEIGHT: 22px; BACKGROUND-COLOR: buttonface
}
.ae_inset {
        BORDER-RIGHT: buttonshadow 1px inset; PADDING-RIGHT: 0px; BORDER-TOP: buttonshadow 1px inset; PADDING-LEFT: 0px; VISIBILITY: visible; PADDING-BOTTOM: 0px; MARGIN: 0px; VERTICAL-ALIGN: middle; BORDER-LEFT: buttonshadow 1px inset; WIDTH: 23px; PADDING-TOP: 0px; BORDER-BOTTOM: buttonshadow 1px inset; HEIGHT: 22px
}
.ae_latched {
        BORDER-RIGHT: buttonshadow 1px inset; PADDING-RIGHT: 0px; BORDER-TOP: buttonshadow 1px inset; PADDING-LEFT: 0px; VISIBILITY: visible; PADDING-BOTTOM: 0px; MARGIN: 0px; VERTICAL-ALIGN: middle; BORDER-LEFT: buttonshadow 1px inset; WIDTH: 23px; PADDING-TOP: 0px; BORDER-BOTTOM: buttonshadow 1px inset; HEIGHT: 22px; BACKGROUND-COLOR: #dddddd
}
.ae_spc {
        PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; MARGIN: 0px; VERTICAL-ALIGN: middle; PADDING-TOP: 0px
}
TABLE.ae_tableClass {
        BORDER-RIGHT: buttonface 1px outset; BORDER-TOP: buttonface 1px outset; BORDER-LEFT: buttonface 1px outset; CURSOR: default; BORDER-BOTTOM: buttonface 1px outset; BACKGROUND-COLOR: buttonface
}
TR.ae_trClass {
        BACKGROUND-COLOR: buttonface
}
TD.ae_tdClass {
        BACKGROUND-COLOR: buttonface
}
.ae_menuspacer {
        BORDER-TOP: buttonshadow 1px inset; MARGIN-TOP: 4px; MARGIN-BOTTOM: 4px; CURSOR: default
}
.ae_menuitem {
        PADDING-RIGHT: 20px; PADDING-LEFT: 20px; FONT-SIZE: 9pt; PADDING-BOTTOM: 4px; CURSOR: default; PADDING-TOP: 4px; FONT-FAMILY: Arial,Helvetica,sans-serif
}
.ae_menuitem_over {
        PADDING-RIGHT: 20px; PADDING-LEFT: 20px; FONT-SIZE: 9pt; PADDING-BOTTOM: 4px; CURSOR: default; PADDING-TOP: 4px; FONT-FAMILY: Arial,Helvetica,sans-serif; BACKGROUND-COLOR: #aaaaff
}
</STYLE>

<DIV id=ae_rightmenu
style="BORDER-RIGHT: buttonshadow 1px outset; PADDING-RIGHT: 4px; BORDER-TOP: buttonshadow 1px outset; PADDING-LEFT: 4px; VISIBILITY: hidden; PADDING-BOTTOM: 4px; BORDER-LEFT: buttonshadow 1px outset; CURSOR: default; PADDING-TOP: 4px; BORDER-BOTTOM: buttonshadow 1px outset; POSITION: absolute; BACKGROUND-COLOR: buttonface"></DIV>
<DIV id=ae_popup
style="BORDER-RIGHT: buttonshadow 1px outset; PADDING-RIGHT: 4px; BORDER-TOP: buttonshadow 1px outset; PADDING-LEFT: 4px; FONT-SIZE: 10pt; VISIBILITY: hidden; PADDING-BOTTOM: 4px; BORDER-LEFT: buttonshadow 1px outset; PADDING-TOP: 4px; BORDER-BOTTOM: buttonshadow 1px outset; FONT-FAMILY: Arial,Helvetica; POSITION: absolute; BACKGROUND-COLOR: #ffffcc"></DIV>
<TABLE class=ae_tableClass onselectstart=event.returnValue=false;
ondragstart=event.returnValue=false; style="PADDING-BOTTOM: 4px" height=400
cellSpacing=0 cellPadding=0 width=800 bgColor=buttonface>

  <TBODY>
  <TR class=ae_trClass>
    <TD class=ae_tdClass vAlign=bottom width="100%" colSpan=2><IFRAME
      id=quicktable
      style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; Z-INDEX: 2; VISIBILITY: hidden; BORDER-LEFT: medium none; WIDTH: 10px; BORDER-BOTTOM: medium none; POSITION: absolute; HEIGHT: 10px"
      marginWidth=0 marginHeight=0 src="inc/blank.htm"
      scrolling=no></IFRAME>
      <TABLE cellSpacing=2 cellPadding=0 border=0>
        <TBODY>
        <TR vAlign=center>
          <TD width=10 background=inc/toolbar.gif><IMG
            height=24 alt="" src="inc/spacer.gif"
          width=9></TD>
          <TD><SPAN onmouseup=ae_m_up(event);
            onselectstart=event.returnValue=false; onmousedown=ae_m_down(event);
            id=ae_tbar1 ondragstart=event.returnValue=false;
            onmouseover=ae_m_over(event); onmouseout=ae_m_out(event);><NOBR><IMG
            class=ae_flat onmousedown="return ae_onCommand(event, 'Font', 1);"
            title=Font alt=Font src="inc/fgcolor.gif"
            cid="Font" tag="btn" status="Change font and color"><IMG
            class=ae_flat onmousedown="return ae_onCommand(event, 'Bold', 1);"
            title=Bold alt=Bold src="inc/bold.gif"
            cid="Bold" tag="btn" status="Make selected text bold"><IMG
            class=ae_flat onmousedown="return ae_onCommand(event, 'Italic', 1);"
            title=Italic alt=Italic src="inc/italic.gif"
            cid="Italic" tag="btn" status="Make selected text italic"><IMG
            class=ae_flat
            onmousedown="return ae_onCommand(event, 'Underline', 1);"
            title=Underline alt=Underline
            src="inc/under.gif" cid="Underline" tag="btn"
            status="Underline the selected text"><IMG class=ae_spc alt=""
            src="inc/space.gif"><WBR><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'Undo', 1);" title=Undo
            alt=Undo src="inc/undo.gif" cid="Undo"
            tag="btn" status="Undo the last operation"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'Redo', 1);" title=Redo
            alt=Redo src="inc/redo.gif" cid="Redo"
            tag="btn" status="Redo the last operation"><IMG class=ae_spc alt=""
            src="inc/space.gif"><WBR><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'Outdent', 1);"
            title="Decrease Indent" alt="Decrease Indent"
            src="inc/deindent.gif" cid="Outdent" tag="btn"
            status="Reverse indent the current line"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'Indent', 1);" title=Indent
            alt=Indent src="inc/inindent.gif" cid="Indent"
            tag="btn" status="Indent the current line"><IMG class=ae_spc alt=""
            src="inc/space.gif"><WBR><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'JustifyLeft', 1);"
            title="Align Left" alt="Align Left"
            src="inc/left.gif" cid="JustifyLeft" tag="btn"
            status="Left justify the current line"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'JustifyCenter', 1);"
            title=Center alt=Center src="inc/center.gif"
            cid="JustifyCenter" tag="btn" status="Center the current line"><IMG
            class=ae_flat
            onmousedown="return ae_onCommand(event, 'JustifyRight', 1);"
            title="Align Right" alt="Align Right"
            src="inc/right.gif" cid="JustifyRight"
            tag="btn" status="Right justify the current line"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'InsertUnorderedList', 1);"
            title=Bullets alt=Bullets
            src="inc/bullist.gif"
            cid="InsertUnorderedList" tag="btn"
            status="Insert a bullet on current line"><IMG class=ae_spc alt=""
            src="inc/space.gif"><WBR><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'InsertTable', 1);"
            id=ae_tbtn1 ondragstart="return false"
            onmouseover=ae_m_over(ae_tdrop1); title="Insert Table"
            onmouseout=ae_m_out(ae_tdrop1); alt="Insert Table"
            src="inc/instable.gif" cid="InsertTable"
            tag="btn" status="Insert a table"><IMG class=ae_flat
            onmousedown=ae_bs_saveRange(1); ondragstart="return false"
            onmouseover=ae_m_over(ae_tbtn1); title="Quick Table"
            style="WIDTH: 9px" onclick=onTable(1);
            onmouseout=ae_m_out(ae_tbtn1); alt="Quick Table"
            src="inc/tbdown.gif" name=ae_tdrop1 cid="5022"
            tag="btn" status="Insert a table"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'InsertImage', 1);"
            title="Insert Image" alt="Insert Image"
            src="inc/image.gif" cid="InsertImage"
            tag="btn" status="Insert an image"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'Hyperlink', 1);"
            title="Insert Hyperlink" alt="Insert Hyperlink"
            src="inc/link.gif" cid="Hyperlink" tag="btn"
            status="Insert a hyperlink on selected text"><IMG class=ae_spc
            alt="" src="inc/space.gif"><WBR>
	  <!-- <IMG   class=ae_flat  onmousedown="return ae_onCommand(event, 'SpellCheck', 1);" title="Spell Check" alt="Spell Check" src="inc/spell.gif" cid="SpellCheck" tag="btn"  status="Open spell check window"> -->
	   <IMG   class=ae_flat  onmousedown="return openSpellChecker();" title="Spell Check" alt="Spell Check" src="inc/spell.gif" cid="SpellCheck" tag="btn"  status="Open spell check window"> 
	   <IMG class=ae_spc alt=""  src="inc/space.gif"><WBR><IMG class=ae_flat  onmousedown="return ae_onCommand(event, 'Subscript', 1);" 
            title=Subscript alt=Subscript
            src="inc/subscript.gif" cid="Subscript"
            tag="btn" status="Subscript"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'Superscript', 1);"
            title=Superscript alt=Superscript
            src="inc/superscript.gif" cid="Superscript"
            tag="btn" status="Superscript"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'Strike', 1);" title=Strike
            alt=Strike src="inc/strike.gif" cid="Strike"
            tag="btn" status="Strike the selected text"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'Highlight', 1);"
            title=Highlight alt=Highlight
            src="inc/highlight.gif" cid="Highlight"
            tag="btn" status="Highlight the selected text"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'RemoveFormat', 1);"
            title="Remove Format" alt="Remove Format"
            src="inc/removeformat.gif" cid="RemoveFormat"
            tag="btn" status="Remove Formatting"><IMG class=ae_flat
            onmousedown="return ae_onCommand(event, 'Replace', 1);"
            title=Replace alt=Replace
            src="inc/replace.gif" cid="Replace" tag="btn"
            status="Replace Text"><BR><IMG style="VERTICAL-ALIGN: middle"
            height=24 alt="" src="inc/spacer.gif" width=1>
            <SELECT onmousedown=ae_bs_saveRange(1); id=oQuickStyle1
            style="FONT: 8pt Arial; VERTICAL-ALIGN: middle; WIDTH: 120px"
            onchange=ae_quickstyle(1); size=1> <OPTION value=none
              selected>Select Style</OPTION>
                <OPTION value=title>Title</OPTION>
              <OPTION value=emphasize>Emphasize</OPTION>
             <OPTION value=ae_removeStyle>Remove Style</OPTION>
           </SELECT> <IMG  style="VERTICAL-ALIGN: middle" height=24 alt=""
            src="inc/spacer.gif" width=1> <SELECT
            onmousedown=ae_bs_saveRange(1); id=oQuickFormat1
            style="FONT: 8pt Arial; VERTICAL-ALIGN: middle; WIDTH: 120px"
            onchange=ae_quickformat(1); size=1></SELECT> <IMG
            style="VERTICAL-ALIGN: middle" height=24 alt=""
            src="inc/spacer.gif" width=1> <SELECT
            onmousedown=ae_bs_saveRange(1); id=oQuickFont1
            style="FONT: 8pt Arial; VERTICAL-ALIGN: middle; WIDTH: 120px"
            onchange=ae_quickfont(1); size=1> <OPTION value=Arial
              selected>Arial</OPTION> <OPTION value="Courier New">Courier
              New</OPTION> <OPTION value=Helvetica>Helvetica</OPTION> <OPTION
              value="Times New Roman">Times New Roman</OPTION> <OPTION
              value=Verdana>Verdana</OPTION></SELECT> <IMG
            style="VERTICAL-ALIGN: middle" height=24 alt=""
            src="inc/spacer.gif" width=1> <SELECT
            onmousedown=ae_bs_saveRange(1); id=oQuickFontSize1
            style="FONT: 8pt Arial; VERTICAL-ALIGN: middle; WIDTH: 45px"
            onchange=ae_quickfontsize(1); size=1> <OPTION value=8
              selected>8</OPTION> <OPTION value=10>10</OPTION> <OPTION
              value=12>12</OPTION> <OPTION value=14>14</OPTION> <OPTION
              value=18>18</OPTION> <OPTION value=24>24</OPTION> <OPTION
              value=36>36</OPTION></SELECT> <IMG style="VERTICAL-ALIGN: middle"
            height=24 alt="" src="inc/spacer.gif" width=1>
            <SELECT onmousedown=ae_bs_saveRange(1); id=oQuickFontColors1
            style="FONT: 8pt Arial; VERTICAL-ALIGN: middle; WIDTH: 120px"
            onchange=ae_quickFontColor(1); size=1> <OPTION
              value="Quick Font Colors" selected>Text Colors</OPTION> <OPTION
              value=red>red</OPTION> <OPTION value=blue>blue</OPTION> <OPTION
              value=green>green</OPTION> <OPTION value=orange>orange</OPTION>
              <OPTION value=gray>gray</OPTION> <OPTION
              value=silver>silver</OPTION> <OPTION value=black>black</OPTION>
              <OPTION value=white>white</OPTION></SELECT> </NOBR></SPAN><IMG
            height=1 alt="" src="inc/spacer.gif"
          width=1></TD></TR></TBODY></TABLE></TD>
    <TD class=ae_tdClass>&nbsp;</TD></TR>
  <TR class=ae_trClass>
    <TD class=ae_tdClass>&nbsp;</TD>
    <TD class=ae_tdClass
    style="PADDING-RIGHT: 1px; PADDING-LEFT: 1px; PADDING-BOTTOM: 1px; PADDING-TOP: 1px"
    width="100%" height="100%"><IFRAME id=ae_frame1
      style="WIDTH: 100%; HEIGHT: 100%; BACKGROUND-COLOR: #ffffff"
      src="inc/blank.htm"></IFRAME></TD>
    <TD class=ae_tdClass>&nbsp;</TD></TR>
  <TR class=ae_trClass>
    <TD class=ae_tdClass colSpan=3>
      <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
        <TBODY>
        <TR>
          <TD vAlign=top><IMG height=1 alt=""
            src="inc/spacer.gif" width=20><IMG
            onclick="ae_editsourceinline(1, false)" height=11
            alt="Visual Edit Mode"
            src="inc/normaltabon.gif" width=49 border=0
            name=normaltab1><IMG onclick="ae_editsourceinline(1, true)"
            height=11 alt="HTML Edit Mode"
            src="inc/htmltaboff.gif" width=41 border=0
            name=htmltab1></TD>
          <TD vAlign=top align=right><A
            style="COLOR: gray; TEXT-DECORATION: none"
            href="javascript:ae_cmd_About()"><SPAN
            style="FONT-SIZE: 10px; FONT-FAMILY: Arial">
</SPAN></A></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE><TEXTAREA id="ae_tx_content1" style="POSITION: absolute; VISIBILITY: hidden" name="content">
<? 
	//if($xContent) {
	echo($xContent);
//}else {
//	echo "Type here";
//}
?>
        </TEXTAREA>
<SCRIPT>ae_initialize(1);</SCRIPT>

