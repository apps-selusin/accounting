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

$person_delete = NULL; // Initialize page object first

class cperson_delete extends cperson {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{994C6BDE-6323-4115-98D0-9A87BE039368}";

	// Table name
	var $TableName = 'person';

	// Page object name
	var $PageObjName = 'person_delete';

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

		// Table object (person)
		if (!isset($GLOBALS["person"]) || get_class($GLOBALS["person"]) == "cperson") {
			$GLOBALS["person"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["person"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'person', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("personlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in person class, personinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("personlist.php"); // Return to list
			}
		}
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("personlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($person_delete)) $person_delete = new cperson_delete();

// Page init
$person_delete->Page_Init();

// Page main
$person_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$person_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fpersondelete = new ew_Form("fpersondelete", "delete");

// Form_CustomValidate event
fpersondelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpersondelete.ValidateRequired = true;
<?php } else { ?>
fpersondelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $person_delete->ShowPageHeader(); ?>
<?php
$person_delete->ShowMessage();
?>
<form name="fpersondelete" id="fpersondelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($person_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $person_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="person">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($person_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $person->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($person->id->Visible) { // id ?>
		<th><span id="elh_person_id" class="person_id"><?php echo $person->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->kode->Visible) { // kode ?>
		<th><span id="elh_person_kode" class="person_kode"><?php echo $person->kode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->nama->Visible) { // nama ?>
		<th><span id="elh_person_nama" class="person_nama"><?php echo $person->nama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->kontak->Visible) { // kontak ?>
		<th><span id="elh_person_kontak" class="person_kontak"><?php echo $person->kontak->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->type_id->Visible) { // type_id ?>
		<th><span id="elh_person_type_id" class="person_type_id"><?php echo $person->type_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->telp1->Visible) { // telp1 ?>
		<th><span id="elh_person_telp1" class="person_telp1"><?php echo $person->telp1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->matauang_id->Visible) { // matauang_id ?>
		<th><span id="elh_person_matauang_id" class="person_matauang_id"><?php echo $person->matauang_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->username->Visible) { // username ?>
		<th><span id="elh_person_username" class="person_username"><?php echo $person->username->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->password->Visible) { // password ?>
		<th><span id="elh_person_password" class="person_password"><?php echo $person->password->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->telp2->Visible) { // telp2 ?>
		<th><span id="elh_person_telp2" class="person_telp2"><?php echo $person->telp2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->fax->Visible) { // fax ?>
		<th><span id="elh_person_fax" class="person_fax"><?php echo $person->fax->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->hp->Visible) { // hp ?>
		<th><span id="elh_person_hp" class="person_hp"><?php echo $person->hp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->_email->Visible) { // email ?>
		<th><span id="elh_person__email" class="person__email"><?php echo $person->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->website->Visible) { // website ?>
		<th><span id="elh_person_website" class="person_website"><?php echo $person->website->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->npwp->Visible) { // npwp ?>
		<th><span id="elh_person_npwp" class="person_npwp"><?php echo $person->npwp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->alamat->Visible) { // alamat ?>
		<th><span id="elh_person_alamat" class="person_alamat"><?php echo $person->alamat->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->kota->Visible) { // kota ?>
		<th><span id="elh_person_kota" class="person_kota"><?php echo $person->kota->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->zip->Visible) { // zip ?>
		<th><span id="elh_person_zip" class="person_zip"><?php echo $person->zip->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->klasifikasi_id->Visible) { // klasifikasi_id ?>
		<th><span id="elh_person_klasifikasi_id" class="person_klasifikasi_id"><?php echo $person->klasifikasi_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($person->id_FK->Visible) { // id_FK ?>
		<th><span id="elh_person_id_FK" class="person_id_FK"><?php echo $person->id_FK->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$person_delete->RecCnt = 0;
$i = 0;
while (!$person_delete->Recordset->EOF) {
	$person_delete->RecCnt++;
	$person_delete->RowCnt++;

	// Set row properties
	$person->ResetAttrs();
	$person->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$person_delete->LoadRowValues($person_delete->Recordset);

	// Render row
	$person_delete->RenderRow();
?>
	<tr<?php echo $person->RowAttributes() ?>>
<?php if ($person->id->Visible) { // id ?>
		<td<?php echo $person->id->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_id" class="person_id">
<span<?php echo $person->id->ViewAttributes() ?>>
<?php echo $person->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->kode->Visible) { // kode ?>
		<td<?php echo $person->kode->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_kode" class="person_kode">
<span<?php echo $person->kode->ViewAttributes() ?>>
<?php echo $person->kode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->nama->Visible) { // nama ?>
		<td<?php echo $person->nama->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_nama" class="person_nama">
<span<?php echo $person->nama->ViewAttributes() ?>>
<?php echo $person->nama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->kontak->Visible) { // kontak ?>
		<td<?php echo $person->kontak->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_kontak" class="person_kontak">
<span<?php echo $person->kontak->ViewAttributes() ?>>
<?php echo $person->kontak->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->type_id->Visible) { // type_id ?>
		<td<?php echo $person->type_id->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_type_id" class="person_type_id">
<span<?php echo $person->type_id->ViewAttributes() ?>>
<?php echo $person->type_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->telp1->Visible) { // telp1 ?>
		<td<?php echo $person->telp1->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_telp1" class="person_telp1">
<span<?php echo $person->telp1->ViewAttributes() ?>>
<?php echo $person->telp1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->matauang_id->Visible) { // matauang_id ?>
		<td<?php echo $person->matauang_id->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_matauang_id" class="person_matauang_id">
<span<?php echo $person->matauang_id->ViewAttributes() ?>>
<?php echo $person->matauang_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->username->Visible) { // username ?>
		<td<?php echo $person->username->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_username" class="person_username">
<span<?php echo $person->username->ViewAttributes() ?>>
<?php echo $person->username->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->password->Visible) { // password ?>
		<td<?php echo $person->password->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_password" class="person_password">
<span<?php echo $person->password->ViewAttributes() ?>>
<?php echo $person->password->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->telp2->Visible) { // telp2 ?>
		<td<?php echo $person->telp2->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_telp2" class="person_telp2">
<span<?php echo $person->telp2->ViewAttributes() ?>>
<?php echo $person->telp2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->fax->Visible) { // fax ?>
		<td<?php echo $person->fax->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_fax" class="person_fax">
<span<?php echo $person->fax->ViewAttributes() ?>>
<?php echo $person->fax->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->hp->Visible) { // hp ?>
		<td<?php echo $person->hp->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_hp" class="person_hp">
<span<?php echo $person->hp->ViewAttributes() ?>>
<?php echo $person->hp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->_email->Visible) { // email ?>
		<td<?php echo $person->_email->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person__email" class="person__email">
<span<?php echo $person->_email->ViewAttributes() ?>>
<?php echo $person->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->website->Visible) { // website ?>
		<td<?php echo $person->website->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_website" class="person_website">
<span<?php echo $person->website->ViewAttributes() ?>>
<?php echo $person->website->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->npwp->Visible) { // npwp ?>
		<td<?php echo $person->npwp->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_npwp" class="person_npwp">
<span<?php echo $person->npwp->ViewAttributes() ?>>
<?php echo $person->npwp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->alamat->Visible) { // alamat ?>
		<td<?php echo $person->alamat->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_alamat" class="person_alamat">
<span<?php echo $person->alamat->ViewAttributes() ?>>
<?php echo $person->alamat->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->kota->Visible) { // kota ?>
		<td<?php echo $person->kota->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_kota" class="person_kota">
<span<?php echo $person->kota->ViewAttributes() ?>>
<?php echo $person->kota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->zip->Visible) { // zip ?>
		<td<?php echo $person->zip->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_zip" class="person_zip">
<span<?php echo $person->zip->ViewAttributes() ?>>
<?php echo $person->zip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->klasifikasi_id->Visible) { // klasifikasi_id ?>
		<td<?php echo $person->klasifikasi_id->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_klasifikasi_id" class="person_klasifikasi_id">
<span<?php echo $person->klasifikasi_id->ViewAttributes() ?>>
<?php echo $person->klasifikasi_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($person->id_FK->Visible) { // id_FK ?>
		<td<?php echo $person->id_FK->CellAttributes() ?>>
<span id="el<?php echo $person_delete->RowCnt ?>_person_id_FK" class="person_id_FK">
<span<?php echo $person->id_FK->ViewAttributes() ?>>
<?php echo $person->id_FK->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$person_delete->Recordset->MoveNext();
}
$person_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $person_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpersondelete.Init();
</script>
<?php
$person_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$person_delete->Page_Terminate();
?>
