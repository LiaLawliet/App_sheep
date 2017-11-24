<svg width="1000px" height="300px" transform="rotate(180 0 0) scale(-1, 1)">

	<?php 
		$diffx = 20;
		$color = ["#E63E3E", "#FA97ED", "#4536D4", "#2FB6CB", "#4FC682", "#C0DC4F"];
		$i= 0;
		foreach ($depenses as $depenses):
			$pourcentage = ($depenses["price"]*100/$totalSpends)*100;
			$diffx += 80;
			$i++;
			echo "<rect x= " .$diffx. " y= '0' width='50' height=".$pourcentage." style='fill:".$color[$i]."'><animate attributeName='height' attributeType='XML' fill='freeze' from='0' to=".$pourcentage." begin='0s' dur='1.5s'/></rect>";
	?>

	<?php endforeach; ?>

</svg>