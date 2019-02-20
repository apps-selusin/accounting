<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "personinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$person_list = NULL; // Initialize page object first

class cperson_list extends cperson {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{994C6BDE-6323-4115-98D0-9A87BE039368}";

	// Table name
	var $TableName = 'person';

	// Page object name
	var $PageObjName = 'person_list';

	// Grid form hidden field names
	var $FormName = 'fpersonlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (person)
		if (!isset($GLOBALS["person"]) || get_class($GLOBALS["person"]) == "cperson") {
			$GLOBALS["person"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["person"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "personadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "persondelete.php";
		$this->MultiUpdateUrl = "personupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'person', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fpersonlistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->id->SetVisibility();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->kode->SetVisibility();
		$this->nama->SetVisibility();
		$this->kontak->SetVisibility();
		$this->type_id->SetVisibility();
		$this->telp1->SetVisibility();
		$this->matauang_id->SetVisibility();
		$this->username->SetVisibility();
		$this->password->SetVisibility();
		$this->telp2->SetVisibility();
		$this->fax->SetVisibility();
		$this->hp->SetVisibility();
		$this->_email->SetVisibility();
		$this->website->SetVisibility();
		$this->npwp->SetVisibility();
		$this->alamat->SetVisibility();
		$this->kota->SetVisibility();
		$this->zip->SetVisibility();
		$this->klasifikasi_id->SetVisibility();
		$this->id_FK->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $person;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($person);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fpersonlistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->kode->AdvancedSearch->ToJSON(), ","); // Field kode
		$sFilterList = ew_Concat($sFilterList, $this->nama->AdvancedSearch->ToJSON(), ","); // Field nama
		$sFilterList = ew_Concat($sFilterList, $this->kontak->AdvancedSearch->ToJSON(), ","); // Field kontak
		$sFilterList = ew_Concat($sFilterList, $this->type_id->AdvancedSearch->ToJSON(), ","); // Field type_id
		$sFilterList = ew_Concat($sFilterList, $this->telp1->AdvancedSearch->ToJSON(), ","); // Field telp1
		$sFilterList = ew_Concat($sFilterList, $this->matauang_id->AdvancedSearch->ToJSON(), ","); // Field matauang_id
		$sFilterList = ew_Concat($sFilterList, $this->username->AdvancedSearch->ToJSON(), ","); // Field username
		$sFilterList = ew_Concat($sFilterList, $this->password->AdvancedSearch->ToJSON(), ","); // Field password
		$sFilterList = ew_Concat($sFilterList, $this->telp2->AdvancedSearch->ToJSON(), ","); // Field telp2
		$sFilterList = ew_Concat($sFilterList, $this->fax->AdvancedSearch->ToJSON(), ","); // Field fax
		$sFilterList = ew_Concat($sFilterList, $this->hp->AdvancedSearch->ToJSON(), ","); // Field hp
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJSON(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->website->AdvancedSearch->ToJSON(), ","); // Field website
		$sFilterList = ew_Concat($sFilterList, $this->npwp->AdvancedSearch->ToJSON(), ","); // Field npwp
		$sFilterList = ew_Concat($sFilterList, $this->alamat->AdvancedSearch->ToJSON(), ","); // Field alamat
		$sFilterList = ew_Concat($sFilterList, $this->kota->AdvancedSearch->ToJSON(), ","); // Field kota
		$sFilterList = ew_Concat($sFilterList, $this->zip->AdvancedSearch->ToJSON(), ","); // Field zip
		$sFilterList = ew_Concat($sFilterList, $this->klasifikasi_id->AdvancedSearch->ToJSON(), ","); // Field klasifikasi_id
		$sFilterList = ew_Concat($sFilterList, $this->id_FK->AdvancedSearch->ToJSON(), ","); // Field id_FK
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "fpersonlistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field kode
		$this->kode->AdvancedSearch->SearchValue = @$filter["x_kode"];
		$this->kode->AdvancedSearch->SearchOperator = @$filter["z_kode"];
		$this->kode->AdvancedSearch->SearchCondition = @$filter["v_kode"];
		$this->kode->AdvancedSearch->SearchValue2 = @$filter["y_kode"];
		$this->kode->AdvancedSearch->SearchOperator2 = @$filter["w_kode"];
		$this->kode->AdvancedSearch->Save();

		// Field nama
		$this->nama->AdvancedSearch->SearchValue = @$filter["x_nama"];
		$this->nama->AdvancedSearch->SearchOperator = @$filter["z_nama"];
		$this->nama->AdvancedSearch->SearchCondition = @$filter["v_nama"];
		$this->nama->AdvancedSearch->SearchValue2 = @$filter["y_nama"];
		$this->nama->AdvancedSearch->SearchOperator2 = @$filter["w_nama"];
		$this->nama->AdvancedSearch->Save();

		// Field kontak
		$this->kontak->AdvancedSearch->SearchValue = @$filter["x_kontak"];
		$this->kontak->AdvancedSearch->SearchOperator = @$filter["z_kontak"];
		$this->kontak->AdvancedSearch->SearchCondition = @$filter["v_kontak"];
		$this->kontak->AdvancedSearch->SearchValue2 = @$filter["y_kontak"];
		$this->kontak->AdvancedSearch->SearchOperator2 = @$filter["w_kontak"];
		$this->kontak->AdvancedSearch->Save();

		// Field type_id
		$this->type_id->AdvancedSearch->SearchValue = @$filter["x_type_id"];
		$this->type_id->AdvancedSearch->SearchOperator = @$filter["z_type_id"];
		$this->type_id->AdvancedSearch->SearchCondition = @$filter["v_type_id"];
		$this->type_id->AdvancedSearch->SearchValue2 = @$filter["y_type_id"];
		$this->type_id->AdvancedSearch->SearchOperator2 = @$filter["w_type_id"];
		$this->type_id->AdvancedSearch->Save();

		// Field telp1
		$this->telp1->AdvancedSearch->SearchValue = @$filter["x_telp1"];
		$this->telp1->AdvancedSearch->SearchOperator = @$filter["z_telp1"];
		$this->telp1->AdvancedSearch->SearchCondition = @$filter["v_telp1"];
		$this->telp1->AdvancedSearch->SearchValue2 = @$filter["y_telp1"];
		$this->telp1->AdvancedSearch->SearchOperator2 = @$filter["w_telp1"];
		$this->telp1->AdvancedSearch->Save();

		// Field matauang_id
		$this->matauang_id->AdvancedSearch->SearchValue = @$filter["x_matauang_id"];
		$this->matauang_id->AdvancedSearch->SearchOperator = @$filter["z_matauang_id"];
		$this->matauang_id->AdvancedSearch->SearchCondition = @$filter["v_matauang_id"];
		$this->matauang_id->AdvancedSearch->SearchValue2 = @$filter["y_matauang_id"];
		$this->matauang_id->AdvancedSearch->SearchOperator2 = @$filter["w_matauang_id"];
		$this->matauang_id->AdvancedSearch->Save();

		// Field username
		$this->username->AdvancedSearch->SearchValue = @$filter["x_username"];
		$this->username->AdvancedSearch->SearchOperator = @$filter["z_username"];
		$this->username->AdvancedSearch->SearchCondition = @$filter["v_username"];
		$this->username->AdvancedSearch->SearchValue2 = @$filter["y_username"];
		$this->username->AdvancedSearch->SearchOperator2 = @$filter["w_username"];
		$this->username->AdvancedSearch->Save();

		// Field password
		$this->password->AdvancedSearch->SearchValue = @$filter["x_password"];
		$this->password->AdvancedSearch->SearchOperator = @$filter["z_password"];
		$this->password->AdvancedSearch->SearchCondition = @$filter["v_password"];
		$this->password->AdvancedSearch->SearchValue2 = @$filter["y_password"];
		$this->password->AdvancedSearch->SearchOperator2 = @$filter["w_password"];
		$this->password->AdvancedSearch->Save();

		// Field telp2
		$this->telp2->AdvancedSearch->SearchValue = @$filter["x_telp2"];
		$this->telp2->AdvancedSearch->SearchOperator = @$filter["z_telp2"];
		$this->telp2->AdvancedSearch->SearchCondition = @$filter["v_telp2"];
		$this->telp2->AdvancedSearch->SearchValue2 = @$filter["y_telp2"];
		$this->telp2->AdvancedSearch->SearchOperator2 = @$filter["w_telp2"];
		$this->telp2->AdvancedSearch->Save();

		// Field fax
		$this->fax->AdvancedSearch->SearchValue = @$filter["x_fax"];
		$this->fax->AdvancedSearch->SearchOperator = @$filter["z_fax"];
		$this->fax->AdvancedSearch->SearchCondition = @$filter["v_fax"];
		$this->fax->AdvancedSearch->SearchValue2 = @$filter["y_fax"];
		$this->fax->AdvancedSearch->SearchOperator2 = @$filter["w_fax"];
		$this->fax->AdvancedSearch->Save();

		// Field hp
		$this->hp->AdvancedSearch->SearchValue = @$filter["x_hp"];
		$this->hp->AdvancedSearch->SearchOperator = @$filter["z_hp"];
		$this->hp->AdvancedSearch->SearchCondition = @$filter["v_hp"];
		$this->hp->AdvancedSearch->SearchValue2 = @$filter["y_hp"];
		$this->hp->AdvancedSearch->SearchOperator2 = @$filter["w_hp"];
		$this->hp->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field website
		$this->website->AdvancedSearch->SearchValue = @$filter["x_website"];
		$this->website->AdvancedSearch->SearchOperator = @$filter["z_website"];
		$this->website->AdvancedSearch->SearchCondition = @$filter["v_website"];
		$this->website->AdvancedSearch->SearchValue2 = @$filter["y_website"];
		$this->website->AdvancedSearch->SearchOperator2 = @$filter["w_website"];
		$this->website->AdvancedSearch->Save();

		// Field npwp
		$this->npwp->AdvancedSearch->SearchValue = @$filter["x_npwp"];
		$this->npwp->AdvancedSearch->SearchOperator = @$filter["z_npwp"];
		$this->npwp->AdvancedSearch->SearchCondition = @$filter["v_npwp"];
		$this->npwp->AdvancedSearch->SearchValue2 = @$filter["y_npwp"];
		$this->npwp->AdvancedSearch->SearchOperator2 = @$filter["w_npwp"];
		$this->npwp->AdvancedSearch->Save();

		// Field alamat
		$this->alamat->AdvancedSearch->SearchValue = @$filter["x_alamat"];
		$this->alamat->AdvancedSearch->SearchOperator = @$filter["z_alamat"];
		$this->alamat->AdvancedSearch->SearchCondition = @$filter["v_alamat"];
		$this->alamat->AdvancedSearch->SearchValue2 = @$filter["y_alamat"];
		$this->alamat->AdvancedSearch->SearchOperator2 = @$filter["w_alamat"];
		$this->alamat->AdvancedSearch->Save();

		// Field kota
		$this->kota->AdvancedSearch->SearchValue = @$filter["x_kota"];
		$this->kota->AdvancedSearch->SearchOperator = @$filter["z_kota"];
		$this->kota->AdvancedSearch->SearchCondition = @$filter["v_kota"];
		$this->kota->AdvancedSearch->SearchValue2 = @$filter["y_kota"];
		$this->kota->AdvancedSearch->SearchOperator2 = @$filter["w_kota"];
		$this->kota->AdvancedSearch->Save();

		// Field zip
		$this->zip->AdvancedSearch->SearchValue = @$filter["x_zip"];
		$this->zip->AdvancedSearch->SearchOperator = @$filter["z_zip"];
		$this->zip->AdvancedSearch->SearchCondition = @$filter["v_zip"];
		$this->zip->AdvancedSearch->SearchValue2 = @$filter["y_zip"];
		$this->zip->AdvancedSearch->SearchOperator2 = @$filter["w_zip"];
		$this->zip->AdvancedSearch->Save();

		// Field klasifikasi_id
		$this->klasifikasi_id->AdvancedSearch->SearchValue = @$filter["x_klasifikasi_id"];
		$this->klasifikasi_id->AdvancedSearch->SearchOperator = @$filter["z_klasifikasi_id"];
		$this->klasifikasi_id->AdvancedSearch->SearchCondition = @$filter["v_klasifikasi_id"];
		$this->klasifikasi_id->AdvancedSearch->SearchValue2 = @$filter["y_klasifikasi_id"];
		$this->klasifikasi_id->AdvancedSearch->SearchOperator2 = @$filter["w_klasifikasi_id"];
		$this->klasifikasi_id->AdvancedSearch->Save();

		// Field id_FK
		$this->id_FK->AdvancedSearch->SearchValue = @$filter["x_id_FK"];
		$this->id_FK->AdvancedSearch->SearchOperator = @$filter["z_id_FK"];
		$this->id_FK->AdvancedSearch->SearchCondition = @$filter["v_id_FK"];
		$this->id_FK->AdvancedSearch->SearchValue2 = @$filter["y_id_FK"];
		$this->id_FK->AdvancedSearch->SearchOperator2 = @$filter["w_id_FK"];
		$this->id_FK->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->kode, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nama, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->kontak, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->telp1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->username, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->password, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->telp2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fax, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->hp, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->website, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->npwp, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->alamat, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->kota, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->zip, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id, $bCtrl); // id
			$this->UpdateSort($this->kode, $bCtrl); // kode
			$this->UpdateSort($this->nama, $bCtrl); // nama
			$this->UpdateSort($this->kontak, $bCtrl); // kontak
			$this->UpdateSort($this->type_id, $bCtrl); // type_id
			$this->UpdateSort($this->telp1, $bCtrl); // telp1
			$this->UpdateSort($this->matauang_id, $bCtrl); // matauang_id
			$this->UpdateSort($this->username, $bCtrl); // username
			$this->UpdateSort($this->password, $bCtrl); // password
			$this->UpdateSort($this->telp2, $bCtrl); // telp2
			$this->UpdateSort($this->fax, $bCtrl); // fax
			$this->UpdateSort($this->hp, $bCtrl); // hp
			$this->UpdateSort($this->_email, $bCtrl); // email
			$this->UpdateSort($this->website, $bCtrl); // website
			$this->UpdateSort($this->npwp, $bCtrl); // npwp
			$this->UpdateSort($this->alamat, $bCtrl); // alamat
			$this->UpdateSort($this->kota, $bCtrl); // kota
			$this->UpdateSort($this->zip, $bCtrl); // zip
			$this->UpdateSort($this->klasifikasi_id, $bCtrl); // klasifikasi_id
			$this->UpdateSort($this->id_FK, $bCtrl); // id_FK
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->kode->setSort("");
				$this->nama->setSort("");
				$this->kontak->setSort("");
				$this->type_id->setSort("");
				$this->telp1->setSort("");
				$this->matauang_id->setSort("");
				$this->username->setSort("");
				$this->password->setSort("");
				$this->telp2->setSort("");
				$this->fax->setSort("");
				$this->hp->setSort("");
				$this->_email->setSort("");
				$this->website->setSort("");
				$this->npwp->setSort("");
				$this->alamat->setSort("");
				$this->kota->setSort("");
				$this->zip->setSort("");
				$this->klasifikasi_id->setSort("");
				$this->id_FK->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fpersonlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fpersonlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fpersonlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fpersonlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->kode->setDbValue($rs->fields('kode'));
		$this->nama->setDbValue($rs->fields('nama'));
		$this->kontak->setDbValue($rs->fields('kontak'));
		$this->type_id->setDbValue($rs->fields('type_id'));
		$this->telp1->setDbValue($rs->fields('telp1'));
		$this->matauang_id->setDbValue($rs->fields('matauang_id'));
		$this->username->setDbValue($rs->fields('username'));
		$this->password->setDbValue($rs->fields('password'));
		$this->telp2->setDbValue($rs->fields('telp2'));
		$this->fax->setDbValue($rs->fields('fax'));
		$this->hp->setDbValue($rs->fields('hp'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->website->setDbValue($rs->fields('website'));
		$this->npwp->setDbValue($rs->fields('npwp'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->kota->setDbValue($rs->fields('kota'));
		$this->zip->setDbValue($rs->fields('zip'));
		$this->klasifikasi_id->setDbValue($rs->fields('klasifikasi_id'));
		$this->id_FK->setDbValue($rs->fields('id_FK'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->kode->DbValue = $row['kode'];
		$this->nama->DbValue = $row['nama'];
		$this->kontak->DbValue = $row['kontak'];
		$this->type_id->DbValue = $row['type_id'];
		$this->telp1->DbValue = $row['telp1'];
		$this->matauang_id->DbValue = $row['matauang_id'];
		$this->username->DbValue = $row['username'];
		$this->password->DbValue = $row['password'];
		$this->telp2->DbValue = $row['telp2'];
		$this->fax->DbValue = $row['fax'];
		$this->hp->DbValue = $row['hp'];
		$this->_email->DbValue = $row['email'];
		$this->website->DbValue = $row['website'];
		$this->npwp->DbValue = $row['npwp'];
		$this->alamat->DbValue = $row['alamat'];
		$this->kota->DbValue = $row['kota'];
		$this->zip->DbValue = $row['zip'];
		$this->klasifikasi_id->DbValue = $row['klasifikasi_id'];
		$this->id_FK->DbValue = $row['id_FK'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// kode
		// nama
		// kontak
		// type_id
		// telp1
		// matauang_id
		// username
		// password
		// telp2
		// fax
		// hp
		// email
		// website
		// npwp
		// alamat
		// kota
		// zip
		// klasifikasi_id
		// id_FK

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// kode
		$this->kode->ViewValue = $this->kode->CurrentValue;
		$this->kode->ViewCustomAttributes = "";

		// nama
		$this->nama->ViewValue = $this->nama->CurrentValue;
		$this->nama->ViewCustomAttributes = "";

		// kontak
		$this->kontak->ViewValue = $this->kontak->CurrentValue;
		$this->kontak->ViewCustomAttributes = "";

		// type_id
		$this->type_id->ViewValue = $this->type_id->CurrentValue;
		$this->type_id->ViewCustomAttributes = "";

		// telp1
		$this->telp1->ViewValue = $this->telp1->CurrentValue;
		$this->telp1->ViewCustomAttributes = "";

		// matauang_id
		$this->matauang_id->ViewValue = $this->matauang_id->CurrentValue;
		$this->matauang_id->ViewCustomAttributes = "";

		// username
		$this->username->ViewValue = $this->username->CurrentValue;
		$this->username->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// telp2
		$this->telp2->ViewValue = $this->telp2->CurrentValue;
		$this->telp2->ViewCustomAttributes = "";

		// fax
		$this->fax->ViewValue = $this->fax->CurrentValue;
		$this->fax->ViewCustomAttributes = "";

		// hp
		$this->hp->ViewValue = $this->hp->CurrentValue;
		$this->hp->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// website
		$this->website->ViewValue = $this->website->CurrentValue;
		$this->website->ViewCustomAttributes = "";

		// npwp
		$this->npwp->ViewValue = $this->npwp->CurrentValue;
		$this->npwp->ViewCustomAttributes = "";

		// alamat
		$this->alamat->ViewValue = $this->alamat->CurrentValue;
		$this->alamat->ViewCustomAttributes = "";

		// kota
		$this->kota->ViewValue = $this->kota->CurrentValue;
		$this->kota->ViewCustomAttributes = "";

		// zip
		$this->zip->ViewValue = $this->zip->CurrentValue;
		$this->zip->ViewCustomAttributes = "";

		// klasifikasi_id
		$this->klasifikasi_id->ViewValue = $this->klasifikasi_id->CurrentValue;
		$this->klasifikasi_id->ViewCustomAttributes = "";

		// id_FK
		$this->id_FK->ViewValue = $this->id_FK->CurrentValue;
		$this->id_FK->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// kode
			$this->kode->LinkCustomAttributes = "";
			$this->kode->HrefValue = "";
			$this->kode->TooltipValue = "";

			// nama
			$this->nama->LinkCustomAttributes = "";
			$this->nama->HrefValue = "";
			$this->nama->TooltipValue = "";

			// kontak
			$this->kontak->LinkCustomAttributes = "";
			$this->kontak->HrefValue = "";
			$this->kontak->TooltipValue = "";

			// type_id
			$this->type_id->LinkCustomAttributes = "";
			$this->type_id->HrefValue = "";
			$this->type_id->TooltipValue = "";

			// telp1
			$this->telp1->LinkCustomAttributes = "";
			$this->telp1->HrefValue = "";
			$this->telp1->TooltipValue = "";

			// matauang_id
			$this->matauang_id->LinkCustomAttributes = "";
			$this->matauang_id->HrefValue = "";
			$this->matauang_id->TooltipValue = "";

			// username
			$this->username->LinkCustomAttributes = "";
			$this->username->HrefValue = "";
			$this->username->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// telp2
			$this->telp2->LinkCustomAttributes = "";
			$this->telp2->HrefValue = "";
			$this->telp2->TooltipValue = "";

			// fax
			$this->fax->LinkCustomAttributes = "";
			$this->fax->HrefValue = "";
			$this->fax->TooltipValue = "";

			// hp
			$this->hp->LinkCustomAttributes = "";
			$this->hp->HrefValue = "";
			$this->hp->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";
			$this->website->TooltipValue = "";

			// npwp
			$this->npwp->LinkCustomAttributes = "";
			$this->npwp->HrefValue = "";
			$this->npwp->TooltipValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";
			$this->alamat->TooltipValue = "";

			// kota
			$this->kota->LinkCustomAttributes = "";
			$this->kota->HrefValue = "";
			$this->kota->TooltipValue = "";

			// zip
			$this->zip->LinkCustomAttributes = "";
			$this->zip->HrefValue = "";
			$this->zip->TooltipValue = "";

			// klasifikasi_id
			$this->klasifikasi_id->LinkCustomAttributes = "";
			$this->klasifikasi_id->HrefValue = "";
			$this->klasifikasi_id->TooltipValue = "";

			// id_FK
			$this->id_FK->LinkCustomAttributes = "";
			$this->id_FK->HrefValue = "";
			$this->id_FK->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($person_list)) $person_list = new cperson_list();

// Page init
$person_list->Page_Init();

// Page main
$person_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$person_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fpersonlist = new ew_Form("fpersonlist", "list");
fpersonlist.FormKeyCountName = '<?php echo $person_list->FormKeyCountName ?>';

// Form_CustomValidate event
fpersonlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpersonlist.ValidateRequired = true;
<?php } else { ?>
fpersonlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fpersonlistsrch = new ew_Form("fpersonlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($person_list->TotalRecs > 0 && $person_list->ExportOptions->Visible()) { ?>
<?php $person_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($person_list->SearchOptions->Visible()) { ?>
<?php $person_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($person_list->FilterOptions->Visible()) { ?>
<?php $person_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $person_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($person_list->TotalRecs <= 0)
			$person_list->TotalRecs = $person->SelectRecordCount();
	} else {
		if (!$person_list->Recordset && ($person_list->Recordset = $person_list->LoadRecordset()))
			$person_list->TotalRecs = $person_list->Recordset->RecordCount();
	}
	$person_list->StartRec = 1;
	if ($person_list->DisplayRecs <= 0 || ($person->Export <> "" && $person->ExportAll)) // Display all records
		$person_list->DisplayRecs = $person_list->TotalRecs;
	if (!($person->Export <> "" && $person->ExportAll))
		$person_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$person_list->Recordset = $person_list->LoadRecordset($person_list->StartRec-1, $person_list->DisplayRecs);

	// Set no record found message
	if ($person->CurrentAction == "" && $person_list->TotalRecs == 0) {
		if ($person_list->SearchWhere == "0=101")
			$person_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$person_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$person_list->RenderOtherOptions();
?>
<?php if ($person->Export == "" && $person->CurrentAction == "") { ?>
<form name="fpersonlistsrch" id="fpersonlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($person_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fpersonlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="person">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($person_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($person_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $person_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($person_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($person_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($person_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($person_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $person_list->ShowPageHeader(); ?>
<?php
$person_list->ShowMessage();
?>
<?php if ($person_list->TotalRecs > 0 || $person->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid person">
<form name="fpersonlist" id="fpersonlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($person_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $person_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="person">
<div id="gmp_person" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($person_list->TotalRecs > 0 || $person->CurrentAction == "gridedit") { ?>
<table id="tbl_personlist" class="table ewTable">
<?php echo $person->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$person_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$person_list->RenderListOptions();

// Render list options (header, left)
$person_list->ListOptions->Render("header", "left");
?>
<?php if ($person->id->Visible) { // id ?>
	<?php if ($person->SortUrl($person->id) == "") { ?>
		<th data-name="id"><div id="elh_person_id" class="person_id"><div class="ewTableHeaderCaption"><?php echo $person->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->id) ?>',2);"><div id="elh_person_id" class="person_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($person->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->kode->Visible) { // kode ?>
	<?php if ($person->SortUrl($person->kode) == "") { ?>
		<th data-name="kode"><div id="elh_person_kode" class="person_kode"><div class="ewTableHeaderCaption"><?php echo $person->kode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->kode) ?>',2);"><div id="elh_person_kode" class="person_kode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->kode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->kode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->kode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->nama->Visible) { // nama ?>
	<?php if ($person->SortUrl($person->nama) == "") { ?>
		<th data-name="nama"><div id="elh_person_nama" class="person_nama"><div class="ewTableHeaderCaption"><?php echo $person->nama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nama"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->nama) ?>',2);"><div id="elh_person_nama" class="person_nama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->nama->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->kontak->Visible) { // kontak ?>
	<?php if ($person->SortUrl($person->kontak) == "") { ?>
		<th data-name="kontak"><div id="elh_person_kontak" class="person_kontak"><div class="ewTableHeaderCaption"><?php echo $person->kontak->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kontak"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->kontak) ?>',2);"><div id="elh_person_kontak" class="person_kontak">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->kontak->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->kontak->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->kontak->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->type_id->Visible) { // type_id ?>
	<?php if ($person->SortUrl($person->type_id) == "") { ?>
		<th data-name="type_id"><div id="elh_person_type_id" class="person_type_id"><div class="ewTableHeaderCaption"><?php echo $person->type_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="type_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->type_id) ?>',2);"><div id="elh_person_type_id" class="person_type_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->type_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($person->type_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->type_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->telp1->Visible) { // telp1 ?>
	<?php if ($person->SortUrl($person->telp1) == "") { ?>
		<th data-name="telp1"><div id="elh_person_telp1" class="person_telp1"><div class="ewTableHeaderCaption"><?php echo $person->telp1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telp1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->telp1) ?>',2);"><div id="elh_person_telp1" class="person_telp1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->telp1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->telp1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->telp1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->matauang_id->Visible) { // matauang_id ?>
	<?php if ($person->SortUrl($person->matauang_id) == "") { ?>
		<th data-name="matauang_id"><div id="elh_person_matauang_id" class="person_matauang_id"><div class="ewTableHeaderCaption"><?php echo $person->matauang_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="matauang_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->matauang_id) ?>',2);"><div id="elh_person_matauang_id" class="person_matauang_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->matauang_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($person->matauang_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->matauang_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->username->Visible) { // username ?>
	<?php if ($person->SortUrl($person->username) == "") { ?>
		<th data-name="username"><div id="elh_person_username" class="person_username"><div class="ewTableHeaderCaption"><?php echo $person->username->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="username"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->username) ?>',2);"><div id="elh_person_username" class="person_username">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->username->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->username->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->username->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->password->Visible) { // password ?>
	<?php if ($person->SortUrl($person->password) == "") { ?>
		<th data-name="password"><div id="elh_person_password" class="person_password"><div class="ewTableHeaderCaption"><?php echo $person->password->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="password"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->password) ?>',2);"><div id="elh_person_password" class="person_password">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->password->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->password->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->password->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->telp2->Visible) { // telp2 ?>
	<?php if ($person->SortUrl($person->telp2) == "") { ?>
		<th data-name="telp2"><div id="elh_person_telp2" class="person_telp2"><div class="ewTableHeaderCaption"><?php echo $person->telp2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telp2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->telp2) ?>',2);"><div id="elh_person_telp2" class="person_telp2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->telp2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->telp2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->telp2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->fax->Visible) { // fax ?>
	<?php if ($person->SortUrl($person->fax) == "") { ?>
		<th data-name="fax"><div id="elh_person_fax" class="person_fax"><div class="ewTableHeaderCaption"><?php echo $person->fax->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fax"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->fax) ?>',2);"><div id="elh_person_fax" class="person_fax">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->fax->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->fax->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->fax->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->hp->Visible) { // hp ?>
	<?php if ($person->SortUrl($person->hp) == "") { ?>
		<th data-name="hp"><div id="elh_person_hp" class="person_hp"><div class="ewTableHeaderCaption"><?php echo $person->hp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->hp) ?>',2);"><div id="elh_person_hp" class="person_hp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->hp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->hp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->hp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->_email->Visible) { // email ?>
	<?php if ($person->SortUrl($person->_email) == "") { ?>
		<th data-name="_email"><div id="elh_person__email" class="person__email"><div class="ewTableHeaderCaption"><?php echo $person->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->_email) ?>',2);"><div id="elh_person__email" class="person__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->website->Visible) { // website ?>
	<?php if ($person->SortUrl($person->website) == "") { ?>
		<th data-name="website"><div id="elh_person_website" class="person_website"><div class="ewTableHeaderCaption"><?php echo $person->website->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="website"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->website) ?>',2);"><div id="elh_person_website" class="person_website">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->website->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->website->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->website->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->npwp->Visible) { // npwp ?>
	<?php if ($person->SortUrl($person->npwp) == "") { ?>
		<th data-name="npwp"><div id="elh_person_npwp" class="person_npwp"><div class="ewTableHeaderCaption"><?php echo $person->npwp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="npwp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->npwp) ?>',2);"><div id="elh_person_npwp" class="person_npwp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->npwp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->npwp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->npwp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->alamat->Visible) { // alamat ?>
	<?php if ($person->SortUrl($person->alamat) == "") { ?>
		<th data-name="alamat"><div id="elh_person_alamat" class="person_alamat"><div class="ewTableHeaderCaption"><?php echo $person->alamat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="alamat"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->alamat) ?>',2);"><div id="elh_person_alamat" class="person_alamat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->alamat->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->alamat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->alamat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->kota->Visible) { // kota ?>
	<?php if ($person->SortUrl($person->kota) == "") { ?>
		<th data-name="kota"><div id="elh_person_kota" class="person_kota"><div class="ewTableHeaderCaption"><?php echo $person->kota->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kota"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->kota) ?>',2);"><div id="elh_person_kota" class="person_kota">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->kota->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->kota->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->kota->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->zip->Visible) { // zip ?>
	<?php if ($person->SortUrl($person->zip) == "") { ?>
		<th data-name="zip"><div id="elh_person_zip" class="person_zip"><div class="ewTableHeaderCaption"><?php echo $person->zip->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zip"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->zip) ?>',2);"><div id="elh_person_zip" class="person_zip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->zip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($person->zip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->zip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->klasifikasi_id->Visible) { // klasifikasi_id ?>
	<?php if ($person->SortUrl($person->klasifikasi_id) == "") { ?>
		<th data-name="klasifikasi_id"><div id="elh_person_klasifikasi_id" class="person_klasifikasi_id"><div class="ewTableHeaderCaption"><?php echo $person->klasifikasi_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="klasifikasi_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->klasifikasi_id) ?>',2);"><div id="elh_person_klasifikasi_id" class="person_klasifikasi_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->klasifikasi_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($person->klasifikasi_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->klasifikasi_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($person->id_FK->Visible) { // id_FK ?>
	<?php if ($person->SortUrl($person->id_FK) == "") { ?>
		<th data-name="id_FK"><div id="elh_person_id_FK" class="person_id_FK"><div class="ewTableHeaderCaption"><?php echo $person->id_FK->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_FK"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $person->SortUrl($person->id_FK) ?>',2);"><div id="elh_person_id_FK" class="person_id_FK">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $person->id_FK->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($person->id_FK->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($person->id_FK->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$person_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($person->ExportAll && $person->Export <> "") {
	$person_list->StopRec = $person_list->TotalRecs;
} else {

	// Set the last record to display
	if ($person_list->TotalRecs > $person_list->StartRec + $person_list->DisplayRecs - 1)
		$person_list->StopRec = $person_list->StartRec + $person_list->DisplayRecs - 1;
	else
		$person_list->StopRec = $person_list->TotalRecs;
}
$person_list->RecCnt = $person_list->StartRec - 1;
if ($person_list->Recordset && !$person_list->Recordset->EOF) {
	$person_list->Recordset->MoveFirst();
	$bSelectLimit = $person_list->UseSelectLimit;
	if (!$bSelectLimit && $person_list->StartRec > 1)
		$person_list->Recordset->Move($person_list->StartRec - 1);
} elseif (!$person->AllowAddDeleteRow && $person_list->StopRec == 0) {
	$person_list->StopRec = $person->GridAddRowCount;
}

// Initialize aggregate
$person->RowType = EW_ROWTYPE_AGGREGATEINIT;
$person->ResetAttrs();
$person_list->RenderRow();
while ($person_list->RecCnt < $person_list->StopRec) {
	$person_list->RecCnt++;
	if (intval($person_list->RecCnt) >= intval($person_list->StartRec)) {
		$person_list->RowCnt++;

		// Set up key count
		$person_list->KeyCount = $person_list->RowIndex;

		// Init row class and style
		$person->ResetAttrs();
		$person->CssClass = "";
		if ($person->CurrentAction == "gridadd") {
		} else {
			$person_list->LoadRowValues($person_list->Recordset); // Load row values
		}
		$person->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$person->RowAttrs = array_merge($person->RowAttrs, array('data-rowindex'=>$person_list->RowCnt, 'id'=>'r' . $person_list->RowCnt . '_person', 'data-rowtype'=>$person->RowType));

		// Render row
		$person_list->RenderRow();

		// Render list options
		$person_list->RenderListOptions();
?>
	<tr<?php echo $person->RowAttributes() ?>>
<?php

// Render list options (body, left)
$person_list->ListOptions->Render("body", "left", $person_list->RowCnt);
?>
	<?php if ($person->id->Visible) { // id ?>
		<td data-name="id"<?php echo $person->id->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_id" class="person_id">
<span<?php echo $person->id->ViewAttributes() ?>>
<?php echo $person->id->ListViewValue() ?></span>
</span>
<a id="<?php echo $person_list->PageObjName . "_row_" . $person_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($person->kode->Visible) { // kode ?>
		<td data-name="kode"<?php echo $person->kode->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_kode" class="person_kode">
<span<?php echo $person->kode->ViewAttributes() ?>>
<?php echo $person->kode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->nama->Visible) { // nama ?>
		<td data-name="nama"<?php echo $person->nama->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_nama" class="person_nama">
<span<?php echo $person->nama->ViewAttributes() ?>>
<?php echo $person->nama->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->kontak->Visible) { // kontak ?>
		<td data-name="kontak"<?php echo $person->kontak->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_kontak" class="person_kontak">
<span<?php echo $person->kontak->ViewAttributes() ?>>
<?php echo $person->kontak->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->type_id->Visible) { // type_id ?>
		<td data-name="type_id"<?php echo $person->type_id->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_type_id" class="person_type_id">
<span<?php echo $person->type_id->ViewAttributes() ?>>
<?php echo $person->type_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->telp1->Visible) { // telp1 ?>
		<td data-name="telp1"<?php echo $person->telp1->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_telp1" class="person_telp1">
<span<?php echo $person->telp1->ViewAttributes() ?>>
<?php echo $person->telp1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->matauang_id->Visible) { // matauang_id ?>
		<td data-name="matauang_id"<?php echo $person->matauang_id->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_matauang_id" class="person_matauang_id">
<span<?php echo $person->matauang_id->ViewAttributes() ?>>
<?php echo $person->matauang_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->username->Visible) { // username ?>
		<td data-name="username"<?php echo $person->username->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_username" class="person_username">
<span<?php echo $person->username->ViewAttributes() ?>>
<?php echo $person->username->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->password->Visible) { // password ?>
		<td data-name="password"<?php echo $person->password->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_password" class="person_password">
<span<?php echo $person->password->ViewAttributes() ?>>
<?php echo $person->password->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->telp2->Visible) { // telp2 ?>
		<td data-name="telp2"<?php echo $person->telp2->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_telp2" class="person_telp2">
<span<?php echo $person->telp2->ViewAttributes() ?>>
<?php echo $person->telp2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->fax->Visible) { // fax ?>
		<td data-name="fax"<?php echo $person->fax->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_fax" class="person_fax">
<span<?php echo $person->fax->ViewAttributes() ?>>
<?php echo $person->fax->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->hp->Visible) { // hp ?>
		<td data-name="hp"<?php echo $person->hp->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_hp" class="person_hp">
<span<?php echo $person->hp->ViewAttributes() ?>>
<?php echo $person->hp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $person->_email->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person__email" class="person__email">
<span<?php echo $person->_email->ViewAttributes() ?>>
<?php echo $person->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->website->Visible) { // website ?>
		<td data-name="website"<?php echo $person->website->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_website" class="person_website">
<span<?php echo $person->website->ViewAttributes() ?>>
<?php echo $person->website->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->npwp->Visible) { // npwp ?>
		<td data-name="npwp"<?php echo $person->npwp->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_npwp" class="person_npwp">
<span<?php echo $person->npwp->ViewAttributes() ?>>
<?php echo $person->npwp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->alamat->Visible) { // alamat ?>
		<td data-name="alamat"<?php echo $person->alamat->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_alamat" class="person_alamat">
<span<?php echo $person->alamat->ViewAttributes() ?>>
<?php echo $person->alamat->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->kota->Visible) { // kota ?>
		<td data-name="kota"<?php echo $person->kota->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_kota" class="person_kota">
<span<?php echo $person->kota->ViewAttributes() ?>>
<?php echo $person->kota->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->zip->Visible) { // zip ?>
		<td data-name="zip"<?php echo $person->zip->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_zip" class="person_zip">
<span<?php echo $person->zip->ViewAttributes() ?>>
<?php echo $person->zip->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->klasifikasi_id->Visible) { // klasifikasi_id ?>
		<td data-name="klasifikasi_id"<?php echo $person->klasifikasi_id->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_klasifikasi_id" class="person_klasifikasi_id">
<span<?php echo $person->klasifikasi_id->ViewAttributes() ?>>
<?php echo $person->klasifikasi_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($person->id_FK->Visible) { // id_FK ?>
		<td data-name="id_FK"<?php echo $person->id_FK->CellAttributes() ?>>
<span id="el<?php echo $person_list->RowCnt ?>_person_id_FK" class="person_id_FK">
<span<?php echo $person->id_FK->ViewAttributes() ?>>
<?php echo $person->id_FK->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$person_list->ListOptions->Render("body", "right", $person_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($person->CurrentAction <> "gridadd")
		$person_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($person->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($person_list->Recordset)
	$person_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($person->CurrentAction <> "gridadd" && $person->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($person_list->Pager)) $person_list->Pager = new cPrevNextPager($person_list->StartRec, $person_list->DisplayRecs, $person_list->TotalRecs) ?>
<?php if ($person_list->Pager->RecordCount > 0 && $person_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($person_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $person_list->PageUrl() ?>start=<?php echo $person_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($person_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $person_list->PageUrl() ?>start=<?php echo $person_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $person_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($person_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $person_list->PageUrl() ?>start=<?php echo $person_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($person_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $person_list->PageUrl() ?>start=<?php echo $person_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $person_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $person_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $person_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $person_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($person_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($person_list->TotalRecs == 0 && $person->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($person_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fpersonlistsrch.FilterList = <?php echo $person_list->GetFilterList() ?>;
fpersonlistsrch.Init();
fpersonlist.Init();
</script>
<?php
$person_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$person_list->Page_Terminate();
?>
