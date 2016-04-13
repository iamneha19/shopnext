<?
ob_start();
require("include/config.php");
require("include/xmlconfig.php");

/**
 * include the slideshow class
 */
require ("classes/slideshow.class.php");

/**
 * class object: slideshow class
 */
$oSlideshow = new slideshow();


include("html/header.php");
$copydate = $_REQUEST['copydate'];
$copydate1 = $_REQUEST['copydate1'];


/*
  <!-- rx_add_article.php -->
  <!-- params- aid if edit mode -->
  <!-- add article to the repository -->
 */
$pubdate = $_REQUEST['pubdate'];
$stateof = $_REQUEST['stateof'];
$copydate = $_REQUEST['copydate'];
$copydate1 = $_REQUEST['copydate1'];
$seldate = $_REQUEST['seldate'];
$seldate1 = $_REQUEST['seldate1'];
$DateDiff = $_REQUEST['DateDiff'];
$rdpst = $_REQUEST['rdpst'];
$rdpub = $_REQUEST['rdpub'];
$rdver = $_REQUEST['rdver'];
$artid = $_REQUEST['artid'];
$killed = $_REQUEST['killed'];
$aid = $_REQUEST['aid'];
$ed = $_REQUEST['ed'];
$eslug = $_REQUEST['eslug'];
$econtent = $_REQUEST['econtent'];
$eindexh = $_REQUEST['eindexh'];
$eleadimg = $_REQUEST['eleadimg'];
$eleadimgcap = $_REQUEST['eleadimgcap'];
$emobileh = $_REQUEST['emobileh'];
$eabstract = $_REQUEST['eabstract'];
$ecolumn = $_REQUEST['ecolumn'];
$ebyline = $_REQUEST['ebyline'];
$eplace = $_REQUEST['eplace'];
$epub = $_REQUEST['epub'];
$eregion = $_REQUEST['eregion'];
$CurrentDate = $_REQUEST['CurrentDate'];
$SortOrder = $_REQUEST['SortOrder'];
$DateSortOrder = $_REQUEST['DateSortOrder'];
$PubSortOrder = $_REQUEST['PubSortOrder'];
$SlugSortOrder = $_REQUEST['SlugSortOrder'];
$AuthorSortOrder = $_REQUEST['AuthorSortOrder'];
$StatusSortOrder = $_REQUEST['StatusSortOrder'];
$slug = $_REQUEST['slug'];
$copyh = $_REQUEST['copyh'];
$indexh = $_REQUEST['indexh'];
$mobileh = $_REQUEST['mobileh'];
$subheading = $_REQUEST['subheading'];
$note = $_REQUEST['note'];
$leadImg = $_REQUEST['leadImg'];
$leadImgCap = $_REQUEST['leadImgCap'];
$abstract = $_REQUEST['abstract'];
$TagName = $_REQUEST['TagName'];
$byline = $_REQUEST['byline'];
$region = $_REQUEST['region'];
$gacat = $_REQUEST['gacat'];
$dateline = $_REQUEST['dateline'];
$datelineplace = $_REQUEST['datelineplace'];
$Interviewee = $_REQUEST['Interviewee'];
$moddate = $_REQUEST['moddate'];
$xfile = $_REQUEST['xfile'];
$xyear = $_REQUEST['xyear'];
$xmonth = $_REQUEST['xmonth'];
$pd = $_REQUEST['pd'];
$pt = $_REQUEST['pt'];
$mfname = $_REQUEST['mfname'];
$rlflg = $_REQUEST['rlflg'];
$edt = $_REQUEST['edt'];

//slideshow selected
$iSlideShowId = $_REQUEST['slideshowId'];
if ($iSlideShowId < 0)
    $iSlideShowId = 0;
$iContainSlideshow = $_REQUEST['frmContainSlideshow'];
$iContainVideo = $_REQUEST['frmContainVideo'];
$h = $homedir;

function stri_replace($find, $replace, $string) {
    $parts = explode(strtolower($find), strtolower($string));
    $pos = 0;
    $ctr = 0;
    foreach ($parts as $key => $part) {
        $parts[$key] = substr($string, $pos, strlen($part));
        $pos += strlen($part) + strlen($find);
        $ss = substr($string, $pos, strlen($part));
    }
    return( join($replace, $parts) );
}

