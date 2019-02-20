<?php

// Create page object
if (!isset($akun_grid)) $akun_grid = new cakun_grid();

// Page init
$akun_grid->Page_Init();

// Page main
$akun_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$akun_grid->Page_Render();
?>
<?php if ($akun->Export == "") { ?>
<script type="text/javascript">

// Form object
var fakungrid = new ew_Form("fakungrid", "grid");
fakungrid.FormKeyCountName = '<?php echo $akun_grid->FormKeyCountName ?>';

// Validate form
fakungrid.Validate = function() {
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fakungrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "group_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "subgrup_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "kode", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nama", false)) return false;
	if (ew_ValueChanged(fobj, infix, "matauang_id", false)) return false;
	return true;
}

// Form_CustomValidate event
fakungrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fakungrid.ValidateRequired = true;
<?php } else { ?>
fakungrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fakungrid.Lists["x_group_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":["x_subgrup_id"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"grup"};
fakungrid.Lists["x_subgrup_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_kode","x_nama","",""],"ParentFields":["x_group_id"],"ChildFields":[],"FilterFields":["x_grup_id"],"Options":[],"Template":"","LinkTable":"subgrup"};
fakungrid.Lists["x_matauang_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_kode","x_nama","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"matauang"};

// Form object for search
</script>
<?php } ?>
<?php
if ($akun->CurrentAction == "gridadd") {
	if ($akun->CurrentMode == "copy") {
		$bSelectLimit = $akun_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$akun_grid->TotalRecs = $akun->SelectRecordCount();
			$akun_grid->Recordset = $akun_grid->LoadRecordset($akun_grid->StartRec-1, $akun_grid->DisplayRecs);
		} else {
			if ($akun_grid->Recordset = $akun_grid->LoadRecordset())
				$akun_grid->TotalRecs = $akun_grid->Recordset->RecordCount();
		}
		$akun_grid->StartRec = 1;
		$akun_grid->DisplayRecs = $akun_grid->TotalRecs;
	} else {
		$akun->CurrentFilter = "0=1";
		$akun_grid->StartRec = 1;
		$akun_grid->DisplayRecs = $akun->GridAddRowCount;
	}
	$akun_grid->TotalRecs = $akun_grid->DisplayRecs;
	$akun_grid->StopRec = $akun_grid->DisplayRecs;
} else {
	$bSelectLimit = $akun_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($akun_grid->TotalRecs <= 0)
			$akun_grid->TotalRecs = $akun->SelectRecordCount();
	} else {
		if (!$akun_grid->Recordset && ($akun_grid->Recordset = $akun_grid->LoadRecordset()))
			$akun_grid->TotalRecs = $akun_grid->Recordset->RecordCount();
	}
	$akun_grid->StartRec = 1;
	$akun_grid->DisplayRecs = $akun_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$akun_grid->Recordset = $akun_grid->LoadRecordset($akun_grid->StartRec-1, $akun_grid->DisplayRecs);

	// Set no record found message
	if ($akun->CurrentAction == "" && $akun_grid->TotalRecs == 0) {
		if ($akun_grid->SearchWhere == "0=101")
			$akun_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$akun_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$akun_grid->RenderOtherOptions();
?>
<?php $akun_grid->ShowPageHeader(); ?>
<?php
$akun_grid->ShowMessage();
?>
<?php if ($akun_grid->TotalRecs > 0 || $akun->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid akun">
<div id="fakungrid" class="ewForm form-inline">
<div id="gmp_akun" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_akungrid" class="table ewTable">
<?php echo $akun->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$akun_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$akun_grid->RenderListOptions();

// Render list options (header, left)
$akun_grid->ListOptions->Render("header", "left");
?>
<?php if ($akun->group_id->Visible) { // group_id ?>
	<?php if ($akun->SortUrl($akun->group_id) == "") { ?>
		<th data-name="group_id"><div id="elh_akun_group_id" class="akun_group_id"><div class="ewTableHeaderCaption"><?php echo $akun->group_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="group_id"><div><div id="elh_akun_group_id" class="akun_group_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $akun->group_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($akun->group_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($akun->group_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($akun->subgrup_id->Visible) { // subgrup_id ?>
	<?php if ($akun->SortUrl($akun->subgrup_id) == "") { ?>
		<th data-name="subgrup_id"><div id="elh_akun_subgrup_id" class="akun_subgrup_id"><div class="ewTableHeaderCaption"><?php echo $akun->subgrup_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="subgrup_id"><div><div id="elh_akun_subgrup_id" class="akun_subgrup_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $akun->subgrup_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($akun->subgrup_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($akun->subgrup_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($akun->kode->Visible) { // kode ?>
	<?php if ($akun->SortUrl($akun->kode) == "") { ?>
		<th data-name="kode"><div id="elh_akun_kode" class="akun_kode"><div class="ewTableHeaderCaption"><?php echo $akun->kode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kode"><div><div id="elh_akun_kode" class="akun_kode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $akun->kode->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($akun->kode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($akun->kode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($akun->nama->Visible) { // nama ?>
	<?php if ($akun->SortUrl($akun->nama) == "") { ?>
		<th data-name="nama"><div id="elh_akun_nama" class="akun_nama"><div class="ewTableHeaderCaption"><?php echo $akun->nama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nama"><div><div id="elh_akun_nama" class="akun_nama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $akun->nama->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($akun->nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($akun->nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($akun->matauang_id->Visible) { // matauang_id ?>
	<?php if ($akun->SortUrl($akun->matauang_id) == "") { ?>
		<th data-name="matauang_id"><div id="elh_akun_matauang_id" class="akun_matauang_id"><div class="ewTableHeaderCaption"><?php echo $akun->matauang_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="matauang_id"><div><div id="elh_akun_matauang_id" class="akun_matauang_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $akun->matauang_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($akun->matauang_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($akun->matauang_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$akun_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$akun_grid->StartRec = 1;
$akun_grid->StopRec = $akun_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($akun_grid->FormKeyCountName) && ($akun->CurrentAction == "gridadd" || $akun->CurrentAction == "gridedit" || $akun->CurrentAction == "F")) {
		$akun_grid->KeyCount = $objForm->GetValue($akun_grid->FormKeyCountName);
		$akun_grid->StopRec = $akun_grid->StartRec + $akun_grid->KeyCount - 1;
	}
}
$akun_grid->RecCnt = $akun_grid->StartRec - 1;
if ($akun_grid->Recordset && !$akun_grid->Recordset->EOF) {
	$akun_grid->Recordset->MoveFirst();
	$bSelectLimit = $akun_grid->UseSelectLimit;
	if (!$bSelectLimit && $akun_grid->StartRec > 1)
		$akun_grid->Recordset->Move($akun_grid->StartRec - 1);
} elseif (!$akun->AllowAddDeleteRow && $akun_grid->StopRec == 0) {
	$akun_grid->StopRec = $akun->GridAddRowCount;
}

// Initialize aggregate
$akun->RowType = EW_ROWTYPE_AGGREGATEINIT;
$akun->ResetAttrs();
$akun_grid->RenderRow();
if ($akun->CurrentAction == "gridadd")
	$akun_grid->RowIndex = 0;
if ($akun->CurrentAction == "gridedit")
	$akun_grid->RowIndex = 0;
while ($akun_grid->RecCnt < $akun_grid->StopRec) {
	$akun_grid->RecCnt++;
	if (intval($akun_grid->RecCnt) >= intval($akun_grid->StartRec)) {
		$akun_grid->RowCnt++;
		if ($akun->CurrentAction == "gridadd" || $akun->CurrentAction == "gridedit" || $akun->CurrentAction == "F") {
			$akun_grid->RowIndex++;
			$objForm->Index = $akun_grid->RowIndex;
			if ($objForm->HasValue($akun_grid->FormActionName))
				$akun_grid->RowAction = strval($objForm->GetValue($akun_grid->FormActionName));
			elseif ($akun->CurrentAction == "gridadd")
				$akun_grid->RowAction = "insert";
			else
				$akun_grid->RowAction = "";
		}

		// Set up key count
		$akun_grid->KeyCount = $akun_grid->RowIndex;

		// Init row class and style
		$akun->ResetAttrs();
		$akun->CssClass = "";
		if ($akun->CurrentAction == "gridadd") {
			if ($akun->CurrentMode == "copy") {
				$akun_grid->LoadRowValues($akun_grid->Recordset); // Load row values
				$akun_grid->SetRecordKey($akun_grid->RowOldKey, $akun_grid->Recordset); // Set old record key
			} else {
				$akun_grid->LoadDefaultValues(); // Load default values
				$akun_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$akun_grid->LoadRowValues($akun_grid->Recordset); // Load row values
		}
		$akun->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($akun->CurrentAction == "gridadd") // Grid add
			$akun->RowType = EW_ROWTYPE_ADD; // Render add
		if ($akun->CurrentAction == "gridadd" && $akun->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$akun_grid->RestoreCurrentRowFormValues($akun_grid->RowIndex); // Restore form values
		if ($akun->CurrentAction == "gridedit") { // Grid edit
			if ($akun->EventCancelled) {
				$akun_grid->RestoreCurrentRowFormValues($akun_grid->RowIndex); // Restore form values
			}
			if ($akun_grid->RowAction == "insert")
				$akun->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$akun->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($akun->CurrentAction == "gridedit" && ($akun->RowType == EW_ROWTYPE_EDIT || $akun->RowType == EW_ROWTYPE_ADD) && $akun->EventCancelled) // Update failed
			$akun_grid->RestoreCurrentRowFormValues($akun_grid->RowIndex); // Restore form values
		if ($akun->RowType == EW_ROWTYPE_EDIT) // Edit row
			$akun_grid->EditRowCnt++;
		if ($akun->CurrentAction == "F") // Confirm row
			$akun_grid->RestoreCurrentRowFormValues($akun_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$akun->RowAttrs = array_merge($akun->RowAttrs, array('data-rowindex'=>$akun_grid->RowCnt, 'id'=>'r' . $akun_grid->RowCnt . '_akun', 'data-rowtype'=>$akun->RowType));

		// Render row
		$akun_grid->RenderRow();

		// Render list options
		$akun_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($akun_grid->RowAction <> "delete" && $akun_grid->RowAction <> "insertdelete" && !($akun_grid->RowAction == "insert" && $akun->CurrentAction == "F" && $akun_grid->EmptyRow())) {
?>
	<tr<?php echo $akun->RowAttributes() ?>>
<?php

// Render list options (body, left)
$akun_grid->ListOptions->Render("body", "left", $akun_grid->RowCnt);
?>
	<?php if ($akun->group_id->Visible) { // group_id ?>
		<td data-name="group_id"<?php echo $akun->group_id->CellAttributes() ?>>
<?php if ($akun->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_group_id" class="form-group akun_group_id">
<?php $akun->group_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$akun->group_id->EditAttrs["onchange"]; ?>
<select data-table="akun" data-field="x_group_id" data-value-separator="<?php echo $akun->group_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $akun_grid->RowIndex ?>_group_id" name="x<?php echo $akun_grid->RowIndex ?>_group_id"<?php echo $akun->group_id->EditAttributes() ?>>
<?php echo $akun->group_id->SelectOptionListHtml("x<?php echo $akun_grid->RowIndex ?>_group_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $akun_grid->RowIndex ?>_group_id" id="s_x<?php echo $akun_grid->RowIndex ?>_group_id" value="<?php echo $akun->group_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="akun" data-field="x_group_id" name="o<?php echo $akun_grid->RowIndex ?>_group_id" id="o<?php echo $akun_grid->RowIndex ?>_group_id" value="<?php echo ew_HtmlEncode($akun->group_id->OldValue) ?>">
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_group_id" class="form-group akun_group_id">
<?php $akun->group_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$akun->group_id->EditAttrs["onchange"]; ?>
<select data-table="akun" data-field="x_group_id" data-value-separator="<?php echo $akun->group_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $akun_grid->RowIndex ?>_group_id" name="x<?php echo $akun_grid->RowIndex ?>_group_id"<?php echo $akun->group_id->EditAttributes() ?>>
<?php echo $akun->group_id->SelectOptionListHtml("x<?php echo $akun_grid->RowIndex ?>_group_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $akun_grid->RowIndex ?>_group_id" id="s_x<?php echo $akun_grid->RowIndex ?>_group_id" value="<?php echo $akun->group_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_group_id" class="akun_group_id">
<span<?php echo $akun->group_id->ViewAttributes() ?>>
<?php echo $akun->group_id->ListViewValue() ?></span>
</span>
<?php if ($akun->CurrentAction <> "F") { ?>
<input type="hidden" data-table="akun" data-field="x_group_id" name="x<?php echo $akun_grid->RowIndex ?>_group_id" id="x<?php echo $akun_grid->RowIndex ?>_group_id" value="<?php echo ew_HtmlEncode($akun->group_id->FormValue) ?>">
<input type="hidden" data-table="akun" data-field="x_group_id" name="o<?php echo $akun_grid->RowIndex ?>_group_id" id="o<?php echo $akun_grid->RowIndex ?>_group_id" value="<?php echo ew_HtmlEncode($akun->group_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="akun" data-field="x_group_id" name="fakungrid$x<?php echo $akun_grid->RowIndex ?>_group_id" id="fakungrid$x<?php echo $akun_grid->RowIndex ?>_group_id" value="<?php echo ew_HtmlEncode($akun->group_id->FormValue) ?>">
<input type="hidden" data-table="akun" data-field="x_group_id" name="fakungrid$o<?php echo $akun_grid->RowIndex ?>_group_id" id="fakungrid$o<?php echo $akun_grid->RowIndex ?>_group_id" value="<?php echo ew_HtmlEncode($akun->group_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $akun_grid->PageObjName . "_row_" . $akun_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="akun" data-field="x_id" name="x<?php echo $akun_grid->RowIndex ?>_id" id="x<?php echo $akun_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($akun->id->CurrentValue) ?>">
<input type="hidden" data-table="akun" data-field="x_id" name="o<?php echo $akun_grid->RowIndex ?>_id" id="o<?php echo $akun_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($akun->id->OldValue) ?>">
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_EDIT || $akun->CurrentMode == "edit") { ?>
<input type="hidden" data-table="akun" data-field="x_id" name="x<?php echo $akun_grid->RowIndex ?>_id" id="x<?php echo $akun_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($akun->id->CurrentValue) ?>">
<?php } ?>
	<?php if ($akun->subgrup_id->Visible) { // subgrup_id ?>
		<td data-name="subgrup_id"<?php echo $akun->subgrup_id->CellAttributes() ?>>
<?php if ($akun->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($akun->subgrup_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_subgrup_id" class="form-group akun_subgrup_id">
<span<?php echo $akun->subgrup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $akun->subgrup_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" name="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo ew_HtmlEncode($akun->subgrup_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_subgrup_id" class="form-group akun_subgrup_id">
<select data-table="akun" data-field="x_subgrup_id" data-value-separator="<?php echo $akun->subgrup_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" name="x<?php echo $akun_grid->RowIndex ?>_subgrup_id"<?php echo $akun->subgrup_id->EditAttributes() ?>>
<?php echo $akun->subgrup_id->SelectOptionListHtml("x<?php echo $akun_grid->RowIndex ?>_subgrup_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $akun_grid->RowIndex ?>_subgrup_id" id="s_x<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo $akun->subgrup_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="akun" data-field="x_subgrup_id" name="o<?php echo $akun_grid->RowIndex ?>_subgrup_id" id="o<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo ew_HtmlEncode($akun->subgrup_id->OldValue) ?>">
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($akun->subgrup_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_subgrup_id" class="form-group akun_subgrup_id">
<span<?php echo $akun->subgrup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $akun->subgrup_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" name="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo ew_HtmlEncode($akun->subgrup_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_subgrup_id" class="form-group akun_subgrup_id">
<select data-table="akun" data-field="x_subgrup_id" data-value-separator="<?php echo $akun->subgrup_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" name="x<?php echo $akun_grid->RowIndex ?>_subgrup_id"<?php echo $akun->subgrup_id->EditAttributes() ?>>
<?php echo $akun->subgrup_id->SelectOptionListHtml("x<?php echo $akun_grid->RowIndex ?>_subgrup_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $akun_grid->RowIndex ?>_subgrup_id" id="s_x<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo $akun->subgrup_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_subgrup_id" class="akun_subgrup_id">
<span<?php echo $akun->subgrup_id->ViewAttributes() ?>>
<?php echo $akun->subgrup_id->ListViewValue() ?></span>
</span>
<?php if ($akun->CurrentAction <> "F") { ?>
<input type="hidden" data-table="akun" data-field="x_subgrup_id" name="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" id="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo ew_HtmlEncode($akun->subgrup_id->FormValue) ?>">
<input type="hidden" data-table="akun" data-field="x_subgrup_id" name="o<?php echo $akun_grid->RowIndex ?>_subgrup_id" id="o<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo ew_HtmlEncode($akun->subgrup_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="akun" data-field="x_subgrup_id" name="fakungrid$x<?php echo $akun_grid->RowIndex ?>_subgrup_id" id="fakungrid$x<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo ew_HtmlEncode($akun->subgrup_id->FormValue) ?>">
<input type="hidden" data-table="akun" data-field="x_subgrup_id" name="fakungrid$o<?php echo $akun_grid->RowIndex ?>_subgrup_id" id="fakungrid$o<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo ew_HtmlEncode($akun->subgrup_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($akun->kode->Visible) { // kode ?>
		<td data-name="kode"<?php echo $akun->kode->CellAttributes() ?>>
<?php if ($akun->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_kode" class="form-group akun_kode">
<input type="text" data-table="akun" data-field="x_kode" name="x<?php echo $akun_grid->RowIndex ?>_kode" id="x<?php echo $akun_grid->RowIndex ?>_kode" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($akun->kode->getPlaceHolder()) ?>" value="<?php echo $akun->kode->EditValue ?>"<?php echo $akun->kode->EditAttributes() ?>>
</span>
<input type="hidden" data-table="akun" data-field="x_kode" name="o<?php echo $akun_grid->RowIndex ?>_kode" id="o<?php echo $akun_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($akun->kode->OldValue) ?>">
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_kode" class="form-group akun_kode">
<input type="text" data-table="akun" data-field="x_kode" name="x<?php echo $akun_grid->RowIndex ?>_kode" id="x<?php echo $akun_grid->RowIndex ?>_kode" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($akun->kode->getPlaceHolder()) ?>" value="<?php echo $akun->kode->EditValue ?>"<?php echo $akun->kode->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_kode" class="akun_kode">
<span<?php echo $akun->kode->ViewAttributes() ?>>
<?php echo $akun->kode->ListViewValue() ?></span>
</span>
<?php if ($akun->CurrentAction <> "F") { ?>
<input type="hidden" data-table="akun" data-field="x_kode" name="x<?php echo $akun_grid->RowIndex ?>_kode" id="x<?php echo $akun_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($akun->kode->FormValue) ?>">
<input type="hidden" data-table="akun" data-field="x_kode" name="o<?php echo $akun_grid->RowIndex ?>_kode" id="o<?php echo $akun_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($akun->kode->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="akun" data-field="x_kode" name="fakungrid$x<?php echo $akun_grid->RowIndex ?>_kode" id="fakungrid$x<?php echo $akun_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($akun->kode->FormValue) ?>">
<input type="hidden" data-table="akun" data-field="x_kode" name="fakungrid$o<?php echo $akun_grid->RowIndex ?>_kode" id="fakungrid$o<?php echo $akun_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($akun->kode->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($akun->nama->Visible) { // nama ?>
		<td data-name="nama"<?php echo $akun->nama->CellAttributes() ?>>
<?php if ($akun->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_nama" class="form-group akun_nama">
<input type="text" data-table="akun" data-field="x_nama" name="x<?php echo $akun_grid->RowIndex ?>_nama" id="x<?php echo $akun_grid->RowIndex ?>_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($akun->nama->getPlaceHolder()) ?>" value="<?php echo $akun->nama->EditValue ?>"<?php echo $akun->nama->EditAttributes() ?>>
</span>
<input type="hidden" data-table="akun" data-field="x_nama" name="o<?php echo $akun_grid->RowIndex ?>_nama" id="o<?php echo $akun_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($akun->nama->OldValue) ?>">
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_nama" class="form-group akun_nama">
<input type="text" data-table="akun" data-field="x_nama" name="x<?php echo $akun_grid->RowIndex ?>_nama" id="x<?php echo $akun_grid->RowIndex ?>_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($akun->nama->getPlaceHolder()) ?>" value="<?php echo $akun->nama->EditValue ?>"<?php echo $akun->nama->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_nama" class="akun_nama">
<span<?php echo $akun->nama->ViewAttributes() ?>>
<?php echo $akun->nama->ListViewValue() ?></span>
</span>
<?php if ($akun->CurrentAction <> "F") { ?>
<input type="hidden" data-table="akun" data-field="x_nama" name="x<?php echo $akun_grid->RowIndex ?>_nama" id="x<?php echo $akun_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($akun->nama->FormValue) ?>">
<input type="hidden" data-table="akun" data-field="x_nama" name="o<?php echo $akun_grid->RowIndex ?>_nama" id="o<?php echo $akun_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($akun->nama->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="akun" data-field="x_nama" name="fakungrid$x<?php echo $akun_grid->RowIndex ?>_nama" id="fakungrid$x<?php echo $akun_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($akun->nama->FormValue) ?>">
<input type="hidden" data-table="akun" data-field="x_nama" name="fakungrid$o<?php echo $akun_grid->RowIndex ?>_nama" id="fakungrid$o<?php echo $akun_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($akun->nama->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($akun->matauang_id->Visible) { // matauang_id ?>
		<td data-name="matauang_id"<?php echo $akun->matauang_id->CellAttributes() ?>>
<?php if ($akun->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_matauang_id" class="form-group akun_matauang_id">
<select data-table="akun" data-field="x_matauang_id" data-value-separator="<?php echo $akun->matauang_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $akun_grid->RowIndex ?>_matauang_id" name="x<?php echo $akun_grid->RowIndex ?>_matauang_id"<?php echo $akun->matauang_id->EditAttributes() ?>>
<?php echo $akun->matauang_id->SelectOptionListHtml("x<?php echo $akun_grid->RowIndex ?>_matauang_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $akun_grid->RowIndex ?>_matauang_id" id="s_x<?php echo $akun_grid->RowIndex ?>_matauang_id" value="<?php echo $akun->matauang_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="akun" data-field="x_matauang_id" name="o<?php echo $akun_grid->RowIndex ?>_matauang_id" id="o<?php echo $akun_grid->RowIndex ?>_matauang_id" value="<?php echo ew_HtmlEncode($akun->matauang_id->OldValue) ?>">
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_matauang_id" class="form-group akun_matauang_id">
<select data-table="akun" data-field="x_matauang_id" data-value-separator="<?php echo $akun->matauang_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $akun_grid->RowIndex ?>_matauang_id" name="x<?php echo $akun_grid->RowIndex ?>_matauang_id"<?php echo $akun->matauang_id->EditAttributes() ?>>
<?php echo $akun->matauang_id->SelectOptionListHtml("x<?php echo $akun_grid->RowIndex ?>_matauang_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $akun_grid->RowIndex ?>_matauang_id" id="s_x<?php echo $akun_grid->RowIndex ?>_matauang_id" value="<?php echo $akun->matauang_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($akun->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $akun_grid->RowCnt ?>_akun_matauang_id" class="akun_matauang_id">
<span<?php echo $akun->matauang_id->ViewAttributes() ?>>
<?php echo $akun->matauang_id->ListViewValue() ?></span>
</span>
<?php if ($akun->CurrentAction <> "F") { ?>
<input type="hidden" data-table="akun" data-field="x_matauang_id" name="x<?php echo $akun_grid->RowIndex ?>_matauang_id" id="x<?php echo $akun_grid->RowIndex ?>_matauang_id" value="<?php echo ew_HtmlEncode($akun->matauang_id->FormValue) ?>">
<input type="hidden" data-table="akun" data-field="x_matauang_id" name="o<?php echo $akun_grid->RowIndex ?>_matauang_id" id="o<?php echo $akun_grid->RowIndex ?>_matauang_id" value="<?php echo ew_HtmlEncode($akun->matauang_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="akun" data-field="x_matauang_id" name="fakungrid$x<?php echo $akun_grid->RowIndex ?>_matauang_id" id="fakungrid$x<?php echo $akun_grid->RowIndex ?>_matauang_id" value="<?php echo ew_HtmlEncode($akun->matauang_id->FormValue) ?>">
<input type="hidden" data-table="akun" data-field="x_matauang_id" name="fakungrid$o<?php echo $akun_grid->RowIndex ?>_matauang_id" id="fakungrid$o<?php echo $akun_grid->RowIndex ?>_matauang_id" value="<?php echo ew_HtmlEncode($akun->matauang_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$akun_grid->ListOptions->Render("body", "right", $akun_grid->RowCnt);
?>
	</tr>
<?php if ($akun->RowType == EW_ROWTYPE_ADD || $akun->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fakungrid.UpdateOpts(<?php echo $akun_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($akun->CurrentAction <> "gridadd" || $akun->CurrentMode == "copy")
		if (!$akun_grid->Recordset->EOF) $akun_grid->Recordset->MoveNext();
}
?>
<?php
	if ($akun->CurrentMode == "add" || $akun->CurrentMode == "copy" || $akun->CurrentMode == "edit") {
		$akun_grid->RowIndex = '$rowindex$';
		$akun_grid->LoadDefaultValues();

		// Set row properties
		$akun->ResetAttrs();
		$akun->RowAttrs = array_merge($akun->RowAttrs, array('data-rowindex'=>$akun_grid->RowIndex, 'id'=>'r0_akun', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($akun->RowAttrs["class"], "ewTemplate");
		$akun->RowType = EW_ROWTYPE_ADD;

		// Render row
		$akun_grid->RenderRow();

		// Render list options
		$akun_grid->RenderListOptions();
		$akun_grid->StartRowCnt = 0;
?>
	<tr<?php echo $akun->RowAttributes() ?>>
<?php

// Render list options (body, left)
$akun_grid->ListOptions->Render("body", "left", $akun_grid->RowIndex);
?>
	<?php if ($akun->group_id->Visible) { // group_id ?>
		<td data-name="group_id">
<?php if ($akun->CurrentAction <> "F") { ?>
<span id="el$rowindex$_akun_group_id" class="form-group akun_group_id">
<?php $akun->group_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$akun->group_id->EditAttrs["onchange"]; ?>
<select data-table="akun" data-field="x_group_id" data-value-separator="<?php echo $akun->group_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $akun_grid->RowIndex ?>_group_id" name="x<?php echo $akun_grid->RowIndex ?>_group_id"<?php echo $akun->group_id->EditAttributes() ?>>
<?php echo $akun->group_id->SelectOptionListHtml("x<?php echo $akun_grid->RowIndex ?>_group_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $akun_grid->RowIndex ?>_group_id" id="s_x<?php echo $akun_grid->RowIndex ?>_group_id" value="<?php echo $akun->group_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_akun_group_id" class="form-group akun_group_id">
<span<?php echo $akun->group_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $akun->group_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="akun" data-field="x_group_id" name="x<?php echo $akun_grid->RowIndex ?>_group_id" id="x<?php echo $akun_grid->RowIndex ?>_group_id" value="<?php echo ew_HtmlEncode($akun->group_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="akun" data-field="x_group_id" name="o<?php echo $akun_grid->RowIndex ?>_group_id" id="o<?php echo $akun_grid->RowIndex ?>_group_id" value="<?php echo ew_HtmlEncode($akun->group_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($akun->subgrup_id->Visible) { // subgrup_id ?>
		<td data-name="subgrup_id">
<?php if ($akun->CurrentAction <> "F") { ?>
<?php if ($akun->subgrup_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_akun_subgrup_id" class="form-group akun_subgrup_id">
<span<?php echo $akun->subgrup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $akun->subgrup_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" name="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo ew_HtmlEncode($akun->subgrup_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_akun_subgrup_id" class="form-group akun_subgrup_id">
<select data-table="akun" data-field="x_subgrup_id" data-value-separator="<?php echo $akun->subgrup_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" name="x<?php echo $akun_grid->RowIndex ?>_subgrup_id"<?php echo $akun->subgrup_id->EditAttributes() ?>>
<?php echo $akun->subgrup_id->SelectOptionListHtml("x<?php echo $akun_grid->RowIndex ?>_subgrup_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $akun_grid->RowIndex ?>_subgrup_id" id="s_x<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo $akun->subgrup_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_akun_subgrup_id" class="form-group akun_subgrup_id">
<span<?php echo $akun->subgrup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $akun->subgrup_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="akun" data-field="x_subgrup_id" name="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" id="x<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo ew_HtmlEncode($akun->subgrup_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="akun" data-field="x_subgrup_id" name="o<?php echo $akun_grid->RowIndex ?>_subgrup_id" id="o<?php echo $akun_grid->RowIndex ?>_subgrup_id" value="<?php echo ew_HtmlEncode($akun->subgrup_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($akun->kode->Visible) { // kode ?>
		<td data-name="kode">
<?php if ($akun->CurrentAction <> "F") { ?>
<span id="el$rowindex$_akun_kode" class="form-group akun_kode">
<input type="text" data-table="akun" data-field="x_kode" name="x<?php echo $akun_grid->RowIndex ?>_kode" id="x<?php echo $akun_grid->RowIndex ?>_kode" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($akun->kode->getPlaceHolder()) ?>" value="<?php echo $akun->kode->EditValue ?>"<?php echo $akun->kode->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_akun_kode" class="form-group akun_kode">
<span<?php echo $akun->kode->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $akun->kode->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="akun" data-field="x_kode" name="x<?php echo $akun_grid->RowIndex ?>_kode" id="x<?php echo $akun_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($akun->kode->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="akun" data-field="x_kode" name="o<?php echo $akun_grid->RowIndex ?>_kode" id="o<?php echo $akun_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($akun->kode->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($akun->nama->Visible) { // nama ?>
		<td data-name="nama">
<?php if ($akun->CurrentAction <> "F") { ?>
<span id="el$rowindex$_akun_nama" class="form-group akun_nama">
<input type="text" data-table="akun" data-field="x_nama" name="x<?php echo $akun_grid->RowIndex ?>_nama" id="x<?php echo $akun_grid->RowIndex ?>_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($akun->nama->getPlaceHolder()) ?>" value="<?php echo $akun->nama->EditValue ?>"<?php echo $akun->nama->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_akun_nama" class="form-group akun_nama">
<span<?php echo $akun->nama->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $akun->nama->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="akun" data-field="x_nama" name="x<?php echo $akun_grid->RowIndex ?>_nama" id="x<?php echo $akun_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($akun->nama->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="akun" data-field="x_nama" name="o<?php echo $akun_grid->RowIndex ?>_nama" id="o<?php echo $akun_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($akun->nama->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($akun->matauang_id->Visible) { // matauang_id ?>
		<td data-name="matauang_id">
<?php if ($akun->CurrentAction <> "F") { ?>
<span id="el$rowindex$_akun_matauang_id" class="form-group akun_matauang_id">
<select data-table="akun" data-field="x_matauang_id" data-value-separator="<?php echo $akun->matauang_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $akun_grid->RowIndex ?>_matauang_id" name="x<?php echo $akun_grid->RowIndex ?>_matauang_id"<?php echo $akun->matauang_id->EditAttributes() ?>>
<?php echo $akun->matauang_id->SelectOptionListHtml("x<?php echo $akun_grid->RowIndex ?>_matauang_id") ?>
</select>
<input type="hidden" name="s_x<?php echo $akun_grid->RowIndex ?>_matauang_id" id="s_x<?php echo $akun_grid->RowIndex ?>_matauang_id" value="<?php echo $akun->matauang_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_akun_matauang_id" class="form-group akun_matauang_id">
<span<?php echo $akun->matauang_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $akun->matauang_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="akun" data-field="x_matauang_id" name="x<?php echo $akun_grid->RowIndex ?>_matauang_id" id="x<?php echo $akun_grid->RowIndex ?>_matauang_id" value="<?php echo ew_HtmlEncode($akun->matauang_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="akun" data-field="x_matauang_id" name="o<?php echo $akun_grid->RowIndex ?>_matauang_id" id="o<?php echo $akun_grid->RowIndex ?>_matauang_id" value="<?php echo ew_HtmlEncode($akun->matauang_id->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$akun_grid->ListOptions->Render("body", "right", $akun_grid->RowCnt);
?>
<script type="text/javascript">
fakungrid.UpdateOpts(<?php echo $akun_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($akun->CurrentMode == "add" || $akun->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $akun_grid->FormKeyCountName ?>" id="<?php echo $akun_grid->FormKeyCountName ?>" value="<?php echo $akun_grid->KeyCount ?>">
<?php echo $akun_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($akun->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $akun_grid->FormKeyCountName ?>" id="<?php echo $akun_grid->FormKeyCountName ?>" value="<?php echo $akun_grid->KeyCount ?>">
<?php echo $akun_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($akun->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fakungrid">
</div>
<?php

// Close recordset
if ($akun_grid->Recordset)
	$akun_grid->Recordset->Close();
?>
<?php if ($akun_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($akun_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($akun_grid->TotalRecs == 0 && $akun->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($akun_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($akun->Export == "") { ?>
<script type="text/javascript">
fakungrid.Init();
</script>
<?php } ?>
<?php
$akun_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$akun_grid->Page_Terminate();
?>
