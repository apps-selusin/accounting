<?php

// tipejurnal_id
// createon
// keterangan
// nomer

?>
<?php if ($jurnal->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $jurnal->TableCaption() ?></h4> -->
<table id="tbl_jurnalmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $jurnal->TableCustomInnerHtml ?>
	<tbody>
<?php if ($jurnal->tipejurnal_id->Visible) { // tipejurnal_id ?>
		<tr id="r_tipejurnal_id">
			<td><?php echo $jurnal->tipejurnal_id->FldCaption() ?></td>
			<td<?php echo $jurnal->tipejurnal_id->CellAttributes() ?>>
<span id="el_jurnal_tipejurnal_id">
<span<?php echo $jurnal->tipejurnal_id->ViewAttributes() ?>>
<?php echo $jurnal->tipejurnal_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jurnal->createon->Visible) { // createon ?>
		<tr id="r_createon">
			<td><?php echo $jurnal->createon->FldCaption() ?></td>
			<td<?php echo $jurnal->createon->CellAttributes() ?>>
<span id="el_jurnal_createon">
<span<?php echo $jurnal->createon->ViewAttributes() ?>>
<?php echo $jurnal->createon->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jurnal->keterangan->Visible) { // keterangan ?>
		<tr id="r_keterangan">
			<td><?php echo $jurnal->keterangan->FldCaption() ?></td>
			<td<?php echo $jurnal->keterangan->CellAttributes() ?>>
<span id="el_jurnal_keterangan">
<span<?php echo $jurnal->keterangan->ViewAttributes() ?>>
<?php echo $jurnal->keterangan->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($jurnal->nomer->Visible) { // nomer ?>
		<tr id="r_nomer">
			<td><?php echo $jurnal->nomer->FldCaption() ?></td>
			<td<?php echo $jurnal->nomer->CellAttributes() ?>>
<span id="el_jurnal_nomer">
<span<?php echo $jurnal->nomer->ViewAttributes() ?>>
<?php echo $jurnal->nomer->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
