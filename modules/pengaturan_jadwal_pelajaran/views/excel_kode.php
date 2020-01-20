<!DOCTYPE html>
<html>
	<head>
		<title>Kode Guru dan Kelas</title>
	</head>
	<body>

		<table border="1" cellpadding="0" cellspacing="0" width="400px">
			<thead>
				<tr>
					<th colspan="2">Data Guru</th>
				</tr>
				<tr>
					<th width="20%">Kode</th>
					<th width="80%">Nama</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data_guru as $key => $c): ?>
					<tr>
						<td style="text-align: center;"><?=$c->user_id?></td>
						<td><?=$c->nama?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<table border="1" cellpadding="0" cellspacing="0" width="400px">
			<thead>
				<tr>
					<th colspan="2">Data Mata Pelajaran</th>
				</tr>
				<tr>
					<th width="20%">Kode</th>
					<th width="80%">Nama</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($data_mata_pelajaran as $key => $c): ?>
					<tr>
						<td style="text-align: center;"><?=$c->mata_pelajaran_id?></td>
						<td><?=$c->nama?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>		

	</body>
</html>