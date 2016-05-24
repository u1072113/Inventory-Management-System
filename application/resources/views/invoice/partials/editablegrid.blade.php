<script>
    $(document).ready(function () {
        //Helpers
        $('select').select2({});
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function image(relativePath) {
            return "{{url()}}/images/" + relativePath;
        }

        /**************************************************************/
        //Columns
        var metadata = [
            {"name": "id", "label": "Product ID", "datatype": "string", "editable": false},
            {"name": "productName", "label": "Product", "datatype": "string", "editable": true},
            {"name": "quantity", "label": "Quantity", "datatype": "integer", "editable": true},
            {"name": "price", "label": "Price", "datatype": "double(2)", "editable": true},
            {"name": "discountPercentage", "label": "Discount %", "datatype": "double(2)", "editable": true},
            {"name": "serialNumber", "label": "SerialNumber", "datatype": "string", "editable": true},
            {"name": "taxable", "label": "Taxable (T)", "datatype": "string", "editable": true},
            {"name": "cost", "label": "Cost", "datatype": "double(2)", "editable": false},
            {"name": "action", "label": "Action", "datatype": "string", "editable": false}

        ];

        editableGrid = new EditableGrid("DemoGridJSON", {
            baseUrl: "{{url()}}"
        });
        editableGrid.load({"metadata": metadata, "data": {}});
        editableGrid.setCellRenderer("action", new CellRenderer({
            render: function (cell, value) {
                // this action will remove the row, so first find the ID of the row containing this cell
                var rowId = editableGrid.getRowId(cell.rowIndex);
                cell.innerHTML = "<a onclick=\"if (confirm('Are you sure you want to delete this Order ? ')) { editableGrid.remove(" + cell.rowIndex + "); editableGrid.renderCharts(); } \" style=\"cursor:pointer\">" +
                        "<img src=\"" + image("delete.png") + "\" border=\"0\" alt=\"delete\" title=\"Delete row\"/></a>";
            }
        }));
        //Initialize The grid and Render
        editableGrid.renderGrid("tablecontent", "table table-paper table-hover table-bordered");
        editableGrid.modelChanged = function (rowIndex, columnIndex, oldValue, newValue, row) {
            updateVals(editableGrid);
        }

        function updateVals(editableGrid) {
            console.log(editableGrid.getRowCount());
            console.log(editableGrid.getRowValues(2));
            var orders = [];
            var rowCount = editableGrid.getRowCount();
            var total = 0;
            for (i = 0; i < rowCount; i++) {
                var costColumn = editableGrid.getColumnIndex("cost");
                var price = editableGrid.getValueAt(i, editableGrid.getColumnIndex("price"));
                var quantity = editableGrid.getValueAt(i, editableGrid.getColumnIndex("quantity"));
                var cost = parseFloat(quantity) * parseFloat(price);
                editableGrid.setValueAt(i, costColumn, numberWithCommas(cost.toFixed(2)));
                orders.push(editableGrid.getRowValues(i))
                total = (parseFloat(total) + cost).toFixed(2);
            }
            $("#totalCost").val(numberWithCommas(total));
            $(".totalCost").html(numberWithCommas(total));
            console.log(orders);
            $("#invoiceDetails").val(JSON.stringify(orders));
        }

        //Add Order to Invoice List
        $("#addOrder").click(function () {
            var theID = $("#productName").select2('val');
            $.ajax({
                method: "GET",
                url: "api/getproduct/" + theID,
                beforeSend: function (xhr) {
                    $("#addOrder").text("Loading");
                    $("#addOrder").attr('disabled', 'disabled')
                }
            })
                    .done(function (msg) {
                        console.log(msg);
                        var rowCount = editableGrid.getRowCount();
                        rowCount = rowCount - 1;
                        console.log(rowCount);
                        console.log(msg.values);
                        editableGrid.insertAfter(rowCount, msg.id, msg.values);
                        updateVals(editableGrid);
                        $("#addOrder").text("Add Order");
                        $("#addOrder").removeAttr('disabled');
                    });
        });
    });
</script>