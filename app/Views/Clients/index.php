<div class="page-heading">
  <div class="page-title mb-3">
    <div class="d-lg-flex row align-items-center">
      <div class="col col-md-6 order-md-1 order-last">
        <h3 class="">Clients</h3> 
      </div>
      <div class="col-auto col-md-6 order-md-2 order-last">
        <div class="float-start float-lg-end d-lg-flex gap-2">
          <button class="btn icon icon-left btn-warning"><i class="bi bi-file-earmark-arrow-up-fill"></i> Export</button>
          <a class="btn icon icon-left btn-dark" href="./clients/create"><i class="bi bi-plus"></i> Add New Client</a>
        </div>
      </div>
    </div>
  </div>
  <section class="section">
    <div class="card"> 
      <div class="card-body">
        <table class="table table-striped" id="table1">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>City</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          	<?php foreach ($clients as $key => $client): ?>
        		<tr>
              <td><?php echo $client->getFullNameAttribute() ?></td>
              <td><?php echo $client->email ?></td>
              <td><?php echo $client->phone_number ?></td>
              <td><?php echo $client->address ?></td>
              <td>
                <span class="badge bg-success">Active</span>
              </td>
              <td>
                <a class="btn btn-sm btn-info" href="./clients/<?php echo $client->id ?>/edit">Edit</a>
                <form action="./clients/<?php echo $client->id ?>/delete" class="d-inline" method="POST">
                  <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                </form>
                
              </td> 
            </tr>
          	<?php endforeach ?>
            
          </tbody>
        </table>
      </div>
    </div>
  </section>
</div>