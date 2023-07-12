$(document).ready(function () {
    var counter = 0;

    $("#addrow").on("click", function () {
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td><input required type="text" name="prod_name[]" class="form-control" form="order_form" /></td>';
        cols += '<td><input required type="text" name="count_name[]"  class="form-control" form="order_form"/></td>';
        cols += '<td><input required type="date" name="date_name[]"  class="form-control" form="order_form"/></td>';
        cols += '<td><input required type="text" name="price_name[]"  class="form-control" form="order_form"/></td>';
        cols += '<td><input required type="text" name="sale_name[]"  class="form-control" form="order_form"/></td>';
        cols += '<td><input disabled type="text" name="total_name[]"  class="form-control" form="order_form"/></td>';


        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("table.order-list").append(newRow);
        counter++;
    });



    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
    });


});



function calculateRow(row) {
    var price = +row.find('input[name^="price"]').val();

}

function calculateGrandTotal() {
    var grandTotal = 0;
    $("table.order-list").find('input[name^="price"]').each(function () {
        grandTotal += +$(this).val();
    });
    $("#grandtotal").text(grandTotal.toFixed(2));
}



// snipping.js

// Event listener for page load event
window.addEventListener('load', function() {
    // Get the container element
    var container = document.getElementById('snipping-container');
  
    // Set the HTML of the container to the snipping GIF image
    container.innerHTML = '<img src="../images/loader.gif" alt="Loading GIF">';
  
    // After a delay, remove the snipping GIF
    setTimeout(function() {
      container.innerHTML = '';
    }, 500); // Adjust the delay (in milliseconds) as needed
  });