if ($aid == "") {
    // if user click new and enter this page or control is not coming back from save..
    // this variable stores the new version of the article after saving..
    $editor_compose = "T";
    mt_srand((double) microtime() * 1000000);
    $file_id = mt_rand();
    setcookie("cookie_file_id", $file_id);
    $file_id = $_COOKIE['cookie_file_id'];
    setcookie("cookie_new_article_id", "");
    $new_article_id = $_COOKIE['cookie_new_article_id'];
    $FYear = date('Y', time());
    $FMonth = date('M', time());
    $FDay = date('d', time());
    if ($edt == "m")
        chmod($h, 0444);
    setcookie("cookie_year", $FYear);
    setcookie("cookie_month", $FMonth);
    setcookie("cookie_day", $FDay);
}
$_COOKIE['cookie_file_id'];
$Page_Context = "Midday Editors WorkBench-Compose Article";
$channels = array();
$channelCounter = 0;
?>
<html>
    <head>
        <title><?= $Page_Context ?></title>

        <SCRIPT LANGUAGE=JavaScript # src="htmledit/date-picker-dateline.js"></SCRIPT>
        <script language="Javascript" type="text/javascript" src="/js/common.js"></script>
        <SCRIPT LANGUAGE="JavaScript">

            function CountLeft(field, count, maxlength)
            {
                var num_Words =  field.value.length;
                count.value = num_Words;

                if (count.value>maxlength) 
                {
                    alert("You cannot enter more than "+maxlength+" chars.");
                    field.value = field.value.substr(0,maxlength);
                    count.value = maxlength;
                } 	
            }


            function maxLength(obj,i) {
                if (obj.value.length >= i) {
                    obj.value = obj.value.slice(0,i);
                }
            }
            function conv(target) {
                var tempStr ;
                var char ;
                var holding = "";
                tempStr =new String(target) ;
                for (var i=0; i<=tempStr.length-1; i++)
                {
                    char = tempStr.charCodeAt(i) ;
                    if(char==8220){
                        tempStrl = tempStr.substring((0-i-1),i);
                        tempStr2 = tempStr.substring(i+1,tempStr.length);
                        tempStr	= tempStrl+"\""+tempStr2 ;
                    }
                    if(char==8221){
                        tempStrl = tempStr.substring((0-i-1),i);
                        tempStr2 = tempStr.substring(i+1,tempStr.length);
                        tempStr	= tempStrl+"\""+tempStr2 ;
                    }
                    if(char==8216){
                        tempStrl = tempStr.substring((0-i-1),i);
                        tempStr2 = tempStr.substring(i+1,tempStr.length);
                        tempStr	= tempStrl+"'"+tempStr2 ;
                    }
                    if(char==8217){
                        tempStrl = tempStr.substring((0-i-1),i);
                        tempStr2 = tempStr.substring(i+1,tempStr.length);
                        tempStr	= tempStrl+"\'"+tempStr2 ;
                    }
                }
                return(tempStr);
            }

            function Closeform()
            {
                document.editform.seldate.value  = document.editform.copydate.value ;
                document.editform.seldate1.value = document.editform.copydate1.value ;
                document.editform.action="rx_close_wizard.php<? if ($flg == "")
    echo("?cancel=T"); ?>";
            document.editform.submit();
        }

        function ViewArticle(mid){
            document.editform.artid.value =mid;
            document.editform.action="rx_pubart.php";
            document.editform.submit();
        }

        function SubmitReturnBack(){
            ae_onSubmit();
            CheckData();
            if (datacomplete == "T") {
                //document.editform.sdateline.value	= document.editform.pubondate.value;
                document.editform.thisContentItem.value=document.editform.ae_tx_content1.value;
                document.editform.mobileh.value=conv(document.editform.mobileh.value);
                document.editform.copyh.value=conv(document.editform.copyh.value);
                //		document.editform.indexh.value=conv(document.editform.indexh.value);
                document.editform.abstract.value=conv(document.editform.abstract.value);
                document.editform.TagName.value=conv(document.editform.TagName.value);
                document.editform.byline.value=conv(document.editform.byline.value);
                document.editform.thisContentItem.value = conv(document.editform.thisContentItem.value);
                document.editform.note.value = conv(document.editform.note.value);
                document.editform.leadImg.value = conv(document.editform.leadImg.value);
                document.editform.leadImgCap.value = conv(document.editform.leadImgCap.value);
                //		document.editform.Interviewee.value = conv(document.editform.Interviewee.value);
                document.editform.datelineplace.value = conv(document.editform.datelineplace.value);
                document.editform.slug.value = conv(document.editform.slug.value);
                
                document.forms[0].elements["rlflg"].value="R";
                document.editform.action="rx_save_article_return.php";
                document.editform.submit();
            }

        }
        function setFocus()
        {
            document.forms[0].elements[0].focus();
        }
        function isDigit(num)
        {
            try 
            {
                if(num !=''){
                    if (num.length>1){return false;}
                    var string="1234567890";
                    if (string.indexOf(num)!=-1){return true;}
                    return false;
                }
	
            }catch(e) {}
        }

        function RelatedLinks() {
            document.forms[0].elements["rlflg"].value="Y";
            SubmitValid();
            return true;
        }
        var datacomplete ;

        function PublishArticle() {
            ae_onSubmit();
            CheckData() ;
            if(datacomplete=="T"){
                document.editform.thisContentItem.value=document.editform.ae_tx_content1.value;
                document.editform.mobileh.value=conv(document.editform.mobileh.value);
                document.editform.copyh.value=conv(document.editform.copyh.value);
                //		document.editform.indexh.value=conv(document.editform.indexh.value);
                document.editform.abstract.value=conv(document.editform.abstract.value);
                document.editform.TagName.value=conv(document.editform.TagName.value);
                document.editform.byline.value=conv(document.editform.byline.value);
                document.editform.thisContentItem.value = conv(document.editform.thisContentItem.value);
                document.editform.note.value = conv(document.editform.note.value);
                document.editform.leadImg.value = conv(document.editform.leadImg.value);
                document.editform.leadImgCap.value = conv(document.editform.leadImgCap.value);
                //		document.editform.Interviewee.value = conv(document.editform.Interviewee.value);
                document.editform.datelineplace.value = conv(document.editform.datelineplace.value);
                document.editform.slug.value = conv(document.editform.slug.value);
                //document.editform.sdateline.value	= document.editform.pubondate.value;
                document.forms[0].elements["rlflg"].value="P";
                document.editform.submit();
            }
        }

        function SubmitValid(){
            ae_onSubmit();
            CheckData();
            if (datacomplete == "T") {
                //document.editform.sdateline.value	= document.editform.pubondate.value;
                document.editform.thisContentItem.value=document.editform.ae_tx_content1.value;
                document.editform.mobileh.value=conv(document.editform.mobileh.value);
                document.editform.copyh.value=conv(document.editform.copyh.value);
                //		document.editform.indexh.value=conv(document.editform.indexh.value);
                document.editform.abstract.value=conv(document.editform.abstract.value);
                document.editform.TagName.value=conv(document.editform.TagName.value);
                document.editform.byline.value=conv(document.editform.byline.value);
                document.editform.thisContentItem.value = conv(document.editform.thisContentItem.value);
                document.editform.note.value = conv(document.editform.note.value);
                document.editform.leadImg.value = conv(document.editform.leadImg.value);
                document.editform.leadImgCap.value = conv(document.editform.leadImgCap.value);
                //		document.editform.Interviewee.value = conv(document.editform.Interviewee.value);
                document.editform.datelineplace.value = conv(document.editform.datelineplace.value);
                document.editform.slug.value = conv(document.editform.slug.value);
                document.forms[0].elements["rlflg"].value="N";
                document.editform.action="rx_save_article_return.php";
                document.editform.submit();
            }
        }

        var apress;
        //body.onfocus	= GiveFocus;

        function GiveFocus(){
            if(apress=="1"){
                aeObjects[1].contentDocument.body.focus();
                //document.editform.DHTMLSafe1.focus();
                //document.editform.DHTMLSafe1.DOM.body.focus();
                apress = "0";
            }
        }

        function fileupload(){
            document.editform.action="leadupload.php";
            document.editform.submit();
        }

        function trim(str) {
            str = str.replace(/^\s+|\s+$/g, ''); 
            return str;
        } // Ends the "trim" function


        function CheckData()
        {
            document.editform.thisContentItem.value=trim(document.editform.ae_tx_content1.value);
            document.editform.thisContentItem.value = (document.editform.thisContentItem.value).replace(/(&nbsp;)+/, '');
            if (trim(document.editform.thisContentItem.value)==""){
                alert("Content can't be blank");
                aeObjects[1].contentDocument.body.focus();
                //aeObjects[1].contentDocument.body.focus();
                return false;
            }

            else if (trim(document.editform.slug.value)=="")
            {
                alert("Article name cannot be empty");
                document.editform.slug.focus();
                return false;
            }

            else if (trim(document.editform.copyh.value)==""){
                alert("Copy Heading cannot be empty");
                document.editform.copyh.focus();
                return false;
            }
            /*	else if (trim(document.editform.indexh.value)==""){
                        alert("Index Headline cannot be empty");
                        document.editform.indexh.focus();
                        return false;
                }*/
            else if (trim(document.editform.mobileh.value)==""){
                alert("Mobile Headline cannot be empty");
                document.editform.mobileh.focus();
                return false;
            }
            else if (trim(document.editform.datelineplace.value)==""){
                alert("Place cannot be empty");
                document.editform.datelineplace.focus();
                return false;
            }
            else if (document.editform.publn.value=="0"){
                alert("Select a publication ");
                document.editform.publn.focus();
                return false;
            }
            /*	else if (document.editform["region[]"].value=="") {
                alert("Please select the region");
                //document.editform.region[].focus();
                return false;
                }
             */
            else if (document.editform.columnist.value=="-"){
                alert("Please select whether this story is to be published as a column");
                document.editform.columnist.focus();
                return false;
            }

            else if (trim(document.editform.abstract.value)==""){
                alert("Abstract cannot be empty");
                document.editform.abstract.focus();
                return false;
            }
            /*else if (trim(document.editform.subheading.value)==""){
                        alert("Subheading cannot be empty");
                        document.editform.subheading.focus();
                        return false;
                }
	
                else if(document.editform.columnist.value=="1"){
                        if(document.editform.byline.value==""){
                                alert("Byline cannot be empty as story is a column");
                                document.editform.byline.focus();
                                return false;
                        }
                }
             */
            else if(document.editform.byline.value==""){
                alert("Byline cannot be empty.");
                document.editform.byline.focus();
                return false;

            }

            /*else if (trim(document.editform.Interviewee.value)==""){
                        alert("Interviewee cannot be empty");
                        document.editform.Interviewee.focus();
                        return false;
                }
                else if (trim(document.editform.note.value)==""){
                        alert("Note cannot be empty");
                        document.editform.note.focus();
                        return false;
                }
                else if {
                var sregion = new Array();
                sregion = document.editform.region.value
                (document.editform.sregion[0].value=="-")
                alert("Please select the region");
                document.editform.region.focus();
                return false;
                }*/
            else if (trim(document.editform.TagName.value)==""){
                alert("Please Enter Tags");
                document.editform.TagName.focus();
                return false;
            }

            datacomplete = "T";
        }


        //Function to open slideshow page in pop up
        function openNewWindowForSlideshow(sUrl, sFlag) 
        {
            if(sFlag=="e") 
            {
                var iSlideshowId = document.getElementById('frmSlideshow').value; 
                sUrl = sUrl+"?slideshowId="+iSlideshowId;
            }

            var win1 = window.open(sUrl);
        }

        </SCRIPT>

    </head>

    <body onfocus="javascript:GiveFocus();">
        <div id="main_body">
            <form action=rx_save_article_return.php method=post name=editform >
                <?
// lock this and all the related records for other users
// save the current status in editor status field in case of recovery
// this happens only first time before the new record is created
// new records created will be locked in the next page while saving...

                if ($cookie_UserID == null) {
                    echo("Invalid session, Please login again...");
                    exit;
                }


