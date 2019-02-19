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

$produk_add = NULL; // Initialize page object first

class cproduk_add extends cproduk {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{994C6BDE-6323-4115-98D0-9A87BE039368}";

	// Table name
	var $TableName = 'produk';

	// Page object name
	var $PageObjName = 'produk_add';

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

		// Table object (produk)
		if (!isset($GLOBALS["produk"]) || get_class($GLOBALS["produk"]) == "cproduk") {
			$GLOBALS["produk"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["produk"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'produk', TRUE);

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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("produklist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "produklist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "produkview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->kode->CurrentValue = NULL;
		$this->kode->OldValue = $this->kode->CurrentValue;
		$this->nama->CurrentValue = NULL;
		$this->nama->OldValue = $this->nama->CurrentValue;
		$this->kelompok_id->CurrentValue = NULL;
		$this->kelompok_id->OldValue = $this->kelompok_id->CurrentValue;
		$this->satuan_id->CurrentValue = NULL;
		$this->satuan_id->OldValue = $this->satuan_id->CurrentValue;
		$this->satuan_id2->CurrentValue = NULL;
		$this->satuan_id2->OldValue = $this->satuan_id2->CurrentValue;
		$this->gudang_id->CurrentValue = NULL;
		$this->gudang_id->OldValue = $this->gudang_id->CurrentValue;
		$this->minstok->CurrentValue = NULL;
		$this->minstok->OldValue = $this->minstok->CurrentValue;
		$this->minorder->CurrentValue = NULL;
		$this->minorder->OldValue = $this->minorder->CurrentValue;
		$this->akunhpp->CurrentValue = NULL;
		$this->akunhpp->OldValue = $this->akunhpp->CurrentValue;
		$this->akunjual->CurrentValue = NULL;
		$this->akunjual->OldValue = $this->akunjual->CurrentValue;
		$this->akunpersediaan->CurrentValue = NULL;
		$this->akunpersediaan->OldValue = $this->akunpersediaan->CurrentValue;
		$this->akunreturjual->CurrentValue = NULL;
		$this->akunreturjual->OldValue = $this->akunreturjual->CurrentValue;
		$this->hargapokok->CurrentValue = NULL;
		$this->hargapokok->OldValue = $this->hargapokok->CurrentValue;
		$this->p->CurrentValue = NULL;
		$this->p->OldValue = $this->p->CurrentValue;
		$this->l->CurrentValue = NULL;
		$this->l->OldValue = $this->l->CurrentValue;
		$this->t->CurrentValue = NULL;
		$this->t->OldValue = $this->t->CurrentValue;
		$this->berat->CurrentValue = NULL;
		$this->berat->OldValue = $this->berat->CurrentValue;
		$this->supplier_id->CurrentValue = NULL;
		$this->supplier_id->OldValue = $this->supplier_id->CurrentValue;
		$this->waktukirim->CurrentValue = NULL;
		$this->waktukirim->OldValue = $this->waktukirim->CurrentValue;
		$this->aktif->CurrentValue = NULL;
		$this->aktif->OldValue = $this->aktif->CurrentValue;
		$this->id_FK->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->kode->FldIsDetailKey) {
			$this->kode->setFormValue($objForm->GetValue("x_kode"));
		}
		if (!$this->nama->FldIsDetailKey) {
			$this->nama->setFormValue($objForm->GetValue("x_nama"));
		}
		if (!$this->kelompok_id->FldIsDetailKey) {
			$this->kelompok_id->setFormValue($objForm->GetValue("x_kelompok_id"));
		}
		if (!$this->satuan_id->FldIsDetailKey) {
			$this->satuan_id->setFormValue($objForm->GetValue("x_satuan_id"));
		}
		if (!$this->satuan_id2->FldIsDetailKey) {
			$this->satuan_id2->setFormValue($objForm->GetValue("x_satuan_id2"));
		}
		if (!$this->gudang_id->FldIsDetailKey) {
			$this->gudang_id->setFormValue($objForm->GetValue("x_gudang_id"));
		}
		if (!$this->minstok->FldIsDetailKey) {
			$this->minstok->setFormValue($objForm->GetValue("x_minstok"));
		}
		if (!$this->minorder->FldIsDetailKey) {
			$this->minorder->setFormValue($objForm->GetValue("x_minorder"));
		}
		if (!$this->akunhpp->FldIsDetailKey) {
			$this->akunhpp->setFormValue($objForm->GetValue("x_akunhpp"));
		}
		if (!$this->akunjual->FldIsDetailKey) {
			$this->akunjual->setFormValue($objForm->GetValue("x_akunjual"));
		}
		if (!$this->akunpersediaan->FldIsDetailKey) {
			$this->akunpersediaan->setFormValue($objForm->GetValue("x_akunpersediaan"));
		}
		if (!$this->akunreturjual->FldIsDetailKey) {
			$this->akunreturjual->setFormValue($objForm->GetValue("x_akunreturjual"));
		}
		if (!$this->hargapokok->FldIsDetailKey) {
			$this->hargapokok->setFormValue($objForm->GetValue("x_hargapokok"));
		}
		if (!$this->p->FldIsDetailKey) {
			$this->p->setFormValue($objForm->GetValue("x_p"));
		}
		if (!$this->l->FldIsDetailKey) {
			$this->l->setFormValue($objForm->GetValue("x_l"));
		}
		if (!$this->t->FldIsDetailKey) {
			$this->t->setFormValue($objForm->GetValue("x_t"));
		}
		if (!$this->berat->FldIsDetailKey) {
			$this->berat->setFormValue($objForm->GetValue("x_berat"));
		}
		if (!$this->supplier_id->FldIsDetailKey) {
			$this->supplier_id->setFormValue($objForm->GetValue("x_supplier_id"));
		}
		if (!$this->waktukirim->FldIsDetailKey) {
			$this->waktukirim->setFormValue($objForm->GetValue("x_waktukirim"));
		}
		if (!$this->aktif->FldIsDetailKey) {
			$this->aktif->setFormValue($objForm->GetValue("x_aktif"));
		}
		if (!$this->id_FK->FldIsDetailKey) {
			$this->id_FK->setFormValue($objForm->GetValue("x_id_FK"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->kode->CurrentValue = $this->kode->FormValue;
		$this->nama->CurrentValue = $this->nama->FormValue;
		$this->kelompok_id->CurrentValue = $this->kelompok_id->FormValue;
		$this->satuan_id->CurrentValue = $this->satuan_id->FormValue;
		$this->satuan_id2->CurrentValue = $this->satuan_id2->FormValue;
		$this->gudang_id->CurrentValue = $this->gudang_id->FormValue;
		$this->minstok->CurrentValue = $this->minstok->FormValue;
		$this->minorder->CurrentValue = $this->minorder->FormValue;
		$this->akunhpp->CurrentValue = $this->akunhpp->FormValue;
		$this->akunjual->CurrentValue = $this->akunjual->FormValue;
		$this->akunpersediaan->CurrentValue = $this->akunpersediaan->FormValue;
		$this->akunreturjual->CurrentValue = $this->akunreturjual->FormValue;
		$this->hargapokok->CurrentValue = $this->hargapokok->FormValue;
		$this->p->CurrentValue = $this->p->FormValue;
		$this->l->CurrentValue = $this->l->FormValue;
		$this->t->CurrentValue = $this->t->FormValue;
		$this->berat->CurrentValue = $this->berat->FormValue;
		$this->supplier_id->CurrentValue = $this->supplier_id->FormValue;
		$this->waktukirim->CurrentValue = $this->waktukirim->FormValue;
		$this->aktif->CurrentValue = $this->aktif->FormValue;
		$this->id_FK->CurrentValue = $this->id_FK->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// kelompok_id
			$this->kelompok_id->EditAttrs["class"] = "form-control";
			$this->kelompok_id->EditCustomAttributes = "";
			$this->kelompok_id->EditValue = ew_HtmlEncode($this->kelompok_id->CurrentValue);
			$this->kelompok_id->PlaceHolder = ew_RemoveHtml($this->kelompok_id->FldCaption());

			// satuan_id
			$this->satuan_id->EditAttrs["class"] = "form-control";
			$this->satuan_id->EditCustomAttributes = "";
			$this->satuan_id->EditValue = ew_HtmlEncode($this->satuan_id->CurrentValue);
			$this->satuan_id->PlaceHolder = ew_RemoveHtml($this->satuan_id->FldCaption());

			// satuan_id2
			$this->satuan_id2->EditAttrs["class"] = "form-control";
			$this->satuan_id2->EditCustomAttributes = "";
			$this->satuan_id2->EditValue = ew_HtmlEncode($this->satuan_id2->CurrentValue);
			$this->satuan_id2->PlaceHolder = ew_RemoveHtml($this->satuan_id2->FldCaption());

			// gudang_id
			$this->gudang_id->EditAttrs["class"] = "form-control";
			$this->gudang_id->EditCustomAttributes = "";
			$this->gudang_id->EditValue = ew_HtmlEncode($this->gudang_id->CurrentValue);
			$this->gudang_id->PlaceHolder = ew_RemoveHtml($this->gudang_id->FldCaption());

			// minstok
			$this->minstok->EditAttrs["class"] = "form-control";
			$this->minstok->EditCustomAttributes = "";
			$this->minstok->EditValue = ew_HtmlEncode($this->minstok->CurrentValue);
			$this->minstok->PlaceHolder = ew_RemoveHtml($this->minstok->FldCaption());
			if (strval($this->minstok->EditValue) <> "" && is_numeric($this->minstok->EditValue)) $this->minstok->EditValue = ew_FormatNumber($this->minstok->EditValue, -2, -1, -2, 0);

			// minorder
			$this->minorder->EditAttrs["class"] = "form-control";
			$this->minorder->EditCustomAttributes = "";
			$this->minorder->EditValue = ew_HtmlEncode($this->minorder->CurrentValue);
			$this->minorder->PlaceHolder = ew_RemoveHtml($this->minorder->FldCaption());
			if (strval($this->minorder->EditValue) <> "" && is_numeric($this->minorder->EditValue)) $this->minorder->EditValue = ew_FormatNumber($this->minorder->EditValue, -2, -1, -2, 0);

			// akunhpp
			$this->akunhpp->EditAttrs["class"] = "form-control";
			$this->akunhpp->EditCustomAttributes = "";
			$this->akunhpp->EditValue = ew_HtmlEncode($this->akunhpp->CurrentValue);
			$this->akunhpp->PlaceHolder = ew_RemoveHtml($this->akunhpp->FldCaption());

			// akunjual
			$this->akunjual->EditAttrs["class"] = "form-control";
			$this->akunjual->EditCustomAttributes = "";
			$this->akunjual->EditValue = ew_HtmlEncode($this->akunjual->CurrentValue);
			$this->akunjual->PlaceHolder = ew_RemoveHtml($this->akunjual->FldCaption());

			// akunpersediaan
			$this->akunpersediaan->EditAttrs["class"] = "form-control";
			$this->akunpersediaan->EditCustomAttributes = "";
			$this->akunpersediaan->EditValue = ew_HtmlEncode($this->akunpersediaan->CurrentValue);
			$this->akunpersediaan->PlaceHolder = ew_RemoveHtml($this->akunpersediaan->FldCaption());

			// akunreturjual
			$this->akunreturjual->EditAttrs["class"] = "form-control";
			$this->akunreturjual->EditCustomAttributes = "";
			$this->akunreturjual->EditValue = ew_HtmlEncode($this->akunreturjual->CurrentValue);
			$this->akunreturjual->PlaceHolder = ew_RemoveHtml($this->akunreturjual->FldCaption());

			// hargapokok
			$this->hargapokok->EditAttrs["class"] = "form-control";
			$this->hargapokok->EditCustomAttributes = "";
			$this->hargapokok->EditValue = ew_HtmlEncode($this->hargapokok->CurrentValue);
			$this->hargapokok->PlaceHolder = ew_RemoveHtml($this->hargapokok->FldCaption());
			if (strval($this->hargapokok->EditValue) <> "" && is_numeric($this->hargapokok->EditValue)) $this->hargapokok->EditValue = ew_FormatNumber($this->hargapokok->EditValue, -2, -1, -2, 0);

			// p
			$this->p->EditAttrs["class"] = "form-control";
			$this->p->EditCustomAttributes = "";
			$this->p->EditValue = ew_HtmlEncode($this->p->CurrentValue);
			$this->p->PlaceHolder = ew_RemoveHtml($this->p->FldCaption());
			if (strval($this->p->EditValue) <> "" && is_numeric($this->p->EditValue)) $this->p->EditValue = ew_FormatNumber($this->p->EditValue, -2, -1, -2, 0);

			// l
			$this->l->EditAttrs["class"] = "form-control";
			$this->l->EditCustomAttributes = "";
			$this->l->EditValue = ew_HtmlEncode($this->l->CurrentValue);
			$this->l->PlaceHolder = ew_RemoveHtml($this->l->FldCaption());
			if (strval($this->l->EditValue) <> "" && is_numeric($this->l->EditValue)) $this->l->EditValue = ew_FormatNumber($this->l->EditValue, -2, -1, -2, 0);

			// t
			$this->t->EditAttrs["class"] = "form-control";
			$this->t->EditCustomAttributes = "";
			$this->t->EditValue = ew_HtmlEncode($this->t->CurrentValue);
			$this->t->PlaceHolder = ew_RemoveHtml($this->t->FldCaption());
			if (strval($this->t->EditValue) <> "" && is_numeric($this->t->EditValue)) $this->t->EditValue = ew_FormatNumber($this->t->EditValue, -2, -1, -2, 0);

			// berat
			$this->berat->EditAttrs["class"] = "form-control";
			$this->berat->EditCustomAttributes = "";
			$this->berat->EditValue = ew_HtmlEncode($this->berat->CurrentValue);
			$this->berat->PlaceHolder = ew_RemoveHtml($this->berat->FldCaption());
			if (strval($this->berat->EditValue) <> "" && is_numeric($this->berat->EditValue)) $this->berat->EditValue = ew_FormatNumber($this->berat->EditValue, -2, -1, -2, 0);

			// supplier_id
			$this->supplier_id->EditAttrs["class"] = "form-control";
			$this->supplier_id->EditCustomAttributes = "";
			$this->supplier_id->EditValue = ew_HtmlEncode($this->supplier_id->CurrentValue);
			$this->supplier_id->PlaceHolder = ew_RemoveHtml($this->supplier_id->FldCaption());

			// waktukirim
			$this->waktukirim->EditAttrs["class"] = "form-control";
			$this->waktukirim->EditCustomAttributes = "";
			$this->waktukirim->EditValue = ew_HtmlEncode($this->waktukirim->CurrentValue);
			$this->waktukirim->PlaceHolder = ew_RemoveHtml($this->waktukirim->FldCaption());

			// aktif
			$this->aktif->EditAttrs["class"] = "form-control";
			$this->aktif->EditCustomAttributes = "";
			$this->aktif->EditValue = ew_HtmlEncode($this->aktif->CurrentValue);
			$this->aktif->PlaceHolder = ew_RemoveHtml($this->aktif->FldCaption());

			// id_FK
			$this->id_FK->EditAttrs["class"] = "form-control";
			$this->id_FK->EditCustomAttributes = "";
			$this->id_FK->EditValue = ew_HtmlEncode($this->id_FK->CurrentValue);
			$this->id_FK->PlaceHolder = ew_RemoveHtml($this->id_FK->FldCaption());

			// Add refer script
			// kode

			$this->kode->LinkCustomAttributes = "";
			$this->kode->HrefValue = "";

			// nama
			$this->nama->LinkCustomAttributes = "";
			$this->nama->HrefValue = "";

			// kelompok_id
			$this->kelompok_id->LinkCustomAttributes = "";
			$this->kelompok_id->HrefValue = "";

			// satuan_id
			$this->satuan_id->LinkCustomAttributes = "";
			$this->satuan_id->HrefValue = "";

			// satuan_id2
			$this->satuan_id2->LinkCustomAttributes = "";
			$this->satuan_id2->HrefValue = "";

			// gudang_id
			$this->gudang_id->LinkCustomAttributes = "";
			$this->gudang_id->HrefValue = "";

			// minstok
			$this->minstok->LinkCustomAttributes = "";
			$this->minstok->HrefValue = "";

			// minorder
			$this->minorder->LinkCustomAttributes = "";
			$this->minorder->HrefValue = "";

			// akunhpp
			$this->akunhpp->LinkCustomAttributes = "";
			$this->akunhpp->HrefValue = "";

			// akunjual
			$this->akunjual->LinkCustomAttributes = "";
			$this->akunjual->HrefValue = "";

			// akunpersediaan
			$this->akunpersediaan->LinkCustomAttributes = "";
			$this->akunpersediaan->HrefValue = "";

			// akunreturjual
			$this->akunreturjual->LinkCustomAttributes = "";
			$this->akunreturjual->HrefValue = "";

			// hargapokok
			$this->hargapokok->LinkCustomAttributes = "";
			$this->hargapokok->HrefValue = "";

			// p
			$this->p->LinkCustomAttributes = "";
			$this->p->HrefValue = "";

			// l
			$this->l->LinkCustomAttributes = "";
			$this->l->HrefValue = "";

			// t
			$this->t->LinkCustomAttributes = "";
			$this->t->HrefValue = "";

			// berat
			$this->berat->LinkCustomAttributes = "";
			$this->berat->HrefValue = "";

			// supplier_id
			$this->supplier_id->LinkCustomAttributes = "";
			$this->supplier_id->HrefValue = "";

			// waktukirim
			$this->waktukirim->LinkCustomAttributes = "";
			$this->waktukirim->HrefValue = "";

			// aktif
			$this->aktif->LinkCustomAttributes = "";
			$this->aktif->HrefValue = "";

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
		if (!ew_CheckInteger($this->kelompok_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->kelompok_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->satuan_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->satuan_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->satuan_id2->FormValue)) {
			ew_AddMessage($gsFormError, $this->satuan_id2->FldErrMsg());
		}
		if (!ew_CheckInteger($this->gudang_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->gudang_id->FldErrMsg());
		}
		if (!ew_CheckNumber($this->minstok->FormValue)) {
			ew_AddMessage($gsFormError, $this->minstok->FldErrMsg());
		}
		if (!ew_CheckNumber($this->minorder->FormValue)) {
			ew_AddMessage($gsFormError, $this->minorder->FldErrMsg());
		}
		if (!ew_CheckInteger($this->akunhpp->FormValue)) {
			ew_AddMessage($gsFormError, $this->akunhpp->FldErrMsg());
		}
		if (!ew_CheckInteger($this->akunjual->FormValue)) {
			ew_AddMessage($gsFormError, $this->akunjual->FldErrMsg());
		}
		if (!ew_CheckInteger($this->akunpersediaan->FormValue)) {
			ew_AddMessage($gsFormError, $this->akunpersediaan->FldErrMsg());
		}
		if (!ew_CheckInteger($this->akunreturjual->FormValue)) {
			ew_AddMessage($gsFormError, $this->akunreturjual->FldErrMsg());
		}
		if (!ew_CheckNumber($this->hargapokok->FormValue)) {
			ew_AddMessage($gsFormError, $this->hargapokok->FldErrMsg());
		}
		if (!ew_CheckNumber($this->p->FormValue)) {
			ew_AddMessage($gsFormError, $this->p->FldErrMsg());
		}
		if (!ew_CheckNumber($this->l->FormValue)) {
			ew_AddMessage($gsFormError, $this->l->FldErrMsg());
		}
		if (!ew_CheckNumber($this->t->FormValue)) {
			ew_AddMessage($gsFormError, $this->t->FldErrMsg());
		}
		if (!ew_CheckNumber($this->berat->FormValue)) {
			ew_AddMessage($gsFormError, $this->berat->FldErrMsg());
		}
		if (!ew_CheckInteger($this->supplier_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->supplier_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->waktukirim->FormValue)) {
			ew_AddMessage($gsFormError, $this->waktukirim->FldErrMsg());
		}
		if (!ew_CheckInteger($this->aktif->FormValue)) {
			ew_AddMessage($gsFormError, $this->aktif->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// kode
		$this->kode->SetDbValueDef($rsnew, $this->kode->CurrentValue, NULL, FALSE);

		// nama
		$this->nama->SetDbValueDef($rsnew, $this->nama->CurrentValue, NULL, FALSE);

		// kelompok_id
		$this->kelompok_id->SetDbValueDef($rsnew, $this->kelompok_id->CurrentValue, NULL, FALSE);

		// satuan_id
		$this->satuan_id->SetDbValueDef($rsnew, $this->satuan_id->CurrentValue, NULL, FALSE);

		// satuan_id2
		$this->satuan_id2->SetDbValueDef($rsnew, $this->satuan_id2->CurrentValue, NULL, FALSE);

		// gudang_id
		$this->gudang_id->SetDbValueDef($rsnew, $this->gudang_id->CurrentValue, NULL, FALSE);

		// minstok
		$this->minstok->SetDbValueDef($rsnew, $this->minstok->CurrentValue, NULL, FALSE);

		// minorder
		$this->minorder->SetDbValueDef($rsnew, $this->minorder->CurrentValue, NULL, FALSE);

		// akunhpp
		$this->akunhpp->SetDbValueDef($rsnew, $this->akunhpp->CurrentValue, NULL, FALSE);

		// akunjual
		$this->akunjual->SetDbValueDef($rsnew, $this->akunjual->CurrentValue, NULL, FALSE);

		// akunpersediaan
		$this->akunpersediaan->SetDbValueDef($rsnew, $this->akunpersediaan->CurrentValue, NULL, FALSE);

		// akunreturjual
		$this->akunreturjual->SetDbValueDef($rsnew, $this->akunreturjual->CurrentValue, NULL, FALSE);

		// hargapokok
		$this->hargapokok->SetDbValueDef($rsnew, $this->hargapokok->CurrentValue, NULL, FALSE);

		// p
		$this->p->SetDbValueDef($rsnew, $this->p->CurrentValue, NULL, FALSE);

		// l
		$this->l->SetDbValueDef($rsnew, $this->l->CurrentValue, NULL, FALSE);

		// t
		$this->t->SetDbValueDef($rsnew, $this->t->CurrentValue, NULL, FALSE);

		// berat
		$this->berat->SetDbValueDef($rsnew, $this->berat->CurrentValue, NULL, FALSE);

		// supplier_id
		$this->supplier_id->SetDbValueDef($rsnew, $this->supplier_id->CurrentValue, NULL, FALSE);

		// waktukirim
		$this->waktukirim->SetDbValueDef($rsnew, $this->waktukirim->CurrentValue, NULL, FALSE);

		// aktif
		$this->aktif->SetDbValueDef($rsnew, $this->aktif->CurrentValue, NULL, FALSE);

		// id_FK
		$this->id_FK->SetDbValueDef($rsnew, $this->id_FK->CurrentValue, 0, strval($this->id_FK->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("produklist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($produk_add)) $produk_add = new cproduk_add();

// Page init
$produk_add->Page_Init();

// Page main
$produk_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$produk_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fprodukadd = new ew_Form("fprodukadd", "add");

// Validate form
fprodukadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_kelompok_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->kelompok_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_satuan_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->satuan_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_satuan_id2");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->satuan_id2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_gudang_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->gudang_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_minstok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->minstok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_minorder");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->minorder->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_akunhpp");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->akunhpp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_akunjual");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->akunjual->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_akunpersediaan");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->akunpersediaan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_akunreturjual");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->akunreturjual->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hargapokok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->hargapokok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_p");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->p->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_l");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->l->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_t");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->t->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_berat");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->berat->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_supplier_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->supplier_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_waktukirim");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->waktukirim->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_aktif");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->aktif->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_FK");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $produk->id_FK->FldCaption(), $produk->id_FK->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_FK");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($produk->id_FK->FldErrMsg()) ?>");

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
fprodukadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprodukadd.ValidateRequired = true;
<?php } else { ?>
fprodukadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$produk_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $produk_add->ShowPageHeader(); ?>
<?php
$produk_add->ShowMessage();
?>
<form name="fprodukadd" id="fprodukadd" class="<?php echo $produk_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($produk_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $produk_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="produk">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($produk_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($produk->kode->Visible) { // kode ?>
	<div id="r_kode" class="form-group">
		<label id="elh_produk_kode" for="x_kode" class="col-sm-2 control-label ewLabel"><?php echo $produk->kode->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->kode->CellAttributes() ?>>
<span id="el_produk_kode">
<input type="text" data-table="produk" data-field="x_kode" name="x_kode" id="x_kode" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($produk->kode->getPlaceHolder()) ?>" value="<?php echo $produk->kode->EditValue ?>"<?php echo $produk->kode->EditAttributes() ?>>
</span>
<?php echo $produk->kode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->nama->Visible) { // nama ?>
	<div id="r_nama" class="form-group">
		<label id="elh_produk_nama" for="x_nama" class="col-sm-2 control-label ewLabel"><?php echo $produk->nama->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->nama->CellAttributes() ?>>
<span id="el_produk_nama">
<input type="text" data-table="produk" data-field="x_nama" name="x_nama" id="x_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($produk->nama->getPlaceHolder()) ?>" value="<?php echo $produk->nama->EditValue ?>"<?php echo $produk->nama->EditAttributes() ?>>
</span>
<?php echo $produk->nama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->kelompok_id->Visible) { // kelompok_id ?>
	<div id="r_kelompok_id" class="form-group">
		<label id="elh_produk_kelompok_id" for="x_kelompok_id" class="col-sm-2 control-label ewLabel"><?php echo $produk->kelompok_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->kelompok_id->CellAttributes() ?>>
<span id="el_produk_kelompok_id">
<input type="text" data-table="produk" data-field="x_kelompok_id" name="x_kelompok_id" id="x_kelompok_id" size="30" placeholder="<?php echo ew_HtmlEncode($produk->kelompok_id->getPlaceHolder()) ?>" value="<?php echo $produk->kelompok_id->EditValue ?>"<?php echo $produk->kelompok_id->EditAttributes() ?>>
</span>
<?php echo $produk->kelompok_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->satuan_id->Visible) { // satuan_id ?>
	<div id="r_satuan_id" class="form-group">
		<label id="elh_produk_satuan_id" for="x_satuan_id" class="col-sm-2 control-label ewLabel"><?php echo $produk->satuan_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->satuan_id->CellAttributes() ?>>
<span id="el_produk_satuan_id">
<input type="text" data-table="produk" data-field="x_satuan_id" name="x_satuan_id" id="x_satuan_id" size="30" placeholder="<?php echo ew_HtmlEncode($produk->satuan_id->getPlaceHolder()) ?>" value="<?php echo $produk->satuan_id->EditValue ?>"<?php echo $produk->satuan_id->EditAttributes() ?>>
</span>
<?php echo $produk->satuan_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->satuan_id2->Visible) { // satuan_id2 ?>
	<div id="r_satuan_id2" class="form-group">
		<label id="elh_produk_satuan_id2" for="x_satuan_id2" class="col-sm-2 control-label ewLabel"><?php echo $produk->satuan_id2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->satuan_id2->CellAttributes() ?>>
<span id="el_produk_satuan_id2">
<input type="text" data-table="produk" data-field="x_satuan_id2" name="x_satuan_id2" id="x_satuan_id2" size="30" placeholder="<?php echo ew_HtmlEncode($produk->satuan_id2->getPlaceHolder()) ?>" value="<?php echo $produk->satuan_id2->EditValue ?>"<?php echo $produk->satuan_id2->EditAttributes() ?>>
</span>
<?php echo $produk->satuan_id2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->gudang_id->Visible) { // gudang_id ?>
	<div id="r_gudang_id" class="form-group">
		<label id="elh_produk_gudang_id" for="x_gudang_id" class="col-sm-2 control-label ewLabel"><?php echo $produk->gudang_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->gudang_id->CellAttributes() ?>>
<span id="el_produk_gudang_id">
<input type="text" data-table="produk" data-field="x_gudang_id" name="x_gudang_id" id="x_gudang_id" size="30" placeholder="<?php echo ew_HtmlEncode($produk->gudang_id->getPlaceHolder()) ?>" value="<?php echo $produk->gudang_id->EditValue ?>"<?php echo $produk->gudang_id->EditAttributes() ?>>
</span>
<?php echo $produk->gudang_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->minstok->Visible) { // minstok ?>
	<div id="r_minstok" class="form-group">
		<label id="elh_produk_minstok" for="x_minstok" class="col-sm-2 control-label ewLabel"><?php echo $produk->minstok->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->minstok->CellAttributes() ?>>
<span id="el_produk_minstok">
<input type="text" data-table="produk" data-field="x_minstok" name="x_minstok" id="x_minstok" size="30" placeholder="<?php echo ew_HtmlEncode($produk->minstok->getPlaceHolder()) ?>" value="<?php echo $produk->minstok->EditValue ?>"<?php echo $produk->minstok->EditAttributes() ?>>
</span>
<?php echo $produk->minstok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->minorder->Visible) { // minorder ?>
	<div id="r_minorder" class="form-group">
		<label id="elh_produk_minorder" for="x_minorder" class="col-sm-2 control-label ewLabel"><?php echo $produk->minorder->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->minorder->CellAttributes() ?>>
<span id="el_produk_minorder">
<input type="text" data-table="produk" data-field="x_minorder" name="x_minorder" id="x_minorder" size="30" placeholder="<?php echo ew_HtmlEncode($produk->minorder->getPlaceHolder()) ?>" value="<?php echo $produk->minorder->EditValue ?>"<?php echo $produk->minorder->EditAttributes() ?>>
</span>
<?php echo $produk->minorder->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->akunhpp->Visible) { // akunhpp ?>
	<div id="r_akunhpp" class="form-group">
		<label id="elh_produk_akunhpp" for="x_akunhpp" class="col-sm-2 control-label ewLabel"><?php echo $produk->akunhpp->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->akunhpp->CellAttributes() ?>>
<span id="el_produk_akunhpp">
<input type="text" data-table="produk" data-field="x_akunhpp" name="x_akunhpp" id="x_akunhpp" size="30" placeholder="<?php echo ew_HtmlEncode($produk->akunhpp->getPlaceHolder()) ?>" value="<?php echo $produk->akunhpp->EditValue ?>"<?php echo $produk->akunhpp->EditAttributes() ?>>
</span>
<?php echo $produk->akunhpp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->akunjual->Visible) { // akunjual ?>
	<div id="r_akunjual" class="form-group">
		<label id="elh_produk_akunjual" for="x_akunjual" class="col-sm-2 control-label ewLabel"><?php echo $produk->akunjual->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->akunjual->CellAttributes() ?>>
<span id="el_produk_akunjual">
<input type="text" data-table="produk" data-field="x_akunjual" name="x_akunjual" id="x_akunjual" size="30" placeholder="<?php echo ew_HtmlEncode($produk->akunjual->getPlaceHolder()) ?>" value="<?php echo $produk->akunjual->EditValue ?>"<?php echo $produk->akunjual->EditAttributes() ?>>
</span>
<?php echo $produk->akunjual->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->akunpersediaan->Visible) { // akunpersediaan ?>
	<div id="r_akunpersediaan" class="form-group">
		<label id="elh_produk_akunpersediaan" for="x_akunpersediaan" class="col-sm-2 control-label ewLabel"><?php echo $produk->akunpersediaan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->akunpersediaan->CellAttributes() ?>>
<span id="el_produk_akunpersediaan">
<input type="text" data-table="produk" data-field="x_akunpersediaan" name="x_akunpersediaan" id="x_akunpersediaan" size="30" placeholder="<?php echo ew_HtmlEncode($produk->akunpersediaan->getPlaceHolder()) ?>" value="<?php echo $produk->akunpersediaan->EditValue ?>"<?php echo $produk->akunpersediaan->EditAttributes() ?>>
</span>
<?php echo $produk->akunpersediaan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->akunreturjual->Visible) { // akunreturjual ?>
	<div id="r_akunreturjual" class="form-group">
		<label id="elh_produk_akunreturjual" for="x_akunreturjual" class="col-sm-2 control-label ewLabel"><?php echo $produk->akunreturjual->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->akunreturjual->CellAttributes() ?>>
<span id="el_produk_akunreturjual">
<input type="text" data-table="produk" data-field="x_akunreturjual" name="x_akunreturjual" id="x_akunreturjual" size="30" placeholder="<?php echo ew_HtmlEncode($produk->akunreturjual->getPlaceHolder()) ?>" value="<?php echo $produk->akunreturjual->EditValue ?>"<?php echo $produk->akunreturjual->EditAttributes() ?>>
</span>
<?php echo $produk->akunreturjual->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->hargapokok->Visible) { // hargapokok ?>
	<div id="r_hargapokok" class="form-group">
		<label id="elh_produk_hargapokok" for="x_hargapokok" class="col-sm-2 control-label ewLabel"><?php echo $produk->hargapokok->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->hargapokok->CellAttributes() ?>>
<span id="el_produk_hargapokok">
<input type="text" data-table="produk" data-field="x_hargapokok" name="x_hargapokok" id="x_hargapokok" size="30" placeholder="<?php echo ew_HtmlEncode($produk->hargapokok->getPlaceHolder()) ?>" value="<?php echo $produk->hargapokok->EditValue ?>"<?php echo $produk->hargapokok->EditAttributes() ?>>
</span>
<?php echo $produk->hargapokok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->p->Visible) { // p ?>
	<div id="r_p" class="form-group">
		<label id="elh_produk_p" for="x_p" class="col-sm-2 control-label ewLabel"><?php echo $produk->p->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->p->CellAttributes() ?>>
<span id="el_produk_p">
<input type="text" data-table="produk" data-field="x_p" name="x_p" id="x_p" size="30" placeholder="<?php echo ew_HtmlEncode($produk->p->getPlaceHolder()) ?>" value="<?php echo $produk->p->EditValue ?>"<?php echo $produk->p->EditAttributes() ?>>
</span>
<?php echo $produk->p->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->l->Visible) { // l ?>
	<div id="r_l" class="form-group">
		<label id="elh_produk_l" for="x_l" class="col-sm-2 control-label ewLabel"><?php echo $produk->l->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->l->CellAttributes() ?>>
<span id="el_produk_l">
<input type="text" data-table="produk" data-field="x_l" name="x_l" id="x_l" size="30" placeholder="<?php echo ew_HtmlEncode($produk->l->getPlaceHolder()) ?>" value="<?php echo $produk->l->EditValue ?>"<?php echo $produk->l->EditAttributes() ?>>
</span>
<?php echo $produk->l->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->t->Visible) { // t ?>
	<div id="r_t" class="form-group">
		<label id="elh_produk_t" for="x_t" class="col-sm-2 control-label ewLabel"><?php echo $produk->t->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->t->CellAttributes() ?>>
<span id="el_produk_t">
<input type="text" data-table="produk" data-field="x_t" name="x_t" id="x_t" size="30" placeholder="<?php echo ew_HtmlEncode($produk->t->getPlaceHolder()) ?>" value="<?php echo $produk->t->EditValue ?>"<?php echo $produk->t->EditAttributes() ?>>
</span>
<?php echo $produk->t->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->berat->Visible) { // berat ?>
	<div id="r_berat" class="form-group">
		<label id="elh_produk_berat" for="x_berat" class="col-sm-2 control-label ewLabel"><?php echo $produk->berat->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->berat->CellAttributes() ?>>
<span id="el_produk_berat">
<input type="text" data-table="produk" data-field="x_berat" name="x_berat" id="x_berat" size="30" placeholder="<?php echo ew_HtmlEncode($produk->berat->getPlaceHolder()) ?>" value="<?php echo $produk->berat->EditValue ?>"<?php echo $produk->berat->EditAttributes() ?>>
</span>
<?php echo $produk->berat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->supplier_id->Visible) { // supplier_id ?>
	<div id="r_supplier_id" class="form-group">
		<label id="elh_produk_supplier_id" for="x_supplier_id" class="col-sm-2 control-label ewLabel"><?php echo $produk->supplier_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->supplier_id->CellAttributes() ?>>
<span id="el_produk_supplier_id">
<input type="text" data-table="produk" data-field="x_supplier_id" name="x_supplier_id" id="x_supplier_id" size="30" placeholder="<?php echo ew_HtmlEncode($produk->supplier_id->getPlaceHolder()) ?>" value="<?php echo $produk->supplier_id->EditValue ?>"<?php echo $produk->supplier_id->EditAttributes() ?>>
</span>
<?php echo $produk->supplier_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->waktukirim->Visible) { // waktukirim ?>
	<div id="r_waktukirim" class="form-group">
		<label id="elh_produk_waktukirim" for="x_waktukirim" class="col-sm-2 control-label ewLabel"><?php echo $produk->waktukirim->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->waktukirim->CellAttributes() ?>>
<span id="el_produk_waktukirim">
<input type="text" data-table="produk" data-field="x_waktukirim" name="x_waktukirim" id="x_waktukirim" size="30" placeholder="<?php echo ew_HtmlEncode($produk->waktukirim->getPlaceHolder()) ?>" value="<?php echo $produk->waktukirim->EditValue ?>"<?php echo $produk->waktukirim->EditAttributes() ?>>
</span>
<?php echo $produk->waktukirim->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->aktif->Visible) { // aktif ?>
	<div id="r_aktif" class="form-group">
		<label id="elh_produk_aktif" for="x_aktif" class="col-sm-2 control-label ewLabel"><?php echo $produk->aktif->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produk->aktif->CellAttributes() ?>>
<span id="el_produk_aktif">
<input type="text" data-table="produk" data-field="x_aktif" name="x_aktif" id="x_aktif" size="30" placeholder="<?php echo ew_HtmlEncode($produk->aktif->getPlaceHolder()) ?>" value="<?php echo $produk->aktif->EditValue ?>"<?php echo $produk->aktif->EditAttributes() ?>>
</span>
<?php echo $produk->aktif->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produk->id_FK->Visible) { // id_FK ?>
	<div id="r_id_FK" class="form-group">
		<label id="elh_produk_id_FK" for="x_id_FK" class="col-sm-2 control-label ewLabel"><?php echo $produk->id_FK->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $produk->id_FK->CellAttributes() ?>>
<span id="el_produk_id_FK">
<input type="text" data-table="produk" data-field="x_id_FK" name="x_id_FK" id="x_id_FK" size="30" placeholder="<?php echo ew_HtmlEncode($produk->id_FK->getPlaceHolder()) ?>" value="<?php echo $produk->id_FK->EditValue ?>"<?php echo $produk->id_FK->EditAttributes() ?>>
</span>
<?php echo $produk->id_FK->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$produk_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $produk_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fprodukadd.Init();
</script>
<?php
$produk_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$produk_add->Page_Terminate();
?>
