<?php
require_once("DBConnection.php");
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `product_list` where product_id = '{$_GET['id']}'");
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="product-form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($product_id) ? $product_id : '' ?>">
        <div class="col-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="product_code" class="control-label">Code</label>
                        <input type="text" name="product_code" autofocus id="product_code" required class="form-control form-control-sm rounded-0" value="<?php echo isset($product_code) ? $product_code : '' ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="category_id" class="control-label">Category</label>
                        <select name="category_id" id="categry_id" class="form-select form-select-sm rounded-0 select2" required>
                            <option value="" <?php echo (!isset($category_id)) ? 'selected' : '' ?> disabled>Please Select Here</option>
                            <?php
                            $cat_qry = $conn->query("SELECT * FROM category_list where `status` = 1 and `delete_flag` = 0  order by `name` asc");
                            while($row= $cat_qry->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['category_id'] ?>" <?php echo (isset($category_id) && $category_id == $row['category_id'] ) ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name" class="control-label">Name</label>
                        <input type="text" name="name" id="name" required class="form-control form-control-sm rounded-0" value="<?php echo isset($name) ? $name : '' ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="price" class="control-label">Price</label>
                        <input type="number" step="any" name="price" id="price" required class="form-control form-control-sm rounded-0 text-end" value="<?php echo isset($price) ? $price : '' ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="image" class="control-label">Image</label>
                        <?php if(isset($image) && !empty($image)): ?>
                            <div class="mb-2">
                                <img src="images/products/<?php echo $image ?>" alt="Product Image" class="img-thumbnail" style="max-height: 100px;">
                                <input type="hidden" name="current_image" value="<?php echo $image ?>">
                            </div>
                            <input type="file" name="image" id="image" class="form-control form-control-sm rounded-0" accept="image/*">
                            <small class="text-muted">Leave this blank if you don't want to change the image</small>
                        <?php else: ?>
                            <input type="file" name="image" id="image" required class="form-control form-control-sm rounded-0" accept="image/*">
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-3">
                        <label for="alert_restock" class="control-label">QTY Alert for Restock</label>
                        <input type="number" step="any" name="alert_restock" id="alert_restock" required class="form-control form-control-sm rounded-0 text-end" value="<?php echo isset($alert_restock) ? $alert_restock : '' ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="description" class="control-label">Description</label>
                        <textarea name="description" id="description" cols="30" rows="3" class="form-control rounded-0" required><?php echo isset($description) ? $description : '' ?></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="status" class="control-label">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm rounded-0" required>
                            <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Popular</option>
                            <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Unpopular</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#product-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove();
            var _this = $(this);
            var _el = $('<div>');
            _el.addClass('pop_msg');
            $('#uni_modal button').attr('disabled',true);
            $('#uni_modal button[type="submit"]').text('submitting form...');
            
            // For debugging
            console.log("Form submission started");
            
            $.ajax({
                url:'./Actions.php?a=save_product',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:function(xhr, status, error){
                    console.log("Error details:", xhr, status, error);
                    _el.addClass('alert alert-danger');
                    _el.html("An error occurred: <br>" + error + "<br>Status: " + xhr.status);
                    _this.prepend(_el);
                    _el.show('slow');
                    $('#uni_modal button').attr('disabled',false);
                    $('#uni_modal button[type="submit"]').text('Save');
                },
                success:function(resp){
                    console.log("Response:", resp);
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success');
                        $('#uni_modal').on('hide.bs.modal',function(){
                            location.reload();
                        });
                        if("<?php echo isset($product_id) ?>" != 1){
                            _this.get(0).reset();
                            $('.select2').val('').trigger('change');
                        }
                    }else{
                        _el.addClass('alert alert-danger');
                    }
                    _el.text(resp.msg);
                    _el.hide();
                    _this.prepend(_el);
                    _el.show('slow');
                    $('#uni_modal button').attr('disabled',false);
                    $('#uni_modal button[type="submit"]').text('Save');
                }
            });
        });
    });
</script>