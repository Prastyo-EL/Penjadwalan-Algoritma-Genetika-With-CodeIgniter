<div id="content_modal" style="max-width:400px;margin:auto;background-color:#efefef;">

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-remove"></i></button>
	<h3>Data Waktu</h3>
</div>

<form id="myforms" class="myform" method="post" name="myform" action="#" >
<div class="modal-body" style="max-height:400px;overflow-y:auto;">
<div class="row-fluid table-responsive">

<table class="table table-bordered">
	<thead>
		<tr>
			<th>No</th>
			<th>Jam Ke-</th>
			<th>Hari</th>
			<th>Waktu</th>
			<th style="width:30px;text-align:center;">Aksi</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$no = $filter['start']+1; 
		if (!empty($data_waktu)) {
			foreach ($data_waktu as $key => $value) {
				?>
				<tr>
					<td><?=$no++?></td>
					<td><?=$value['id']?></td>
					<td><?=$value['hari']?></td>
					<td><?=$value['jam']?></td>
					<td align="center">
						<input type="radio" name="waktu" class="waktu" value="<?=$value['id']?>" id="id" style=""  >
						<input type="hidden" id="hari_<?=$value['id']?>" class="waktu" value="<?=$value['hari']?>" id="" style="">
						<input type="hidden" id="jam_<?=$value['id']?>" class="waktu" value="<?=$value['jam']?>" id="" style="">
					</td>                                       
				</tr>
				<?php 
			}
		}else{
			$filter['start'] = $filter['start'] - 1;
			?>
			<tr>
				<td colspan="7" style="text-align:center;font-style:oblique"> -- Data tidak ada --</td>
			</tr>
			<?php
		} 
		?>
	</tbody>
</table>

</div>

</div>
<div class="modal-footer">
	<input type="hidden" name="id_kelas" id="id_kelas" value="" />
	<button type="button" data-dismiss="modal" class="btn btn-round btn-default">Close</button>
	<button type="submit" class="btn btn-round btn-primary" id="submit_waktu">Pilih</button>
</div>
</form>


<div class="" style="margin:0px 20px">
  <span class="badge">Showing <?=$filter['start']+1?> to <?=($no-1)?> of <?=($jumlah_data_waktu)?> entries</span>
  <ul style="margin:0px" class="pagination pull-right"><?=$paging_waktu?></ul>
</div>

<hr>
</div>

<script type="text/javascript">
$(".pagin" ).on( "click", function() {
	urle = $(this).find("a").attr('href');
	id_kelas = $("#id_kelas").val();
	$.ajax({
		type: "POST",
		url: urle,
		data: { idkls: id_kelas }
	}).done(function( msg ) {
		$('#content_modal').html(msg);
		$modal.modal('show');
	});
	return false;
});

$("#submit_waktu" ).on( "click", function() {
	id = $('.waktu:checked').val();
	hari = $('#hari_'+id).val();
	jam = $('#jam_'+id).val();
	rowCount = $('#table_time').find('tr').index();
	rowCount++;
	var row_data = "";
	row_data += "<tr class='"+id+"'>";
	row_data += "<td>"+id+"</td>";
	row_data += "<td>"+hari+"</td>";
	row_data += "<td>"+jam+"</td>";
	row_data += "<td>";
	row_data += "<button type='button' class='btn btn-xs btn-round btn-danger remove'><i class='fa fa-trash'></i> Hapus </button>";
	row_data += "<input type='hidden' name='waktu[]' value='"+id+"' >";
	row_data += "</td>";
	row_data += "</tr>";
	$("#table_time>tbody").append(row_data);
	$('#waktu').modal('hide');
	return false;
});

</script>