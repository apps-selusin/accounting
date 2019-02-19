<?php

// Global variable for table object
$produk = NULL;

//
// Table class for produk
//
class cproduk extends cTable {
	var $id;
	var $kode;
	var $nama;
	var $kelompok_id;
	var $satuan_id;
	var $satuan_id2;
	var $gudang_id;
	var $minstok;
	var $minorder;
	var $akunhpp;
	var $akunjual;
	var $akunpersediaan;
	var $akunreturjual;
	var $hargapokok;
	var $p;
	var $l;
	var $t;
	var $berat;
	var $supplier_id;
	var $waktukirim;
	var $aktif;
	var $id_FK;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'produk';
		$this->TableName = 'produk';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`produk`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('produk', 'produk', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// kode
		$this->kode = new cField('produk', 'produk', 'x_kode', 'kode', '`kode`', '`kode`', 200, -1, FALSE, '`kode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kode->Sortable = TRUE; // Allow sort
		$this->fields['kode'] = &$this->kode;

		// nama
		$this->nama = new cField('produk', 'produk', 'x_nama', 'nama', '`nama`', '`nama`', 200, -1, FALSE, '`nama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nama->Sortable = TRUE; // Allow sort
		$this->fields['nama'] = &$this->nama;

		// kelompok_id
		$this->kelompok_id = new cField('produk', 'produk', 'x_kelompok_id', 'kelompok_id', '`kelompok_id`', '`kelompok_id`', 3, -1, FALSE, '`kelompok_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kelompok_id->Sortable = TRUE; // Allow sort
		$this->kelompok_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['kelompok_id'] = &$this->kelompok_id;

		// satuan_id
		$this->satuan_id = new cField('produk', 'produk', 'x_satuan_id', 'satuan_id', '`satuan_id`', '`satuan_id`', 3, -1, FALSE, '`satuan_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->satuan_id->Sortable = TRUE; // Allow sort
		$this->satuan_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['satuan_id'] = &$this->satuan_id;

		// satuan_id2
		$this->satuan_id2 = new cField('produk', 'produk', 'x_satuan_id2', 'satuan_id2', '`satuan_id2`', '`satuan_id2`', 3, -1, FALSE, '`satuan_id2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->satuan_id2->Sortable = TRUE; // Allow sort
		$this->satuan_id2->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['satuan_id2'] = &$this->satuan_id2;

		// gudang_id
		$this->gudang_id = new cField('produk', 'produk', 'x_gudang_id', 'gudang_id', '`gudang_id`', '`gudang_id`', 3, -1, FALSE, '`gudang_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->gudang_id->Sortable = TRUE; // Allow sort
		$this->gudang_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['gudang_id'] = &$this->gudang_id;

		// minstok
		$this->minstok = new cField('produk', 'produk', 'x_minstok', 'minstok', '`minstok`', '`minstok`', 5, -1, FALSE, '`minstok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->minstok->Sortable = TRUE; // Allow sort
		$this->minstok->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['minstok'] = &$this->minstok;

		// minorder
		$this->minorder = new cField('produk', 'produk', 'x_minorder', 'minorder', '`minorder`', '`minorder`', 5, -1, FALSE, '`minorder`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->minorder->Sortable = TRUE; // Allow sort
		$this->minorder->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['minorder'] = &$this->minorder;

		// akunhpp
		$this->akunhpp = new cField('produk', 'produk', 'x_akunhpp', 'akunhpp', '`akunhpp`', '`akunhpp`', 3, -1, FALSE, '`akunhpp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->akunhpp->Sortable = TRUE; // Allow sort
		$this->akunhpp->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['akunhpp'] = &$this->akunhpp;

		// akunjual
		$this->akunjual = new cField('produk', 'produk', 'x_akunjual', 'akunjual', '`akunjual`', '`akunjual`', 3, -1, FALSE, '`akunjual`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->akunjual->Sortable = TRUE; // Allow sort
		$this->akunjual->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['akunjual'] = &$this->akunjual;

		// akunpersediaan
		$this->akunpersediaan = new cField('produk', 'produk', 'x_akunpersediaan', 'akunpersediaan', '`akunpersediaan`', '`akunpersediaan`', 3, -1, FALSE, '`akunpersediaan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->akunpersediaan->Sortable = TRUE; // Allow sort
		$this->akunpersediaan->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['akunpersediaan'] = &$this->akunpersediaan;

		// akunreturjual
		$this->akunreturjual = new cField('produk', 'produk', 'x_akunreturjual', 'akunreturjual', '`akunreturjual`', '`akunreturjual`', 3, -1, FALSE, '`akunreturjual`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->akunreturjual->Sortable = TRUE; // Allow sort
		$this->akunreturjual->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['akunreturjual'] = &$this->akunreturjual;

		// hargapokok
		$this->hargapokok = new cField('produk', 'produk', 'x_hargapokok', 'hargapokok', '`hargapokok`', '`hargapokok`', 5, -1, FALSE, '`hargapokok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hargapokok->Sortable = TRUE; // Allow sort
		$this->hargapokok->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['hargapokok'] = &$this->hargapokok;

		// p
		$this->p = new cField('produk', 'produk', 'x_p', 'p', '`p`', '`p`', 5, -1, FALSE, '`p`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->p->Sortable = TRUE; // Allow sort
		$this->p->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['p'] = &$this->p;

		// l
		$this->l = new cField('produk', 'produk', 'x_l', 'l', '`l`', '`l`', 5, -1, FALSE, '`l`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->l->Sortable = TRUE; // Allow sort
		$this->l->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['l'] = &$this->l;

		// t
		$this->t = new cField('produk', 'produk', 'x_t', 't', '`t`', '`t`', 5, -1, FALSE, '`t`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->t->Sortable = TRUE; // Allow sort
		$this->t->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['t'] = &$this->t;

		// berat
		$this->berat = new cField('produk', 'produk', 'x_berat', 'berat', '`berat`', '`berat`', 5, -1, FALSE, '`berat`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->berat->Sortable = TRUE; // Allow sort
		$this->berat->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['berat'] = &$this->berat;

		// supplier_id
		$this->supplier_id = new cField('produk', 'produk', 'x_supplier_id', 'supplier_id', '`supplier_id`', '`supplier_id`', 3, -1, FALSE, '`supplier_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->supplier_id->Sortable = TRUE; // Allow sort
		$this->supplier_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['supplier_id'] = &$this->supplier_id;

		// waktukirim
		$this->waktukirim = new cField('produk', 'produk', 'x_waktukirim', 'waktukirim', '`waktukirim`', '`waktukirim`', 3, -1, FALSE, '`waktukirim`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->waktukirim->Sortable = TRUE; // Allow sort
		$this->waktukirim->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['waktukirim'] = &$this->waktukirim;

		// aktif
		$this->aktif = new cField('produk', 'produk', 'x_aktif', 'aktif', '`aktif`', '`aktif`', 3, -1, FALSE, '`aktif`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->aktif->Sortable = TRUE; // Allow sort
		$this->aktif->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['aktif'] = &$this->aktif;

		// id_FK
		$this->id_FK = new cField('produk', 'produk', 'x_id_FK', 'id_FK', '`id_FK`', '`id_FK`', 3, -1, FALSE, '`id_FK`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id_FK->Sortable = TRUE; // Allow sort
		$this->id_FK->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_FK'] = &$this->id_FK;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`produk`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->id->setDbValue($conn->Insert_ID());
			$rs['id'] = $this->id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "produklist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "produklist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("produkview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("produkview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "produkadd.php?" . $this->UrlParm($parm);
		else
			$url = "produkadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("produkedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("produkadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("produkdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["id"]))
				$arKeys[] = ew_StripSlashes($_POST["id"]);
			elseif (isset($_GET["id"]))
				$arKeys[] = ew_StripSlashes($_GET["id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// kode
		$this->kode->EditAttrs["class"] = "form-control";
		$this->kode->EditCustomAttributes = "";
		$this->kode->EditValue = $this->kode->CurrentValue;
		$this->kode->PlaceHolder = ew_RemoveHtml($this->kode->FldCaption());

		// nama
		$this->nama->EditAttrs["class"] = "form-control";
		$this->nama->EditCustomAttributes = "";
		$this->nama->EditValue = $this->nama->CurrentValue;
		$this->nama->PlaceHolder = ew_RemoveHtml($this->nama->FldCaption());

		// kelompok_id
		$this->kelompok_id->EditAttrs["class"] = "form-control";
		$this->kelompok_id->EditCustomAttributes = "";
		$this->kelompok_id->EditValue = $this->kelompok_id->CurrentValue;
		$this->kelompok_id->PlaceHolder = ew_RemoveHtml($this->kelompok_id->FldCaption());

		// satuan_id
		$this->satuan_id->EditAttrs["class"] = "form-control";
		$this->satuan_id->EditCustomAttributes = "";
		$this->satuan_id->EditValue = $this->satuan_id->CurrentValue;
		$this->satuan_id->PlaceHolder = ew_RemoveHtml($this->satuan_id->FldCaption());

		// satuan_id2
		$this->satuan_id2->EditAttrs["class"] = "form-control";
		$this->satuan_id2->EditCustomAttributes = "";
		$this->satuan_id2->EditValue = $this->satuan_id2->CurrentValue;
		$this->satuan_id2->PlaceHolder = ew_RemoveHtml($this->satuan_id2->FldCaption());

		// gudang_id
		$this->gudang_id->EditAttrs["class"] = "form-control";
		$this->gudang_id->EditCustomAttributes = "";
		$this->gudang_id->EditValue = $this->gudang_id->CurrentValue;
		$this->gudang_id->PlaceHolder = ew_RemoveHtml($this->gudang_id->FldCaption());

		// minstok
		$this->minstok->EditAttrs["class"] = "form-control";
		$this->minstok->EditCustomAttributes = "";
		$this->minstok->EditValue = $this->minstok->CurrentValue;
		$this->minstok->PlaceHolder = ew_RemoveHtml($this->minstok->FldCaption());
		if (strval($this->minstok->EditValue) <> "" && is_numeric($this->minstok->EditValue)) $this->minstok->EditValue = ew_FormatNumber($this->minstok->EditValue, -2, -1, -2, 0);

		// minorder
		$this->minorder->EditAttrs["class"] = "form-control";
		$this->minorder->EditCustomAttributes = "";
		$this->minorder->EditValue = $this->minorder->CurrentValue;
		$this->minorder->PlaceHolder = ew_RemoveHtml($this->minorder->FldCaption());
		if (strval($this->minorder->EditValue) <> "" && is_numeric($this->minorder->EditValue)) $this->minorder->EditValue = ew_FormatNumber($this->minorder->EditValue, -2, -1, -2, 0);

		// akunhpp
		$this->akunhpp->EditAttrs["class"] = "form-control";
		$this->akunhpp->EditCustomAttributes = "";
		$this->akunhpp->EditValue = $this->akunhpp->CurrentValue;
		$this->akunhpp->PlaceHolder = ew_RemoveHtml($this->akunhpp->FldCaption());

		// akunjual
		$this->akunjual->EditAttrs["class"] = "form-control";
		$this->akunjual->EditCustomAttributes = "";
		$this->akunjual->EditValue = $this->akunjual->CurrentValue;
		$this->akunjual->PlaceHolder = ew_RemoveHtml($this->akunjual->FldCaption());

		// akunpersediaan
		$this->akunpersediaan->EditAttrs["class"] = "form-control";
		$this->akunpersediaan->EditCustomAttributes = "";
		$this->akunpersediaan->EditValue = $this->akunpersediaan->CurrentValue;
		$this->akunpersediaan->PlaceHolder = ew_RemoveHtml($this->akunpersediaan->FldCaption());

		// akunreturjual
		$this->akunreturjual->EditAttrs["class"] = "form-control";
		$this->akunreturjual->EditCustomAttributes = "";
		$this->akunreturjual->EditValue = $this->akunreturjual->CurrentValue;
		$this->akunreturjual->PlaceHolder = ew_RemoveHtml($this->akunreturjual->FldCaption());

		// hargapokok
		$this->hargapokok->EditAttrs["class"] = "form-control";
		$this->hargapokok->EditCustomAttributes = "";
		$this->hargapokok->EditValue = $this->hargapokok->CurrentValue;
		$this->hargapokok->PlaceHolder = ew_RemoveHtml($this->hargapokok->FldCaption());
		if (strval($this->hargapokok->EditValue) <> "" && is_numeric($this->hargapokok->EditValue)) $this->hargapokok->EditValue = ew_FormatNumber($this->hargapokok->EditValue, -2, -1, -2, 0);

		// p
		$this->p->EditAttrs["class"] = "form-control";
		$this->p->EditCustomAttributes = "";
		$this->p->EditValue = $this->p->CurrentValue;
		$this->p->PlaceHolder = ew_RemoveHtml($this->p->FldCaption());
		if (strval($this->p->EditValue) <> "" && is_numeric($this->p->EditValue)) $this->p->EditValue = ew_FormatNumber($this->p->EditValue, -2, -1, -2, 0);

		// l
		$this->l->EditAttrs["class"] = "form-control";
		$this->l->EditCustomAttributes = "";
		$this->l->EditValue = $this->l->CurrentValue;
		$this->l->PlaceHolder = ew_RemoveHtml($this->l->FldCaption());
		if (strval($this->l->EditValue) <> "" && is_numeric($this->l->EditValue)) $this->l->EditValue = ew_FormatNumber($this->l->EditValue, -2, -1, -2, 0);

		// t
		$this->t->EditAttrs["class"] = "form-control";
		$this->t->EditCustomAttributes = "";
		$this->t->EditValue = $this->t->CurrentValue;
		$this->t->PlaceHolder = ew_RemoveHtml($this->t->FldCaption());
		if (strval($this->t->EditValue) <> "" && is_numeric($this->t->EditValue)) $this->t->EditValue = ew_FormatNumber($this->t->EditValue, -2, -1, -2, 0);

		// berat
		$this->berat->EditAttrs["class"] = "form-control";
		$this->berat->EditCustomAttributes = "";
		$this->berat->EditValue = $this->berat->CurrentValue;
		$this->berat->PlaceHolder = ew_RemoveHtml($this->berat->FldCaption());
		if (strval($this->berat->EditValue) <> "" && is_numeric($this->berat->EditValue)) $this->berat->EditValue = ew_FormatNumber($this->berat->EditValue, -2, -1, -2, 0);

		// supplier_id
		$this->supplier_id->EditAttrs["class"] = "form-control";
		$this->supplier_id->EditCustomAttributes = "";
		$this->supplier_id->EditValue = $this->supplier_id->CurrentValue;
		$this->supplier_id->PlaceHolder = ew_RemoveHtml($this->supplier_id->FldCaption());

		// waktukirim
		$this->waktukirim->EditAttrs["class"] = "form-control";
		$this->waktukirim->EditCustomAttributes = "";
		$this->waktukirim->EditValue = $this->waktukirim->CurrentValue;
		$this->waktukirim->PlaceHolder = ew_RemoveHtml($this->waktukirim->FldCaption());

		// aktif
		$this->aktif->EditAttrs["class"] = "form-control";
		$this->aktif->EditCustomAttributes = "";
		$this->aktif->EditValue = $this->aktif->CurrentValue;
		$this->aktif->PlaceHolder = ew_RemoveHtml($this->aktif->FldCaption());

		// id_FK
		$this->id_FK->EditAttrs["class"] = "form-control";
		$this->id_FK->EditCustomAttributes = "";
		$this->id_FK->EditValue = $this->id_FK->CurrentValue;
		$this->id_FK->PlaceHolder = ew_RemoveHtml($this->id_FK->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->kode->Exportable) $Doc->ExportCaption($this->kode);
					if ($this->nama->Exportable) $Doc->ExportCaption($this->nama);
					if ($this->kelompok_id->Exportable) $Doc->ExportCaption($this->kelompok_id);
					if ($this->satuan_id->Exportable) $Doc->ExportCaption($this->satuan_id);
					if ($this->satuan_id2->Exportable) $Doc->ExportCaption($this->satuan_id2);
					if ($this->gudang_id->Exportable) $Doc->ExportCaption($this->gudang_id);
					if ($this->minstok->Exportable) $Doc->ExportCaption($this->minstok);
					if ($this->minorder->Exportable) $Doc->ExportCaption($this->minorder);
					if ($this->akunhpp->Exportable) $Doc->ExportCaption($this->akunhpp);
					if ($this->akunjual->Exportable) $Doc->ExportCaption($this->akunjual);
					if ($this->akunpersediaan->Exportable) $Doc->ExportCaption($this->akunpersediaan);
					if ($this->akunreturjual->Exportable) $Doc->ExportCaption($this->akunreturjual);
					if ($this->hargapokok->Exportable) $Doc->ExportCaption($this->hargapokok);
					if ($this->p->Exportable) $Doc->ExportCaption($this->p);
					if ($this->l->Exportable) $Doc->ExportCaption($this->l);
					if ($this->t->Exportable) $Doc->ExportCaption($this->t);
					if ($this->berat->Exportable) $Doc->ExportCaption($this->berat);
					if ($this->supplier_id->Exportable) $Doc->ExportCaption($this->supplier_id);
					if ($this->waktukirim->Exportable) $Doc->ExportCaption($this->waktukirim);
					if ($this->aktif->Exportable) $Doc->ExportCaption($this->aktif);
					if ($this->id_FK->Exportable) $Doc->ExportCaption($this->id_FK);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->kode->Exportable) $Doc->ExportCaption($this->kode);
					if ($this->nama->Exportable) $Doc->ExportCaption($this->nama);
					if ($this->kelompok_id->Exportable) $Doc->ExportCaption($this->kelompok_id);
					if ($this->satuan_id->Exportable) $Doc->ExportCaption($this->satuan_id);
					if ($this->satuan_id2->Exportable) $Doc->ExportCaption($this->satuan_id2);
					if ($this->gudang_id->Exportable) $Doc->ExportCaption($this->gudang_id);
					if ($this->minstok->Exportable) $Doc->ExportCaption($this->minstok);
					if ($this->minorder->Exportable) $Doc->ExportCaption($this->minorder);
					if ($this->akunhpp->Exportable) $Doc->ExportCaption($this->akunhpp);
					if ($this->akunjual->Exportable) $Doc->ExportCaption($this->akunjual);
					if ($this->akunpersediaan->Exportable) $Doc->ExportCaption($this->akunpersediaan);
					if ($this->akunreturjual->Exportable) $Doc->ExportCaption($this->akunreturjual);
					if ($this->hargapokok->Exportable) $Doc->ExportCaption($this->hargapokok);
					if ($this->p->Exportable) $Doc->ExportCaption($this->p);
					if ($this->l->Exportable) $Doc->ExportCaption($this->l);
					if ($this->t->Exportable) $Doc->ExportCaption($this->t);
					if ($this->berat->Exportable) $Doc->ExportCaption($this->berat);
					if ($this->supplier_id->Exportable) $Doc->ExportCaption($this->supplier_id);
					if ($this->waktukirim->Exportable) $Doc->ExportCaption($this->waktukirim);
					if ($this->aktif->Exportable) $Doc->ExportCaption($this->aktif);
					if ($this->id_FK->Exportable) $Doc->ExportCaption($this->id_FK);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->kode->Exportable) $Doc->ExportField($this->kode);
						if ($this->nama->Exportable) $Doc->ExportField($this->nama);
						if ($this->kelompok_id->Exportable) $Doc->ExportField($this->kelompok_id);
						if ($this->satuan_id->Exportable) $Doc->ExportField($this->satuan_id);
						if ($this->satuan_id2->Exportable) $Doc->ExportField($this->satuan_id2);
						if ($this->gudang_id->Exportable) $Doc->ExportField($this->gudang_id);
						if ($this->minstok->Exportable) $Doc->ExportField($this->minstok);
						if ($this->minorder->Exportable) $Doc->ExportField($this->minorder);
						if ($this->akunhpp->Exportable) $Doc->ExportField($this->akunhpp);
						if ($this->akunjual->Exportable) $Doc->ExportField($this->akunjual);
						if ($this->akunpersediaan->Exportable) $Doc->ExportField($this->akunpersediaan);
						if ($this->akunreturjual->Exportable) $Doc->ExportField($this->akunreturjual);
						if ($this->hargapokok->Exportable) $Doc->ExportField($this->hargapokok);
						if ($this->p->Exportable) $Doc->ExportField($this->p);
						if ($this->l->Exportable) $Doc->ExportField($this->l);
						if ($this->t->Exportable) $Doc->ExportField($this->t);
						if ($this->berat->Exportable) $Doc->ExportField($this->berat);
						if ($this->supplier_id->Exportable) $Doc->ExportField($this->supplier_id);
						if ($this->waktukirim->Exportable) $Doc->ExportField($this->waktukirim);
						if ($this->aktif->Exportable) $Doc->ExportField($this->aktif);
						if ($this->id_FK->Exportable) $Doc->ExportField($this->id_FK);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->kode->Exportable) $Doc->ExportField($this->kode);
						if ($this->nama->Exportable) $Doc->ExportField($this->nama);
						if ($this->kelompok_id->Exportable) $Doc->ExportField($this->kelompok_id);
						if ($this->satuan_id->Exportable) $Doc->ExportField($this->satuan_id);
						if ($this->satuan_id2->Exportable) $Doc->ExportField($this->satuan_id2);
						if ($this->gudang_id->Exportable) $Doc->ExportField($this->gudang_id);
						if ($this->minstok->Exportable) $Doc->ExportField($this->minstok);
						if ($this->minorder->Exportable) $Doc->ExportField($this->minorder);
						if ($this->akunhpp->Exportable) $Doc->ExportField($this->akunhpp);
						if ($this->akunjual->Exportable) $Doc->ExportField($this->akunjual);
						if ($this->akunpersediaan->Exportable) $Doc->ExportField($this->akunpersediaan);
						if ($this->akunreturjual->Exportable) $Doc->ExportField($this->akunreturjual);
						if ($this->hargapokok->Exportable) $Doc->ExportField($this->hargapokok);
						if ($this->p->Exportable) $Doc->ExportField($this->p);
						if ($this->l->Exportable) $Doc->ExportField($this->l);
						if ($this->t->Exportable) $Doc->ExportField($this->t);
						if ($this->berat->Exportable) $Doc->ExportField($this->berat);
						if ($this->supplier_id->Exportable) $Doc->ExportField($this->supplier_id);
						if ($this->waktukirim->Exportable) $Doc->ExportField($this->waktukirim);
						if ($this->aktif->Exportable) $Doc->ExportField($this->aktif);
						if ($this->id_FK->Exportable) $Doc->ExportField($this->id_FK);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
