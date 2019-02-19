<?php

// Create page object
if (!isset($subgrup_grid)) $subgrup_grid = new csubgrup_grid();

// Page init
$subgrup_grid->Page_Init();

// Page main
$subgrup_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$subgrup_grid->Page_Render();
?>
<?php if ($subgrup->Export == "") { ?>
<script type="text/javascript">

// Form object
var fsubgrupgrid = new ew_Form("fsubgrupgrid", "grid");
fsubgrupgrid.FormKeyCountName = '<?php echo $subgrup_grid->FormKeyCountName ?>';

// Validate form
fsubgrupgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_grup_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subgrup->grup_id->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fsubgrupgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "grup_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "kode", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nama", false)) return false;
	return true;
}

// Form_CustomValidate event
fsubgrupgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsubgrupgrid.ValidateRequired = true;
<?php } else { ?>
fsubgrupgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($subgrup->CurrentAction == "gridadd") {
	if ($subgrup->CurrentMode == "copy") {
		$bSelectLimit = $subgrup_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$subgrup_grid->TotalRecs = $subgrup->SelectRecordCount();
			$subgrup_grid->Recordset = $subgrup_grid->LoadRecordset($subgrup_grid->StartRec-1, $subgrup_grid->DisplayRecs);
		} else {
			if ($subgrup_grid->Recordset = $subgrup_grid->LoadRecordset())
				$subgrup_grid->TotalRecs = $subgrup_grid->Recordset->RecordCount();
		}
		$subgrup_grid->StartRec = 1;
		$subgrup_grid->DisplayRecs = $subgrup_grid->TotalRecs;
	} else {
		$subgrup->CurrentFilter = "0=1";
		$subgrup_grid->StartRec = 1;
		$subgrup_grid->DisplayRecs = $subgrup->GridAddRowCount;
	}
	$subgrup_grid->TotalRecs = $subgrup_grid->DisplayRecs;
	$subgrup_grid->StopRec = $subgrup_grid->DisplayRecs;
} else {
	$bSelectLimit = $subgrup_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($subgrup_grid->TotalRecs <= 0)
			$subgrup_grid->TotalRecs = $subgrup->SelectRecordCount();
	} else {
		if (!$subgrup_grid->Recordset && ($subgrup_grid->Recordset = $subgrup_grid->LoadRecordset()))
			$subgrup_grid->TotalRecs = $subgrup_grid->Recordset->RecordCount();
	}
	$subgrup_grid->StartRec = 1;
	$subgrup_grid->DisplayRecs = $subgrup_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$subgrup_grid->Recordset = $subgrup_grid->LoadRecordset($subgrup_grid->StartRec-1, $subgrup_grid->DisplayRecs);

	// Set no record found message
	if ($subgrup->CurrentAction == "" && $subgrup_grid->TotalRecs == 0) {
		if ($subgrup_grid->SearchWhere == "0=101")
			$subgrup_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$subgrup_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$subgrup_grid->RenderOtherOptions();
?>
<?php $subgrup_grid->ShowPageHeader(); ?>
<?php
$subgrup_grid->ShowMessage();
?>
<?php if ($subgrup_grid->TotalRecs > 0 || $subgrup->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid subgrup">
<div id="fsubgrupgrid" class="ewForm form-inline">
<div id="gmp_subgrup" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_subgrupgrid" class="table ewTable">
<?php echo $subgrup->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$subgrup_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$subgrup_grid->RenderListOptions();

// Render list options (header, left)
$subgrup_grid->ListOptions->Render("header", "left");
?>
<?php if ($subgrup->id->Visible) { // id ?>
	<?php if ($subgrup->SortUrl($subgrup->id) == "") { ?>
		<th data-name="id"><div id="elh_subgrup_id" class="subgrup_id"><div class="ewTableHeaderCaption"><?php echo $subgrup->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div><div id="elh_subgrup_id" class="subgrup_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subgrup->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subgrup->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subgrup->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subgrup->grup_id->Visible) { // grup_id ?>
	<?php if ($subgrup->SortUrl($subgrup->grup_id) == "") { ?>
		<th data-name="grup_id"><div id="elh_subgrup_grup_id" class="subgrup_grup_id"><div class="ewTableHeaderCaption"><?php echo $subgrup->grup_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="grup_id"><div><div id="elh_subgrup_grup_id" class="subgrup_grup_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subgrup->grup_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subgrup->grup_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subgrup->grup_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subgrup->kode->Visible) { // kode ?>
	<?php if ($subgrup->SortUrl($subgrup->kode) == "") { ?>
		<th data-name="kode"><div id="elh_subgrup_kode" class="subgrup_kode"><div class="ewTableHeaderCaption"><?php echo $subgrup->kode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kode"><div><div id="elh_subgrup_kode" class="subgrup_kode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subgrup->kode->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subgrup->kode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subgrup->kode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subgrup->nama->Visible) { // nama ?>
	<?php if ($subgrup->SortUrl($subgrup->nama) == "") { ?>
		<th data-name="nama"><div id="elh_subgrup_nama" class="subgrup_nama"><div class="ewTableHeaderCaption"><?php echo $subgrup->nama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nama"><div><div id="elh_subgrup_nama" class="subgrup_nama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subgrup->nama->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subgrup->nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subgrup->nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$subgrup_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$subgrup_grid->StartRec = 1;
$subgrup_grid->StopRec = $subgrup_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($subgrup_grid->FormKeyCountName) && ($subgrup->CurrentAction == "gridadd" || $subgrup->CurrentAction == "gridedit" || $subgrup->CurrentAction == "F")) {
		$subgrup_grid->KeyCount = $objForm->GetValue($subgrup_grid->FormKeyCountName);
		$subgrup_grid->StopRec = $subgrup_grid->StartRec + $subgrup_grid->KeyCount - 1;
	}
}
$subgrup_grid->RecCnt = $subgrup_grid->StartRec - 1;
if ($subgrup_grid->Recordset && !$subgrup_grid->Recordset->EOF) {
	$subgrup_grid->Recordset->MoveFirst();
	$bSelectLimit = $subgrup_grid->UseSelectLimit;
	if (!$bSelectLimit && $subgrup_grid->StartRec > 1)
		$subgrup_grid->Recordset->Move($subgrup_grid->StartRec - 1);
} elseif (!$subgrup->AllowAddDeleteRow && $subgrup_grid->StopRec == 0) {
	$subgrup_grid->StopRec = $subgrup->GridAddRowCount;
}

// Initialize aggregate
$subgrup->RowType = EW_ROWTYPE_AGGREGATEINIT;
$subgrup->ResetAttrs();
$subgrup_grid->RenderRow();
if ($subgrup->CurrentAction == "gridadd")
	$subgrup_grid->RowIndex = 0;
if ($subgrup->CurrentAction == "gridedit")
	$subgrup_grid->RowIndex = 0;
while ($subgrup_grid->RecCnt < $subgrup_grid->StopRec) {
	$subgrup_grid->RecCnt++;
	if (intval($subgrup_grid->RecCnt) >= intval($subgrup_grid->StartRec)) {
		$subgrup_grid->RowCnt++;
		if ($subgrup->CurrentAction == "gridadd" || $subgrup->CurrentAction == "gridedit" || $subgrup->CurrentAction == "F") {
			$subgrup_grid->RowIndex++;
			$objForm->Index = $subgrup_grid->RowIndex;
			if ($objForm->HasValue($subgrup_grid->FormActionName))
				$subgrup_grid->RowAction = strval($objForm->GetValue($subgrup_grid->FormActionName));
			elseif ($subgrup->CurrentAction == "gridadd")
				$subgrup_grid->RowAction = "insert";
			else
				$subgrup_grid->RowAction = "";
		}

		// Set up key count
		$subgrup_grid->KeyCount = $subgrup_grid->RowIndex;

		// Init row class and style
		$subgrup->ResetAttrs();
		$subgrup->CssClass = "";
		if ($subgrup->CurrentAction == "gridadd") {
			if ($subgrup->CurrentMode == "copy") {
				$subgrup_grid->LoadRowValues($subgrup_grid->Recordset); // Load row values
				$subgrup_grid->SetRecordKey($subgrup_grid->RowOldKey, $subgrup_grid->Recordset); // Set old record key
			} else {
				$subgrup_grid->LoadDefaultValues(); // Load default values
				$subgrup_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$subgrup_grid->LoadRowValues($subgrup_grid->Recordset); // Load row values
		}
		$subgrup->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($subgrup->CurrentAction == "gridadd") // Grid add
			$subgrup->RowType = EW_ROWTYPE_ADD; // Render add
		if ($subgrup->CurrentAction == "gridadd" && $subgrup->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$subgrup_grid->RestoreCurrentRowFormValues($subgrup_grid->RowIndex); // Restore form values
		if ($subgrup->CurrentAction == "gridedit") { // Grid edit
			if ($subgrup->EventCancelled) {
				$subgrup_grid->RestoreCurrentRowFormValues($subgrup_grid->RowIndex); // Restore form values
			}
			if ($subgrup_grid->RowAction == "insert")
				$subgrup->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$subgrup->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($subgrup->CurrentAction == "gridedit" && ($subgrup->RowType == EW_ROWTYPE_EDIT || $subgrup->RowType == EW_ROWTYPE_ADD) && $subgrup->EventCancelled) // Update failed
			$subgrup_grid->RestoreCurrentRowFormValues($subgrup_grid->RowIndex); // Restore form values
		if ($subgrup->RowType == EW_ROWTYPE_EDIT) // Edit row
			$subgrup_grid->EditRowCnt++;
		if ($subgrup->CurrentAction == "F") // Confirm row
			$subgrup_grid->RestoreCurrentRowFormValues($subgrup_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$subgrup->RowAttrs = array_merge($subgrup->RowAttrs, array('data-rowindex'=>$subgrup_grid->RowCnt, 'id'=>'r' . $subgrup_grid->RowCnt . '_subgrup', 'data-rowtype'=>$subgrup->RowType));

		// Render row
		$subgrup_grid->RenderRow();

		// Render list options
		$subgrup_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($subgrup_grid->RowAction <> "delete" && $subgrup_grid->RowAction <> "insertdelete" && !($subgrup_grid->RowAction == "insert" && $subgrup->CurrentAction == "F" && $subgrup_grid->EmptyRow())) {
?>
	<tr<?php echo $subgrup->RowAttributes() ?>>
<?php

// Render list options (body, left)
$subgrup_grid->ListOptions->Render("body", "left", $subgrup_grid->RowCnt);
?>
	<?php if ($subgrup->id->Visible) { // id ?>
		<td data-name="id"<?php echo $subgrup->id->CellAttributes() ?>>
<?php if ($subgrup->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="subgrup" data-field="x_id" name="o<?php echo $subgrup_grid->RowIndex ?>_id" id="o<?php echo $subgrup_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subgrup->id->OldValue) ?>">
<?php } ?>
<?php if ($subgrup->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_id" class="form-group subgrup_id">
<span<?php echo $subgrup->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subgrup->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="subgrup" data-field="x_id" name="x<?php echo $subgrup_grid->RowIndex ?>_id" id="x<?php echo $subgrup_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subgrup->id->CurrentValue) ?>">
<?php } ?>
<?php if ($subgrup->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_id" class="subgrup_id">
<span<?php echo $subgrup->id->ViewAttributes() ?>>
<?php echo $subgrup->id->ListViewValue() ?></span>
</span>
<?php if ($subgrup->CurrentAction <> "F") { ?>
<input type="hidden" data-table="subgrup" data-field="x_id" name="x<?php echo $subgrup_grid->RowIndex ?>_id" id="x<?php echo $subgrup_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subgrup->id->FormValue) ?>">
<input type="hidden" data-table="subgrup" data-field="x_id" name="o<?php echo $subgrup_grid->RowIndex ?>_id" id="o<?php echo $subgrup_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subgrup->id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="subgrup" data-field="x_id" name="fsubgrupgrid$x<?php echo $subgrup_grid->RowIndex ?>_id" id="fsubgrupgrid$x<?php echo $subgrup_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subgrup->id->FormValue) ?>">
<input type="hidden" data-table="subgrup" data-field="x_id" name="fsubgrupgrid$o<?php echo $subgrup_grid->RowIndex ?>_id" id="fsubgrupgrid$o<?php echo $subgrup_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subgrup->id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $subgrup_grid->PageObjName . "_row_" . $subgrup_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($subgrup->grup_id->Visible) { // grup_id ?>
		<td data-name="grup_id"<?php echo $subgrup->grup_id->CellAttributes() ?>>
<?php if ($subgrup->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($subgrup->grup_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_grup_id" class="form-group subgrup_grup_id">
<span<?php echo $subgrup->grup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subgrup->grup_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" name="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" value="<?php echo ew_HtmlEncode($subgrup->grup_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_grup_id" class="form-group subgrup_grup_id">
<input type="text" data-table="subgrup" data-field="x_grup_id" name="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" id="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" size="30" placeholder="<?php echo ew_HtmlEncode($subgrup->grup_id->getPlaceHolder()) ?>" value="<?php echo $subgrup->grup_id->EditValue ?>"<?php echo $subgrup->grup_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="subgrup" data-field="x_grup_id" name="o<?php echo $subgrup_grid->RowIndex ?>_grup_id" id="o<?php echo $subgrup_grid->RowIndex ?>_grup_id" value="<?php echo ew_HtmlEncode($subgrup->grup_id->OldValue) ?>">
<?php } ?>
<?php if ($subgrup->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($subgrup->grup_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_grup_id" class="form-group subgrup_grup_id">
<span<?php echo $subgrup->grup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subgrup->grup_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" name="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" value="<?php echo ew_HtmlEncode($subgrup->grup_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_grup_id" class="form-group subgrup_grup_id">
<input type="text" data-table="subgrup" data-field="x_grup_id" name="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" id="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" size="30" placeholder="<?php echo ew_HtmlEncode($subgrup->grup_id->getPlaceHolder()) ?>" value="<?php echo $subgrup->grup_id->EditValue ?>"<?php echo $subgrup->grup_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($subgrup->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_grup_id" class="subgrup_grup_id">
<span<?php echo $subgrup->grup_id->ViewAttributes() ?>>
<?php echo $subgrup->grup_id->ListViewValue() ?></span>
</span>
<?php if ($subgrup->CurrentAction <> "F") { ?>
<input type="hidden" data-table="subgrup" data-field="x_grup_id" name="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" id="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" value="<?php echo ew_HtmlEncode($subgrup->grup_id->FormValue) ?>">
<input type="hidden" data-table="subgrup" data-field="x_grup_id" name="o<?php echo $subgrup_grid->RowIndex ?>_grup_id" id="o<?php echo $subgrup_grid->RowIndex ?>_grup_id" value="<?php echo ew_HtmlEncode($subgrup->grup_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="subgrup" data-field="x_grup_id" name="fsubgrupgrid$x<?php echo $subgrup_grid->RowIndex ?>_grup_id" id="fsubgrupgrid$x<?php echo $subgrup_grid->RowIndex ?>_grup_id" value="<?php echo ew_HtmlEncode($subgrup->grup_id->FormValue) ?>">
<input type="hidden" data-table="subgrup" data-field="x_grup_id" name="fsubgrupgrid$o<?php echo $subgrup_grid->RowIndex ?>_grup_id" id="fsubgrupgrid$o<?php echo $subgrup_grid->RowIndex ?>_grup_id" value="<?php echo ew_HtmlEncode($subgrup->grup_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subgrup->kode->Visible) { // kode ?>
		<td data-name="kode"<?php echo $subgrup->kode->CellAttributes() ?>>
<?php if ($subgrup->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_kode" class="form-group subgrup_kode">
<input type="text" data-table="subgrup" data-field="x_kode" name="x<?php echo $subgrup_grid->RowIndex ?>_kode" id="x<?php echo $subgrup_grid->RowIndex ?>_kode" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subgrup->kode->getPlaceHolder()) ?>" value="<?php echo $subgrup->kode->EditValue ?>"<?php echo $subgrup->kode->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subgrup" data-field="x_kode" name="o<?php echo $subgrup_grid->RowIndex ?>_kode" id="o<?php echo $subgrup_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($subgrup->kode->OldValue) ?>">
<?php } ?>
<?php if ($subgrup->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_kode" class="form-group subgrup_kode">
<input type="text" data-table="subgrup" data-field="x_kode" name="x<?php echo $subgrup_grid->RowIndex ?>_kode" id="x<?php echo $subgrup_grid->RowIndex ?>_kode" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subgrup->kode->getPlaceHolder()) ?>" value="<?php echo $subgrup->kode->EditValue ?>"<?php echo $subgrup->kode->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subgrup->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_kode" class="subgrup_kode">
<span<?php echo $subgrup->kode->ViewAttributes() ?>>
<?php echo $subgrup->kode->ListViewValue() ?></span>
</span>
<?php if ($subgrup->CurrentAction <> "F") { ?>
<input type="hidden" data-table="subgrup" data-field="x_kode" name="x<?php echo $subgrup_grid->RowIndex ?>_kode" id="x<?php echo $subgrup_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($subgrup->kode->FormValue) ?>">
<input type="hidden" data-table="subgrup" data-field="x_kode" name="o<?php echo $subgrup_grid->RowIndex ?>_kode" id="o<?php echo $subgrup_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($subgrup->kode->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="subgrup" data-field="x_kode" name="fsubgrupgrid$x<?php echo $subgrup_grid->RowIndex ?>_kode" id="fsubgrupgrid$x<?php echo $subgrup_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($subgrup->kode->FormValue) ?>">
<input type="hidden" data-table="subgrup" data-field="x_kode" name="fsubgrupgrid$o<?php echo $subgrup_grid->RowIndex ?>_kode" id="fsubgrupgrid$o<?php echo $subgrup_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($subgrup->kode->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subgrup->nama->Visible) { // nama ?>
		<td data-name="nama"<?php echo $subgrup->nama->CellAttributes() ?>>
<?php if ($subgrup->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_nama" class="form-group subgrup_nama">
<input type="text" data-table="subgrup" data-field="x_nama" name="x<?php echo $subgrup_grid->RowIndex ?>_nama" id="x<?php echo $subgrup_grid->RowIndex ?>_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subgrup->nama->getPlaceHolder()) ?>" value="<?php echo $subgrup->nama->EditValue ?>"<?php echo $subgrup->nama->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subgrup" data-field="x_nama" name="o<?php echo $subgrup_grid->RowIndex ?>_nama" id="o<?php echo $subgrup_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($subgrup->nama->OldValue) ?>">
<?php } ?>
<?php if ($subgrup->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_nama" class="form-group subgrup_nama">
<input type="text" data-table="subgrup" data-field="x_nama" name="x<?php echo $subgrup_grid->RowIndex ?>_nama" id="x<?php echo $subgrup_grid->RowIndex ?>_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subgrup->nama->getPlaceHolder()) ?>" value="<?php echo $subgrup->nama->EditValue ?>"<?php echo $subgrup->nama->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subgrup->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subgrup_grid->RowCnt ?>_subgrup_nama" class="subgrup_nama">
<span<?php echo $subgrup->nama->ViewAttributes() ?>>
<?php echo $subgrup->nama->ListViewValue() ?></span>
</span>
<?php if ($subgrup->CurrentAction <> "F") { ?>
<input type="hidden" data-table="subgrup" data-field="x_nama" name="x<?php echo $subgrup_grid->RowIndex ?>_nama" id="x<?php echo $subgrup_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($subgrup->nama->FormValue) ?>">
<input type="hidden" data-table="subgrup" data-field="x_nama" name="o<?php echo $subgrup_grid->RowIndex ?>_nama" id="o<?php echo $subgrup_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($subgrup->nama->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="subgrup" data-field="x_nama" name="fsubgrupgrid$x<?php echo $subgrup_grid->RowIndex ?>_nama" id="fsubgrupgrid$x<?php echo $subgrup_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($subgrup->nama->FormValue) ?>">
<input type="hidden" data-table="subgrup" data-field="x_nama" name="fsubgrupgrid$o<?php echo $subgrup_grid->RowIndex ?>_nama" id="fsubgrupgrid$o<?php echo $subgrup_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($subgrup->nama->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$subgrup_grid->ListOptions->Render("body", "right", $subgrup_grid->RowCnt);
?>
	</tr>
<?php if ($subgrup->RowType == EW_ROWTYPE_ADD || $subgrup->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsubgrupgrid.UpdateOpts(<?php echo $subgrup_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($subgrup->CurrentAction <> "gridadd" || $subgrup->CurrentMode == "copy")
		if (!$subgrup_grid->Recordset->EOF) $subgrup_grid->Recordset->MoveNext();
}
?>
<?php
	if ($subgrup->CurrentMode == "add" || $subgrup->CurrentMode == "copy" || $subgrup->CurrentMode == "edit") {
		$subgrup_grid->RowIndex = '$rowindex$';
		$subgrup_grid->LoadDefaultValues();

		// Set row properties
		$subgrup->ResetAttrs();
		$subgrup->RowAttrs = array_merge($subgrup->RowAttrs, array('data-rowindex'=>$subgrup_grid->RowIndex, 'id'=>'r0_subgrup', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($subgrup->RowAttrs["class"], "ewTemplate");
		$subgrup->RowType = EW_ROWTYPE_ADD;

		// Render row
		$subgrup_grid->RenderRow();

		// Render list options
		$subgrup_grid->RenderListOptions();
		$subgrup_grid->StartRowCnt = 0;
?>
	<tr<?php echo $subgrup->RowAttributes() ?>>
<?php

// Render list options (body, left)
$subgrup_grid->ListOptions->Render("body", "left", $subgrup_grid->RowIndex);
?>
	<?php if ($subgrup->id->Visible) { // id ?>
		<td data-name="id">
<?php if ($subgrup->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_subgrup_id" class="form-group subgrup_id">
<span<?php echo $subgrup->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subgrup->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subgrup" data-field="x_id" name="x<?php echo $subgrup_grid->RowIndex ?>_id" id="x<?php echo $subgrup_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subgrup->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subgrup" data-field="x_id" name="o<?php echo $subgrup_grid->RowIndex ?>_id" id="o<?php echo $subgrup_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($subgrup->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subgrup->grup_id->Visible) { // grup_id ?>
		<td data-name="grup_id">
<?php if ($subgrup->CurrentAction <> "F") { ?>
<?php if ($subgrup->grup_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_subgrup_grup_id" class="form-group subgrup_grup_id">
<span<?php echo $subgrup->grup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subgrup->grup_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" name="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" value="<?php echo ew_HtmlEncode($subgrup->grup_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_subgrup_grup_id" class="form-group subgrup_grup_id">
<input type="text" data-table="subgrup" data-field="x_grup_id" name="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" id="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" size="30" placeholder="<?php echo ew_HtmlEncode($subgrup->grup_id->getPlaceHolder()) ?>" value="<?php echo $subgrup->grup_id->EditValue ?>"<?php echo $subgrup->grup_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_subgrup_grup_id" class="form-group subgrup_grup_id">
<span<?php echo $subgrup->grup_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subgrup->grup_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subgrup" data-field="x_grup_id" name="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" id="x<?php echo $subgrup_grid->RowIndex ?>_grup_id" value="<?php echo ew_HtmlEncode($subgrup->grup_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subgrup" data-field="x_grup_id" name="o<?php echo $subgrup_grid->RowIndex ?>_grup_id" id="o<?php echo $subgrup_grid->RowIndex ?>_grup_id" value="<?php echo ew_HtmlEncode($subgrup->grup_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subgrup->kode->Visible) { // kode ?>
		<td data-name="kode">
<?php if ($subgrup->CurrentAction <> "F") { ?>
<span id="el$rowindex$_subgrup_kode" class="form-group subgrup_kode">
<input type="text" data-table="subgrup" data-field="x_kode" name="x<?php echo $subgrup_grid->RowIndex ?>_kode" id="x<?php echo $subgrup_grid->RowIndex ?>_kode" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subgrup->kode->getPlaceHolder()) ?>" value="<?php echo $subgrup->kode->EditValue ?>"<?php echo $subgrup->kode->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_subgrup_kode" class="form-group subgrup_kode">
<span<?php echo $subgrup->kode->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subgrup->kode->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subgrup" data-field="x_kode" name="x<?php echo $subgrup_grid->RowIndex ?>_kode" id="x<?php echo $subgrup_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($subgrup->kode->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subgrup" data-field="x_kode" name="o<?php echo $subgrup_grid->RowIndex ?>_kode" id="o<?php echo $subgrup_grid->RowIndex ?>_kode" value="<?php echo ew_HtmlEncode($subgrup->kode->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subgrup->nama->Visible) { // nama ?>
		<td data-name="nama">
<?php if ($subgrup->CurrentAction <> "F") { ?>
<span id="el$rowindex$_subgrup_nama" class="form-group subgrup_nama">
<input type="text" data-table="subgrup" data-field="x_nama" name="x<?php echo $subgrup_grid->RowIndex ?>_nama" id="x<?php echo $subgrup_grid->RowIndex ?>_nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subgrup->nama->getPlaceHolder()) ?>" value="<?php echo $subgrup->nama->EditValue ?>"<?php echo $subgrup->nama->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_subgrup_nama" class="form-group subgrup_nama">
<span<?php echo $subgrup->nama->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subgrup->nama->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="subgrup" data-field="x_nama" name="x<?php echo $subgrup_grid->RowIndex ?>_nama" id="x<?php echo $subgrup_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($subgrup->nama->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="subgrup" data-field="x_nama" name="o<?php echo $subgrup_grid->RowIndex ?>_nama" id="o<?php echo $subgrup_grid->RowIndex ?>_nama" value="<?php echo ew_HtmlEncode($subgrup->nama->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$subgrup_grid->ListOptions->Render("body", "right", $subgrup_grid->RowCnt);
?>
<script type="text/javascript">
fsubgrupgrid.UpdateOpts(<?php echo $subgrup_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($subgrup->CurrentMode == "add" || $subgrup->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $subgrup_grid->FormKeyCountName ?>" id="<?php echo $subgrup_grid->FormKeyCountName ?>" value="<?php echo $subgrup_grid->KeyCount ?>">
<?php echo $subgrup_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($subgrup->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $subgrup_grid->FormKeyCountName ?>" id="<?php echo $subgrup_grid->FormKeyCountName ?>" value="<?php echo $subgrup_grid->KeyCount ?>">
<?php echo $subgrup_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($subgrup->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsubgrupgrid">
</div>
<?php

// Close recordset
if ($subgrup_grid->Recordset)
	$subgrup_grid->Recordset->Close();
?>
<?php if ($subgrup_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($subgrup_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($subgrup_grid->TotalRecs == 0 && $subgrup->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($subgrup_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($subgrup->Export == "") { ?>
<script type="text/javascript">
fsubgrupgrid.Init();
</script>
<?php } ?>
<?php
$subgrup_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$subgrup_grid->Page_Terminate();
?>
