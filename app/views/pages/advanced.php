<script>
$(document).ready(function() {
    $('.js-matcher').select2();
});
</script>
<?php
if(empty($_GET['step'])) { $_GET['step'] = 1; } 
$index_genres = $genres['biggestID'];
$index_features =  $features['biggestID'];
?>
<div class="wrapper wrapper700">
<center><h1>Random Game Generator</h1></center>
<hr class="hr-style"/>
<center><h2><?php echo $_GET['step']?>/3</h2></center>
<div class="buttonlike">
<form method="POST">

<?php if($_GET['step'] == 1) { ?>
<div class="checkboxes">
<h2>Features</h2>
    <div class="pretty p-smooth p-default p-curve">
	<input type="checkbox" name="form[dlc]"    value="1" />
        <div class="state">
            <label>DLC</label>
        </div>
    </div>
    <br/>
    <br/>
        <div class="pretty p-smooth p-default p-curve">
	<input type="checkbox" name="form[coming_soon]"   value="1" />        
	    <div class="state">
            <label>Coming Soon</label>
        </div>
    </div>
</div>
<div class="inputs">
<h2>Price - Euro</h2>

 <label><input type="number" name="form[min_price]"   value="0" /> &nbsp; MIN</label>
<br/>
<br/>
 <label><input type="number" name="form[max_price]"   value="0" /> &nbsp; MAX</label>

</div>
<?php } if($_GET['step'] == 2) {?>
</br></br></br>
<select class="js-matcher" name="form[categories][0]">
<option value="">Nothing</option>
<?php 
for ($i = 0; $i < $index_features; $i++) {
    echo '<option value='.$features[$i]['value'].'>'.$features[$i]['value'].'</option>';
}
?>
</select>

<select class="js-matcher" name="form[categories][1]">
<option value="">Nothing</option>
<?php 
for ($i = 0; $i < $index_features; $i++) {
    echo '<option value='.$features[$i]['value'].'>'.$features[$i]['value'].'</option>';
}
?>
</select>

<select class="js-matcher" name="form[categories][2]">
<option value="">Nothing</option>
<?php 
for ($i = 0; $i < $index_features; $i++) {
    echo '<option value='.$features[$i]['value'].'>'.$features[$i]['value'].'</option>';
}
?>
</select>

<?php } if($_GET['step'] == 3) {?>
</br></br></br>

<select class="js-matcher" name="form[genres][0]">
<option value="">Nothing</option>
<?php 
for ($i = 0; $i < $index_genres; $i++) {
    echo '<option value='.$genres[$i]['value'].'>'.$genres[$i]['value'].'</option>';
}
?>
</select>
<select class="js-matcher" name="form[genres][1]">
<option value="">Nothing</option>

<?php 
for ($i = 0; $i < $index_genres; $i++) {
    echo '<option value='.$genres[$i]['value'].'>'.$genres[$i]['value'].'</option>';
}
?>

</select>
<select class="js-matcher" name="form[genres][2]">
<option value="">Nothing</option>

<?php 
for ($i = 0; $i < $index_genres; $i++) {
    echo '<option value='.$genres[$i]['value'].'>'.$genres[$i]['value'].'</option>';
}
?>

</select>
<?php } if($_GET['step'] == 4) { ?>

               
<?php } ?>

<input type="submit" name="submit" value="Next Step" class="submit"/>

</form>

</div>
</div>
<?php $run; ?>