//if aricleid is passed then edit mode, retrieve existing values from table and database

                if ($aid <> "") {
                    //If Editor edit page without publish publish, embargo, Dateline date and headline be Null

                    if ($edt == "E") {
                        $insertsql = "UPDATE
					RX_ARTICLES
				 SET
					RXART_PUBLISHED_ON = 'NULL',
					RX_EMBARGODATE = 'NULL', rxart_Story_dateline ='NULL', RX_ISWMLHEADLINE ='NULL'
				WHERE
					RXART_ARTICLE_ID=$artid";

                        if (!mysql_query($insertsql, $conn)) {
                            echo("<a href=\"javascript:ErrorBack()\">Back</a>");
                            exit;
                        }
                    }


                    //get the article related info from the article table --

                    $sql = "Select RXART_COPY_HEADLINE,
		date_format(RXART_PUBLISHED_ON,'%b') FPUBMONTH,
		date_format(RXART_PUBLISHED_ON,'%Y') FPUBYEAR,
		RXART_INDEX_HEADLINE,
		RXART_MOBILE_HEADLINE,
		RXART_PUBLICATION,
		RXART_REGION,
		RXART_INTERVIEWEE,
		RXART_STATUS,
		RXART_CREATED_BY,
		RXART_PARENT_ID,
		RXART_SOURCE,
		date_format(RXART_CREATED_ON,'%Y-%m-%d') CDATE,
		date_format(RXART_CREATED_ON,'%b') FMONTH,
		date_format(RXART_CREATED_ON,'%Y') FYEAR,
		date_format(RXART_CREATED_ON,'%d') FDD,
		RXART_DOCID,
		date_format(RXART_CREATED_ON,'%m') MON1,
		RXART_SLUG,
		RXART_REMARK,
		RXART_BYLINE,
		RXART_DATELINE,
		RXART_DATELINE_PLACE,
		RXART_EVERSION,
		RXART_CREATED_BY,
		RXART_ATTENTION_OF,
		RXART_LOCK_STATUS,
		RXART_LOCKEDBY,
		RXART_KILLED,
		RX_CLOUMNIST,
		RXART_LEADIMG,
		RXART_LEADIMGCAP,
		RXART_GETAHEADCAT,
		RXART_QBOX,
		RXART_HUTCHLIVE,
		date_format(RX_EMBARGODATE,'%Y-%m-%d %H:%i:%s') RX_EMBARGODATE,
		RXART_TITLE, 
		SLIDESHOW_ID,
		CONTAIN_SLIDESHOW,
		CONTAIN_VIDEO
		FROM
		RX_ARTICLES
		WHERE
		RXART_ARTICLE_ID=" . $aid;

                    $stmt = mysql_query($sql, $conn);
                    if (!$stmt) {
                        echo $arr = mysql_error();
                        echo("<br><img onclick=\"Closeform()\" alt=\"Exit without saving\" src=\"images\close.gif\" />");
                        exit;
                    }

                    if ($result = mysql_fetch_array($stmt)) {
                        $mlock = $result['RXART_LOCK_STATUS'];
                        $mlockby = $result['RXART_LOCKEDBY'];
                        $mkilled = $result['RXART_KILLED'];

                        // if this story was locked by someone else meanwhile...

                        if ($mlock == "T" and $mlockby != $cookie_UserID) {

                            $sql = "select RX_DISPLAYNAME from RX_USERS where  RX_USERID =$mlockby";
                            $stmt = mysql_query($sql, $conn);
                            if (!$stmt) {
                                echo $arr = mysql_error();
                                echo("<br><img onclick=\"Closeform()\" alt=\"Exit without saving\" src=\"images\close.gif\" />");
                                exit;
                            }
                            if ($output = mysql_fetch_array($stmt)) {
                                $name = $output['RX_DISPLAYNAME'];
                                echo "<FONT size=\"-1\" face=\"Arial, Helvetica, sans-serif\" COLOR=\"Brown\">This story has been Locked for Editing by <b>$name</b>.. Please try after some time..</font>";
                                echo("<a href=\"javascript:history.back();\" >Back </a>");
                                exit;
                            }
                        }
                        if ($mkilled == 'Y') {
                            echo "<FONT size=\"-1\" face=\"Arial, Helvetica, sans-serif\" COLOR=\"Brown\">Cannot Edit, This story has been marked as killed</font>";
                            echo("<a href=\"javascript:history.back();\" >Back</a>");
                            exit;
                        }

                        $mAttention = $result['RXART_ATTENTION_OF'];
                        $mByline = htmlentities(stripslashes($result['RXART_BYLINE']));
                        $mPlace = htmlentities(stripslashes($result['RXART_DATELINE_PLACE']));
                        $mDateLine = htmlentities(stripslashes($result['RXART_DATELINE']));
                        $mInter = htmlentities(stripslashes($result['RXART_INTERVIEWEE']));

                        $mCopyH = htmlentities(stripslashes($result['RXART_COPY_HEADLINE']));
                        $mIndexH = htmlentities(stripslashes($result['RXART_INDEX_HEADLINE']));
                        $mMobileH = htmlentities(stripslashes($result['RXART_MOBILE_HEADLINE']));

                        $mreg = htmlentities(stripslashes(trim($result['RXART_REGION'])));
                        $mpub = htmlentities(stripslashes($result['RXART_PUBLICATION']));
                        $mCDate = $result['CDATE'];
                        $pubon = $result['RX_EMBARGODATE'];
                        $mFMonth = strtolower($result['FMONTH']);
                        $mFYear = $result['FYEAR'];
                        $mMonth = $result['MON1'];
                        $myy = $result['FPUBYEAR'];
                        $mmm = $result['FPUBMONTH'];
                        $mcol = htmlentities(stripslashes($result['RX_CLOUMNIST']));
                        $mSource = htmlentities(stripslashes($result['RXART_SOURCE']));
                        $mSlug = htmlentities(stripslashes($result['RXART_SLUG']));

                        $mRemark = htmlentities(stripslashes($result['RXART_REMARK']));
                        $file = $result['RXART_DOCID'];
                        $mAuthor = htmlentities(stripslashes($result['RXART_CREATED_BY']));
                        $mleadImg = $result['RXART_LEADIMG'];
                        $mleadImgCap = htmlentities(stripslashes($result['RXART_LEADIMGCAP']));
                        $mgacat = $result['RXART_GETAHEADCAT'];
                        $mqbox = $result['RXART_QBOX'];
                        $mhutchlive = $result['RXART_HUTCHLIVE'];
                        $mFDay = $result['FDD'];
                        //page title
                        $pagetitle = htmlentities(stripslashes($result['RXART_TITLE']));

                        $iSlideShowId = $result['SLIDESHOW_ID'];

                        $iContainSlideshow = $result['CONTAIN_SLIDESHOW'];
                        $iContainVideo = $result['CONTAIN_VIDEO'];



                        if ($result['RXART_EVERSION'] <> "") {
                            $path = $articles;  //editor version ,story will be in 'articles' folder
                        } else {
                            $path = $stories;  //writer version,story will be in 'stories' folder
                            //echo $path ;
                        }
                        $arrArticles = readArticleInfo($path . $mFYear . "/" . $mFMonth . "/" . $file);

                        //read the xml for tag name value
                        $cont = file_get_contents($path . $mFYear . "/" . $mFMonth . "/" . $file);


                        $doc = new DOMDocument();
                        $doc->loadXML($cont);

                        // code start - GET THE VALUE OF THE TAGNAME tags
                        $oContentInstance = $doc->getElementsByTagName('ContentInstance');
                        $sTagNameValue = "";
                        if ($oContentInstance->length > 0) {
                            foreach ($oContentInstance->item(0)->getElementsByTagName('TagName') as $RESULT) {
                                if (strlen($sTagNameValue) == 0) {
                                    $sTagNameValue = htmlentities(stripslashes($RESULT->nodeValue));
                                } else {
                                    $sTagNameValue .="," . htmlentities(stripslashes($RESULT->nodeValue));
                                }
                            }

                            //get the content of the article 
                            $oContent = $doc->getElementsByTagName('Content')->item(0);
                            $sContentValue = $oContent->nodeValue;
                        }
                        // code end - GET THE VALUE OF THE TAGNAME tags
                    }
                    setcookie("cookie_year", $mFYear);
                    $year = $_COOKIE['cookie_year'];
                    setcookie("cookie_month", $mFMonth);
                    $month = $_COOKIE['cookie_month'];
                    setcookie("cookie_parent_id", $result['RXART_PARENT_ID']);
                    setcookie("cookie_day", $mFDay);
                    $day = $_COOKIE['cookie_day'];
                    $parent_id = $_COOKIE['cookie_parent_id'];

                    if ($new_article_id == "") {
                        $sql = "UPDATE
				RX_ARTICLES
				SET
				RXART_EDITOR_STATUS=RXART_STATUS,
				RXART_STATUS='Locked by $cookie_username',
				RXART_LOCKEDBY='$cookie_UserID',
				RXART_LOCK_STATUS='T'
				where
				RXART_STATUS<>'Locked by $cookie_username' and
				RXART_PARENT_ID='$parent_id'";

                        // dont update the record if it was already locked and the user is getting in to release lock

                        if (!mysql_query($sql, $conn)) {
                            echo $arr = mysql_error();
                            exit;
                        }
                    }

                    //get the article channel info from the article table --
                    $sql = "select * from RX_ARTICLE_CHANNEL WHERE RXARTCH_ARTICLE_ID=" . $aid;
                    $stmt = mysql_query($sql, $conn);

                    if (!$stmt) {
                        echo $arr = mysql_error();
                        echo("<br><img onclick=\"Closeform()\" alt=\"Exit without saving\" src=\"images\close2.gif\" />");
                        exit;
                    }
                    while ($output = mysql_fetch_row($stmt)) {
                        $channels[] = $output[2];
                        $channelCounter++;
                    }
                }
                ?>
                <br>
                <TABLE width="100%" cellpadding="0" cellspacing="0" border="0">
                    <TR>
                    <div id="page_title">
                        <TD WIDTH=85% align="center">
                            <?= $cookie_UserDName ?> at work<BR>
                            <img src="image/dot.gif" width="30%" height="1" border=0 alt="dot"><BR>
                        </TD>
                    </div>
                    <TD WIDTH=15% VALIGN=bottom><a href="rx_close_wizard.php?out=T<? if ($flg == "")
                                echo("&cancel=T"); ?>" target="_parent"  class="section_links">Logout</a></TD>
                    </TR>
                </TABLE>
                <BR>
                <TABLE width="100%" cellpadding="0" cellspacing="0" border="0">
                    <TR>
                        <?
                        if ($editor_compose == "T") {
                            echo("<TD WIDTH=100% VALIGN=top>");
                            $roll = "Compose Article";
                        } else {
                            echo("<TD align=left WIDTH=30% VALIGN=top>");
                            $roll = "Edit Article";
                            echo("<TD WIDTH=70% VALIGN=top>");
                            echo("<FONT size=\"+1\" face=\"VERDANA\"><b>$mSlug</b></FONT><BR></TD>");
                        }
                        ?>


                        </TD>
                        <TD WIDTH=70% VALIGN=top>
                        </TD>
                    </TR>
                </TABLE>
                <div id="div_stories" class="section">
                    <fieldset>
                        <legend class="section_title">
                            <? echo $roll; ?>
                        </legend>
                        <?
                        $xContent = $sContentValue;
                        $find = $upload;
                        $replace = $homeurl . $upload;
