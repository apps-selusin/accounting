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

$produk_delete = NULL; // Initialize page object first

class cproduk_delete extends cproduk {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{994C6BDE-6323-4115-98D0-9A87BE039368}";

	// Table name
	var $TableName = 'produk';

	// Page object name
	var $PageObjName = 'produk_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
			$this->Page_Terminate("produklist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in produk class, produkinfo.php

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
				$this->Page_Terminate("produklist.php"); // Return to list
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("produklist.php"), "", $this->TableVar, TRUE);
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
if (!isset($produk_delete)) $produk_delete = new cproduk_delete();

// Page init
$produk_delete->Page_Init();

// Page main
$produk_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$produk_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fprodukdelete = new ew_Form("fprodukdelete", "delete");

// Form_CustomValidate event
fprodukdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprodukdelete.ValidateRequired = true;
<?php } else { ?>
fprodukdelete.ValidateRequired = false; 
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
<?php $produk_delete->ShowPageHeader(); ?>
<?php
$produk_delete->ShowMessage();
?>
<form name="fprodukdelete" id="fprodukdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($produk_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $produk_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="produk">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($produk_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $produk->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($produk->id->Visible) { // id ?>
		<th><span id="elh_produk_id" class="produk_id"><?php echo $produk->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->kode->Visible) { // kode ?>
		<th><span id="elh_produk_kode" class="produk_kode"><?php echo $produk->kode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->nama->Visible) { // nama ?>
		<th><span id="elh_produk_nama" class="produk_nama"><?php echo $produk->nama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->kelompok_id->Visible) { // kelompok_id ?>
		<th><span id="elh_produk_kelompok_id" class="produk_kelompok_id"><?php echo $produk->kelompok_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->satuan_id->Visible) { // satuan_id ?>
		<th><span id="elh_produk_satuan_id" class="produk_satuan_id"><?php echo $produk->satuan_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->satuan_id2->Visible) { // satuan_id2 ?>
		<th><span id="elh_produk_satuan_id2" class="produk_satuan_id2"><?php echo $produk->satuan_id2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->gudang_id->Visible) { // gudang_id ?>
		<th><span id="elh_produk_gudang_id" class="produk_gudang_id"><?php echo $produk->gudang_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->minstok->Visible) { // minstok ?>
		<th><span id="elh_produk_minstok" class="produk_minstok"><?php echo $produk->minstok->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->minorder->Visible) { // minorder ?>
		<th><span id="elh_produk_minorder" class="produk_minorder"><?php echo $produk->minorder->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->akunhpp->Visible) { // akunhpp ?>
		<th><span id="elh_produk_akunhpp" class="produk_akunhpp"><?php echo $produk->akunhpp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->akunjual->Visible) { // akunjual ?>
		<th><span id="elh_produk_akunjual" class="produk_akunjual"><?php echo $produk->akunjual->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->akunpersediaan->Visible) { // akunpersediaan ?>
		<th><span id="elh_produk_akunpersediaan" class="produk_akunpersediaan"><?php echo $produk->akunpersediaan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->akunreturjual->Visible) { // akunreturjual ?>
		<th><span id="elh_produk_akunreturjual" class="produk_akunreturjual"><?php echo $produk->akunreturjual->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->hargapokok->Visible) { // hargapokok ?>
		<th><span id="elh_produk_hargapokok" class="produk_hargapokok"><?php echo $produk->hargapokok->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->p->Visible) { // p ?>
		<th><span id="elh_produk_p" class="produk_p"><?php echo $produk->p->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->l->Visible) { // l ?>
		<th><span id="elh_produk_l" class="produk_l"><?php echo $produk->l->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->t->Visible) { // t ?>
		<th><span id="elh_produk_t" class="produk_t"><?php echo $produk->t->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->berat->Visible) { // berat ?>
		<th><span id="elh_produk_berat" class="produk_berat"><?php echo $produk->berat->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->supplier_id->Visible) { // supplier_id ?>
		<th><span id="elh_produk_supplier_id" class="produk_supplier_id"><?php echo $produk->supplier_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->waktukirim->Visible) { // waktukirim ?>
		<th><span id="elh_produk_waktukirim" class="produk_waktukirim"><?php echo $produk->waktukirim->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->aktif->Visible) { // aktif ?>
		<th><span id="elh_produk_aktif" class="produk_aktif"><?php echo $produk->aktif->FldCaption() ?></span></th>
<?php } ?>
<?php if ($produk->id_FK->Visible) { // id_FK ?>
		<th><span id="elh_produk_id_FK" class="produk_id_FK"><?php echo $produk->id_FK->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$produk_delete->RecCnt = 0;
$i = 0;
while (!$produk_delete->Recordset->EOF) {
	$produk_delete->RecCnt++;
	$produk_delete->RowCnt++;

	// Set row properties
	$produk->ResetAttrs();
	$produk->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$produk_delete->LoadRowValues($produk_delete->Recordset);

	// Render row
	$produk_delete->RenderRow();
?>
	<tr<?php echo $produk->RowAttributes() ?>>
<?php if ($produk->id->Visible) { // id ?>
		<td<?php echo $produk->id->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_id" class="produk_id">
<span<?php echo $produk->id->ViewAttributes() ?>>
<?php echo $produk->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->kode->Visible) { // kode ?>
		<td<?php echo $produk->kode->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_kode" class="produk_kode">
<span<?php echo $produk->kode->ViewAttributes() ?>>
<?php echo $produk->kode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->nama->Visible) { // nama ?>
		<td<?php echo $produk->nama->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_nama" class="produk_nama">
<span<?php echo $produk->nama->ViewAttributes() ?>>
<?php echo $produk->nama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->kelompok_id->Visible) { // kelompok_id ?>
		<td<?php echo $produk->kelompok_id->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_kelompok_id" class="produk_kelompok_id">
<span<?php echo $produk->kelompok_id->ViewAttributes() ?>>
<?php echo $produk->kelompok_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->satuan_id->Visible) { // satuan_id ?>
		<td<?php echo $produk->satuan_id->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_satuan_id" class="produk_satuan_id">
<span<?php echo $produk->satuan_id->ViewAttributes() ?>>
<?php echo $produk->satuan_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->satuan_id2->Visible) { // satuan_id2 ?>
		<td<?php echo $produk->satuan_id2->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_satuan_id2" class="produk_satuan_id2">
<span<?php echo $produk->satuan_id2->ViewAttributes() ?>>
<?php echo $produk->satuan_id2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->gudang_id->Visible) { // gudang_id ?>
		<td<?php echo $produk->gudang_id->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_gudang_id" class="produk_gudang_id">
<span<?php echo $produk->gudang_id->ViewAttributes() ?>>
<?php echo $produk->gudang_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->minstok->Visible) { // minstok ?>
		<td<?php echo $produk->minstok->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_minstok" class="produk_minstok">
<span<?php echo $produk->minstok->ViewAttributes() ?>>
<?php echo $produk->minstok->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->minorder->Visible) { // minorder ?>
		<td<?php echo $produk->minorder->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_minorder" class="produk_minorder">
<span<?php echo $produk->minorder->ViewAttributes() ?>>
<?php echo $produk->minorder->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->akunhpp->Visible) { // akunhpp ?>
		<td<?php echo $produk->akunhpp->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_akunhpp" class="produk_akunhpp">
<span<?php echo $produk->akunhpp->ViewAttributes() ?>>
<?php echo $produk->akunhpp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->akunjual->Visible) { // akunjual ?>
		<td<?php echo $produk->akunjual->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_akunjual" class="produk_akunjual">
<span<?php echo $produk->akunjual->ViewAttributes() ?>>
<?php echo $produk->akunjual->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->akunpersediaan->Visible) { // akunpersediaan ?>
		<td<?php echo $produk->akunpersediaan->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_akunpersediaan" class="produk_akunpersediaan">
<span<?php echo $produk->akunpersediaan->ViewAttributes() ?>>
<?php echo $produk->akunpersediaan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->akunreturjual->Visible) { // akunreturjual ?>
		<td<?php echo $produk->akunreturjual->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_akunreturjual" class="produk_akunreturjual">
<span<?php echo $produk->akunreturjual->ViewAttributes() ?>>
<?php echo $produk->akunreturjual->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->hargapokok->Visible) { // hargapokok ?>
		<td<?php echo $produk->hargapokok->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_hargapokok" class="produk_hargapokok">
<span<?php echo $produk->hargapokok->ViewAttributes() ?>>
<?php echo $produk->hargapokok->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->p->Visible) { // p ?>
		<td<?php echo $produk->p->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_p" class="produk_p">
<span<?php echo $produk->p->ViewAttributes() ?>>
<?php echo $produk->p->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->l->Visible) { // l ?>
		<td<?php echo $produk->l->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_l" class="produk_l">
<span<?php echo $produk->l->ViewAttributes() ?>>
<?php echo $produk->l->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->t->Visible) { // t ?>
		<td<?php echo $produk->t->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_t" class="produk_t">
<span<?php echo $produk->t->ViewAttributes() ?>>
<?php echo $produk->t->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->berat->Visible) { // berat ?>
		<td<?php echo $produk->berat->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_berat" class="produk_berat">
<span<?php echo $produk->berat->ViewAttributes() ?>>
<?php echo $produk->berat->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->supplier_id->Visible) { // supplier_id ?>
		<td<?php echo $produk->supplier_id->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_supplier_id" class="produk_supplier_id">
<span<?php echo $produk->supplier_id->ViewAttributes() ?>>
<?php echo $produk->supplier_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->waktukirim->Visible) { // waktukirim ?>
		<td<?php echo $produk->waktukirim->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_waktukirim" class="produk_waktukirim">
<span<?php echo $produk->waktukirim->ViewAttributes() ?>>
<?php echo $produk->waktukirim->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->aktif->Visible) { // aktif ?>
		<td<?php echo $produk->aktif->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_aktif" class="produk_aktif">
<span<?php echo $produk->aktif->ViewAttributes() ?>>
<?php echo $produk->aktif->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($produk->id_FK->Visible) { // id_FK ?>
		<td<?php echo $produk->id_FK->CellAttributes() ?>>
<span id="el<?php echo $produk_delete->RowCnt ?>_produk_id_FK" class="produk_id_FK">
<span<?php echo $produk->id_FK->ViewAttributes() ?>>
<?php echo $produk->id_FK->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$produk_delete->Recordset->MoveNext();
}
$produk_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $produk_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fprodukdelete.Init();
</script>
<?php
$produk_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$produk_delete->Page_Terminate();
?>
