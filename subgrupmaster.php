<?php

// grup_id
// kode
// nama

?>
<?php if ($subgrup->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $subgrup->TableCaption() ?></h4> -->
<table id="tbl_subgrupmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $subgrup->TableCustomInnerHtml ?>
	<tbody>
<?php if ($subgrup->grup_id->Visible) { // grup_id ?>
		<tr id="r_grup_id">
			<td><?php echo $subgrup->grup_id->FldCaption() ?></td>
			<td<?php echo $subgrup->grup_id->CellAttributes() ?>>
<span id="el_subgrup_grup_id">
<span<?php echo $subgrup->grup_id->ViewAttributes() ?>>
<?php echo $subgrup->grup_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subgrup->kode->Visible) { // kode ?>
		<tr id="r_kode">
			<td><?php echo $subgrup->kode->FldCaption() ?></td>
			<td<?php echo $subgrup->kode->CellAttributes() ?>>
<span id="el_subgrup_kode">
<span<?php echo $subgrup->kode->ViewAttributes() ?>>
<?php echo $subgrup->kode->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subgrup->nama->Visible) { // nama ?>
		<tr id="r_nama">
			<td><?php echo $subgrup->nama->FldCaption() ?></td>
			<td<?php echo $subgrup->nama->CellAttributes() ?>>
<span id="el_subgrup_nama">
<span<?php echo $subgrup->nama->ViewAttributes() ?>>
<?php echo $subgrup->nama->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
