(function($) {
	$.fn.mx_lonestar = function(options) {
		var defaults = {
			climit: 1,
			rlimit: 1
		};
		var options = $.extend(defaults, options);
		return this.each(function() {
			var originalCheckBox = $(this);
			var checkBox = $('<span>', {
				class: 'mxCheckBox ' + (this.checked ? 'checked' : ''),
				html: ''
			});
			checkBox.insertAfter(originalCheckBox.hide());
			checkBox.click(function() {
				if (rlimit(originalCheckBox, originalCheckBox.data("rlimit")) && climit(originalCheckBox, originalCheckBox.data("climit"))) {
					checkBox.toggleClass('checked');
					var isChecked = checkBox.hasClass('checked');
					originalCheckBox.attr('checked', isChecked);
				}
			});
			originalCheckBox.bind('change', function() {
				checkBox.click();
			});
		}); // in column


		function climit(obj, limit) {
			if (limit > 0) {
				var td = obj.parents("td:first");
				var tr = obj.parents("tr:first");
				for (var i = 0; i < tr.children().length; i++) {
					if (tr.children().get(i) == td[0]) {
						var column = i + 1;
						break;
					}
				}
				chk_count = (obj.attr('checked')) ? -1 : 0;
				var rows = obj.parents("table:first").find(" tr:gt(0)");
				rows.children("td:nth-child(" + column + ")").each(function() {
					checkBox = $(this).find("span:first");
					if (limit == 1) {
						originalCheckBox = $(this).find("input:checkbox:first");
						if (originalCheckBox[0] != obj[0]) {
							checkBox.removeClass('checked');
							var isChecked = false;
							originalCheckBox.attr('checked', isChecked);
						}
					}
					chk_count = (checkBox.hasClass('checked')) ? chk_count + 1 : chk_count;
				});
				if ((limit >= 1 && limit > chk_count)) {
					return true;
				} else {
					return false;
				}
			}
			return true;
		} // in row


		function rlimit(obj, limit) {
			if (limit > 0) {
				var td = obj.parents("td:first");
				var tr = obj.parents("tr:first");
				var tbody = obj.parents("tr:first");
				for (var i = 0; i < tr.children().length; i++) {
					if (tr.children().get(i) == td[0]) {
						var column = i + 1;
						break;
					}
				}
				chk_count = (obj.attr('checked')) ? -1 : 0;
				var rows = obj.parents("tr:first").find("td");
			
				rows.find(".lonestar").each(function() {
					checkBox = $(this).next("span:first");
					if (limit == 1) {
						originalCheckBox = $(this);
						if (originalCheckBox[0] != obj[0]) {
							checkBox.removeClass('checked');
							var isChecked = false;
							originalCheckBox.attr('checked', isChecked);
						}
					}
					chk_count = (checkBox.hasClass('checked')) ? chk_count + 1 : chk_count;
				});


				if ((limit >= 1 && limit > chk_count)) {
					return true;
				} else {
					return false;
				}
			}
			return true;
		}
	};
})(jQuery);