<div class="card rounded-0 shadow">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Online Orders</h3>
        <div>
            <button class="btn btn-flat btn-sm btn-primary" onclick="location.reload()"><i class="fa fa-sync"></i> Refresh</button>
        </div>
    </div>
    <div class="card-body">
        <table id="myTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>User name</th>        
                    <th>Contact Number</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Order Notes</th>
                    <th>Address</th>
                    <th>Status</th>                                                
                    <th>Ordered Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
// Modified SQL query with LEFT JOIN and error handling
$sql = "SELECT 
        o.o_id,
        o.name as product_name,
        o.quantity,
        o.price,
        o.customization,
        o.status,
        o.date,
        (o.quantity * o.price) as total_price,
        COALESCE(u.username, 'Guest User') as username,
        COALESCE(u.phone, 'N/A') as phone,
        COALESCE(u.address, 'No Address') as address
        FROM users_orders o
        LEFT JOIN users u ON u.u_id = o.u_id
        ORDER BY o.date DESC";

$orders = [];
$qry = $conn->query($sql);

if ($qry) {
    while ($row = $qry->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    // Add error logging
    error_log("Database Error: " . $conn->error);
    echo '<tr><td colspan="12"><center>Error loading orders. Please try refreshing.</center></td></tr>';
}
?><?php
if (empty($orders)) {
    echo '<tr><td colspan="12"><center>No Orders Found!</center></td></tr>';
} else {
    foreach ($orders as $order) {
?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($order['o_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                            <td><?php echo htmlspecialchars($order['phone']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td>Rs <?php echo number_format($order['price'], 2); ?></td>
                            <td>Rs <?php echo number_format($order['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['customization']); ?></td>
                            <td><?php echo htmlspecialchars($order['address']); ?></td>
                            
                            
                            <?php
                            $status = $order['status'];
                            $status_class = '';
                            $status_text = '';
                            
                            switch($status) {
                                case "":
                                case "NULL":
                                case NULL:
                                    $status_class = 'info';
                                    $status_text = 'Pending';
                                    break;
                                case "in process":
                                    $status_class = 'warning';
                                    $status_text = 'On the Way';
                                    break;
                                case "closed":
                                    $status_class = 'success';
                                    $status_text = 'Delivered';
                                    break;
                                case "rejected":
                                    $status_class = 'danger';
                                    $status_text = 'Cancelled';
                                    break;
                                default:
                                    $status_class = 'secondary';
                                    $status_text = htmlspecialchars($status);
                            }
                            ?>
                            <td>
                                <button type="button" class="btn btn-<?php echo $status_class; ?>" style="font-weight:bold;">
                                    <?php echo $status_text; ?>
                                </button>
                            </td>
                            <td><?php echo date('M d, Y h:i A', strtotime($order['date'])); ?></td>
                            <td class="text-center py-0 px-1">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li><a class="dropdown-item edit_data" data-id='<?php echo $order['o_id'] ?>' href="javascript:void(0)">Edit</a></li>
                                        <li><a class="dropdown-item delete_data" data-id='<?php echo $order['o_id'] ?>' data-name='<?php echo "#".$order['o_id']." - ".$order['product_name'] ?>' href="javascript:void(0)">Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable with enhanced configuration
        var table = $('#myTable').DataTable({
            "dom": '<"top"lf>rt<"bottom"ip><"clear">',
            "pageLength": 100, // Show 100 records by default
            "lengthMenu": [10, 25, 50, 100, 500, 1000, "All"],
            "order": [[10, 'desc']], // Sort by order date descending
            "stateSave": true, // Remember user's settings
            "processing": true,
            "language": {
                "lengthMenu": "Show _MENU_ orders per page",
                "zeroRecords": "No orders found",
                "info": "Showing _START_ to _END_ of _TOTAL_ orders",
                "infoEmpty": "No orders available",
                "infoFiltered": "(filtered from _MAX_ total orders)",
                "search": "Search all orders:"
            },
            "columnDefs": [
                { "orderable": false, "targets": [11] }, // Action column
                { "searchable": false, "targets": [11] }, // Action column
                { "type": "num", "targets": [0] }, // Order ID
                { "type": "date", "targets": [10] } // Date column
            ],
            "initComplete": function() {
                // Add custom filter for status
                this.api().columns([9]).every(function() {
                    var column = this;
                    var select = $('<select class="form-control form-control-sm"><option value="">All Statuses</option></select>')
                        .appendTo($(column.header()))
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^'+val+'$' : '', true, false).draw();
                        });
                    
                    column.data().unique().sort().each(function(d, j) {
                        var statusText = '';
                        switch(d) {
                            case "":
                            case "NULL":
                            case null: statusText = 'Pending'; break;
                            case "in process": statusText = 'On the Way'; break;
                            case "closed": statusText = 'Delivered'; break;
                            case "rejected": statusText = 'Cancelled'; break;
                            default: statusText = d;
                        }
                        select.append('<option value="'+d+'">'+statusText+'</option>');
                    });
                });
            }
        });
        
        // Edit button handler
        $('body').on('click', '.edit_data', function(){
            uni_modal('Edit Order Details', "manage_orders.php?id="+$(this).attr('data-id'), 'mid-large');
        });
        
        // Delete button handler
        $('body').on('click', '.delete_data', function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from order list?", 'delete_data', [$(this).attr('data-id')]);
        });
    });
    
    function delete_data($id){
        $('#confirm_modal button').attr('disabled', true);
        $.ajax({
            url: './Actions.php?a=delete_orders',
            method: 'POST',
            data: {id: $id},
            dataType: 'JSON',
            error: function(err){
                console.log(err);
                alert("An error occurred while deleting the order.");
                $('#confirm_modal button').attr('disabled', false);
            },
            success: function(resp){
                if(resp.status == 'success') {
                    location.reload();
                } else {
                    alert(resp.msg || "Failed to delete order.");
                    $('#confirm_modal button').attr('disabled', false);
                }
            }
        });
    }
</script>