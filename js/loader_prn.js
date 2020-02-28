//global vars.
console.log('Fetching Prn Data');

function fixTable() {
	var table = $('#example').DataTable({
		"scrollX": true
	});

	$('a.toggle-vis').on('click', function (e) {
		e.preventDefault();

		// Get the column API object
		var column = table.column($(this).attr('data-column'));

		// Toggle the visibility
		column.visible(!column.visible());
	});
};

function getCSVData() {
	var csv_value = $('#example').table2CSV({
		delivery: 'value'
	});
	$("#csv_text").val(csv_value);
}

let search = window.location.search;

fetch(prn_url + search)
	.then(response => response.text())
	.then((response) => {
		//console.log(response)
		document.getElementById("Loader").innerHTML = response;
		fixTable();
	})
	.catch(err => console.log(err))