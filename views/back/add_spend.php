<?php ob_start() ; ?>
<link rel="stylesheet" href="/assets/css/add_spend.css">
<?php $add_spendcss = ob_get_clean() ; ?>

<?php ob_start() ; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>

<section>
<form action="/add_spend" method="POST">
	    <p><input type="text" name="title" placeholder="Title"></p>
	    <p><input type="text" name="description" placeholder="Description"></p>
	    <p><input type="number" name="price" placeholder="Price" min="0"></p>
	    <p><input type="date" name="date" placeholder="Date"></p>
	    <p><?php 
	    	$userId =1;
	    	foreach ($datas as $data) :
	    		$nameUser = htmlentities($data['name']);?>
	    	<p><input class="checkbox" id="checkbox" type="checkbox" name="name[]" value="<?php echo intval($data['user_id']) ?>">
	    	<?php echo $nameUser;
	    	$userId++; ?></p>
	    	<?php endforeach ?>
	    </p>
	    <p><input type="submit" value="Ajouter"></p>
	</form>
</section>

<?php $content = ob_get_clean() ; ?>

<?php include __DIR__ . '/../layouts/master.php' ?>