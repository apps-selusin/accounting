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

$person_edit = NULL; // Initialize page object first

class cperson_edit extends cperson {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{994C6BDE-6323-4115-98D0-9A87BE039368}";

	// Table name
	var $TableName = 'person';

	// Page object name
	var $PageObjName = 'person_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
			$this->Page_Terminate("personlist.php"); // Return to list page
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
					$this->Page_Terminate("personlist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "personlist.php")
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
		if (!$this->kode->FldIsDetailKey) {
			$this->kode->setFormValue($objForm->GetValue("x_kode"));
		}
		if (!$this->nama->FldIsDetailKey) {
			$this->nama->setFormValue($objForm->GetValue("x_nama"));
		}
		if (!$this->kontak->FldIsDetailKey) {
			$this->kontak->setFormValue($objForm->GetValue("x_kontak"));
		}
		if (!$this->type_id->FldIsDetailKey) {
			$this->type_id->setFormValue($objForm->GetValue("x_type_id"));
		}
		if (!$this->telp1->FldIsDetailKey) {
			$this->telp1->setFormValue($objForm->GetValue("x_telp1"));
		}
		if (!$this->matauang_id->FldIsDetailKey) {
			$this->matauang_id->setFormValue($objForm->GetValue("x_matauang_id"));
		}
		if (!$this->username->FldIsDetailKey) {
			$this->username->setFormValue($objForm->GetValue("x_username"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->telp2->FldIsDetailKey) {
			$this->telp2->setFormValue($objForm->GetValue("x_telp2"));
		}
		if (!$this->fax->FldIsDetailKey) {
			$this->fax->setFormValue($objForm->GetValue("x_fax"));
		}
		if (!$this->hp->FldIsDetailKey) {
			$this->hp->setFormValue($objForm->GetValue("x_hp"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->website->FldIsDetailKey) {
			$this->website->setFormValue($objForm->GetValue("x_website"));
		}
		if (!$this->npwp->FldIsDetailKey) {
			$this->npwp->setFormValue($objForm->GetValue("x_npwp"));
		}
		if (!$this->alamat->FldIsDetailKey) {
			$this->alamat->setFormValue($objForm->GetValue("x_alamat"));
		}
		if (!$this->kota->FldIsDetailKey) {
			$this->kota->setFormValue($objForm->GetValue("x_kota"));
		}
		if (!$this->zip->FldIsDetailKey) {
			$this->zip->setFormValue($objForm->GetValue("x_zip"));
		}
		if (!$this->klasifikasi_id->FldIsDetailKey) {
			$this->klasifikasi_id->setFormValue($objForm->GetValue("x_klasifikasi_id"));
		}
		if (!$this->id_FK->FldIsDetailKey) {
			$this->id_FK->setFormValue($objForm->GetValue("x_id_FK"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->kode->CurrentValue = $this->kode->FormValue;
		$this->nama->CurrentValue = $this->nama->FormValue;
		$this->kontak->CurrentValue = $this->kontak->FormValue;
		$this->type_id->CurrentValue = $this->type_id->FormValue;
		$this->telp1->CurrentValue = $this->telp1->FormValue;
		$this->matauang_id->CurrentValue = $this->matauang_id->FormValue;
		$this->username->CurrentValue = $this->username->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->telp2->CurrentValue = $this->telp2->FormValue;
		$this->fax->CurrentValue = $this->fax->FormValue;
		$this->hp->CurrentValue = $this->hp->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->website->CurrentValue = $this->website->FormValue;
		$this->npwp->CurrentValue = $this->npwp->FormValue;
		$this->alamat->CurrentValue = $this->alamat->FormValue;
		$this->kota->CurrentValue = $this->kota->FormValue;
		$this->zip->CurrentValue = $this->zip->FormValue;
		$this->klasifikasi_id->CurrentValue = $this->klasifikasi_id->FormValue;
		$this->id_FK->CurrentValue = $this->id_FK->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// kode
			$this->kode->EditAttrs["class"] = "form-control";
			$this->kode->EditCustomAttributes = "";
			$this->kode->EditValue = ew_HtmlEncode($this->kode->CurrentValue);
			$this->kode->PlaceHolder = ew_RemoveHtml($this->kode->FldCaption());

			// nama
			$this->nama->EditAttrs["class"] = "form-control";
			$this->nama->EditCustomAttributes = "";
			$this->nama->EditValue = ew_HtmlEncode($this->nama->CurrentValue);
			$this->nama->PlaceHolder = ew_RemoveHtml($this->nama->FldCaption());

			// kontak
			$this->kontak->EditAttrs["class"] = "form-control";
			$this->kontak->EditCustomAttributes = "";
			$this->kontak->EditValue = ew_HtmlEncode($this->kontak->CurrentValue);
			$this->kontak->PlaceHolder = ew_RemoveHtml($this->kontak->FldCaption());

			// type_id
			$this->type_id->EditAttrs["class"] = "form-control";
			$this->type_id->EditCustomAttributes = "";
			$this->type_id->EditValue = ew_HtmlEncode($this->type_id->CurrentValue);
			$this->type_id->PlaceHolder = ew_RemoveHtml($this->type_id->FldCaption());

			// telp1
			$this->telp1->EditAttrs["class"] = "form-control";
			$this->telp1->EditCustomAttributes = "";
			$this->telp1->EditValue = ew_HtmlEncode($this->telp1->CurrentValue);
			$this->telp1->PlaceHolder = ew_RemoveHtml($this->telp1->FldCaption());

			// matauang_id
			$this->matauang_id->EditAttrs["class"] = "form-control";
			$this->matauang_id->EditCustomAttributes = "";
			$this->matauang_id->EditValue = ew_HtmlEncode($this->matauang_id->CurrentValue);
			$this->matauang_id->PlaceHolder = ew_RemoveHtml($this->matauang_id->FldCaption());

			// username
			$this->username->EditAttrs["class"] = "form-control";
			$this->username->EditCustomAttributes = "";
			$this->username->EditValue = ew_HtmlEncode($this->username->CurrentValue);
			$this->username->PlaceHolder = ew_RemoveHtml($this->username->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// telp2
			$this->telp2->EditAttrs["class"] = "form-control";
			$this->telp2->EditCustomAttributes = "";
			$this->telp2->EditValue = ew_HtmlEncode($this->telp2->CurrentValue);
			$this->telp2->PlaceHolder = ew_RemoveHtml($this->telp2->FldCaption());

			// fax
			$this->fax->EditAttrs["class"] = "form-control";
			$this->fax->EditCustomAttributes = "";
			$this->fax->EditValue = ew_HtmlEncode($this->fax->CurrentValue);
			$this->fax->PlaceHolder = ew_RemoveHtml($this->fax->FldCaption());

			// hp
			$this->hp->EditAttrs["class"] = "form-control";
			$this->hp->EditCustomAttributes = "";
			$this->hp->EditValue = ew_HtmlEncode($this->hp->CurrentValue);
			$this->hp->PlaceHolder = ew_RemoveHtml($this->hp->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// website
			$this->website->EditAttrs["class"] = "form-control";
			$this->website->EditCustomAttributes = "";
			$this->website->EditValue = ew_HtmlEncode($this->website->CurrentValue);
			$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

			// npwp
			$this->npwp->EditAttrs["class"] = "form-control";
			$this->npwp->EditCustomAttributes = "";
			$this->npwp->EditValue = ew_HtmlEncode($this->npwp->CurrentValue);
			$this->npwp->PlaceHolder = ew_RemoveHtml($this->npwp->FldCaption());

			// alamat
			$this->alamat->EditAttrs["class"] = "form-control";
			$this->alamat->EditCustomAttributes = "";
			$this->alamat->EditValue = ew_HtmlEncode($this->alamat->CurrentValue);
			$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

			// kota
			$this->kota->EditAttrs["class"] = "form-control";
			$this->kota->EditCustomAttributes = "";
			$this->kota->EditValue = ew_HtmlEncode($this->kota->CurrentValue);
			$this->kota->PlaceHolder = ew_RemoveHtml($this->kota->FldCaption());

			// zip
			$this->zip->EditAttrs["class"] = "form-control";
			$this->zip->EditCustomAttributes = "";
			$this->zip->EditValue = ew_HtmlEncode($this->zip->CurrentValue);
			$this->zip->PlaceHolder = ew_RemoveHtml($this->zip->FldCaption());

			// klasifikasi_id
			$this->klasifikasi_id->EditAttrs["class"] = "form-control";
			$this->klasifikasi_id->EditCustomAttributes = "";
			$this->klasifikasi_id->EditValue = ew_HtmlEncode($this->klasifikasi_id->CurrentValue);
			$this->klasifikasi_id->PlaceHolder = ew_RemoveHtml($this->klasifikasi_id->FldCaption());

			// id_FK
			$this->id_FK->EditAttrs["class"] = "form-control";
			$this->id_FK->EditCustomAttributes = "";
			$this->id_FK->EditValue = ew_HtmlEncode($this->id_FK->CurrentValue);
			$this->id_FK->PlaceHolder = ew_RemoveHtml($this->id_FK->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// kode
			$this->kode->LinkCustomAttributes = "";
			$this->kode->HrefValue = "";

			// nama
			$this->nama->LinkCustomAttributes = "";
			$this->nama->HrefValue = "";

			// kontak
			$this->kontak->LinkCustomAttributes = "";
			$this->kontak->HrefValue = "";

			// type_id
			$this->type_id->LinkCustomAttributes = "";
			$this->type_id->HrefValue = "";

			// telp1
			$this->telp1->LinkCustomAttributes = "";
			$this->telp1->HrefValue = "";

			// matauang_id
			$this->matauang_id->LinkCustomAttributes = "";
			$this->matauang_id->HrefValue = "";

			// username
			$this->username->LinkCustomAttributes = "";
			$this->username->HrefValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";

			// telp2
			$this->telp2->LinkCustomAttributes = "";
			$this->telp2->HrefValue = "";

			// fax
			$this->fax->LinkCustomAttributes = "";
			$this->fax->HrefValue = "";

			// hp
			$this->hp->LinkCustomAttributes = "";
			$this->hp->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";

			// npwp
			$this->npwp->LinkCustomAttributes = "";
			$this->npwp->HrefValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";

			// kota
			$this->kota->LinkCustomAttributes = "";
			$this->kota->HrefValue = "";

			// zip
			$this->zip->LinkCustomAttributes = "";
			$this->zip->HrefValue = "";

			// klasifikasi_id
			$this->klasifikasi_id->LinkCustomAttributes = "";
			$this->klasifikasi_id->HrefValue = "";

			// id_FK
			$this->id_FK->LinkCustomAttributes = "";
			$this->id_FK->HrefValue = "";
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
		if (!ew_CheckInteger($this->type_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->type_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->matauang_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->matauang_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->klasifikasi_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->klasifikasi_id->FldErrMsg());
		}
		if (!$this->id_FK->FldIsDetailKey && !is_null($this->id_FK->FormValue) && $this->id_FK->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_FK->FldCaption(), $this->id_FK->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_FK->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_FK->FldErrMsg());
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// kode
			$this->kode->SetDbValueDef($rsnew, $this->kode->CurrentValue, NULL, $this->kode->ReadOnly);

			// nama
			$this->nama->SetDbValueDef($rsnew, $this->nama->CurrentValue, NULL, $this->nama->ReadOnly);

			// kontak
			$this->kontak->SetDbValueDef($rsnew, $this->kontak->CurrentValue, NULL, $this->kontak->ReadOnly);

			// type_id
			$this->type_id->SetDbValueDef($rsnew, $this->type_id->CurrentValue, NULL, $this->type_id->ReadOnly);

			// telp1
			$this->telp1->SetDbValueDef($rsnew, $this->telp1->CurrentValue, NULL, $this->telp1->ReadOnly);

			// matauang_id
			$this->matauang_id->SetDbValueDef($rsnew, $this->matauang_id->CurrentValue, NULL, $this->matauang_id->ReadOnly);

			// username
			$this->username->SetDbValueDef($rsnew, $this->username->CurrentValue, NULL, $this->username->ReadOnly);

			// password
			$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, NULL, $this->password->ReadOnly);

			// telp2
			$this->telp2->SetDbValueDef($rsnew, $this->telp2->CurrentValue, NULL, $this->telp2->ReadOnly);

			// fax
			$this->fax->SetDbValueDef($rsnew, $this->fax->CurrentValue, NULL, $this->fax->ReadOnly);

			// hp
			$this->hp->SetDbValueDef($rsnew, $this->hp->CurrentValue, NULL, $this->hp->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, $this->_email->ReadOnly);

			// website
			$this->website->SetDbValueDef($rsnew, $this->website->CurrentValue, NULL, $this->website->ReadOnly);

			// npwp
			$this->npwp->SetDbValueDef($rsnew, $this->npwp->CurrentValue, NULL, $this->npwp->ReadOnly);

			// alamat
			$this->alamat->SetDbValueDef($rsnew, $this->alamat->CurrentValue, NULL, $this->alamat->ReadOnly);

			// kota
			$this->kota->SetDbValueDef($rsnew, $this->kota->CurrentValue, NULL, $this->kota->ReadOnly);

			// zip
			$this->zip->SetDbValueDef($rsnew, $this->zip->CurrentValue, NULL, $this->zip->ReadOnly);

			// klasifikasi_id
			$this->klasifikasi_id->SetDbValueDef($rsnew, $this->klasifikasi_id->CurrentValue, NULL, $this->klasifikasi_id->ReadOnly);

			// id_FK
			$this->id_FK->SetDbValueDef($rsnew, $this->id_FK->CurrentValue, 0, $this->id_FK->ReadOnly);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("personlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($person_edit)) $person_edit = new cperson_edit();

// Page init
$person_edit->Page_Init();

// Page main
$person_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$person_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fpersonedit = new ew_Form("fpersonedit", "edit");

// Validate form
fpersonedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_type_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($person->type_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_matauang_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($person->matauang_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_klasifikasi_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($person->klasifikasi_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_FK");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $person->id_FK->FldCaption(), $person->id_FK->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_FK");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($person->id_FK->FldErrMsg()) ?>");

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
fpersonedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpersonedit.ValidateRequired = true;
<?php } else { ?>
fpersonedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$person_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $person_edit->ShowPageHeader(); ?>
<?php
$person_edit->ShowMessage();
?>
<form name="fpersonedit" id="fpersonedit" class="<?php echo $person_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($person_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $person_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="person">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($person_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($person->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_person_id" class="col-sm-2 control-label ewLabel"><?php echo $person->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->id->CellAttributes() ?>>
<span id="el_person_id">
<span<?php echo $person->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $person->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="person" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($person->id->CurrentValue) ?>">
<?php echo $person->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->kode->Visible) { // kode ?>
	<div id="r_kode" class="form-group">
		<label id="elh_person_kode" for="x_kode" class="col-sm-2 control-label ewLabel"><?php echo $person->kode->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->kode->CellAttributes() ?>>
<span id="el_person_kode">
<input type="text" data-table="person" data-field="x_kode" name="x_kode" id="x_kode" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->kode->getPlaceHolder()) ?>" value="<?php echo $person->kode->EditValue ?>"<?php echo $person->kode->EditAttributes() ?>>
</span>
<?php echo $person->kode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->nama->Visible) { // nama ?>
	<div id="r_nama" class="form-group">
		<label id="elh_person_nama" for="x_nama" class="col-sm-2 control-label ewLabel"><?php echo $person->nama->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->nama->CellAttributes() ?>>
<span id="el_person_nama">
<input type="text" data-table="person" data-field="x_nama" name="x_nama" id="x_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->nama->getPlaceHolder()) ?>" value="<?php echo $person->nama->EditValue ?>"<?php echo $person->nama->EditAttributes() ?>>
</span>
<?php echo $person->nama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->kontak->Visible) { // kontak ?>
	<div id="r_kontak" class="form-group">
		<label id="elh_person_kontak" for="x_kontak" class="col-sm-2 control-label ewLabel"><?php echo $person->kontak->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->kontak->CellAttributes() ?>>
<span id="el_person_kontak">
<input type="text" data-table="person" data-field="x_kontak" name="x_kontak" id="x_kontak" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->kontak->getPlaceHolder()) ?>" value="<?php echo $person->kontak->EditValue ?>"<?php echo $person->kontak->EditAttributes() ?>>
</span>
<?php echo $person->kontak->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->type_id->Visible) { // type_id ?>
	<div id="r_type_id" class="form-group">
		<label id="elh_person_type_id" for="x_type_id" class="col-sm-2 control-label ewLabel"><?php echo $person->type_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->type_id->CellAttributes() ?>>
<span id="el_person_type_id">
<input type="text" data-table="person" data-field="x_type_id" name="x_type_id" id="x_type_id" size="30" placeholder="<?php echo ew_HtmlEncode($person->type_id->getPlaceHolder()) ?>" value="<?php echo $person->type_id->EditValue ?>"<?php echo $person->type_id->EditAttributes() ?>>
</span>
<?php echo $person->type_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->telp1->Visible) { // telp1 ?>
	<div id="r_telp1" class="form-group">
		<label id="elh_person_telp1" for="x_telp1" class="col-sm-2 control-label ewLabel"><?php echo $person->telp1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->telp1->CellAttributes() ?>>
<span id="el_person_telp1">
<input type="text" data-table="person" data-field="x_telp1" name="x_telp1" id="x_telp1" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->telp1->getPlaceHolder()) ?>" value="<?php echo $person->telp1->EditValue ?>"<?php echo $person->telp1->EditAttributes() ?>>
</span>
<?php echo $person->telp1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->matauang_id->Visible) { // matauang_id ?>
	<div id="r_matauang_id" class="form-group">
		<label id="elh_person_matauang_id" for="x_matauang_id" class="col-sm-2 control-label ewLabel"><?php echo $person->matauang_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->matauang_id->CellAttributes() ?>>
<span id="el_person_matauang_id">
<input type="text" data-table="person" data-field="x_matauang_id" name="x_matauang_id" id="x_matauang_id" size="30" placeholder="<?php echo ew_HtmlEncode($person->matauang_id->getPlaceHolder()) ?>" value="<?php echo $person->matauang_id->EditValue ?>"<?php echo $person->matauang_id->EditAttributes() ?>>
</span>
<?php echo $person->matauang_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->username->Visible) { // username ?>
	<div id="r_username" class="form-group">
		<label id="elh_person_username" for="x_username" class="col-sm-2 control-label ewLabel"><?php echo $person->username->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->username->CellAttributes() ?>>
<span id="el_person_username">
<input type="text" data-table="person" data-field="x_username" name="x_username" id="x_username" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->username->getPlaceHolder()) ?>" value="<?php echo $person->username->EditValue ?>"<?php echo $person->username->EditAttributes() ?>>
</span>
<?php echo $person->username->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->password->Visible) { // password ?>
	<div id="r_password" class="form-group">
		<label id="elh_person_password" for="x_password" class="col-sm-2 control-label ewLabel"><?php echo $person->password->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->password->CellAttributes() ?>>
<span id="el_person_password">
<input type="text" data-table="person" data-field="x_password" name="x_password" id="x_password" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->password->getPlaceHolder()) ?>" value="<?php echo $person->password->EditValue ?>"<?php echo $person->password->EditAttributes() ?>>
</span>
<?php echo $person->password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->telp2->Visible) { // telp2 ?>
	<div id="r_telp2" class="form-group">
		<label id="elh_person_telp2" for="x_telp2" class="col-sm-2 control-label ewLabel"><?php echo $person->telp2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->telp2->CellAttributes() ?>>
<span id="el_person_telp2">
<input type="text" data-table="person" data-field="x_telp2" name="x_telp2" id="x_telp2" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->telp2->getPlaceHolder()) ?>" value="<?php echo $person->telp2->EditValue ?>"<?php echo $person->telp2->EditAttributes() ?>>
</span>
<?php echo $person->telp2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->fax->Visible) { // fax ?>
	<div id="r_fax" class="form-group">
		<label id="elh_person_fax" for="x_fax" class="col-sm-2 control-label ewLabel"><?php echo $person->fax->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->fax->CellAttributes() ?>>
<span id="el_person_fax">
<input type="text" data-table="person" data-field="x_fax" name="x_fax" id="x_fax" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->fax->getPlaceHolder()) ?>" value="<?php echo $person->fax->EditValue ?>"<?php echo $person->fax->EditAttributes() ?>>
</span>
<?php echo $person->fax->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->hp->Visible) { // hp ?>
	<div id="r_hp" class="form-group">
		<label id="elh_person_hp" for="x_hp" class="col-sm-2 control-label ewLabel"><?php echo $person->hp->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->hp->CellAttributes() ?>>
<span id="el_person_hp">
<input type="text" data-table="person" data-field="x_hp" name="x_hp" id="x_hp" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->hp->getPlaceHolder()) ?>" value="<?php echo $person->hp->EditValue ?>"<?php echo $person->hp->EditAttributes() ?>>
</span>
<?php echo $person->hp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_person__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $person->_email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->_email->CellAttributes() ?>>
<span id="el_person__email">
<input type="text" data-table="person" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->_email->getPlaceHolder()) ?>" value="<?php echo $person->_email->EditValue ?>"<?php echo $person->_email->EditAttributes() ?>>
</span>
<?php echo $person->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->website->Visible) { // website ?>
	<div id="r_website" class="form-group">
		<label id="elh_person_website" for="x_website" class="col-sm-2 control-label ewLabel"><?php echo $person->website->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->website->CellAttributes() ?>>
<span id="el_person_website">
<input type="text" data-table="person" data-field="x_website" name="x_website" id="x_website" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->website->getPlaceHolder()) ?>" value="<?php echo $person->website->EditValue ?>"<?php echo $person->website->EditAttributes() ?>>
</span>
<?php echo $person->website->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->npwp->Visible) { // npwp ?>
	<div id="r_npwp" class="form-group">
		<label id="elh_person_npwp" for="x_npwp" class="col-sm-2 control-label ewLabel"><?php echo $person->npwp->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->npwp->CellAttributes() ?>>
<span id="el_person_npwp">
<input type="text" data-table="person" data-field="x_npwp" name="x_npwp" id="x_npwp" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->npwp->getPlaceHolder()) ?>" value="<?php echo $person->npwp->EditValue ?>"<?php echo $person->npwp->EditAttributes() ?>>
</span>
<?php echo $person->npwp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->alamat->Visible) { // alamat ?>
	<div id="r_alamat" class="form-group">
		<label id="elh_person_alamat" for="x_alamat" class="col-sm-2 control-label ewLabel"><?php echo $person->alamat->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->alamat->CellAttributes() ?>>
<span id="el_person_alamat">
<input type="text" data-table="person" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->alamat->getPlaceHolder()) ?>" value="<?php echo $person->alamat->EditValue ?>"<?php echo $person->alamat->EditAttributes() ?>>
</span>
<?php echo $person->alamat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->kota->Visible) { // kota ?>
	<div id="r_kota" class="form-group">
		<label id="elh_person_kota" for="x_kota" class="col-sm-2 control-label ewLabel"><?php echo $person->kota->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->kota->CellAttributes() ?>>
<span id="el_person_kota">
<input type="text" data-table="person" data-field="x_kota" name="x_kota" id="x_kota" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->kota->getPlaceHolder()) ?>" value="<?php echo $person->kota->EditValue ?>"<?php echo $person->kota->EditAttributes() ?>>
</span>
<?php echo $person->kota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->zip->Visible) { // zip ?>
	<div id="r_zip" class="form-group">
		<label id="elh_person_zip" for="x_zip" class="col-sm-2 control-label ewLabel"><?php echo $person->zip->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->zip->CellAttributes() ?>>
<span id="el_person_zip">
<input type="text" data-table="person" data-field="x_zip" name="x_zip" id="x_zip" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($person->zip->getPlaceHolder()) ?>" value="<?php echo $person->zip->EditValue ?>"<?php echo $person->zip->EditAttributes() ?>>
</span>
<?php echo $person->zip->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->klasifikasi_id->Visible) { // klasifikasi_id ?>
	<div id="r_klasifikasi_id" class="form-group">
		<label id="elh_person_klasifikasi_id" for="x_klasifikasi_id" class="col-sm-2 control-label ewLabel"><?php echo $person->klasifikasi_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $person->klasifikasi_id->CellAttributes() ?>>
<span id="el_person_klasifikasi_id">
<input type="text" data-table="person" data-field="x_klasifikasi_id" name="x_klasifikasi_id" id="x_klasifikasi_id" size="30" placeholder="<?php echo ew_HtmlEncode($person->klasifikasi_id->getPlaceHolder()) ?>" value="<?php echo $person->klasifikasi_id->EditValue ?>"<?php echo $person->klasifikasi_id->EditAttributes() ?>>
</span>
<?php echo $person->klasifikasi_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($person->id_FK->Visible) { // id_FK ?>
	<div id="r_id_FK" class="form-group">
		<label id="elh_person_id_FK" for="x_id_FK" class="col-sm-2 control-label ewLabel"><?php echo $person->id_FK->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $person->id_FK->CellAttributes() ?>>
<span id="el_person_id_FK">
<input type="text" data-table="person" data-field="x_id_FK" name="x_id_FK" id="x_id_FK" size="30" placeholder="<?php echo ew_HtmlEncode($person->id_FK->getPlaceHolder()) ?>" value="<?php echo $person->id_FK->EditValue ?>"<?php echo $person->id_FK->EditAttributes() ?>>
</span>
<?php echo $person->id_FK->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$person_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $person_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($person_edit->Pager)) $person_edit->Pager = new cPrevNextPager($person_edit->StartRec, $person_edit->DisplayRecs, $person_edit->TotalRecs) ?>
<?php if ($person_edit->Pager->RecordCount > 0 && $person_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($person_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $person_edit->PageUrl() ?>start=<?php echo $person_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($person_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $person_edit->PageUrl() ?>start=<?php echo $person_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $person_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($person_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $person_edit->PageUrl() ?>start=<?php echo $person_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($person_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $person_edit->PageUrl() ?>start=<?php echo $person_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $person_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fpersonedit.Init();
</script>
<?php
$person_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$person_edit->Page_Terminate();
?>
