<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "produkinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$produk_list = NULL; // Initialize page object first

class cproduk_list extends cproduk {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{994C6BDE-6323-4115-98D0-9A87BE039368}";

	// Table name
	var $TableName = 'produk';

	// Page object name
	var $PageObjName = 'produk_list';

	// Grid form hidden field names
	var $FormName = 'fproduklist';
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

		// Table object (produk)
		if (!isset($GLOBALS["produk"]) || get_class($GLOBALS["produk"]) == "cproduk") {
			$GLOBALS["produk"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["produk"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "produkadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "produkdelete.php";
		$this->MultiUpdateUrl = "produkupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'produk', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fproduklistsrch";

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
		$this->kelompok_id->SetVisibility();
		$this->satuan_id->SetVisibility();
		$this->satuan_id2->SetVisibility();
		$this->gudang_id->SetVisibility();
		$this->minstok->SetVisibility();
		$this->minorder->SetVisibility();
		$this->akunhpp->SetVisibility();
		$this->akunjual->SetVisibility();
		$this->akunpersediaan->SetVisibility();
		$this->akunreturjual->SetVisibility();
		$this->hargapokok->SetVisibility();
		$this->p->SetVisibility();
		$this->l->SetVisibility();
		$this->t->SetVisibility();
		$this->berat->SetVisibility();
		$this->supplier_id->SetVisibility();
		$this->waktukirim->SetVisibility();
		$this->aktif->SetVisibility();
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
		global $EW_EXPORT, $produk;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($produk);
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
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fproduklistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->kode->AdvancedSearch->ToJSON(), ","); // Field kode
		$sFilterList = ew_Concat($sFilterList, $this->nama->AdvancedSearch->ToJSON(), ","); // Field nama
		$sFilterList = ew_Concat($sFilterList, $this->kelompok_id->AdvancedSearch->ToJSON(), ","); // Field kelompok_id
		$sFilterList = ew_Concat($sFilterList, $this->satuan_id->AdvancedSearch->ToJSON(), ","); // Field satuan_id
		$sFilterList = ew_Concat($sFilterList, $this->satuan_id2->AdvancedSearch->ToJSON(), ","); // Field satuan_id2
		$sFilterList = ew_Concat($sFilterList, $this->gudang_id->AdvancedSearch->ToJSON(), ","); // Field gudang_id
		$sFilterList = ew_Concat($sFilterList, $this->minstok->AdvancedSearch->ToJSON(), ","); // Field minstok
		$sFilterList = ew_Concat($sFilterList, $this->minorder->AdvancedSearch->ToJSON(), ","); // Field minorder
		$sFilterList = ew_Concat($sFilterList, $this->akunhpp->AdvancedSearch->ToJSON(), ","); // Field akunhpp
		$sFilterList = ew_Concat($sFilterList, $this->akunjual->AdvancedSearch->ToJSON(), ","); // Field akunjual
		$sFilterList = ew_Concat($sFilterList, $this->akunpersediaan->AdvancedSearch->ToJSON(), ","); // Field akunpersediaan
		$sFilterList = ew_Concat($sFilterList, $this->akunreturjual->AdvancedSearch->ToJSON(), ","); // Field akunreturjual
		$sFilterList = ew_Concat($sFilterList, $this->hargapokok->AdvancedSearch->ToJSON(), ","); // Field hargapokok
		$sFilterList = ew_Concat($sFilterList, $this->p->AdvancedSearch->ToJSON(), ","); // Field p
		$sFilterList = ew_Concat($sFilterList, $this->l->AdvancedSearch->ToJSON(), ","); // Field l
		$sFilterList = ew_Concat($sFilterList, $this->t->AdvancedSearch->ToJSON(), ","); // Field t
		$sFilterList = ew_Concat($sFilterList, $this->berat->AdvancedSearch->ToJSON(), ","); // Field berat
		$sFilterList = ew_Concat($sFilterList, $this->supplier_id->AdvancedSearch->ToJSON(), ","); // Field supplier_id
		$sFilterList = ew_Concat($sFilterList, $this->waktukirim->AdvancedSearch->ToJSON(), ","); // Field waktukirim
		$sFilterList = ew_Concat($sFilterList, $this->aktif->AdvancedSearch->ToJSON(), ","); // Field aktif
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fproduklistsrch", $filters);

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

		// Field kelompok_id
		$this->kelompok_id->AdvancedSearch->SearchValue = @$filter["x_kelompok_id"];
		$this->kelompok_id->AdvancedSearch->SearchOperator = @$filter["z_kelompok_id"];
		$this->kelompok_id->AdvancedSearch->SearchCondition = @$filter["v_kelompok_id"];
		$this->kelompok_id->AdvancedSearch->SearchValue2 = @$filter["y_kelompok_id"];
		$this->kelompok_id->AdvancedSearch->SearchOperator2 = @$filter["w_kelompok_id"];
		$this->kelompok_id->AdvancedSearch->Save();

		// Field satuan_id
		$this->satuan_id->AdvancedSearch->SearchValue = @$filter["x_satuan_id"];
		$this->satuan_id->AdvancedSearch->SearchOperator = @$filter["z_satuan_id"];
		$this->satuan_id->AdvancedSearch->SearchCondition = @$filter["v_satuan_id"];
		$this->satuan_id->AdvancedSearch->SearchValue2 = @$filter["y_satuan_id"];
		$this->satuan_id->AdvancedSearch->SearchOperator2 = @$filter["w_satuan_id"];
		$this->satuan_id->AdvancedSearch->Save();

		// Field satuan_id2
		$this->satuan_id2->AdvancedSearch->SearchValue = @$filter["x_satuan_id2"];
		$this->satuan_id2->AdvancedSearch->SearchOperator = @$filter["z_satuan_id2"];
		$this->satuan_id2->AdvancedSearch->SearchCondition = @$filter["v_satuan_id2"];
		$this->satuan_id2->AdvancedSearch->SearchValue2 = @$filter["y_satuan_id2"];
		$this->satuan_id2->AdvancedSearch->SearchOperator2 = @$filter["w_satuan_id2"];
		$this->satuan_id2->AdvancedSearch->Save();

		// Field gudang_id
		$this->gudang_id->AdvancedSearch->SearchValue = @$filter["x_gudang_id"];
		$this->gudang_id->AdvancedSearch->SearchOperator = @$filter["z_gudang_id"];
		$this->gudang_id->AdvancedSearch->SearchCondition = @$filter["v_gudang_id"];
		$this->gudang_id->AdvancedSearch->SearchValue2 = @$filter["y_gudang_id"];
		$this->gudang_id->AdvancedSearch->SearchOperator2 = @$filter["w_gudang_id"];
		$this->gudang_id->AdvancedSearch->Save();

		// Field minstok
		$this->minstok->AdvancedSearch->SearchValue = @$filter["x_minstok"];
		$this->minstok->AdvancedSearch->SearchOperator = @$filter["z_minstok"];
		$this->minstok->AdvancedSearch->SearchCondition = @$filter["v_minstok"];
		$this->minstok->AdvancedSearch->SearchValue2 = @$filter["y_minstok"];
		$this->minstok->AdvancedSearch->SearchOperator2 = @$filter["w_minstok"];
		$this->minstok->AdvancedSearch->Save();

		// Field minorder
		$this->minorder->AdvancedSearch->SearchValue = @$filter["x_minorder"];
		$this->minorder->AdvancedSearch->SearchOperator = @$filter["z_minorder"];
		$this->minorder->AdvancedSearch->SearchCondition = @$filter["v_minorder"];
		$this->minorder->AdvancedSearch->SearchValue2 = @$filter["y_minorder"];
		$this->minorder->AdvancedSearch->SearchOperator2 = @$filter["w_minorder"];
		$this->minorder->AdvancedSearch->Save();

		// Field akunhpp
		$this->akunhpp->AdvancedSearch->SearchValue = @$filter["x_akunhpp"];
		$this->akunhpp->AdvancedSearch->SearchOperator = @$filter["z_akunhpp"];
		$this->akunhpp->AdvancedSearch->SearchCondition = @$filter["v_akunhpp"];
		$this->akunhpp->AdvancedSearch->SearchValue2 = @$filter["y_akunhpp"];
		$this->akunhpp->AdvancedSearch->SearchOperator2 = @$filter["w_akunhpp"];
		$this->akunhpp->AdvancedSearch->Save();

		// Field akunjual
		$this->akunjual->AdvancedSearch->SearchValue = @$filter["x_akunjual"];
		$this->akunjual->AdvancedSearch->SearchOperator = @$filter["z_akunjual"];
		$this->akunjual->AdvancedSearch->SearchCondition = @$filter["v_akunjual"];
		$this->akunjual->AdvancedSearch->SearchValue2 = @$filter["y_akunjual"];
		$this->akunjual->AdvancedSearch->SearchOperator2 = @$filter["w_akunjual"];
		$this->akunjual->AdvancedSearch->Save();

		// Field akunpersediaan
		$this->akunpersediaan->AdvancedSearch->SearchValue = @$filter["x_akunpersediaan"];
		$this->akunpersediaan->AdvancedSearch->SearchOperator = @$filter["z_akunpersediaan"];
		$this->akunpersediaan->AdvancedSearch->SearchCondition = @$filter["v_akunpersediaan"];
		$this->akunpersediaan->AdvancedSearch->SearchValue2 = @$filter["y_akunpersediaan"];
		$this->akunpersediaan->AdvancedSearch->SearchOperator2 = @$filter["w_akunpersediaan"];
		$this->akunpersediaan->AdvancedSearch->Save();

		// Field akunreturjual
		$this->akunreturjual->AdvancedSearch->SearchValue = @$filter["x_akunreturjual"];
		$this->akunreturjual->AdvancedSearch->SearchOperator = @$filter["z_akunreturjual"];
		$this->akunreturjual->AdvancedSearch->SearchCondition = @$filter["v_akunreturjual"];
		$this->akunreturjual->AdvancedSearch->SearchValue2 = @$filter["y_akunreturjual"];
		$this->akunreturjual->AdvancedSearch->SearchOperator2 = @$filter["w_akunreturjual"];
		$this->akunreturjual->AdvancedSearch->Save();

		// Field hargapokok
		$this->hargapokok->AdvancedSearch->SearchValue = @$filter["x_hargapokok"];
		$this->hargapokok->AdvancedSearch->SearchOperator = @$filter["z_hargapokok"];
		$this->hargapokok->AdvancedSearch->SearchCondition = @$filter["v_hargapokok"];
		$this->hargapokok->AdvancedSearch->SearchValue2 = @$filter["y_hargapokok"];
		$this->hargapokok->AdvancedSearch->SearchOperator2 = @$filter["w_hargapokok"];
		$this->hargapokok->AdvancedSearch->Save();

		// Field p
		$this->p->AdvancedSearch->SearchValue = @$filter["x_p"];
		$this->p->AdvancedSearch->SearchOperator = @$filter["z_p"];
		$this->p->AdvancedSearch->SearchCondition = @$filter["v_p"];
		$this->p->AdvancedSearch->SearchValue2 = @$filter["y_p"];
		$this->p->AdvancedSearch->SearchOperator2 = @$filter["w_p"];
		$this->p->AdvancedSearch->Save();

		// Field l
		$this->l->AdvancedSearch->SearchValue = @$filter["x_l"];
		$this->l->AdvancedSearch->SearchOperator = @$filter["z_l"];
		$this->l->AdvancedSearch->SearchCondition = @$filter["v_l"];
		$this->l->AdvancedSearch->SearchValue2 = @$filter["y_l"];
		$this->l->AdvancedSearch->SearchOperator2 = @$filter["w_l"];
		$this->l->AdvancedSearch->Save();

		// Field t
		$this->t->AdvancedSearch->SearchValue = @$filter["x_t"];
		$this->t->AdvancedSearch->SearchOperator = @$filter["z_t"];
		$this->t->AdvancedSearch->SearchCondition = @$filter["v_t"];
		$this->t->AdvancedSearch->SearchValue2 = @$filter["y_t"];
		$this->t->AdvancedSearch->SearchOperator2 = @$filter["w_t"];
		$this->t->AdvancedSearch->Save();

		// Field berat
		$this->berat->AdvancedSearch->SearchValue = @$filter["x_berat"];
		$this->berat->AdvancedSearch->SearchOperator = @$filter["z_berat"];
		$this->berat->AdvancedSearch->SearchCondition = @$filter["v_berat"];
		$this->berat->AdvancedSearch->SearchValue2 = @$filter["y_berat"];
		$this->berat->AdvancedSearch->SearchOperator2 = @$filter["w_berat"];
		$this->berat->AdvancedSearch->Save();

		// Field supplier_id
		$this->supplier_id->AdvancedSearch->SearchValue = @$filter["x_supplier_id"];
		$this->supplier_id->AdvancedSearch->SearchOperator = @$filter["z_supplier_id"];
		$this->supplier_id->AdvancedSearch->SearchCondition = @$filter["v_supplier_id"];
		$this->supplier_id->AdvancedSearch->SearchValue2 = @$filter["y_supplier_id"];
		$this->supplier_id->AdvancedSearch->SearchOperator2 = @$filter["w_supplier_id"];
		$this->supplier_id->AdvancedSearch->Save();

		// Field waktukirim
		$this->waktukirim->AdvancedSearch->SearchValue = @$filter["x_waktukirim"];
		$this->waktukirim->AdvancedSearch->SearchOperator = @$filter["z_waktukirim"];
		$this->waktukirim->AdvancedSearch->SearchCondition = @$filter["v_waktukirim"];
		$this->waktukirim->AdvancedSearch->SearchValue2 = @$filter["y_waktukirim"];
		$this->waktukirim->AdvancedSearch->SearchOperator2 = @$filter["w_waktukirim"];
		$this->waktukirim->AdvancedSearch->Save();

		// Field aktif
		$this->aktif->AdvancedSearch->SearchValue = @$filter["x_aktif"];
		$this->aktif->AdvancedSearch->SearchOperator = @$filter["z_aktif"];
		$this->aktif->AdvancedSearch->SearchCondition = @$filter["v_aktif"];
		$this->aktif->AdvancedSearch->SearchValue2 = @$filter["y_aktif"];
		$this->aktif->AdvancedSearch->SearchOperator2 = @$filter["w_aktif"];
		$this->aktif->AdvancedSearch->Save();

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
			$this->UpdateSort($this->kelompok_id, $bCtrl); // kelompok_id
			$this->UpdateSort($this->satuan_id, $bCtrl); // satuan_id
			$this->UpdateSort($this->satuan_id2, $bCtrl); // satuan_id2
			$this->UpdateSort($this->gudang_id, $bCtrl); // gudang_id
			$this->UpdateSort($this->minstok, $bCtrl); // minstok
			$this->UpdateSort($this->minorder, $bCtrl); // minorder
			$this->UpdateSort($this->akunhpp, $bCtrl); // akunhpp
			$this->UpdateSort($this->akunjual, $bCtrl); // akunjual
			$this->UpdateSort($this->akunpersediaan, $bCtrl); // akunpersediaan
			$this->UpdateSort($this->akunreturjual, $bCtrl); // akunreturjual
			$this->UpdateSort($this->hargapokok, $bCtrl); // hargapokok
			$this->UpdateSort($this->p, $bCtrl); // p
			$this->UpdateSort($this->l, $bCtrl); // l
			$this->UpdateSort($this->t, $bCtrl); // t
			$this->UpdateSort($this->berat, $bCtrl); // berat
			$this->UpdateSort($this->supplier_id, $bCtrl); // supplier_id
			$this->UpdateSort($this->waktukirim, $bCtrl); // waktukirim
			$this->UpdateSort($this->aktif, $bCtrl); // aktif
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
				$this->kelompok_id->setSort("");
				$this->satuan_id->setSort("");
				$this->satuan_id2->setSort("");
				$this->gudang_id->setSort("");
				$this->minstok->setSort("");
				$this->minorder->setSort("");
				$this->akunhpp->setSort("");
				$this->akunjual->setSort("");
				$this->akunpersediaan->setSort("");
				$this->akunreturjual->setSort("");
				$this->hargapokok->setSort("");
				$this->p->setSort("");
				$this->l->setSort("");
				$this->t->setSort("");
				$this->berat->setSort("");
				$this->supplier_id->setSort("");
				$this->waktukirim->setSort("");
				$this->aktif->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fproduklistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fproduklistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fproduklist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fproduklistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->kelompok_id->setDbValue($rs->fields('kelompok_id'));
		$this->satuan_id->setDbValue($rs->fields('satuan_id'));
		$this->satuan_id2->setDbValue($rs->fields('satuan_id2'));
		$this->gudang_id->setDbValue($rs->fields('gudang_id'));
		$this->minstok->setDbValue($rs->fields('minstok'));
		$this->minorder->setDbValue($rs->fields('minorder'));
		$this->akunhpp->setDbValue($rs->fields('akunhpp'));
		$this->akunjual->setDbValue($rs->fields('akunjual'));
		$this->akunpersediaan->setDbValue($rs->fields('akunpersediaan'));
		$this->akunreturjual->setDbValue($rs->fields('akunreturjual'));
		$this->hargapokok->setDbValue($rs->fields('hargapokok'));
		$this->p->setDbValue($rs->fields('p'));
		$this->l->setDbValue($rs->fields('l'));
		$this->t->setDbValue($rs->fields('t'));
		$this->berat->setDbValue($rs->fields('berat'));
		$this->supplier_id->setDbValue($rs->fields('supplier_id'));
		$this->waktukirim->setDbValue($rs->fields('waktukirim'));
		$this->aktif->setDbValue($rs->fields('aktif'));
		$this->id_FK->setDbValue($rs->fields('id_FK'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->kode->DbValue = $row['kode'];
		$this->nama->DbValue = $row['nama'];
		$this->kelompok_id->DbValue = $row['kelompok_id'];
		$this->satuan_id->DbValue = $row['satuan_id'];
		$this->satuan_id2->DbValue = $row['satuan_id2'];
		$this->gudang_id->DbValue = $row['gudang_id'];
		$this->minstok->DbValue = $row['minstok'];
		$this->minorder->DbValue = $row['minorder'];
		$this->akunhpp->DbValue = $row['akunhpp'];
		$this->akunjual->DbValue = $row['akunjual'];
		$this->akunpersediaan->DbValue = $row['akunpersediaan'];
		$this->akunreturjual->DbValue = $row['akunreturjual'];
		$this->hargapokok->DbValue = $row['hargapokok'];
		$this->p->DbValue = $row['p'];
		$this->l->DbValue = $row['l'];
		$this->t->DbValue = $row['t'];
		$this->berat->DbValue = $row['berat'];
		$this->supplier_id->DbValue = $row['supplier_id'];
		$this->waktukirim->DbValue = $row['waktukirim'];
		$this->aktif->DbValue = $row['aktif'];
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

		// Convert decimal values if posted back
		if ($this->minstok->FormValue == $this->minstok->CurrentValue && is_numeric(ew_StrToFloat($this->minstok->CurrentValue)))
			$this->minstok->CurrentValue = ew_StrToFloat($this->minstok->CurrentValue);

		// Convert decimal values if posted back
		if ($this->minorder->FormValue == $this->minorder->CurrentValue && is_numeric(ew_StrToFloat($this->minorder->CurrentValue)))
			$this->minorder->CurrentValue = ew_StrToFloat($this->minorder->CurrentValue);

		// Convert decimal values if posted back
		if ($this->hargapokok->FormValue == $this->hargapokok->CurrentValue && is_numeric(ew_StrToFloat($this->hargapokok->CurrentValue)))
			$this->hargapokok->CurrentValue = ew_StrToFloat($this->hargapokok->CurrentValue);

		// Convert decimal values if posted back
		if ($this->p->FormValue == $this->p->CurrentValue && is_numeric(ew_StrToFloat($this->p->CurrentValue)))
			$this->p->CurrentValue = ew_StrToFloat($this->p->CurrentValue);

		// Convert decimal values if posted back
		if ($this->l->FormValue == $this->l->CurrentValue && is_numeric(ew_StrToFloat($this->l->CurrentValue)))
			$this->l->CurrentValue = ew_StrToFloat($this->l->CurrentValue);

		// Convert decimal values if posted back
		if ($this->t->FormValue == $this->t->CurrentValue && is_numeric(ew_StrToFloat($this->t->CurrentValue)))
			$this->t->CurrentValue = ew_StrToFloat($this->t->CurrentValue);

		// Convert decimal values if posted back
		if ($this->berat->FormValue == $this->berat->CurrentValue && is_numeric(ew_StrToFloat($this->berat->CurrentValue)))
			$this->berat->CurrentValue = ew_StrToFloat($this->berat->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// kode
		// nama
		// kelompok_id
		// satuan_id
		// satuan_id2
		// gudang_id
		// minstok
		// minorder
		// akunhpp
		// akunjual
		// akunpersediaan
		// akunreturjual
		// hargapokok
		// p
		// l
		// t
		// berat
		// supplier_id
		// waktukirim
		// aktif
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

		// kelompok_id
		$this->kelompok_id->ViewValue = $this->kelompok_id->CurrentValue;
		$this->kelompok_id->ViewCustomAttributes = "";

		// satuan_id
		$this->satuan_id->ViewValue = $this->satuan_id->CurrentValue;
		$this->satuan_id->ViewCustomAttributes = "";

		// satuan_id2
		$this->satuan_id2->ViewValue = $this->satuan_id2->CurrentValue;
		$this->satuan_id2->ViewCustomAttributes = "";

		// gudang_id
		$this->gudang_id->ViewValue = $this->gudang_id->CurrentValue;
		$this->gudang_id->ViewCustomAttributes = "";

		// minstok
		$this->minstok->ViewValue = $this->minstok->CurrentValue;
		$this->minstok->ViewCustomAttributes = "";

		// minorder
		$this->minorder->ViewValue = $this->minorder->CurrentValue;
		$this->minorder->ViewCustomAttributes = "";

		// akunhpp
		$this->akunhpp->ViewValue = $this->akunhpp->CurrentValue;
		$this->akunhpp->ViewCustomAttributes = "";

		// akunjual
		$this->akunjual->ViewValue = $this->akunjual->CurrentValue;
		$this->akunjual->ViewCustomAttributes = "";

		// akunpersediaan
		$this->akunpersediaan->ViewValue = $this->akunpersediaan->CurrentValue;
		$this->akunpersediaan->ViewCustomAttributes = "";

		// akunreturjual
		$this->akunreturjual->ViewValue = $this->akunreturjual->CurrentValue;
		$this->akunreturjual->ViewCustomAttributes = "";

		// hargapokok
		$this->hargapokok->ViewValue = $this->hargapokok->CurrentValue;
		$this->hargapokok->ViewCustomAttributes = "";

		// p
		$this->p->ViewValue = $this->p->CurrentValue;
		$this->p->ViewCustomAttributes = "";

		// l
		$this->l->ViewValue = $this->l->CurrentValue;
		$this->l->ViewCustomAttributes = "";

		// t
		$this->t->ViewValue = $this->t->CurrentValue;
		$this->t->ViewCustomAttributes = "";

		// berat
		$this->berat->ViewValue = $this->berat->CurrentValue;
		$this->berat->ViewCustomAttributes = "";

		// supplier_id
		$this->supplier_id->ViewValue = $this->supplier_id->CurrentValue;
		$this->supplier_id->ViewCustomAttributes = "";

		// waktukirim
		$this->waktukirim->ViewValue = $this->waktukirim->CurrentValue;
		$this->waktukirim->ViewCustomAttributes = "";

		// aktif
		$this->aktif->ViewValue = $this->aktif->CurrentValue;
		$this->aktif->ViewCustomAttributes = "";

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

			// kelompok_id
			$this->kelompok_id->LinkCustomAttributes = "";
			$this->kelompok_id->HrefValue = "";
			$this->kelompok_id->TooltipValue = "";

			// satuan_id
			$this->satuan_id->LinkCustomAttributes = "";
			$this->satuan_id->HrefValue = "";
			$this->satuan_id->TooltipValue = "";

			// satuan_id2
			$this->satuan_id2->LinkCustomAttributes = "";
			$this->satuan_id2->HrefValue = "";
			$this->satuan_id2->TooltipValue = "";

			// gudang_id
			$this->gudang_id->LinkCustomAttributes = "";
			$this->gudang_id->HrefValue = "";
			$this->gudang_id->TooltipValue = "";

			// minstok
			$this->minstok->LinkCustomAttributes = "";
			$this->minstok->HrefValue = "";
			$this->minstok->TooltipValue = "";

			// minorder
			$this->minorder->LinkCustomAttributes = "";
			$this->minorder->HrefValue = "";
			$this->minorder->TooltipValue = "";

			// akunhpp
			$this->akunhpp->LinkCustomAttributes = "";
			$this->akunhpp->HrefValue = "";
			$this->akunhpp->TooltipValue = "";

			// akunjual
			$this->akunjual->LinkCustomAttributes = "";
			$this->akunjual->HrefValue = "";
			$this->akunjual->TooltipValue = "";

			// akunpersediaan
			$this->akunpersediaan->LinkCustomAttributes = "";
			$this->akunpersediaan->HrefValue = "";
			$this->akunpersediaan->TooltipValue = "";

			// akunreturjual
			$this->akunreturjual->LinkCustomAttributes = "";
			$this->akunreturjual->HrefValue = "";
			$this->akunreturjual->TooltipValue = "";

			// hargapokok
			$this->hargapokok->LinkCustomAttributes = "";
			$this->hargapokok->HrefValue = "";
			$this->hargapokok->TooltipValue = "";

			// p
			$this->p->LinkCustomAttributes = "";
			$this->p->HrefValue = "";
			$this->p->TooltipValue = "";

			// l
			$this->l->LinkCustomAttributes = "";
			$this->l->HrefValue = "";
			$this->l->TooltipValue = "";

			// t
			$this->t->LinkCustomAttributes = "";
			$this->t->HrefValue = "";
			$this->t->TooltipValue = "";

			// berat
			$this->berat->LinkCustomAttributes = "";
			$this->berat->HrefValue = "";
			$this->berat->TooltipValue = "";

			// supplier_id
			$this->supplier_id->LinkCustomAttributes = "";
			$this->supplier_id->HrefValue = "";
			$this->supplier_id->TooltipValue = "";

			// waktukirim
			$this->waktukirim->LinkCustomAttributes = "";
			$this->waktukirim->HrefValue = "";
			$this->waktukirim->TooltipValue = "";

			// aktif
			$this->aktif->LinkCustomAttributes = "";
			$this->aktif->HrefValue = "";
			$this->aktif->TooltipValue = "";

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
if (!isset($produk_list)) $produk_list = new cproduk_list();

// Page init
$produk_list->Page_Init();

// Page main
$produk_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$produk_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fproduklist = new ew_Form("fproduklist", "list");
fproduklist.FormKeyCountName = '<?php echo $produk_list->FormKeyCountName ?>';

// Form_CustomValidate event
fproduklist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproduklist.ValidateRequired = true;
<?php } else { ?>
fproduklist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fproduklistsrch = new ew_Form("fproduklistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($produk_list->TotalRecs > 0 && $produk_list->ExportOptions->Visible()) { ?>
<?php $produk_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($produk_list->SearchOptions->Visible()) { ?>
<?php $produk_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($produk_list->FilterOptions->Visible()) { ?>
<?php $produk_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $produk_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($produk_list->TotalRecs <= 0)
			$produk_list->TotalRecs = $produk->SelectRecordCount();
	} else {
		if (!$produk_list->Recordset && ($produk_list->Recordset = $produk_list->LoadRecordset()))
			$produk_list->TotalRecs = $produk_list->Recordset->RecordCount();
	}
	$produk_list->StartRec = 1;
	if ($produk_list->DisplayRecs <= 0 || ($produk->Export <> "" && $produk->ExportAll)) // Display all records
		$produk_list->DisplayRecs = $produk_list->TotalRecs;
	if (!($produk->Export <> "" && $produk->ExportAll))
		$produk_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$produk_list->Recordset = $produk_list->LoadRecordset($produk_list->StartRec-1, $produk_list->DisplayRecs);

	// Set no record found message
	if ($produk->CurrentAction == "" && $produk_list->TotalRecs == 0) {
		if ($produk_list->SearchWhere == "0=101")
			$produk_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$produk_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$produk_list->RenderOtherOptions();
?>
<?php if ($produk->Export == "" && $produk->CurrentAction == "") { ?>
<form name="fproduklistsrch" id="fproduklistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($produk_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fproduklistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="produk">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($produk_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($produk_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $produk_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($produk_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($produk_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($produk_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($produk_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $produk_list->ShowPageHeader(); ?>
<?php
$produk_list->ShowMessage();
?>
<?php if ($produk_list->TotalRecs > 0 || $produk->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid produk">
<form name="fproduklist" id="fproduklist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($produk_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $produk_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="produk">
<div id="gmp_produk" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($produk_list->TotalRecs > 0 || $produk->CurrentAction == "gridedit") { ?>
<table id="tbl_produklist" class="table ewTable">
<?php echo $produk->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$produk_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$produk_list->RenderListOptions();

// Render list options (header, left)
$produk_list->ListOptions->Render("header", "left");
?>
<?php if ($produk->id->Visible) { // id ?>
	<?php if ($produk->SortUrl($produk->id) == "") { ?>
		<th data-name="id"><div id="elh_produk_id" class="produk_id"><div class="ewTableHeaderCaption"><?php echo $produk->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->id) ?>',2);"><div id="elh_produk_id" class="produk_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->kode->Visible) { // kode ?>
	<?php if ($produk->SortUrl($produk->kode) == "") { ?>
		<th data-name="kode"><div id="elh_produk_kode" class="produk_kode"><div class="ewTableHeaderCaption"><?php echo $produk->kode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->kode) ?>',2);"><div id="elh_produk_kode" class="produk_kode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->kode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($produk->kode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->kode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->nama->Visible) { // nama ?>
	<?php if ($produk->SortUrl($produk->nama) == "") { ?>
		<th data-name="nama"><div id="elh_produk_nama" class="produk_nama"><div class="ewTableHeaderCaption"><?php echo $produk->nama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nama"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->nama) ?>',2);"><div id="elh_produk_nama" class="produk_nama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->nama->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($produk->nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->kelompok_id->Visible) { // kelompok_id ?>
	<?php if ($produk->SortUrl($produk->kelompok_id) == "") { ?>
		<th data-name="kelompok_id"><div id="elh_produk_kelompok_id" class="produk_kelompok_id"><div class="ewTableHeaderCaption"><?php echo $produk->kelompok_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kelompok_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->kelompok_id) ?>',2);"><div id="elh_produk_kelompok_id" class="produk_kelompok_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->kelompok_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->kelompok_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->kelompok_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->satuan_id->Visible) { // satuan_id ?>
	<?php if ($produk->SortUrl($produk->satuan_id) == "") { ?>
		<th data-name="satuan_id"><div id="elh_produk_satuan_id" class="produk_satuan_id"><div class="ewTableHeaderCaption"><?php echo $produk->satuan_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="satuan_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->satuan_id) ?>',2);"><div id="elh_produk_satuan_id" class="produk_satuan_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->satuan_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->satuan_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->satuan_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->satuan_id2->Visible) { // satuan_id2 ?>
	<?php if ($produk->SortUrl($produk->satuan_id2) == "") { ?>
		<th data-name="satuan_id2"><div id="elh_produk_satuan_id2" class="produk_satuan_id2"><div class="ewTableHeaderCaption"><?php echo $produk->satuan_id2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="satuan_id2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->satuan_id2) ?>',2);"><div id="elh_produk_satuan_id2" class="produk_satuan_id2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->satuan_id2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->satuan_id2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->satuan_id2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->gudang_id->Visible) { // gudang_id ?>
	<?php if ($produk->SortUrl($produk->gudang_id) == "") { ?>
		<th data-name="gudang_id"><div id="elh_produk_gudang_id" class="produk_gudang_id"><div class="ewTableHeaderCaption"><?php echo $produk->gudang_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gudang_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->gudang_id) ?>',2);"><div id="elh_produk_gudang_id" class="produk_gudang_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->gudang_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->gudang_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->gudang_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->minstok->Visible) { // minstok ?>
	<?php if ($produk->SortUrl($produk->minstok) == "") { ?>
		<th data-name="minstok"><div id="elh_produk_minstok" class="produk_minstok"><div class="ewTableHeaderCaption"><?php echo $produk->minstok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="minstok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->minstok) ?>',2);"><div id="elh_produk_minstok" class="produk_minstok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->minstok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->minstok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->minstok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->minorder->Visible) { // minorder ?>
	<?php if ($produk->SortUrl($produk->minorder) == "") { ?>
		<th data-name="minorder"><div id="elh_produk_minorder" class="produk_minorder"><div class="ewTableHeaderCaption"><?php echo $produk->minorder->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="minorder"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->minorder) ?>',2);"><div id="elh_produk_minorder" class="produk_minorder">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->minorder->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->minorder->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->minorder->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->akunhpp->Visible) { // akunhpp ?>
	<?php if ($produk->SortUrl($produk->akunhpp) == "") { ?>
		<th data-name="akunhpp"><div id="elh_produk_akunhpp" class="produk_akunhpp"><div class="ewTableHeaderCaption"><?php echo $produk->akunhpp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="akunhpp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->akunhpp) ?>',2);"><div id="elh_produk_akunhpp" class="produk_akunhpp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->akunhpp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->akunhpp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->akunhpp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->akunjual->Visible) { // akunjual ?>
	<?php if ($produk->SortUrl($produk->akunjual) == "") { ?>
		<th data-name="akunjual"><div id="elh_produk_akunjual" class="produk_akunjual"><div class="ewTableHeaderCaption"><?php echo $produk->akunjual->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="akunjual"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->akunjual) ?>',2);"><div id="elh_produk_akunjual" class="produk_akunjual">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->akunjual->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->akunjual->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->akunjual->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->akunpersediaan->Visible) { // akunpersediaan ?>
	<?php if ($produk->SortUrl($produk->akunpersediaan) == "") { ?>
		<th data-name="akunpersediaan"><div id="elh_produk_akunpersediaan" class="produk_akunpersediaan"><div class="ewTableHeaderCaption"><?php echo $produk->akunpersediaan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="akunpersediaan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->akunpersediaan) ?>',2);"><div id="elh_produk_akunpersediaan" class="produk_akunpersediaan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->akunpersediaan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->akunpersediaan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->akunpersediaan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->akunreturjual->Visible) { // akunreturjual ?>
	<?php if ($produk->SortUrl($produk->akunreturjual) == "") { ?>
		<th data-name="akunreturjual"><div id="elh_produk_akunreturjual" class="produk_akunreturjual"><div class="ewTableHeaderCaption"><?php echo $produk->akunreturjual->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="akunreturjual"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->akunreturjual) ?>',2);"><div id="elh_produk_akunreturjual" class="produk_akunreturjual">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->akunreturjual->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->akunreturjual->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->akunreturjual->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->hargapokok->Visible) { // hargapokok ?>
	<?php if ($produk->SortUrl($produk->hargapokok) == "") { ?>
		<th data-name="hargapokok"><div id="elh_produk_hargapokok" class="produk_hargapokok"><div class="ewTableHeaderCaption"><?php echo $produk->hargapokok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hargapokok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->hargapokok) ?>',2);"><div id="elh_produk_hargapokok" class="produk_hargapokok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->hargapokok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->hargapokok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->hargapokok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->p->Visible) { // p ?>
	<?php if ($produk->SortUrl($produk->p) == "") { ?>
		<th data-name="p"><div id="elh_produk_p" class="produk_p"><div class="ewTableHeaderCaption"><?php echo $produk->p->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="p"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->p) ?>',2);"><div id="elh_produk_p" class="produk_p">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->p->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->p->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->p->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->l->Visible) { // l ?>
	<?php if ($produk->SortUrl($produk->l) == "") { ?>
		<th data-name="l"><div id="elh_produk_l" class="produk_l"><div class="ewTableHeaderCaption"><?php echo $produk->l->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="l"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->l) ?>',2);"><div id="elh_produk_l" class="produk_l">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->l->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->l->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->l->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->t->Visible) { // t ?>
	<?php if ($produk->SortUrl($produk->t) == "") { ?>
		<th data-name="t"><div id="elh_produk_t" class="produk_t"><div class="ewTableHeaderCaption"><?php echo $produk->t->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="t"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->t) ?>',2);"><div id="elh_produk_t" class="produk_t">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->t->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->t->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->t->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->berat->Visible) { // berat ?>
	<?php if ($produk->SortUrl($produk->berat) == "") { ?>
		<th data-name="berat"><div id="elh_produk_berat" class="produk_berat"><div class="ewTableHeaderCaption"><?php echo $produk->berat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="berat"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->berat) ?>',2);"><div id="elh_produk_berat" class="produk_berat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->berat->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->berat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->berat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->supplier_id->Visible) { // supplier_id ?>
	<?php if ($produk->SortUrl($produk->supplier_id) == "") { ?>
		<th data-name="supplier_id"><div id="elh_produk_supplier_id" class="produk_supplier_id"><div class="ewTableHeaderCaption"><?php echo $produk->supplier_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="supplier_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->supplier_id) ?>',2);"><div id="elh_produk_supplier_id" class="produk_supplier_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->supplier_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->supplier_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->supplier_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->waktukirim->Visible) { // waktukirim ?>
	<?php if ($produk->SortUrl($produk->waktukirim) == "") { ?>
		<th data-name="waktukirim"><div id="elh_produk_waktukirim" class="produk_waktukirim"><div class="ewTableHeaderCaption"><?php echo $produk->waktukirim->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="waktukirim"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->waktukirim) ?>',2);"><div id="elh_produk_waktukirim" class="produk_waktukirim">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->waktukirim->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->waktukirim->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->waktukirim->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->aktif->Visible) { // aktif ?>
	<?php if ($produk->SortUrl($produk->aktif) == "") { ?>
		<th data-name="aktif"><div id="elh_produk_aktif" class="produk_aktif"><div class="ewTableHeaderCaption"><?php echo $produk->aktif->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="aktif"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->aktif) ?>',2);"><div id="elh_produk_aktif" class="produk_aktif">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->aktif->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->aktif->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->aktif->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($produk->id_FK->Visible) { // id_FK ?>
	<?php if ($produk->SortUrl($produk->id_FK) == "") { ?>
		<th data-name="id_FK"><div id="elh_produk_id_FK" class="produk_id_FK"><div class="ewTableHeaderCaption"><?php echo $produk->id_FK->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_FK"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $produk->SortUrl($produk->id_FK) ?>',2);"><div id="elh_produk_id_FK" class="produk_id_FK">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $produk->id_FK->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($produk->id_FK->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($produk->id_FK->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$produk_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($produk->ExportAll && $produk->Export <> "") {
	$produk_list->StopRec = $produk_list->TotalRecs;
} else {

	// Set the last record to display
	if ($produk_list->TotalRecs > $produk_list->StartRec + $produk_list->DisplayRecs - 1)
		$produk_list->StopRec = $produk_list->StartRec + $produk_list->DisplayRecs - 1;
	else
		$produk_list->StopRec = $produk_list->TotalRecs;
}
$produk_list->RecCnt = $produk_list->StartRec - 1;
if ($produk_list->Recordset && !$produk_list->Recordset->EOF) {
	$produk_list->Recordset->MoveFirst();
	$bSelectLimit = $produk_list->UseSelectLimit;
	if (!$bSelectLimit && $produk_list->StartRec > 1)
		$produk_list->Recordset->Move($produk_list->StartRec - 1);
} elseif (!$produk->AllowAddDeleteRow && $produk_list->StopRec == 0) {
	$produk_list->StopRec = $produk->GridAddRowCount;
}

// Initialize aggregate
$produk->RowType = EW_ROWTYPE_AGGREGATEINIT;
$produk->ResetAttrs();
$produk_list->RenderRow();
while ($produk_list->RecCnt < $produk_list->StopRec) {
	$produk_list->RecCnt++;
	if (intval($produk_list->RecCnt) >= intval($produk_list->StartRec)) {
		$produk_list->RowCnt++;

		// Set up key count
		$produk_list->KeyCount = $produk_list->RowIndex;

		// Init row class and style
		$produk->ResetAttrs();
		$produk->CssClass = "";
		if ($produk->CurrentAction == "gridadd") {
		} else {
			$produk_list->LoadRowValues($produk_list->Recordset); // Load row values
		}
		$produk->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$produk->RowAttrs = array_merge($produk->RowAttrs, array('data-rowindex'=>$produk_list->RowCnt, 'id'=>'r' . $produk_list->RowCnt . '_produk', 'data-rowtype'=>$produk->RowType));

		// Render row
		$produk_list->RenderRow();

		// Render list options
		$produk_list->RenderListOptions();
?>
	<tr<?php echo $produk->RowAttributes() ?>>
<?php

// Render list options (body, left)
$produk_list->ListOptions->Render("body", "left", $produk_list->RowCnt);
?>
	<?php if ($produk->id->Visible) { // id ?>
		<td data-name="id"<?php echo $produk->id->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_id" class="produk_id">
<span<?php echo $produk->id->ViewAttributes() ?>>
<?php echo $produk->id->ListViewValue() ?></span>
</span>
<a id="<?php echo $produk_list->PageObjName . "_row_" . $produk_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($produk->kode->Visible) { // kode ?>
		<td data-name="kode"<?php echo $produk->kode->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_kode" class="produk_kode">
<span<?php echo $produk->kode->ViewAttributes() ?>>
<?php echo $produk->kode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->nama->Visible) { // nama ?>
		<td data-name="nama"<?php echo $produk->nama->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_nama" class="produk_nama">
<span<?php echo $produk->nama->ViewAttributes() ?>>
<?php echo $produk->nama->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->kelompok_id->Visible) { // kelompok_id ?>
		<td data-name="kelompok_id"<?php echo $produk->kelompok_id->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_kelompok_id" class="produk_kelompok_id">
<span<?php echo $produk->kelompok_id->ViewAttributes() ?>>
<?php echo $produk->kelompok_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->satuan_id->Visible) { // satuan_id ?>
		<td data-name="satuan_id"<?php echo $produk->satuan_id->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_satuan_id" class="produk_satuan_id">
<span<?php echo $produk->satuan_id->ViewAttributes() ?>>
<?php echo $produk->satuan_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->satuan_id2->Visible) { // satuan_id2 ?>
		<td data-name="satuan_id2"<?php echo $produk->satuan_id2->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_satuan_id2" class="produk_satuan_id2">
<span<?php echo $produk->satuan_id2->ViewAttributes() ?>>
<?php echo $produk->satuan_id2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->gudang_id->Visible) { // gudang_id ?>
		<td data-name="gudang_id"<?php echo $produk->gudang_id->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_gudang_id" class="produk_gudang_id">
<span<?php echo $produk->gudang_id->ViewAttributes() ?>>
<?php echo $produk->gudang_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->minstok->Visible) { // minstok ?>
		<td data-name="minstok"<?php echo $produk->minstok->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_minstok" class="produk_minstok">
<span<?php echo $produk->minstok->ViewAttributes() ?>>
<?php echo $produk->minstok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->minorder->Visible) { // minorder ?>
		<td data-name="minorder"<?php echo $produk->minorder->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_minorder" class="produk_minorder">
<span<?php echo $produk->minorder->ViewAttributes() ?>>
<?php echo $produk->minorder->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->akunhpp->Visible) { // akunhpp ?>
		<td data-name="akunhpp"<?php echo $produk->akunhpp->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_akunhpp" class="produk_akunhpp">
<span<?php echo $produk->akunhpp->ViewAttributes() ?>>
<?php echo $produk->akunhpp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->akunjual->Visible) { // akunjual ?>
		<td data-name="akunjual"<?php echo $produk->akunjual->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_akunjual" class="produk_akunjual">
<span<?php echo $produk->akunjual->ViewAttributes() ?>>
<?php echo $produk->akunjual->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->akunpersediaan->Visible) { // akunpersediaan ?>
		<td data-name="akunpersediaan"<?php echo $produk->akunpersediaan->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_akunpersediaan" class="produk_akunpersediaan">
<span<?php echo $produk->akunpersediaan->ViewAttributes() ?>>
<?php echo $produk->akunpersediaan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->akunreturjual->Visible) { // akunreturjual ?>
		<td data-name="akunreturjual"<?php echo $produk->akunreturjual->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_akunreturjual" class="produk_akunreturjual">
<span<?php echo $produk->akunreturjual->ViewAttributes() ?>>
<?php echo $produk->akunreturjual->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->hargapokok->Visible) { // hargapokok ?>
		<td data-name="hargapokok"<?php echo $produk->hargapokok->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_hargapokok" class="produk_hargapokok">
<span<?php echo $produk->hargapokok->ViewAttributes() ?>>
<?php echo $produk->hargapokok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->p->Visible) { // p ?>
		<td data-name="p"<?php echo $produk->p->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_p" class="produk_p">
<span<?php echo $produk->p->ViewAttributes() ?>>
<?php echo $produk->p->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->l->Visible) { // l ?>
		<td data-name="l"<?php echo $produk->l->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_l" class="produk_l">
<span<?php echo $produk->l->ViewAttributes() ?>>
<?php echo $produk->l->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->t->Visible) { // t ?>
		<td data-name="t"<?php echo $produk->t->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_t" class="produk_t">
<span<?php echo $produk->t->ViewAttributes() ?>>
<?php echo $produk->t->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->berat->Visible) { // berat ?>
		<td data-name="berat"<?php echo $produk->berat->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_berat" class="produk_berat">
<span<?php echo $produk->berat->ViewAttributes() ?>>
<?php echo $produk->berat->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->supplier_id->Visible) { // supplier_id ?>
		<td data-name="supplier_id"<?php echo $produk->supplier_id->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_supplier_id" class="produk_supplier_id">
<span<?php echo $produk->supplier_id->ViewAttributes() ?>>
<?php echo $produk->supplier_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->waktukirim->Visible) { // waktukirim ?>
		<td data-name="waktukirim"<?php echo $produk->waktukirim->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_waktukirim" class="produk_waktukirim">
<span<?php echo $produk->waktukirim->ViewAttributes() ?>>
<?php echo $produk->waktukirim->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->aktif->Visible) { // aktif ?>
		<td data-name="aktif"<?php echo $produk->aktif->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_aktif" class="produk_aktif">
<span<?php echo $produk->aktif->ViewAttributes() ?>>
<?php echo $produk->aktif->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($produk->id_FK->Visible) { // id_FK ?>
		<td data-name="id_FK"<?php echo $produk->id_FK->CellAttributes() ?>>
<span id="el<?php echo $produk_list->RowCnt ?>_produk_id_FK" class="produk_id_FK">
<span<?php echo $produk->id_FK->ViewAttributes() ?>>
<?php echo $produk->id_FK->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$produk_list->ListOptions->Render("body", "right", $produk_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($produk->CurrentAction <> "gridadd")
		$produk_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($produk->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($produk_list->Recordset)
	$produk_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($produk->CurrentAction <> "gridadd" && $produk->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($produk_list->Pager)) $produk_list->Pager = new cPrevNextPager($produk_list->StartRec, $produk_list->DisplayRecs, $produk_list->TotalRecs) ?>
<?php if ($produk_list->Pager->RecordCount > 0 && $produk_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($produk_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $produk_list->PageUrl() ?>start=<?php echo $produk_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($produk_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $produk_list->PageUrl() ?>start=<?php echo $produk_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $produk_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($produk_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $produk_list->PageUrl() ?>start=<?php echo $produk_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($produk_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $produk_list->PageUrl() ?>start=<?php echo $produk_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $produk_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $produk_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $produk_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $produk_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($produk_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($produk_list->TotalRecs == 0 && $produk->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($produk_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fproduklistsrch.FilterList = <?php echo $produk_list->GetFilterList() ?>;
fproduklistsrch.Init();
fproduklist.Init();
</script>
<?php
$produk_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$produk_list->Page_Terminate();
?>