//$xContent =stri_replace($find, $replace, $xContent );
                        ?>
                        <div class="section_body">
                            <!-- Start -  Table Header-->
                            <table width="100%" border="0">
                                <tr class="table_header">
                                    <td align="center" class="table_align_left">
                                        <B>Content</B>
                                    </TD>
                                </TR>


                                <TR>
                                    <TD bgcolor=#FFFFFF>
                                        <TABLE width=90% border=0 cellpadding=0 cellspacing=0 ALIGN=center>
                                            <TR>
                                                <TD>
                                                    <? require ("rx_htmleditnew.php"); ?>
                                                </TD>
                                            </TR>

                                            <TR>
                                                <TD>
                                                    <a href="Javascript:openNewWindowForSlideshow('rx_create_slideshow.php','c');">Create Slideshow</a> |   
                                                    <?
                                                    if ($iSlideShowId == 0) {
                                                        $sStyle = "visibility:hidden;";
                                                    } else {
                                                        $sStyle = "";
                                                    }
                                                    ?>
                                                    <a style="<?= $sStyle ?>" id="editSlideshowLink"  href="Javascript:openNewWindowForSlideshow('rx_create_slideshow.php', 'e');">Edit Created Slideshow </a> | 

                                                    <a href="Javascript:openNewWindowForSlideshow('rx_list_slideshow.php', 'p');">Add Previous Slideshow</a>
                                                </TD>
                                            </TR>

                                            <TR><TD>To include 'Created Slideshow', please copy paste this tag <#slideshow_code> , where you would like to display slideshow.
                                                </TD></TR>


                                        </TABLE>
                            </table>
                        </div></fieldset>
                </div>
                <BR>

                <!-- for date range variables introduced -->
                <input type="hidden" name="stateof" value="<?= $stateof ?>">
                <input type="hidden" name="copydate" value="<?= $copydate ?>">
                <input type="hidden" name="copydate1" value="<?= $copydate1 ?>">
                <input type="hidden" name="seldate" value='<?= $seldate ?>'>
                <input type="hidden" name="DateDiff" value="<?= $DateDiff ?>">
                <input type="hidden" name="seldate1" value='<?= $seldate1 ?>'>
                <input type="hidden" name="rdpst" value="<?= $rdpst ?>" >
                <input type="hidden" name="rdpub" value="<?= $rdpub ?>" >
                <input type="hidden" name="rdver" value="<?= $rdver ?>" >
                <input type="hidden" name="artid" value="<?= $artid ?>">

                <?
                $sql = "SELECT
			RX_DISPLAYNAME,
			RX_USERID,
			date_format(NOW(),'%Y-%m-%d %H:%i:%s') AS RX_CDATE
	   FROM
			RX_USERS
	   WHERE
			RX_STATUS='e'";

