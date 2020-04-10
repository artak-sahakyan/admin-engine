<div class="videos">
   <div class="videos__box">
   		<?php foreach ($items as $item): ?>
	    <div class="videos__cell">
	         <div class="video youtube" data-video-id="<?= $item['id'] ?>"><a href="javascript: window.open('http://www.youtube.com/embed/<?= $item['id'] ?>', '', 'width='+screen.availWidth/2+',height='+screen.availHeight/2+',top='+screen.availHeight/4+',left='+screen.availWidth/4); void(0);" class="video__img-box"><img class="video__img" src="//img.youtube.com/vi/<?= $item['id'] ?>/mqdefault.jpg"></a></div>
	      </div>
		<?php endforeach?>
   </div>
</div>
