<?php

// Create page object
if (!isset($jurnald_grid)) $jurnald_grid = new cjurnald_grid();

// Page init
$jurnald_grid->Page_Init();

// Page main
$jurnald_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jurnald_grid->Page_Render();
?>
<?php if ($jurnald->Export == "") { ?>
<script type="text/javascript">

// Form object
var fjurnaldgrid = new ew_Form("fjurnaldgrid", "grid");
fjurnaldgrid.FormKeyCountName = '<?php echo $jurnald_grid->FormKeyCountName ?>';

// Validate form
fjurnaldgrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_jurnal_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jurnald->jurnal_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jurnald->debet->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kredit");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jurnald->kredit->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fjurnaldgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "jurnal_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "akun_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "debet", false)) return false;
	if (ew_ValueChanged(fobj, infix, "kredit", false)) return false;
	return true;
}

// Form_CustomValidate event
fjurnaldgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjurnaldgrid.ValidateRequired = true;
<?php } else { ?>
fjurnaldgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fjurnaldgrid.Lists["x_akun_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"akun"};

// Form object for search
</script>
<?php } ?>
<?php
if ($jurnald->CurrentAction == "gridadd") {
	if ($jurnald->CurrentMode == "copy") {
		$bSelectLimit = $jurnald_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$jurnald_grid->TotalRecs = $jurnald->SelectRecordCount();
			$jurnald_grid->Recordset = $jurnald_grid->LoadRecordset($jurnald_grid->StartRec-1, $jurnald_grid->DisplayRecs);
		} else {
			if ($jurnald_grid->Recordset = $jurnald_grid->LoadRecordset())
				$jurnald_grid->TotalRecs = $jurnald_grid->Recordset->RecordCount();
		}
		$jurnald_grid->StartRec = 1;
		$jurnald_grid->DisplayRecs = $jurnald_grid->TotalRecs;
	} else {
		$jurnald->CurrentFilter = "0=1";
		$jurnald_grid->StartRec = 1;
		$jurnald_grid->DisplayRecs = $jurnald->GridAddRowCount;
	}
	$jurnald_grid->TotalRecs = $jurnald_grid->DisplayRecs;
	$jurnald_grid->StopRec = $jurnald_grid->DisplayRecs;
} else {
	$bSelectLimit = $jurnald_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($jurnald_grid->TotalRecs <= 0)
			$jurnald_grid->TotalRecs = $jurnald->SelectRecordCount();
	} else {
		if (!$jurnald_grid->Recordset && ($jurnald_grid->Recordset = $jurnald_grid->LoadRecordset()))
			$jurnald_grid->TotalRecs = $jurnald_grid->Recordset->RecordCount();
	}
	$jurnald_grid->StartRec = 1;
	$jurnald_grid->DisplayRecs = $jurnald_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$jurnald_grid->Recordset = $jurnald_grid->LoadRecordset($jurnald_grid->StartRec-1, $jurnald_grid->DisplayRecs);

	// Set no record found message
	if ($jurnald->CurrentAction == "" && $jurnald_grid->TotalRecs == 0) {
		if ($jurnald_grid->SearchWhere == "0=101")
			$jurnald_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$jurnald_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$jurnald_grid->RenderOtherOptions();
?>
<?php $jurnald_grid->ShowPageHeader(); ?>
<?php
$jurnald_grid->ShowMessage();
?>
<?php if ($jurnald_grid->TotalRecs > 0 || $jurnald->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid jurnald">
<div id="fjurnaldgrid" class="ewForm form-inline">
<div id="gmp_jurnald" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_jurnaldgrid" class="table ewTable">
<?php echo $jurnald->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$jurnald_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$jurnald_grid->RenderListOptions();

// Render list options (header, left)
$jurnald_grid->ListOptions->Render("header", "left");
?>
<?php if ($jurnald->id->Visible) { // id ?>
	<?php if ($jurnald->SortUrl($jurnald->id) == "") { ?>
		<th data-name="id"><div id="elh_jurnald_id" class="jurnald_id"><div class="ewTableHeaderCaption"><?php echo $jurnald->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div><div id="elh_jurnald_id" class="jurnald_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jurnald->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jurnald->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jurnald->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jurnald->jurnal_id->Visible) { // jurnal_id ?>
	<?php if ($jurnald->SortUrl($jurnald->jurnal_id) == "") { ?>
		<th data-name="jurnal_id"><div id="elh_jurnald_jurnal_id" class="jurnald_jurnal_id"><div class="ewTableHeaderCaption"><?php echo $jurnald->jurnal_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jurnal_id"><div><div id="elh_jurnald_jurnal_id" class="jurnald_jurnal_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jurnald->jurnal_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jurnald->jurnal_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jurnald->jurnal_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jurnald->akun_id->Visible) { // akun_id ?>
	<?php if ($jurnald->SortUrl($jurnald->akun_id) == "") { ?>
		<th data-name="akun_id"><div id="elh_jurnald_akun_id" class="jurnald_akun_id"><div class="ewTableHeaderCaption"><?php echo $jurnald->akun_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="akun_id"><div><div id="elh_jurnald_akun_id" class="jurnald_akun_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jurnald->akun_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jurnald->akun_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jurnald->akun_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jurnald->debet->Visible) { // debet ?>
	<?php if ($jurnald->SortUrl($jurnald->debet) == "") { ?>
		<th data-name="debet"><div id="elh_jurnald_debet" class="jurnald_debet"><div class="ewTableHeaderCaption"><?php echo $jurnald->debet->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet"><div><div id="elh_jurnald_debet" class="jurnald_debet">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jurnald->debet->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jurnald->debet->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jurnald->debet->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($jurnald->kredit->Visible) { // kredit ?>
	<?php if ($jurnald->SortUrl($jurnald->kredit) == "") { ?>
		<th data-name="kredit"><div id="elh_jurnald_kredit" class="jurnald_kredit"><div class="ewTableHeaderCaption"><?php echo $jurnald->kredit->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kredit"><div><div id="elh_jurnald_kredit" class="jurnald_kredit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $jurnald->kredit->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($jurnald->kredit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($jurnald->kredit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$jurnald_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$jurnald_grid->StartRec = 1;
$jurnald_grid->StopRec = $jurnald_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($jurnald_grid->FormKeyCountName) && ($jurnald->CurrentAction == "gridadd" || $jurnald->CurrentAction == "gridedit" || $jurnald->CurrentAction == "F")) {
		$jurnald_grid->KeyCount = $objForm->GetValue($jurnald_grid->FormKeyCountName);
		$jurnald_grid->StopRec = $jurnald_grid->StartRec + $jurnald_grid->KeyCount - 1;
	}
}
$jurnald_grid->RecCnt = $jurnald_grid->StartRec - 1;
if ($jurnald_grid->Recordset && !$jurnald_grid->Recordset->EOF) {
	$jurnald_grid->Recordset->MoveFirst();
	$bSelectLimit = $jurnald_grid->UseSelectLimit;
	if (!$bSelectLimit && $jurnald_grid->StartRec > 1)
		$jurnald_grid->Recordset->Move($jurnald_grid->StartRec - 1);
} elseif (!$jurnald->AllowAddDeleteRow && $jurnald_grid->StopRec == 0) {
	$jurnald_grid->StopRec = $jurnald->GridAddRowCount;
}

// Initialize aggregate
$jurnald->RowType = EW_ROWTYPE_AGGREGATEINIT;
$jurnald->ResetAttrs();
$jurnald_grid->RenderRow();
if ($jurnald->CurrentAction == "gridadd")
	$jurnald_grid->RowIndex = 0;
if ($jurnald->CurrentAction == "gridedit")
	$jurnald_grid->RowIndex = 0;
while ($jurnald_grid->RecCnt < $jurnald_grid->StopRec) {
	$jurnald_grid->RecCnt++;
	if (intval($jurnald_grid->RecCnt) >= intval($jurnald_grid->StartRec)) {
		$jurnald_grid->RowCnt++;
		if ($jurnald->CurrentAction == "gridadd" || $jurnald->CurrentAction == "gridedit" || $jurnald->CurrentAction == "F") {
			$jurnald_grid->RowIndex++;
			$objForm->Index = $jurnald_grid->RowIndex;
			if ($objForm->HasValue($jurnald_grid->FormActionName))
				$jurnald_grid->RowAction = strval($objForm->GetValue($jurnald_grid->FormActionName));
			elseif ($jurnald->CurrentAction == "gridadd")
				$jurnald_grid->RowAction = "insert";
			else
				$jurnald_grid->RowAction = "";
		}

		// Set up key count
		$jurnald_grid->KeyCount = $jurnald_grid->RowIndex;

		// Init row class and style
		$jurnald->ResetAttrs();
		$jurnald->CssClass = "";
		if ($jurnald->CurrentAction == "gridadd") {
			if ($jurnald->CurrentMode == "copy") {
				$jurnald_grid->LoadRowValues($jurnald_grid->Recordset); // Load row values
				$jurnald_grid->SetRecordKey($jurnald_grid->RowOldKey, $jurnald_grid->Recordset); // Set old record key
			} else {
				$jurnald_grid->LoadDefaultValues(); // Load default values
				$jurnald_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$jurnald_grid->LoadRowValues($jurnald_grid->Recordset); // Load row values
		}
		$jurnald->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($jurnald->CurrentAction == "gridadd") // Grid add
			$jurnald->RowType = EW_ROWTYPE_ADD; // Render add
		if ($jurnald->CurrentAction == "gridadd" && $jurnald->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$jurnald_grid->RestoreCurrentRowFormValues($jurnald_grid->RowIndex); // Restore form values
		if ($jurnald->CurrentAction == "gridedit") { // Grid edit
			if ($jurnald->EventCancelled) {
				$jurnald_grid->RestoreCurrentRowFormValues($jurnald_grid->RowIndex); // Restore form values
			}
			if ($jurnald_grid->RowAction == "insert")
				$jurnald->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$jurnald->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($jurnald->CurrentAction == "gridedit" && ($jurnald->RowType == EW_ROWTYPE_EDIT || $jurnald->RowType == EW_ROWTYPE_ADD) && $jurnald->EventCancelled) // Update failed
			$jurnald_grid->RestoreCurrentRowFormValues($jurnald_grid->RowIndex); // Restore form values
		if ($jurnald->RowType == EW_ROWTYPE_EDIT) // Edit row
			$jurnald_grid->EditRowCnt++;
		if ($jurnald->CurrentAction == "F") // Confirm row
			$jurnald_grid->RestoreCurrentRowFormValues($jurnald_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$jurnald->RowAttrs = array_merge($jurnald->RowAttrs, array('data-rowindex'=>$jurnald_grid->RowCnt, 'id'=>'r' . $jurnald_grid->RowCnt . '_jurnald', 'data-rowtype'=>$jurnald->RowType));

		// Render row
		$jurnald_grid->RenderRow();

		// Render list options
		$jurnald_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($jurnald_grid->RowAction <> "delete" && $jurnald_grid->RowAction <> "insertdelete" && !($jurnald_grid->RowAction == "insert" && $jurnald->CurrentAction == "F" && $jurnald_grid->EmptyRow())) {
?>
	<tr<?php echo $jurnald->RowAttributes() ?>>
<?php

// Render list options (body, left)
$jurnald_grid->ListOptions->Render("body", "left", $jurnald_grid->RowCnt);
?>
	<?php if ($jurnald->id->Visible) { // id ?>
		<td data-name="id"<?php echo $jurnald->id->CellAttributes() ?>>
<?php if ($jurnald->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="jurnald" data-field="x_id" name="o<?php echo $jurnald_grid->RowIndex ?>_id" id="o<?php echo $jurnald_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jurnald->id->OldValue) ?>">
<?php } ?>
<?php if ($jurnald->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_id" class="form-group jurnald_id">
<span<?php echo $jurnald->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jurnald->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="jurnald" data-field="x_id" name="x<?php echo $jurnald_grid->RowIndex ?>_id" id="x<?php echo $jurnald_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jurnald->id->CurrentValue) ?>">
<?php } ?>
<?php if ($jurnald->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_id" class="jurnald_id">
<span<?php echo $jurnald->id->ViewAttributes() ?>>
<?php echo $jurnald->id->ListViewValue() ?></span>
</span>
<?php if ($jurnald->CurrentAction <> "F") { ?>
<input type="hidden" data-table="jurnald" data-field="x_id" name="x<?php echo $jurnald_grid->RowIndex ?>_id" id="x<?php echo $jurnald_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jurnald->id->FormValue) ?>">
<input type="hidden" data-table="jurnald" data-field="x_id" name="o<?php echo $jurnald_grid->RowIndex ?>_id" id="o<?php echo $jurnald_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jurnald->id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="jurnald" data-field="x_id" name="fjurnaldgrid$x<?php echo $jurnald_grid->RowIndex ?>_id" id="fjurnaldgrid$x<?php echo $jurnald_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jurnald->id->FormValue) ?>">
<input type="hidden" data-table="jurnald" data-field="x_id" name="fjurnaldgrid$o<?php echo $jurnald_grid->RowIndex ?>_id" id="fjurnaldgrid$o<?php echo $jurnald_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jurnald->id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $jurnald_grid->PageObjName . "_row_" . $jurnald_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($jurnald->jurnal_id->Visible) { // jurnal_id ?>
		<td data-name="jurnal_id"<?php echo $jurnald->jurnal_id->CellAttributes() ?>>
<?php if ($jurnald->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($jurnald->jurnal_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_jurnal_id" class="form-group jurnald_jurnal_id">
<span<?php echo $jurnald->jurnal_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jurnald->jurnal_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" name="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" value="<?php echo ew_HtmlEncode($jurnald->jurnal_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_jurnal_id" class="form-group jurnald_jurnal_id">
<input type="text" data-table="jurnald" data-field="x_jurnal_id" name="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" id="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" size="30" placeholder="<?php echo ew_HtmlEncode($jurnald->jurnal_id->getPlaceHolder()) ?>" value="<?php echo $jurnald->jurnal_id->EditValue ?>"<?php echo $jurnald->jurnal_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="jurnald" data-field="x_jurnal_id" name="o<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" id="o<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" value="<?php echo ew_HtmlEncode($jurnald->jurnal_id->OldValue) ?>">
<?php } ?>
<?php if ($jurnald->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($jurnald->jurnal_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_jurnal_id" class="form-group jurnald_jurnal_id">
<span<?php echo $jurnald->jurnal_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jurnald->jurnal_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" name="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" value="<?php echo ew_HtmlEncode($jurnald->jurnal_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_jurnal_id" class="form-group jurnald_jurnal_id">
<input type="text" data-table="jurnald" data-field="x_jurnal_id" name="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" id="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" size="30" placeholder="<?php echo ew_HtmlEncode($jurnald->jurnal_id->getPlaceHolder()) ?>" value="<?php echo $jurnald->jurnal_id->EditValue ?>"<?php echo $jurnald->jurnal_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($jurnald->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_jurnal_id" class="jurnald_jurnal_id">
<span<?php echo $jurnald->jurnal_id->ViewAttributes() ?>>
<?php echo $jurnald->jurnal_id->ListViewValue() ?></span>
</span>
<?php if ($jurnald->CurrentAction <> "F") { ?>
<input type="hidden" data-table="jurnald" data-field="x_jurnal_id" name="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" id="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" value="<?php echo ew_HtmlEncode($jurnald->jurnal_id->FormValue) ?>">
<input type="hidden" data-table="jurnald" data-field="x_jurnal_id" name="o<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" id="o<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" value="<?php echo ew_HtmlEncode($jurnald->jurnal_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="jurnald" data-field="x_jurnal_id" name="fjurnaldgrid$x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" id="fjurnaldgrid$x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" value="<?php echo ew_HtmlEncode($jurnald->jurnal_id->FormValue) ?>">
<input type="hidden" data-table="jurnald" data-field="x_jurnal_id" name="fjurnaldgrid$o<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" id="fjurnaldgrid$o<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" value="<?php echo ew_HtmlEncode($jurnald->jurnal_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($jurnald->akun_id->Visible) { // akun_id ?>
		<td data-name="akun_id"<?php echo $jurnald->akun_id->CellAttributes() ?>>
<?php if ($jurnald->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_akun_id" class="form-group jurnald_akun_id">
<select data-table="jurnald" data-field="x_akun_id" data-value-separator="<?php echo $jurnald->akun_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $jurnald_grid->RowIndex ?>_akun_id" name="x<?php echo $jurnald_grid->RowIndex ?>_akun_id"<?php echo $jurnald->akun_id->EditAttributes() ?>>
<?php echo $jurnald->akun_id->SelectOptionListHtml("x<?php echo $jurnald_grid->RowIndex ?>_akun_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $jurnald_grid->RowIndex ?>_akun_id" id="s_x<?php echo $jurnald_grid->RowIndex ?>_akun_id" value="<?php echo $jurnald->akun_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="jurnald" data-field="x_akun_id" name="o<?php echo $jurnald_grid->RowIndex ?>_akun_id" id="o<?php echo $jurnald_grid->RowIndex ?>_akun_id" value="<?php echo ew_HtmlEncode($jurnald->akun_id->OldValue) ?>">
<?php } ?>
<?php if ($jurnald->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_akun_id" class="form-group jurnald_akun_id">
<select data-table="jurnald" data-field="x_akun_id" data-value-separator="<?php echo $jurnald->akun_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $jurnald_grid->RowIndex ?>_akun_id" name="x<?php echo $jurnald_grid->RowIndex ?>_akun_id"<?php echo $jurnald->akun_id->EditAttributes() ?>>
<?php echo $jurnald->akun_id->SelectOptionListHtml("x<?php echo $jurnald_grid->RowIndex ?>_akun_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $jurnald_grid->RowIndex ?>_akun_id" id="s_x<?php echo $jurnald_grid->RowIndex ?>_akun_id" value="<?php echo $jurnald->akun_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($jurnald->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_akun_id" class="jurnald_akun_id">
<span<?php echo $jurnald->akun_id->ViewAttributes() ?>>
<?php echo $jurnald->akun_id->ListViewValue() ?></span>
</span>
<?php if ($jurnald->CurrentAction <> "F") { ?>
<input type="hidden" data-table="jurnald" data-field="x_akun_id" name="x<?php echo $jurnald_grid->RowIndex ?>_akun_id" id="x<?php echo $jurnald_grid->RowIndex ?>_akun_id" value="<?php echo ew_HtmlEncode($jurnald->akun_id->FormValue) ?>">
<input type="hidden" data-table="jurnald" data-field="x_akun_id" name="o<?php echo $jurnald_grid->RowIndex ?>_akun_id" id="o<?php echo $jurnald_grid->RowIndex ?>_akun_id" value="<?php echo ew_HtmlEncode($jurnald->akun_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="jurnald" data-field="x_akun_id" name="fjurnaldgrid$x<?php echo $jurnald_grid->RowIndex ?>_akun_id" id="fjurnaldgrid$x<?php echo $jurnald_grid->RowIndex ?>_akun_id" value="<?php echo ew_HtmlEncode($jurnald->akun_id->FormValue) ?>">
<input type="hidden" data-table="jurnald" data-field="x_akun_id" name="fjurnaldgrid$o<?php echo $jurnald_grid->RowIndex ?>_akun_id" id="fjurnaldgrid$o<?php echo $jurnald_grid->RowIndex ?>_akun_id" value="<?php echo ew_HtmlEncode($jurnald->akun_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($jurnald->debet->Visible) { // debet ?>
		<td data-name="debet"<?php echo $jurnald->debet->CellAttributes() ?>>
<?php if ($jurnald->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_debet" class="form-group jurnald_debet">
<input type="text" data-table="jurnald" data-field="x_debet" name="x<?php echo $jurnald_grid->RowIndex ?>_debet" id="x<?php echo $jurnald_grid->RowIndex ?>_debet" size="30" placeholder="<?php echo ew_HtmlEncode($jurnald->debet->getPlaceHolder()) ?>" value="<?php echo $jurnald->debet->EditValue ?>"<?php echo $jurnald->debet->EditAttributes() ?>>
</span>
<input type="hidden" data-table="jurnald" data-field="x_debet" name="o<?php echo $jurnald_grid->RowIndex ?>_debet" id="o<?php echo $jurnald_grid->RowIndex ?>_debet" value="<?php echo ew_HtmlEncode($jurnald->debet->OldValue) ?>">
<?php } ?>
<?php if ($jurnald->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_debet" class="form-group jurnald_debet">
<input type="text" data-table="jurnald" data-field="x_debet" name="x<?php echo $jurnald_grid->RowIndex ?>_debet" id="x<?php echo $jurnald_grid->RowIndex ?>_debet" size="30" placeholder="<?php echo ew_HtmlEncode($jurnald->debet->getPlaceHolder()) ?>" value="<?php echo $jurnald->debet->EditValue ?>"<?php echo $jurnald->debet->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($jurnald->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_debet" class="jurnald_debet">
<span<?php echo $jurnald->debet->ViewAttributes() ?>>
<?php echo $jurnald->debet->ListViewValue() ?></span>
</span>
<?php if ($jurnald->CurrentAction <> "F") { ?>
<input type="hidden" data-table="jurnald" data-field="x_debet" name="x<?php echo $jurnald_grid->RowIndex ?>_debet" id="x<?php echo $jurnald_grid->RowIndex ?>_debet" value="<?php echo ew_HtmlEncode($jurnald->debet->FormValue) ?>">
<input type="hidden" data-table="jurnald" data-field="x_debet" name="o<?php echo $jurnald_grid->RowIndex ?>_debet" id="o<?php echo $jurnald_grid->RowIndex ?>_debet" value="<?php echo ew_HtmlEncode($jurnald->debet->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="jurnald" data-field="x_debet" name="fjurnaldgrid$x<?php echo $jurnald_grid->RowIndex ?>_debet" id="fjurnaldgrid$x<?php echo $jurnald_grid->RowIndex ?>_debet" value="<?php echo ew_HtmlEncode($jurnald->debet->FormValue) ?>">
<input type="hidden" data-table="jurnald" data-field="x_debet" name="fjurnaldgrid$o<?php echo $jurnald_grid->RowIndex ?>_debet" id="fjurnaldgrid$o<?php echo $jurnald_grid->RowIndex ?>_debet" value="<?php echo ew_HtmlEncode($jurnald->debet->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($jurnald->kredit->Visible) { // kredit ?>
		<td data-name="kredit"<?php echo $jurnald->kredit->CellAttributes() ?>>
<?php if ($jurnald->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_kredit" class="form-group jurnald_kredit">
<input type="text" data-table="jurnald" data-field="x_kredit" name="x<?php echo $jurnald_grid->RowIndex ?>_kredit" id="x<?php echo $jurnald_grid->RowIndex ?>_kredit" size="30" placeholder="<?php echo ew_HtmlEncode($jurnald->kredit->getPlaceHolder()) ?>" value="<?php echo $jurnald->kredit->EditValue ?>"<?php echo $jurnald->kredit->EditAttributes() ?>>
</span>
<input type="hidden" data-table="jurnald" data-field="x_kredit" name="o<?php echo $jurnald_grid->RowIndex ?>_kredit" id="o<?php echo $jurnald_grid->RowIndex ?>_kredit" value="<?php echo ew_HtmlEncode($jurnald->kredit->OldValue) ?>">
<?php } ?>
<?php if ($jurnald->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_kredit" class="form-group jurnald_kredit">
<input type="text" data-table="jurnald" data-field="x_kredit" name="x<?php echo $jurnald_grid->RowIndex ?>_kredit" id="x<?php echo $jurnald_grid->RowIndex ?>_kredit" size="30" placeholder="<?php echo ew_HtmlEncode($jurnald->kredit->getPlaceHolder()) ?>" value="<?php echo $jurnald->kredit->EditValue ?>"<?php echo $jurnald->kredit->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($jurnald->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $jurnald_grid->RowCnt ?>_jurnald_kredit" class="jurnald_kredit">
<span<?php echo $jurnald->kredit->ViewAttributes() ?>>
<?php echo $jurnald->kredit->ListViewValue() ?></span>
</span>
<?php if ($jurnald->CurrentAction <> "F") { ?>
<input type="hidden" data-table="jurnald" data-field="x_kredit" name="x<?php echo $jurnald_grid->RowIndex ?>_kredit" id="x<?php echo $jurnald_grid->RowIndex ?>_kredit" value="<?php echo ew_HtmlEncode($jurnald->kredit->FormValue) ?>">
<input type="hidden" data-table="jurnald" data-field="x_kredit" name="o<?php echo $jurnald_grid->RowIndex ?>_kredit" id="o<?php echo $jurnald_grid->RowIndex ?>_kredit" value="<?php echo ew_HtmlEncode($jurnald->kredit->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="jurnald" data-field="x_kredit" name="fjurnaldgrid$x<?php echo $jurnald_grid->RowIndex ?>_kredit" id="fjurnaldgrid$x<?php echo $jurnald_grid->RowIndex ?>_kredit" value="<?php echo ew_HtmlEncode($jurnald->kredit->FormValue) ?>">
<input type="hidden" data-table="jurnald" data-field="x_kredit" name="fjurnaldgrid$o<?php echo $jurnald_grid->RowIndex ?>_kredit" id="fjurnaldgrid$o<?php echo $jurnald_grid->RowIndex ?>_kredit" value="<?php echo ew_HtmlEncode($jurnald->kredit->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$jurnald_grid->ListOptions->Render("body", "right", $jurnald_grid->RowCnt);
?>
	</tr>
<?php if ($jurnald->RowType == EW_ROWTYPE_ADD || $jurnald->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fjurnaldgrid.UpdateOpts(<?php echo $jurnald_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($jurnald->CurrentAction <> "gridadd" || $jurnald->CurrentMode == "copy")
		if (!$jurnald_grid->Recordset->EOF) $jurnald_grid->Recordset->MoveNext();
}
?>
<?php
	if ($jurnald->CurrentMode == "add" || $jurnald->CurrentMode == "copy" || $jurnald->CurrentMode == "edit") {
		$jurnald_grid->RowIndex = '$rowindex$';
		$jurnald_grid->LoadDefaultValues();

		// Set row properties
		$jurnald->ResetAttrs();
		$jurnald->RowAttrs = array_merge($jurnald->RowAttrs, array('data-rowindex'=>$jurnald_grid->RowIndex, 'id'=>'r0_jurnald', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($jurnald->RowAttrs["class"], "ewTemplate");
		$jurnald->RowType = EW_ROWTYPE_ADD;

		// Render row
		$jurnald_grid->RenderRow();

		// Render list options
		$jurnald_grid->RenderListOptions();
		$jurnald_grid->StartRowCnt = 0;
?>
	<tr<?php echo $jurnald->RowAttributes() ?>>
<?php

// Render list options (body, left)
$jurnald_grid->ListOptions->Render("body", "left", $jurnald_grid->RowIndex);
?>
	<?php if ($jurnald->id->Visible) { // id ?>
		<td data-name="id">
<?php if ($jurnald->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_jurnald_id" class="form-group jurnald_id">
<span<?php echo $jurnald->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jurnald->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="jurnald" data-field="x_id" name="x<?php echo $jurnald_grid->RowIndex ?>_id" id="x<?php echo $jurnald_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jurnald->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="jurnald" data-field="x_id" name="o<?php echo $jurnald_grid->RowIndex ?>_id" id="o<?php echo $jurnald_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($jurnald->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($jurnald->jurnal_id->Visible) { // jurnal_id ?>
		<td data-name="jurnal_id">
<?php if ($jurnald->CurrentAction <> "F") { ?>
<?php if ($jurnald->jurnal_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_jurnald_jurnal_id" class="form-group jurnald_jurnal_id">
<span<?php echo $jurnald->jurnal_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jurnald->jurnal_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" name="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" value="<?php echo ew_HtmlEncode($jurnald->jurnal_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_jurnald_jurnal_id" class="form-group jurnald_jurnal_id">
<input type="text" data-table="jurnald" data-field="x_jurnal_id" name="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" id="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" size="30" placeholder="<?php echo ew_HtmlEncode($jurnald->jurnal_id->getPlaceHolder()) ?>" value="<?php echo $jurnald->jurnal_id->EditValue ?>"<?php echo $jurnald->jurnal_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_jurnald_jurnal_id" class="form-group jurnald_jurnal_id">
<span<?php echo $jurnald->jurnal_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jurnald->jurnal_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="jurnald" data-field="x_jurnal_id" name="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" id="x<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" value="<?php echo ew_HtmlEncode($jurnald->jurnal_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="jurnald" data-field="x_jurnal_id" name="o<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" id="o<?php echo $jurnald_grid->RowIndex ?>_jurnal_id" value="<?php echo ew_HtmlEncode($jurnald->jurnal_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($jurnald->akun_id->Visible) { // akun_id ?>
		<td data-name="akun_id">
<?php if ($jurnald->CurrentAction <> "F") { ?>
<span id="el$rowindex$_jurnald_akun_id" class="form-group jurnald_akun_id">
<select data-table="jurnald" data-field="x_akun_id" data-value-separator="<?php echo $jurnald->akun_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $jurnald_grid->RowIndex ?>_akun_id" name="x<?php echo $jurnald_grid->RowIndex ?>_akun_id"<?php echo $jurnald->akun_id->EditAttributes() ?>>
<?php echo $jurnald->akun_id->SelectOptionListHtml("x<?php echo $jurnald_grid->RowIndex ?>_akun_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $jurnald_grid->RowIndex ?>_akun_id" id="s_x<?php echo $jurnald_grid->RowIndex ?>_akun_id" value="<?php echo $jurnald->akun_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_jurnald_akun_id" class="form-group jurnald_akun_id">
<span<?php echo $jurnald->akun_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jurnald->akun_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="jurnald" data-field="x_akun_id" name="x<?php echo $jurnald_grid->RowIndex ?>_akun_id" id="x<?php echo $jurnald_grid->RowIndex ?>_akun_id" value="<?php echo ew_HtmlEncode($jurnald->akun_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="jurnald" data-field="x_akun_id" name="o<?php echo $jurnald_grid->RowIndex ?>_akun_id" id="o<?php echo $jurnald_grid->RowIndex ?>_akun_id" value="<?php echo ew_HtmlEncode($jurnald->akun_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($jurnald->debet->Visible) { // debet ?>
		<td data-name="debet">
<?php if ($jurnald->CurrentAction <> "F") { ?>
<span id="el$rowindex$_jurnald_debet" class="form-group jurnald_debet">
<input type="text" data-table="jurnald" data-field="x_debet" name="x<?php echo $jurnald_grid->RowIndex ?>_debet" id="x<?php echo $jurnald_grid->RowIndex ?>_debet" size="30" placeholder="<?php echo ew_HtmlEncode($jurnald->debet->getPlaceHolder()) ?>" value="<?php echo $jurnald->debet->EditValue ?>"<?php echo $jurnald->debet->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_jurnald_debet" class="form-group jurnald_debet">
<span<?php echo $jurnald->debet->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jurnald->debet->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="jurnald" data-field="x_debet" name="x<?php echo $jurnald_grid->RowIndex ?>_debet" id="x<?php echo $jurnald_grid->RowIndex ?>_debet" value="<?php echo ew_HtmlEncode($jurnald->debet->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="jurnald" data-field="x_debet" name="o<?php echo $jurnald_grid->RowIndex ?>_debet" id="o<?php echo $jurnald_grid->RowIndex ?>_debet" value="<?php echo ew_HtmlEncode($jurnald->debet->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($jurnald->kredit->Visible) { // kredit ?>
		<td data-name="kredit">
<?php if ($jurnald->CurrentAction <> "F") { ?>
<span id="el$rowindex$_jurnald_kredit" class="form-group jurnald_kredit">
<input type="text" data-table="jurnald" data-field="x_kredit" name="x<?php echo $jurnald_grid->RowIndex ?>_kredit" id="x<?php echo $jurnald_grid->RowIndex ?>_kredit" size="30" placeholder="<?php echo ew_HtmlEncode($jurnald->kredit->getPlaceHolder()) ?>" value="<?php echo $jurnald->kredit->EditValue ?>"<?php echo $jurnald->kredit->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_jurnald_kredit" class="form-group jurnald_kredit">
<span<?php echo $jurnald->kredit->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jurnald->kredit->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="jurnald" data-field="x_kredit" name="x<?php echo $jurnald_grid->RowIndex ?>_kredit" id="x<?php echo $jurnald_grid->RowIndex ?>_kredit" value="<?php echo ew_HtmlEncode($jurnald->kredit->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="jurnald" data-field="x_kredit" name="o<?php echo $jurnald_grid->RowIndex ?>_kredit" id="o<?php echo $jurnald_grid->RowIndex ?>_kredit" value="<?php echo ew_HtmlEncode($jurnald->kredit->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$jurnald_grid->ListOptions->Render("body", "right", $jurnald_grid->RowCnt);
?>
<script type="text/javascript">
fjurnaldgrid.UpdateOpts(<?php echo $jurnald_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($jurnald->CurrentMode == "add" || $jurnald->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $jurnald_grid->FormKeyCountName ?>" id="<?php echo $jurnald_grid->FormKeyCountName ?>" value="<?php echo $jurnald_grid->KeyCount ?>">
<?php echo $jurnald_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($jurnald->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $jurnald_grid->FormKeyCountName ?>" id="<?php echo $jurnald_grid->FormKeyCountName ?>" value="<?php echo $jurnald_grid->KeyCount ?>">
<?php echo $jurnald_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($jurnald->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fjurnaldgrid">
</div>
<?php

// Close recordset
if ($jurnald_grid->Recordset)
	$jurnald_grid->Recordset->Close();
?>
<?php if ($jurnald_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($jurnald_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($jurnald_grid->TotalRecs == 0 && $jurnald->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($jurnald_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($jurnald->Export == "") { ?>
<script type="text/javascript">
fjurnaldgrid.Init();
</script>
<?php } ?>
<?php
$jurnald_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$jurnald_grid->Page_Terminate();
?>
