Math.randomFromRange = function(min, max) {
	return Math.floor(Math.random() * Math.abs(max - min)) + min;
}

$(function() {
	(function() {
		var prevMargin = 0;
		$('ul.random li').each(function() {
			var $this = $(this), maxWidth = $this.width(), 
				linkWidth = $this.find('a').width(), MAX_DEVIATION = maxWidth / 2,
				min = (prevMargin > MAX_DEVIATION)?prevMargin - MAX_DEVIATION:0,
				max = (prevMargin < maxWidth - linkWidth - MAX_DEVIATION)?prevMargin + MAX_DEVIATION:maxWidth - linkWidth;
			$this.css('margin-left', prevMargin = Math.randomFromRange(min, max));
		});
	})();

	$('#catalog')
		.scrollable({
			items : '.images',
			circular:true,
			mousewheel:true
		})
		.find('.arrow').click(function(e) { e.preventDefault(); });
});