//error handling added on november 18,2002

                $stmt = mysql_query($sql, $conn);
                if (!$stmt) {
                    echo $arr = mysql_error();
                    echo("</TD></TR><TR><TD colspan=4 align=center> <br><img onclick=\"Closeform()\" alt=\"Exit without saving\" src=\"images\close3.gif\" /></TD></TR>");
                    exit;
                }
                while ($output = mysql_fetch_array($stmt)) {

                    $id = $output['RX_USERID'];
                    $name = $output['RX_DISPLAYNAME'];
                    $cdate = $output['RX_CDATE'];
                }
                ?>
                <TABLE width="100%" cellpadding="0" cellspacing="0" border="0">
                    <TR>
                        <TD WIDTH=54% VALIGN=top>

                            <TABLE width="100%" cellpadding="1" cellspacing="0" border="0">
                                <TR>
                                    <TD VALIGN=TOP WIDTH=50%>
                                        <FONT size="-1" face="VERDANA"><B>Article Name: </B></FONT>
                                        <FONT color=red pointsize="10"><B>*</B></font></TD>

                                    <TD WIDTH=55%>

                                        <input name=slug size=27 maxlength=150 onKeyUp="CountLeft(this.form.slug,this.form.left,75);" value="<? echo(str_replace("\"", "&quot;", $mSlug)); ?>">
                                    </TD>

                                    <td valign=top width=10%>
                                        <input readonly type="text" name="left" size=1 maxlength=2 value="0">
                                    </td>
                                </TR>
                                <TR>
                                    <TD WIDTH=40%>
                                        <FONT size="-1" face="VERDANA"><B>Page Title</B></FONT> <FONT color=red pointsize="10"><B>*</B></font></TD>
                                    <TD WIDTH=55%>
                                        <INPUT name="pagetitle" size=27 maxlength=100 value="<?php echo $pagetitle; ?>" onKeyUp="CountLeft(this.form.pagetitle,this.form.ptitle,100);"></FONT>
                                    </TD>
                                    <td valign=top width=10%>
                                        <input readonly type="text" name="ptitle" size=1 maxlength=2 value="0">
                                    </td>

                                </TR>

                                <TR>
                                    <TD VALIGN=TOP WIDTH=40%>
                                            <!--<INPUT NAME=copyh size=30 maxlength=100 value="<? echo(str_replace("\"", "&quot;", $mCopyH)); ?>"></FONT>-->
                                        <FONT size="-1" face="VERDANA"><B>Headline</B></FONT>
                                        <FONT color=red pointsize="10"><B>*</B></font></TD>
                                    <TD WIDTH=55%>
                                                    <!--	<INPUT NAME=copyh size=30 maxlength=98 value="<? echo(str_replace("\"", "&quot;", $mCopyH)); ?>"></FONT> -->
                                        <TEXTAREA id=copyh name=copyh onKeyUp="CountLeft(this.form.copyh,this.form.headline,100);" rows="2"cols="22"  ><? echo(str_replace("\"", "&quot;", $mCopyH)); ?></TEXTAREA>
                                    </TD>
                                    <td valign=top width=10%>
                                        <input readonly type="text" name="headline" size=1 maxlength=2 value="0">
                                    </td>

                                </TR>
                                <!--TR>
                                <TD WIDTH=40%>
                                <FONT size="-1" face="VERDANA"><B>Index Headline</B></FONT> <FONT color=red pointsize="10"><B>*</B></font></TD>
                                <TD WIDTH=60%>
                                <INPUT NAME=indexh size=30 maxlength=50 value="<? echo(str_replace("\"", "&quot;", $mIndexH)); ?>"></FONT>
                                </TD>
                                </TR-->

                                <TR>
                                    <TD WIDTH=40%>
                                        &nbsp;<FONT size="-1" face="VERDANA"><B>Byline</B></FONT> <FONT color=red pointsize="10"><B>*</B></font></TD>
                                    <TD WIDTH=55%>
                                        <INPUT NAME=byline size=27 maxlength=100 value="<? echo(str_replace("\"", "&quot;", $mByline)); ?>">
                                    </TD>
                                </TR>

                                <TR>
                                    <TD WIDTH=40%>
                                        <FONT size="-1" face="VERDANA"><B>Mobile Headline</B></FONT> <FONT color=red pointsize="10"><B>*</B></font></TD>
                                    <TD WIDTH=55%>
                                        <INPUT NAME=mobileh size=27 maxlength=40 onKeyUp="CountLeft(this.form.mobileh,this.form.mheadline,50);" value="<? echo(str_replace("\"", "&quot;", $mMobileH)); ?>"></FONT>
                                    </TD>
                                    <td valign=top width=10%>
                                        <input readonly type="text" name="mheadline" size=1 maxlength=2 value="0">
                                    </td>

                                </TR>
                                <TR>
                                    <TD WIDTH=40%>
                                        <FONT size="-1" face="VERDANA"><B>Place:</B></FONT><FONT color=red pointsize="10"><B>*</B></font><BR></TD>
                                    <TD WIDTH=55%>
                                        <input maxlength=50 size=27 type="text" name=datelineplace value="<? echo(str_replace("\"", "&quot;", $mPlace)); ?>">
                                    </TD>
                                </TR>

                                <TR>
                                    <TD WIDTH=40%>
                                        <FONT size="-1" face="VERDANA"><B>Publication:</B></FONT>  <FONT color=red pointsize="10"><B>*</B></font><BR></TD>
                                    <TD WIDTH=55%>
                                        <select name=publn>
                                            <option value="Midday" selected>Midday</option>
                                        </select>
                                        <!--input type="text" name="publn" value="Midday"-->
                                    </TD>
                                </TR>
                                <TR>
                                    <TD WIDTH=40%>
                                        <FONT size="-1" face="VERDANA"><B>Region:</B></FONT><FONT color=red pointsize="10"><B>*</B></font><BR></TD>
                                    <TD WIDTH=55%>

                                        <select name="region[]" size="3" MULTIPLE>
                                            <? if ($mreg == "") { ?>
                                                <option value="None" selected>None</option>
                                            <? } else { ?>
                                                <?
                                                $mmreg = explode("*", $mreg);
                                                for ($i = 0; $i < count($mmreg) - 1; $i++)
                                                    print("<option selected>" . $mmreg[$i] . "</option>");
                                                ?>


                                            <? } ?>

                                            <option value="Andaman and Nicobar">Andaman and Nicobar</option>
                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                            <option value="Assam">Assam</option>
                                            <option value="Bihar">Bihar</option>
                                            <option value="Chandigarh">Chandigarh</option>
                                            <option value="Chhattisgarh">Chhattisgarh </option>
                                            <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                            <option value="Daman and Diu">Daman and Diu</option>
                                            <option value="Delhi">Delhi</option>
                                            <option value="Goa">Goa</option>
                                            <option value="Gujarat">Gujarat</option>
                                            <option value="Haryana">Haryana </option>
                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                            <option value="Jharkhand">Jharkhand</option>
                                            <option value="Karnataka">Karnataka</option>
                                            <option value="Kerala">Kerala </option>
                                            <option value="Lakshadweep">Lakshadweep</option>
                                            <option value="Madhya Pradesh">Madhya Pradesh </option>
                                            <option value="Maharashtra">Maharashtra </option>
                                            <option value="Manipur">Manipur</option>
                                            <option value="Meghalaya">Meghalaya</option>
                                            <option value="Mizoram">Mizoram</option>
                                            <option value="Nagaland">Nagaland</option>
                                            <option value="Orissa">Orissa</option>
                                            <option value="Pondicherry">Pondicherry</option>
                                            <option value="Punjab">Punjab</option>
                                            <option value="Rajasthan">Rajasthan</option>
                                            <option value="Sikkim">Sikkim</option>
                                            <option value="Tamil Nadu">Tamil Nadu</option>
                                            <option value="Tripura">Tripura</option>
                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                            <option value="Uttaranchal">Uttaranchal </option>
                                            <option value="West Bengal">West Bengal</option>
                                            <option value="Afghanistan" >Afghanistan</option>
                                            <option value="Aland Islands" >Aland Islands</option>
                                            <option value="Albania" >Albania</option>
                                            <option value="Algeria" >Algeria</option>
                                            <option value="American Samoa" >American Samoa</option>
                                            <option value="Andorra" >Andorra</option>
                                            <option value="Angola" >Angola</option>
                                            <option value="Anguilla" >Anguilla</option>
                                            <option value="Antarctica" >Antarctica</option>
                                            <option value="Antigua and Barbuda" >Antigua and Barbuda</option>
                                            <option value="Argentina" >Argentina</option>
                                            <option value="Armenia" >Armenia</option>
                                            <option value="Aruba" >Aruba</option>
                                            <option value="Australia" >Australia</option>
                                            <option value="Austria" >Austria</option>
                                            <option value="Azerbaijan" >Azerbaijan</option>
                                            <option value="Bahamas" >Bahamas</option>
                                            <option value="Bahrain" >Bahrain</option>
                                            <option value="Bangladesh" >Bangladesh</option>
                                            <option value="Barbados" >Barbados</option>
                                            <option value="Belarus" >Belarus</option>
                                            <option value="Belgium" >Belgium</option>
                                            <option value="Belize" >Belize</option>
                                            <option value="Benin" >Benin</option>
                                            <option value="Bermuda" >Bermuda</option>
                                            <option value="Bhutan" >Bhutan</option>
                                            <option value="Bolivia" >Bolivia</option>
                                            <option value="Bosnia and Herzegovina" >Bosnia and Herzegovina</option>
                                            <option value="Botswana" >Botswana</option>
                                            <option value="Bouvet Island" >Bouvet Island</option>
                                            <option value="Brazil" >Brazil</option>
                                            <option value="British Indian Ocean Territory" >British Indian Ocean Territory</option>
                                            <option value="British Virgin Islands" >British Virgin Islands</option>
                                            <option value="Brunei" >Brunei</option>
                                            <option value="Bulgaria" >Bulgaria</option>
                                            <option value="Burkina Faso" >Burkina Faso</option>
                                            <option value="Burundi" >Burundi</option>
                                            <option value="Cambodia" >Cambodia</option>
                                            <option value="Cameroon" >Cameroon</option>
                                            <option value="Canada" >Canada</option>
                                            <option value="Cape Verde" >Cape Verde</option>
                                            <option value="Cayman Islands" >Cayman Islands</option>
                                            <option value="Central African Republic" >Central African Republic</option>
                                            <option value="Chad" >Chad</option>
                                            <option value="Chile" >Chile</option>
                                            <option value="China" >China</option>
                                            <option value="Christmas Island" >Christmas Island</option>
                                            <option value="Cocos (Keeling) Islands" >Cocos (Keeling) Islands</option>
                                            <option value="Colombia" >Colombia</option>
                                            <option value="Comoros" >Comoros</option>
                                            <option value="Congo" >Congo</option>
                                            <option value="Cook Islands" >Cook Islands</option>
                                            <option value="Costa Rica" >Costa Rica</option>
                                            <option value="Croatia" >Croatia</option>
                                            <option value="Cyprus" >Cyprus</option>
                                            <option value="Czech Republic" >Czech Republic</option>
                                            <option value="Democratic Republic of Congo" >Democratic Republic of Congo</option>
                                            <option value="Denmark" >Denmark</option>
                                            <option value="Disputed Territory" >Disputed Territory</option>
                                            <option value="Djibouti" >Djibouti</option>
                                            <option value="Dominica" >Dominica</option>
                                            <option value="Dominican Republic" >Dominican Republic</option>
                                            <option value="East Timor" >East Timor</option>
                                            <option value="Ecuador" >Ecuador</option>
                                            <option value="Egypt" >Egypt</option>
                                            <option value="El Salvador" >El Salvador</option>
                                            <option value="Equatorial Guinea" >Equatorial Guinea</option>
                                            <option value="Eritrea" >Eritrea</option>
                                            <option value="Estonia" >Estonia</option>
                                            <option value="Ethiopia" >Ethiopia</option>
                                            <option value="Falkland Islands" >Falkland Islands</option>
                                            <option value="Faroe Islands" >Faroe Islands</option>
                                            <option value="Federated States of Micronesia" >Federated States of Micronesia</option>
                                            <option value="Fiji" >Fiji</option>
                                            <option value="Finland" >Finland</option>
                                            <option value="France" >France</option>
                                            <option value="French Guyana" >French Guyana</option>
                                            <option value="French Polynesia" >French Polynesia</option>
                                            <option value="French Southern Territories" >French Southern Territories</option>
                                            <option value="Gabon" >Gabon</option>
                                            <option value="Gambia" >Gambia</option>
                                            <option value="Georgia" >Georgia</option>
                                            <option value="Germany" >Germany</option>
                                            <option value="Ghana" >Ghana</option>
                                            <option value="Gibraltar" >Gibraltar</option>
                                            <option value="Greece" >Greece</option>
                                            <option value="Greenland" >Greenland</option>
                                            <option value="Grenada" >Grenada</option>
                                            <option value="Guadeloupe" >Guadeloupe</option>
                                            <option value="Guam" >Guam</option>
                                            <option value="Guatemala" >Guatemala</option>
                                            <option value="Guinea" >Guinea</option>
                                            <option value="Guinea-Bissau" >Guinea-Bissau</option>
                                            <option value="Guyana" >Guyana</option>
                                            <option value="Haiti" >Haiti</option>
                                            <option value="Heard Island and Mcdonald Islands" >Heard Island and Mcdonald Islands</option>
                                            <option value="Honduras" >Honduras</option>
                                            <option value="Hong Kong" >Hong Kong</option>
                                            <option value="Hungary" >Hungary</option>
                                            <option value="Iceland" >Iceland</option>
                                            <option value="India" selected >India</option>
                                            <option value="Indonesia" >Indonesia</option>
                                            <option value="Iraq" >Iraq</option>
                                            <option value="Iraq-Saudi Arabia Neutral Zone" >Iraq-Saudi Arabia Neutral Zone</option>
                                            <option value="Ireland" >Ireland</option>
                                            <option value="Israel" >Israel</option>
                                            <option value="Italy" >Italy</option>
                                            <option value="Ivory Coast" >Ivory Coast</option>
                                            <option value="Jamaica" >Jamaica</option>
                                            <option value="Japan" >Japan</option>
                                            <option value="Jordan" >Jordan</option>
                                            <option value="Kazakhstan" >Kazakhstan</option>
                                            <option value="Kenya" >Kenya</option>
                                            <option value="Kiribati" >Kiribati</option>
                                            <option value="Kuwait" >Kuwait</option>
                                            <option value="Kyrgyzstan" >Kyrgyzstan</option>
                                            <option value="Laos" >Laos</option>
                                            <option value="Latvia" >Latvia</option>
                                            <option value="Lebanon" >Lebanon</option>
                                            <option value="Lesotho" >Lesotho</option>
                                            <option value="Liberia" >Liberia</option>
                                            <option value="Libya" >Libya</option>
                                            <option value="Liechtenstein" >Liechtenstein</option>
                                            <option value="Lithuania" >Lithuania</option>
                                            <option value="Luxembourg" >Luxembourg</option>
                                            <option value="Macau" >Macau</option>
                                            <option value="Macedonia" >Macedonia</option>
                                            <option value="Madagascar" >Madagascar</option>
                                            <option value="Malawi" >Malawi</option>
                                            <option value="Malaysia" >Malaysia</option>
                                            <option value="Maldives" >Maldives</option>
                                            <option value="Mali" >Mali</option>
                                            <option value="Malta" >Malta</option>
                                            <option value="Marshall Islands" >Marshall Islands</option>
                                            <option value="Martinique" >Martinique</option>
                                            <option value="Mauritania" >Mauritania</option>
                                            <option value="Mauritius" >Mauritius</option>
                                            <option value="Mayotte" >Mayotte</option>
                                            <option value="Mexico" >Mexico</option>
                                            <option value="Moldova" >Moldova</option>
                                            <option value="Monaco" >Monaco</option>
                                            <option value="Mongolia" >Mongolia</option>
                                            <option value="Montserrat" >Montserrat</option>
                                            <option value="Morocco" >Morocco</option>
                                            <option value="Mozambique" >Mozambique</option>
                                            <option value="Myanmar" >Myanmar</option>
                                            <option value="Namibia" >Namibia</option>
                                            <option value="Nauru" >Nauru</option>
                                            <option value="Nepal" >Nepal</option>
                                            <option value="Netherlands" >Netherlands</option>
                                            <option value="Netherlands Antilles" >Netherlands Antilles</option>
                                            <option value="New Caledonia" >New Caledonia</option>
                                            <option value="New Zealand" >New Zealand</option>
                                            <option value="Nicaragua" >Nicaragua</option>
                                            <option value="Niger" >Niger</option>
                                            <option value="Nigeria" >Nigeria</option>
                                            <option value="Niue" >Niue</option>
                                            <option value="Norfolk Island" >Norfolk Island</option>
                                            <option value="North Korea" >North Korea</option>
                                            <option value="Northern Mariana Islands" >Northern Mariana Islands</option>
                                            <option value="Norway" >Norway</option>
                                            <option value="Oman" >Oman</option>
                                            <option value="Pakistan" >Pakistan</option>
                                            <option value="Palau" >Palau</option>
                                            <option value="Palestinian Occupied Territories" >Palestinian Occupied Territories</option>
                                            <option value="Panama" >Panama</option>
                                            <option value="Papua New Guinea" >Papua New Guinea</option>
                                            <option value="Paraguay" >Paraguay</option>
                                            <option value="Peru" >Peru</option>
                                            <option value="Philippines" >Philippines</option>
                                            <option value="Pitcairn Islands" >Pitcairn Islands</option>
                                            <option value="Poland" >Poland</option>
                                            <option value="Portugal" >Portugal</option>
                                            <option value="Puerto Rico" >Puerto Rico</option>
                                            <option value="Qatar" >Qatar</option>
                                            <option value="Reunion" >Reunion</option>
                                            <option value="Romania" >Romania</option>
                                            <option value="Russia" >Russia</option>
                                            <option value="Rwanda" >Rwanda</option>
                                            <option value="Saint Helena and Dependencies" >Saint Helena and Dependencies</option>
                                            <option value="Saint Kitts and Nevis" >Saint Kitts and Nevis</option>
                                            <option value="Saint Lucia" >Saint Lucia</option>
                                            <option value="Saint Pierre and Miquelon" >Saint Pierre and Miquelon</option>
                                            <option value="Saint Vincent and the Grenadines" >Saint Vincent and the Grenadines</option>
                                            <option value="Samoa" >Samoa</option>
                                            <option value="San Marino" >San Marino</option>
                                            <option value="Sao Tome and Principe" >Sao Tome and Principe</option>
                                            <option value="Saudi Arabia" >Saudi Arabia</option>
                                            <option value="Senegal" >Senegal</option>
                                            <option value="Serbia and Montenegro" >Serbia and Montenegro</option>
                                            <option value="Seychelles" >Seychelles</option>
                                            <option value="Sierra Leone" >Sierra Leone</option>
                                            <option value="Singapore" >Singapore</option>
                                            <option value="Slovakia" >Slovakia</option>
                                            <option value="Slovenia" >Slovenia</option>
                                            <option value="Solomon Islands" >Solomon Islands</option>
                                            <option value="Somalia" >Somalia</option>
                                            <option value="South Africa" >South Africa</option>
                                            <option value="South Georgia and South Sandwich Islands" >South Georgia and South Sandwich Islands</option>
                                            <option value="South Korea" >South Korea</option>
                                            <option value="Spain" >Spain</option>
                                            <option value="Spratly Islands" >Spratly Islands</option>
                                            <option value="Sri Lanka" >Sri Lanka</option>
                                            <option value="Suriname" >Suriname</option>
                                            <option value="Svalbard and Jan Mayen" >Svalbard and Jan Mayen</option>
                                            <option value="Swaziland" >Swaziland</option>
                                            <option value="Sweden" >Sweden</option>
                                            <option value="Switzerland" >Switzerland</option>
                                            <option value="Syria" >Syria</option>
                                            <option value="Taiwan" >Taiwan</option>
                                            <option value="Tajikistan" >Tajikistan</option>
                                            <option value="Tanzania" >Tanzania</option>
                                            <option value="Thailand" >Thailand</option>
                                            <option value="Togo" >Togo</option>
                                            <option value="Tokelau" >Tokelau</option>
                                            <option value="Tonga" >Tonga</option>
                                            <option value="Trinidad and Tobago" >Trinidad and Tobago</option>
                                            <option value="Tunisia" >Tunisia</option>
                                            <option value="Turkey" >Turkey</option>
                                            <option value="Turkmenistan" >Turkmenistan</option>
                                            <option value="Turks And Caicos Islands" >Turks And Caicos Islands</option>
                                            <option value="Tuvalu" >Tuvalu</option>
                                            <option value="Uganda" >Uganda</option>
                                            <option value="Ukraine" >Ukraine</option>
                                            <option value="United Arab Emirates" >United Arab Emirates</option>
                                            <option value="United Kingdom" >United Kingdom</option>
                                            <option value="United Nations Neutral Zone" >United Nations Neutral Zone</option>
                                            <option value="United States" >United States</option>
                                            <option value="United States Minor Outlying Islands" >United States Minor Outlying Islands</option>
                                            <option value="Uruguay" >Uruguay</option>
                                            <option value="US Virgin Islands" >US Virgin Islands</option>
                                            <option value="Uzbekistan" >Uzbekistan</option>
                                            <option value="Vanuatu" >Vanuatu</option>
                                            <option value="Vatican City" >Vatican City</option>
                                            <option value="Venezuela" >Venezuela</option>
                                            <option value="Vietnam" >Vietnam</option>
                                            <option value="Wallis and Futuna" >Wallis and Futuna</option>
                                            <option value="Western Sahara" >Western Sahara</option>
                                            <option value="Yemen" >Yemen</option>
                                            <option value="Zambia" >Zambia</option>
                                            <option value="Zimbabwe" >Zimbabwe</option>
                                            <!--option value="Pakistan">Pakistan</option>
                                            <option value="Sri Lanka">Sri Lanka</option>
                                            <option value="Bangladesh">Bangladesh</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Afghanistan">Afghanistan</option>
                                            <option value="China">China</option>
                                            <option value="Iraq">Iraq</option>
                                            <option value="Iran">Iran</option>
                                            <option value="UK">UK</option>
                                        <option value="USA">USA</option>
                                        <option value="Israel">Israel</option -->
                                        </select>
                                    </TD>
                                </tr>

                                <TR>
                                    <TD WIDTH="40%">
                                        <FONT size="-1" face="VERDANA"><B>Is this a column ?:</B></FONT> <FONT color=red pointsize="10"><B>*</B></font>
                                    </TD>
                                    <TD WIDTH="55%">
                                        <select name=columnist>

                                            <?
                                            if ($mcol == "1") {
                                                ?>
                                                <option selected value="1">Yes</option>
                                                <option value="0" >No</option>
                                                <?
                                            } else if ($mcol == "0") {
                                                ?>
                                                <option selected value="0">No</option>
                                                <option value="1" >Yes</option>
                                            <? } else { ?>

                                                <option value="-" >Set Column Status</option>
                                                <option value="1">Yes</option>
                                                <option value="0" selected>No</option>
                                            <? } ?>
                                        </select>
                                    </TD>
                                </TR>

                                <TR>
                                    <TD WIDTH=40%>
                                        <FONT size="-1" face="VERDANA"><B>Abstract<FONT color=red pointsize="10">*</FONT></B></TD>
                                    <TD WIDTH=55%><TEXTAREA id=abstract name=abstract maxlength=480 ONKEYUP="maxLength(this,480)" rows=5 cols=22 ><? echo(trim($arrArticles[0]["abstract"])); ?></TEXTAREA></TD>
                                </TR>

                            </TABLE>
                        </TD>
                        <TD WIDTH=3% ALIGN=center VALIGN=top>
                            <img src="image/darkdot.jpg" width="1" height="400" border=0 alt="line" VSPACE=3 HSPACE=0><BR>
                        </TD>
                        <TD WIDTH=43% VALIGN=top>

                            <TABLE width="100%" cellpadding="3" cellspacing="0" border="0">
                                <TR>
                                    <TD WIDTH=40%>

                                        &nbsp;<FONT size="-1" face="VERDANA"><B>Sub Headline</B></FONT>
                                        <FONT color=red pointsize="10"></FONT></FONT></TD>
                                    <TD WIDTH=60%>
                                        <INPUT NAME=subheading size=27 maxlength=98 value="<? echo(str_replace("\"", "&quot;", $arrArticles[0]["subheading"])); ?>"></FONT>
                                    </TD>
                                </TR>
                                <!--TR>
                                <TD WIDTH=40%>
                                &nbsp;<FONT size="-1" face="VERDANA"><B>Byline</B></FONT></TD>
                                <TD WIDTH=60%>
                                <INPUT NAME=byline size=30 maxlength=100 value="<? echo(str_replace("\"", "&quot;", $mByline)); ?>">
                                </TD>
                        </TR-->

                                <TR>
                                    <TD WIDTH=40%>
                                        &nbsp;<FONT size="-1" face="VERDANA"><B>Reporters Dateline:</B></FONT></TD>
                                    <TD WIDTH=60%>
                                        <?
                                        if ($mDateLine == "") {
                                            $mDateLine = date('Y-m-d H:i:s', time());
                                            ?>
                                            <FONT size="-1" face="VERDANA"><? echo $mDateLine; ?></FONT>
                                        <? } else { ?>
                                            <FONT size="-1" face="VERDANA"><? echo($mDateLine); ?></FONT>
                                        <? } ?>
                                        <INPUT type=hidden name=dateline value="<? echo($mDateLine); ?>">
                                        <INPUT type=hidden name=moddate  value="<? echo($mCDate); ?>">
                                        <INPUT type=hidden name=xfile value="<? echo($file); ?>">
                                        <INPUT type=hidden name=xyear value="<? echo($mFYear); ?>">
                                        <INPUT type=hidden name=xmonth value="<? echo($mFMonth); ?>">


                                        <INPUT type=hidden name=dateline value="<? echo($mDateLine); ?>">
                                        <INPUT type=hidden name=pubdate value="<? echo($cdate); ?>">
                                        <input type=hidden name=pd value=<?= urlencode($pd) ?>>
                                        <input type=hidden name=pt value=<?= urlencode($pt) ?>>
                                    </TD>
                                </TR>
                                <TR>
                                    <!--TR>
                                    <TD WIDTH=40%>
                                    &nbsp;<FONT size="-1" face="VERDANA"><B>Source:</B></FONT><BR></TD>
                                    <TD WIDTH=60%>
                                            <select name=source>
                                                          <option value="None" >None</option>
                                                              <option value="PTI" <? if ($mSource == "PTI")
                                            echo (" Selected " ); ?>>PTI</option>
                                                              <option value="UNI" <? if ($mSource == "UNI")
                                        echo (" Selected " ); ?>>UNI</option>
                                                              <option value="REUTERS" <? if ($mSource == "REUTERS")
                                        echo (" Selected " ); ?>>REUTERS</option>
                                                              <option value="BS" <? if ($mSource == "BS")
                                        echo (" Selected " ); ?>>BS</option>
                                                              <option value="MC" <? if ($mSource == "MC")
                                        echo (" Selected " ); ?>>MC</option>
                                                              <option value="ANI" <? if ($mSource == "ANI")
                                        echo (" Selected " ); ?>>ANI</option>
                                                              <option value="AP" <? if ($mSource == "AP")
                                        echo (" Selected " ); ?>>AP</option>
                                                              <option value="BB" <? if ($mSource == "BB")
                                        echo (" Selected " ); ?>>Bombay Bitch</option>
                                                              <option value="AGENCIES" <? if ($mSource == "AGENCIES")
                                        echo (" Selected " ); ?>>AGENCIES</option>
                                                    </select>
                                    </TD>
                                    </TR-->
                                <TR><TD width="40%" VALIGN=top >
                                        &nbsp;<FONT face="VERDANA" size="-1"><B>Mark for the attention of</B></FONT><BR>
                                    </TD>

                                    <TD WIDTH=60%>
                                        <?
                                        $sql = "SELECT
							   				RX_DISPLAYNAME,
							   				RX_USERID,
							   				date_format(NOW(),'%Y-%m-%d %H:%i:%s') AS RX_CDATE
							   		   FROM
							   		   		RX_USERS
							   		   WHERE
							   		   		RX_STATUS='e'
											ORDER BY
											RX_DISPLAYNAME";

                                        $stmt = mysql_query($sql, $conn);
                                        if (!$stmt) {
                                            echo $arr = mysql_error();
                                            echo("</TD></TR><TR><TD colspan=4 align=center> <br><img onclick=\"Closeform()\" alt=\"Exit without saving\" src=\"images\close4.gif\" /></TD></TR>");
                                            exit;
                                        }

                                        echo("<select name=attention><option >None</option>");

                                        while ($output = mysql_fetch_array($stmt)) {

                                            $id = $output['RX_USERID'];
                                            $name = $output['RX_DISPLAYNAME'];
                                            $cdate = $output['RX_CDATE'];

                                            echo("<option ");
                                            if ($mAttention == $name)
                                                echo(" Selected");
                                            echo(">$name</option>");
                                        }
                                        echo("</select>");
                                        ?>
                                    </TD></TR>

                                <!--TR>
                                        <TD WIDTH=40%>
                                        &nbsp;<FONT size="-1" face="VERDANA"><B>Interviewee:</B></FONT><BR></TD>
                                        <TD WIDTH=60%>
                        
                                        <input maxlength=50 type="text" name=Interviewee value="<? echo(str_replace("\"", "&quot;", $mInter)); ?>">
                                </TD>
                            </TR-->
                                <TR>
                                    <TD WIDTH=40%>
                                        &nbsp;<FONT size="-1" face="VERDANA"><B>Note : </B></FONT><BR></TD>
                                    <TD WIDTH=60%>
                                        <TEXTAREA id=note name=note ONKEYUP="maxLength(this,490)" maxlength=490 rows=3 cols=22 ><? echo(str_replace("\"", "&quot;", $mRemark)) ?></TEXTAREA>
                                    </TD>
                                </TR>

                                <TR>
                                    <TD WIDTH=40%>
                                        &nbsp;<FONT size="-1" face="VERDANA"><B>Lead Image URL: </B></FONT><BR><FONT size="-2" face="VERDANA">(Link as http://www.mid-day.com/)</FONT></TD>
                                    <TD WIDTH=60%>
                                        <input maxlength=100 size=27 type="text" name=leadImg value="<? echo(str_replace("\"", "&quot;", $mleadImg)); ?>">

                                    </TD>
                                </TR>
                                <TR>
                                    <TD WIDTH=40%>
                                        &nbsp;<FONT size="-1" face="VERDANA"><B>Lead Image Caption : </B></FONT><BR></TD>
                                    <TD WIDTH=60%>
                                        <TEXTAREA id=leadImgCap  name=leadImgCap ONKEYUP="maxLength(this,490)" maxlength=490 rows=3 cols=22 ><? echo(str_replace("\"", "&quot;", $mleadImgCap)) ?></TEXTAREA>
                                    </TD>
                                </TR>
                                <TR>
                                    <TD WIDTH=40%>
                                        <FONT size="-1" face="VERDANA"><B>Tags </B></FONT><FONT color=red pointsize="10"><b>*</b></FONT></TD>
                                    <TD WIDTH=60%><input type="text" id="TagName"  maxlength=500 size=27 name="TagName" value="<?= $sTagNameValue ?>">
                                        <input type="button" value="Tagsearch" onClick="window.open('rx_tag_search.php');" /></TD>
                                </TR>

                                <TR>
                                    <TD WIDTH=40%>
                                        <FONT size="-1" face="VERDANA"><B>Contains Slideshow </B></FONT>
                                    </TD>
                                    <TD WIDTH=60%>
                                        <input type="checkbox" id="frmContainSlideshow"  name="frmContainSlideshow" value="1" <? if ($iContainSlideshow == 1)
                                            echo "checked"; ?>>
                                    </TD>
                                </TR>

                                <TR>
                                    <TD WIDTH=40%>
                                        <FONT size="-1" face="VERDANA"><B>Contains Video </B></FONT>
                                    </TD>
                                    <TD WIDTH=60%>
                                        <input type="checkbox" id="frmContainVideo"  name="frmContainVideo" value="1"
                                        <? if ($iContainVideo == 1)
                                            echo "checked"; ?> >
                                    </TD>
                                </TR>

                                <?
                                if ($aid <> "") {
                                    $sql = "SELECT RXARTCH_FILENAME,RXCHA_CHANNEL_NAME FROM							  RX_ARTICLE_CHANNEL,RX_CHANNELS
		WHERE  RXCHA_CHANNEL_ID = RXARTCH_CHANNEL_ID and  RXARTCH_ARTICLE_ID=$aid";

                                    $st = mysql_query($sql, $conn);
                                    if (!$st) {
                                        echo $arr = mysql_error();
                                    }

                                    while ($output = mysql_fetch_array($st)) {
                                        $mfname = $output['RXARTCH_FILENAME'];
                                    }

                                    if ($mfname != "") {
                                        ?>
                                        <TR>
                                            <TD WIDTH=40%>
                                                &nbsp;<FONT size="-1" face="VERDANA"><B>FileName:</B></FONT><BR></TD>
                                            <TD WIDTH=60%>
                                                <FONT size="-2" face="VERDANA">
                                                <?
                                                $sql = "SELECT RXARTCH_FILENAME,RXCHA_CHANNEL_NAME FROM RX_ARTICLE_CHANNEL,RX_CHANNELS
						  WHERE  RXCHA_CHANNEL_ID = RXARTCH_CHANNEL_ID and  RXARTCH_ARTICLE_ID=$aid";
                                                $st = mysql_query($sql, $conn);
                                                if (!$st) {
                                                    echo $arr = mysql_error();
                                                }
                                                while ($output = mysql_fetch_array($st)) {
                                                    $mchname = $output['RXCHA_CHANNEL_NAME'];
                                                    $mfname = $output['RXARTCH_FILENAME'];
                                                    $mhtml = explode(".", $mfname);
                                                    $mdocid = $mchname . $myy . $mmm . $mhtml[0];
                                                    $mxml = $mhtml[0] . ".xml";
                                                    $mhtmlpath = strtolower("/" . $mchname . "/" . $myy . "/" . $mmm . "/") . $mfname;
                                                    $path = "http://www.mid-day.com" . $mhtmlpath;
                                                    ?>
                                                    <a href= "<? echo $path ?>" target="<? echo $path ?>">
                                                        <? echo $mfname; ?> </a>
                                                    <?
                                                }
                                            }
                                            ?>
                                            </FONT>
                                        </TD>
                                    </TR>
                                <? } ?>
                            </TABLE>
                        </TD>
                    </TR>
                </TABLE>
                </TD>
                </TR>
                </TABLE>
                <input type="hidden" name="mfname" value="<?= $mfname ?>">

                <BR>


                <h5>Add Related Links</h5>
                <?
                if ($aid <> "") {
                    $sql = "Select RXRL_ID,RXRL_ARTICLE_ID,RXRL_TITLE,RXRL_URL FROM RX_RELATED_LINKS where RXRL_ARTICLE_ID=" . $aid . " order by RXRL_ID ASC";
                    $stmt = mysql_query($sql, $conn);
                    $i = 0;
                    while ($output = mysql_fetch_array($stmt)) {
                        $mTitle[$i] = $output['RXRL_TITLE'];
                        $mURL[$i] = $output['RXRL_URL'];
                        $i+=1;
                    }
                }
                ?>
                <table width=90% align=center border="0">
                    <tr>
                        <td>
                            <b><FONT face="MS Sans Serif" pointsize="14">&nbsp;&nbsp;&nbsp;&nbsp;Title
                            </td><td>
                            <b><FONT face="MS Sans Serif" pointsize="14">URL
                            </td><td>
                            </tr>
                        <tr>
                            <td>
                                <FONT face="MS Sans Serif" pointsize="14">1)&nbsp;&nbsp;<input name=headline1 size=60 value="<?= $mTitle[0] ?>">
                            </td><td>
                                <FONT face="MS Sans Serif" pointsize="14"> <input name=URL1 size=70 value="<?= $mURL[0] ?>">
                            </td><td>
                        </tr>
                        <tr>
                            <td>
                                <FONT face="MS Sans Serif" pointsize="14">2)&nbsp;&nbsp;<input name=headline2 size=60 value="<?= $mTitle[1] ?>">
                            </td><td>
                                <FONT face="MS Sans Serif" pointsize="14"> <input name=URL2 size=70 value="<?= $mURL[1] ?>">
                            </td><td>
                        </tr>
                        <tr>
                            <td>
                                <FONT face="MS Sans Serif" pointsize="14">3)&nbsp;&nbsp;<input name=headline3 size=60 value="<?= $mTitle[2] ?>">
                            </td><td>
                                <FONT face="MS Sans Serif" pointsize="14"><input name=URL3 size=70 value="<?= $mURL[2] ?>">
                            </td><td>
                        </tr>
                        <tr>
                            <td>
                                <FONT face="MS Sans Serif" pointsize="14">4)&nbsp;&nbsp;<input name=headline4 size=60 value="<?= $mTitle[3] ?>">
                            </td><td>
                                <FONT face="MS Sans Serif" pointsize="14"> <input name=URL4 size=70 value="<?= $mURL[3] ?>">
                            </td><td>
                        </tr>
                        <tr>
                            <td>
                                <FONT face="MS Sans Serif" pointsize="14">5)&nbsp;&nbsp;<input name=headline5 size=60 value="<?= $mTitle[4] ?>">
                            </td><td>
                                <FONT face="MS Sans Serif" pointsize="14"> <input name=URL5 size=70 value="<?= $mURL[4] ?>">
                                <input type="hidden" id="FURL5" size=30 value="">
                            </td><td>

                        </tr>
                        <tr>
                            <td>
                                <FONT face="MS Sans Serif" pointsize="14">6)&nbsp;&nbsp;<input name=headline6 size=60 value="<?= $mTitle[5] ?>">
                            </td><td>
                                <FONT face="MS Sans Serif" pointsize="14"> <input name=URL6 size=70 value="<?= $mURL[5] ?>">
                                <input type="hidden" id="FURL6" size=70 value="">
                            </td><td>

                        </tr>

                        <tr>
                            <td>
                                <FONT face="MS Sans Serif" pointsize="14">7)&nbsp;&nbsp;<input name=headline7 size=60 value="<?= $mTitle[6] ?>">
                            </td><td>
                                <FONT face="MS Sans Serif" pointsize="14"> <input name=URL7 size=70 value="<?= $mURL[6] ?>">
                                <input type="hidden" id="FURL7" size=30 value="">
                            </td><td>
                        </tr>

                        <tr>
                            <td>
                                <FONT face="MS Sans Serif" pointsize="14">8)&nbsp;&nbsp;<input name=headline8 size=60 value="<?= $mTitle[7] ?>">
                            </td><td>
                                <FONT face="MS Sans Serif" pointsize="14"> <input name=URL8 size=70 value="<?= $mURL[7] ?>">
                                <input type="hidden" id="FURL8" size=70 value="">
                            </td><td>
                        </tr>

                        <tr>
                            <td>
                                <FONT face="MS Sans Serif" pointsize="14">9)&nbsp;&nbsp;<input name=headline9 size=60 value="<?= $mTitle[8] ?>">
                            </td><td>
                                <FONT face="MS Sans Serif" pointsize="14"> <input name=URL9 size=70 value="<?= $mURL[8] ?>">
                                <input type="hidden" id="FURL9" size=30 value="">
                            </td><td>
                        </tr>

                        <tr>
                            <td>
                                <FONT face="MS Sans Serif" pointsize="14">10)&nbsp;&nbsp;<input name=headline10 size=60 value="<?= $mTitle[9] ?>">
                            </td><td>
                                <FONT face="MS Sans Serif" pointsize="14"> <input name=URL10 size=70 value="<?= $mURL[9] ?>">
                                <input type="hidden" id="FURL10" size=30 value="">
                            </td><td>
                        </tr>
                        </table>
                        <!-- 07 Feb-->

                        <table width=90% align=center border="0">
                            <tr>
                                <td>
                                    <b><FONT face="MS Sans Serif" pointsize="14">&nbsp;&nbsp;&nbsp;&nbsp;Title
                                    </td><td>
                                    <b><FONT face="MS Sans Serif" pointsize="14">People
                                    </td><td>
                                    </tr>
                                <tr>
                                    <td>
                                        <FONT face="MS Sans Serif" pointsize="14">1)&nbsp;&nbsp;<input name=headline1 size=60 value="<?= $mTitle[0] ?>">
                                    </td><td>
                                        <FONT face="MS Sans Serif" pointsize="14"> <input name=people size=70 value="<?= $mURL[0] ?>">
                                    </td><td>
                                </tr>
                                </table>

                                <table width=90% align=center border="0">
                                    <tr>
                                        <td>
                                            <b><FONT face="MS Sans Serif" pointsize="14">&nbsp;&nbsp;&nbsp;&nbsp;Title
                                            </td><td>
                                            <b><FONT face="MS Sans Serif" pointsize="14">Category
                                            </td><td>
                                            </tr>
                                        <tr>
                                            <td>
                                                <FONT face="MS Sans Serif" pointsize="14">1)&nbsp;&nbsp;<input name=headline1 size=60 value="<?= $mTitle[0] ?>">
                                            </td><td>
                                                <FONT face="MS Sans Serif" pointsize="14"> <input name=category size=70 value="<?= $mURL[0] ?>">
                                            </td><td>
                                        </tr>
                                        </table>























                                        <TABLE width=100% border="0">

                                            <TR BGCOLOR="#CEE8FF" HEIGHT=35>
                                                <TD colspan="2">

                                                    <FONT size="-2" face="VERDANA" COLOR="#883E38"><B>Attachments :</B></FONT>
                                                    <?
                                                    if ($aid <> "") {


                                                        $sql = "SELECT
				 							RXART_ATCH_FILENAME
				 						FROM
				 							RX_ARTICLE_ATTACHS
				 						WHERE
				 							RXART_ATCH_ARTICLE_ID
				 						= '$aid'";

                                                        //error handling added on november 18,2002

                                                        $stmt = mysql_query($sql, $conn);
                                                        if (!$stmt) {
                                                            echo $arr = mysql_error();
                                                            echo("<a href=\"javascript:history.back()\">Back</a>");
                                                            exit;
                                                        }

                                                        while ($output = mysql_fetch_array($stmt)) {
                                                            $mfile = $output['RXART_ATCH_FILENAME'];

                                                            $path = "midday/articles/$mFYear/$mFMonth/attachs/$mFDay/" . $mfile;
                                                            echo "<a target=new href=\"$path\"><FONT size=\"-2\" face=\"VERDANA\">$mfile</a> &nbsp;&nbsp;&nbsp;&nbsp;";
                                                        }
                                                    }
                                                    ?>
                                                </TD>


                                            </TR>
                                            <TR HEIGHT="35"  BGCOLOR="#CEE8FF">
                                                <? if ($aid <> "") { ?>

                                                    <td width="10%">
                                                        <font size="-2" face="verdana" color="#883E38"><B>Versions :   </B></FONT>
                                                    </td>
                                                    <?
                                                    $sql = "select RXART_EVERSION,RXART_VERSION,RXART_ARTICLE_ID from
						     		 RX_ARTICLES where RXART_PARENT_ID =(select RXART_PARENT_ID
						     		 from RX_ARTICLES where
						     		 RXART_ARTICLE_ID=$aid) and RXART_SENDSTATUS='Y'
						     		 order by RXART_VERSION desc,RXART_EVERSION desc";

                                                    $st = mysql_query($sql, $conn);
                                                    if (!$st) {
                                                        echo $arr = mysql_error();
                                                    }
                                                    ?>
                                                    <td width="90%">
                                                        <?
                                                        while ($output = mysql_fetch_array($st)) {
                                                            ?>
                                                            <FONT size="-2" face="VERDANA" >
                                                            <?
                                                            $mid = $output['RXART_ARTICLE_ID'];
                                                            if ($output['RXART_EVERSION'])
                                                                $mver = ("E" . $output['RXART_EVERSION']);
                                                            else
                                                                $mver = ("V" . $output['RXART_VERSION']);


                                                            if ($mid != $aid) {
                                                                ?>
                                                                <style>
                                                                    a
                                                                    {
                                                                        font:bold;
                                                                        color:blue;
                                                                        text-decoration:none;
                                                                    }
                                                                </style>
                                                                <a href="javascript:ViewArticle(<?= $mid; ?>);">
                                                                    <? echo $mver . " | "; ?> </a>
                                                                <?
                                                            } else {
                                                                echo $mver . " | ";
                                                                ?>

                                                                </FONT>

                                                            <? }
                                                        } ?>
                                                    </TD>
                                                    <?
                                                }
                                                ?>
                                            </TR>
                                            <div id="search_form">
                                                <TR HIEGHT="40">
                                                    <TD align="left" colspan="2">
                                                        <table width=100% cellpadding=0 cellspacing=0 border=0>
                                                            <tr>
                                                                <td width=30%>
                                                                    <input type="button" name="Cancel" value="Cancel"  class="ebtn1"  onClick="javascript:Closeform();" />
                                                                </td>
                                                                <td width=70% align=right  class="form_label">
                                                                    <input type="button" name="SaveAttachment" class="ebtn1" value="Save and Attachment" onClick="SubmitReturnBack()" />
                                                                    <input type="button" name="PublishArt" class="ebtn1" id="btn_search" value="Publish" onClick="PublishArticle()" />
                                                                    <input type="button" name="SaveExit" class="ebtn1" id="btn_search" value="Save and Exit" onClick="SubmitValid()" />
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </TD>
                                                </TR>
                                            </div>
                                        </TABLE>

                                        <BR>
                                        <input name=aid type=hidden value="<? echo($aid); ?>">
                                        <input name="frmSlideshow"  id="frmSlideshow" type="hidden" value="<? echo($iSlideShowId); ?>">
                                        <input name=rlflg type=hidden value="N">
                                        <INPUT name=thisContentItem type=hidden>
                                        </FORM>
                                        </div>
                                        </BODY>
                                        </HTML>
                                        <?
                                        include("html/footer.php");
                                        mysql_close($conn);
                                        ?> 