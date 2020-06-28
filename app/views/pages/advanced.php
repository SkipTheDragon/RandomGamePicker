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

<div class="buttonlike">
    <div class="titles">
        <h2>Choose <? echo $title; ?></h2>
        <h2 style="float: right;margin-bottom: -30px;">Step <?php echo $_GET['step']?> of 3</h2>
    </div>
<form method="POST">

<?php if($_GET['step'] == 1) { ?>
<div class="block">
<h3>Game Features</h3>
    <div class="pretty p-smooth p-default p-curve">
	<input type="checkbox" name="form[dlc]"    value="1" />
        <div class="state">
            <label>DLC</label>
        </div>
    </div>
    <br/>
    <br/>
        <div class="pretty p-smooth p-default p-curve">
	<input type="checkbox" name="form[coming_soon]"  value="1" />
	    <div class="state">
            <label>Coming Soon</label>
        </div>
    </div>
    <br/>
    <br/>
    <div class="pretty p-smooth p-default p-curve">
        <input type="checkbox" name="form[demo]"  value="1" />
        <div class="state">
            <label>Demo</label>
        </div>
    </div>
    <br/>
    <br/>
    <div class="pretty p-smooth p-default p-curve">
        <input type="checkbox" name="form[dlc]"  disabled  value="1" />
        <div class="state">
            <label>Only Steam Games</label>
        </div>
    </div>
    <br/>
</div>
<div class="block">
    <h3>Price</h3>
    <input type="number" name="form[min_price]"   value="0" /> -- <input type="number" name="form[max_price]"   value="0" />
    <br/>
    <br/>
    <div class="pretty p-smooth p-default p-curve">
        <input type="checkbox" name="form[dlc]"  disabled  value="1" />
        <div class="state">
            <label>Free</label>
        </div>
    </div>
    <br/>
    <br/>
    <div class="pretty p-smooth p-default p-curve">
        <input type="checkbox" name="form[dlc]"  disabled  value="1" />
        <div class="state">
            <label>Sale</label>
        </div>
    </div>

</div>
<div class="block macigselect">
    <h3>Review Score</h3>
    <select class=" js-matcher" name="form[score]">
        <option value="0">Any Score</option>
        <option value="1">Overwhelmingly Negative</option>
        <option value="2">Very Negative</option>
        <option value="3">Negative</option>
        <option value="4">Mostly Negative</option>
        <option value="5">Mixed</option>
        <option value="6">Mostly Positive</option>
        <option value="7">Positive</option>
        <option value="8">Very Positive</option>
        <option value="9">Overwhelmingly Positive</option>
    </select>
    <br/>
    <div class="pretty p-smooth p-default p-curve">
        <input type="checkbox" name="form[dlc]"   disabled value="1" />
        <div class="state">
            <label>Lot Of Reviews</label>
        </div>
    </div>
</div>
<?php } if($_GET['step'] == 2) { ?>
</br></br>
<select class="js-matcher" name="form[categories][0]">
<option value="">Nothing</option>
<?php
for ($i = 0; $i < $index_features; $i++) {
    echo "<option value='{$features[$i]['id']}'>{$features[$i]['value']}</option>";
}
?>
</select>

<select class="js-matcher" name="form[categories][1]">
<option value="">Nothing</option>
<?php
for ($i = 0; $i < $index_features; $i++) {
    echo "<option value='{$features[$i]['id']}'>{$features[$i]['value']}</option>";
}
?>
</select>

<select class="js-matcher" name="form[categories][2]">
<option value="">Nothing</option>
<?php 
for ($i = 0; $i < $index_features; $i++) {
    echo "<option value='{$features[$i]['id']}'>{$features[$i]['value']}</option>";
}
?>
</select>

<?php } if($_GET['step'] == 3) {?>
</br></br></br>

<select class="js-matcher" name="form[genres][0]">
<option value="">Nothing</option>
<?php 
for ($i = 0; $i < $index_genres; $i++) {
    echo "<option value='{$genres[$i]['id']}'>{$genres[$i]['value']}</option>";
}
?>
</select>
<select class="js-matcher" name="form[genres][1]">
<option value="">Nothing</option>
<?php
for ($i = 0; $i < $index_genres; $i++) {
    echo "<option value='{$genres[$i]['id']}'>{$genres[$i]['value']}</option>";
}
?>

</select>
<select class="js-matcher" name="form[genres][2]">
<option value="">Nothing</option>
<?php
for ($i = 0; $i < $index_genres; $i++) {
    echo "<option value='{$genres[$i]['id']}'>{$genres[$i]['value']}</option>";
}
?>

</select>
<?php } if($_GET['step'] == 4) { ?>

               
<?php } ?>
<div class="buttonsBar">
<input type="submit" name="submit" value="Next Step" class="submit"/>
</div>
</form>
</div>
</div>
<?php $run; ?>

