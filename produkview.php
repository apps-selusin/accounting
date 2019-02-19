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

$produk_view = NULL; // Initialize page object first

class cproduk_view extends cproduk {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{994C6BDE-6323-4115-98D0-9A87BE039368}";

	// Table name
	var $TableName = 'produk';

	// Page object name
	var $PageObjName = 'produk_view';

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'produk', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} elseif (@$_POST["id"] <> "") {
				$this->id->setFormValue($_POST["id"]);
				$this->RecKey["id"] = $this->id->FormValue;
			} else {
				$sReturnUrl = "produklist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "produklist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "produklist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "");

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_AddQueryStringToUrl($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "");

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("produklist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($produk_view)) $produk_view = new cproduk_view();

// Page init
$produk_view->Page_Init();

// Page main
$produk_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$produk_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fprodukview = new ew_Form("fprodukview", "view");

// Form_CustomValidate event
fprodukview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprodukview.ValidateRequired = true;
<?php } else { ?>
fprodukview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$produk_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $produk_view->ExportOptions->Render("body") ?>
<?php
	foreach ($produk_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$produk_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $produk_view->ShowPageHeader(); ?>
<?php
$produk_view->ShowMessage();
?>
<form name="fprodukview" id="fprodukview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($produk_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $produk_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="produk">
<?php if ($produk_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($produk->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_produk_id"><?php echo $produk->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $produk->id->CellAttributes() ?>>
<span id="el_produk_id">
<span<?php echo $produk->id->ViewAttributes() ?>>
<?php echo $produk->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->kode->Visible) { // kode ?>
	<tr id="r_kode">
		<td><span id="elh_produk_kode"><?php echo $produk->kode->FldCaption() ?></span></td>
		<td data-name="kode"<?php echo $produk->kode->CellAttributes() ?>>
<span id="el_produk_kode">
<span<?php echo $produk->kode->ViewAttributes() ?>>
<?php echo $produk->kode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->nama->Visible) { // nama ?>
	<tr id="r_nama">
		<td><span id="elh_produk_nama"><?php echo $produk->nama->FldCaption() ?></span></td>
		<td data-name="nama"<?php echo $produk->nama->CellAttributes() ?>>
<span id="el_produk_nama">
<span<?php echo $produk->nama->ViewAttributes() ?>>
<?php echo $produk->nama->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->kelompok_id->Visible) { // kelompok_id ?>
	<tr id="r_kelompok_id">
		<td><span id="elh_produk_kelompok_id"><?php echo $produk->kelompok_id->FldCaption() ?></span></td>
		<td data-name="kelompok_id"<?php echo $produk->kelompok_id->CellAttributes() ?>>
<span id="el_produk_kelompok_id">
<span<?php echo $produk->kelompok_id->ViewAttributes() ?>>
<?php echo $produk->kelompok_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->satuan_id->Visible) { // satuan_id ?>
	<tr id="r_satuan_id">
		<td><span id="elh_produk_satuan_id"><?php echo $produk->satuan_id->FldCaption() ?></span></td>
		<td data-name="satuan_id"<?php echo $produk->satuan_id->CellAttributes() ?>>
<span id="el_produk_satuan_id">
<span<?php echo $produk->satuan_id->ViewAttributes() ?>>
<?php echo $produk->satuan_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->satuan_id2->Visible) { // satuan_id2 ?>
	<tr id="r_satuan_id2">
		<td><span id="elh_produk_satuan_id2"><?php echo $produk->satuan_id2->FldCaption() ?></span></td>
		<td data-name="satuan_id2"<?php echo $produk->satuan_id2->CellAttributes() ?>>
<span id="el_produk_satuan_id2">
<span<?php echo $produk->satuan_id2->ViewAttributes() ?>>
<?php echo $produk->satuan_id2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->gudang_id->Visible) { // gudang_id ?>
	<tr id="r_gudang_id">
		<td><span id="elh_produk_gudang_id"><?php echo $produk->gudang_id->FldCaption() ?></span></td>
		<td data-name="gudang_id"<?php echo $produk->gudang_id->CellAttributes() ?>>
<span id="el_produk_gudang_id">
<span<?php echo $produk->gudang_id->ViewAttributes() ?>>
<?php echo $produk->gudang_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->minstok->Visible) { // minstok ?>
	<tr id="r_minstok">
		<td><span id="elh_produk_minstok"><?php echo $produk->minstok->FldCaption() ?></span></td>
		<td data-name="minstok"<?php echo $produk->minstok->CellAttributes() ?>>
<span id="el_produk_minstok">
<span<?php echo $produk->minstok->ViewAttributes() ?>>
<?php echo $produk->minstok->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->minorder->Visible) { // minorder ?>
	<tr id="r_minorder">
		<td><span id="elh_produk_minorder"><?php echo $produk->minorder->FldCaption() ?></span></td>
		<td data-name="minorder"<?php echo $produk->minorder->CellAttributes() ?>>
<span id="el_produk_minorder">
<span<?php echo $produk->minorder->ViewAttributes() ?>>
<?php echo $produk->minorder->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->akunhpp->Visible) { // akunhpp ?>
	<tr id="r_akunhpp">
		<td><span id="elh_produk_akunhpp"><?php echo $produk->akunhpp->FldCaption() ?></span></td>
		<td data-name="akunhpp"<?php echo $produk->akunhpp->CellAttributes() ?>>
<span id="el_produk_akunhpp">
<span<?php echo $produk->akunhpp->ViewAttributes() ?>>
<?php echo $produk->akunhpp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->akunjual->Visible) { // akunjual ?>
	<tr id="r_akunjual">
		<td><span id="elh_produk_akunjual"><?php echo $produk->akunjual->FldCaption() ?></span></td>
		<td data-name="akunjual"<?php echo $produk->akunjual->CellAttributes() ?>>
<span id="el_produk_akunjual">
<span<?php echo $produk->akunjual->ViewAttributes() ?>>
<?php echo $produk->akunjual->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->akunpersediaan->Visible) { // akunpersediaan ?>
	<tr id="r_akunpersediaan">
		<td><span id="elh_produk_akunpersediaan"><?php echo $produk->akunpersediaan->FldCaption() ?></span></td>
		<td data-name="akunpersediaan"<?php echo $produk->akunpersediaan->CellAttributes() ?>>
<span id="el_produk_akunpersediaan">
<span<?php echo $produk->akunpersediaan->ViewAttributes() ?>>
<?php echo $produk->akunpersediaan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->akunreturjual->Visible) { // akunreturjual ?>
	<tr id="r_akunreturjual">
		<td><span id="elh_produk_akunreturjual"><?php echo $produk->akunreturjual->FldCaption() ?></span></td>
		<td data-name="akunreturjual"<?php echo $produk->akunreturjual->CellAttributes() ?>>
<span id="el_produk_akunreturjual">
<span<?php echo $produk->akunreturjual->ViewAttributes() ?>>
<?php echo $produk->akunreturjual->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->hargapokok->Visible) { // hargapokok ?>
	<tr id="r_hargapokok">
		<td><span id="elh_produk_hargapokok"><?php echo $produk->hargapokok->FldCaption() ?></span></td>
		<td data-name="hargapokok"<?php echo $produk->hargapokok->CellAttributes() ?>>
<span id="el_produk_hargapokok">
<span<?php echo $produk->hargapokok->ViewAttributes() ?>>
<?php echo $produk->hargapokok->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->p->Visible) { // p ?>
	<tr id="r_p">
		<td><span id="elh_produk_p"><?php echo $produk->p->FldCaption() ?></span></td>
		<td data-name="p"<?php echo $produk->p->CellAttributes() ?>>
<span id="el_produk_p">
<span<?php echo $produk->p->ViewAttributes() ?>>
<?php echo $produk->p->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->l->Visible) { // l ?>
	<tr id="r_l">
		<td><span id="elh_produk_l"><?php echo $produk->l->FldCaption() ?></span></td>
		<td data-name="l"<?php echo $produk->l->CellAttributes() ?>>
<span id="el_produk_l">
<span<?php echo $produk->l->ViewAttributes() ?>>
<?php echo $produk->l->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->t->Visible) { // t ?>
	<tr id="r_t">
		<td><span id="elh_produk_t"><?php echo $produk->t->FldCaption() ?></span></td>
		<td data-name="t"<?php echo $produk->t->CellAttributes() ?>>
<span id="el_produk_t">
<span<?php echo $produk->t->ViewAttributes() ?>>
<?php echo $produk->t->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->berat->Visible) { // berat ?>
	<tr id="r_berat">
		<td><span id="elh_produk_berat"><?php echo $produk->berat->FldCaption() ?></span></td>
		<td data-name="berat"<?php echo $produk->berat->CellAttributes() ?>>
<span id="el_produk_berat">
<span<?php echo $produk->berat->ViewAttributes() ?>>
<?php echo $produk->berat->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->supplier_id->Visible) { // supplier_id ?>
	<tr id="r_supplier_id">
		<td><span id="elh_produk_supplier_id"><?php echo $produk->supplier_id->FldCaption() ?></span></td>
		<td data-name="supplier_id"<?php echo $produk->supplier_id->CellAttributes() ?>>
<span id="el_produk_supplier_id">
<span<?php echo $produk->supplier_id->ViewAttributes() ?>>
<?php echo $produk->supplier_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->waktukirim->Visible) { // waktukirim ?>
	<tr id="r_waktukirim">
		<td><span id="elh_produk_waktukirim"><?php echo $produk->waktukirim->FldCaption() ?></span></td>
		<td data-name="waktukirim"<?php echo $produk->waktukirim->CellAttributes() ?>>
<span id="el_produk_waktukirim">
<span<?php echo $produk->waktukirim->ViewAttributes() ?>>
<?php echo $produk->waktukirim->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->aktif->Visible) { // aktif ?>
	<tr id="r_aktif">
		<td><span id="elh_produk_aktif"><?php echo $produk->aktif->FldCaption() ?></span></td>
		<td data-name="aktif"<?php echo $produk->aktif->CellAttributes() ?>>
<span id="el_produk_aktif">
<span<?php echo $produk->aktif->ViewAttributes() ?>>
<?php echo $produk->aktif->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($produk->id_FK->Visible) { // id_FK ?>
	<tr id="r_id_FK">
		<td><span id="elh_produk_id_FK"><?php echo $produk->id_FK->FldCaption() ?></span></td>
		<td data-name="id_FK"<?php echo $produk->id_FK->CellAttributes() ?>>
<span id="el_produk_id_FK">
<span<?php echo $produk->id_FK->ViewAttributes() ?>>
<?php echo $produk->id_FK->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fprodukview.Init();
</script>
<?php
$produk_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$produk_view->Page_Terminate();
?>
