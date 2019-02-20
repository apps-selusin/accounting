<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "jurnalinfo.php" ?>
<?php include_once "jurnaldgridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$jurnal_edit = NULL; // Initialize page object first

class cjurnal_edit extends cjurnal {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{994C6BDE-6323-4115-98D0-9A87BE039368}";

	// Table name
	var $TableName = 'jurnal';

	// Page object name
	var $PageObjName = 'jurnal_edit';

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

		// Table object (jurnal)
		if (!isset($GLOBALS["jurnal"]) || get_class($GLOBALS["jurnal"]) == "cjurnal") {
			$GLOBALS["jurnal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jurnal"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jurnal', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->tipejurnal_id->SetVisibility();
		$this->period_id->SetVisibility();
		$this->createon->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->person_id->SetVisibility();
		$this->nomer->SetVisibility();

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

			// Process auto fill for detail table 'jurnald'
			if (@$_POST["grid"] == "fjurnaldgrid") {
				if (!isset($GLOBALS["jurnald_grid"])) $GLOBALS["jurnald_grid"] = new cjurnald_grid;
				$GLOBALS["jurnald_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $jurnal;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($jurnal);
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
			$this->RecKey["id"] = $this->id->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("jurnallist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->id->CurrentValue) == strval($this->Recordset->fields('id'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("jurnallist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "jurnallist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->tipejurnal_id->FldIsDetailKey) {
			$this->tipejurnal_id->setFormValue($objForm->GetValue("x_tipejurnal_id"));
		}
		if (!$this->period_id->FldIsDetailKey) {
			$this->period_id->setFormValue($objForm->GetValue("x_period_id"));
		}
		if (!$this->createon->FldIsDetailKey) {
			$this->createon->setFormValue($objForm->GetValue("x_createon"));
			$this->createon->CurrentValue = ew_UnFormatDateTime($this->createon->CurrentValue, 0);
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
		if (!$this->person_id->FldIsDetailKey) {
			$this->person_id->setFormValue($objForm->GetValue("x_person_id"));
		}
		if (!$this->nomer->FldIsDetailKey) {
			$this->nomer->setFormValue($objForm->GetValue("x_nomer"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->tipejurnal_id->CurrentValue = $this->tipejurnal_id->FormValue;
		$this->period_id->CurrentValue = $this->period_id->FormValue;
		$this->createon->CurrentValue = $this->createon->FormValue;
		$this->createon->CurrentValue = ew_UnFormatDateTime($this->createon->CurrentValue, 0);
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
		$this->person_id->CurrentValue = $this->person_id->FormValue;
		$this->nomer->CurrentValue = $this->nomer->FormValue;
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
		$this->tipejurnal_id->setDbValue($rs->fields('tipejurnal_id'));
		$this->period_id->setDbValue($rs->fields('period_id'));
		$this->createon->setDbValue($rs->fields('createon'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->person_id->setDbValue($rs->fields('person_id'));
		$this->nomer->setDbValue($rs->fields('nomer'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->tipejurnal_id->DbValue = $row['tipejurnal_id'];
		$this->period_id->DbValue = $row['period_id'];
		$this->createon->DbValue = $row['createon'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->person_id->DbValue = $row['person_id'];
		$this->nomer->DbValue = $row['nomer'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// tipejurnal_id
		// period_id
		// createon
		// keterangan
		// person_id
		// nomer

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// tipejurnal_id
		if (strval($this->tipejurnal_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->tipejurnal_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipejurnal`";
		$sWhereWrk = "";
		$this->tipejurnal_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tipejurnal_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tipejurnal_id->ViewValue = $this->tipejurnal_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tipejurnal_id->ViewValue = $this->tipejurnal_id->CurrentValue;
			}
		} else {
			$this->tipejurnal_id->ViewValue = NULL;
		}
		$this->tipejurnal_id->ViewCustomAttributes = "";

		// period_id
		if (strval($this->period_id->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->period_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `start` AS `DispFld`, `end` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `periode`";
		$sWhereWrk = "";
		$this->period_id->LookupFilters = array("df1" => "7", "df2" => "7");
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->period_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_FormatDateTime($rswrk->fields('DispFld'), 7);
				$arwrk[2] = ew_FormatDateTime($rswrk->fields('Disp2Fld'), 7);
				$this->period_id->ViewValue = $this->period_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->period_id->ViewValue = $this->period_id->CurrentValue;
			}
		} else {
			$this->period_id->ViewValue = NULL;
		}
		$this->period_id->ViewCustomAttributes = "";

		// createon
		$this->createon->ViewValue = $this->createon->CurrentValue;
		$this->createon->ViewValue = ew_FormatDateTime($this->createon->ViewValue, 0);
		$this->createon->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// person_id
		$this->person_id->ViewValue = $this->person_id->CurrentValue;
		$this->person_id->ViewCustomAttributes = "";

		// nomer
		$this->nomer->ViewValue = $this->nomer->CurrentValue;
		$this->nomer->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// tipejurnal_id
			$this->tipejurnal_id->LinkCustomAttributes = "";
			$this->tipejurnal_id->HrefValue = "";
			$this->tipejurnal_id->TooltipValue = "";

			// period_id
			$this->period_id->LinkCustomAttributes = "";
			$this->period_id->HrefValue = "";
			$this->period_id->TooltipValue = "";

			// createon
			$this->createon->LinkCustomAttributes = "";
			$this->createon->HrefValue = "";
			$this->createon->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

			// person_id
			$this->person_id->LinkCustomAttributes = "";
			$this->person_id->HrefValue = "";
			$this->person_id->TooltipValue = "";

			// nomer
			$this->nomer->LinkCustomAttributes = "";
			$this->nomer->HrefValue = "";
			$this->nomer->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// tipejurnal_id
			$this->tipejurnal_id->EditAttrs["class"] = "form-control";
			$this->tipejurnal_id->EditCustomAttributes = "";
			if (trim(strval($this->tipejurnal_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->tipejurnal_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tipejurnal`";
			$sWhereWrk = "";
			$this->tipejurnal_id->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->tipejurnal_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->tipejurnal_id->EditValue = $arwrk;

			// period_id
			$this->period_id->EditAttrs["class"] = "form-control";
			$this->period_id->EditCustomAttributes = "";
			if (trim(strval($this->period_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->period_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `start` AS `DispFld`, `end` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `periode`";
			$sWhereWrk = "";
			$this->period_id->LookupFilters = array("df1" => "7", "df2" => "7");
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->period_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][1] = ew_FormatDateTime($arwrk[$rowcntwrk][1], 7);
				$arwrk[$rowcntwrk][2] = ew_FormatDateTime($arwrk[$rowcntwrk][2], 7);
			}
			$this->period_id->EditValue = $arwrk;

			// createon
			$this->createon->EditAttrs["class"] = "form-control";
			$this->createon->EditCustomAttributes = "";
			$this->createon->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->createon->CurrentValue, 8));
			$this->createon->PlaceHolder = ew_RemoveHtml($this->createon->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// person_id
			$this->person_id->EditAttrs["class"] = "form-control";
			$this->person_id->EditCustomAttributes = "";
			$this->person_id->EditValue = ew_HtmlEncode($this->person_id->CurrentValue);
			$this->person_id->PlaceHolder = ew_RemoveHtml($this->person_id->FldCaption());

			// nomer
			$this->nomer->EditAttrs["class"] = "form-control";
			$this->nomer->EditCustomAttributes = "";
			$this->nomer->EditValue = ew_HtmlEncode($this->nomer->CurrentValue);
			$this->nomer->PlaceHolder = ew_RemoveHtml($this->nomer->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// tipejurnal_id
			$this->tipejurnal_id->LinkCustomAttributes = "";
			$this->tipejurnal_id->HrefValue = "";

			// period_id
			$this->period_id->LinkCustomAttributes = "";
			$this->period_id->HrefValue = "";

			// createon
			$this->createon->LinkCustomAttributes = "";
			$this->createon->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";

			// person_id
			$this->person_id->LinkCustomAttributes = "";
			$this->person_id->HrefValue = "";

			// nomer
			$this->nomer->LinkCustomAttributes = "";
			$this->nomer->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckDateDef($this->createon->FormValue)) {
			ew_AddMessage($gsFormError, $this->createon->FldErrMsg());
		}
		if (!ew_CheckInteger($this->person_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->person_id->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("jurnald", $DetailTblVar) && $GLOBALS["jurnald"]->DetailEdit) {
			if (!isset($GLOBALS["jurnald_grid"])) $GLOBALS["jurnald_grid"] = new cjurnald_grid(); // get detail page object
			$GLOBALS["jurnald_grid"]->ValidateGridForm();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// tipejurnal_id
			$this->tipejurnal_id->SetDbValueDef($rsnew, $this->tipejurnal_id->CurrentValue, NULL, $this->tipejurnal_id->ReadOnly);

			// period_id
			$this->period_id->SetDbValueDef($rsnew, $this->period_id->CurrentValue, NULL, $this->period_id->ReadOnly);

			// createon
			$this->createon->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->createon->CurrentValue, 0), NULL, $this->createon->ReadOnly);

			// keterangan
			$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, NULL, $this->keterangan->ReadOnly);

			// person_id
			$this->person_id->SetDbValueDef($rsnew, $this->person_id->CurrentValue, NULL, $this->person_id->ReadOnly);

			// nomer
			$this->nomer->SetDbValueDef($rsnew, $this->nomer->CurrentValue, NULL, $this->nomer->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("jurnald", $DetailTblVar) && $GLOBALS["jurnald"]->DetailEdit) {
						if (!isset($GLOBALS["jurnald_grid"])) $GLOBALS["jurnald_grid"] = new cjurnald_grid(); // Get detail page object
						$EditRow = $GLOBALS["jurnald_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("jurnald", $DetailTblVar)) {
				if (!isset($GLOBALS["jurnald_grid"]))
					$GLOBALS["jurnald_grid"] = new cjurnald_grid;
				if ($GLOBALS["jurnald_grid"]->DetailEdit) {
					$GLOBALS["jurnald_grid"]->CurrentMode = "edit";
					$GLOBALS["jurnald_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["jurnald_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["jurnald_grid"]->setStartRecordNumber(1);
					$GLOBALS["jurnald_grid"]->jurnal_id->FldIsDetailKey = TRUE;
					$GLOBALS["jurnald_grid"]->jurnal_id->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["jurnald_grid"]->jurnal_id->setSessionValue($GLOBALS["jurnald_grid"]->jurnal_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("jurnallist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_tipejurnal_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tipejurnal`";
			$sWhereWrk = "";
			$this->tipejurnal_id->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->tipejurnal_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_period_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `start` AS `DispFld`, `end` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `periode`";
			$sWhereWrk = "";
			$this->period_id->LookupFilters = array("df1" => "7", "df2" => "7");
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->period_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($jurnal_edit)) $jurnal_edit = new cjurnal_edit();

// Page init
$jurnal_edit->Page_Init();

// Page main
$jurnal_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jurnal_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fjurnaledit = new ew_Form("fjurnaledit", "edit");

// Validate form
fjurnaledit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_createon");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jurnal->createon->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_person_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jurnal->person_id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fjurnaledit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjurnaledit.ValidateRequired = true;
<?php } else { ?>
fjurnaledit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fjurnaledit.Lists["x_tipejurnal_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"tipejurnal"};
fjurnaledit.Lists["x_period_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_start","x_end","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"periode"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$jurnal_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $jurnal_edit->ShowPageHeader(); ?>
<?php
$jurnal_edit->ShowMessage();
?>
<form name="fjurnaledit" id="fjurnaledit" class="<?php echo $jurnal_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jurnal_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jurnal_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jurnal">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($jurnal_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($jurnal->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_jurnal_id" class="col-sm-2 control-label ewLabel"><?php echo $jurnal->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jurnal->id->CellAttributes() ?>>
<span id="el_jurnal_id">
<span<?php echo $jurnal->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jurnal->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="jurnal" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($jurnal->id->CurrentValue) ?>">
<?php echo $jurnal->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jurnal->tipejurnal_id->Visible) { // tipejurnal_id ?>
	<div id="r_tipejurnal_id" class="form-group">
		<label id="elh_jurnal_tipejurnal_id" for="x_tipejurnal_id" class="col-sm-2 control-label ewLabel"><?php echo $jurnal->tipejurnal_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jurnal->tipejurnal_id->CellAttributes() ?>>
<span id="el_jurnal_tipejurnal_id">
<select data-table="jurnal" data-field="x_tipejurnal_id" data-value-separator="<?php echo $jurnal->tipejurnal_id->DisplayValueSeparatorAttribute() ?>" id="x_tipejurnal_id" name="x_tipejurnal_id"<?php echo $jurnal->tipejurnal_id->EditAttributes() ?>>
<?php echo $jurnal->tipejurnal_id->SelectOptionListHtml("x_tipejurnal_id") ?>
</select>
<input type="hidden" name="s_x_tipejurnal_id" id="s_x_tipejurnal_id" value="<?php echo $jurnal->tipejurnal_id->LookupFilterQuery() ?>">
</span>
<?php echo $jurnal->tipejurnal_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jurnal->period_id->Visible) { // period_id ?>
	<div id="r_period_id" class="form-group">
		<label id="elh_jurnal_period_id" for="x_period_id" class="col-sm-2 control-label ewLabel"><?php echo $jurnal->period_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jurnal->period_id->CellAttributes() ?>>
<span id="el_jurnal_period_id">
<select data-table="jurnal" data-field="x_period_id" data-value-separator="<?php echo $jurnal->period_id->DisplayValueSeparatorAttribute() ?>" id="x_period_id" name="x_period_id"<?php echo $jurnal->period_id->EditAttributes() ?>>
<?php echo $jurnal->period_id->SelectOptionListHtml("x_period_id") ?>
</select>
<input type="hidden" name="s_x_period_id" id="s_x_period_id" value="<?php echo $jurnal->period_id->LookupFilterQuery() ?>">
</span>
<?php echo $jurnal->period_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jurnal->createon->Visible) { // createon ?>
	<div id="r_createon" class="form-group">
		<label id="elh_jurnal_createon" for="x_createon" class="col-sm-2 control-label ewLabel"><?php echo $jurnal->createon->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jurnal->createon->CellAttributes() ?>>
<span id="el_jurnal_createon">
<input type="text" data-table="jurnal" data-field="x_createon" name="x_createon" id="x_createon" placeholder="<?php echo ew_HtmlEncode($jurnal->createon->getPlaceHolder()) ?>" value="<?php echo $jurnal->createon->EditValue ?>"<?php echo $jurnal->createon->EditAttributes() ?>>
</span>
<?php echo $jurnal->createon->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jurnal->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_jurnal_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $jurnal->keterangan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jurnal->keterangan->CellAttributes() ?>>
<span id="el_jurnal_keterangan">
<input type="text" data-table="jurnal" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($jurnal->keterangan->getPlaceHolder()) ?>" value="<?php echo $jurnal->keterangan->EditValue ?>"<?php echo $jurnal->keterangan->EditAttributes() ?>>
</span>
<?php echo $jurnal->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jurnal->person_id->Visible) { // person_id ?>
	<div id="r_person_id" class="form-group">
		<label id="elh_jurnal_person_id" for="x_person_id" class="col-sm-2 control-label ewLabel"><?php echo $jurnal->person_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jurnal->person_id->CellAttributes() ?>>
<span id="el_jurnal_person_id">
<input type="text" data-table="jurnal" data-field="x_person_id" name="x_person_id" id="x_person_id" size="30" placeholder="<?php echo ew_HtmlEncode($jurnal->person_id->getPlaceHolder()) ?>" value="<?php echo $jurnal->person_id->EditValue ?>"<?php echo $jurnal->person_id->EditAttributes() ?>>
</span>
<?php echo $jurnal->person_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jurnal->nomer->Visible) { // nomer ?>
	<div id="r_nomer" class="form-group">
		<label id="elh_jurnal_nomer" for="x_nomer" class="col-sm-2 control-label ewLabel"><?php echo $jurnal->nomer->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jurnal->nomer->CellAttributes() ?>>
<span id="el_jurnal_nomer">
<input type="text" data-table="jurnal" data-field="x_nomer" name="x_nomer" id="x_nomer" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($jurnal->nomer->getPlaceHolder()) ?>" value="<?php echo $jurnal->nomer->EditValue ?>"<?php echo $jurnal->nomer->EditAttributes() ?>>
</span>
<?php echo $jurnal->nomer->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("jurnald", explode(",", $jurnal->getCurrentDetailTable())) && $jurnald->DetailEdit) {
?>
<?php if ($jurnal->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("jurnald", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "jurnaldgrid.php" ?>
<?php } ?>
<?php if (!$jurnal_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $jurnal_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($jurnal_edit->Pager)) $jurnal_edit->Pager = new cPrevNextPager($jurnal_edit->StartRec, $jurnal_edit->DisplayRecs, $jurnal_edit->TotalRecs) ?>
<?php if ($jurnal_edit->Pager->RecordCount > 0 && $jurnal_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($jurnal_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $jurnal_edit->PageUrl() ?>start=<?php echo $jurnal_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($jurnal_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $jurnal_edit->PageUrl() ?>start=<?php echo $jurnal_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $jurnal_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($jurnal_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $jurnal_edit->PageUrl() ?>start=<?php echo $jurnal_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($jurnal_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $jurnal_edit->PageUrl() ?>start=<?php echo $jurnal_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $jurnal_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fjurnaledit.Init();
</script>
<?php
$jurnal_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jurnal_edit->Page_Terminate();
?>
