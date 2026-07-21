<div class="content py-3">
  <div class="card rounded-0 shadow">
    <div class="card-body">
      <h3>Welcome to Bakery Management System</h3>
      <hr>
      <div class="row row-cols-1 row-cols-md-3 g-3">
      <div class="col">
  <a href="./?page=maintenance" style="text-decoration: none; color: inherit;">
    <div class="card text-dark">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="col-auto pe-1">
            <span class="fa fa-th-list fs-3 text-primary"></span>
          </div>
          <div class="col-auto flex-grow-1">
            <div class="fs-5" style="position: relative; top: 10px; right: -10px;"><b>Categories</b></div>
            <div class="fs-6 text-end fw-bold" style="position: relative; top: -17px;">
              <?php 
              $category = $conn->query("SELECT count(category_id) as `count` FROM `category_list` where delete_flag = 0")->fetch_array()['count'];
              echo $category > 0 ? format_num($category) : 0;
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </a>
</div>

        <div class="col">
        <a href="./?page=products" style="text-decoration: none; color: inherit;">
          <div class="card text-dark">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="col-auto pe-1">
                  <span class="fas fa-shopping-bag fs-3 text-secondary"></span>
                </div>
                <div class="col-auto flex-grow-1">
                  <div class="fs-5" style="position: relative;top: 10px;right: -10px;"><b>Products</b></div>
                  <div class="fs-6 text-end fw-bold" style="position: relative;top: -17px;">
                    <?php 
                    $product = $conn->query("SELECT count(product_id) as `count` FROM `product_list` where delete_flag = 0 ")->fetch_array()['count'];
                    echo $product > 0 ? format_num($product) : 0 ;
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
        </div>
        <div class="col">
        <a href="./?page=sales_report" style="text-decoration: none; color: inherit;">
          <div class="card text-dark">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="col-auto pe-1">
                  <span class="fa fa-coins fs-3 text-warning"></span>
                </div>
                <div class="col-auto flex-grow-1">
                  <div class="fs-5" style="position: relative;top: 10px;right: -10px;"><b>Today's Sales</b></div>
                  <div class="fs-6 text-end fw-bold" style="position: relative;top: -17px;">
                    <?php 
                    $sales = $conn->query("SELECT sum(total) as `total` FROM `transaction_list` WHERE date(date_added) = date(CURRENT_TIMESTAMP)")->fetch_array()[0];
                    echo $sales > 0 ? format_num($sales) : 0 ;
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
        </div>

     </div>

   
    <hr>
    <div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <h3 class="text-left">Stock Available</h3>
      <hr>
      <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="inventory">
          <colgroup>
            <col width="25%">
            <col width="25%">
            <col width="10%">
            <col width="25%">
            <col width="15%">
          </colgroup>
          <thead class="table-dark">
            <tr>
              <th class="py-2 px-1">Category</th>
              <th class="py-2 px-1">Code</th>
              <th class="py-2 px-1">Image</th>
              <th class="py-2 px-1">Name</th>
              <th class="py-2 px-1 text-left">Quantity</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT p.*,c.name as cname FROM `product_list` p inner join `category_list` c on p.category_id = c.category_id where p.delete_flag = 0 order by `name` asc";
            $qry = $conn->query($sql);
            while($row = $qry->fetch_assoc()):
              $stock_in = $conn->query("SELECT sum(quantity) as `total` FROM `stock_list` where unix_timestamp(CONCAT(`expiry_date`, ' 23:59:59')) >= unix_timestamp(CURRENT_TIMESTAMP) and product_id = '{$row['product_id']}' ")->fetch_array()['total'];
              $stock_out = $conn->query("SELECT sum(quantity) as `total` FROM `transaction_items` where product_id = '{$row['product_id']}' ")->fetch_array()['total'];
              $stock_in = $stock_in > 0 ? $stock_in : 0;
              $stock_out = $stock_out > 0 ? $stock_out : 0;
              $qty = $stock_in - $stock_out;
              $qty = $qty > 0 ? $qty : 0;
            ?>
            <tr class="<?php echo $qty < 50? "bg-opacity-25":'' ?>">
              <td class="py-2 px-1"><?php echo $row['cname'] ?></td>
              <td class="py-2 px-1"><?php echo $row['product_code'] ?></td>
              <td class="py-2 px-1">
                <?php
                  $image_filename = $row['image'];
                  $image_path = '/images/products/' . $image_filename; 
                  $image_url = 'http://localhost/bakery/' . $image_path; 
                ?>
                <img src="<?php echo $image_url; ?>" alt="Image" style="height: 50px; width:50px">
              </td>
              <td class="py-2 px-1"><?php echo $row['name'] ?></td>
              <td class="py-2 px-1 text-end">
                <?php echo number_format($row['alert_restock']) ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
       

        </table>
      </div>
    </div>
  </div>
</div>


<script>
    $(function(){
        $('.restock').click(function(){
            uni_modal('Add New Stock for <span class="text-primary">'+$(this).attr('data-name')+"</span>","manage_stock.php?pid="+$(this).attr('data-pid'))
        })
        $('table#inventory').dataTable()

    })
</script>