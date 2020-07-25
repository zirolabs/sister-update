<?php foreach($kelas as $key => $c): ?>
	<div class="checkbox">
	    <label>
	      	<input id="kelas" type="checkbox" value="<?=$key?>" name="kelas[]"> <?=$c?>
	    </label>
	</div>
<?php endforeach; ?>