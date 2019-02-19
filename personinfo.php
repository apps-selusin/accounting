<?php

// Global variable for table object
$person = NULL;

//
// Table class for person
//
class cperson extends cTable {
	var $id;
	var $kode;
	var $nama;
	var $kontak;
	var $type_id;
	var $telp1;
	var $matauang_id;
	var $username;
	var $password;
	var $telp2;
	var $fax;
	var $hp;
	var $_email;
	var $website;
	var $npwp;
	var $alamat;
	var $kota;
	var $zip;
	var $klasifikasi_id;
	var $id_FK;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'person';
		$this->TableName = 'person';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`person`";
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
		$this->id = new cField('person', 'person', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// kode
		$this->kode = new cField('person', 'person', 'x_kode', 'kode', '`kode`', '`kode`', 200, -1, FALSE, '`kode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kode->Sortable = TRUE; // Allow sort
		$this->fields['kode'] = &$this->kode;

		// nama
		$this->nama = new cField('person', 'person', 'x_nama', 'nama', '`nama`', '`nama`', 200, -1, FALSE, '`nama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nama->Sortable = TRUE; // Allow sort
		$this->fields['nama'] = &$this->nama;

		// kontak
		$this->kontak = new cField('person', 'person', 'x_kontak', 'kontak', '`kontak`', '`kontak`', 200, -1, FALSE, '`kontak`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kontak->Sortable = TRUE; // Allow sort
		$this->fields['kontak'] = &$this->kontak;

		// type_id
		$this->type_id = new cField('person', 'person', 'x_type_id', 'type_id', '`type_id`', '`type_id`', 3, -1, FALSE, '`type_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->type_id->Sortable = TRUE; // Allow sort
		$this->type_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['type_id'] = &$this->type_id;

		// telp1
		$this->telp1 = new cField('person', 'person', 'x_telp1', 'telp1', '`telp1`', '`telp1`', 200, -1, FALSE, '`telp1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->telp1->Sortable = TRUE; // Allow sort
		$this->fields['telp1'] = &$this->telp1;

		// matauang_id
		$this->matauang_id = new cField('person', 'person', 'x_matauang_id', 'matauang_id', '`matauang_id`', '`matauang_id`', 3, -1, FALSE, '`matauang_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->matauang_id->Sortable = TRUE; // Allow sort
		$this->matauang_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['matauang_id'] = &$this->matauang_id;

		// username
		$this->username = new cField('person', 'person', 'x_username', 'username', '`username`', '`username`', 200, -1, FALSE, '`username`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->username->Sortable = TRUE; // Allow sort
		$this->fields['username'] = &$this->username;

		// password
		$this->password = new cField('person', 'person', 'x_password', 'password', '`password`', '`password`', 200, -1, FALSE, '`password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->password->Sortable = TRUE; // Allow sort
		$this->fields['password'] = &$this->password;

		// telp2
		$this->telp2 = new cField('person', 'person', 'x_telp2', 'telp2', '`telp2`', '`telp2`', 200, -1, FALSE, '`telp2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->telp2->Sortable = TRUE; // Allow sort
		$this->fields['telp2'] = &$this->telp2;

		// fax
		$this->fax = new cField('person', 'person', 'x_fax', 'fax', '`fax`', '`fax`', 200, -1, FALSE, '`fax`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fax->Sortable = TRUE; // Allow sort
		$this->fields['fax'] = &$this->fax;

		// hp
		$this->hp = new cField('person', 'person', 'x_hp', 'hp', '`hp`', '`hp`', 200, -1, FALSE, '`hp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hp->Sortable = TRUE; // Allow sort
		$this->fields['hp'] = &$this->hp;

		// email
		$this->_email = new cField('person', 'person', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// website
		$this->website = new cField('person', 'person', 'x_website', 'website', '`website`', '`website`', 200, -1, FALSE, '`website`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->website->Sortable = TRUE; // Allow sort
		$this->fields['website'] = &$this->website;

		// npwp
		$this->npwp = new cField('person', 'person', 'x_npwp', 'npwp', '`npwp`', '`npwp`', 200, -1, FALSE, '`npwp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->npwp->Sortable = TRUE; // Allow sort
		$this->fields['npwp'] = &$this->npwp;

		// alamat
		$this->alamat = new cField('person', 'person', 'x_alamat', 'alamat', '`alamat`', '`alamat`', 200, -1, FALSE, '`alamat`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->alamat->Sortable = TRUE; // Allow sort
		$this->fields['alamat'] = &$this->alamat;

		// kota
		$this->kota = new cField('person', 'person', 'x_kota', 'kota', '`kota`', '`kota`', 200, -1, FALSE, '`kota`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kota->Sortable = TRUE; // Allow sort
		$this->fields['kota'] = &$this->kota;

		// zip
		$this->zip = new cField('person', 'person', 'x_zip', 'zip', '`zip`', '`zip`', 200, -1, FALSE, '`zip`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->zip->Sortable = TRUE; // Allow sort
		$this->fields['zip'] = &$this->zip;

		// klasifikasi_id
		$this->klasifikasi_id = new cField('person', 'person', 'x_klasifikasi_id', 'klasifikasi_id', '`klasifikasi_id`', '`klasifikasi_id`', 3, -1, FALSE, '`klasifikasi_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->klasifikasi_id->Sortable = TRUE; // Allow sort
		$this->klasifikasi_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['klasifikasi_id'] = &$this->klasifikasi_id;

		// id_FK
		$this->id_FK = new cField('person', 'person', 'x_id_FK', 'id_FK', '`id_FK`', '`id_FK`', 3, -1, FALSE, '`id_FK`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`person`";
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
			return "personlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "personlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("personview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("personview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "personadd.php?" . $this->UrlParm($parm);
		else
			$url = "personadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("personedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("personadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("persondelete.php", $this->UrlParm());
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// kontak
		$this->kontak->EditAttrs["class"] = "form-control";
		$this->kontak->EditCustomAttributes = "";
		$this->kontak->EditValue = $this->kontak->CurrentValue;
		$this->kontak->PlaceHolder = ew_RemoveHtml($this->kontak->FldCaption());

		// type_id
		$this->type_id->EditAttrs["class"] = "form-control";
		$this->type_id->EditCustomAttributes = "";
		$this->type_id->EditValue = $this->type_id->CurrentValue;
		$this->type_id->PlaceHolder = ew_RemoveHtml($this->type_id->FldCaption());

		// telp1
		$this->telp1->EditAttrs["class"] = "form-control";
		$this->telp1->EditCustomAttributes = "";
		$this->telp1->EditValue = $this->telp1->CurrentValue;
		$this->telp1->PlaceHolder = ew_RemoveHtml($this->telp1->FldCaption());

		// matauang_id
		$this->matauang_id->EditAttrs["class"] = "form-control";
		$this->matauang_id->EditCustomAttributes = "";
		$this->matauang_id->EditValue = $this->matauang_id->CurrentValue;
		$this->matauang_id->PlaceHolder = ew_RemoveHtml($this->matauang_id->FldCaption());

		// username
		$this->username->EditAttrs["class"] = "form-control";
		$this->username->EditCustomAttributes = "";
		$this->username->EditValue = $this->username->CurrentValue;
		$this->username->PlaceHolder = ew_RemoveHtml($this->username->FldCaption());

		// password
		$this->password->EditAttrs["class"] = "form-control";
		$this->password->EditCustomAttributes = "";
		$this->password->EditValue = $this->password->CurrentValue;
		$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

		// telp2
		$this->telp2->EditAttrs["class"] = "form-control";
		$this->telp2->EditCustomAttributes = "";
		$this->telp2->EditValue = $this->telp2->CurrentValue;
		$this->telp2->PlaceHolder = ew_RemoveHtml($this->telp2->FldCaption());

		// fax
		$this->fax->EditAttrs["class"] = "form-control";
		$this->fax->EditCustomAttributes = "";
		$this->fax->EditValue = $this->fax->CurrentValue;
		$this->fax->PlaceHolder = ew_RemoveHtml($this->fax->FldCaption());

		// hp
		$this->hp->EditAttrs["class"] = "form-control";
		$this->hp->EditCustomAttributes = "";
		$this->hp->EditValue = $this->hp->CurrentValue;
		$this->hp->PlaceHolder = ew_RemoveHtml($this->hp->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// website
		$this->website->EditAttrs["class"] = "form-control";
		$this->website->EditCustomAttributes = "";
		$this->website->EditValue = $this->website->CurrentValue;
		$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

		// npwp
		$this->npwp->EditAttrs["class"] = "form-control";
		$this->npwp->EditCustomAttributes = "";
		$this->npwp->EditValue = $this->npwp->CurrentValue;
		$this->npwp->PlaceHolder = ew_RemoveHtml($this->npwp->FldCaption());

		// alamat
		$this->alamat->EditAttrs["class"] = "form-control";
		$this->alamat->EditCustomAttributes = "";
		$this->alamat->EditValue = $this->alamat->CurrentValue;
		$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

		// kota
		$this->kota->EditAttrs["class"] = "form-control";
		$this->kota->EditCustomAttributes = "";
		$this->kota->EditValue = $this->kota->CurrentValue;
		$this->kota->PlaceHolder = ew_RemoveHtml($this->kota->FldCaption());

		// zip
		$this->zip->EditAttrs["class"] = "form-control";
		$this->zip->EditCustomAttributes = "";
		$this->zip->EditValue = $this->zip->CurrentValue;
		$this->zip->PlaceHolder = ew_RemoveHtml($this->zip->FldCaption());

		// klasifikasi_id
		$this->klasifikasi_id->EditAttrs["class"] = "form-control";
		$this->klasifikasi_id->EditCustomAttributes = "";
		$this->klasifikasi_id->EditValue = $this->klasifikasi_id->CurrentValue;
		$this->klasifikasi_id->PlaceHolder = ew_RemoveHtml($this->klasifikasi_id->FldCaption());

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
					if ($this->kontak->Exportable) $Doc->ExportCaption($this->kontak);
					if ($this->type_id->Exportable) $Doc->ExportCaption($this->type_id);
					if ($this->telp1->Exportable) $Doc->ExportCaption($this->telp1);
					if ($this->matauang_id->Exportable) $Doc->ExportCaption($this->matauang_id);
					if ($this->username->Exportable) $Doc->ExportCaption($this->username);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->telp2->Exportable) $Doc->ExportCaption($this->telp2);
					if ($this->fax->Exportable) $Doc->ExportCaption($this->fax);
					if ($this->hp->Exportable) $Doc->ExportCaption($this->hp);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->website->Exportable) $Doc->ExportCaption($this->website);
					if ($this->npwp->Exportable) $Doc->ExportCaption($this->npwp);
					if ($this->alamat->Exportable) $Doc->ExportCaption($this->alamat);
					if ($this->kota->Exportable) $Doc->ExportCaption($this->kota);
					if ($this->zip->Exportable) $Doc->ExportCaption($this->zip);
					if ($this->klasifikasi_id->Exportable) $Doc->ExportCaption($this->klasifikasi_id);
					if ($this->id_FK->Exportable) $Doc->ExportCaption($this->id_FK);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->kode->Exportable) $Doc->ExportCaption($this->kode);
					if ($this->nama->Exportable) $Doc->ExportCaption($this->nama);
					if ($this->kontak->Exportable) $Doc->ExportCaption($this->kontak);
					if ($this->type_id->Exportable) $Doc->ExportCaption($this->type_id);
					if ($this->telp1->Exportable) $Doc->ExportCaption($this->telp1);
					if ($this->matauang_id->Exportable) $Doc->ExportCaption($this->matauang_id);
					if ($this->username->Exportable) $Doc->ExportCaption($this->username);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->telp2->Exportable) $Doc->ExportCaption($this->telp2);
					if ($this->fax->Exportable) $Doc->ExportCaption($this->fax);
					if ($this->hp->Exportable) $Doc->ExportCaption($this->hp);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->website->Exportable) $Doc->ExportCaption($this->website);
					if ($this->npwp->Exportable) $Doc->ExportCaption($this->npwp);
					if ($this->alamat->Exportable) $Doc->ExportCaption($this->alamat);
					if ($this->kota->Exportable) $Doc->ExportCaption($this->kota);
					if ($this->zip->Exportable) $Doc->ExportCaption($this->zip);
					if ($this->klasifikasi_id->Exportable) $Doc->ExportCaption($this->klasifikasi_id);
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
						if ($this->kontak->Exportable) $Doc->ExportField($this->kontak);
						if ($this->type_id->Exportable) $Doc->ExportField($this->type_id);
						if ($this->telp1->Exportable) $Doc->ExportField($this->telp1);
						if ($this->matauang_id->Exportable) $Doc->ExportField($this->matauang_id);
						if ($this->username->Exportable) $Doc->ExportField($this->username);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->telp2->Exportable) $Doc->ExportField($this->telp2);
						if ($this->fax->Exportable) $Doc->ExportField($this->fax);
						if ($this->hp->Exportable) $Doc->ExportField($this->hp);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->website->Exportable) $Doc->ExportField($this->website);
						if ($this->npwp->Exportable) $Doc->ExportField($this->npwp);
						if ($this->alamat->Exportable) $Doc->ExportField($this->alamat);
						if ($this->kota->Exportable) $Doc->ExportField($this->kota);
						if ($this->zip->Exportable) $Doc->ExportField($this->zip);
						if ($this->klasifikasi_id->Exportable) $Doc->ExportField($this->klasifikasi_id);
						if ($this->id_FK->Exportable) $Doc->ExportField($this->id_FK);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->kode->Exportable) $Doc->ExportField($this->kode);
						if ($this->nama->Exportable) $Doc->ExportField($this->nama);
						if ($this->kontak->Exportable) $Doc->ExportField($this->kontak);
						if ($this->type_id->Exportable) $Doc->ExportField($this->type_id);
						if ($this->telp1->Exportable) $Doc->ExportField($this->telp1);
						if ($this->matauang_id->Exportable) $Doc->ExportField($this->matauang_id);
						if ($this->username->Exportable) $Doc->ExportField($this->username);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->telp2->Exportable) $Doc->ExportField($this->telp2);
						if ($this->fax->Exportable) $Doc->ExportField($this->fax);
						if ($this->hp->Exportable) $Doc->ExportField($this->hp);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->website->Exportable) $Doc->ExportField($this->website);
						if ($this->npwp->Exportable) $Doc->ExportField($this->npwp);
						if ($this->alamat->Exportable) $Doc->ExportField($this->alamat);
						if ($this->kota->Exportable) $Doc->ExportField($this->kota);
						if ($this->zip->Exportable) $Doc->ExportField($this->zip);
						if ($this->klasifikasi_id->Exportable) $Doc->ExportField($this->klasifikasi_id);
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
