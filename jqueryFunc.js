$('#subpageContent').load("work.html", function () {
        alert("Load functionPage1.html OK.");
});

$('li').hover(
  function() {
    $(this).append($('<span> ***</span>'));
  }, function() {
    $(this).find('span:last').remove();
  }
);
