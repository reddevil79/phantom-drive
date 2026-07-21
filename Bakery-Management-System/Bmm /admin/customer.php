 <div class="card rounded-0 shadow">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Customers </h3>
    
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="30%">
                <col width="25%">
                <col width="25%">
                <col width="15%">
            </colgroup>
            <thead class="table-dark">
                <tr>
                    <th class="text-center p-0">No</th>
                    <th class="text-center p-0">Email</th>
                    <th class="text-center p-0">Username</th>
                    <th class="text-center p-0">Contact Number</th>
                    <th class="text-center p-0">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT * FROM `users` WHERE u_id != 1 ORDER BY `email` ASC";
                $qry = $conn->query($sql);
                $i = 1;
                while($row = $qry->fetch_assoc()):
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1"><?php echo $row['email'] ?></td>
                    <td class="py-0 px-1"><?php echo $row['username'] ?></td>
                    <td class="py-0 px-1"><?php echo $row['phone'] ?></td>
                    <th class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <li><a class="dropdown-item delete_data" data-id='<?php echo $row['u_id'] ?>' data-name='<?php echo $row['email'] ?>' href="javascript:void(0)">Delete</a></li>
                            </ul>
                        </div>
                    </th>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(function(){
        $('.delete_data').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            if(confirm("Are you sure to delete " + name + " from the list?")) {
                delete_data(id);
            }
        });
    });

    function delete_data($id){
        $.ajax({
            url:'./Actions.php?a=delete_u',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("An error occurred.")
                }
            }
        })
    }
</script>
