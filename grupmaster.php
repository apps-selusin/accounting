<?php

// name
?>
<?php if ($grup->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $grup->TableCaption() ?></h4> -->
<table id="tbl_grupmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $grup->TableCustomInnerHtml ?>
	<tbody>
<?php if ($grup->name->Visible) { // name ?>
		<tr id="r_name">
			<td><?php echo $grup->name->FldCaption() ?></td>
			<td<?php echo $grup->name->CellAttributes() ?>>
<span id="el_grup_name">
<span<?php echo $grup->name->ViewAttributes() ?>>
<?php echo $grup->name->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
